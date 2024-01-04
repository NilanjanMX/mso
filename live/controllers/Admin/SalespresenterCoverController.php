<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Models\SalespresenterCover;
use App\Models\Salespresentercategory;
use DB;

class SalespresenterCoverController extends Controller
{
    public function index(Request $request){
        // dd(1);
        if ($request->ajax()) {
            //$data = SalespresenterCover::with('category')->latest()->get();
            $data = SalespresenterCover::with('category')->where('uploaded_by', 'A')->orderBy('position','ASC')->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url=asset("uploads/salespresentersoftcopy/$row->image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('updated_at', function ($row) {
                    
                    if(!empty($row->updated_at)){
                        $updated_at = date('d-m-Y',strtotime($row->updated_at));
                    }

                    return $updated_at;
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
                    $btn = '';
                    if(is_permitted('sales-presenters-pdf-cover-image', 'edit')){
                    $btn = '<a href="'.route('webadmin.salespresentercoverEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('sales-presenters-pdf-cover-image', 'delete')){
                    $btn .= '<a href="'.route('webadmin.salespresentercoverDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['image','category','created_at','updated_at','status','action'])
                ->make(true);
        }
        return view('admin.salespresenter_cover.index');
    }

    public function add(){
        // $data['categories'] = Salespresentercategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.salespresenter_cover.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'image' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            // 'category' => 'required',
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            // 'salespresentercategories_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];

        // if(isset($input['status']) && $input['status'] == "1"){
        //     DB::table('salespresenter_covers')->update(['is_active' => 0]);
        // }
        
        if ($image = $request->file('image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['image'] = $file;

            $destinationPath = public_path('/uploads/salespresentersoftcopy');
            $image->move($destinationPath, $file);
            
        }

        $res = SalespresenterCover::create($saveData);
        if ($res){
            toastr()->success('Sales Presenter Soft Copy successfully saved.');
            return redirect()->route('webadmin.salespresentercover');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['salespresentersoftcopy'] = SalespresenterCover::where('id',$id)->first();
        // $data['categories'] = Salespresentercategory::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.salespresenter_cover.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            // 'category' => 'required',
        ]);

        $previousBlog = SalespresenterCover::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresentercover');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            // 'salespresentercategories_id' => $input['category'],
            'is_active' => isset($input['status'])?1:0
        ];
        // dd(isset($input['status']) && $input['status'] == "1");
        // if(isset($input['status']) && $input['status'] == "1"){
        //     DB::table('salespresenter_covers')->update(['is_active' => 0]);
        //     $saveData['is_active'] = 1;
        // }

        if ($image = $request->file('image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['image'] = $file;

            $destinationPath = public_path('/uploads/salespresentersoftcopy');
            $image->move($destinationPath, $file);
            if (file_exists(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']))) {
                //chmod(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']), 0644);
                unlink(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']));
            }
        }

        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Sales Presenter Soft Copy successfully saved.');
            return redirect()->route('webadmin.salespresentercover');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = SalespresenterCover::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.salespresentercover');
        }

        
        if (file_exists(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']))) {
            //chmod(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']), 0644);
            unlink(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']));
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Sales Presenter Soft Copy successfully deleted.');
            return redirect()->route('webadmin.salespresentercover');
        }

        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $salespresentersoftcopies = SalespresenterCover::orderBy('position','ASC')->get();
        return view('admin.salespresenter_cover.reorder',compact('salespresentersoftcopies'));
    }

    public function updateOrder(Request $request)
    {
        $salespresentersoftcopies = SalespresenterCover::all();

        foreach ($salespresentersoftcopies as $salespresentersoftcopy) {
            $salespresentersoftcopy->timestamps = false; // To disable update_at field updation
            $id = $salespresentersoftcopy->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    $salespresentersoftcopy->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

}
