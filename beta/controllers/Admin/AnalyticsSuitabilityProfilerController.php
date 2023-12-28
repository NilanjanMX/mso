<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\ReferralCode;
use App\Models\Order;
use App\Models\HistoryLibrary;
use App\Models\HistoryClientCommunication;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsSuitabilityProfilerController extends Controller
{

    public function dashboard(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['date_type'] = $request->date_type;
        
        if(!$data['date_from'] && !$data['date_to']){
            $current_date = date('d').'/'.date('m').'/'.date('Y');
            $data['date_from'] = $current_date;
            $data['date_to'] = $current_date;
        }
        // $data['users'] = User::get();
        $current_date = date('Y-m-d');
        if($data['date_from'] && $data['date_to']){
            
            $ustart_date = date('Y-m-d', strtotime($data['date_from']));
            $uend_date = date('Y-m-d', strtotime($data['date_to']));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['no_of_click_on_copy'] = HistoryClientCommunication::where("value","=","TEXT")->where("page_type","=",'Client Communication')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_click_on_whatsapp'] = HistoryClientCommunication::where("value","=","WHATSAPP")->where("page_type","=",'Client Communication')->whereBetween('created_at', [$from, $to])->count();

        }else{
            $data['no_of_click_on_copy'] = HistoryClientCommunication::where("value","=","TEXT")->where("page_type","=",'Client Communication')->count();
            $data['no_of_click_on_whatsapp'] = HistoryClientCommunication::where("value","=","WHATSAPP")->where("page_type","=",'Client Communication')->count();
        }
        return view('admin.analytics.suitability_profiler.dashboard',$data);
    }

    public function suggested_asset_allocation(Request $request){
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
        
        $data['reports'] = DB::table("history_suitability_profiler_suggested_schemes")->select(['history_suitability_profiler_suggested_schemes.*',DB::raw('COUNT(history_suitability_profiler_suggested_schemes.scheme_id) as total_count')])->leftJoin('history_suitability_profilers', 'history_suitability_profilers.id', '=', 'history_suitability_profiler_suggested_schemes.user_history_id');

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

            $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_suitability_profilers.device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('history_suitability_profiler_suggested_schemes.type','=',0)->groupBy(['history_suitability_profiler_suggested_schemes.scheme_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_suitability_profiler_suggested_asset_allocation.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Scheme', 'Total Count'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->scheme_id, $row->total_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.suitability_profiler.suggested_asset_allocation',$data);
    }

    public function suggested_product(Request $request){
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
        
        $data['reports'] = DB::table("history_suitability_profiler_suggested_schemes")->select(['history_suitability_profiler_suggested_schemes.*',DB::raw('COUNT(history_suitability_profiler_suggested_schemes.scheme_id) as total_count')])->leftJoin('history_suitability_profilers', 'history_suitability_profilers.id', '=', 'history_suitability_profiler_suggested_schemes.user_history_id');

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

            $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_suitability_profilers.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['amc_code'])){
            $data['reports'] = $data['reports']->where('mf_scanner.amc_code','=',$data['amc_code']);
        }

        $data['reports'] = $data['reports']->where('history_suitability_profiler_suggested_schemes.type','=',1)->groupBy(['history_suitability_profiler_suggested_schemes.scheme_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_suitability_profiler_suggested_product.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Scheme', 'Total Count'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->scheme_id, $row->total_count));
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();
        // dd($data);
        return view('admin.analytics.suitability_profiler.suggested_product',$data);
    }

    public function no_of_saved_user(Request $request){
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
        
        $data['reports'] = DB::table("history_suitability_profilers")->select(['history_suitability_profilers.*','users.name as username',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_suitability_profilers.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Suitability Profiler')->where('value','=','SAVE')->groupBy(['history_suitability_profilers.user_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_suitability_profiler_no_of_saved_user.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'Total Count'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->username,$row->total_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        return view('admin.analytics.suitability_profiler.no_of_saved_user',$data);
    }

    public function no_of_download_user(Request $request){
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
        
        $data['reports'] = DB::table("history_suitability_profilers")->select(['history_suitability_profilers.*','users.name as username',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_suitability_profilers.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_suitability_profilers.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Suitability Profiler')->where('value','=','DOWNLOAD')->groupBy(['history_suitability_profilers.user_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_suitability_profiler_no_of_download_user.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'Total Count'));
            
            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->username,$row->total_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        return view('admin.analytics.suitability_profiler.no_of_download_user',$data);
    }
    

}
