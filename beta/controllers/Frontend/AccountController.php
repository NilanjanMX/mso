<?php

namespace App\Http\Controllers\Frontend;

use PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\Models\Membership;
use App\Models\Billingaddress;
use App\Models\Option;
use App\User;
use Auth;
use Session;
use Illuminate\Support\Facades\Mail;

use App\Models\Cart;
use App\Models\Stationary;
use App\Models\Coupon;
use App\Models\PackageCoupon;
use App\Models\Orderitem;
use App\Models\Order;
use App\Models\ReferralCode;
use App\Models\ReferralCodeSetting;
use App\Models\UserBilling;

use App\Models\Displayinfo;
use App\Models\Adminlogo;
use App\Models\PackageCreationDropdown;
use App\Models\PackageCreationHint;
use App\Models\SalespresenterCover;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use DateTime;
use Hash;
use DB;

class AccountController extends Controller
{
    public function profile(){
       
        $user = Auth::user();
        $adminlogos = Adminlogo::where('is_active',1)->get();
        $left_menu = "profile";
        if(empty($user->id)){
            return redirect('login');
        }else{
            return view('frontend.account.profile')->with(compact('user','adminlogos','left_menu'));
        }
    }

    public function profileUpdate(Request $request,$id){
        //dd($id);
        if ($request->has('update_profile')) {
                $request->validate([
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
                    'company_logo_old' => 'image|mimes:jpeg,png,jpg',
                    //'city' => 'required',
                    //'company_name' => 'required',
                    //'company_logo' => 'dimensions:width=600,height=300'
                ]);
        
                $user = User::where('id',$id)->first();
                //dd($user);
                
                $input = $request->all();       
        
                //dd($input);
                
                if(!isset($input['company_logo']) && empty($input['company_logo'])){
                    $input['company_logo'] = $user->company_logo;
                }
        
                $name = $input['first_name'].' '.$input['last_name'];
        
                $saveData = [
                    'name' => $name,
                    'first_name' => $input['first_name'],
                    'last_name' => $input['last_name'],
                    'phone_no' => $input['phone_no'],
                    'city' => $input['city'],
                    'gst_number' => $input['gst_number'],
                    'company_name' => $input['company_name'],
                    'company_logo' => $input['company_logo'],
                ];
        
                /*if ($image = $request->file('company_logo')){
                    $saveData['company_logo'] = time().'.'.$image->getClientOriginalExtension();
        
                    $destinationPath = public_path('/uploads/logo');
                    $img = Image::make($image->getRealPath());
                    $img->resize(null, 70, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath.'/'.$saveData['company_logo']);
                    $destinationPath = public_path('/uploads/logo/original');
                    $image->move($destinationPath, $saveData['company_logo']);
                    if(isset($user['company_logo']) && !empty($user['company_logo'])){
                        if (file_exists(public_path('uploads/logo/thumbnail/'.$user['company_logo']))) {
                            chmod(public_path('uploads/logo/thumbnail/'.$user['company_logo']), 0644);
                            unlink(public_path('uploads/logo/thumbnail/'.$user['company_logo']));
                        }
                        if (file_exists(public_path('uploads/logo/'.$user['company_logo']))) {
                            chmod(public_path('uploads/logo/'.$user['company_logo']), 0644);
                            unlink(public_path('uploads/logo/'.$user['company_logo']));
                        }
                        if (file_exists(public_path('uploads/logo/original/'.$user['company_logo']))) {
                            chmod(public_path('uploads/logo/original/'.$user['company_logo']), 0644);
                            unlink(public_path('uploads/logo/original/'.$user['company_logo']));
                        }
                    }
                }*/
        
                $res = $user->update($saveData);
        
        
        
                if ($res){
                    //toastr()->success('Profile successfully updated.');
                    return redirect()->back()->with('success','Profile successfully updated.');
                }
        
                return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
        }
        
        if ($request->has('update_logo')) {
            $input = $request->all();  
            $adminlogo = Adminlogo::where('id',$input['company_logo'])->first();
            $user = User::where('id',$id)->first();
            //dd($adminlogo->logo);
            
            $saveData = [
                    'company_logo' => $adminlogo->logo,
                ];
            
            $res = $user->update($saveData);
            
            if ($res){
                return redirect()->back()->with('success','Logo successfully updated.');
            }
    
            return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
            
        }
    }

    public function update_gst_number(Request $request){
        $input = $request->all();       
        $user_id = Auth::user()->id;

        $gst_number = ($request->gst_number)?$request->gst_number:"";
        $saveData = [
            'gst_number' => $gst_number,
        ];

        $res = User::where('id',$user_id)->update($saveData);
        echo $gst_number; exit;
    }
    
    public function profileUpdateLogo(Request $request,$id){
        $input = $request->all();       

        dd($input);
        
        //dd($id);
    }
    
    public function profileRemoveLogo($id){
        
        $user = User::where('id',$id)->first();
        
        $saveData = [
            'company_logo' => '',
        ];

        $res = $user->update($saveData);

        if ($res){
            return redirect()->back()->with('success','Logo successfully removed.');
        }

        return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
    }

    public function display_settings(){
       
        $user = Auth::user();
        
        if(empty($user->id)){
            return redirect('login');
        }else{
            
            $displayInfo = Displayinfo::where('user_id',$user->id)->first();
            $coverImages = SalespresenterCover::where([
                'uploaded_by' => 'A',
            ])->orWhere([
                'user_id'=> $user->id
            ])->where('is_active', 1)->get();

            $left_menu = "display_settings";
            return view('frontend.account.display-settings')->with(compact('displayInfo','left_menu', 'coverImages'));
        }
    }

    public function cover_image_remove( Request $request) {
        SalespresenterCover::where('id', $request->id)->update([
            'is_active' => 0
        ]);

        return response()->json(['success' => 'The cover image was removed'], 200);
    }

    public function imageUpload(Request $request){
        //echo $data = $_POST["image"];
        $input = $request->all();
        $data = $input['image'];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $imageName = time() . '.png';
        $destinationPath = public_path('/uploads/salespresentersoftcopy/'.$imageName);
    
        file_put_contents($destinationPath, $data);

        $url = asset('/uploads/salespresentersoftcopy/'.$imageName);
        
        $latest_data = SalespresenterCover::latest()->first();
        
        $last_insert_data = SalespresenterCover::create([
            'salespresentercategories_id' => 0,
            'title' => 'Cover Page_'.$latest_data->id,
            'slug' => 'cover-page-'.$latest_data->id,
            'image' => $imageName,
            'uploaded_by' => 'U',
            'user_id' => Auth::id(),
            'is_active' => 1,
        ]);

        return response([
            'id' => $last_insert_data->id,
            'url' => $url,
        ]);
    }
    
    public function coverImageUpdate(Request $request,$id){
        // dd($request);
        
        if ($request->has('update_pdf_cover')) {
            $input = $request->all();  
            $adminlogo = SalespresenterCover::where('id', $input['cover_image'])->first();
            $displayInfo = Displayinfo::where('user_id', Auth::id())->first();

            $saveData = [
                    'pdf_cover_image' => $adminlogo->image,
            ];
            
            $res = Displayinfo::where('user_id', Auth::id())->update($saveData);
            // dd($saveData);
            if ($res){
                return redirect()->back()->with('success','PDF Cover Image successfully updated.');
            }
    
            return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
            
        }
    }

    public function user_invoice(Request $request){
        $user = Auth::user();
        $id = $user->id;
        $data['left_menu'] = "invoice";
        $data['invoice_list'] = Membership::where('user_id',$id)->get();
        return view('frontend.account.invoice',$data);
    }

    public function user_management(Request $request){
        $user = Auth::user();

        $data = [];
        $user_list = User::where('user_id',$user->id);
        if($request->search_text){
            $user_list = $user_list->where('name', 'like', '%' . $request->search_text . '%');
        }
        $data['left_menu'] = "user_management";
        $data['search_text'] = $request->search_text;
        $data['user_list'] = $user_list->get();
        $data['user_detail'] = User::where('id',$user->id)->first();
        $data['package_detail'] = PackageCreationDropdown::where("id",$data['user_detail']->package_id)->first();
        // dd($data['package_detail']);
        // dd($data['user_detail']);
        return view('frontend.account.user_management',$data);
    }

    public function save_user_permission(Request $request){
        $input = $request->all();
        $insertData = [
            "permission_sales_presenter"=>isset($input['permission_sales_presenter'])?1:0,
            "permission_calculators_proposals"=>isset($input['permission_calculators_proposals'])?1:0,
            "permission_premium_calculator"=>isset($input['permission_premium_calculator'])?1:0,
            "permission_investment_suitablity_profiler"=>isset($input['permission_investment_suitablity_profiler'])?1:0,
            "permission_marketing_banners"=>isset($input['permission_marketing_banners'])?1:0,
            "permission_marketing_video"=>isset($input['permission_marketing_video'])?1:0,
            "permission_premade_sales_presenter"=>isset($input['permission_premade_sales_presenter'])?1:0,
            "permission_trail_calculators"=>isset($input['permission_trail_calculators'])?1:0,
            "permission_scanner"=>isset($input['permission_scanner'])?1:0,
            "permission_famous_quotes"=>isset($input['permission_famous_quotes'])?1:0
        ];

        $user = User::where('id',$input['permission_user_id'])->first();
        $user->update($insertData);
        return redirect('account/user-management')->with('success','User permission change successfully.');
    }

    public function add_user_management(){
        $data['left_menu'] = "user_management";
        return view('frontend.account.add_user_management',$data);
    }

    public function edit_user_management($id){
        $data['user'] = User::where('id',$id)->first();
        $data['left_menu'] = "user_management";
        return view('frontend.account.edit_user_management',$data);
    }

    public function resend_user_management($id){
        $user = User::where('id',$id)->first();
        $master_user = User::where('id',auth()->user()->id)->first();
        $password = rand(100000, 999999);
        $insertData = [];
        $insertData['password'] = Hash::make($password);
        $email = $user->email;
        $user->update($insertData);
        $messageData = ['email'=>$email,'name'=>$user->name,'master_name'=>$master_user->name,"password"=>$password];
        Mail::send('emails.create_user',$messageData,function($message) use($email){
             $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to($email)->cc('info@masterstrokeonline.com')
            ->subject('Membership with Masterstrokeonline');
        });

        return redirect('account/user-management')->with('success','User resend email successfully.');
    }

    public function save_user_management(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10|unique:users,phone_no',
            'email' => 'required|email|unique:users,email'
        ]);
        $input = $request->all();
        // dd($input);
        $password = rand(100000, 999999);
        $user = Auth::user();
        $name = $input['first_name'].' '.$input['last_name'];
        $insertData = [];
        $insertData['name'] = $name;
        $insertData['first_name'] = $input['first_name'];
        $insertData['last_name'] = $input['last_name'];
        $insertData['phone_no'] = $input['phone_no'];
        $insertData['email'] = $input['email'];
        $insertData['gst_number'] = $input['gst_number'];
        $insertData['package_id'] = $user->package_id;
        $insertData['user_id'] = $user->id;
        $insertData['password'] = Hash::make($password);

        $user = User::create($insertData);
        $last_insert_id = $user->id;
        // dd($insertData);
        $displayinfoData = array(
            'user_id' => $last_insert_id,
            'name' => $name,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone_no' => $input['phone_no'],
            'city' => "",
            'company_name' => "",
            'company_logo' => "",
            'address' => ""
        );

        $displayinfo = Displayinfo::create($displayinfoData);

        $date=strtotime(date('Y-m-d'));
        $expire_at = date('Y-m-d',strtotime('1 year',$date));
        $user = Auth::user();
        $membership_detail = Membership::where('user_id',$user->id)->where('is_active',1)->first();
        $order_id = $last_insert_id.rand(1000,1000000);
        $ip_address = getIp();

        $membershipData = array(
            'user_id' => $last_insert_id,
            'subscription_id' => $user->package_id,
            'package_id' => $user->package_id,
            'order_id' => $order_id,
            'subscription_type' => 'paid',
            'amount' => 0,
            'duration' => 0,
            'duration_name' => 'year',
            'expire_at' => $membership_detail->expire_at,
            'is_active' => 1,
            'type' => 1,
            'ip_address' => $ip_address,
            'is_paid' => 0
        );

        $membership = Membership::create($membershipData);
        $email = $input['email'];
        $messageData = ['email'=>$email,'name'=>$name,"password"=>$password,"master_name"=>$user->name];
        // return view('emails.corporate_subscription',$messageData);
        Mail::send('emails.create_user',$messageData,function($message) use($email){
             $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to($email)->cc('info@masterstrokeonline.com')
            ->subject('Membership with Masterstrokeonline');
        });
        return redirect('account/user-management')->with('success','User created successfully.');
    }

    public function update_user_management(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required'
        ]);
        $input = $request->all();

        $user = User::where('id',$input['id'])->first();
        $name = $input['first_name'].' '.$input['last_name'];
        $insertData = [];
        $insertData['name'] = $name;
        $insertData['first_name'] = $input['first_name'];
        $insertData['gst_number'] = $input['gst_number'];
        $insertData['last_name'] = $input['last_name'];

        $user->update($insertData);
        return redirect('account/user-management')->with('success','User updated successfully.');
    }

    public function delete_user_management($id){
        $user = User::where('id',$id)->first();
        $user->delete();
        return redirect('account/user-management')->with('success','User deleted successfully.');
    }

    public function block_user_management($id){
        $user = User::where('id',$id)->first();
        $user->update(["is_active"=>!$user->is_active]);
        return redirect('account/user-management')->with('success','User blocked / unblocked successfully.');
    }

    public function displaysettingsUpdate_old(Request $request,$id){
        $request->validate([
            'first_name' => 'required',
            'phone_no' => 'required',
            'company_name' => 'required',
        ]);

        $displayInfo = Displayinfo::where('id',$id)->first();
        
        $input = $request->all(); 

        $name = $input['first_name'].' '.$input['last_name'];

        $saveData = [
            'name' => $name,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'company_name' => $input['company_name'],
            'amfi_registered' => (isset($input['amfi_registered']) && $input['amfi_registered'] == 1)?1:0,
            'template' => $input['template'],
            'template_soft_copy' => $input['template_soft_copy'],
            'phone_no' => $input['phone_no'],
            'email' => $input['email'],
            'website' => $input['website'],
            'address' => $input['address'],
            // 'address2' => $input['address2'],
            'name_color' => $input['name_color'],
            'company_name_color' => $input['company_name_color'],
            'phone_no_color' => $input['phone_no_color'],
            'email_color' => $input['email_color'],
            'website_color' => $input['website_color'],
            'address_color' => $input['address_color'],
            'address_color_background' => $input['address_color_background'],
            'name_check' => (isset($input['name_check']))?1:0,
            'company_name_check' => (isset($input['company_name_check']))?1:0,
            'phone_no_check' => (isset($input['phone_no_check']))?1:0,
            'email_check' => (isset($input['email_check']))?1:0,
            'website_check' => (isset($input['website_check']))?1:0,
            'address_check' => (isset($input['address_check']))?1:0,
            'address2_check' => (isset($input['address2_check']))?1:0,
            'name_watermark' => (isset($input['name_watermark']))?1:0,
            'company_name_watermark' => (isset($input['company_name_watermark']))?1:0
        ];

        $res = $displayInfo->update($saveData);
        if ($res){
            //toastr()->success('Profile successfully updated.');
            return redirect()->back()->with('success','Display Settings successfully updated.');
        }

        return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
    }

    public function displaysettingsUpdate(Request $request,$id){
        $request->validate([
            'first_name' => 'required',
            'phone_no' => 'required',
            'company_name' => 'required',
        ]);

        $displayInfo = Displayinfo::where('id',$id)->first();
        
        $input = $request->all(); 

        $name = $input['first_name'].' '.$input['last_name'];

        $saveData = [
            'name' => $name,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'company_name' => $input['company_name'],
            'amfi_registered' => (isset($input['amfi_registered']) && $input['amfi_registered'] == 1)?1:0,
            'template' => $input['template'],
            'footer_branding_option' => $input['footer_branding_option'],
            // 'template_soft_copy' => $input['template_soft_copy'],
            'phone_no' => $input['phone_no'],
            'email' => $input['email'],
            'website' => $input['website'],
            'address' => $input['address'],
            'address2' => $input['address2'],
            'name_color' => $input['name_color'],
            'company_name_color' => $input['company_name_color'],
            'phone_no_color' => $input['phone_no_color'],
            'email_color' => $input['email_color'],
            'website_color' => $input['website_color'],
            'address_color' => $input['address_color'],
            'city_color' => $input['city_color'],
            'address_color_background' => $input['address_color_background'],
            'name_check' => (isset($input['name_check']))?1:0,
            'company_name_check' => (isset($input['company_name_check']))?1:0,
            'phone_no_check' => (isset($input['phone_no_check']))?1:0,
            'email_check' => (isset($input['email_check']))?1:0,
            'website_check' => (isset($input['website_check']))?1:0,
            'address_check' => (isset($input['address_check']))?1:0,
            'address2_check' => (isset($input['address2_check']))?1:0,
            'name_watermark' => (isset($input['name_watermark']))?1:0,
            'company_name_watermark' => (isset($input['company_name_watermark']))?1:0
        ];

        $res = $displayInfo->update($saveData);
        if ($res){
            //toastr()->success('Profile successfully updated.');
            return redirect()->back()->with('success','Display Settings successfully updated.');
        }

        return redirect()->back()->withInput()->with('error','Some problem. Please try again!');
    }

    public function subscription(){
        $user = Auth::user();
        $id = $user->id;
        $membership_plan = Membership::select(['memberships.*','package_creation_dropdowns.name','package_creation_dropdowns.price'])->LeftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'memberships.package_id')->where('memberships.user_id',$id)->orderBy('memberships.id','DESC')->get();
        $current_package = PackageCreationDropdown::where('id',$user->package_id)->where('is_active',1)->first();
        $membership_detail = Membership::where('user_id',$id)->where('is_active',1)->orderBy('id', 'desc')->first();
        // dd($membership_detail);
        $is_open_subscription = 0;
        if ($membership_detail) {
            $current_date = strtotime(date('d-m-Y'));
            $current_date = date('Y-m-d',strtotime('+3 month',$current_date));
            if((int) $current_package->price){
                if(strtotime($current_date) >= strtotime(date('d-m-Y', strtotime($membership_detail['expire_at'])))){
                    $is_open_subscription = 1;
                }else{
                    $is_open_subscription = 0;
                }
            }else{
                $is_open_subscription = 0;
            }
        }

        $data["type"] = 1;

        if($membership_detail->package_id == 14){
            $data["type"] = 0;
        }else{

        }
        // dd($package);
        // echo $is_open_subscription; exit;
        $left_menu = "subscription";
        $billing_detail = UserBilling::where('user_id',$user->id)->first();
        $package = PackageCreationDropdown::select('id')->where('is_active',1)->orderBy('price','DESC')->first();
        // dd($user);
        $is_update_status = true;
        if($package->id == $user->package_id){
            $is_update_status = false;
        }
        // dd($is_update_status);
        return view('frontend.account.subscription')->with(compact('membership_plan','left_menu','billing_detail','is_update_status','membership_detail'))->with(compact('is_open_subscription'));
    }

    public function subscription_view($id){
        $user = Auth::user();
        $user_id = $user->id;
        $billingAddress = Billingaddress::where('user_id',$user_id)->first();
        if(empty($billingAddress)){
            $billingAddress = $user;
        }

        $planDetails = Membership::where('user_id',$user_id)->where('id',$id)->first();
        if(!empty($planDetails)){
            return view('frontend.account.subscription_view')->with(compact('planDetails','billingAddress'));
        }else{
            return redirect()->back();
        }
    }

    public function subscription_cart(Request $request){
        // dd($input);
        $input = $request->all();
        $data['user'] = Auth::user();
        $data['current_package'] = PackageCreationDropdown::where('id',$data['user']->package_id)->where('is_active',1)->first();
        $pre_amount = "";
        $data['membership_detail'] = Membership::where('user_id',$data['user']->id)->where('is_active',1)->first();
        // dd($data);

        $current_amount = $data['current_package']->price - $data['current_package']->discount_price;
        $current_user = ($data['user']->number_user - 1) * $data['current_package']->price_per_user;
        $current_amount = $current_amount + $current_user;
        
        if(1){
            $membership_point_plus = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
            $membership_point_mins = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
            $total_membership_point = $membership_point_plus - $membership_point_mins;
            $referralCodeSetting = ReferralCodeSetting::where('id','=',3)->first();
            $wallet_amount = $total_membership_point / $referralCodeSetting->value;
        }else{
            $wallet_amount = 0;
        }
        // echo $current_amount."++".$old_amount;
        $data['total_price_per_user'] = $current_amount;
        $data['total_price_per_user_with_discount'] = $current_amount;
        if($data['total_price_per_user'] > 0){
            $data['total_price_per_user'] = $current_amount;
            $data['total_price_per_user_gst'] = $data['total_price_per_user_with_discount'] * 1.18;
            $data['total_gst_price'] = $data['total_price_per_user_with_discount'] * 0.18;
        }else{
            $data['total_price_per_user'] = 0;
            $data['total_price_per_user_gst'] = 0;
            $data['total_gst_price'] = 0;
        }
        $data['total_user'] = $data['user']->number_user;
        $data['package_id'] = $data['current_package']->id;
        $data['package_name'] = $data['current_package']->name;
        $data['wallet_amount'] = $wallet_amount;
        $data['coupon_price'] = 0;
        $data['coupon_code'] = "";


        if(isset($request->coupon_code)){
            $couponDetails = Coupon::where('coupon_code',$request->coupon_code)->first();
            if(!empty($couponDetails)){
                $coupon = [];
                if($couponDetails->is_active == 1 && $couponDetails->expired_at >= date('Y-m-d') || $couponDetails->expired_at==''){
                    if($couponDetails->coupon_type == "percentage_amount_all_product"){
                        $total_price = $data['total_price_per_user'];
                        $response = $total_price * $couponDetails->coupon_amount / 100;
                    }else{
                        $response = $couponDetails->coupon_amount;
                    }
                }else{
                    $response = 0;
                }
            }else{
                $response = 0;
            }
            $data['total_price_per_user_gst'] = ($data['total_price_per_user'] - $response) * 1.18;
            $data['total_gst_price'] = ($data['total_price_per_user'] - $response) * 0.18;
            $data['coupon_price'] = $response;
            $data['coupon_code'] = $request->coupon_code;
        }else{
            $expire_at = $data['membership_detail']->expire_at;
            $current_date = date("Y-m-d");

            $expire_at = date('Y-m-d',strtotime($expire_at));

            // echo $expire_at."-- expire_at -- <br>";
            // echo $current_date."-- current_date -- <br>";

            $current_date = strtotime($current_date);
            $expire_at = strtotime($expire_at);


            if($current_date <= $expire_at){
                $current_date_30 = strtotime(date('Y-m-d',strtotime('+30 days',$current_date)));
                $current_date_15 = strtotime(date('Y-m-d',strtotime('+15 days',$current_date)));
                // echo $current_date_15."-- 15 -- <br>";

                if($current_date_30 <= $expire_at){
                    $renewal_discount = DB::table("renewal_discount_price")->where("type",1)->first();
                    $renewal_discount_price = $renewal_discount->percent;

                    $data['total_price_per_user_with_discount'] = $data['total_price_per_user'] - ($data['total_price_per_user'] * $renewal_discount->percent / 100);
                    $data['discount_price'] = $data['total_price_per_user'] * $renewal_discount->percent / 100;
                    $data['total_price_per_user_gst'] = ($data['total_price_per_user_with_discount']) * 1.18;
                    $data['total_gst_price'] = ($data['total_price_per_user_with_discount']) * 0.18;

                }else if($current_date_15 <= $expire_at){
                    $renewal_discount = DB::table("renewal_discount_price")->where("type",2)->first();
                    $renewal_discount_price = $renewal_discount->percent;

                    $data['total_price_per_user_with_discount'] = $data['total_price_per_user'] - ($data['total_price_per_user'] * $renewal_discount->percent / 100);
                    $data['discount_price'] = $data['total_price_per_user'] * $renewal_discount->percent / 100;
                    $data['total_price_per_user_gst'] = ($data['total_price_per_user_with_discount']) * 1.18;
                    $data['total_gst_price'] = ($data['total_price_per_user_with_discount']) * 0.18;
                }else{
                    $renewal_discount = DB::table("renewal_discount_price")->where("type",3)->first();
                    $renewal_discount_price = $renewal_discount->percent;

                    $data['total_price_per_user_with_discount'] = $data['total_price_per_user'] - ($data['total_price_per_user'] * $renewal_discount->percent / 100);
                    $data['discount_price'] = $data['total_price_per_user'] * $renewal_discount->percent / 100;
                    $data['total_price_per_user_gst'] = ($data['total_price_per_user_with_discount']) * 1.18;
                    $data['total_gst_price'] = ($data['total_price_per_user_with_discount']) * 0.18;
                }
            }else{
                $expire_at = date('Y-m-d',strtotime('+30 days',$expire_at));
                $expire_at = strtotime($expire_at);
                if($current_date <= $expire_at){
                    $renewal_discount = DB::table("renewal_discount_price")->where("type",4)->first();
                    $renewal_discount_price = $renewal_discount->percent;

                    $data['total_price_per_user_with_discount'] = $data['total_price_per_user'] - ($data['total_price_per_user'] * $renewal_discount->percent / 100);
                    $data['discount_price'] = $data['total_price_per_user'] * $renewal_discount->percent / 100;
                    $data['total_price_per_user_gst'] = ($data['total_price_per_user_with_discount']) * 1.18;
                    $data['total_gst_price'] = ($data['total_price_per_user_with_discount']) * 0.18;
                }
            }
        }
        return view('frontend.account.cart',$data);
    }

    public function membership_renewal_payment(Request $request){
        
        $input = $request->all();
        
        $user = Auth::user();
        $order_id = $user->id.rand(1000,1000000);
        $user_id = $user->id;
        $amount =  $input['total_amount'];
        
        $package_id =  $input['package_id'];
        $wallet_amount =  $input['wallet_amount_id'];
        $wallet_amount =  ($input['wallet_amount_id'])?$input['wallet_amount_id']:0;
        $total_user =  ($input['total_user'])?$input['total_user']:0;
        if($amount > 0){
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
              'order' => $order_id,
              'user' => $user_id,
              'mobile_number' => $user->phone_no,
              'email' => $user->email,
              'amount' => $amount,
              'callback_url' => url('api/v1/payment/status/'.$user->id.'/'.$wallet_amount)
            ]);
            return $payment->receive();
        }else{
            $current_package = PackageCreationDropdown::where('id',$user->package_id)->where('is_active',1)->first();
            $membership_detail = Membership::where('user_id',$user->id)->where('is_active',1)->first();
            // dd($membership_detail);
            $membership_date=strtotime($membership_detail->expire_at);
            $current_date=strtotime(date('Y-m-d'));
            if($membership_date > $current_date){
                $expire_at = date('Y-m-d',strtotime('+'.$current_package->days.' day',$membership_date));
            }else{
                $expire_at = date('Y-m-d',strtotime('+'.$current_package->days.' day',$current_date));
            }

            $inserData = [
                "permission_sales_presenter"=>$current_package->sales_presenters,
                "permission_calculators_proposals"=>$current_package->client_proposals,
                "permission_premium_calculator"=>$current_package->premium_calculator,
                "permission_investment_suitablity_profiler"=>$current_package->investment_suitability_profiler,
                "permission_marketing_banners"=>$current_package->marketing_banners,
                "permission_marketing_video"=>$current_package->marketing_videos,
                "permission_premade_sales_presenter"=>$current_package->pre_made_sales_presenters,
                "permission_trail_calculators"=>$current_package->trail_calculator,
                "permission_scanner"=>$current_package->scanner,
                "permission_famous_quotes"=>$current_package->famous_quotes
            ];
            $user->update($inserData);

            Membership::where('user_id','=',$user_id)->update(['is_active'=>0]);
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user_id,
                'subscription_id' => $package_id,
                'package_id' => $package_id,
                'package_amount' => $current_package->price,
                'package_user_amount' => $current_package->price_per_user,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $total_user,
                'duration_name' => 'year',
                'expire_at' => $expire_at,
                'is_active' => 1,
                'type' => 2,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );
            
            $membership = Membership::create($membershipData);
            
            // $user_list = User::where("user_id",$user_id)->first();
            // foreach($user_list as $value){
            //     Membership::where('user_id','=',$value->id)->update(['is_active'=>0]);
                
            //     $membershipData = array(
            //         'user_id' => $value->id,
            //         'subscription_id' => $package_id,
            //         'package_id' => $package_id,
            //         'package_amount' => 0,
            //         'package_user_amount' => 0,
            //         'order_id' => 0,
            //         'subscription_type' => 'paid',
            //         'amount' => $amount,
            //         'duration' => $total_user,
            //         'duration_name' => 'year',
            //         'expire_at' => $expire_at,
            //         'is_active' => 1,
            //         'type' => 2,
            //         'ip_address' => $ip_address,
            //         'is_paid' => 1
            //     );
                
            //     $membership = Membership::create($membershipData);
            // }

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
                $inserData['note'] = "Membership";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }

            return redirect()->route('account.subscription.index')->with('success','Mmembership renewal successfully');
        }
    }

    public function paymentCallback($user_id,$wallet_amount){
        
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        //dd($response['TXNAMOUNT']);
        $order_id = $transaction->getOrderId();
        if($transaction->isSuccessful()){
            
            $user = User::where("id",$user_id)->first();
            $current_package = PackageCreationDropdown::where('id',$user->package_id)->where('is_active',1)->first();
            $membership_detail = Membership::where('user_id',$user->id)->where('is_active',1)->first();
            
            $membership_date=strtotime($membership_detail->expire_at);
            $current_date=strtotime(date('Y-m-d'));
            
            if($membership_date > $current_date){
                $expire_at = date('Y-m-d',strtotime('+'.$current_package->days.' day',$membership_date));
            }else{
                $expire_at = date('Y-m-d',strtotime('+'.$current_package->days.' day',$current_date));
            }

            $inserData = [
                "permission_sales_presenter"=>$current_package->sales_presenters,
                "permission_premium_calculator"=>$current_package->premium_calculator,
                "permission_investment_suitablity_profiler"=>$current_package->investment_suitability_profiler,
                "permission_marketing_banners"=>$current_package->marketing_banners,
                "permission_marketing_video"=>$current_package->marketing_videos,
                "permission_premade_sales_presenter"=>$current_package->pre_made_sales_presenters,
                "permission_trail_calculators"=>$current_package->trail_calculator,
                "permission_scanner"=>$current_package->scanner,
                "permission_famous_quotes"=>$current_package->famous_quotes
            ];
            
            $user->update($inserData);
            $order_id = $user->id.rand(1000,1000000);
            $amount =  $response['TXNAMOUNT'];
            Membership::where('user_id','=',$user_id)->update(['is_active'=>0]);
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user_id,
                'subscription_id' => $user->package_id,
                'package_id' => $user->package_id,
                'package_amount' => $current_package->price,
                'package_user_amount' => $current_package->price_per_user,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $user->number_user,
                'duration_name' => 'year',
                'expire_at' => $expire_at,
                'is_active' => 1,
                'type' => 2,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );
            
            $membership = Membership::create($membershipData);

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
                $inserData['note'] = "Membership";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }
            
            if($amount){
                $referralCodeSetting = ReferralCodeSetting::where('id',2)->first();
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
                $total_amount_plus = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
                $total_amount_mins = ReferralCode::where('user_id','=',$user_id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
                $total_amount = $total_amount_plus - $total_amount_mins;
                $total_amount = $total_amount + $referral_amount;
                $inserData['user_id'] = $user_id;
                $inserData['referral_amount'] = $referral_amount;
                $inserData['total_amount'] = $total_amount;
                $inserData['note'] = "Subscription Renewal";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 0;
                ReferralCode::create($inserData);

                $email = $user->email;
                $dynamic_email = DB::table("dynamic_email")->where('id',3)->first();
                $messageData = ['name'=>$user->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"membership_point_email"=>$referral_amount,"total_amount"=>$amount];
                
                Mail::send('emails.membership-point',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }
            
            if($membership){
                return redirect()->route('account.subscription.index')->with('success','Mmembership renewal successfully');
            }
        }else if($transaction->isFailed()){
          return redirect()->route('account.subscription.index')->with('error','There have some issues. Please try again');
        }
    } 

    public function orderList(){
        $user = Auth::user();
        $user_id = $user->id;
        $orderDetails = Order::where('user_id',$user_id)->get();
        $data['billing_detail'] = UserBilling::where('user_id',$user->id)->first();

        $data['orderDetails'] = $orderDetails;
        
        $data['left_menu'] = "orders";
        return view('frontend.account.orderlist',$data);
    }
    
    public function orderDetails($id){
        $user = Auth::user();
        $user_id = $user->id;
        $order = Order::where('id',$id)->first();
        $allOrders = [];
        $orderInfo = [];
        $orderDetails = [];
        //$orderItems = [];

        //foreach($orders as $order){
            
            $orderItems = array();
            $orderitems = Orderitem::where('order_id',$order->id)->get();
            foreach($orderitems as $orderitem){
                $orderItem = [
                    "product_name" => $orderitem->name,
                    "quantity" => strval($orderitem->quantity),
                    "price" => strval($orderitem->price),
                    "product_image" => url('uploads/stationary/thumbnail/'.$orderitem->photo)
                ];
                array_push($orderItems,$orderItem);
            }

            $billingAddress = Billingaddress::where('id',$order->billingaddress_id)->first();
            $billingAddressInfo = array();
            if($billingAddress){
                $billingAddressInfo = [
                    "name" => empty($billingAddress->name)?'':$billingAddress->name,
                    "company_name" => empty($billingAddress->company_name)?'':$billingAddress->company_name,
                    "country" => empty($billingAddress->country)?'':$billingAddress->country,
                    "street_name" => empty($billingAddress->street_name)?'':$billingAddress->street_name,
                    "city" => empty($billingAddress->city)?'':$billingAddress->city,
                    "state" => empty($billingAddress->state)?'':$billingAddress->state,
                    "zip_code" => empty($billingAddress->zip_code)?'':$billingAddress->zip_code,
                    "phone_no" => $billingAddress->phone_no,
                    "email" => empty($billingAddress->email)?'':$billingAddress->email,
                    "additional_info" => empty($billingAddress->additional_info)?'':$billingAddress->additional_info,
                ];
            }


            $orderInfo = [
                "order_id" => strval($order->id),
                "invoice_id" => strval($order->invoice_id),
                "coupon_code" => empty($order->coupon_code)?'':$order->coupon_code,
                "coupon_amount" => strval($order->coupon_amount),
                "total_amount" => strval($order->total_amount),
                "payable_amount" => strval($order->payable_amount),
                "payment_status" => $order->payment_status,
                "status" => $order->status,
                "order_date" => $order->created_at->format('d/m/Y'),
                "orderitems" => $orderItems,
                "billing_address" => $billingAddressInfo
            ];

            array_push($orderDetails,$orderInfo);
        //}
        
        //dd($orderDetails);

        $data['orderDetails'] = $orderDetails;
        
        // Order Email test
        
        
        /*$order_id = 24;
        $user_id = 6747;
        $order = Order::where('id',$order_id)->first();
        $user = User::where('id',$user_id)->first();
        $email = $user->email;
        $name = $user->name;
        $phone_no = $user->phone_no;
        $date=date('d/m/Y');
        
        $orderItems = array();
        $orderitems = Orderitem::where('order_id',$order_id)->get();
        foreach($orderitems as $orderitem){
            $orderItem = [
                "product_name" => $orderitem->name,
                "quantity" => strval($orderitem->quantity),
                "price" => strval($orderitem->price),
                //"product_image" => url('uploads/stationary/thumbnail/'.$orderitem->photo)
            ];
            array_push($orderItems,$orderItem);
        }
        //dd($orderItems);
        $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'date'=>$date,'order'=>$order,'orderItems'=>$orderItems];
        
        
        $email_to = 'subhasishsamanta28@gmail.com';
        //$email_to = 'info@masterstrokeonline.com';
        Mail::send('emails.order',$messageData,function($message) use($email,$email_to){
                $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to($email)->cc($email_to)
            ->subject('Order History');
        });*/
        $data['left_menu'] = "orders";
        return view('frontend.account.order_view',$data);

        //return response()->json(['status' => true, 'message' => '', 'data' => $data]);
    }
    
    public function membership_update_db(){
        $memberships = Membership::where('subscription_type','free')->get();
        foreach($memberships as $membership){
            //echo $membership->subscription_type;
            $membershipupdate = Membership::where('id',$membership->id)->first();
            $saveData = [
                'membership_via' => ''
            ];
    
            $res = $membershipupdate->update($saveData);
        }
        dd();
    }

    public function refer_to_a_friend(Request $request){
        $user = Auth::user();
        $data = [];
        $user_list = ReferralCode::where('user_id',$user->id);
        $data['left_menu'] = "refer_to_a_friend";
        $data['search_text'] = $request->search_text;
        $data['user_list'] = $user_list->orderBy('id','DESC')->get();
        $data['user_detail'] = User::where('id',$user->id)->first();
        if(!$data['user_detail']->referral_code){
            $user_id_count = strlen($user->id);
            if($user_id_count == 1){
                $referral_code = $user->id."".mt_rand(1000000,9999999);
            }else if($user_id_count == 2){
                $referral_code = $user->id."".mt_rand(100000,999999);
            }else if($user_id_count == 3){
                $referral_code = $user->id."".mt_rand(10000,99999);
            }else if($user_id_count == 4){
                $referral_code = $user->id."".mt_rand(1000,9999);
            }else if($user_id_count == 5){
                $referral_code = $user->id."".mt_rand(100,999);
            }else if($user_id_count == 6){
                $referral_code = $user->id."".mt_rand(10,99);
            }else if($user_id_count == 7){
                $referral_code = $user->id."".mt_rand(0,9);
            }else if($user_id_count == 8){
                $referral_code = $user->id;
            }
            $data['user_detail']->update(["referral_code"=>$referral_code]);
            $data['user_detail'] = User::where('id',$user->id)->first();
        }

        
        $total_amount_plus = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
        $total_amount_mins = ReferralCode::where('user_id','=',$user->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
        $data['total'] = $total_amount_plus - $total_amount_mins;
        $data['earned'] = $total_amount_plus;
        $data['claimed'] = $total_amount_mins;
        // dd($data);
        foreach ($data['user_list'] as $key => $value) {
            $start_date = date('Y-m-d',strtotime($value->created_at));
            $date=strtotime($start_date);
            $data['user_list'][$key]->expire_date = date('d-m-Y',strtotime('15 month',$date));
        }
        // dd($data['user_list']);
        return view('frontend.account.refer_to_a_friend',$data);
    }

    public function billing(){
        $user = Auth::user();
        $data = [];
        $data['left_menu'] = "billing";
        $data['detail'] = UserBilling::where('user_id',$user->id)->first();
        return view('frontend.account.billing',$data);
    }

    public function save_billing(Request $request){
        $this->validate($request, [
            'company_name' => 'required',
            'billing_address' => 'required',
            'gst_detail' => 'required',
            'gst_zone' => 'required'
        ]);

        $input = $request->all();

        $user = Auth::user();

        $UserBilling = UserBilling::where('user_id',$user->id)->first();
        $insertData = [];
        $insertData['company_name'] = $input['company_name'];
        $insertData['billing_address'] = $input['billing_address'];
        $insertData['gst_detail'] = $input['gst_detail'];
        $insertData['gst_zone'] = $input['gst_zone'];
        // dd($insertData);
        if($UserBilling){
            $UserBilling->update($insertData);
        }else{
            $insertData['user_id'] = $user->id;
            UserBilling::create($insertData);
        }

        return redirect('account/billing')->with('success','Billing detail updated successfully.');
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

    public function downloadInvoice(Request $request,$subscription_id){
        $user = Auth::user();
        $data = [];
        $data['user_billing_detail'] = UserBilling::where('user_id',$user->id)->first();
        $data['membership_detail'] = Membership::where('id',$subscription_id)->first();
        $data['invoice_number'] = $subscription_id;
        if($data['membership_detail']->duration_name){
            $data['invoice_name'] = "ONLINE MEMBERSHIP FOR 1 YEAR SUBSCRIPTION";
        }else{
            $data['invoice_name'] = "UPDATE USER";
        }
        
        $amount = $data['membership_detail']->amount;
        $data['total_amount'] = $amount;
        if($amount){
            $cgst = $amount - $amount/1.18 ;
            $orignal_amount = $amount - $cgst;

            $data['membership_detail']->orignal_amount = number_format($orignal_amount, 2, '.', '');
            $data['membership_detail']->cgst = number_format($cgst/2, 2, '.', '');
            $data['membership_detail']->sgst = number_format($cgst/2, 2, '.', '');

            $round_off= $amount - $data['membership_detail']->orignal_amount - $data['membership_detail']->cgst - $data['membership_detail']->cgst;
            $data['membership_detail']->round_off = number_format($round_off , 2, '.', '');
            $total_tax = $data['membership_detail']->cgst * 2;
            $data['membership_detail']->amount_in_word = "INR ".$this->AmountInWords($total_tax)." Only";
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
        // dd($data);
        if($data['user_billing_detail']->gst_zone == "outside"){
            // return view('frontend.invoice.outside_wb',$data);
            $pdf = PDF::loadView('frontend.invoice.outside_wb', $data);
            return $pdf->download(time().'_invoice.pdf');
        }else{
            // return view('frontend.invoice.outside_wb',$data);
            $pdf = PDF::loadView('frontend.invoice.wb', $data);
            return $pdf->download(time().'_invoice.pdf');
        }
        // $data['fsgdf'] = 
        // dd($data);
    }

    public function order_invioce_download(Request $request,$id){
        $user = Auth::user();
        $data = [];
        $data['user_billing_detail'] = UserBilling::where('user_id',$user->id)->first();
        $data['membership_detail'] = Order::where('id',$id)->first();
        $order_item = Orderitem::where('order_id','=',$data['membership_detail']->id)->first();
        // dd($data['membership_detail']);
        $data['invoice_number'] = $id;
        $data['invoice_name'] = $order_item->name;
        $amount = $data['membership_detail']->payable_amount;
        $data['total_amount'] = $amount;
        if($amount){
            $cgst = $amount - $amount/1.18 ;
            $orignal_amount = $amount - $cgst;

            $data['membership_detail']->orignal_amount = number_format($orignal_amount, 2, '.', '');
            $data['membership_detail']->cgst = number_format($cgst/2, 2, '.', '');
            $data['membership_detail']->sgst = number_format($cgst/2, 2, '.', '');

            $round_off= $amount - $data['membership_detail']->orignal_amount - $data['membership_detail']->cgst - $data['membership_detail']->cgst;
            $data['membership_detail']->round_off = number_format($round_off , 2, '.', '');
            $total_tax = $data['membership_detail']->cgst * 2;
            $data['membership_detail']->amount_in_word = "INR ".$this->AmountInWords($total_tax)." Only";
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
        // $data['fsgdf'] = 
        // dd($data);
    }

    public function upgradePackage(){
        $data = [];
        $data['user'] = Auth::user();
        $data['hint'] = PackageCreationHint::first();
        $data['package_list'] = PackageCreationDropdown::where('is_active',1)->orderBy('order_by','ASC')->get();
        $data['current_package'] = PackageCreationDropdown::where('id',$data['user']->package_id)->first();
        $data['membership_detail'] = Membership::where('user_id',$data['user']->id)->where('is_active',1)->first();
        $data['old_package'] = PackageCreationDropdown::where('id',$data['user']->package_id)->first();
        $old_amount = (int) $data['current_package']->price - $data['current_package']->discount_price;
        $old_user = ($data['user']->number_user-1) *(int) $data['current_package']->price_per_user;
        $old_amount = $old_amount + $old_user;
        $data['days_used'] = "";
        $data['price_paid'] = $old_amount;
        $data['balance_period'] = "";
        $data['balance_amount'] = "";
        if($old_amount != 0){
            $current_date = date('Y-m-d');
            $old_date = date('Y-m-d',strtotime($data['membership_detail']->expire_at));
            $datetime1 = strtotime($current_date);
            $datetime2 = strtotime($old_date);
            $interval = (int)(($datetime2 - $datetime1)/86400);
            // echo $interval->format('%invert');
            // dd($interval);
            if($interval > 0){
                $data['days_used'] = 365 - $interval;
                $days = $interval;
                $data['balance_period'] = $days;
                // $old_amount = $old_amount / 1.18;
                $old_amount = (int) $old_amount * $days / 365;
                $old_amount = number_format((float)$old_amount, 2, '.', '');
            }else{
                $data['days_used'] = 365;
                $data['balance_period'] = 0;
                $old_amount = 0;
                // $old_amount = 
            }
        }
        $data['old_amount'] = $old_amount;
        $data['balance_amount'] = $old_amount;
        // dd($data);
        return view('frontend.account.update_package',$data);
    }

    public function membership_update(Request $request){

        $input = $request->all();
        $data['user'] = Auth::user();
        $data['current_package'] = PackageCreationDropdown::where('id',$input['package_id'])->where('is_active',1)->first();
        $pre_amount = "";
        $data['membership_detail'] = Membership::where('user_id',$data['user']->id)->where('is_active',1)->first();
        // dd($data);
        $data['old_package'] = PackageCreationDropdown::where('id',$data['user']->package_id)->first();

        $old_amount = (int) $data['old_package']->price - $data['old_package']->discount_price;;
        $old_user = ($data['user']->number_user-1) *(int) $data['old_package']->price_per_user;
        $old_amount = $old_amount + $old_user;
        
        $current_amount = $data['current_package']->price - $data['current_package']->discount_price;
        $current_user = ($data['user']->number_user - 1) * $data['current_package']->price_per_user;
        $current_amount = $current_amount + $current_user;
        $data['message'] = "";
        // exit;
        // dd($data);
        if($old_amount != 0){
            $current_date = date('Y-m-d');
            $old_date = date('Y-m-d',strtotime($data['membership_detail']->expire_at));
            $datetime1 = strtotime($current_date);
            $datetime2 = strtotime($old_date);
            $interval = (int)(($datetime2 - $datetime1)/86400);
            // echo $interval->format('%invert');
            // dd($interval);
            if($interval > 0){
                $data['days_used'] = 365 - $interval;
                $days = $interval;
                $data['balance_period'] = $days;
                // $old_amount = $old_amount / 1.18;
                $old_amount = (int) $old_amount * $days / 365;
                $old_amount = number_format((float)$old_amount, 2, '.', '');
            }else{
                $data['days_used'] = 365;
                $data['balance_period'] = 0;
                $old_amount = 0;
                // $old_amount = 
            }
        }

        if(!$data['user']->user_id){
            $membership_point_plus = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
            $membership_point_mins = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
            $total_membership_point = $membership_point_plus - $membership_point_mins;
            $referralCodeSetting = ReferralCodeSetting::where('id','=',3)->first();
            $wallet_amount = $total_membership_point / $referralCodeSetting->value;
        }else{
            $wallet_amount = 0;
        }
            
        //echo $current_amount."++".$old_amount;
        
        $data['total_price_per_user'] = $current_amount - $old_amount;
        if($data['total_price_per_user'] > 0){
            $data['total_price_per_user'] = $current_amount - $old_amount;
            $data['total_price_per_user_gst'] = $data['total_price_per_user'] * 1.18;
            $data['total_gst_price'] = $data['total_price_per_user'] * 0.18;
        }else{
            $data['total_price_per_user'] = 0;
            $data['total_price_per_user_gst'] = 0;
            $data['total_gst_price'] = 0;
        }
        $data['total_user'] = $input['user_number'];
        $data['package_id'] = $data['current_package']->id;
        $data['package_name'] = $data['current_package']->name;
        $data['wallet_amount'] = $wallet_amount;
        $data['coupon_price'] = 0;
        $data['coupon_code'] = "";
        // dd($data);
        if(isset($request->coupon_code)){
            $mem = Membership::where('user_id',$data['user']->id)->where('amount',"!=",0)->first();
            if($mem){
                $couponDetails = Coupon::where('coupon_code',$request->coupon_code)->first();
                if(!empty($couponDetails)){
                    $coupon = [];
                    if($couponDetails->is_active == 1 && $couponDetails->expired_at >= date('Y-m-d') || $couponDetails->expired_at==''){
                        if($couponDetails->coupon_type == "percentage_amount_all_product"){
                            $total_price = $data['total_price_per_user'];
                            $response = $total_price * $couponDetails->coupon_amount / 100;
                        }else{
                            $response = $couponDetails->coupon_amount;
                        }
                    }else{
                        $response = 0;
                        $data['message'] = "This Coupon Code is not valid for existing user / previous user for renewal or upgrade. Please renew/upgrade without this coupon";
                    }
                }else{
                    $response = 0;
                    $data['message'] = "This Coupon Code is not valid for existing user / previous user for renewal or upgrade. Please renew/upgrade without this coupon";
                }
            }else{
                $couponDetails = PackageCoupon::where('coupon_code',$request->coupon_code)->first();
                if(!empty($couponDetails)){
                    if($couponDetails->package_id == $data['package_id']){
                        $coupon = [];
                        if($couponDetails->is_active == 1 && $couponDetails->expired_at >= date('Y-m-d') || $couponDetails->expired_at==''){
                            $response = $couponDetails->coupon_amount;
                        }else{
                            $response = 0;
                            $data['message'] = "This Coupon Code is not valid for existing user / previous user for renewal or upgrade. Please renew/upgrade without this coupon";
                        }
                    }else{
                        $data['message'] = "This Coupon Code is not valid for selected package";
                        $response = 0;
                    } 
                }else{
                    $data['message'] = "This Coupon Code is not valid for existing user / previous user for renewal or upgrade. Please renew/upgrade without this coupon";
                    $response = 0;
                }
            }
            $data['total_price_per_user_gst'] = ($data['total_price_per_user'] - $response) * 1.18;
            $data['total_gst_price'] = ($data['total_price_per_user'] - $response) * 0.18;
            $data['coupon_price'] = $response;
            $data['coupon_code'] = $request->coupon_code;
        }
        // 
        // dd($data); 
        return view('frontend.account.update_cart_package',$data);
    }

    public function membership_update_payment(Request $request){
        $input = $request->all();
        // dd($input);
        $user = Auth::user();
        $order_id = $user->id.rand(1000,1000000);
        $user_id = $user->id;
        $amount =  $input['total_amount'];
        // $total_user =  $input['total_user'];
        $package_id =  $input['package_id'];
        $wallet_amount =  $input['wallet_amount_id'];
        $wallet_amount =  ($input['wallet_amount_id'])?$input['wallet_amount_id']:0;
        $total_user =  ($input['total_user'])?$input['total_user']:0;
        if($amount > 0){
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
              'order' => $order_id,
              'user' => $user_id,
              'mobile_number' => $user->phone_no,
              'email' => $user->email,
              'amount' => $amount,
              'callback_url' => url('api/v1/subscriptions/user-update-status/'.$user_id.'/'.$package_id.'/'.$total_user.'/'.$wallet_amount)
            ]);
            return $payment->receive();
        }else{
            $user = User::where("id",$user_id)->first();
            $p_detail = PackageCreationDropdown::where("id",$package_id)->first();
            $inserData = [
                "number_user"=>$total_user,
                "package_id"=>$package_id,
                "permission_sales_presenter"=>$p_detail->sales_presenters,
                "permission_calculators_proposals"=>$p_detail->client_proposals,
                "permission_premium_calculator"=>$p_detail->premium_calculator,
                "permission_investment_suitablity_profiler"=>$p_detail->investment_suitability_profiler,
                "permission_marketing_banners"=>$p_detail->marketing_banners,
                "permission_marketing_video"=>$p_detail->marketing_videos,
                "permission_premade_sales_presenter"=>$p_detail->pre_made_sales_presenters,
                "permission_trail_calculators"=>$p_detail->trail_calculator,
                "permission_scanner"=>$p_detail->scanner,
                "permission_famous_quotes"=>$p_detail->famous_quotes
            ];
            $user->update($inserData);

            $date=strtotime(date('Y-m-d'));
            $expire_at = date('Y-m-d',strtotime('+1 year',$date));
            // $amount =  $response['TXNAMOUNT'];
            Membership::where('user_id','=',$user_id)->update(['is_active'=>0]);
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user_id,
                'subscription_id' => $package_id,
                'package_id' => $package_id,
                'package_amount' => $p_detail->price,
                'package_user_amount' => $p_detail->price_per_user,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $total_user,
                'duration_name' => 'year',
                'expire_at' => $expire_at,
                'is_active' => 1,
                'type' => 4,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );

            $membership = Membership::create($membershipData);

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
                $inserData['note'] = "Membership";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }

            if($membership){
                $email = $user->email;
                $name = $user->name;
                $phone_no = $user->phone_no;
                //$amount = $amount;
                $date=date('d/m/Y');
                $dynamic_email = DB::table("dynamic_email")->where('id',1)->first();
                $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'date'=>$date,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer];
                Mail::send('emails.subscription',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }

            return redirect()->route('account.subscription.index')->with('success','Your subscription updated successfully');
        }
    }

    public function membership_update_payment_callback($user_id,$package_id,$total_user,$wallet_amount){
        $transaction = PaytmWallet::with('receive');
        //dd($transaction);
        $response = $transaction->response();
        if($transaction->isSuccessful()){
            $order_id = $transaction->getOrderId();
            $user = User::where("id",$user_id)->first();
            $p_detail = PackageCreationDropdown::where("id",$package_id)->first();
            $inserData = [
                "number_user"=>$total_user,
                "package_id"=>$package_id,
                "permission_sales_presenter"=>$p_detail->sales_presenters,
                "permission_calculators_proposals"=>$p_detail->client_proposals,
                "permission_premium_calculator"=>$p_detail->premium_calculator,
                "permission_investment_suitablity_profiler"=>$p_detail->investment_suitability_profiler,
                "permission_marketing_banners"=>$p_detail->marketing_banners,
                "permission_marketing_video"=>$p_detail->marketing_videos,
                "permission_premade_sales_presenter"=>$p_detail->pre_made_sales_presenters,
                "permission_trail_calculators"=>$p_detail->trail_calculator,
                "permission_scanner"=>$p_detail->scanner,
                "permission_famous_quotes"=>$p_detail->famous_quotes
            ];
            // dd($inserData);
            $user->update($inserData);

            $date=strtotime(date('Y-m-d'));
            $expire_at = date('Y-m-d',strtotime('+1 year',$date));
            $amount =  $response['TXNAMOUNT'];
            Membership::where('user_id','=',$user_id)->update(['is_active'=>0]);
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user->id,
                'subscription_id' => $package_id,
                'package_id' => $package_id,
                'package_amount' => $p_detail->price,
                'package_user_amount' => $p_detail->price_per_user,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $total_user,
                'duration_name' => 'year',
                'expire_at' => $expire_at,
                'is_active' => 1,
                'type' => 4,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );

            $membership = Membership::create($membershipData);

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
                $inserData['note'] = "Membership";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }

            if($membership){
                $user = User::where('id',$user->id)->first();
                $email = $user->email;
                $name = $user->name;
                $phone_no = $user->phone_no;
                //$amount = $amount;
                $date=date('d/m/Y');
                $dynamic_email = DB::table("dynamic_email")->where('id',1)->first();
                $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'date'=>$date,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer];
                Mail::send('emails.subscription',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
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
                $inserData['note'] = "Subscription Update";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 0;
                ReferralCode::create($inserData);

                $email = $user->email;
                $dynamic_email = DB::table("dynamic_email")->where('id',3)->first();
                $messageData = ['name'=>$user->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"membership_point_email"=>$referral_amount,"total_amount"=>$amount];
                
                Mail::send('emails.membership-point',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }

            // return redirect()->route('account.profile');
            return redirect()->route('account.subscription.index')->with('success','Your subscription updated successfully');
        }else if($transaction->isFailed()){
            return redirect()->route('frontend.membership_update_payment')->with('error','There have some issues. Please try again');
        }else{
            echo "2";
        }
    }

    public function upgrade_number_of_user(Request $request){
        $input = $request->all();
        $user_number = $request->user_number;
        // dd($user_number);
        $data['user'] = Auth::user();
        $data['current_package'] = PackageCreationDropdown::where('id',$data['user']->package_id)->where('is_active',1)->first();
        $data['membership_detail'] = Membership::where('user_id',$data['user']->id)->where('is_active',1)->first();
        
        if($data['membership_detail']){
            $current_date = date('Y-m-d');
            $old_date = date('Y-m-d',strtotime($data['membership_detail']->expire_at));
            $datetime1 = new DateTime($current_date);
            $datetime2 = new DateTime($old_date);
            $interval = $datetime1->diff($datetime2);
            // echo $interval->format('%a');
            $data['days_used'] = $interval->format('%a');
            $days = 365 - $interval->format('%a');
            $data['balance_period'] = $days;
            // $old_amount = $old_amount / 1.18;
            $old_amount = (int) $data['current_package']->price_per_user * $data['days_used'] / 365;

            $price_per_user = number_format((float)$old_amount, 2, '.', '');
        }else{
            $price_per_user = $data['current_package']->price_per_user;
        }

        $membership_point_plus = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",0)->groupBy('user_id')->sum('referral_amount');
        $membership_point_mins = ReferralCode::where('user_id','=',$data['user']->id)->where("is_paid","=",1)->groupBy('user_id')->sum('referral_amount');
        $total_membership_point = $membership_point_plus - $membership_point_mins;
        $referralCodeSetting = ReferralCodeSetting::where('id','=',3)->first();
        $wallet_amount = $total_membership_point / $referralCodeSetting->value;

        // dd($data);

        $data['total_price_per_user'] = 0;
        $data['total_price_per_user'] = $user_number * $price_per_user;
        $data['total_amount_gst'] = $data['total_price_per_user'] * 1.18;
        $data['total_user'] = $user_number;
        $data['coupon_price'] = 0;
        $data['coupon_code'] = "";
        $data['package_id'] = $data['current_package']->id;
        $data['package_name'] = $data['current_package']->name;
        $data['wallet_amount'] = $wallet_amount;
        // dd($data);
        if(isset($request->coupon_code)){
            $couponDetails = Coupon::where('coupon_code',$request->coupon_code)->first();
            if(!empty($couponDetails)){
                $coupon = [];
                if($couponDetails->is_active == 1 && $couponDetails->expired_at >= date('Y-m-d') || $couponDetails->expired_at==''){
                    if($couponDetails->coupon_type == "percentage_amount_all_product"){
                        $total_price = $data['total_price_per_user'];
                        $response = $total_price * $couponDetails->coupon_amount / 100;
                    }else{
                        $response = $couponDetails->coupon_amount;
                    }
                }else{
                    $response = 0;
                }
            }else{
                $response = 0;
            }
            $data['total_price_per_user_gst'] = ($data['total_price_per_user'] - $response) * 1.18;
            $data['coupon_price'] = $response;
            $data['coupon_code'] = $request->coupon_code;
            
            // dd($data);
        }else{
            $data['total_price_per_user_gst'] = $data['total_amount_gst'];
        }
        return view('frontend.account.update_user',$data);
    }

    public function membership_update_user(Request $request){
        $input = $request->all();
        
        $user = Auth::user();
        $order_id = $user->id.rand(1000,1000000);
        $user_id = $user->id;
        $amount =  $input['total_amount'];
        $total_user =  $input['total_user'];
        $package_id =  $input['package_id'];
        $wallet_amount =  ($input['wallet_amount_id'])?$input['wallet_amount_id']:0;
        // exit;
        // dd($input);
        if($amount > 0){
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
              'order' => $order_id,
              'user' => $user_id,
              'mobile_number' => $user->phone_no,
              'email' => $user->email,
              'amount' => $amount,
              'callback_url' => url('api/v1/subscriptions/user-status/'.$user_id.'/'.$package_id.'/'.$total_user.'/'.$wallet_amount)
            ]);
            return $payment->receive();
        }else{
            $user = User::where("id",$user_id)->first();
            $inserData = [
                "number_user"=>$user->number_user + $total_user
            ];
            // dd($inserData);
            $user->update($inserData);
            
            $membership_detail = Membership::where('user_id',$user_id)->where('is_active',1)->orderBy('id', 'desc')->first();

            $p_detail = PackageCreationDropdown::where("id",$package_id)->first();
            // $date=strtotime(date('Y-m-d'));
            $expire_at = $membership_detail->expire_at;
            // $amount =  $response['TXNAMOUNT'];
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user_id,
                'subscription_id' => $package_id,
                'package_id' => $package_id,
                'package_amount' => $p_detail->price,
                'package_user_amount' => $p_detail->price_per_user,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $total_user,
                'duration_name' => '',
                'expire_at' => $expire_at,
                'is_active' => 0,
                'type' => 3,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );

            $membership = Membership::create($membershipData);

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
                $inserData['note'] = "Membership";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 1;
                ReferralCode::create($inserData);
            }

            if($membership){
                $email = $user->email;
                $name = $user->name;
                $phone_no = $user->phone_no;
                //$amount = $amount;
                $date=date('d/m/Y');
                $dynamic_email = DB::table("dynamic_email")->where('id',1)->first();
                $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'date'=>$date,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer];
                Mail::send('emails.subscription',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }
            return redirect()->route('account.user_management')->with('success','Your subscription updated successfully');
        }
    }

    public function membership_update_user_callback($user_id,$package_id,$total_user,$wallet_amount){
        $transaction = PaytmWallet::with('receive');
 
        $response = $transaction->response();
         // dd($response);
        if($transaction->isSuccessful()){
            $order_id = $transaction->getOrderId();
            // dd($response);
            $user = User::where("id",$user_id)->first();
            $inserData = [
                "number_user"=>$user->number_user + $total_user
            ];
            $user->update($inserData);
            
            $p_detail = PackageCreationDropdown::where("id",$package_id)->first();

            $membership_detail = Membership::where('user_id',$user_id)->where('is_active',1)->orderBy('id', 'desc')->first();

            $expire_at = $membership_detail->expire_at;
            
            $amount =  $response['TXNAMOUNT'];
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user->id,
                'subscription_id' => $package_id,
                'package_amount' => $p_detail->price,
                'package_user_amount' => $p_detail->price_per_user,
                'package_id' => $package_id,
                'order_id' => $order_id,
                'subscription_type' => 'paid',
                'amount' => $amount,
                'duration' => $total_user,
                'duration_name' => '',
                'expire_at' => $expire_at,
                'is_active' => 0,
                'type' => 3,
                'ip_address' => $ip_address,
                'is_paid' => 1
            );

            $membership = Membership::create($membershipData);
            if($membership){
                $user = User::where('id',$user->id)->first();
                $email = $user->email;
                $name = $user->name;
                $phone_no = $user->phone_no;
                //$amount = $amount;
                $date=date('d/m/Y');
                $dynamic_email = DB::table("dynamic_email")->where('id',1)->first();
                $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'date'=>$date,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer];
                Mail::send('emails.subscription',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }
            
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
                $inserData['note'] = "Membership";
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
                $inserData['note'] = "User Addition";
                $inserData['from_user_id'] = 0;
                $inserData['is_paid'] = 0;
                ReferralCode::create($inserData);

                $email = $user->email;
                $dynamic_email = DB::table("dynamic_email")->where('id',3)->first();
                $messageData = ['name'=>$user->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"membership_point_email"=>$referral_amount,"total_amount"=>$amount];
                
                Mail::send('emails.membership-point',$messageData,function($message) use($email){
                     $message->from('info@masterstrokeonline.com', 'Masterstroke');
                    $message->to($email)->cc('info@masterstrokeonline.com')
                    ->subject('Membership with Masterstrokeonline');
                });
            }

            // return redirect()->route('account.profile');
            return redirect()->route('account.user_management')->with('success','Your subscription updated successfully');
        }else if($transaction->isFailed()){
            return redirect()->route('frontend.user_management')->with('error','There have some issues. Please try again');
        }
    }

    public function upgradePremiumMembershipTrial22(){
        $user = Auth::user();
        $premium_membership_trial=DB::table("premium_membership_trial")->where("user_id",$user->id)->first();
        $memberships = DB::table("memberships")->where("user_id",$user->id)->where("is_active",1)->first();

        if($premium_membership_trial){
            if($premium_membership_trial->status == 0){
                $expired_at = date('Y-m-d');
                $insertData = [];
                $insertData['package_id'] = $memberships->package_id;
                $insertData['use_count'] = $premium_membership_trial->use_count + 1;
                $insertData['expired_at'] = $expired_at;
                DB::table("premium_membership_trial")->where("user_id",$user->id)->update($insertData);

                $insertData = [];
                $insertData['package_id'] = 17;
                User::where('id',$memberships->user_id)->update($insertData);
                $insertData['response'] = $memberships->package_id;
                Membership::where('id',$memberships->id)->update($insertData);
            }else{
                return redirect()->back()->with('error','Premium Membership Trial Already.');
            }
        }else{
            $expired_at = date('Y-m-d');
            $insertData = [];
            $insertData['user_id'] = $user->id;
            $insertData['membership_id'] = $memberships->id;
            $insertData['package_id'] = $memberships->package_id;
            $insertData['use_count'] = 1;
            $insertData['expired_at'] = $expired_at;

            DB::table("premium_membership_trial")->insert($insertData);

            $insertData = [];
            $insertData['package_id'] = 17;
            User::where('id',$memberships->user_id)->update($insertData);
            $insertData['response'] = $memberships->package_id;
            Membership::where('id',$memberships->id)->update($insertData);
        }

        return redirect()->back()->with('success','Premium Membership Trial Successfully Updated.');
    }


    public function upgradePremiumMembershipTrial(){
        $user = Auth::user();
        
        $memberships = DB::table("memberships")->where("user_id",$user->id)->where("is_active",1)->where("flag",1)->first();
        $current_memberships = DB::table("memberships")->where("user_id",$user->id)->where("is_active",1)->first();
        
        $membership_flag_count = DB::table("memberships")->where("user_id",$user->id)->where("flag",1)->count();

        if($memberships){
            return redirect()->back()->with('error','Premium Membership Trial Already.');
        }else{

            $expire_at = strtotime(date('Y-m-d'));
            $expire_at = date('Y-m-d',strtotime('+15 day',$expire_at));

            $insertData = [];
            $insertData['is_active'] = 0;
            $insertData['flag'] = 2;
            Membership::where('id',$current_memberships->id)->update($insertData);
            $ip_address = getIp();
            $membershipData = array(
                'user_id' => $user->id,
                'subscription_id' => 15,
                'package_id' => 15,
                'order_id' => 0,
                'subscription_type' => 'free',
                'amount' => 0,
                'duration' => 0,
                'duration_name' => 'year',
                'expire_at' => $expire_at,
                'is_active' => 1,
                'flag' => 1,
                'ip_address' => $ip_address,
                'is_paid' => 0
            );
    
            $membership = Membership::create($membershipData);

            $insertData = [];
            $insertData['package_id'] = 15;
            User::where('id',$current_memberships->user_id)->update($insertData);
        }

        return redirect()->back()->with('success','Premium Membership Trial Successfully Updated.');
    }

}
