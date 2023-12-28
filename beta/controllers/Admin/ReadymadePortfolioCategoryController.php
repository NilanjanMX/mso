<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\ReadymadePortfolioCategory;

class ReadymadePortfolioCategoryController extends Controller
{
    // Category

    public function index(Request $request){
        if ($request->ajax()) {
            $data = ReadymadePortfolioCategory::latest()->get();
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
                    if(is_permitted('readymade-portfolio-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.readymadePortfolioCategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('readymade-portfolio-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.readymadePortfolioCategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.readymadePortfolio.category.index');
    }

    public function add(){
        return view('admin.readymadePortfolio.category.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = ReadymadePortfolioCategory::create($saveData);
        if ($res){
            toastr()->success('Readymade Portfolio Category successfully saved.');
            return redirect()->route('webadmin.readymadePortfolioCategory');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['detail'] = ReadymadePortfolioCategory::where('id',$id)->first();
        return view('admin.readymadePortfolio.category.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = ReadymadePortfolioCategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadePortfolioCategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Readymade Portfolio Category successfully updated.');
            return redirect()->route('webadmin.readymadePortfolioCategory');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBusinessfaqcategory = ReadymadePortfolioCategory::where('id',$id)->first();
        if (!$previousBusinessfaqcategory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.readymadePortfolioCategory');
        }
        $res = $previousBusinessfaqcategory->delete();
        if ($res){
            toastr()->success('Readymade Portfolio Category successfully deleted.');
            return redirect()->route('webadmin.readymadePortfolioCategory');
        }
        return redirect()->back()->withInput();
    }
}
