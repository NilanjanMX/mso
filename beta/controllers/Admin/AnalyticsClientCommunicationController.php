<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\ReferralCode;
use App\Models\Order;
use App\Models\ClientCommunication;
use App\Models\HistoryClientCommunication;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsClientCommunicationController extends Controller
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
            
            $date_from = explode('/', $data['date_from']);
            $date_to = explode('/', $data['date_to']);

            $from = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
            $to = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

            $data['no_of_click_on_copy'] = HistoryClientCommunication::where("is_text","=",1)->where("page_type","=",'Client Communication')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_click_on_whatsapp'] = HistoryClientCommunication::where("is_whatsapp","=",1)->where("page_type","=",'Client Communication')->whereBetween('created_at', [$from, $to])->count();

        }else{
            $data['no_of_click_on_copy'] = HistoryClientCommunication::where("is_text","=",1)->where("page_type","=",'Client Communication')->count();
            $data['no_of_click_on_whatsapp'] = HistoryClientCommunication::where("is_whatsapp","=",1)->where("page_type","=",'Client Communication')->count();
        }
        return view('admin.analytics.client_communication.dashboard',$data);
    }

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
        
        $data['reports'] = DB::table("history_client_communications")->select(['history_client_communications.*','client_communication_category.name as category_name',DB::raw('SUM(is_text) as text_count'),DB::raw('SUM(is_whatsapp) as whatsapp_count'),DB::raw('SUM(is_link) as link_count'),DB::raw('SUM(is_view) as view_count')])->leftJoin('client_communication', 'history_client_communications.page_id', '=', 'client_communication.id')->leftJoin('client_communication_category', 'client_communication.category_id', '=', 'client_communication_category.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Client Communication')->groupBy('client_communication.category_id')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_client_communication_category_wise.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Category Name', 'Text Count', 'Whatsapp Count', 'Link Count', 'View Count', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->category_name, $row->text_count, $row->whatsapp_count, $row->link_count, $row->view_count,$row->view_count+$row->text_count+$row->whatsapp_count+$row->link_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.client_communication.category_wise',$data);
    }

    public function client_comm_title(Request $request){
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
        
        $data['reports'] = DB::table("history_client_communications")->select(['history_client_communications.*','client_communication.question','users.name as username','users.city as user_city','package_creation_dropdowns.name as package_name'])->leftJoin('client_communication', 'history_client_communications.page_id', '=', 'client_communication.id')->leftJoin('users', 'users.id', '=', 'history_client_communications.user_id')->leftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'users.package_id');

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

            $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_client_communications.device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('history_client_communications.page_type','=','Client Communication')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_client_communication_title.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Question','Type', 'User Name', 'User City','Package Name', 'Device Type', 'Created Date', 'IP'));

            foreach($data['reports'] as $key=>$row) {
                $typee = "";
                if($row->is_text){
                    $typee = "COPY";
                }
                elseif($row->is_whatsapp){
                    $typee = "WHATSAPP";
                }
                elseif($row->is_link){
                    $typee = "LINK";
                }
                elseif($row->is_view){
                    $typee = "VIEW";
                }
                fputcsv($handle, array($key+1, $row->question, $typee, $row->username, $row->user_city, $row->package_name, $row->device_type, date('d-m-Y h:s', strtotime($row->created_at)), $row->ip));
            }
            // dd("row");
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.client_communication.client_comm_title',$data);
    }

    public function most_used(Request $request){
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
        
        $data['reports'] = DB::table("history_client_communications")->select(['history_client_communications.*','client_communication.question',DB::raw('SUM(is_text) as text_count'),DB::raw('SUM(is_whatsapp) as whatsapp_count'),DB::raw('SUM(is_link) as link_count'),DB::raw('SUM(is_view) as view_count')])->leftJoin('client_communication', 'history_client_communications.page_id', '=', 'client_communication.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_client_communications.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_client_communications.device_type','=',$data['udevice_type']);
        }

        if(!empty($data['amc_code'])){
            $data['reports'] = $data['reports']->where('mf_scanner.amc_code','=',$data['amc_code']);
        }

        $data['reports'] = $data['reports']->groupBy(['client_communication.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_client_most_used.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Question', 'Text Count', 'Whatsapp Count', 'Link Count', 'View Count', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {

                fputcsv($handle, array($key+1, $row->question, $row->text_count, $row->whatsapp_count, $row->link_count, $row->view_count,$row->view_count+$row->text_count+$row->whatsapp_count+$row->link_count));
            }
            // dd("row");
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.client_communication.most_used',$data);
    }
    

}