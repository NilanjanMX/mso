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

class AnalyticsController extends Controller
{


    public function trail_calculator(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','trail_calculations.name as user_name',DB::raw('COUNT(trail_calculations.id) as total_count')])->leftJoin('trail_calculations', 'history_sites.page_id', '=', 'trail_calculations.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Brokerage Calculator')->where('value','=','OUTPUT')->orderBy('total_count','DESC')->groupBy(['trail_calculations.id'])->get();

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
        return view('admin.analytics.trail_calculator',$data);
    }


    public function other_download(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','famous_quotes.name as user_name',DB::raw('COUNT(famous_quotes.id) as total_count')])->leftJoin('famous_quotes', 'history_sites.page_id', '=', 'famous_quotes.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_sites.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Other Download')->orderBy('total_count','DESC')->groupBy(['famous_quotes.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_marketing_banner_top_5_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Name', 'Total Count'));

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
        return view('admin.analytics.other_download',$data);
    }
    public function calculator(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        // dd($data);
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('histories.device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Calculator')->groupBy(['page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.calculator',$data);
    }
    public function calculator_scheme_wise(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        
        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('m').'/'.date('d').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Calculator')->groupBy(['page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.calculator_scheme_wise',$data);
    }
    public function calculator_suggested_schemes(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['amc_code'] = isset($input['amc_code'])?$input['amc_code']:'';
        $data['category_id'] = isset($input['category_id'])?$input['category_id']:'';

        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('m').'/'.date('d').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("history_suggested_schemes")->select(['history_suggested_schemes.*','mf_scanner.s_name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('histories', 'histories.id', '=', 'history_suggested_schemes.user_history_id')->leftJoin('mf_scanner', 'mf_scanner.schemecode', '=', 'history_suggested_schemes.scheme_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('histories.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['amc_code'])){
            $data['reports'] = $data['reports']->where('mf_scanner.amc_code','=',$data['amc_code']);
        }

        if(!empty($data['category_id'])){
            $data['reports'] = $data['reports']->where('mf_scanner.classcode','=',$data['category_id']);
        }

        $data['reports'] = $data['reports']->where('histories.page_type','=','Calculator')->groupBy(['history_suggested_schemes.scheme_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
            }
            // dd("row");
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        $data['fund_house_list'] = DB::table("accord_amc_mst")->select(['amc_code','fund'])->orderBy('fund', 'asc')->get();
        $data['category_list'] = DB::table("accord_sclass_mst")
                        ->select(['accord_sclass_mst.classcode','classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.status',1)
                        ->orderBy('classname', 'asc')->get();
        // dd($data);
        return view('admin.analytics.calculator_suggested_schemes',$data);
    }
    public function calculator_user_wise(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count'),'users.name as user_name','users.city as user_city','package_creation_dropdowns.name as package_name'])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id')->leftJoin('users', 'histories.user_id', '=', 'users.id')->leftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'users.package_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Calculator')->groupBy(['user_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_user_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.calculator_user_wise',$data);
    }

    public function calculators(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['page_id'] = isset($input['page_id'])?$input['page_id']:'';
        $data['upage_type'] = isset($input['upage_type'])?$input['upage_type']:'';

        if(!$data['ustart_date'] && !$data['uend_date']){
            $current_date = date('m').'/'.date('d').'/'.date('Y');
            $data['ustart_date'] = $current_date;
            $data['uend_date'] = $current_date;
        }
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name','users.name as username'])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id')->leftJoin('users', 'histories.user_id', '=', 'users.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('histories.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['upage_type'])){
            if($data['upage_type'] == "List"){
                $data['reports'] = $data['reports']->where('list_count','=',1);
            }else if($data['upage_type'] == "View"){
                $data['reports'] = $data['reports']->where('view_count','=',1);
            }else if($data['upage_type'] == "Download"){
                $data['reports'] = $data['reports']->where('download_count','=',1);
            }else if($data['upage_type'] == "Save"){
                $data['reports'] = $data['reports']->where('save_count','=',1);
            }
        }else{
            $data['reports'] = $data['reports']->where('list_count','!=',1);
        }

        if(!empty($data['page_id'])){
            $data['reports'] = $data['reports']->where('page_id','=',$data['page_id']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Calculator')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'Type', 'IP', 'Username', 'Device Type', 'Created Date'));
            

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

                fputcsv($handle, array($key+1, $row->page_name, $typee, $row->ip, $row->username, $row->device_type,$row->created_at));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }

        $data['calculator_list'] = Calculator::select(["id","name"])->where('status',1)->get();
        // dd($data);
        return view('admin.analytics.calculator_detail',$data);
    }

    public function mf_research(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("histories")->select(['histories.*','mf_researches.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('mf_researches', 'histories.page_id', '=', 'mf_researches.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','MF Research')->groupBy(['page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('mf_research_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "mf_research_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.mf_research',$data);
    }

    public function mf_research_detail(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        $data['page_id'] = isset($input['page_id'])?$input['page_id']:'';
        
        $data['reports'] = DB::table("histories")->select(['histories.*','mf_researches.name as page_name','users.name as username','users.city as user_city','package_creation_dropdowns.name as package_name'])->leftJoin('mf_researches', 'histories.page_id', '=', 'mf_researches.id')->leftJoin('users', 'histories.user_id', '=', 'users.id')->leftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'users.package_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('histories.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['page_id'])){
            $data['reports'] = $data['reports']->where('page_id','=',$data['page_id']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','MF Research')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('mf_research_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "mf_research_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'Type', 'IP', 'Username', 'Device Type', 'Created Date'));
            

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

                fputcsv($handle, array($key+1, $row->page_name, $typee, $row->ip, $row->username, $row->device_type,$row->created_at));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }

        $data['calculator_list'] = DB::table("mf_researches")->select(["id","name"])->where('status',1)->get();
        // dd($data);
        return view('admin.analytics.mf_research_detail',$data);
    }

    public function mf_research_user_wise(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count'),'users.name as user_name'])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id')->leftJoin('users', 'histories.user_id', '=', 'users.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','MF Research')->groupBy(['user_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "mf_research_user_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->user_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.mf_research_user_wise',$data);
    }

    public function user_module(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['date_type'] = $request->date_type;
        
        if(!$data['date_from'] && !$data['date_to']){
            $current_date = date('m').'/'.date('d').'/'.date('Y');
            $data['date_from'] = $current_date;
            $data['date_to'] = $current_date;
        }

        if($data['date_type']){
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $current_date = $year.'/'.$month.'/'.$day;
            
            $month = $month - $data['date_type'];
            
            if($month < 1){
                $year = $year -1;
            }
            $pre_date = $year.'/'.$month.'/'.$day;
            
            $ustart_date = date('Y-m-d', strtotime($current_date));
            $uend_date = date('Y-m-d', strtotime($pre_date));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['no_of_basic'] = Membership::where("package_id","=",14)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_free_trial'] = Membership::where("package_id","=",15)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_premium'] = Membership::where("package_id","=",17)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_users_not_renewed'] = Membership::where("is_active","=",1)->where('memberships.expire_at','<',$current_date)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_add_on'] = User::whereBetween('created_at', [$from, $to])->whereNotNull('user_id')->count();
            $data['no_of_active'] = Membership::where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_inactive'] = Membership::where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

            $data['no_of_active_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_inactive_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

            $data['no_of_active_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_inactive_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

            $data['no_of_active_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_inactive_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

            $data['sessoin_count'] = 0;

        }else{
            $current_date = date('Y').'/'.date('m').'/'.date('d');
            if($data['date_from'] && $data['date_to']){
                $ustart_date = date('Y-m-d', strtotime($data['date_from']));
                $uend_date = date('Y-m-d', strtotime($data['date_to']));
    
                $from = $ustart_date." 00:00:01";
                $to = $uend_date."  23:59:59";
                
                $data['no_of_basic'] = Membership::where("package_id","=",14)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_free_trial'] = Membership::where("package_id","=",15)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_premium'] = Membership::where("package_id","=",17)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_users_not_renewed'] = Membership::where("is_active","=",1)->where('memberships.expire_at','<',$current_date)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_add_on'] = User::whereBetween('created_at', [$from, $to])->whereNotNull('user_id')->count();
                $data['no_of_active'] = Membership::where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_inactive'] = Membership::where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

                $data['no_of_active_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_inactive_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

                $data['no_of_active_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_inactive_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

                $data['no_of_active_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_inactive_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->whereBetween('created_at', [$from, $to])->count();

                $data['sessoin_count'] = 0;
            }else{
                $data['no_of_basic'] = Membership::where("package_id","=",14)->where("is_active","=",1)->count();
                $data['no_of_free_trial'] = Membership::where("package_id","=",15)->where("is_active","=",1)->count();
                $data['no_of_premium'] = Membership::where("package_id","=",17)->where("is_active","=",1)->count();
                $data['no_of_users_not_renewed'] = Membership::where("is_active","=",1)->where('memberships.expire_at','<',$current_date)->count();
                $data['no_of_add_on'] = User::whereNotNull('user_id')->count();

                $data['no_of_active'] = Membership::where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->count();
                $data['no_of_inactive'] = Membership::where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->count();

                $data['no_of_active_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->count();
                $data['no_of_inactive_t'] = Membership::where("package_id","=",15)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->count();

                $data['no_of_active_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->count();
                $data['no_of_inactive_b'] = Membership::where("package_id","=",14)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->count();

                $data['no_of_active_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','>',$current_date)->where("is_active","=",1)->count();
                $data['no_of_inactive_p'] = Membership::where("package_id","=",17)->where('memberships.expire_at','<',$current_date)->where("is_active","=",1)->count();

                $data['sessoin_count'] = 0;
            }
        }
        return view('admin.analytics.user_module',$data);
    }

    public function user_profile(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        $data['date_type'] = $request->date_type;
        
        if(!$data['date_from'] && !$data['date_to']){
            $current_date = date('m').'/'.date('d').'/'.date('Y');
            $data['date_from'] = $current_date;
            $data['date_to'] = $current_date;
        }
        
        if($data['date_type']){
            $month = date('m');
            $day = date('d');
            $year = date('Y');
            $current_date = $year.'/'.$month.'/'.$day;
            
            $month = $month - $data['date_type'];
            
            if($month < 1){
                $year = $year -1;
            }
            $pre_date = $year.'/'.$month.'/'.$day;
            
            $from = date($current_date);
            $to = date($pre_date);
            
            $ustart_date = date('Y-m-d', strtotime($current_date));
            $uend_date = date('Y-m-d', strtotime($pre_date));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['no_of_users_with_all_fields'] = User::where("package_id","=",14)->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_users_who_have_not_uploaded_logo'] = User::whereNull("company_logo")->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_users_without_billing'] = User::LeftJoin('billingaddresses','billingaddresses.user_id', '=', 'users.id')->whereNull('billingaddresses.id')->whereBetween('users.created_at', [$from, $to])->count();
            $data['no_of_users_without_customisation_cover'] = Displayinfo::whereNull("pdf_cover_image")->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_users_without_customisation_banner'] = Displayinfo::where('footer_branding_option','all_pages')->whereBetween('created_at', [$from, $to])->count();

        }else{
            if($data['date_from'] && $data['date_to']){
                $ustart_date = date('Y-m-d', strtotime($data['date_from']));
                $uend_date = date('Y-m-d', strtotime($data['date_to']));
    
                $from = $ustart_date." 00:00:01";
                $to = $uend_date."  23:59:59";
                
    
                $data['no_of_users_with_all_fields'] = User::where("package_id","=",14)->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_users_who_have_not_uploaded_logo'] = User::whereNull("company_logo")->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_users_without_billing'] = User::LeftJoin('billingaddresses','billingaddresses.user_id', '=', 'users.id')->whereNull('billingaddresses.id')->whereBetween('users.created_at', [$from, $to])->count();
                $data['no_of_users_without_customisation_cover'] = Displayinfo::whereNull("pdf_cover_image")->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_users_without_customisation_banner'] = Displayinfo::where('footer_branding_option','all_pages')->whereBetween('created_at', [$from, $to])->count();
            }else{
                $data['no_of_users_with_all_fields'] = User::where("package_id","=",14)->count();
                $data['no_of_users_who_have_not_uploaded_logo'] = User::whereNull("company_logo")->count();
                $data['no_of_users_without_billing'] = User::LeftJoin('billingaddresses','billingaddresses.user_id', '=', 'users.id')->whereNull('billingaddresses.id')->count();
                $data['no_of_users_without_customisation_cover'] = Displayinfo::whereNull("pdf_cover_image")->count();
                $data['no_of_users_without_customisation_banner'] = Displayinfo::where('footer_branding_option','all_pages')->count();
            }
        }
            
        return view('admin.analytics.user_profile',$data);
    }

    public function membership(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("histories")->select(['histories.*','calculators.name as page_name',DB::raw('SUM(list_count) as total_list_count'),DB::raw('SUM(view_count) as total_view_count'),DB::raw('SUM(download_count) as total_download_count'),DB::raw('SUM(save_count) as total_save_count')])->leftJoin('calculators', 'histories.page_id', '=', 'calculators.id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('histories.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Calculator')->groupBy(['page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.membership',$data);
    }
    
    public function store(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("orders")->select(['orders.*']);

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('orders.created_at', [$ustart_date , $uend_date]);
        }

        $data['reports'] = $data['reports']->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "calculator_analytics.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Calculator Name', 'List Count', 'View Count', 'DownLoad Count', 'Save Count', 'Total Count'));
            

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->page_name, $row->total_list_count, $row->total_view_count, $row->total_download_count, $row->total_save_count,$row->total_list_count+$row->total_download_count+$row->total_save_count+$row->total_view_count));
                
                
            }
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.store',$data);
    }
    
    public function dashboard(){
        $data = [];
        return view('admin.analytics.dashboard',$data);
    }
    
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
