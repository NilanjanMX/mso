<?php

namespace App\Http\Controllers\Admin;

use App\Models\DemoSessions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class DemoSessionsController extends Controller
{
   
     // Category

     public function index(Request $request){
        if ($request->ajax()) {
            $data = DemoSessions::latest()->get();
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
                    $btn = '<a href="'.route('webadmin.demoSessionsEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.demoSessionsDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.demo_sessions.category_index');
    }

    public function add(){
        return view('admin.demo_sessions.category_add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = DemoSessions::create($saveData);
        if ($res){
            toastr()->success('Client communication category successfully saved.');
            return redirect()->route('webadmin.demoSessions');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['category'] = DemoSessions::where('id',$id)->first();
        return view('admin.demo_sessions.category_edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = DemoSessions::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.demoSessions');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Client communication category successfully updated.');
            return redirect()->route('webadmin.demoSessions');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = DemoSessions::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.demoSessions');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Client communication category successfully deleted.');
            return redirect()->route('webadmin.demoSessions');
        }
        return redirect()->back()->withInput();
    }


}
