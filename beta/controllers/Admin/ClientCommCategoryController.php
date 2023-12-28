<?php

namespace App\Http\Controllers\Admin;

use App\Models\ClientCommunicationCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class ClientCommCategoryController extends Controller
{
   
     // Category

     public function category_index(Request $request){
        if ($request->ajax()) {
            $data = ClientCommunicationCategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
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
                    if(is_permitted('client-communication-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.clientCommunicationcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('client-communication-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.clientCommunicationcategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.client_communication.category_index');
    }

    public function category_add(){
        return view('admin.client_communication.category_add');
    }

    public function category_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = ClientCommunicationCategory::create($saveData);
        if ($res){
            toastr()->success('Client communication category successfully saved.');
            return redirect()->route('webadmin.clientCommunicationcategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['category'] = ClientCommunicationCategory::where('id',$id)->first();
        return view('admin.client_communication.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = ClientCommunicationCategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.clientCommunicationcategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Client communication category successfully updated.');
            return redirect()->route('webadmin.clientCommunicationcategory');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBlog = ClientCommunicationCategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.clientCommunicationcategory');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Client communication category successfully deleted.');
            return redirect()->route('webadmin.clientCommunicationcategory');
        }
        return redirect()->back()->withInput();
    }


}
