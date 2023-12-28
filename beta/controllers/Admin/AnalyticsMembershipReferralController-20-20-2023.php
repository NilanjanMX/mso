<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\ReferralCode;
use App\Models\Order;
use App\Models\HistoryLibrary;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsMembershipReferralController extends Controller
{

    public function membership_referral_dashboard(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        // $data['users'] = User::get();
        $current_date = date('Y-m-d');
        if($data['date_from'] && $data['date_to']){
            $ustart_date = date('Y-m-d', strtotime($data['date_from']));
            $uend_date = date('Y-m-d', strtotime($data['date_to']));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['no_of_membership_referred'] = DB::table("referral_links")->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_referrals_converted'] = DB::table("referral_links")->where("is_used",1)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
        }else{
            $data['no_of_membership_referred'] = DB::table("referral_links")->where("is_active","=",1)->count();
            $data['no_of_referrals_converted'] = DB::table("referral_links")->where("is_used",1)->where("is_active","=",1)->count();
        }
        return view('admin.analytics.membership_referral.dashdord',$data);
    }

    public function membership_referral_list(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("referral_links")->select(['referral_links.*','users.email','users.name as user_name'])->leftJoin('users', 'referral_links.user_id', '=', 'users.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('referral_links.created_at', [$ustart_date , $uend_date]);
        }

        $data['reports'] = $data['reports']->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_membership_referral_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'IP', 'Referee Phone', 'Member Email', 'Created'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->user_name, $row->ip, $row->phone_number, $row->email, $row->created_at));   
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }

        // dd($data['reports']);
        return view('admin.analytics.membership_referral.membership_referral_list',$data);
    }
    

    public function membership_points_dashboard(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['date_type'] = $request->date_type;
        // $data['users'] = User::get();
        if($data['date_type']){
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $current_date = $month.'/'.$day.'/'.$year;
            
            $month = $month - $data['date_type'];
            
            if($month < 1){
                $year = $year -1;
            }
            $pre_date = $month.'/'.$day.'/'.$year;
            

            $ustart_date = date('Y-m-d', strtotime($current_date));
            $uend_date = date('Y-m-d', strtotime($pre_date));

            $from = $uend_date." 00:00:01";
            $to = $ustart_date."  23:59:59";

            $data['total_number_of_point_issued'] = ReferralCode::where('is_paid','0')->whereBetween('created_at', [$from, $to])->sum('referral_amount');
            $data['total_number_of_point_claim'] = ReferralCode::where('is_paid','1')->whereBetween('created_at', [$from, $to])->sum('referral_amount');
            $data['number_of_points_lapse'] = 0;
            $data['net_number_point_claim'] = $data['total_number_of_point_issued'] - $data['total_number_of_point_claim'];

        }else{
            if($data['date_from'] && $data['date_to']){
                $ustart_date = date('Y-m-d', strtotime($data['date_from']));
                $uend_date = date('Y-m-d', strtotime($data['date_to']));

                $from = $ustart_date." 00:00:01";
                $to = $uend_date."  23:59:59";

                $data['total_number_of_point_issued'] = ReferralCode::where('is_paid','0')->whereBetween('created_at', [$from, $to])->sum('referral_amount');
                $data['total_number_of_point_claim'] = ReferralCode::where('is_paid','1')->whereBetween('created_at', [$from, $to])->sum('referral_amount');
                $data['number_of_points_lapse'] = 0;
                $data['net_number_point_claim'] = $data['total_number_of_point_issued'] - $data['total_number_of_point_claim'];
            }else{
                $data['total_number_of_point_issued'] = ReferralCode::where('is_paid','0')->sum('referral_amount');
                $data['total_number_of_point_claim'] = ReferralCode::where('is_paid','1')->sum('referral_amount');
                $data['number_of_points_lapse'] = 0;
                $data['net_number_point_claim'] = $data['total_number_of_point_issued'] - $data['total_number_of_point_claim'];
            }
        }
        return view('admin.analytics.membership_referral.membership_points_dashboard',$data);
    }

    public function membership_points_list(Request $request){
        $data = [];
        $data['user_id'] = $request->user_id;
        $data['type'] = $request->type;
        $data['page_type'] = $request->page_type;
        $data['type_id'] = $request->type_id;

        $data['reports'] = ReferralCode::select(["referral_codes.*","users.name"])->LeftJoin('users', 'users.id', '=', 'referral_codes.user_id')->orderBy('referral_codes.created_at','ASC');

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

        if($data['type_id'] == 'Issued'){
            $data['reports'] = $data['reports']->where('is_paid',0);
        }else if($data['type_id'] == 'Claimed'){
            $data['reports'] = $data['reports']->where('is_paid',1);
        }
        $data['reports'] = $data['reports']->get();
        
        $data['users'] = User::get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_membership_points_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Name', 'Amount', 'Note', 'Created', 'Expired'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->name, $row->referral_amount, $row->note, $row->created_at, $row->expire_at));   
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data['reports'][0]);
        return view('admin.analytics.membership_referral.membership_points_list',$data);
    }

    public function coupon_code_dashboard(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        // $data['users'] = User::get();
        $current_date = date('Y-m-d');
        if($data['date_from'] && $data['date_to']){
            $ustart_date = date('Y-m-d', strtotime($data['date_from']));
            $uend_date = date('Y-m-d', strtotime($data['date_to']));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['coupon_code_issued'] = 0;
            $data['coupon_code_claim'] = Order::where("coupon_amount","!=",0)->whereBetween('created_at', [$from, $to])->sum('coupon_amount');
            $data['coupon_code_lapse'] = 0;
            $data['coupon_code_yet_claim'] = 0;
        }else{
            $data['coupon_code_issued'] = 0;
            $data['coupon_code_claim'] = Order::where("coupon_amount","!=",0)->sum('coupon_amount');
            $data['coupon_code_lapse'] = 0;
            $data['coupon_code_yet_claim'] = 0;
        }
        return view('admin.analytics.membership_referral.coupon_code_dashboard',$data);
    }

    public function coupon_code_list(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("coupons")->select(['coupons.*']);

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('coupons.created_at', [$ustart_date , $uend_date]);
        }

        $data['reports'] = $data['reports']->where('is_active','=',1)->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_coupon_code_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Code', 'Amount', 'Type', 'Expired', 'Created'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->coupon_code, $row->coupon_amount, $row->coupon_type, $row->expired_at, $row->created_at));
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.membership_referral.coupon_code_list',$data);
    }

    public function coupon_code_claimed(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_libraries")->select(['history_libraries.*','users.email','users.name as user_name',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_libraries.user_id', '=', 'users.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_libraries.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','LIBRARY')->where("value","=","OUTPUT")->groupBy(['users.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_name_of_member.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Member Name', 'Member Email', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->email, $row->total_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.membership_referral.coupon_code_claimed',$data);
    }   

}
