<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\User;
use App\Models\ReferralCode;
use App\Models\AdminAuth;
use DB;

class RewardPointController extends Controller
{

    public function dashboard(Request $request){

        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['date_type'] = $request->date_type;
        
        if($data['date_type']){
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $current_date = $year.'/'.$month.'/'.$day;
            
            $month = $month - $data['date_type'];
            
            if($month < 1){
                $year = $year -1;
                $month = 12 + $month;
            }
            $pre_date = $year.'/'.$month.'/'.$day;
            
            $ustart_date = date('Y-m-d', strtotime($current_date));
            $uend_date = date('Y-m-d', strtotime($pre_date));

            $from = $uend_date." 00:00:01";
            $to = $ustart_date."  23:59:59";
            
            $data['total_points_issued'] = ReferralCode::where("is_paid","=",0)->whereBetween('created_at', [$from, $to])->sum('referral_amount');
            $data['claim_points_issued'] = ReferralCode::where("is_paid","=",1)->whereBetween('created_at', [$from, $to])->sum('referral_amount');
            
        }else{
            // $data['users'] = User::get();
            if($data['date_from'] && $data['date_to']){
    
                $date_from = explode('/', $data['date_from']);
                $date_to = explode('/', $data['date_to']);
    
                $from = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $to = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";
                
                $data['total_points_issued'] = ReferralCode::where("is_paid","=",0)->whereBetween('created_at', [$from, $to])->sum('referral_amount');
                $data['claim_points_issued'] = ReferralCode::where("is_paid","=",1)->whereBetween('created_at', [$from, $to])->sum('referral_amount');
            }else{
                $data['total_points_issued'] = ReferralCode::where("is_paid","=",0)->sum('referral_amount');
                $data['claim_points_issued'] = ReferralCode::where("is_paid","=",1)->sum('referral_amount');
            }
        }
            
        
        return view('admin.reward_point.dashboard',$data);
    }

    public function total_points_not_claimed(Request $request){
        $data = [];
        $data['user_id'] = $request->user_id;
        if($data['user_id']){
            $data['reports'] = ReferralCode::select(["referral_codes.*","users.name"])->LeftJoin('users', 'users.id', '=', 'referral_codes.user_id')->orderBy('referral_codes.created_at','ASC')->where('referral_codes.user_id',$data['user_id'])->get();
        }else{
            $data['reports'] = [];
        }
        
        $data['users'] = User::get();
        return view('admin.reward_point.total_points_not_claimed',$data);
    }

    public function admin_user(Request $request){
        $data = [];
        $data['user_id'] = $request->user_id;
        if($data['user_id']){
            $data['reports'] = ReferralCode::select(["referral_codes.*","users.name","users.email","admin_auths.name as admin_name"])->LeftJoin('users', 'users.id', '=', 'referral_codes.user_id')->LeftJoin('admin_auths', 'admin_auths.id', '=', 'referral_codes.is_from_admin')->orderBy('referral_codes.created_at','ASC')->where('referral_codes.is_from_admin',$data['user_id'])->get();
        }else{
            $data['reports'] = [];
        }
        
        $data['users'] = AdminAuth::where('is_super',0)->get();
        return view('admin.reward_point.admin_user',$data);
    }

    public function claim_point(Request $request){
        $data = [];
        $data['user_id'] = $request->user_id;
        $data['type'] = $request->type;

        $data['reports'] = ReferralCode::select(["referral_codes.*","users.name","users.email"])->LeftJoin('users', 'users.id', '=', 'referral_codes.user_id')->orderBy('referral_codes.created_at','ASC');

        if($data['user_id']){
            $data['reports'] = $data['reports']->where('referral_codes.user_id',$data['user_id']);
        }

        if($data['type'] == "Store"){
            $data['reports'] = $data['reports']->where('note','Store Purchase');
        }else if($data['type'] == "Subscription Update"){
            $data['reports'] = $data['reports']->where('note','Subscription Update');
        }else if($data['type'] == "Subscription Renewal"){
            $data['reports'] = $data['reports']->where('note','Subscription Renewal');
        }else if($data['type'] == "Membership Renewal"){
            $data['reports'] = $data['reports']->where('note','Membership');
        }else if($data['type'] == "New Membership"){
            $data['reports'] = $data['reports']->where('note','Registration');
        }else if($data['type'] == "Manual Issue"){
            $data['reports'] = $data['reports']->whereNotIn('note',['Registration','Store Purchase','Membership','Subscription Renewal','Subscription Update']);
        }
        $data['reports'] = $data['reports']->get();
        
        $data['users'] = User::get();
        return view('admin.reward_point.claim_point',$data);
    }

    public function add(){
        return view('admin.become_a_member.add');
    }

    public function save(Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();



        $data = [
            'name' => $input['name'],
            'hint' => $input['hint'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = BecomeAMember::create($data);
        if ($res){
            toastr()->success('Type successfully created.');
            return redirect()->route('webadmin.become_a_member');
        }
        return redirect()->back()->withInput();
    }

    public function edit($id){
        $data['detail'] = BecomeAMember::where('id',$id)->first();
        return view('admin.become_a_member.edit',$data);
    }

    public function update(Request $request){

        
        $request->validate([
            'name' => 'required',
        ]);

        $input = $request->all();

        $id = $request->id;

        // dd($input);

        $previousGallery = BecomeAMember::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.become_a_member');
        }

        $data = [
            'name' => $input['name'],
            'hint' => $input['hint'],
            'description' => $input['description'],
            'is_active' => (isset($input['status']))?1:0
        ];

        $res = BecomeAMember::where('id',$id)->update($data);
        if ($res){
            toastr()->success('Type successfully updated.');
            return redirect()->route('webadmin.become_a_member');
        }
        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $previousGallery = BecomeAMember::where('id',$id)->first();
        if (!$previousGallery){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.become_a_member');
        }

        $res = $previousGallery->delete();
        if ($res){
            toastr()->success('Type successfully deleted.');
            return redirect()->route('webadmin.become_a_member');
        }

        return redirect()->back()->withInput();
    }

    public function reorder()
    {
        $datas = BecomeAMember::orderBy('position','ASC')->get();
        return view('admin.become_a_member.reorder',compact('datas'));
    }

    public function updateOrder(Request $request)
    {
        $datas = BecomeAMember::all();



        foreach ($datas as $data) {
            $data->timestamps = false; // To disable update_at field updation
            $id = $data->id;

            foreach ($request->order as $order) {
                if ($order['id'] == $id) {
                    BecomeAMember::where('id',$id)->update(['position' => $order['position']]);
                }
            }
        }
        
        return response('Update Successfully.', 200);
    }

}
