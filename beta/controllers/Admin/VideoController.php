<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class VideoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Video::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('type', function($row){
                    return ($row->is_payable==1)?'Paid':'Free';
                })
                ->addColumn('view_details', function ($row) {
                        if($row->is_payable==1){
                            $view_details = '';
                        }else{
                            $view_details = '<a href="'.url('short-video').'/'.$row->slug.'" target="_blank"><p style="font-weight: 700;color: #0d4988;">View Details</p></a>';
                        }
                    return $view_details;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    // home-videos
                    $btn = '';
                    if(is_permitted('home-videos', 'edit')){
                    $btn = '<a href="'.route('webadmin.videoEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('home-videos', 'delete')){
                    $btn .= '<a href="'.route('webadmin.videoDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['view_details','type','action'])
                ->make(true);
        }
        return view('admin.video.index');
    }

    public function add(){
        return view('admin.video.add');
    }

    public function save(Request $request){
        $input = $request->all();
        if ($input['video_type']==1){
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'video_cover_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
                'video' => 'required|mimes:mp4,mov,ogg|max:100000',
                'price' => 'required'
            ]);
        }else{
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'youtube_url' => 'required'
            ]);
        }

        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'is_payable' => $input['video_type'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($input['video_type']==0){
            //$youtube_embed_url = preg_replace("/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","<iframe width=\"420\" height=\"315\" src=\"//www.youtube.com/embed/$1\" frameborder=\"0\" allowfullscreen></iframe>",$input['youtube_url']);
            $saveData['youtube_url'] = $input['youtube_url'];
        }else{
            $saveData['price'] = $input['price'];
        }

        if ($video = $request->file('video')){
            $video_name = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = 'uploads/videos';
            $video->move($destinationPath, $video_name);
            $saveData['video'] = $video_name;
        }

        if ($image = $request->file('video_cover_image')){
            $saveData['video_cover_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/videos/cover/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['video_cover_image']);
            $destinationPath = public_path('/uploads/videos/cover');
            $image->move($destinationPath, $saveData['video_cover_image']);
        }

        $res = Video::create($saveData);
        if ($res){
            toastr()->success('Video successfully saved.');
            return $res;
        }
    }

    public function edit($id){
        $data['video_details'] = Video::where('id',$id)->first();
        return view('admin.video.edit',$data);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        if ($input['video_type']==1){
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'video_cover_image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif,svg',
                'video' => 'sometimes|nullable|mimes:mp4,mov,ogg|max:100000',
                'price' => 'required'
            ]);
        }else{
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'youtube_url' => 'required'
            ]);
        }

        $previousVideo = Video::where('id',$id)->first();
        if (!$previousVideo){
            toastr()->warning('Something went wrong, please try again later.');
            return "true";
        }

        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'is_payable' => $input['video_type'],
            'is_active' => isset($input['status'])?1:0
        ];

        if ($input['video_type']==0){
            $saveData['youtube_url'] = $input['youtube_url'];
        }else{
            $saveData['price'] = $input['price'];
        }

        if ($video = $request->file('video')){
            $video_name = time().'.'.$video->getClientOriginalExtension();
            $destinationPath = 'uploads/videos';
            $video->move($destinationPath, $video_name);
            $saveData['video'] = $video_name;
            if (file_exists(public_path('uploads/videos/'.$previousVideo['video']))) {
                chmod(public_path('uploads/videos/'.$previousVideo['video']), 0644);
                unlink(public_path('uploads/videos/'.$previousVideo['video']));
            }
        }

        if ($image = $request->file('video_cover_image')){
            $saveData['video_cover_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/videos/cover/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['video_cover_image']);
            $destinationPath = public_path('/uploads/videos/cover');
            $image->move($destinationPath, $saveData['video_cover_image']);

            if (file_exists(public_path('uploads/videos/cover/thumbnail/'.$previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/videos/cover/thumbnail/'.$previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/videos/cover/thumbnail/'.$previousVideo['video_cover_image']));
            }
            if (file_exists(public_path('uploads/videos/cover/'.$previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/videos/cover/'.$previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/videos/cover/'.$previousVideo['video_cover_image']));
            }
        }

        $res = $previousVideo->update($saveData);
        if ($res){
            toastr()->success('Video successfully updated.');
            return "true";
        }
    }

    public function delete($id){
        $previousVideo = Video::where('id',$id)->first();
        if (!$previousVideo){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.videoIndex');
        }

        if ($previousVideo['is_payable']==1) {
            if (file_exists(public_path('uploads/videos/cover/thumbnail/' . $previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/videos/cover/thumbnail/' . $previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/videos/cover/thumbnail/' . $previousVideo['video_cover_image']));
            }
            if (file_exists(public_path('uploads/videos/cover/' . $previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/videos/cover/' . $previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/videos/cover/' . $previousVideo['video_cover_image']));
            }

            if (file_exists(public_path('uploads/videos/' . $previousVideo['video']))) {
                chmod(public_path('uploads/videos/' . $previousVideo['video']), 0644);
                unlink(public_path('uploads/videos/' . $previousVideo['video']));
            }
        }
        $res = $previousVideo->delete();
        if ($res){
            toastr()->success('Video successfully deleted.');
            return redirect()->route('webadmin.videoIndex');
        }
    }

    public function showDatatable()
    {
        $datas = Video::orderBy('position','ASC')->get();
        return view('admin.video.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Video::all();

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
