<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Webinar;

class WebinarController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Webinar::orderBy('position','ASC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('webinar_image', function ($row) {
                    if(empty($row->webinar_image)){
                        return '';
                    }else{
                        $url=asset("uploads/webinar/thumbnail/$row->webinar_image");
                        return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                    }
                    
                })
                ->addColumn('view_details', function ($row) {
                        $view_details = '<a href="'.url('webinars').'/'.$row->slug.'" target="_blank"><p style="font-weight: 700;color: #0d4988;">View Details</p></a>';
                    return $view_details;
                })
                ->addColumn('add_to_cart_status', function ($row) {
                    if($row->add_to_cart_status == 1){
                        $add_to_cart_status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $add_to_cart_status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $add_to_cart_status;
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
                    if(is_permitted('webinars-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.webinarEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('webinars-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.webinarDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['webinar_image','view_details','add_to_cart_status','status','action'])
                ->make(true);
        }
        return view('admin.webinar.index');
    }

    public function add(){
        return view('admin.webinar.add');
    }

    public function save(Request $request){
        
        $request->validate([
            'title' => 'required',
            'webinar_image' => 'image|mimes:jpeg,png,jpg,gif,svg'
        ]);

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'day' => $input['day'],
            'webinar_date_time' => $input['webinar_date_time'],
            'webinar_time' => $input['webinar_time'],
            'duration' => $input['duration'],
            //'free_or_paid_status' => $input['free_or_paid'],
            'add_to_cart_status' => isset($input['add_to_cart_status'])?1:0,
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];
        /*if($input['free_or_paid'] == 1){
            $saveData['amount'] = $input['amount'];
        }else{
            $saveData['amount'] = 0;
        }*/

        if ($image = $request->file('webinar_image')){
            $saveData['webinar_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/webinar/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(233, 182, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['webinar_image']);


            $destinationPath = public_path('/uploads/webinar');
            $image->move($destinationPath, $saveData['webinar_image']);
        }

        $res = Webinar::create($saveData);
        if ($res){
            toastr()->success('Webinar successfully saved.');
            return redirect()->route('webadmin.webinars');
        }

        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['webinar'] = Webinar::where('id',$id)->first();
        return view('admin.webinar.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required'
        ]);

        $previousStationary = Webinar::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.webinars');
        }

        $input = $request->all();
        $saveData = [
            'title' => $input['title'],
            'content' => $input['content'],
            'day' => $input['day'],
            'webinar_date_time' => $input['webinar_date_time'],
            'webinar_time' => $input['webinar_time'],
            'duration' => $input['duration'],
            //'free_or_paid_status' => $input['free_or_paid'],
            'add_to_cart_status' => isset($input['add_to_cart_status'])?1:0,
            'redirect_url' => $input['redirect_url'],
            'is_active' => isset($input['status'])?1:0
        ];
        /*if($input['free_or_paid'] == 1){
            $saveData['amount'] = $input['amount'];
        }else{
            $saveData['amount'] = 0;
        }*/

        if ($image = $request->file('webinar_image')){
            $saveData['webinar_image'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/webinar/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(233, 182, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['webinar_image']);
            $destinationPath = public_path('/uploads/webinar');
            $image->move($destinationPath, $saveData['webinar_image']);
            if(!empty($previousStationary['webinar_image'])){

                if (file_exists(public_path('uploads/webinar/thumbnail/'.$previousStationary['webinar_image']))) {
                    //chmod(public_path('uploads/webinar/thumbnail/'.$previousStationary['webinar_image']), 0644);
                    unlink(public_path('uploads/webinar/thumbnail/'.$previousStationary['webinar_image']));
                }
                if (file_exists(public_path('uploads/webinar/'.$previousStationary['webinar_image']))) {
                    //chmod(public_path('uploads/webinar/'.$previousStationary['webinar_image']), 0644);
                    unlink(public_path('uploads/webinar/'.$previousStationary['webinar_image']));
                }

            }
                

        }

        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Webinar successfully updated.');
            return redirect()->route('webadmin.webinars');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousStationary = Webinar::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.webinars');
        }
        if(!empty($previousStationary['webinar_image'])){
            if (file_exists(public_path('uploads/webinar/thumbnail/'.$previousStationary['webinar_image']))) {
                //chmod(public_path('uploads/stationary/thumbnail/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/webinar/thumbnail/'.$previousStationary['webinar_image']));
            }
            if (file_exists(public_path('uploads/webinar/'.$previousStationary['webinar_image']))) {
                //chmod(public_path('uploads/stationary/'.$previousStationary['product_image']), 0644);
                unlink(public_path('uploads/webinar/'.$previousStationary['webinar_image']));
            }
        }
        
        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Webinar successfully deleted.');
            return redirect()->route('webadmin.webinars');
        }

        return redirect()->back()->withInput();
    }
    
    public function showDatatable()
    {
        $datas = Webinar::orderBy('position','ASC')->get();
        return view('admin.webinar.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Webinar::all();

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
