<?php

namespace App\Http\Controllers\Admin;

use App\Models\Premiumbanner;
use App\Models\Premiumbannercategory;
use App\Models\Premiumbannertag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;

class PremiumbannerController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Premiumbanner::with('category')->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/premiumbanner/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('appcoverimage', function ($row) {
                    $url=asset("uploads/premiumbanner/app/$row->app_cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('premiumimage', function ($row) {
                    $url=asset("uploads/premiumbanner/premium/$row->premium_banner");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.premiumbanner.details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
                    }else{
                        $view_details = '';
                    }
                    return $view_details;
                })
                /*->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })*/
                ->addColumn('category', function ($row) {
                    $premiumbannercategory_ids = explode(",",$row->premiumbannercategory_ids);
                    
                    $categoryData = array();
                    if(!empty($premiumbannercategory_ids)){
                        $categories = Premiumbannercategory::whereIn('id',$premiumbannercategory_ids)->get();
                    
                        foreach($categories as $category){
                            array_push($categoryData,$category->name);
                        }
                        $category = implode(", ",$categoryData);
                    }else{
                        $category = '';
                    }
                    return $category;
                    
                })
                ->addColumn('download_count', function ($row) {
                    return $row->download_count;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('Y/m/d',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('updated_at', function ($row) {
                    
                    if(!empty($row->updated_at)){
                        $updated_at = date('Y/m/d',strtotime($row->updated_at));
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
                    if(is_permitted('marketing-banners-banners', 'edit')){
                    $btn = '<a href="'.route('webadmin.premiumbannerEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-banners-banners', 'delete')){
                    $btn .= '<a href="'.route('webadmin.premiumbannerDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','appcoverimage','premiumimage','category','view_details','download_count','status','action','created_at','updated_at'])
                ->make(true);
        }
        return view('admin.premiumbanner.index');
    }

    public function add(){
        $data['categories'] = Premiumbannercategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['tags'] = Premiumbannertag::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.premiumbanner.add',$data);
    }

    public function save(Request $request){
        ini_set('memory_limit', -1);
        $request->validate([
            'title' => 'required',
            //'content' => 'required',
            'app_cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'premium_banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'category_ids' => 'required',
            'tag_ids' => 'required'
        ]);

        $input = $request->all();
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $tag_ids = $input['tag_ids'];
        $tag_ids = implode(",",$tag_ids);
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'premiumbannercategory_id' => $input['category'],
            'premiumbannercategory_ids' => $category_ids,
            'premiumbannertag_ids' => $tag_ids,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/premiumbanner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/premiumbanner');
            $image->move($destinationPath, $saveData['cover_image']);
        }
        if ($image = $request->file('app_cover_image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['app_cover_image'] = $file;

            $destinationPath = public_path('/uploads/premiumbanner/app');
            $image->move($destinationPath, $file);
            
        }
        if ($image = $request->file('premium_banner')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['premium_banner'] = $file;

            $destinationPath = public_path('/uploads/premiumbanner/premium');
            $image->move($destinationPath, $file);
            
        }

        $res = Premiumbanner::create($saveData);
        if ($res){
            toastr()->success('Premium banner successfully saved.');
            return redirect()->route('webadmin.premiumbanner');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['premiumbanner'] = Premiumbanner::where('id',$id)->first();
        $data['categories'] = Premiumbannercategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['tags'] = Premiumbannertag::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.premiumbanner.edit',$data);
    }

    public function update(Request $request,$id){
        
        
        ini_set('memory_limit', -1);
        $request->validate([
            'title' => 'required',
            'category_ids' => 'required',
            'tag_ids' => 'required'
        ]);
        $previousBlog = Premiumbanner::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbanner');
        }
        $input = $request->all();
        // dd($input);
        $category_ids = $input['category_ids'];
        $category_ids = implode(",",$category_ids);
        $tag_ids = $input['tag_ids'];
        $tag_ids = implode(",",$tag_ids);
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'premiumbannercategory_id' => $input['category'],
            'premiumbannercategory_ids' => $category_ids,
            'premiumbannertag_ids' => $tag_ids,
            'is_active' => isset($input['status'])?1:0
        ];

        // dd($saveData);

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/premiumbanner/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/premiumbanner');
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']));
            }
            if (file_exists(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']));
            }
            
        }
        
        if ($image = $request->file('app_cover_image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['app_cover_image'] = $file;

            $destinationPath = public_path('/uploads/premiumbanner/app');
            $image->move($destinationPath, $file);
            if(!empty($previousBlog['app_cover_image'])){
                if (file_exists(public_path('uploads/premiumbanner/app/'.$previousBlog['app_cover_image']))) {
                    //chmod(public_path('uploads/premiumbanner/app/'.$previousBlog['premium_banner']), 0644);
                    unlink(public_path('uploads/premiumbanner/app/'.$previousBlog['app_cover_image']));
                }
            }
            
        }

        if ($image = $request->file('premium_banner')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['premium_banner'] = $file;

            $destinationPath = public_path('/uploads/premiumbanner/premium');
            $image->move($destinationPath, $file);
            if (file_exists(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']))) {
                //chmod(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']), 0644);
                unlink(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']));
            }
        }
        
        // dd($previousBlog);
        
        $res = $previousBlog->update($saveData);
        
        // dd($res);
        if ($res){
            toastr()->success('Premium banner successfully saved.');
            return redirect()->route('webadmin.premiumbanner');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = Premiumbanner::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbanner');
        }

        if (file_exists(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']))) {
            chmod(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/premiumbanner/thumbnail/'.$previousBlog['cover_image']));
        }
        if (file_exists(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']))) {
            chmod(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/premiumbanner/'.$previousBlog['cover_image']));
        }
        if (file_exists(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']))) {
            chmod(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']), 0644);
            unlink(public_path('uploads/premiumbanner/premium/'.$previousBlog['premium_banner']));
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Premium banner successfully deleted.');
            return redirect()->route('webadmin.premiumbanner');
        }

        return redirect()->back()->withInput();
    }




    // Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = Premiumbannercategory::latest()->get();
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
                    if(is_permitted('marketing-banners-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.premiumbannercategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-banners-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.premiumbannercategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                     }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.premiumbanner.category_index');
    }

    public function category_add(){
        return view('admin.premiumbanner.category_add');
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

        $res = Premiumbannercategory::create($saveData);
        if ($res){
            toastr()->success('Premium banner category successfully saved.');
            return redirect()->route('webadmin.premiumbannercategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['category'] = Premiumbannercategory::where('id',$id)->first();
        return view('admin.premiumbanner.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Premiumbannercategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbannercategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Premium banner category successfully updated.');
            return redirect()->route('webadmin.premiumbannercategory');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBlog = Premiumbannercategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbannercategory');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Premium banner category successfully deleted.');
            return redirect()->route('webadmin.premiumbannercategory');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Premiumbanner::orderBy('position','ASC')->get();
        return view('admin.premiumbanner.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Premiumbanner::all();

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


    // Tag

    public function tag_index(Request $request){
        if ($request->ajax()) {
            $data = Premiumbannertag::latest()->get();
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
                    if(is_permitted('marketing-banners-tag', 'edit')){
                    $btn = '<a href="'.route('webadmin.premiumbannertagEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-banners-tag', 'delete')){
                    $btn .= '<a href="'.route('webadmin.premiumbannertagDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                     }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.premiumbanner.tag_index');
    }

    public function tag_add(){
        $data = [];
        $data['premiumbanner'] = Premiumbanner::where('is_active',1)->orderBy('title','asc')->get();
        return view('admin.premiumbanner.tag_add',$data);
    }

    public function tag_save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = Premiumbannertag::create($saveData);
        if ($res){
            toastr()->success('Premium banner tag successfully saved.');
            return redirect()->route('webadmin.premiumbannertag');
        }

        return redirect()->back()->withInput();
    }

    public function tag_edit($id){
        $data = [];
        $data['premiumbanner'] = Premiumbanner::where('is_active',1)->orderBy('title','asc')->get();
        $data['detail'] = Premiumbannertag::where('id',$id)->first();
        return view('admin.premiumbanner.tag_edit',$data);
    }

    public function tag_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Premiumbannertag::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbannertag');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Premium banner category successfully updated.');
            return redirect()->route('webadmin.premiumbannertag');
        }
        return redirect()->back()->withInput();
    }

    public function tag_delete(Request $request,$id){
        $previousBlog = Premiumbannertag::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.premiumbannertag');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Premium banner tag successfully deleted.');
            return redirect()->route('webadmin.premiumbannertag');
        }
        return redirect()->back()->withInput();
    }

}
