<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Models\ClientCommunication;
use App\Models\ClientCommunicationCategory;

class ClientCommController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        if ($request->ajax()) {
            $data = ClientCommunication::with('category')->orderBy('position', 'asc')->get();
            foreach($data as $val){
                
                if(empty($val->slug)){
                    $val->slug = self::makeSlug($val->question);
                    $val->save();
                }
            }
            return Datatables::of($data)
                ->addIndexColumn()
                
                ->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('client-communication-client-communication-items', 'copy')){
                    $btn = '<div id="copy_url_'.$row->id.'" style="font-size:0px;">'.route('frontend.client-communication-details', $row->slug).'</div>';
                    }
                    if(is_permitted('client-communication-client-communication-items', 'edit')){
                    $btn .= '<a href="'.route('webadmin.clientCommunicationEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('client-communication-client-communication-items', 'delete')){
                    $btn .= '<a href="'.route('webadmin.clientCommunicationDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    if(is_permitted('client-communication-client-communication-items', 'copy')){
                    $btn .= '<a href="javascript:void(0);" onclick="copyFuntion(\'copy_url_'.$row->id.'\')"  class="edit btn btn-success btn-sm ml-1">Copy Link</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['answer','category','status','action'])
                ->make(true);
        }
        return view('admin.client_communication.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add(){
        //dd("ok");
        $data['categories'] = ClientCommunicationCategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.client_communication.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request){
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'slug' => self::makeSlug($input['question']),
            'category_id' => $input['category'],
            'answer' => $input['answer'],
            'is_active' => isset($input['status'])?1:0
        ];

        
        $res = ClientCommunication::create($saveData);
        if ($res){
            toastr()->success('successfully saved.');
            return redirect()->route('webadmin.clientCommunication');
        }

        return redirect()->back()->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\sales_presenter_pdf  $sales_presenter_pdf
     * @return \Illuminate\Http\Response
     */
    public function edit($id){
        $data['sales_presenter_pdf'] = ClientCommunication::where('id',$id)->first();
        $data['categories'] = ClientCommunicationCategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.client_communication.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'question' => 'required',
        ]);

        $previousSamplereport = ClientCommunication::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.clientCommunication');
        }

        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'slug' => self::makeSlug($input['question']),
            'answer' => $input['answer'],
            'category_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousSamplereport->update($saveData);
        if ($res){
            toastr()->success('successfully saved.');
            return redirect()->route('webadmin.clientCommunication');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousSamplereport = ClientCommunication::where('id',$id)->first();
        if (!$previousSamplereport){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.clientCommunication');
        }

        $res = $previousSamplereport->delete();
        if ($res){
            toastr()->success('Sample successfully deleted.');
            return redirect()->route('webadmin.clientCommunication');
        }

        return redirect()->back()->withInput();
    }
    
    // Reorder
    
    public function showDatatable()
    {
        $datas = ClientCommunication::orderBy('position', 'asc')->get();
        return view('admin.client_communication.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = ClientCommunication::all();

        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $data->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }


    public function makeSlug($request, $count = 0){
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request)));
        $slug = trim(preg_replace('/-+/', '-', $slug), '-');
        // dd($count);
        if($count != 0){
            $slug = $slug.'-'.$count;
        }
        $exist = ClientCommunication::where('slug', $slug)->get();

        if(count($exist) != 0){
            $count++;
            // dd($count);
            self::makeSlug($request, $count);
        }

        return $slug;
    }

}
