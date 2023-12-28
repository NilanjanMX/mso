<?php

namespace App\Http\Controllers\Admin;

use App\Models\PreviousWebinar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class PreviousWebinarController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = PreviousWebinar::orderBy('position','ASC')->get();
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
                    // previous-webinar-index
                    $btn = '';
                    if(is_permitted('previous-webinar-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.previous_webinarEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('previous-webinar-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.previous_webinarDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['view_details','type','action'])
                ->make(true);
        }
        return view('admin.previous_webinar.index');
    }

    public function add(){
        return view('admin.previous_webinar.add');
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
            $destinationPath = 'uploads/previous_webinar';
            $video->move($destinationPath, $video_name);
            $saveData['video'] = $video_name;
        }

        if ($image = $request->file('video_cover_image')){
            $saveData['video_cover_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/previous_webinar/cover/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['video_cover_image']);
            $destinationPath = public_path('/uploads/previous_webinar/cover');
            $image->move($destinationPath, $saveData['video_cover_image']);
        }

        $res = PreviousWebinar::create($saveData);
        if ($res){
            toastr()->success('Previous Webinar successfully saved.');
            return $res;
        }
    }

    public function edit($id){
        $data['video_details'] = PreviousWebinar::where('id',$id)->first();
        return view('admin.previous_webinar.edit',$data);
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

        $previousVideo = PreviousWebinar::where('id',$id)->first();
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
            $destinationPath = 'uploads/previous_webinar';
            $video->move($destinationPath, $video_name);
            $saveData['video'] = $video_name;
            if (file_exists(public_path('uploads/previous_webinar/'.$previousVideo['video']))) {
                chmod(public_path('uploads/previous_webinar/'.$previousVideo['video']), 0644);
                unlink(public_path('uploads/previous_webinar/'.$previousVideo['video']));
            }
        }

        if ($image = $request->file('video_cover_image')){
            $saveData['video_cover_image'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/previous_webinar/cover/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['video_cover_image']);
            $destinationPath = public_path('/uploads/previous_webinar/cover');
            $image->move($destinationPath, $saveData['video_cover_image']);

            if (file_exists(public_path('uploads/previous_webinar/cover/thumbnail/'.$previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/previous_webinar/cover/thumbnail/'.$previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/previous_webinar/cover/thumbnail/'.$previousVideo['video_cover_image']));
            }
            if (file_exists(public_path('uploads/previous_webinar/cover/'.$previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/previous_webinar/cover/'.$previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/previous_webinar/cover/'.$previousVideo['video_cover_image']));
            }
        }

        $res = $previousVideo->update($saveData);
        if ($res){
            toastr()->success('Previous Webinar successfully updated.');
            return "true";
        }
    }

    public function delete($id){
        $previousVideo = PreviousWebinar::where('id',$id)->first();
        if (!$previousVideo){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.previous_webinarIndex');
        }

        if ($previousVideo['is_payable']==1) {
            if (file_exists(public_path('uploads/previous_webinar/cover/thumbnail/' . $previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/previous_webinar/cover/thumbnail/' . $previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/previous_webinar/cover/thumbnail/' . $previousVideo['video_cover_image']));
            }
            if (file_exists(public_path('uploads/previous_webinar/cover/' . $previousVideo['video_cover_image']))) {
                chmod(public_path('uploads/previous_webinar/cover/' . $previousVideo['video_cover_image']), 0644);
                unlink(public_path('uploads/previous_webinar/cover/' . $previousVideo['video_cover_image']));
            }

            if (file_exists(public_path('uploads/previous_webinar/' . $previousVideo['video']))) {
                chmod(public_path('uploads/previous_webinar/' . $previousVideo['video']), 0644);
                unlink(public_path('uploads/previous_webinar/' . $previousVideo['video']));
            }
        }
        $res = $previousVideo->delete();
        if ($res){
            toastr()->success('Previous Webinar successfully deleted.');
            return redirect()->route('webadmin.previous_webinarIndex');
        }
    }

    public function showDatatable()
    {
        $datas = PreviousWebinar::orderBy('position','ASC')->get();
        return view('admin.previous_webinar.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = PreviousWebinar::all();

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
