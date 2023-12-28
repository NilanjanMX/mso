<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Clientobjectionhandling;
use Yajra\DataTables\Facades\DataTables;

class ClientobjectionhandelingController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Clientobjectionhandling::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.client-objection-handling.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
                    }else{
                        $view_details = '';
                    }
                    return $view_details;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
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
                    if(is_permitted('ifa-tools-client-objection-handling', 'edit')){
                    $btn = '<a href="'.route('webadmin.cohEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>';
                    }
                    if(is_permitted('ifa-tools-client-objection-handling', 'delete')){
                    $btn .= '<a href="'.route('webadmin.cohDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';
                    }
                    return $btn;
                })
                ->rawColumns(['view_details','status','action'])
                ->make(true);
        }
        return view('admin.clientobjectionhandelings.index');
    }

    public function add(){
        return view('admin.clientobjectionhandelings.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Clientobjectionhandling::create($saveData);
        if ($res){
            toastr()->success('Client Objection Handling successfully saved.');
            return redirect()->route('webadmin.coh');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['clientobjectionhandling'] = Clientobjectionhandling::where('id',$id)->first();
        return view('admin.clientobjectionhandelings.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        $previousClientobjectionhandling = Clientobjectionhandling::where('id',$id)->first();
        if (!$previousClientobjectionhandling){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.coh');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousClientobjectionhandling->update($saveData);
        if ($res){
            toastr()->success('Client Objection Handeling successfully saved.');
            return redirect()->route('webadmin.coh');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousClientobjectionhandling = Clientobjectionhandling::where('id',$id)->first();
        if (!$previousClientobjectionhandling){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.coh');
        }

        $res = $previousClientobjectionhandling->delete();
        if ($res){
            toastr()->success('Client Objection Handeling successfully deleted.');
            return redirect()->route('webadmin.coh');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Clientobjectionhandling::orderBy('position','ASC')->get();
        return view('admin.clientobjectionhandelings.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Clientobjectionhandling::all();

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
