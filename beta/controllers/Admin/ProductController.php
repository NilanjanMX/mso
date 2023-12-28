<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use DB;

class ProductController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = DB::table("roei_products")->get();
            return Datatables::of($data)
                ->addIndexColumn()                
                ->addColumn('status', function ($row) {
                    if($row->status == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-product', 'edit')){
                    $btn = '<a href="'.route('webadmin.productEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    //$btn .= '<a href="'.route('webadmin.productDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['name','status','action'])
                ->make(true);
        }
        return view('admin.product.index');
    }

    public function add(){
        return view('admin.product.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'status' => isset($input['status'])?1:0
        ];

        $res = DB::table("roei_products")->insert($saveData);
        if ($res){
            toastr()->success('Product successfully saved.');
            return redirect()->route('webadmin.product');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['detail'] = DB::table("roei_products")->where('id',$id)->first();
        return view('admin.product.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'status' => isset($input['status'])?1:0
        ];

        $res = DB::table("roei_products")->where('id','=',$id)->update($saveData);
        if ($res){
            toastr()->success('Product successfully saved.');
            return redirect()->route('webadmin.product');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = DB::table("roei_products")->where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.product');
        }
        $res = DB::table("roei_products")->where('id',$id)->delete();
        if ($res){
            toastr()->success('Product successfully deleted.');
            return redirect()->route('webadmin.product');
        }

        return redirect()->back()->withInput();
    }
    public function indexProductType(Request $request){
        if ($request->ajax()) {
            $data = DB::table("roei_product_types")->get();
            return Datatables::of($data)
                ->addIndexColumn()                
                ->addColumn('status', function ($row) {
                    if($row->status == 1){
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('calculator-product-type', 'edit')){
                    $btn = '<a href="'.route('webadmin.productTypeEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    //$btn .= '<a href="'.route('webadmin.productDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['name','status','action'])
                ->make(true);
        }
        return view('admin.product.index_product_type');
    }

    public function addProductType(){
        return view('admin.product.add_product_type');
    }

    public function saveProductType(Request $request){
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'status' => isset($input['status'])?1:0
        ];

        $res = DB::table("roei_product_types")->insert($saveData);
        if ($res){
            toastr()->success('Product successfully saved.');
            return redirect()->route('webadmin.productType');
        }

        return redirect()->back()->withInput();
    }

    public function editProductType($id){
        $data['detail'] = DB::table("roei_product_types")->where('id',$id)->first();
        return view('admin.product.edit_product_type',$data);
    }

    public function updateProductType(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'status' => isset($input['status'])?1:0
        ];

        $res = DB::table("roei_product_types")->where('id','=',$id)->update($saveData);
        if ($res){
            toastr()->success('Product successfully saved.');
            return redirect()->route('webadmin.productType');
        }

        return redirect()->back()->withInput();
    }

    public function deleteProductType(Request $request,$id){
        $previousBlog = DB::table("roei_product_types")->where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.product');
        }
        $res = DB::table("roei_product_types")->where('id',$id)->delete();
        if ($res){
            toastr()->success('Product successfully deleted.');
            return redirect()->route('webadmin.productType');
        }

        return redirect()->back()->withInput();
    }

}
