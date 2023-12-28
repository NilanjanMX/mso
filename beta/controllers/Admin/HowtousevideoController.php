<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Howtousevideo;

class HowtousevideoController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Howtousevideo::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('view_details', function ($row) {
                    if($row->slug !=''){
                        $view_details = '<a href="'.route('frontend.how-to-use-videos-details',['slug'=> $row->slug ]).'" target="_blank">View Details</a>';
                    }else{
                        $view_details = '';
                    }
                    return $view_details;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('how-to-use-video-videos', 'edit')){
                    $btn = '<a href="'.route('webadmin.howtousevideoEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('how-to-use-video-videos', 'delete')){
                    $btn .= '<a href="'.route('webadmin.howtousevideoDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['view_details','status','action'])
                ->make(true);
        }
        return view('admin.howtousevideo.index');
    }

    public function add(){
        return view('admin.howtousevideo.add');
    }

    public function save(Request $request){
        $input = $request->all();
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'youtube_url' => 'required'
        ]);

        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'youtube_url' => $input['youtube_url'],
            'is_active' => isset($input['status'])?1:0
        ];
       

        $res = Howtousevideo::create($saveData);
        if ($res){
            toastr()->success('How to use video successfully saved.');
            return redirect()->route('webadmin.howtousevideoIndex');
        }
        return redirect()->back()->withInput();

    }

    public function edit($id){
        $data['video_details'] = Howtousevideo::where('id',$id)->first();
        return view('admin.howtousevideo.edit',$data);
    }

    public function update(Request $request,$id){
        $input = $request->all();
        
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'youtube_url' => 'required'
        ]);

        $previousVideo = Howtousevideo::where('id',$id)->first();
        

        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'youtube_url' => $input['youtube_url'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = $previousVideo->update($saveData);
        if ($res){
            toastr()->success('Video successfully updated.');
            return redirect()->route('webadmin.howtousevideoIndex');
        }
        return redirect()->back()->withInput();
    }

    public function delete($id){
        $previousVideo = Howtousevideo::where('id',$id)->first();
        if (!$previousVideo){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->route('webadmin.howtousevideoIndex');
        }

        $res = $previousVideo->delete();
        if ($res){
            toastr()->success('Video successfully deleted.');
            return redirect()->route('webadmin.howtousevideoIndex');
        }
        return redirect()->route('webadmin.howtousevideoIndex');
    }

    public function showDatatable()
    {
        $datas = Howtousevideo::orderBy('position','ASC')->get();
        return view('admin.howtousevideo.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Howtousevideo::all();

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
