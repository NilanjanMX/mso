<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Demo;
use App\Models\NationalConference as ModelsNationalConference;
use Response;

class NationalConference extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $input = $request->all();
        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';

        $res = ModelsNationalConference::orderBy('id', 'desc');
        // if($data['ustart_date'] && $data['uend_date']){
        //     $res->whereBetween('created_at', [$data['ustart_date'], $data['uend_date']]);
        // }
        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";
            $res->whereBetween('created_at', [$ustart_date , $uend_date]);
        }
        $data['user_list'] = $res->get();
        // dd($data['uend_date']);
        $data['current_date'] = date('Y-m-d');

        // if($data['page_type'] == "DOWNLOAD"){
        //     $pdf = PDF::loadView('admin.analytics.inactive_user_list', $data);
        //     return $pdf->download('inactive_user_list.pdf');
        // }else 
        if($data['page_type'] == "CSV"){
            $filename = "national_conference_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('slno','first_name','last_name','email','phone','city','password','user_type','amount','gst_no','payment_status','transaction_id', 'payment_mode', 'api_eventId','created_at'));
           
            // $data = ModelsNationalConference::get();
            foreach($data['user_list'] as $key=>$row) {
                
                fputcsv($handle, array(
                    $key+1, 
                    $row->first_name,
                    $row->last_name,
                    $row->email,
                    $row->phone,
                    $row->city,
                    $row->password,
                    $row->user_type,
                    $row->amount,
                    $row->gst_no,
                    $row->payment_status,
                    $row->transaction_id,
                    $row->payment_mode,
                    // $row->api_password,
                    $row->api_eventId,
                    $row->created_at,
                ));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
            
        }
           
        return view('admin.conference.index', $data);
    }



    public function filterData(Request $request){
        $input = $request->all();
        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        
        $res = ModelsNationalConference::orderBy('id');
            if($data['ustart_date'] && $data['uend_date']){
                $res->whereBetween('created_at', [$data['ustart_date'], $data['uend_date']]);
            }
            $data = $res->get();

            
            $filename = "national_conference_list.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('slno','first_name','last_name','email','phone','city','password','user_type','amount','payment_status','transaction_id', 'payment_mode', 'api_eventId','created_at'));
           
            // $data = ModelsNationalConference::get();
            foreach($data as $key=>$row) {
                
                fputcsv($handle, array(
                    $key+1, 
                    $row->first_name,
                    $row->last_name,
                    $row->email,
                    $row->phone,
                    $row->city,
                    $row->password,
                    $row->user_type,
                    $row->amount,
                    $row->payment_status,
                    $row->transaction_id,
                    $row->payment_mode,
                    // $row->api_password,
                    $row->api_eventId,
                    $row->created_at,
                ));
                
                
            }
            // dd("row");
            fclose($handle);


            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
            
    
    }

}
