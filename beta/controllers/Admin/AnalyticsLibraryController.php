<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\Calculator;
use App\Models\HistoryLibrary;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsLibraryController extends Controller
{

    public function dashdord(Request $request){
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

            $data['no_of_merge'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_download'] = HistoryLibrary::where("value","=","PDF")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_file_saved'] = HistoryLibrary::where("value","=","SAVE")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_users_used'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->groupBy('user_id')->count();
            $data['avg_number_of_files'] = 0;
            if($data['no_of_file_saved'] && $data['no_of_users_used']){
                $data['avg_number_of_files'] = $data['no_of_file_saved'] / $data['no_of_users_used'];
            }
        }else{


            $current_date = date('Y-m-d');
            if($data['date_from'] && $data['date_to']){
                
                $date_from = explode('/', $data['date_from']);
                $date_to = explode('/', $data['date_to']);

                $from = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $to = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['no_of_merge'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_download'] = HistoryLibrary::where("value","=","PDF")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_file_saved'] = HistoryLibrary::where("value","=","SAVE")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->count();
                $data['no_of_users_used'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->whereBetween('created_at', [$from, $to])->groupBy('user_id')->count();
                $data['avg_number_of_files'] = 0;
                if($data['no_of_file_saved'] && $data['no_of_users_used']){
                    $data['avg_number_of_files'] = $data['no_of_file_saved'] / $data['no_of_users_used'];
                }
                
            }else{
                $data['no_of_merge'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->count();
                $data['no_of_download'] = HistoryLibrary::where("value","=","PDF")->where("page_type","=",'LIBRARY')->count();
                $data['no_of_file_saved'] = HistoryLibrary::where("value","=","SAVE")->where("page_type","=",'LIBRARY')->count();
                $data['no_of_users_used'] = HistoryLibrary::where("value","=","OUTPUT")->where("page_type","=",'LIBRARY')->groupBy('user_id')->count();

                $data['avg_number_of_files'] = 0;
                if($data['no_of_file_saved'] && $data['no_of_users_used']){
                    $data['avg_number_of_files'] = $data['no_of_file_saved'] / $data['no_of_users_used'];
                }
            }

        }
        return view('admin.analytics.library.dashdord',$data);
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
        
        $data['reports'] = DB::table("history_libraries")->select(['history_libraries.*','users.email','users.name as user_name',DB::raw('COUNT(users.name) as total_count')])->leftJoin('users', 'history_libraries.user_id', '=', 'users.id');

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

            $data['reports'] = $data['reports']->whereBetween('history_libraries.created_at', [$from , $to]);
        }else{
            if($data['ustart_date'] && $data['uend_date']){
                $date_from = explode('/', $data['ustart_date']);
                $date_to = explode('/', $data['uend_date']);

                $ustart_date = $date_from[2]."-".$date_from[1]."-".$date_from[0]." 00:00:01";
                $uend_date = $date_to[2]."-".$date_to[1]."-".$date_to[0]." 23:59:59";

                $data['reports'] = $data['reports']->whereBetween('history_libraries.created_at', [$ustart_date , $uend_date]);
            }
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','LIBRARY')->where("value","=","SAVE")->groupBy(['users.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_library_name_wise_download.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Member Name', 'Member Email', 'Total View'));
            

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->user_name, $row->email, $row->total_count));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        return view('admin.analytics.library.name_wise_download',$data);
    }
    

}
