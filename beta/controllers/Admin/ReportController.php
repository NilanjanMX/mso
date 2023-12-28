<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\Order;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class ReportController extends Controller
{
    
    public function membership(Request $request){
        if ($request->ajax()) {
            $data = Membership::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","memberships.package_id","memberships.amount","memberships.created_at","package_creation_dropdowns.name as package_name"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->LeftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'memberships.package_id')->where("amount","!=",0)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        return view('admin.report.membership');
    }

    public function store(Request $request){
        if ($request->ajax()) {
            $data = Order::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","orders.payable_amount","orders.created_at"])->LeftJoin('users', 'users.id', '=', 'orders.user_id')->where("payable_amount","!=",0)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }
                    return $created_at;
                })
                ->rawColumns(['created_at'])
                ->make(true);
        }
        return view('admin.report.store');
    }

    public function download(){
        return view('admin.report.download');
    }


    public function actions(Request $request){
        $input = $request->all();

        $date_from = date($request->date_from);
        $date_to = date($request->date_to);
        $download_file_type = $request->download_file_type;

        if($request->type == "Store"){
            $result = Order::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","orders.payable_amount","orders.created_at"])->LeftJoin('users', 'users.id', '=', 'orders.user_id')->where("payable_amount","!=",0)->whereBetween('orders.created_at', [$date_from, $date_to])->latest()->get();

            
            $filename = "gst-report-store";
            $handle = fopen("./storage/app/".$filename, 'w');

            fputcsv($handle, array('SN', 'Name', 'Email id', 'Contact No', 'City', 'Payment Date', 'Amount', 'GST No'));
            $subscription_type = "";
            foreach($result as $key=>$row) {
                fputcsv($handle, array(
                    $key+1, 
                    $row->name, 
                    $row->email,
                    $row->phone_no,
                    $row->city,
                    date('d-m-Y',strtotime($row->created_at)),
                    $row->payable_amount,
                    $row->gst_number,
                ));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }else{
            $result = Membership::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","memberships.package_id","memberships.amount","memberships.created_at","package_creation_dropdowns.name as package_name"])->LeftJoin('users', 'users.id', '=', 'memberships.user_id')->LeftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'memberships.package_id')->where("amount","!=",0)->whereBetween('memberships.created_at', [$date_from, $date_to])->latest()->get();

            $filename = "gst-report-membership";
            $handle = fopen("./storage/app/".$filename, 'w');

            fputcsv($handle, array('SN', 'Name', 'Email id', 'Contact No', 'City','Package Name', 'Payment Date', 'Amount', 'GST No'));
            $subscription_type = "";
            foreach($result as $key=>$row) {
                fputcsv($handle, array(
                    $key+1, 
                    $row->name, 
                    $row->email,
                    $row->phone_no,
                    $row->city,
                    $row->package_name,
                    date('d-m-Y',strtotime($row->created_at)),
                    $row->amount,
                    $row->gst_number,
                ));
            }
            fclose($handle);
            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        dd($input);
    }
    

}
