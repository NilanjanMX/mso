<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsController extends Controller
{
    
    public function livemember(){
        $data = [];
        $data['web'] = User::where('device_type','WEB')->where('is_login',1)->count();
        $data['android'] = User::where('device_type','Android')->where('is_login',1)->count();;
        $data['ios'] = User::where('device_type','ios')->where('is_login',1)->count();;
        return view('admin.analytics.index',$data);
    }

    public function haventlogged(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';

        $data['user_list'] = User::select(['users.*','memberships.subscription_type','memberships.expire_at'])
                                ->join('memberships', function ($join) {
                                    $join->on('users.id', '=', 'memberships.user_id')
                                        ->where('memberships.is_active', '=', 1);
                                })
                                ->where('email','!=','');
        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";
            $data['user_list'] = $data['user_list']->whereBetween('login_date', [$ustart_date , $uend_date]);
        }
        
        //->where('is_login','=','1')
        $data['user_list'] = $data['user_list']->get();
        foreach($data['user_list'] as $key=>$value){
            $membership = Membership::select(["subscription_type","expire_at",'package_creation_dropdowns.name as package_name'])->leftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'memberships.package_id')->where('user_id',$value->id)->where('memberships.is_active', '=', 1)->first();
            $data['user_list'][$key]->subscription_type = $membership->subscription_type;
            $data['user_list'][$key]->package_name = $membership->package_name;
            $data['user_list'][$key]->expire_at = $membership->expire_at;
        }
        $data['current_date'] = date('Y-m-d');

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.inactive_user_list', $data);
            return $pdf->download('inactive_user_list.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "inactive_user_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Name', 'Email', 'Phone Number', 'Last Login', 'Status', 'Days',"Package Name"));
                
                // $date1 = new DateTime($row->login_date);
                // $date2 = new DateTime($data['current_date']);
                // $days = $date1->diff($date2)->format("%d");
            

            foreach($data['user_list'] as $key=>$row) {
                if($row->login_date == "0000-00-00 00:00:00"){
                    $days = "-";
                }else{
                    $date1=date_create($row->login_date);
                    $date2=date_create($data['current_date']);
                    $diff=date_diff($date1,$date2);
                    $days = $diff->format("%a");
                }

                if(strtotime(date('d-m-Y')) > strtotime(date('d-m-Y', strtotime($row->expire_at))) ){
                    $subscription_type = "expired";
                }else{
                    $subscription_type = $row->subscription_type;
                }

                fputcsv($handle, array($key+1, $row->name, $row->email, $row->phone_no, $row->login_date, $subscription_type, $days,$row->package_name));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
            
        }
        // DB::enableQueryLog();
        // DB::getQueryLog();
        // dd($data);
        return view('admin.analytics.haventlogged',$data);
    }

    public function reportPage(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        
        $data['reports'] = DB::table("user_histories")->select(['user_histories.*',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')]);

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->groupBy(['page_name'])->get();
        // dd($data);
        return view('admin.analytics.report',$data);
    }
    

}
