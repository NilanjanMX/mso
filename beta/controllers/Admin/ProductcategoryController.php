<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Productcategory;

class ProductcategoryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Productcategory::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category_image', function ($row) {
                    $url=asset("uploads/productcategory/thumbnail/$row->category_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
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
                    $btn = '<a href="'.route('webadmin.productcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.productcategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['category_image','status','action'])
                ->make(true);
        }
        return view('admin.productcategory.index');
    }

    public function add(){
        return view('admin.productcategory.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'category_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'sample_pdf' => 'required|max:500000'
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('category_image')){
            $saveData['category_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/productcategory/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['category_image']);


            $destinationPath = public_path('/uploads/productcategory');
            $image->move($destinationPath, $saveData['category_image']);
        }
        if ($image = $request->file('sample_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/productcategory');
            $image->move($destinationPath, $file);
            
        }

        $res = Productcategory::create($saveData);
        if ($res){
            toastr()->success('Product category successfully saved.');
            return redirect()->route('webadmin.productcategory');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['productcategory'] = Productcategory::where('id',$id)->first();
        return view('admin.productcategory.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        $previousBlog = Productcategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productcategory');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description']
        ];

        if ($image = $request->file('category_image')){
            $saveData['category_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/productcategory/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['category_image']);
            $destinationPath = public_path('/uploads/productcategory');
            $image->move($destinationPath, $saveData['category_image']);

            if (file_exists(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']))) {
                chmod(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']), 0644);
                unlink(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']));
            }
            if (file_exists(public_path('uploads/productcategory/'.$previousBlog['category_image']))) {
                chmod(public_path('uploads/productcategory/'.$previousBlog['category_image']), 0644);
                unlink(public_path('uploads/productcategory/'.$previousBlog['category_image']));
            }
            
        }

        if ($image = $request->file('sample_pdf')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['sample_pdf'] = $file;

            $destinationPath = public_path('/uploads/productcategory');
            $image->move($destinationPath, $file);
            if (file_exists(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']))) {
                chmod(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']), 0644);
                unlink(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']));
            }
        }

        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Product category successfully saved.');
            return redirect()->route('webadmin.productcategory');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = Productcategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productcategory');
        }

        if (file_exists(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']))) {
            chmod(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']), 0644);
            unlink(public_path('uploads/productcategory/thumbnail/'.$previousBlog['category_image']));
        }
        if (file_exists(public_path('uploads/productcategory/'.$previousBlog['category_image']))) {
            chmod(public_path('uploads/productcategory/'.$previousBlog['category_image']), 0644);
            unlink(public_path('uploads/productcategory/'.$previousBlog['category_image']));
        }
        if (file_exists(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']))) {
            chmod(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']), 0644);
            unlink(public_path('uploads/productcategory/'.$previousBlog['sample_pdf']));
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Product category successfully deleted.');
            return redirect()->route('webadmin.productcategory');
        }

        return redirect()->back()->withInput();
    }

}
