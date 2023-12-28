<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Businessfaqcategory;

class BusinessfaqcategoryController extends Controller
{
    // Category

    public function index(Request $request){
        if ($request->ajax()) {
            $data = Businessfaqcategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
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
                    if(is_permitted('business-faq-business-faq-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.businessfaqcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('business-faq-business-faq-category', 'faqs')){
                    $btn .= '<a href="'.route('webadmin.businessfaq-by-category',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">FAQs</a>';
                    }
                    if(is_permitted('business-faq-business-faq-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.businessfaqcategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.businessfaq.category_index');
    }

    public function add(){
        return view('admin.businessfaq.category_add');
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

        $res = Businessfaqcategory::create($saveData);
        if ($res){
            toastr()->success('Business Faq Category successfully saved.');
            return redirect()->route('webadmin.businessfaqcategory');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['businessfaqcategory'] = Businessfaqcategory::where('id',$id)->first();
        // dd($data['businessfaqcategory']);
        return view('admin.businessfaq.category_edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Businessfaqcategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.businessfaqcategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Business FAQ Category successfully updated.');
            return redirect()->route('webadmin.businessfaqcategory');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBusinessfaqcategory = Businessfaqcategory::where('id',$id)->first();
        if (!$previousBusinessfaqcategory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.businessfaqcategory');
        }
        $res = $previousBusinessfaqcategory->delete();
        if ($res){
            toastr()->success('Business FAQ Category successfully deleted.');
            return redirect()->route('webadmin.businessfaqcategory');
        }
        return redirect()->back()->withInput();
    }
}
