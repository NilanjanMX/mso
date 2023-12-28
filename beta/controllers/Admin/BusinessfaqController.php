<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Businessfaq;
use App\Models\Businessfaqcategory;


class BusinessfaqController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Businessfaq::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    $businessfaqscategory_ids = explode(",",$row->businessfaqscategory_ids);
                    /*$category = Businessfaqcategory::where('id',$row->businessfaqscategory_id)->first();
                    if(isset($category->name) && !empty($category->name)){
                        $category = $category->name;
                    }else{
                        $category = '';
                    }
                    return $category;*/
                    $categoryData = array();
                    if(!empty($businessfaqscategory_ids)){
                        $categories = Businessfaqcategory::whereIn('id',$businessfaqscategory_ids)->get();
                    
                        foreach($categories as $category){
                            array_push($categoryData,$category->name);
                        }
                        $category = implode(", ",$categoryData);
                    }else{
                        $category = '';
                    }
                    return $category;
                    
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.ifa-business-faqs.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
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
                    if(is_permitted('business-faq-business-faq', 'edit')){
                    $btn = '<a href="'.route('webadmin.businessfaqEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('business-faq-business-faq', 'delete')){
                    $btn .= '<a href="'.route('webadmin.businessfaqDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['category','view_details','status','action'])
                ->make(true);
        }
        return view('admin.businessfaq.index');
    }
    
    public function businessfaq_by_category(Request $request,$category_id){
        if ($request->ajax()) {
            $category_id = $request->get('category_id');
            $data = Businessfaq::where('businessfaqscategory_id',$category_id)->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    $businessfaqscategory_ids = explode(",",$row->businessfaqscategory_ids);
                    /*$category = Businessfaqcategory::where('id',$row->businessfaqscategory_id)->first();
                    if(isset($category->name) && !empty($category->name)){
                        $category = $category->name;
                    }else{
                        $category = '';
                    }
                    return $category;*/
                    $categoryData = array();
                    if(!empty($businessfaqscategory_ids)){
                        $categories = Businessfaqcategory::whereIn('id',$businessfaqscategory_ids)->get();
                    
                        foreach($categories as $category){
                            array_push($categoryData,$category->name);
                        }
                        $category = implode(", ",$categoryData);
                    }else{
                        $category = '';
                    }
                    return $category;
                    
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.ifa-business-faqs.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
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
                    $btn = '<a href="'.route('webadmin.businessfaqEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.businessfaqDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                ->rawColumns(['category','view_details','status','action'])
                ->make(true);
        }
        $data['category_id'] = $category_id;
        return view('admin.businessfaq.faq_by_category',$data);
    }

    public function add(){
        $categories = Businessfaqcategory::get();
        return view('admin.businessfaq.add')->with(compact('categories'));
    }

    public function save(Request $request){
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category_ids' => 'required'
        ]);

        $input = $request->all();
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'businessfaqscategory_id' => $input['businessfaqscategory_id'],
            'businessfaqscategory_ids' => $category_ids,
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Businessfaq::create($saveData);
        if ($res){
            toastr()->success('Business Faq successfully saved.');
            return redirect()->route('webadmin.businessfaq');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['categories'] = Businessfaqcategory::get();
        $data['businessfaq'] = Businessfaq::where('id',$id)->first();
        return view('admin.businessfaq.edit',$data);
    }

    public function update(Request $request,$id){
        //dd("ok");
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'category_ids' => 'required'
        ]);
        $previousBusinessfaq = Businessfaq::where('id',$id)->first();
        if (!$previousBusinessfaq){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.businessfaq');
        }
        $input = $request->all();
        //dd($input);
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $saveData = [
            'question' => $input['question'],
            'answer' => $input['answer'],
            'businessfaqscategory_id' => $input['category'],
            'businessfaqscategory_ids' => $category_ids,
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBusinessfaq->update($saveData);
        if ($res){
            toastr()->success('Business FAQ successfully updated.');
            return redirect()->route('webadmin.businessfaq');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBusinessfaq = Businessfaq::where('id',$id)->first();
        if (!$previousBusinessfaq){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.businessfaq');
        }
        $res = $previousBusinessfaq->delete();
        if ($res){
            toastr()->success('Business FAQ successfully deleted.');
            return redirect()->route('webadmin.businessfaq');
        }
        return redirect()->back()->withInput();
    }
    public function showDatatable()
    {
        $datas = Businessfaq::with('category')->orderBy('position','ASC')->get();
        return view('admin.businessfaq.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Businessfaq::all();

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
