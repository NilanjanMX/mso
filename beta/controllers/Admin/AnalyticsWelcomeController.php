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

class AnalyticsWelcomeController extends Controller
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
        
        $data['reports'] = DB::table("history_welcome_letters")->select(['history_welcome_letters.*','users.email','users.name as user_name',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_welcome_letters.user_id', '=', 'users.id');
        
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

            $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('history_welcome_letters.device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']
        // ->where('page_type','=','Welcome Letter User')
        ->where('value','=','DOWNLOAD')->groupBy(['users.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_welcome_name_of_member.csv";
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
        return view('admin.analytics.welcome.name_of_member',$data);
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
        
        $data['reports'] = DB::table("history_welcome_letters")->select(['history_welcome_letters.*','welcome_letters.name as user_name',DB::raw('COUNT(welcome_letters.id) as total_count')])->leftJoin('welcome_letters', 'history_welcome_letters.page_id', '=', 'welcome_letters.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']
        // ->where('page_type','=','Welcome Letter')
        ->where('value','=','DOWNLOAD')->orderBy('total_count','DESC')->groupBy(['welcome_letters.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_welcome_most_download.csv";
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
        return view('admin.analytics.welcome.most_download',$data);
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
        
        $data['reports'] = DB::table("history_welcome_letters")->select(['history_welcome_letters.*','welcome_letters.name as user_name',DB::raw('COUNT(welcome_letters.id) as total_count')])->leftJoin('welcome_letters', 'history_welcome_letters.page_id', '=', 'welcome_letters.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']
        // ->where('page_type','=','Welcome Letter')
        ->where('value','=','DOWNLOAD')->orderBy('total_count','ASC')->groupBy(['welcome_letters.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_welcome_least_download.csv";
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
        return view('admin.analytics.welcome.least_download',$data);
    }
    public function number_of_customised_template(Request $request){
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
        
        $data['reports'] = DB::table("welcome_letter_users")->select(['welcome_letter_users.*','users.name as user_name','users.email as email',DB::raw('COUNT(users.id) as total_count')])->leftJoin('users', 'welcome_letter_users.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('welcome_letter_users.created_at', [$ustart_date , $uend_date]);
            }
        }

        $data['reports'] = $data['reports']->orderBy('total_count','DESC')->groupBy(['users.id'])->get(5);

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_welcome_number_of_customised_template.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'User Name', 'User Email','Total Count'));

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->user_name, $row->email, $row->total_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        return view('admin.analytics.welcome.top_download',$data);
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
        
        $data['reports'] = DB::table("history_welcome_letters")->select(['history_welcome_letters.*','welcome_letters.name as user_name',DB::raw('COUNT(welcome_letters.id) as total_count')])->leftJoin('welcome_letters', 'history_welcome_letters.page_id', '=', 'welcome_letters.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_welcome_letters.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','Welcome Letter')->where('value','=','DOWNLOAD')->orderBy('welcome_letters.id','ASC')->groupBy(['welcome_letters.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_welcome_name_wise_download.csv";
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
        return view('admin.analytics.welcome.name_wise_download',$data);
    }
    

}
