<?php

namespace App\Http\Controllers\Admin;

use App\Models\Salespresentercategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SalespresenterController extends Controller
{
   
     // Category

     public function category_index(Request $request){
        if ($request->ajax()) {
            $data = Salespresentercategory::latest()->get();
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
                    if(is_permitted('sales-presenters-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.salespresentercategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('sales-presenters-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.salespresentercategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.salespresenter.category_index');
    }

    public function category_add(){
        return view('admin.salespresenter.category_add');
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

        $res = Salespresentercategory::create($saveData);
        if ($res){
            toastr()->success('Sales Presenters category successfully saved.');
            return redirect()->route('webadmin.salespresentercategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['category'] = Salespresentercategory::where('id',$id)->first();
        return view('admin.salespresenter.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Salespresentercategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresentercategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Sales Presenters category successfully updated.');
            return redirect()->route('webadmin.salespresentercategory');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBlog = Salespresentercategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresentercategory');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Sales Presenters category successfully deleted.');
            return redirect()->route('webadmin.salespresentercategory');
        }
        return redirect()->back()->withInput();
    }


}
