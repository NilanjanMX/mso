<?php

namespace App\Http\Controllers\Admin;

use App\Models\Displayinfo;
use App\Models\NotificationGroup as NotificationGroupModel;
use App\Models\NotificationGroupUser;
use App\Models\Membership;
use App\Models\PackageCreationDropdown;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

class NotificationGroup extends Controller
{
    public function index(Request $request){
        $data['users'] = User::latest()->get();
        return view('admin.notification_group.index',$data);
    }

    public function saveGroup(Request $request)
    {
        $input = $request->all();
        $groupInfo = NotificationGroupModel::create([
            'name' => $input['group_name']
        ]);
        if ($groupInfo){
            foreach ($input['users'] as $user){
                NotificationGroupUser::create([
                    'group_id' => $groupInfo->id,
                    'user_id' => $user
                ]);
            }
        }
    }

    public function groupIndex(Request $request){
        if ($request->ajax()) {
            $data = NotificationGroupModel::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('group_count', function($row){
                    return $row->groupUsers()->count();
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('notification-group-list', 'view')){
                    $btn = '<a href="'.route('webadmin.notificationgroupUserindex',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">View</a>';
                    }
                    if(is_permitted('notification-group-list', 'delete')){
                    $btn .= '<a href="'.route('webadmin.notificationRemoveGroup',['id'=> $row->id ]).'" class="edit btn btn-danger btn-sm mr-1" onclick="return confirm(\'Are you sure?\')">Delete</a>';
                    }
                    return $btn;
                    
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.notification_group.groupindex');
    }

    public function groupUserindex(Request $request,$id){
        if ($request->ajax()) {

            $data = NotificationGroupUser::where('group_id',$request->group_id)->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="checkbox" name="groupuser[]" value="'.$row->id.'" />';
                })
                ->addColumn('name', function ($row) {
                    return $row->userDetails['name'];
                })
                ->addColumn('email', function ($row) {
                    return $row->userDetails['email'];
                })
                ->addColumn('phone_no', function ($row) {
                    return $row->userDetails['phone_no'];
                })
                ->rawColumns(['checkbox'])
                ->make(true);
        }
        $data['groupinfo'] = NotificationGroupModel::where('id',$id)->first();
        return view('admin.notification_group.groupuserindex',$data);
    }

    public function removeGroupUser(Request $request)
    {
        $input = $request->all();

        if (count($input['users'])>0){
            foreach ($input['users'] as $user){
                $info = NotificationGroupUser::where('id',$user)->first();
                if ($info){
                    $info->delete();
                }
            }
        }
    }

    public function removeGroup(Request $request)
    {
        $id = $request->id;
        // dd($id);
        NotificationGroupModel::where('id',$id)->delete();
        NotificationGroupUser::where('group_id',$id)->delete();
        
        toastr()->success('Notification successfully deleted.');
        return redirect()->route('webadmin.notificationgroupIndex');
        
    }

    public function getNotificationGroups()
    {
        $data = NotificationGroupModel::latest()->get();

        $table = '';
        if (count($data)>0){
            foreach ($data as $group){
                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }

        return $table;
    }
    
    
    public function getNotificationUsertype()
    {
        $data = PackageCreationDropdown::latest()->get();

        $table = '';
        if (count($data)>0){
            foreach ($data as $group){
                $table .= '<option value="'.$group->id.'">'.$group->name.'</option>';
            }
        }

        return $table;
    }



}
