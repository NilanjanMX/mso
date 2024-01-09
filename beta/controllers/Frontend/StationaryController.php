<?php

namespace App\Http\Controllers\Frontend;

use PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Stationary;
use App\Models\Productcategory;
use App\Models\Billingaddress;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Coupon;
use Session;
use Auth;
use DB;
use App\User;
use App\Models\Membership;
use App\Models\Packageimage;
use App\Models\Packagecorn;
use App\Models\Packagepremade;
use App\Models\ReferralCode;
use App\Models\ReferralCodeSetting;
use App\Models\StationaryGroup as StationaryGroupModel;
use App\Models\StationaryGroupUser;
use App\Models\StationaryGiftCard;
use App\Models\HistoryOrder;
use App\Models\UserCarts;

use Illuminate\Support\Facades\Mail;

class StationaryController extends Controller
{
    public function index(){
        $stationarie = Stationary::select(['stationaries.*','stationary_product_types.name as product_type_name'])
                    ->LeftJoin('stationary_product_types', 'stationary_product_types.id', '=', 'stationaries.product_type_id')
                    ->where('stationaries.is_active',1)->orderBy('stationaries.id','desc')->get();

        $stationaries = [];

        $user_id = "";
        $package_id = "";
        $user_status = 0;
        if (Auth::check()){
            $user_id = Auth::user()->id;
            $package_id = Auth::user()->package_id;
            
            $membership_detail = Membership::where('user_id',$user_id)->where('is_active',1)->first();
            // dd($membership_detail);
            $membership_date=strtotime($membership_detail->expire_at);
            $current_date=strtotime(date('Y-m-d'));
            if($membership_date > $current_date && Auth::user()->is_active == 1){
                $user_status = 1;
            }
        }

        foreach ($stationarie as $key => $value) {
            // dd($user_status);
            if($user_status == 1){
                if($value->user_status == 0 || $value->user_status == 1){
                    if($value->group_type == NULL || $value->group_type == 'all'){
                        array_push($stationaries, $value);
                    }else if($value->group_type == 'user_type'){
                        $group_ids = explode(",", $value->group_ids);
                        // dd($group_ids);
                        foreach ($group_ids as $key => $value1) {
                            if($value1 == $package_id){
                                array_push($stationaries, $value);
                            }
                        }
                    }else if($value->group_type == 'group'){
                        $group_ids = explode(",", $value->group_ids);
                        $group_user = StationaryGroupUser::whereIn('group_id',$group_ids)->where('user_id',$user_id)->count();
                        //dd($group_user);
                        if($group_user){
                            array_push($stationaries, $value);
                        }
                    }
                }else{
                    
                }
            }else if($user_status == 0){ 
                if($value->user_status == 0 || $value->user_status == 2){
                    if($value->group_type == NULL || $value->group_type == 'all'){
                        array_push($stationaries, $value);
                    }else if($value->group_type == 'user_type'){
                        $group_ids = explode(",", $value->group_ids);
                        // dd($group_ids);
                        foreach ($group_ids as $key => $value1) {
                            if($value1 == $package_id){
                                array_push($stationaries, $value);
                            }
                        }
                    }else if($value->group_type == 'group'){
                        $group_ids = explode(",", $value->group_ids);
                        $group_user = StationaryGroupUser::whereIn('group_id',$group_ids)->where('user_id',$user_id)->count();
                        //dd($group_user);
                        if($group_user){
                            array_push($stationaries, $value);
                        }
                    }
                }else{
                    
                }
            }else{
                if($value->group_type == NULL || $value->group_type == 'all' || $value->user_status == 0){
                    array_push($stationaries, $value);
                }
            }
            
        }
        
        return view('frontend.ifatools.stationary.index')->with(compact('stationaries'));
    }
    
    public function details($slug){
        $stationary = Stationary::where('slug',$slug)->first();
        $stationary->pdf_file_free = "";
        $stationary->pdf_file_free_landscape = "";
        if($stationary->store_type == "premade"){
            $premade  = Packagepremade::select(['pdf_file_free','pdf_file_free_landscape'])->where('stationary_id','=',$stationary->id)->first();
            if($premade){
                $stationary->pdf_file_free = $premade->pdf_file_free;
                $stationary->pdf_file_free_landscape = $premade->pdf_file_free_landscape;
            }
        }
        // dd($stationary);
        return view('frontend.ifatools.stationary.details')->with(compact('stationary'));
    }

    public function viewPdf($pdf_name){
        $data = [];
        $data['pdf'] = $pdf_name;
        return view('frontend.ifatools.stationary.pdf_details',$data);
    }

    public function premadesalespresenters(){
        $productcategories = Productcategory::where('is_active',1)->get();
        return view('frontend.premadesalespresenters.index')->with(compact('productcategories'));
    }

    public function premadesalespresenters_products($slug){
        $category = Productcategory::where('slug',$slug)->first();
        $stationaries = Stationary::where('is_active',1)->where('caetegory_id',$category->id)->orderBy('id','desc')->get();
        return view('frontend.premadesalespresenters.products')->with(compact('stationaries'));
    }

    //add to cart

    public function addToCart($id)
    {
        //dd($id);
        $product = Stationary::find($id);
        // Session::forget('cart');
        if(!$product) {
            abort(404);
        }

        $user_id = 0;
        if(Auth::user()){
            $user_id = Auth::user()->id;
        }

        $ip_address = getIp();
        HistoryOrder::create([
            'page_id' => $id,
            'user_id' => $user_id,
            'page_type' => "ORDER",
            'value' => "ORDER CART",
            'ip' => $ip_address
        ]);
        
        if(Auth::user()){
            $user_id = Auth::user()->id;
            $userCarts = UserCarts::where('user_id',$user_id)->where('product_id',$id)->first();

            if($userCarts){
                $inserData = [
                    "quantity" => $userCarts->quantity+1
                ];
                UserCarts::where('user_id',$user_id)->where('product_id',$id)->update($inserData);
            }else{
                $inserData = [
                    "product_id" => $id,
                    "user_id" => $user_id,
                    "name" => $product->title,
                    "quantity" => 1,
                    "price" => $product->amount,
                    "photo" => $product->product_image,
                    "mrp" => $product->mrp,
                    "offer_price" => $product->offer_price,
                    "member_price" => $product->member_price,
                    "coupon_code" => '',
                    "coupon_discount" => 0,
                    "total_product_amount" => $product->amount
                ];

                UserCarts::create($inserData);
            }
        }else{
            $cart_user_id = session()->get('cart_user_id');
            if(!$cart_user_id){
                $cart_user_id = time().rand(99999,99999999999);
                session()->put('cart_user_id', $cart_user_id);
            }
            $userCarts = UserCarts::where('cart_user_id',$cart_user_id)->where('product_id',$id)->first();

            if($userCarts){
                $inserData = [
                    "quantity" => $userCarts->quantity+1
                ];
                UserCarts::where('cart_user_id',$cart_user_id)->where('product_id',$id)->update($inserData);
            }else{
                $inserData = [
                    "product_id" => $id,
                    "cart_user_id" => $cart_user_id,
                    "name" => $product->title,
                    "quantity" => 1,
                    "price" => $product->amount,
                    "photo" => $product->product_image,
                    "mrp" => $product->mrp,
                    "offer_price" => $product->offer_price,
                    "member_price" => $product->member_price,
                    "coupon_code" => '',
                    "coupon_discount" => 0,
                    "total_product_amount" => $product->amount
                ];

                UserCarts::create($inserData);
            }
        }

            
 
        return redirect()->back()->with('successcart', 'Product added to cart successfully!');

    }

    public function addToCartFromDetails(Request $request)
    {
        $input = $request->all();
        
        //dd($input);
        $id=$input['id'];
        $product = Stationary::find($id);
 
        if(!$product) {
 
            abort(404);
 
        }
 
        $cart = session()->get('cart');

        $user_id = 0;
        if(Auth::user()){
            $user_id = Auth::user()->id;
        }

        $ip_address = getIp();
        HistoryOrder::create([
            'page_id' => $id,
            'user_id' => $user_id,
            'page_type' => "ORDER",
            'value' => "ORDER CART",
            'ip' => $ip_address
        ]);
 
        if(Auth::user()){
            $user_id = Auth::user()->id;
            $userCarts = UserCarts::where('user_id',$user_id)->where('product_id',$id)->first();

            if($userCarts){
                $inserData = [
                    "quantity" => $userCarts->quantity+$input['quantity']
                ];
                UserCarts::where('user_id',$user_id)->where('product_id',$id)->update($inserData);
            }else{
                $inserData = [
                    "product_id" => $id,
                    "user_id" => $user_id,
                    "name" => $product->title,
                    "quantity" => $input['quantity'],
                    "price" => $product->amount,
                    "photo" => $product->product_image,
                    "mrp" => $product->mrp,
                    "offer_price" => $product->offer_price,
                    "member_price" => $product->member_price,
                    "coupon_code" => '',
                    "coupon_discount" => 0,
                    "total_product_amount" => $product->amount
                ];

                UserCarts::create($inserData);
            }
        }else{
            $cart_user_id = session()->get('cart_user_id');
            if(!$cart_user_id){
                $cart_user_id = time().rand(99999,99999999999);
                session()->put('cart_user_id', $cart_user_id);
            }
            $userCarts = UserCarts::where('cart_user_id',$cart_user_id)->where('product_id',$id)->first();

            if($userCarts){
                $inserData = [
                    "quantity" => $userCarts->quantity+$input['quantity']
                ];
                UserCarts::where('cart_user_id',$cart_user_id)->where('product_id',$id)->update($inserData);
            }else{
                $inserData = [
                    "product_id" => $id,
                    "cart_user_id" => $cart_user_id,
                    "name" => $product->title,
                    "quantity" => $input['quantity'],
                    "price" => $product->amount,
                    "photo" => $product->product_image,
                    "mrp" => $product->mrp,
                    "offer_price" => $product->offer_price,
                    "member_price" => $product->member_price,
                    "coupon_code" => '',
                    "coupon_discount" => 0,
                    "total_product_amount" => $product->amount
                ];

                UserCarts::create($inserData);
            }
        }
 
        
        return redirect('cart')->with('success', 'Product added to cart successfully!');

    }

    public function cart(){
        //dd("ok");
        //session()->forget('cart');
        $AllCart = session()->get('cart');
        $couponDetails = session()->get('couponDetails');
        // dd($couponDetails);
        $is_price_modal = false;
        $user = Auth::user();
        $wallet_amount = 0;
        if($user){

            $user_id = $user->id;

            $cart_user_id = session()->get('cart_user_id');
            if($cart_user_id){
                $cart = UserCarts::where('cart_user_id',$cart_user_id)->get();
                //dd($cart);
                foreach ($cart as $key => $value) {
                    $cart_de = UserCarts::where('user_id',$user_id)->where('product_id',$value->product_id)->get();
                    if(!$cart_de){
                        $inserData = [
                            "user_id" => $user_id,
                            "cart_user_id" => NULL
                        ];
                        UserCarts::where('id',$value->id)->update($inserData);
                    }
                }
            }
            $cart = UserCarts::select(['user_carts.*','stationaries.type','stationaries.offer_price','stationaries.member_price','stationaries.amount'])->LeftJoin('stationaries', 'user_carts.product_id', '=', 'stationaries.id')->where('user_id',$user_id)->get();
            
            $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('is_active',1)->count();
            // dd($membership);
            if($membership > 0 && !empty($cart)){
                $carts = array();
                foreach($cart as $key => $cartItem){
                    //dd($cartItem['quantity']);
                    // dd($cartItem);
                    $product = Stationary::find($cartItem->product_id);
                    if($product){
                        $total_product_amount = 0;
                        $coupon_discount = 0;
    
                        if(!empty($product->expired_at) && $product->expired_at >= date('Y-m-d')){
                            if($product->member_coupon_type == 'percentage'){
                                if($product->member_coupon_value >0){
                                    $coupon_discount = ($product->amount*$cartItem['quantity']*$product->member_coupon_value)/100;
                                }
                                
                            }elseif($product->member_coupon_type == 'flat'){
                                if($product->member_coupon_value >0){
                                    $coupon_discount = $cartItem['quantity']*$product->member_coupon_value;
                                }
                            }
                        }
    
                        $total_product_amount = $product->amount*$cartItem['quantity']-$coupon_discount;
                        
                        
                        if($cartItem['price'] != $product->amount){
                            if(!$is_price_modal){
                                $is_price_modal = true;
                            }
                        }
                    }
                }
                //$user->package_id == 17 && !
                if($user->user_id == NULL){
                    $membership_point_plus = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
                    $membership_point_mins = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
                    $total_membership_point = $membership_point_plus - $membership_point_mins;
                    $referralCodeSetting = ReferralCodeSetting::where('id','=',3)->first();
                    $wallet_amount = $total_membership_point / $referralCodeSetting->value;
                    
                    
                }else{
                    $wallet_amount = 0;
                }
            }
        }else{
            $cart_user_id = session()->get('cart_user_id');
            $cart = UserCarts::select(['user_carts.*','stationaries.type','stationaries.offer_price','stationaries.member_price','stationaries.amount'])->LeftJoin('stationaries', 'user_carts.product_id', '=', 'stationaries.id')->where('cart_user_id',$cart_user_id)->get();
        }
        
        Session::forget('check_point_demo');
        
        return view('frontend.ifatools.stationary.cart')->with(compact('cart','couponDetails','wallet_amount','is_price_modal'));
    }

    public function removeFromCart($id){
        if($id) {
            UserCarts::where('id',$id)->delete(); 
        }
        return redirect()->back()->with('success', 'Product removed successfully');
    }

    public function updateCart(Request $request)
    {
        $data = $request->all();
        // dd($data);
        foreach($data['quantity'] as $keyinput => $quantity)
        {
            $inserData = [
                "quantity"=>$quantity
            ];
            UserCarts::where('id',$data['key'][$keyinput])->update($inserData);    
        }
        return redirect()->back()->with('success', 'Cart updated successfully');
        
    }

    public function processToCheckout(){
        $user_id = Auth::user()->id;
        $cart_user_id = session()->get('cart_user_id');
        // dd($cart_user_id);
        if($cart_user_id){
            $cart = UserCarts::where('cart_user_id',$cart_user_id)->get();
            
            foreach ($cart as $key => $value) {
                $cart_de = UserCarts::where('user_id',$user_id)->where('product_id',$value->product_id)->first();
                // dd($cart_de);
                if(!$cart_de){
                    $inserData = [
                        "user_id" => $user_id,
                        "cart_user_id" => NULL
                    ];
                    UserCarts::where('id',$value->id)->update($inserData);
                }
            }
            session()->forget('cart_user_id');
        }
        $cart = UserCarts::select(['user_carts.*','stationaries.type','stationaries.offer_price','stationaries.member_price','stationaries.amount'])->LeftJoin('stationaries', 'user_carts.product_id', '=', 'stationaries.id')->where('user_id',$user_id)->get();
        if(empty($cart)){
            return redirect()->route('frontend.cart');
        }
        $user = Auth::user();
        $couponDetails = session()->get('couponDetails');
        $billingAddress = Auth::user();
        $billingAddress = Billingaddress::where('user_id',$billingAddress->id)->orderBy('id','desc')->first();
        if(empty($billingAddress)){
            $billingAddress = Auth::user();
        }
        $wallet_amount = 0;
        if(session()->get('check_point_demo')){
            $membership_point_plus = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
            $membership_point_mins = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
            $total_membership_point = $membership_point_plus - $membership_point_mins;
            $referralCodeSetting = ReferralCodeSetting::where('id','=',3)->first();
            $wallet_amount = $total_membership_point / $referralCodeSetting->value;
        }
        $gst_number = $user->gst_number;

        $user_id = 0;
        if(Auth::user()){
            $user_id = Auth::user()->id;
        }

        $ip_address = getIp();
        HistoryOrder::create([
            'user_id' => $user_id,
            'page_type' => "ORDER",
            'value' => "CHECKOUT INITIATED",
            'ip' => $ip_address
        ]);

        return view('frontend.ifatools.stationary.checkout')->with(compact('billingAddress','cart','couponDetails','wallet_amount','gst_number'));
    }

    public function payment(Request $request){
        //dd("ok");
        $billing_info = $request->all();
        // dd($billing_info);
        $request->validate([
            'name' => 'required',
            'street_name' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'phone_no' => 'required',
            'email' => 'required',
        ]);
        
        //session()->put('billing_info', $billing_info);
        $user = Auth::user();
        $billingAddressData = array(
            'user_id' => $user->id,
            'name' => $billing_info['name'],
            'company_name' => $billing_info['company_name'],
            'country' => $billing_info['country'],
            'street_name' => $billing_info['street_name'],
            'city' => $billing_info['city'],
            'state' => $billing_info['state'],
            'zip_code' => $billing_info['zip_code'],
            'phone_no' => $billing_info['phone_no'],
            'email' => $billing_info['email'],
            'additional_info' => $billing_info['additional_info']
        );
        
        $billlingRes = Billingaddress::create($billingAddressData);
        $billingAddress_id = $billlingRes->id;

        User::where("id",$user->id)->update(["gst_number"=>$request->gst_number]);
        
        $order_id = $user->id.rand(1000,1000000);
        $amount =  $billing_info['payable_amount'];
        $wallet_amount =  ($billing_info['wallet_amount'])?$billing_info['wallet_amount']:0;

        if($amount > 0){
            $payment = PaytmWallet::with('receive');
            
            $payment->prepare([
              'order' => $order_id,
              'user' => $user->id,
              'mobile_number' => $billing_info['phone_no'],
              'email' => $billing_info['email'],
              'amount' => (int) $amount,
              'callback_url' => url('api/v1/payment/process/'.$user->id.'/'.$billingAddress_id.'/'.$wallet_amount)
            ]);
            return $payment->receive();  
        }else{
            $orderData = array(
                'user_id' => $user->id,
                'invoice_id' => $order_id,
                'billingaddress_id' => $billingAddress_id,
                'coupon_code' => '',
                'coupon_amount' => 0,
                'total_amount' => $amount+$wallet_amount,
                'payable_amount' => $amount,
                'payment_status' => 'success',
                'status' => 'pending',
                'is_active' => 1
            );
            
            $orderRes = Order::create($orderData);
            $order_id = $orderRes->id;
            
            if($wallet_amount){
                $referralCodeSetting = ReferralCodeSetting::where('id',3)->first();                
                $referral_amount = $wallet_amount * $referralCodeSetting->value;
                $inserData = [];
                $total_amount_plus = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
                $total_amount_mins = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
                $total_amount = $total_amount_plus - $total_amount_mins;
                $total_amount = $total_amount - $referral_amount;
                $inserData['user_id'] = $user->id;
                $inserData['referral_amount'] = $referral_amount;
                $inserData['total_amount'] = $total_amount;
                $inserData['note'] = "Store Purchase";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }
            return redirect()->route('frontend.stationary.purchase.success',$order_id);
        }

        

    }

    public function paymentCallback($user_id, $billingAddress_id,$wallet_amount)
    {
        //dd($billingAddress_id);
        $transaction = PaytmWallet::with('receive');
 
        $response = $transaction->response();
        // dd($response);
        if($transaction->isSuccessful()){
            //dd($response);
            $amount= $response['TXNAMOUNT'];
            $orderData = array(
                'user_id' => $user_id,
                'invoice_id' => $response['ORDERID'],
                'billingaddress_id' => $billingAddress_id,
                'coupon_code' => '',
                'coupon_amount' => 0,
                'total_amount' => $response['TXNAMOUNT']+$wallet_amount,
                'payable_amount' => $response['TXNAMOUNT'],
                'payment_status' => 'success',
                'status' => 'pending',
                'is_active' => 1
            );
            
            $orderRes = Order::create($orderData);
            $order_id = $orderRes->id;
            // $order_id = 319;
            
            if($wallet_amount){
                $referralCodeSetting = ReferralCodeSetting::where('id',3)->first();                
                $referral_amount = $wallet_amount * $referralCodeSetting->value;
                $inserData = [];
                $total_amount_plus = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
                $total_amount_mins = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
                $total_amount = $total_amount_plus - $total_amount_mins;
                $total_amount = $total_amount - $referral_amount;
                $inserData['user_id'] = $user_id;
                $inserData['referral_amount'] = $referral_amount;
                $inserData['total_amount'] = $total_amount;
                $inserData['note'] = "Store Purchase";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }


            if($amount){
                $referralCodeSetting = ReferralCodeSetting::where('id',2)->first();
                // dd($referralCodeSetting);
                $referral_amount = 0;
                $membership_point_email = "";
                if($referralCodeSetting){
                    if($referralCodeSetting->type_name == 1){
                        $gst_amount = $amount - ($amount*100/118);
                        $amount = $amount - $gst_amount;
                        $referral_amount = (int) $amount * $referralCodeSetting->value/100;
                        $membership_point_email = $referralCodeSetting->value."%";
                    }else{
                        $referral_amount = $referralCodeSetting->value;
                        $membership_point_email = $referral_amount;
                    }
                }
                // dd($referralCodeSetting);
                $total_amount_plus = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
                $total_amount_mins = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
                $total_amount = $total_amount_plus - $total_amount_mins;
                $total_amount = $total_amount + $referral_amount;
                $inserData['user_id'] = $user_id;
                $inserData['referral_amount'] = $referral_amount;
                $inserData['total_amount'] = $total_amount;
                $inserData['note'] = "Store Purchase";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 0;
                ReferralCode::create($inserData);

                $user = User::where("id",$user_id)->first();
                $email = $user->email;
                $dynamic_email = DB::table("dynamic_email")->where('id',3)->first();
                $messageData = ['name'=>$user->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"membership_point_email"=>$referral_amount,"total_amount"=>$amount];
                
                Mail::send('emails.membership-point',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }
            return redirect()->route('frontend.stationary.purchase.success',$order_id);
 
        }else if($transaction->isFailed()){
            return redirect()->route('frontend.stationary.process-to-checkout')->with('error','There have some issues. Please try again');
        }
    }

    public function thankyou($order_id){
        $user = Auth::user();
        $cart = UserCarts::select(['user_carts.*','stationaries.type','stationaries.offer_price','stationaries.member_price','stationaries.amount'])->LeftJoin('stationaries', 'user_carts.product_id', '=', 'stationaries.id')->where('user_id',$user->id)->get();
        // dd($cart);
        if(empty($cart)){
            return redirect()->route('frontend.cart');
        }
        
        $package = \App\Models\Membership::where(['user_id' => $user->id])->where('is_active',1)->where('expire_at','>=',date('Y-m-d'))->count();
        // dd($user);
        $total_amount = 0;
        foreach($cart as $key => $cartItem){
            $product_id = $cartItem['product_id'];
            
            $offer_price = 0;
            $member_price = 0;
            if($cartItem['amount']){
                if($cartItem['type'] == 2){
                    $offer_price = $cartItem['amount'] * $cartItem['offer_price'] / 100;
                    $member_price = $cartItem['amount'] * $cartItem['member_price'] / 100;
                }else{
                    $offer_price = $cartItem['offer_price'];
                    $member_price = $cartItem['member_price'];
                }
            }
            
            if($package <= 1){
                $discount_amount = $member_price;
            }else{
                $discount_amount = $offer_price;
            }
            
            $price = $cartItem['price'] - $discount_amount;
            $cartData = array(
                'order_id' => $order_id,
                'product_id' => $product_id,
                'name' => $cartItem['name'],
                'quantity' => $cartItem['quantity'],
                'price' => $price,
                'photo' => $cartItem['photo']
            );
            
            $total_amount = $total_amount+$price*$cartItem['quantity'];
            
            $product = Stationary::find($product_id);
            // dd($product);
            if($product->store_type=='package'){
                $cartData['store_type'] = 'package';
                $date=strtotime(date('Y-m-d')); 
                $expire_at = date('Y-m-d',strtotime('+30 days',$date));
                $cartData['expired_at'] = $expire_at;
                
                $saveData = array(
                    'order_id' => $order_id,
                    'user_id' => $user->id,
                    'product_id' => $product_id,
                    'expired_at' => $expire_at
                );
                $saveRes = Packagecorn::create($saveData);
            }else if($product->store_type=='premade'){
                $cartData['store_type'] = 'premade';
                $date=strtotime(date('Y-m-d')); 
                $expire_at = date('Y-m-d',strtotime('+30 days',$date));
                $cartData['expired_at'] = $expire_at;
                $email = $user->email;
                $order_detail = Order::where('id',$order_id)->first();
                $messageData = [];
                $messageData['name'] = $user->name;
                $messageData['email'] = $user->email;
                $messageData['date'] = date('d/m/Y');
                $messageData['invoice_id'] = $order_detail->invoice_id;
                $messageData['portrait'] = url('premade-download-portrait/'.$product_id.'/'.$user->id);
                $messageData['landscape'] = url('premade-download-landscape/'.$product_id.'/'.$user->id);
                // dd($messageData);
                // return view('emails.premade',$messageData);
                Mail::send('emails.premade',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Pre Made download link from Masterstrokeonline');
                });
            }
            $cartRes = Orderitem::create($cartData);
        }

        $couponDetails = session()->get('couponDetails');
        
        if(!empty($couponDetails)){
            $order = Order::where('id',$order_id)->first();
            $discount_amount = $total_amount - $order->total_amount;
            $updateData = [
                'total_amount' => $total_amount,
                'coupon_code' => $couponDetails['coupon_code'],
                'coupon_amount' => $discount_amount
            ];

            $res = $order->update($updateData);
        }

        $orderDetails = Order::where('id',$order_id)->first();
        
        $order = Order::where('id',$order_id)->first();
        //$user = User::where('id',$user->id)->first();
        $email = $user->email;
        $name = $user->name;
        $phone_no = $user->phone_no;
        $date=date('d/m/Y');
        
        $orderItems = array();
        $orderitems = Orderitem::where('order_id',$order_id)->get();
        //dd($orderitems);
        foreach($orderitems as $orderitem){
            $product = Stationary::where('id',$orderitem->product_id)->first();
            if($product->store_type == 'package'){
                $store_type = 'package';
            }else{
                $store_type = '';
            }
            $orderItem = [
                "product_name" => $orderitem->name,
                "quantity" => strval($orderitem->quantity),
                "price" => strval($orderitem->price),
                "product_id" => strval($orderitem->product_id)
            ];
            array_push($orderItems,$orderItem);
        }
        //dd($orderItems);
        $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'date'=>$date,'order'=>$order,'orderItems'=>$orderItems,'store_type'=>$store_type];
        // return view('emails.order',$messageData);
        //$email_to = 'randhirjha2212@gmail.com';
        $email_to = 'info@masterstrokeonline.com';
        Mail::send('emails.order',$messageData,function($message) use($email,$email_to){
            $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to($email)->cc($email_to)
            ->subject('Order History Of Masterstroke');
        });

        Session::forget('cart');

        Session::forget('couponDetails');
        
        UserCarts::where('user_id',$user->id)->delete(); 

        return view('frontend.ifatools.stationary.thankyou')->with(compact('orderDetails'));
    }

    public function couponverify($coupon_code){
        $couponDetails = Coupon::where('coupon_code',$coupon_code)->first();
        $giftCardDetails = StationaryGiftCard::where('claim_code',$coupon_code)->where('is_active',1)->first();
        if(!empty($couponDetails)){
            $coupon = [];
            if($couponDetails->is_active == 1 && $couponDetails->expired_at >= date('Y-m-d') || $couponDetails->expired_at==''){
                $coupon = [
                    "id" => $couponDetails->id,
                    "coupon_code" => $couponDetails->coupon_code,
                    "coupon_amount" => $couponDetails->coupon_amount,
                    "coupon_type" => $couponDetails->coupon_type,
                    "expired_at" => $couponDetails->expired_at,
                    "is_active" => $couponDetails->is_active
                ];

                session()->put('couponDetails', $coupon);
                $response = session()->get('couponDetails');
            }else{
                $response = 0;
            }
            //$response = true;
        }else if(!empty($giftCardDetails)){
            $user = Auth::user();
            $response = 0;
            if($giftCardDetails->group_type == NULL || $giftCardDetails->group_type == 'all'){
                $coupon = [
                    "id" => $giftCardDetails->id,
                    "coupon_code" => $giftCardDetails->claim_code,
                    "coupon_amount" => $giftCardDetails->amount,
                    "coupon_type" => "gift_card",
                    "is_active" => $giftCardDetails->is_active
                ];
    
                session()->put('couponDetails', $coupon);
                $response = session()->get('couponDetails');
            }else if($giftCardDetails->group_type == 'user_type'){
                $group_ids = explode(",", $giftCardDetails->group_ids);

                foreach ($group_ids as $key => $value) {
                    if($value == $user->package_id){
                        $coupon = [
                            "id" => $giftCardDetails->id,
                            "coupon_code" => $giftCardDetails->claim_code,
                            "coupon_amount" => $giftCardDetails->amount,
                            "coupon_type" => "gift_card",
                            "is_active" => $giftCardDetails->is_active
                        ];
            
                        session()->put('couponDetails', $coupon);
                        $response = session()->get('couponDetails');
                    }
                }
            }else if($giftCardDetails->group_type == 'group'){
                $group_ids = explode(",", $giftCardDetails->group_ids);
                $group_user = StationaryGroupUser::whereIn('group_id',$group_ids)->where('user_id',$user->id)->get();
                // dd($group_user);
                if($group_user){
                    $coupon = [
                        "id" => $giftCardDetails->id,
                        "coupon_code" => $giftCardDetails->claim_code,
                        "coupon_amount" => $giftCardDetails->amount,
                        "coupon_type" => "gift_card",
                        "is_active" => $giftCardDetails->is_active
                    ];
        
                    session()->put('couponDetails', $coupon);
                    $response = session()->get('couponDetails');
                }
            }
            
            
        }else{
            session()->forget('couponDetails');
            $response = 0;
        }
        return $response;
    } 

    public function checkPoint($value){
        session()->put('check_point_demo', $value);
        return $value;
    }
    
    public function package_details($slug){
        
        $stationary = Stationary::where('slug',$slug)->first();
        $data['packageimages'] = Packageimage::where('stationary_id',$stationary->id)->orderBy('id','desc')->paginate(20);
        $data['totalPackageimages'] = Packageimage::where('stationary_id',$stationary->id)->count();
        return view('frontend.ifatools.stationary.packageimages',$data);
        
    }

    
}
