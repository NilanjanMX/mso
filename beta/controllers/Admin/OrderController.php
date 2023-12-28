<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Billingaddress;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Stationary;
use App\Models\Packagecorn;
use App\Models\UserBilling;
use App\Models\OrderStatus;
use App\User;
use App\Models\Userpurchasehistoryexport;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use App\Exports\UserpurchasehistorydataExport;
use Barryvdh\DomPDF\Facade as PDF;

class OrderController extends Controller
{    
    
    public function index(Request $request){
        if ($request->ajax()) {
            //$data = Order::with('user')->latest()->get();
            $data = Order::latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('username', function ($row) {
                    
                    $user = User::where('id',$row->user_id)->first();
                    if(isset($user->name) && !empty($user->name)){
                        $username = $user->name;
                    }else{
                        $username = '';
                    }
                    
                    //$username = $row->user->name;

                    return $username;
                })
                ->addColumn('status', function ($row) {
                    $status = ucfirst($row->status);
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('order-management-orders', 'update')){
                    $btn = '<a href="'.route('webadmin.ordersEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Update</a>';
                    }
                    if(is_permitted('order-management-orders', 'cancel')){
                    $btn .= '<a href="'.route('webadmin.ordersDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure cancel this order?\')"  class="edit btn btn-danger btn-sm ml-1">Cancel</a>';
                    }
                    
                    if(is_permitted('order-management-orders', 'invoice')){
                    $btn .= '<a href="'.route('webadmin.orderDownload',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Invoice</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['username','status','action'])
                ->make(true);
        }
        return view('admin.order.index');
    }

    public function edit($id){
        $data['order'] = Order::with('user','billing','orderitems')->where('id',$id)->first();
        $data['requestPackage'] = Packagecorn::where('order_id',$id)->get();
        $data['orderStatusList'] = OrderStatus::where('is_active',1)->get();
        
        return view('admin.order.edit',$data);
    }

    public function add($id){
        // dd($id);
        if($id){
            $user_id = 6748;
            $product_id = $id;
            $product = Stationary::find($product_id);
            // dd($product);
            $orderData = array(
                'user_id' => $user_id,
                'invoice_id' => "9444228577",
                'billingaddress_id' => 383,
                'coupon_code' => '',
                'coupon_amount' => 0,
                'total_amount' => 3200,
                'payable_amount' => 3200,
                'payment_status' => 'success',
                'status' => 'pending',
                'is_active' => 1
            );
            dd($orderData);
            $orderRes = Order::create($orderData);
            $order_id = $orderRes->id;

            $cartData = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'name' => $product->title,
                'quantity' => 1,
                'price' => 2400,
                'photo' => $product->product_image
            );
            $cartData['store_type'] = 'package';
            $date=strtotime(date('Y-m-d')); 
            $expire_at = date('Y-m-d',strtotime('+30 days',$date));
            $cartData['expired_at'] = $expire_at;

            $cartRes = Orderitem::create($cartData);

            $saveData = array(
                'order_id' => $order_id,
                'user_id' => $user_id,
                'product_id' => $product_id,
                'expired_at' => $expire_at
            );
            //dd($saveData);
            $saveRes = Packagecorn::create($saveData);
        }
            
    }

    public function update(Request $request,$id){
        
        $previousOrder = Order::where('id',$id)->first();
        if (!$previousOrder){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.order.orders');
        }
        $input = $request->all();
        //dd($input);
        $saveData = [
            'status' => $input['status']
        ];
        $res = $previousOrder->update($saveData);
        if ($res){
            toastr()->success('Order successfully updated.');
            return redirect()->route('webadmin.order.orders');
        }
        return redirect()->back()->withInput();
    }

    public function AmountInWords(float $amount){
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
        while( $x < $count_length ) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
            }
            else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }

    public function orderDownload(Request $request,$id){
        $data['membership_detail'] = Order::where('id',$id)->first();
        if (!$data['membership_detail']){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }
        $user = User::where('id',$data['membership_detail']->user_id)->first();
        // dd($user);
        $data['user_billing_detail'] = UserBilling::where('user_id',$user->id)->first();
        if($data['user_billing_detail']){
            $data['membership_detail'] = Order::where('id',$id)->first();
            $data['invoice_number'] = $id;
            $data['invoice_name'] = "ONLINE MEMBERSHIP FOR 1 YEAR SUBSCRIPTION";
            $amount = $data['membership_detail']->payable_amount;
            $data['total_amount'] = $amount;
            if($amount < 0){
                toastr()->error('Invoice cannot be generated for negative order amount!.');
                return redirect()->route('webadmin.order.orders');
            }
            if($amount){
                $cgst = $amount - $amount * 100 / 109 ;
                $orignal_amount = $amount - $cgst*2;

                $data['membership_detail']->orignal_amount = number_format($orignal_amount, 2, '.', '');
                $data['membership_detail']->cgst = number_format($cgst, 2, '.', '');
                $data['membership_detail']->sgst = number_format($cgst, 2, '.', '');

                $round_off= $amount - $data['membership_detail']->orignal_amount - $data['membership_detail']->cgst - $data['membership_detail']->cgst;
                $data['membership_detail']->round_off = number_format($round_off , 2, '.', '');
                $data['membership_detail']->amount_in_word = "INR ".$this->AmountInWords($amount)." Only";
                $data['total_amount'] = number_format((float)$data['total_amount'], 2, '.', '');
                $data['membership_detail']->total_amount_in_word = "INR ".$this->AmountInWords($data['total_amount'])." Only";
            }else{
                $data['membership_detail']->cgst = 0;
                $data['membership_detail']->sgst = 0;
                $data['membership_detail']->orignal_amount = 0;
                $data['membership_detail']->round_off = 0;
                $data['membership_detail']->amount_in_word = "";
                $data['membership_detail']->total_amount_in_word = "";
            }
            // dd($data['membership_detail']);
            if($data['user_billing_detail']->gst_zone == "outside"){
                // return view('frontend.invoice.outside_wb',$data);
                $pdf = PDF::loadView('frontend.invoice.outside_wb', $data);
                return $pdf->download(time().'_invoice.pdf');
            }else{
                $pdf = PDF::loadView('frontend.invoice.wb', $data);
                return $pdf->download(time().'_invoice.pdf');
            }
        }else{
            toastr()->error('User did not fill billing details.');
            return redirect()->route('webadmin.order.orders');
        }
        
    }

    public function cancel(Request $request,$id){
        $previousOrder = Order::where('id',$id)->first();
        $saveData = [
            'status' => 'cancel',
            'is_active' => 0
        ];
        $res = $previousOrder->update($saveData);
        if ($res){
            toastr()->success('Order successfully cancel.');
            return redirect()->route('webadmin.order.orders');
        }

        return redirect()->back()->withInput();
    }
    
    //Request Package
    
    public function request_package($id){
        //dd($id);
        $package = Packagecorn::where('id',$id)->first();
        $saveData = [
            'status' => 0
        ];
        $res = $package->update($saveData);
        if ($res){
            toastr()->success('Package request successfull');
            return redirect()->route('webadmin.order.orders');
        }else{
            toastr()->error('Package request faild! Please try again');
            return redirect()->back();
        }
    }
    
    //User Purchase History all details (Notebook, Webinar etc)
    
    public function export_order_data_by_user()
    {
        $data['users'] = User::get();
        return view('admin.order.exportorderdatabyuser',$data);
    }
    
    public function exportorderdatabyuser(Request $request)
    {
        $input = $request->all();
        
        $user = User::where('id',$input['user_id'])->first();
        
        $from = date($input['date_from']);
        $to = date($input['date_to']);
        $orders = Order::where('user_id', $user->id)->whereBetween('created_at', [$from, $to])->get();
        
        //$orders = Order::where('user_id', $user->id)->get();
        
        Userpurchasehistoryexport::truncate();
        $count = 0;
        if($count == 0){
            $saveLable = $saveData = array(
                'name' => 'User Name',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone_no' => 'Phone No',
                'city' => 'City',
                'invoice_id' => 'Invoice Number',
                'coupon_code' => 'Coupon Code',
                'coupon_amount' => 'Coupon Amount',
                'total_amount' => 'Total Amount',
                'payable_amount' => 'Payable Amount',
                'status' => 'Status',
                'created_at' => 'Created On',
                'updated_at' => 'Updated On',
                'order_date' => 'Purchase Date',
                'product_name' => 'Product Name',
                'quantity' => 'Quantity',
                'product_price' => 'Product Price',
            );
            
            Userpurchasehistoryexport::create($saveLable);
            
        }
        
        if(isset($input['separate']) && $input['separate']=="1"){
            foreach($orders as $order){
                
                $created_at = date('d-m-Y', strtotime($order['created_at']));
                $updated_at = date('d-m-Y', strtotime($order['updated_at']));
                
                    
                    $saveData = array(
                        'name' => $user['name'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'phone_no' => $user['phone_no'],
                        'city' => $user['city'],
                        'invoice_id' => $order['invoice_id'],
                        'coupon_code' => $order['coupon_code'],
                        'coupon_amount' => $order['coupon_amount'],
                        'total_amount' => $order['total_amount'],
                        'payable_amount' => $order['payable_amount'],
                        'status' => $order['status'],
                        //'created_at' => $created_at,
                        //'updated_at' => $updated_at,
                        'order_date' => $created_at,
                    );
                    
                    
                    
                    $orderSave = Userpurchasehistoryexport::create($saveData);
                    
                    $orderproducts = Orderitem::where('order_id',$order['id'])->get();
                    foreach($orderproducts as $orderproduct){
                        
                        $saveDataorderProduct = array(
                            'product_name' => $orderproduct['name'],
                            'quantity' => $orderproduct['quantity'],
                            'product_price' => $orderproduct['price'],
                        );
                        
                        $orderProductSave = Userpurchasehistoryexport::create($saveDataorderProduct);
                        
                    }
                    
                $count++;
            }
        }else{
            
            foreach($orders as $order){
                
                $created_at = date('d-m-Y', strtotime($order['created_at']));
                $updated_at = date('d-m-Y', strtotime($order['updated_at']));
                    
                    $orderproducts = Orderitem::where('order_id',$order['id'])->get();
                    foreach($orderproducts as $orderproduct){
                        
                        $saveDataorderProduct = array(
                            'name' => $user['name'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'email' => $user['email'],
                            'phone_no' => $user['phone_no'],
                            'city' => $user['city'],
                            'invoice_id' => $order['invoice_id'],
                            'coupon_code' => $order['coupon_code'],
                            'coupon_amount' => $order['coupon_amount'],
                            'total_amount' => $order['total_amount'],
                            'payable_amount' => $order['payable_amount'],
                            'status' => $order['status'],
                            //'created_at' => $created_at,
                            //'updated_at' => $updated_at,
                            'order_date' => $created_at,
                            'product_name' => $orderproduct['name'],
                            'quantity' => $orderproduct['quantity'],
                            'product_price' => $orderproduct['price'],
                        );
                        
                        $orderProductSave = Userpurchasehistoryexport::create($saveDataorderProduct);
                        
                    }
                    
                $count++;
            }
        }
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-user-list-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-user-list-'.date('d-m-Y').'.xlsx');
        }
    }
    
    // Period Wise Purchase details of order
    public function export_order_data_datewise()
    {
        return view('admin.order.exportorderdatabydatewise');
    }
    
    public function exportorderdatadatewise(Request $request)
    {
        $input = $request->all();
        $from = date($input['date_from']);
        $to = date($input['date_to']);
        $orders = Order::whereBetween('created_at', [$from, $to])->get();
        
        Userpurchasehistoryexport::truncate();
        $count = 0;
        if($count == 0){
            $saveLable = $saveData = array(
                'name' => 'User Name',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone_no' => 'Phone No',
                'city' => 'City',
                'invoice_id' => 'Invoice Number',
                'coupon_code' => 'Coupon Code',
                'coupon_amount' => 'Coupon Amount',
                'total_amount' => 'Total Amount',
                'payable_amount' => 'Payable Amount',
                'status' => 'Status',
                'created_at' => 'Created On',
                'updated_at' => 'Updated On',
                'order_date' => 'Purchase Date',
                'product_name' => 'Product Name',
                'quantity' => 'Quantity',
                'product_price' => 'Product Price',
            );
            
            Userpurchasehistoryexport::create($saveLable);
            
        }
        if(isset($input['separate']) && $input['separate']=="1"){
            foreach($orders as $order){
                
                $user = User::where('id',$order['user_id'])->first();
                
                $created_at = date('d-m-Y', strtotime($order['created_at']));
                $updated_at = date('d-m-Y', strtotime($order['updated_at']));
                
                
                    
                    $saveData = array(
                        'name' => $user['name'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'phone_no' => $user['phone_no'],
                        'city' => $user['city'],
                        'invoice_id' => $order['invoice_id'],
                        'coupon_code' => $order['coupon_code'],
                        'coupon_amount' => $order['coupon_amount'],
                        'total_amount' => $order['total_amount'],
                        'payable_amount' => $order['payable_amount'],
                        'status' => $order['status'],
                        //'created_at' => $created_at,
                        //'updated_at' => $updated_at,
                        'order_date' => $created_at,
                    );
                    
                    $orderSave = Userpurchasehistoryexport::create($saveData);
                    
                    $orderproducts = Orderitem::where('order_id',$order['id'])->get();
                    foreach($orderproducts as $orderproduct){
                        
                        $saveDataorderProduct = array(
                            'product_name' => $orderproduct['name'],
                            'quantity' => $orderproduct['quantity'],
                            'product_price' => $orderproduct['price'],
                        );
                        
                        $orderProductSave = Userpurchasehistoryexport::create($saveDataorderProduct);
                        
                    }
                    
                    
                $count++;
            }
        
        }else{
            
            foreach($orders as $order){
                
                $user = User::where('id',$order['user_id'])->first();
                
                $created_at = date('d-m-Y', strtotime($order['created_at']));
                $updated_at = date('d-m-Y', strtotime($order['updated_at']));
                    
                    $orderproducts = Orderitem::where('order_id',$order['id'])->get();
                    foreach($orderproducts as $orderproduct){
                        
                        $saveDataorderProduct = array(
                            'name' => $user['name'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'email' => $user['email'],
                            'phone_no' => $user['phone_no'],
                            'city' => $user['city'],
                            'invoice_id' => $order['invoice_id'],
                            'coupon_code' => $order['coupon_code'],
                            'coupon_amount' => $order['coupon_amount'],
                            'total_amount' => $order['total_amount'],
                            'payable_amount' => $order['payable_amount'],
                            'status' => $order['status'],
                            //'created_at' => $created_at,
                            //'updated_at' => $updated_at,
                            'order_date' => $created_at,
                            'product_name' => $orderproduct['name'],
                            'quantity' => $orderproduct['quantity'],
                            'product_price' => $orderproduct['price'],
                        );
                        
                        $orderProductSave = Userpurchasehistoryexport::create($saveDataorderProduct);
                        
                    }
                    
                    
                $count++;
            }
            
        }
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-datewise-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-datewise-'.date('d-m-Y').'.xlsx');
        }
    }
    
    //Purchase History by Products (Notebook, Webinar etc)
    
    public function export_order_data_by_product()
    {
        $data['products'] = Stationary::get();
        return view('admin.order.exportorderdatabyproduct',$data);
    }
    
    public function exportorderdatabyproduct(Request $request)
    {
        $input = $request->all();
        //dd($input['products'][0]);
        $from = date($input['date_from']);
        $to = date($input['date_to']);
        
        Userpurchasehistoryexport::truncate();
        
        $saveLable = $saveData = array(
                'name' => 'User Name',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'email' => 'Email',
                'phone_no' => 'Phone No',
                'city' => 'City',
                'invoice_id' => 'Invoice Number',
                'coupon_code' => 'Coupon Code',
                'coupon_amount' => 'Coupon Amount',
                'total_amount' => 'Total Amount',
                'payable_amount' => 'Payable Amount',
                'status' => 'Status',
                'created_at' => 'Created On',
                'updated_at' => 'Updated On',
                'order_date' => 'Purchase Date',
                'product_name' => 'Product Name',
                'quantity' => 'Quantity',
                'product_price' => 'Product Price',
            );
            
            Userpurchasehistoryexport::create($saveLable);
            foreach($input['products'] as $product){
                $orderItems = Orderitem::where('name',$product)->whereBetween('created_at', [$from, $to])->get();
                //dd($orderItems);
                
                foreach($orderItems as $orderItem){
                    
                    $order = Order::where('id',$orderItem['order_id'])->first();
                    
                    //dd($order->user_id);
                    
                    $user = User::where('id',$order->user_id)->first();
                    
                    
                
                    $created_at = date('d-m-Y', strtotime($order->created_at));
                    $updated_at = date('d-m-Y', strtotime($order->updated_at));
                    
                    //dd($user['name']);
                    
                    //$orderproducts = Orderitem::where('order_id',$order['id'])->get();
                    //foreach($orderproducts as $orderproduct){
                        
                        $saveDataorderProduct = array(
                            'name' => $user['name'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'email' => $user['email'],
                            'phone_no' => $user['phone_no'],
                            'city' => $user['city'],
                            'invoice_id' => $order->invoice_id,
                            'coupon_code' => $order->coupon_code,
                            'coupon_amount' => $order->coupon_amount,
                            'total_amount' => $order->total_amount,
                            'payable_amount' => $order->payable_amount,
                            'status' => $order->status,
                            //'created_at' => $created_at,
                            //'updated_at' => $updated_at,
                            'order_date' => $created_at,
                            'product_name' => $orderItem['name'],
                            'quantity' => $orderItem['quantity'],
                            'product_price' => $orderItem['price'],
                        );
                        
                        //dd($saveDataorderProduct);
                        
                        $orderProductSave = Userpurchasehistoryexport::create($saveDataorderProduct);
                        
                    //}
                    
                    
                }
            }
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-productwise-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UserpurchasehistorydataExport, 'order-data-by-productwise-'.date('d-m-Y').'.xlsx');
        }
    }



    public function status_index(Request $request){
        if ($request->ajax()) {
            $data = OrderStatus::latest()->get();

            // dd($data);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    if(!empty($row->created_at)){
                        $created_at = date('d-m-Y',strtotime($row->created_at));
                    }

                    return $created_at;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $btn = '';
                    if(is_permitted('order-management-orderstatus', 'edit')){
                    $btn = '<a href="'.route('webadmin.orderStatusEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    }
                    if(is_permitted('order-management-orderstatus', 'delete')){
                    $btn .= '<a href="'.route('webadmin.orderStatusDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    }
                    return $btn;
                })
                ->rawColumns(['created_at','status','action'])
                ->make(true);
        }
        return view('admin.order.status_index');
    }

    public function status_add(){
        $data = [];
        return view('admin.order.status_add',$data);
    }

    public function status_save(Request $request){
        
        $request->validate([
            'name' => 'required'
        ]);

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];

        $res = OrderStatus::create($saveData);
        if ($res){
            toastr()->success('Order status successfully saved.');
            return redirect()->route('webadmin.orderStatus');
        }

        return redirect()->back()->withInput();
    }

    public function status_edit($id){
        $data['stationary'] = OrderStatus::where('id',$id)->first();
        return view('admin.order.status_edit',$data);
    }

    public function status_update(Request $request,$id){
        $request->validate([
            'name' => 'required'
        ]);

        $previousStationary = OrderStatus::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.orderStatus');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            'description' => $input['description'],
            'is_active' => isset($input['status'])?1:0
        ];


        $res = $previousStationary->update($saveData);
        if ($res){
            toastr()->success('Order status successfully updated.');
            return redirect()->route('webadmin.orderStatus');
        }

        return redirect()->back()->withInput();
    }

    public function status_delete(Request $request,$id){
        $previousStationary = OrderStatus::where('id',$id)->first();
        if (!$previousStationary){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.orderStatus');
        }

        $res = $previousStationary->delete();
        if ($res){
            toastr()->success('Order status successfully deleted.');
            return redirect()->route('webadmin.orderStatus');
        }

        return redirect()->back()->withInput();
    }

}
