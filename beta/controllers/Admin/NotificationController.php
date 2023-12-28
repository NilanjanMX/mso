<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use App\Models\UserToNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationGroup;
use App\Models\NotificationSetting;
use App\Models\PackageCreationDropdown;
use App\User;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class NotificationController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $data = Notification::orderBy('created_at','DESC')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    if(!empty($row->image)){
                        $url=asset("uploads/salespresentersoftcopy/$row->image");
                    }else{
                        $url=asset("uploads/salespresentersoftcopy/defaultnotification.jpg");
                    }
                    
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
                        $status = '<p style="color: #6fdc2b;font-weight: 700;">Active</p>';
                    }else{
                        $status = '<p style="color: #de102d;font-weight: 700;">Inactive</p>';
                    }

                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('notification-index', 'edit')){
                    $btn = '<a href="'.route('webadmin.notificationEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('notification-index', 'delete')){
                    $btn .= '<a href="'.route('webadmin.notificationDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->escapeColumns('aaData')
                ->rawColumns(['image','status','action'])
                ->make(true);
        }
        return view('admin.notification.index');
    }

    public function add(){
        $userType = PackageCreationDropdown::select('id','name')->get();
                // dd($userType);
        $table = '';
        if (count($userType)>0){
            foreach ($userType as $group){
                if(!empty($group->name)){
                    $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                }
            }
        }
        // dd($table);
        $data['table'] = $table;
        return view('admin.notification.add', $data);
    }

    public function save(Request $request){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'required',
            'send_to' => 'required',
            // 'send_to_ids' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);

        $input = $request->all();
        
        $publish_date = date("Y-m-d h:i:s");
        $send_to_ids='';
        if(isset($input['send_to_ids'])){
            foreach ($input['send_to_ids'] as $key=>$value){
                if($key+1 < $input['send_to_ids']){
                    $send_to_ids .=  $value.',';
                }
            }
        }
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'url' => $input['url'],
            'send_to' => $input['send_to'],
            'user_status' => $input['user_status'],
            'send_to_ids' => $send_to_ids,
            'created_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['image'] = $file;

            $destinationPath = public_path('/uploads/salespresentersoftcopy');
            $image->move($destinationPath, $file);
            
        }

        $res = Notification::create($saveData);
        if ($res){
            toastr()->success('Notification successfully saved.');
            return redirect()->route('webadmin.notification');
        }

        return redirect()->back()->withInput();
    }
    public function edit($id){
        $data['notification'] = Notification::where('id',$id)->first();

        if(!empty($data['notification']) && $data['notification']->send_to != '' || $data['notification']->send_to != 'all' ){
            $ids = explode(',', $data['notification']->send_to_ids);
            $table = '';
            if($data['notification']->send_to == 'group'){
                $groups = NotificationGroup::latest()->get();
                
                if (count($groups)>0){
                    foreach ($groups as $group){
                        if(in_array($group->id, $ids)){
                            $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                        }else{
                            $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                        }
                    }
                }
                $data['ph'] = 'Select Group(s)';
            }elseif($data['notification']->send_to == 'user_type'){
                $userType = PackageCreationDropdown::select('id','name')->get();
                // dd($userType);
                $table = '';
                if (count($userType)>0){
                    foreach ($userType as $group){
                        if(!empty($group->name)){
                            if(in_array($group->id, $ids)){
                                $table .= '<option value="'.$group->id.'" selected>'.$group->name.'</option>';
                            }else{
                                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
                            }
                        }
                    }
                }
                $data['ph'] = 'Select User Type(s)';
                // dd($table);
            }
            $data['options'] = $table;
            
        }
        // dd($data['notification']->send_to);
        
        return view('admin.notification.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'url' => 'required',
            'send_to' => 'required',
            // 'send_to_ids' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg',
        ]);
        // dd('d');
        $previousNews = Notification::where('id',$id)->first();
        if (!$previousNews){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.notification');
        }

        $input = $request->all();
        $publish_date = date("Y-m-d h:i:s");
        $send_to_ids='';

        if(isset($input['send_to_ids'])){
            foreach ($input['send_to_ids'] as $value){
                // if($key+1 < $input['send_to_ids']){
                    $send_to_ids .=  $value.',';
                // }
            }
        }else{
            $send_to_ids = null;
        }
        
        $saveData = [
            'title' => $input['title'],
            'description' => $input['description'],
            'url' => $input['url'],
            'send_to' => $input['send_to'],
            'user_status' => $input['user_status'],
            'send_to_ids' => $send_to_ids,
            'updated_at' => $publish_date,
            'is_active' => isset($input['status'])?1:0
        ];

        if ($image = $request->file('image')){
            $file = time().'.'.$image->getClientOriginalExtension();
            $saveData['image'] = $file;

            $destinationPath = public_path('/uploads/salespresentersoftcopy');
            $image->move($destinationPath, $file);
            if(!empty($previousNews['image'])){
                if (file_exists(public_path('uploads/salespresentersoftcopy/'.$previousNews['image']))) {
                    //chmod(public_path('uploads/salespresentersoftcopy/'.$previousBlog['image']), 0644);
                    unlink(public_path('uploads/salespresentersoftcopy/'.$previousNews['image']));
                }
            }
        }

        $res = $previousNews->update($saveData);
        if ($res){
            toastr()->success('Notification successfully saved.');
            return redirect()->route('webadmin.notification');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousNews = Notification::where('id',$id)->first();
        if (!$previousNews){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.notification');
        }

        
        $res = $previousNews->delete();
        if ($res){
            toastr()->success('Notification successfully deleted.');
            return redirect()->route('webadmin.notification');
        }

        return redirect()->back()->withInput();
    }
    public function showDatatable()
    {
        $datas = Notification::orderBy('created_at','DESC')->get();
        return view('admin.notification.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = Notification::all();

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

    public function readNotification(Request $request)
    {
        $datas = [
            'user_id' => Auth::id(),
            'notification_id' => $request->id,
            'status' => 1
        ];

        UserToNotification::create($datas);

        return 1;
    }

    public function resetNotificationCount(Request $request)
    {
        $ids = explode(',', $request->id);
        foreach($ids as $id){
            if(!empty($id)){
                $datas = [
                    'user_id' => Auth::id(),
                    'notification_id' => $id,
                    'status' => 2
                ];
                UserToNotification::create($datas);
            }
            
        }

        return 1;
    }
    
    public function hidePopups(Request $request)
    {
        // session()->get('hide_popups');
        session()->put('hide_popups',1);
        
        return 1;
    }

    public function setting(Request $request)
    {
        $data['settings'] = NotificationSetting::get();

        $input = $request->all();
        if(!empty($input)){

            // dd($input['expiry_period']);
            foreach($data['settings'] as $val){
                $key = $val->key;
                $val->value = $input[$key];
                $val->save();
                // dd($input[$key]);
            }
            // dd();
        }

        return view('admin.notification.setting',$data);
    }

   
}
