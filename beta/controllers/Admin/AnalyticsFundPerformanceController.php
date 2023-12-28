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

class AnalyticsFundPerformanceController extends Controller
{

    public function category_wise(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['date_type'] = isset($input['date_type'])?$input['date_type']:'';

        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('d').'/'.date('m').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("history_fund_performances")->select(['history_fund_performances.*','accord_sclass_mst.classname as category_name','users.name as username','users.city as city_name'])->leftJoin('accord_sclass_mst', 'history_fund_performances.page_id', '=', 'accord_sclass_mst.classcode')->leftJoin('users', 'history_fund_performances.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$from , $to]);
        }else{

            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Category Wise')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_fund_performance_category_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Category Name', 'Type', 'Username', 'Device Type', 'Created Date', 'IP'));
            

            foreach($data['reports'] as $key=>$row) {
                $typee = "";
                if($row->list_count){
                   $typee =  "CSV";
                }else if($row->view_count){
                    $typee = "View";
                }else if($row->download_count){
                    "Download";
                }else if($row->save_count){
                    $typee = "Save";
                }
                
                fputcsv($handle, array($key+1, $row->category_name, $typee, $row->username, $row->device_type, date('d-m-Y h:i', strtotime($row->created_at)), $row->ip));
                
                
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.fund_performance.category_wise',$data);
    }

    public function custom_scheme_wise(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['amc_code'] = isset($input['amc_code'])?$input['amc_code']:'';
        $data['date_type'] = isset($input['date_type'])?$input['date_type']:'';

        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('d').'/'.date('m').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("history_fund_performance_suggested_schemes")->select(['history_fund_performance_suggested_schemes.*','mf_scanner.s_name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('history_fund_performances', 'history_fund_performances.id', '=', 'history_fund_performance_suggested_schemes.user_history_id')->leftJoin('mf_scanner', 'mf_scanner.schemecode', '=', 'history_fund_performance_suggested_schemes.scheme_id');

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

            $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_fund_performances.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['amc_code'])){
            $data['reports'] = $data['reports']->where('mf_scanner.amc_code','=',$data['amc_code']);
        }

        $data['reports'] = $data['reports']->where('history_fund_performances.page_type','=','Custom')->groupBy(['history_fund_performance_suggested_schemes.scheme_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "fund_performance_custom_scheme_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Scheme Name', 'View Count', 'DownLoad Count', 'CSV Count', 'Save Count', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_view_count, $row->total_download_count, $row->total_list_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
            }
            // dd("row");
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();
        // dd($data);
        return view('admin.analytics.fund_performance.custom_scheme_wise',$data);
    }

    public function custom_list(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['date_type'] = isset($input['date_type'])?$input['date_type']:'';

        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('d').'/'.date('m').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("history_fund_performances")->select(['history_fund_performances.*','fund_performance_create_category_lists.category_name as category_name','users.name as user_name','users.email as user_email','users.city as city_name'])->leftJoin('fund_performance_create_category_lists', 'history_fund_performances.page_id', '=', 'fund_performance_create_category_lists.id')->leftJoin('users', 'history_fund_performances.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_fund_performances.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Custom')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_fund_performance_category_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'User Email', 'User City', 'Type', 'D Type', 'Created Date', 'IP'));
            
            foreach($data['reports'] as $key=>$row) {
                $typee = "";
                if($row->list_count){
                    $typee = "List";
                }
                elseif($row->view_count){
                    $typee = "View";
                }
                elseif($row->download_count){
                    $typee = "Download";
                }
                elseif($row->save_count){
                    $typee = "Save";
                }
                fputcsv($handle, array($key+1, $row->user_name, $row->user_email, $row->city_name, $typee, $row->device_type, date('d-m-Y h:i', strtotime($row->created_at)), $row->ip));
                
                
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.fund_performance.custom_list',$data);
    }
    

}
