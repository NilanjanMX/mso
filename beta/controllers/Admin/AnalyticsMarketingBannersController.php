<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\Calculator;
use App\Models\Displayinfo;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsMarketingBannersController extends Controller
{

    public function name_of_member(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','users.email','users.name as user_name',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_sites.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->groupBy(['users.id'])->get();

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
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.name_of_member',$data);
    }
    public function download_by_category(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbannercategories.name as user_name',DB::raw('COUNT(premiumbannercategories.name) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id')->leftJoin('premiumbannercategories', 'premiumbanners.premiumbannercategory_ids', '=', 'premiumbannercategories.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->groupBy(['premiumbannercategories.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_download_by_category.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Category Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_count));
                
                
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.download_by_category',$data);
    }
    public function most_download(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbanners.title as user_name',DB::raw('COUNT(premiumbanners.id) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->orderBy('total_count','DESC')->groupBy(['premiumbanners.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_most_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Banner Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_count));
                
                
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.most_download',$data);
    }
    public function least_download(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbanners.title as user_name',DB::raw('COUNT(premiumbanners.id) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->orderBy('total_count','ASC')->groupBy(['premiumbanners.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_least_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Banner Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_count));
                
                
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.least_download',$data);
    }
    public function top_download(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbanners.title as user_name',DB::raw('COUNT(premiumbanners.id) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->orderBy('total_count','DESC')->groupBy(['premiumbanners.id'])->take(5)->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_top_5_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Banner Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_count));
                
                
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.top_download',$data);
    }
    public function name_wise_download(Request $request){
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
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbanners.title as user_name',DB::raw('COUNT(premiumbanners.id) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Marketing Banners')->orderBy('total_count','DESC')->groupBy(['premiumbanners.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_top_5_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Banner Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_count));
                
                
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.marketing_banners.name_wise_download',$data);
    }
    

}