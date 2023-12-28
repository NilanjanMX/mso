<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Models\ReferralCode;
use App\Models\AdminAuth;
use App\Models\MembershipReferralSetting;
use DB;

class MembershipModuleController extends Controller
{

    public function trial_taken(Request $request){

        if ($request->ajax()) {
            $data = DB::table('memberships')->select(["memberships.*","users.name as username","users.email as useremail","users.phone_no as phone_no"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->where('memberships.is_active','1')->where('memberships.package_id','15')->whereNotNull('users.email')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y h:i',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        return view('admin.membership_module.trial_taken');
    }

    public function upgrade_to_premium(Request $request){

        if ($request->ajax()) {
            $data = DB::table('memberships')->select(["memberships.*","users.name as username","users.email as useremail","users.phone_no as phone_no"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->where('memberships.is_active','1')->where('memberships.type','1')->whereNotNull('users.email')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y h:i',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        return view('admin.membership_module.upgrade_to_premium');
    }

    public function downgrade_to_basic(Request $request){

        if ($request->ajax()) {
            $data = DB::table('memberships')->select(["memberships.*","users.name as username","users.email as useremail","users.phone_no as phone_no"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->where('memberships.is_active','1')->where('memberships.type','3')->whereNotNull('users.email')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y h:i',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        return view('admin.membership_module.downgrade_to_basic');
    }

    public function discontinued(Request $request){

        if ($request->ajax()) {
            $date=date('Y-m-d');
            $data = DB::table('memberships')->select(["memberships.*","users.name as username","users.email as useremail","users.phone_no as phone_no"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->where('memberships.is_active','1')->where('memberships.expire_at','<',$date)->whereNotNull('users.email')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y h:i',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->addColumn('expire_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->expire_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at','expire_at'])
                ->make(true);
        }
        return view('admin.membership_module.discontinued');
    }

    public function new_sub_user(Request $request){

        if ($request->ajax()) {
            $data = DB::table('memberships')->select(["memberships.*","users.name as username","users.email as useremail","users.phone_no as phone_no"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->where('memberships.is_active','1')->whereNotNull('users.user_id')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('status', function ($row) {
                    $created_at = "Unsubscribed";
                    if(!empty($row->is_used)){
                        $created_at = "Subscribed";
                    }

                    return $created_at;
                })
                ->rawColumns(['created_at','status'])
                ->make(true);
        }
        return view('admin.membership_module.new_sub_user');
    }


}
