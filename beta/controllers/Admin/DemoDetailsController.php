<?php

namespace App\Http\Controllers\Admin;

use App\Models\DemoDetails;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class DemoDetailsController extends Controller
{
   
     // Category

     public function index(Request $request){
        if ($request->ajax()) {
            $data = DemoDetails::latest()->get();
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
                    $btn = '<a href="'.route('webadmin.demoDetailsEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.demoDetailsDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.demo_details.category_index');
    }

    public function add(){
        return view('admin.demo_details.category_add');
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

        $res = DemoDetails::create($saveData);
        if ($res){
            toastr()->success('Client communication category successfully saved.');
            return redirect()->route('webadmin.demoDetails');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['category'] = DemoDetails::where('id',$id)->first();
        return view('admin.demo_details.category_edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = DemoDetails::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.demoDetails');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Client communication category successfully updated.');
            return redirect()->route('webadmin.demoDetails');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = DemoDetails::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.demoDetails');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Client communication category successfully deleted.');
            return redirect()->route('webadmin.demoDetails');
        }
        return redirect()->back()->withInput();
    }


}
