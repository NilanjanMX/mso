<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Membership;
use App\Models\Calculator;
use App\Models\Displayinfo;
use App\Models\HistoryOrder;
use App\Models\Order;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use DateTime;
use Response;

class AnalyticsOrderController extends Controller
{

    public function dashboard(Request $request){
        $data = [];
        $data['date_from'] = $request->date_from;
        $data['date_to'] = $request->date_to;
        // $data['users'] = User::get();
        $current_date = date('Y-m-d');
        if($data['date_from'] && $data['date_to']){
            
            $ustart_date = date('Y-m-d', strtotime($data['date_from']));
            $uend_date = date('Y-m-d', strtotime($data['date_to']));

            $from = $ustart_date." 00:00:01";
            $to = $uend_date."  23:59:59";

            $data['no_of_checkout'] = HistoryOrder::where("value","=","ORDER CART")->where("page_type","=",'ORDER')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_add_cart'] = HistoryOrder::where("value","=","CHECKOUT INITIATED")->where("page_type","=",'ORDER')->whereBetween('created_at', [$from, $to])->count();
            $data['no_of_purchases'] = Order::where('is_active',1)->whereBetween('created_at', [$from, $to])->count();
        }else{
            $data['no_of_checkout'] = HistoryOrder::where("value","=","ORDER CART")->where("page_type","=",'ORDER')->count();
            $data['no_of_add_cart'] = HistoryOrder::where("value","=","CHECKOUT INITIATED")->where("page_type","=",'ORDER')->count();
            $data['no_of_purchases'] = Order::where('is_active',1)->count();
        }
        return view('admin.analytics.order.dashboard',$data);
    }

    public function added_to_cart(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','stationaries.title as user_name',DB::raw('COUNT(stationaries.title) as total_count')])->leftJoin('stationaries', 'stationaries.id', '=', 'history_orders.page_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','ORDER CART')->groupBy(['history_orders.page_id'])->orderBy('total_count','DESC')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_added_to_cart.csv";
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
        return view('admin.analytics.order.added_to_cart',$data);
    }

    public function least_product(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','stationaries.title as user_name','stationaries.amount',DB::raw('COUNT(stationaries.title) as total_count')])->leftJoin('stationaries', 'stationaries.id', '=', 'history_orders.page_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','ORDER CART')->orderBy('total_count','ASC')->groupBy(['history_orders.page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_least_product.csv";
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
        return view('admin.analytics.order.least_product',$data);
    }

    public function video(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','stationaries.title as user_name',DB::raw('COUNT(stationaries.title) as total_count')])->leftJoin('stationaries', 'stationaries.id', '=', 'history_orders.page_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','ORDER CART')->orderBy('total_count','DESC')->groupBy(['history_orders.page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_video.csv";
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
        return view('admin.analytics.order.video',$data);
    }

    public function no_checkout(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','users.name as user_name',DB::raw('COUNT(users.id) as total_count')])->leftJoin('users', 'users.id', '=', 'history_orders.user_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','CHECKOUT INITIATED')->orderBy('total_count','DESC')->groupBy(['users.id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_no_checkout.csv";
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
        return view('admin.analytics.order.no_checkout',$data);
    }

    public function no_added_to_cart(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','stationaries.title as user_name',DB::raw('COUNT(stationaries.title) as total_count')])->leftJoin('stationaries', 'stationaries.id', '=', 'history_orders.page_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','ORDER CART')->orderBy('total_count','DESC')->groupBy(['history_orders.page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_no_added_to_cart.csv";
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
        return view('admin.analytics.order.no_added_to_cart',$data);
    }

    public function no_purchase(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_orders")->select(['history_orders.*','stationaries.title as user_name',DB::raw('COUNT(stationaries.title) as total_count')])->leftJoin('stationaries', 'stationaries.id', '=', 'history_orders.page_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('page_type','=','ORDER')->where('value','=','ORDER CART')->orderBy('total_count','DESC')->groupBy(['history_orders.page_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_no_purchase.csv";
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
        return view('admin.analytics.order.no_purchase',$data);
    }

    public function revenue_generated(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("orders")->select(['orders.*','users.name as user_name','users.city as user_city','package_creation_dropdowns.name as package_name'])->leftJoin('users', 'users.id', '=', 'orders.user_id')->leftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'users.package_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('payable_amount','!=',0)->orderBy('created_at','DESC')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_revenue_generated.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Invoice ID', 'User Name', 'Total Amount'));

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->invoice_id, $row->user_name, $row->payable_amount));
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.order.revenue_generated',$data);
    }

    public function point_used(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("orders")->select(['orders.*','users.name as user_name'])->leftJoin('users', 'users.id', '=', 'orders.user_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $reports = $data['reports']->where('payable_amount','!=',0)->orderBy('created_at','DESC')->get();

        $data['reports'] = [];

        foreach ($reports as $key => $value) {
            if(($value->coupon_amount + $value->payable_amount) !== $value->total_amount){
                array_push($data['reports'], $value);
            }
        }

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_point_used.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN','Invoice ID', 'User Name', 'Total Amount'));

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->invoice_id, $row->user_name, $row->total_amount - $row->payable_amount + $row->coupon_amount));
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
        }
        // dd($data);
        return view('admin.analytics.order.point_used',$data);
    }

    public function coupon_used(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("orders")->select(['orders.*','users.name as user_name'])->leftJoin('users', 'users.id', '=', 'orders.user_id');

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('history_orders.created_at', [$ustart_date , $uend_date]);
        }

        if(!empty($data['udevice_type'])){
            $data['reports'] = $data['reports']->where('device_type','=',$data['udevice_type']);
        }

        $data['reports'] = $data['reports']->where('coupon_amount','!=',0)->orderBy('created_at','DESC')->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_coupon_used.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN','Invoice ID', 'User Name', 'Total Amount'));

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->invoice_id, $row->user_name, $row->coupon_amount));
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.order.coupon_used',$data);
    }

    public function abandoned_checkouts(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("user_carts")->select(['user_carts.*',DB::raw('SUM(user_carts.quantity) as total_count')]);

        if($data['ustart_date'] && $data['uend_date']){
            $ustart_date = date('Y-m-d', strtotime($data['ustart_date']));
            $uend_date = date('Y-m-d', strtotime($data['uend_date']));

            $ustart_date = $ustart_date." 00:00:01";
            $uend_date = $uend_date."  23:59:59";

            $data['reports'] = $data['reports']->whereBetween('user_carts.created_at', [$ustart_date , $uend_date]);
        }

        $data['reports'] = $data['reports']->orderBy('total_count','DESC')->groupBy(['user_carts.product_id'])->get();

        if($data['page_type'] == "DOWNLOAD"){
            $pdf = PDF::loadView('admin.analytics.calculator_pdf', $data);
            return $pdf->download('calculator_analytics.pdf');
        }else if($data['page_type'] == "CSV"){
            $filename = "analytics_order_abandoned_checkouts.csv";
            $handle = fopen("./storage/app/".$filename, 'w');
            fputcsv($handle, array('SN', 'Name', 'Total Count'));

            foreach($data['reports'] as $key=>$row) {
                fputcsv($handle, array($key+1, $row->name, $row->total_count));
            }
            fclose($handle);

            $headers = array(
                'Content-Type' => 'text/csv',
            );
            return Response::download('./storage/app/'.$filename, $filename.".csv", $headers);
            
        }
        // dd($data);
        return view('admin.analytics.order.abandoned_checkouts',$data);
    }



    public function name_wise_download(Request $request){
        $input = $request->all();

        $data = [];

        $data['ustart_date'] = isset($input['ustart_date'])?$input['ustart_date']:'';
        $data['uend_date'] = isset($input['uend_date'])?$input['uend_date']:'';
        $data['udevice_type'] = isset($input['udevice_type'])?$input['udevice_type']:'';
        $data['page_type'] = isset($input['page_type'])?$input['page_type']:'';
        
        $data['reports'] = DB::table("history_sites")->select(['history_sites.*','premiumbanners.title as user_name',DB::raw('COUNT(premiumbanners.id) as total_count')])->leftJoin('premiumbanners', 'history_sites.page_id', '=', 'premiumbanners.id');

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
