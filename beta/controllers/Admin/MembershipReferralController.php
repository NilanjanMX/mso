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

class MembershipReferralController extends Controller
{

    public function list(Request $request){

        if ($request->ajax()) {
            $data = DB::table('referral_links')->select(["referral_links.*","users.name as username"])->LeftJoin('users', 'users.id', '=', 'referral_links.user_id')->latest()->get();
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
                    if((strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($row->expire_at))))){
                        if(!empty($row->is_used)){
                            $created_at = "Subscribed";
                        }
                    }else{
                        $created_at = "Link Expired";
                    }
                        

                    return $created_at;
                })
                ->rawColumns(['created_at','status'])
                ->make(true);
        }
        return view('admin.membership_referral.list');
    }



    public function setting(Request $request){
        $data = [];
        $data['list'] = MembershipReferralSetting::latest()->get();
        return view('admin.membership_referral.setting',$data);
    }

    public function settingedit($id){
        $data = [];
        $data['detail'] = MembershipReferralSetting::where('id',$id)->first();
        return view('admin.membership_referral.setting_edit',$data);
    }
    
    public function settingupdate(Request $request){
        $request->validate([
            'value' => 'required'
        ]);

        $input = $request->all();
        $detail = MembershipReferralSetting::where('id',$input['id'])->first();
        $saveData = [
            'value' => $input['value'],
            'type_name' => $input['type_name'],
        ];
        
        $detail->update($saveData);
        if ($detail){
            toastr()->success('Membership Referral setting successfully updated.');
            return redirect()->route('webadmin.membership_referral.setting');
        }

        return redirect()->back()->withInput();
    }

}
