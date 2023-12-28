<?php

namespace App\Http\Controllers\Admin;

use App\Models\Marketingvideo;
use App\Models\Marketingvideocategory;
use App\Models\Marketingvideotag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class MarketingvideoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Marketingvideo::with('category')->orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/marketingvideo/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('category', function ($row) {
                    $category = '';
                    if(!empty($row->category->name)){
                        $category = $row->category->name;
                    }

                    return $category;
                })
                ->addColumn('downloads', function ($row) {
                    return $row->downloads;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('Y-m-d',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('updated_at', function ($row) {
                    
                    if(!empty($row->updated_at)){
                        $updated_at = date('Y-m-d',strtotime($row->updated_at));
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
                    if(is_permitted('marketing-video-videos', 'edit')){
                    $btn = '<a href="'.route('webadmin.marketingvideoEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-video-videos', 'delete')){
                    $btn .= '<a href="'.route('webadmin.marketingvideoDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','category','downloads','status','action','created_at','updated_at'])
                ->make(true);
        }
        return view('admin.marketingvideo.index');
    }

    public function add(){
        $data['categories'] = Marketingvideocategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['tags'] = Marketingvideotag::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.marketingvideo.add',$data);
    }

    public function save(Request $request){
        ini_set('memory_limit', -1);
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
            'category' => 'required',
        ]);

        $input = $request->all();
        $tag_ids = $input['tag_ids'];
        $tag_ids = implode(",",$tag_ids);
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'marketingvideocategory_id' => $input['category'],
            'tag_ids' => $tag_ids,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/marketingvideo/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/marketingvideo');
            $image->move($destinationPath, $saveData['cover_image']);
        }
        if ($image = $request->file('video')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['video'] = $file;

            $destinationPath = public_path('/uploads/marketingvideo/video');
            $image->move($destinationPath, $file);
            
        }

        $res = Marketingvideo::create($saveData);
        if ($res){
            toastr()->success('Marketing video successfully saved.');
            return redirect()->route('webadmin.marketingvideo');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['marketingvideo'] = Marketingvideo::where('id',$id)->first();
        $data['categories'] = Marketingvideocategory::where('is_active',1)->orderBy('name','asc')->get();
        $data['tags'] = Marketingvideotag::where('is_active',1)->orderBy('name','asc')->get();
        return view('admin.marketingvideo.edit',$data);
    }

    public function update(Request $request,$id){
        ini_set('memory_limit', -1);
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'category' => 'required',
            // 'tag_ids' => 'required'
        ]);

        $previousBlog = Marketingvideo::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.marketingvideo');
        }

        $input = $request->all();
        $tag_ids = $request->tag_ids;
        if($tag_ids){
            $tag_ids = implode(",",$tag_ids);    
        }else{
            $tag_ids = "";
        }
        
        
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'marketingvideocategory_id' => $input['category'],
            'tag_ids' => $tag_ids,
            'is_active' => isset($input['status'])?1:0
        ];
        // dd($saveData);

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/marketingvideo/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/marketingvideo');
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']));
            }
            if (file_exists(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']))) {
                //chmod(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']), 0644);
                unlink(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']));
            }
            
        }
        
        
        if ($image = $request->file('video')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['video'] = $file;

            $destinationPath = public_path('/uploads/marketingvideo/video');
            $image->move($destinationPath, $file);
            if (file_exists(public_path('uploads/marketingvideo/video/'.$previousBlog['video']))) {
                //chmod(public_path('uploads/marketingvideo/video/'.$previousBlog['video']), 0644);
                unlink(public_path('uploads/marketingvideo/video/'.$previousBlog['video']));
            }
        }

        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Marketing video successfully saved.');
            return redirect()->route('webadmin.marketingvideo');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousBlog = Marketingvideo::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.marketingvideo');
        }

        if (file_exists(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']))) {
            //chmod(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/marketingvideo/thumbnail/'.$previousBlog['cover_image']));
        }
        if (file_exists(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']))) {
            //chmod(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']), 0644);
            unlink(public_path('uploads/marketingvideo/'.$previousBlog['cover_image']));
        }
        if (file_exists(public_path('uploads/marketingvideo/video/'.$previousBlog['video']))) {
            //chmod(public_path('uploads/marketingvideo/video/'.$previousBlog['video']), 0644);
            unlink(public_path('uploads/marketingvideo/video/'.$previousBlog['video']));
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Marketing video successfully deleted.');
            return redirect()->route('webadmin.marketingvideo');
        }

        return redirect()->back()->withInput();
    }




    // Category

    public function category_index(Request $request){
        if ($request->ajax()) {
            $data = Marketingvideocategory::latest()->get();
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
                    if(is_permitted('marketing-video-category', 'edit')){
                    $btn = '<a href="'.route('webadmin.marketingvideocategoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-video-category', 'delete')){
                    $btn .= '<a href="'.route('webadmin.marketingvideocategoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                     }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.marketingvideo.category_index');
    }

    public function category_add(){
        return view('admin.marketingvideo.category_add');
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

        $res = Marketingvideocategory::create($saveData);
        if ($res){
            toastr()->success('Marketing video category successfully saved.');
            return redirect()->route('webadmin.marketingvideocategory');
        }

        return redirect()->back()->withInput();
    }

    public function category_edit($id){
        $data['category'] = Marketingvideocategory::where('id',$id)->first();
        return view('admin.marketingvideo.category_edit',$data);
    }

    public function category_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Marketingvideocategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.marketingvideocategory');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Marketing video category successfully updated.');
            return redirect()->route('webadmin.marketingvideocategory');
        }
        return redirect()->back()->withInput();
    }

    public function category_delete(Request $request,$id){
        $previousBlog = Marketingvideocategory::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.marketingvideocategory');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Marketing video category successfully deleted.');
            return redirect()->route('webadmin.marketingvideocategory');
        }
        return redirect()->back()->withInput();
    }

    public function showDatatable()
    {
        $datas = Marketingvideo::orderBy('position','ASC')->get();
        return view('admin.marketingvideo.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Marketingvideo::all();

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
            $data = Marketingvideotag::latest()->get();
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
                    if(is_permitted('marketing-video-tag', 'edit')){
                    $btn = '<a href="'.route('webadmin.mvtagEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('marketing-video-tag', 'delete')){
                    $btn .= '<a href="'.route('webadmin.mvtagDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }
        return view('admin.marketingvideo.tag_index');
    }

    public function tag_add(){
        $data = [];
        $data['premiumbanner'] = Marketingvideo::where('is_active',1)->orderBy('title','asc')->get();
        return view('admin.marketingvideo.tag_add',$data);
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

        $res = Marketingvideotag::create($saveData);
        if ($res){
            toastr()->success('Marketing Video tag successfully saved.');
            return redirect()->route('webadmin.mvtag');
        }

        return redirect()->back()->withInput();
    }

    public function tag_edit($id){
        $data = [];
        // $data['premiumbanner'] = Marketingvideo::where('is_active',1)->orderBy('title','asc')->get();
        $data['detail'] = Marketingvideotag::where('id',$id)->first();
        return view('admin.marketingvideo.tag_edit',$data);
    }

    public function tag_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);
        $previousBlog = Marketingvideotag::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mvtag');
        }
        $input = $request->all();
        $saveData = [
            'name' => $input['name'],
            'is_active' => isset($input['status'])?1:0
        ];
        $res = $previousBlog->update($saveData);
        if ($res){
            toastr()->success('Marketing Video tag successfully updated.');
            return redirect()->route('webadmin.mvtag');
        }
        return redirect()->back()->withInput();
    }

    public function tag_delete(Request $request,$id){
        $previousBlog = Marketingvideotag::where('id',$id)->first();
        if (!$previousBlog){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.mvtag');
        }
        $res = $previousBlog->delete();
        if ($res){
            toastr()->success('Marketing Video tag successfully deleted.');
            return redirect()->route('webadmin.mvtag');
        }
        return redirect()->back()->withInput();
    }


}
