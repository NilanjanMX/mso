<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Productfaq;
use App\Models\Productfaqcategory;

class ProductfaqController extends Controller
{
    // Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = Productfaqcategory::latest()->get();
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
                    if(is_permitted('product-faq-product-faq-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.productfaqcategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-faq-product-faq-category', 'faqs')){
                    $btn .= '<a href="'.route('webadmin.productfaq-by-category',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">FAQs</a>';
                    }
                    if(is_permitted('product-faq-product-faq-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.productfaqcategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.productfaq.category_index');
    }
    
    public function productfaq_by_category(Request $request,$category_id){
        //dd($category_id);
        if ($request->ajax()) {
            $category_id = $request->get('category_id');
            //dd($id);
            $data = Productfaq::where('productfaqcategory_id',$category_id)->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                
                ->addColumn('category', function ($row) {
                    $category = Productfaqcategory::where('id',$row->productfaqcategory_id)->first();
                    if(isset($category->name) && !empty($category->name)){
                        $category = $category->name;
                    }else{
                        $category = '';
                    }
                    return $category;
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.ifa-product-faqs.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
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
                    $btn = '<a href="'.route('webadmin.productfaqEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.productfaqDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['category','view_details','status','action'])
                ->make(true);
        }
        $data['category_id'] = $category_id;
        return view('admin.productfaq.faq_by_category',$data);
    }
    
    
    

    public function category_add(){
        return view('admin.productfaq.category_add');
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

        $res = Productfaqcategory::create($saveData);
        if ($res){
            toastr()->success('Product Faq Category successfully saved.');
            return redirect()->route('webadmin.productfaqcategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['productfaqcategory'] = Productfaqcategory::where('id',$id)->first();
        return view('admin.productfaq.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Productfaqcategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productfaqcategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Product FAQ Category successfully updated.');
            return redirect()->route('webadmin.productfaqcategory');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBusinessfaqcategory = Productfaqcategory::where('id',$id)->first();
        if (!$previousBusinessfaqcategory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productfaqcategory');
        }
        $res = $previousBusinessfaqcategory->delete();
        if ($res){
            toastr()->success('Product FAQ Category successfully deleted.');
            return redirect()->route('webadmin.productfaqcategory');
        }
        return redirect()->back()->withInput();
    }

    //FAQ

    public function index(Request $request){
        if ($request->ajax()) {
            $data = Productfaq::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                
                ->addColumn('category', function ($row) {
                    $category = Productfaqcategory::where('id',$row->productfaqcategory_id)->first();
                    if(isset($category->name) && !empty($category->name)){
                        $category = $category->name;
                    }else{
                        $category = '';
                    }
                    return $category;
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.ifa-product-faqs.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
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
                    if(is_permitted('product-faq-product-faq', 'edit')){
                    $btn = '<a href="'.route('webadmin.productfaqEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('product-faq-product-faq', 'delete')){
                    $btn .= '<a href="'.route('webadmin.productfaqDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['category','view_details','status','action'])
                ->make(true);
        }
        return view('admin.productfaq.index');
    }

    public function add(){
        $categories = Productfaqcategory::get();
        return view('admin.productfaq.add')->with(compact('categories'));
    }

    public function save(Request $request){
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'productfaqcategory_id' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'productfaqcategory_id' => $input['productfaqcategory_id'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Productfaq::create($saveData);
        if ($res){
            toastr()->success('Product Faq successfully saved.');
            return redirect()->route('webadmin.productfaq');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['categories'] = Productfaqcategory::get();
        $data['productfaq'] = Productfaq::where('id',$id)->first();
        return view('admin.productfaq.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category' => 'required'
        ]);
        $previousBusinessfaq = Productfaq::where('id',$id)->first();
        if (!$previousBusinessfaq){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productfaq');
        }
        $input = $request->all();
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'productfaqcategory_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBusinessfaq->update($saveData);
        if ($res){
            toastr()->success('Product FAQ successfully updated.');
            return redirect()->route('webadmin.productfaq');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBusinessfaq = Productfaq::where('id',$id)->first();
        if (!$previousBusinessfaq){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.productfaq');
        }
        $res = $previousBusinessfaq->delete();
        if ($res){
            toastr()->success('Product FAQ successfully deleted.');
            return redirect()->route('webadmin.productfaq');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Productfaq::with('category')->orderBy('position','ASC')->get();
        //dd($datas);
        /*foreach($datas as $data){
            dd($data->category->name);
        }*/
        return view('admin.productfaq.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Productfaq::all();

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
