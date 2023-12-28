<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\HomepageMembership;
use App\Models\WelcomeLetter;

class WelcomeLetterController extends Controller
{

    public function index(Request $request){
        if ($request->ajax()) {
            $data = WelcomeLetter::orderBy('position')->get();
            return Datatables::of($data)
                ->addIndexColumn()
              
                ->addColumn('body', function ($row) {
                    if($row->body){
                        $body = '<pre class="bodyText">'.$row->body.'</pre>';
                    }
                    return $body;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('client-communication-welcome-letter', 'edit')){
                    $btn = '<a href="'.route('webadmin.welcomeletterEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('client-communication-welcome-letter', 'delete')){
                    $btn .= '<a href="'.route('webadmin.welcomeletterDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['body','status','action'])
                ->make(true);
        }
        // dd(2);
        
        return view('admin.welcomeletter.index');
    }

    public function add(){
        return view('admin.welcomeletter.add');
    }

    public function save(Request $request){
        
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'subject' => $input['subject'],
            'body' => $input['body'],
            // 'user_id' => 'admin',
            'is_active' => isset($input['status'])?1:0
        ];
        
        $res = WelcomeLetter::create($saveData);
        if ($res){
            toastr()->success('successfully saved.');
            return redirect()->route('webadmin.welcomeletters');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['welcomeletter'] = WelcomeLetter::where('id',$id)->first();
        return view('admin.welcomeletter.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
        ]);
        
        $previousStationary = WelcomeLetter::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.welcomeletters');
        }

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'subject' => $input['subject'],
            'body' => $input['body'],
            // 'user_id' => 'admin',
            'is_active' => isset($input['status'])?1:0
        ];

        
        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('successfully updated.');
            return redirect()->route('webadmin.welcomeletters');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = WelcomeLetter::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.welcomeletters');
        }
       
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('successfully deleted.');
            return redirect()->route('webadmin.welcomeletters');
        }

        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = WelcomeLetter::orderBy('position','ASC')->get();
        return view('admin.welcomeletter.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = WelcomeLetter::all();

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

    public function setting(Request $request)
    {
        $datas = WelcomeLetter::all();

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

}
