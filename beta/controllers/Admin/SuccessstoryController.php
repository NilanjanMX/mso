<?php

namespace App\Http\Controllers\Admin;

use App\Models\Successstory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class SuccessstoryController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Successstory::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('profile_image', function ($row) {
                    $url=asset("uploads/successstory/thumbnail/$row->profile_image");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
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
                    if(is_permitted('success-story-success-story', 'edit')){
                    $btn = '<a href="'.route('webadmin.successstoryEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('success-story-success-story', 'delete')){
                    $btn .= '<a href="'.route('webadmin.successstoryDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['profile_image','status','action'])
                ->make(true);
        }
        return view('admin.successstory.index');
    }

    public function add(){
        return view('admin.successstory.add');
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'short_content' => 'required',
            'content' => 'required',
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'author_name' => 'required',
            'location' => 'required',
        ]);

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }

        $saveData = [
            'title' => $input['title'],
            'short_content' => $input['short_content'],
            'content' => $input['content'],
            'author_name' => $input['author_name'],
            'location' => $input['location'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('profile_image')){
            $saveData['profile_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/successstory/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['profile_image']);


            $destinationPath = public_path('/uploads/successstory');
            $image->move($destinationPath, $saveData['profile_image']);
        }

        $res = Successstory::create($saveData);
        if ($res){
            toastr()->success('Success Story successfully saved.');
            return redirect()->route('webadmin.successstory');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['successstory'] = Successstory::where('id',$id)->first();
        return view('admin.successstory.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'short_content' => 'required',
            'content' => 'required',
            'author_name' => 'required',
            'location' => 'required',
        ]);

        $previousSuccessstory = Successstory::where('id',$id)->first();
        if (!$previousSuccessstory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.successstory');
        }

        $input = $request->all();
        if(isset($input['created_at']) || !empty($input['created_at'])){
            $publish_date = date("Y-m-d h:i:s", strtotime($input['created_at']));
        }else{
            $publish_date = date("Y-m-d h:i:s");
        }

        $saveData = [
            'title' => $input['title'],
            'short_content' => $input['short_content'],
            'content' => $input['content'],
            'author_name' => $input['author_name'],
            'location' => $input['location'],
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('profile_image')){
            $saveData['profile_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/successstory/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['profile_image']);
            $destinationPath = public_path('/uploads/successstory');
            $image->move($destinationPath, $saveData['profile_image']);

            if (file_exists(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']))) {
                chmod(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']), 0644);
                unlink(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']));
            }
            if (file_exists(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']))) {
                chmod(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']), 0644);
                unlink(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']));
            }

        }

        $res = $previousSuccessstory->update($saveData);
        if ($res){
            toastr()->success('Success Story successfully updated.');
            return redirect()->route('webadmin.successstory');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousSuccessstory = Successstory::where('id',$id)->first();
        if (!$previousSuccessstory){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.successstory');
        }

        if (file_exists(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']))) {
            chmod(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']), 0644);
            unlink(public_path('uploads/successstory/thumbnail/'.$previousSuccessstory['profile_image']));
        }
        if (file_exists(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']))) {
            chmod(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']), 0644);
            unlink(public_path('uploads/successstory/'.$previousSuccessstory['profile_image']));
        }
        $res = $previousSuccessstory->delete();
        if ($res){
            toastr()->success('Success Story successfully deleted.');
            return redirect()->route('webadmin.successstory');
        }

        return redirect()->back()->withInput();
    }
    public function showDatatable()
    {
        $datas = Successstory::orderBy('position','ASC')->get();
        return view('admin.successstory.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Successstory::all();

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
