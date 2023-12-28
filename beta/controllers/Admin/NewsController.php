<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = News::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('coverimage', function ($row) {
                    $url=asset("uploads/news/thumbnail/$row->cover_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                })
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.news_details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
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
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('news-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.newsEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('news-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.newsDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['coverimage','view_details','status','action'])
                ->make(true);
        }
        return view('admin.news.index');
    }

    public function add(){
        return view('admin.news.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author' => 'required',
            'source_url' => 'required',
        ]);

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'source_url' => $input['source_url'],
            'author' => $input['author'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/news/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);


            $destinationPath = public_path('/uploads/news');
            $image->move($destinationPath, $saveData['cover_image']);
        }

        $res = News::create($saveData);
        if ($res){
            toastr()->success('News successfully saved.');
            return redirect()->route('webadmin.newsIndex');
        }

        return redirect()->back()->withInput();
    }
    public function edit($id){
        $data['news'] = News::where('id',$id)->first();
        return view('admin.news.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'cover_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'source_url' => 'required',
            'author' => 'required',
        ]);

        $previousNews = News::where('id',$id)->first();
        if (!$previousNews){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.newsIndex');
        }

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'source_url' => $input['source_url'],
            'author' => $input['author'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('cover_image')){
            $saveData['cover_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/news/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['cover_image']);
            $destinationPath = public_path('/uploads/news');
            $image->move($destinationPath, $saveData['cover_image']);

            if (file_exists(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']))) {
                chmod(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']), 0644);
                unlink(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']));
            }
            if (file_exists(public_path('uploads/news/'.$previousNews['cover_image']))) {
                chmod(public_path('uploads/news/'.$previousNews['cover_image']), 0644);
                unlink(public_path('uploads/news/'.$previousNews['cover_image']));
            }

        }

        $res = $previousNews->update($saveData);
        if ($res){
            toastr()->success('News successfully saved.');
            return redirect()->route('webadmin.newsIndex');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousNews = News::where('id',$id)->first();
        if (!$previousNews){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.newsIndex');
        }

        if (file_exists(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']))) {
            chmod(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']), 0644);
            unlink(public_path('uploads/news/thumbnail/'.$previousNews['cover_image']));
        }
        if (file_exists(public_path('uploads/news/'.$previousNews['cover_image']))) {
            chmod(public_path('uploads/news/'.$previousNews['cover_image']), 0644);
            unlink(public_path('uploads/news/'.$previousNews['cover_image']));
        }
        $res = $previousNews->delete();
        if ($res){
            toastr()->success('News successfully deleted.');
            return redirect()->route('webadmin.newsIndex');
        }

        return redirect()->back()->withInput();
    }
    public function showDatatable()
    {
        $datas = News::orderBy('position','ASC')->get();
        return view('admin.news.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = News::all();

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
