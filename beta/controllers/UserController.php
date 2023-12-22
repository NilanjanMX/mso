<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use App\User;
use App\Models\Membership;
use App\Models\Displayinfo;
use App\Models\ReferralCode;
use App\Models\PackageCreationDropdown;
//use Spatie\Permission\Models\Role;
use Auth;
use DB;
use Hash;
use Illuminate\Support\Facades\Mail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')->paginate(5);
        return view('users.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if(!empty($user->id)){
            return redirect()->route('account.profile');
        }
        return view('auth.register');

    }
    public function create_referral_code(Request $request,$referral_code)
    {
        
        $user = Auth::user();
        if(!empty($user->id)){
            return redirect()->route('account.profile');
        }
        return view('auth.register');

    }

    public function register_save(Request $request){

        $input = $request->all();

        if($input['page_name'] == "step1"){
            $this->validate($request, [
                'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10|unique:users,phone_no',
                'email' => 'required|email|unique:users,email'
            ]);
            $insertData = [];
            $insertData['email'] = $input['email'];
            $insertData['phone_no'] = $input['phone_no'];
            $insertData['verification_code'] = rand(100000, 999999);
            // dd($insertData);
            session()->put('register_data',$insertData);

            $message = '"Dear User, your OTP is '.$insertData['verification_code'].'. Do not share this OTP with anyone. Regards, MasterStroke www.masterstrokeonline.com"';
            $username = "masterstroke";
            $mobile_number = $insertData['phone_no'];
            $password = "Mstroke@2021";
            $sender = "MSTRKE";
            $template_id = "1507161942771419402";
            $endpoint = "http://api.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3')."&template_id=".urlencode($template_id);
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $endpoint);
            $statusCode = $response->getStatusCode();

            $Scheme_details = json_decode($response->getBody());
            return redirect()->route('frontend.register_step2');
        }else if($input['page_name'] == "step2"){
            $this->validate($request, [
                'verification_code' => 'required'
            ]);

            if(!session()->get('register_data')){
                return redirect()->route('frontend.register');
            }
            if(!session()->get('register_data')['email']){
                return redirect()->route('frontend.register');
            }

            $user_detail = session()->get('register_data');
            // dd($user_detail);
            if($input['verification_code'] == $user_detail['verification_code']){
                $user_detail['is_verified'] = 1;
                session()->put('register_data',$user_detail);
                return redirect()->route('frontend.register_step3');
            }else{
                return redirect()->route('frontend.register_step2');
            }
        }else if($input['page_name'] == "step3"){
            if(!session()->get('register_data')['is_verified']){
                return redirect()->route('frontend.register_step3');
            }

            $this->validate($request, [
                'first_name' => 'required',
                'last_name' => 'required',
                'company_logo_old' => 'image|mimes:jpeg,png,jpg',
                'password' => 'required|same:confirm_password',
                'confirm_password' => 'required'
            ]);

            $user_detail = session()->get('register_data');

            $insertData = [];
            $insertData['email'] = $user_detail['email'];
            $insertData['phone_no'] = $user_detail['phone_no'];

            $insertData['password'] = Hash::make($input['password']);
            if(isset($input['company_logo']) && !empty($input['company_logo'])){
                $insertData['company_logo'] = $input['company_logo'];
            }else{
                $insertData['company_logo'] = '';
            }
            $name = $input['first_name'].' '.$input['last_name'];
            
            $insertData['name'] = $name;
            $insertData['first_name'] = $input['first_name'];
            $insertData['last_name'] = $input['last_name'];
            $insertData['city'] = $input['city'];
            $insertData['company_name'] = $input['company_name'];
            $insertData['gst_number'] = $input['gst_number'];
            //dd($input);
            $user = User::create($insertData);
            $last_insert_id = $user->id;
            
            $email = $insertData['email'];
            //$name = $input['name'];
            $phone_no = $insertData['phone_no'];
            $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no];
            Mail::send('emails.register',$messageData,function($message) use($email){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Registration with Masterstrokeonline');
            });
            
            $displayinfoData = array(
                'user_id' => $last_insert_id,
                'name' => $name,
                'first_name' => $input['first_name'],
                'last_name' => $input['last_name'],
                'email' => $email,
                'phone_no' => $phone_no,
                'city' => $input['city'],
                'company_name' => $input['company_name'],
                'company_logo' => $insertData['company_logo'],
                'address' => $input['city']
            );

            $displayinfo = Displayinfo::create($displayinfoData);

            // $date=strtotime(date('Y-m-d'));  // if today :2013-05-23

            // $expire_at = date('Y-m-d',strtotime('+15 days',$date));

            // //echo $newDate; //after15 days  :2013-06-07
            // $subscription_count = Membership::count();
            // $subscription_last_id = Membership::max('subscription_id');
            // if(!empty($subscription_count)){
            //     $subscription_id = $subscription_last_id+1;
            // }else{
            //     $subscription_id = 00001;
            // }
                
            // $membershipData = array(
            //     'user_id' => $last_insert_id,
            //     'subscription_id' => $subscription_id,
            //     'subscription_type' => 'free',
            //     'amount' => 0,
            //     'duration' => 15,
            //     'duration_name' => 'days',
            //     'membership_via' => '',
            //     'expire_at' => $expire_at,
            //     'is_active' => 1
            // );

            // $membership = Membership::create($membershipData);

            //$user->assignRole($request->input('roles'));

            // if(session()->get('ms_referral_code')){
                
            // }
            $membership_carts = session()->get('membership_carts');
            // dd($membership_carts);
            if($membership_carts){
                session()->put('membership_cart_user_id',$last_insert_id);
                return redirect()->route('frontend.membership_cart_get');
            }else{
                session()->put('membership_cart_user_id',$last_insert_id);
                return redirect()->route('frontend.membership');
            }
        }else {

        }
    }
    
    

    public function register_step2(Request $request){
        $user = Auth::user();
        if(!empty($user->id)){
            return redirect()->route('account.profile');
        }
        // dd(session()->get('register_data'));
        if(!session()->get('register_data')['email']){
            return redirect('/');
        }
        $data['user_detail'] = session()->get('register_data');
        
        return view('auth.registerstep2',$data);
    }

    public function register_step3(Request $request){
        $user = Auth::user();
        if(!empty($user->id)){
            return redirect()->route('account.profile');
        }
        if(!session()->get('register_data')['is_verified']){
            return redirect('/');
        }
        $data['user_detail'] = session()->get('register_data');
        return view('auth.registerstep3',$data);
    }
    
    public function free_register(){
        $package = PackageCreationDropdown::where('price','=',0)->first();

        $data = [];
        $data['user_number'] = 1;
        $data['package_id'] = $package->id;
        $data['package_detail'] = $package;
        $data['total_price_per_user'] = 0;
        $data['total_amount'] = $data['total_price_per_user'] + $data['package_detail']->price;
        $data['total_amount_gst'] = $data['total_amount'] * 1.18;
        // dd($data);
        $data['coupon_price'] = 0;
        $data['coupon_code'] = "";

        session()->put('membership_carts',$data);
        return redirect()->route('frontend.register');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'company_logo_old' => 'image|mimes:jpeg,png,jpg',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        if(isset($input['company_logo']) && !empty($input['company_logo'])){
            $input['company_logo'] = $input['company_logo'];
        }else{
            $input['company_logo'] = '';
        }
        $name = $input['first_name'].' '.$input['last_name'];
        
        $input['name'] = $name;
        //dd($input);
        $user = User::create($input);
        $last_insert_id = $user->id;
        
        $email = $input['email'];
        //$name = $input['name'];
        $phone_no = $input['phone_no'];
        $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no];
		Mail::send('emails.register',$messageData,function($message) use($email){
		     $message->from('info@masterstrokeonline.com', 'Masterstroke');
			$message->to($email)->cc('info@masterstrokeonline.com')
			->subject('Registration with Masterstrokeonline');
		});
        
        $displayinfoData = array(
            'user_id' => $last_insert_id,
            'name' => $name,
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'phone_no' => $input['phone_no'],
            'city' => $input['city'],
            'company_name' => $input['company_name'],
            //'company_logo' => isset($input['company_logo'])?$input['company_logo']:'',
            'company_logo' => $input['company_logo'],
            'address' => $input['city']
        );

        $displayinfo = Displayinfo::create($displayinfoData);

        $date=strtotime(date('Y-m-d'));  // if today :2013-05-23

        $expire_at = date('Y-m-d',strtotime('+15 days',$date));

        //echo $newDate; //after15 days  :2013-06-07
        $subscription_count = Membership::count();
        $subscription_last_id = Membership::max('subscription_id');
        if(!empty($subscription_count)){
            $subscription_id = $subscription_last_id+1;
        }else{
            $subscription_id = 00001;
        }
            
        $membershipData = array(
            'user_id' => $last_insert_id,
            'subscription_id' => $subscription_id,
            'subscription_type' => 'free',
            'amount' => 0,
            'duration' => 15,
            'duration_name' => 'days',
            'membership_via' => '',
            'expire_at' => $expire_at,
            'is_active' => 1
        );

        $membership = Membership::create($membershipData);

        //$user->assignRole($request->input('roles'));

        return redirect('/login')->with('success','User created successfully. You get 15 days trial.');
        //return redirect()->route('users.index')->with('success','User created successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();


        return view('users.edit',compact('user','roles','userRole'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);


        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));    
        }


        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();


        $user->assignRole($request->input('roles'));


        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }

    public function logoupload(Request $request){
        //echo $data = $_POST["image"];
        $input = $request->all();
        $data = $input['image'];
        $image_array_1 = explode(";", $data);
        $image_array_2 = explode(",", $image_array_1[1]);
        $data = base64_decode($image_array_2[1]);
        $imageName = time() . '.png';
        $destinationPath = public_path('/uploads/logo/'.$imageName);
        $destinationPath2 = public_path('/uploads/logo/original/'.$imageName);
        
        //echo $data;
        file_put_contents($destinationPath, $data);
        file_put_contents($destinationPath2, $data);

        $show_logo_url = asset('/uploads/logo/'.$imageName);

        $return_data = '<img src="'.$show_logo_url.'" class="img-thumbnail" />';
        $return_data .= '<input type="hidden" name="company_logo" value="'.$imageName.'">';
        echo $return_data;
        //echo '<img src="'.$show_logo_url.'" class="img-thumbnail" />';

    }

    public function username_update(){
        $users = User::get();
        foreach($users as $user){
            $username = trim($user->name,' ');
            $name = explode(' ',$username);
            if(count($name) > 1){
                $last_name = end($name);
                $first_name = trim(str_replace($last_name,'',$username),'');
            }else{
                $last_name = '';
                $first_name = $username;
            }
            
            $userData = User::where('id',$user->id)->first();
            $saveData = [
                'first_name' => $first_name,
                'last_name' => $last_name
            ];
            $res = $userData->update($saveData);
            
        }
        dd("successfully updated...");
    }

    public function displayinfoname_update(){
        $users = Displayinfo::get();
        foreach($users as $user){
            $username = trim($user->name,' ');
            $name = explode(' ',$username);
            if(count($name) > 1){
                $last_name = end($name);
                $first_name = trim(str_replace($last_name,'',$username),'');
            }else{
                $last_name = '';
                $first_name = $username;
            }
            
            $userData = Displayinfo::where('id',$user->id)->first();
            $saveData = [
                'first_name' => $first_name,
                'last_name' => $last_name
            ];
            $res = $userData->update($saveData);
            
        }
        dd("successfully updated...");
    }
    
    

    public function forgotPassword(){
        return view('auth.forgot_password');
    }

    // public function updateForgotPassword(Request $request){
        
    //     $user = User::where("email",$request->email)->first();
    //     if($user){
    //         require public_path('f/demo_email/vendor/autoload.php');
            
    //         $mail = new PHPMailer(true);
    //         try {
    //             $remember_token = md5("email").time();
    
    //             User::where("email",$request->email)->update(['remember_token'=>$remember_token]);
    //             // dd($user);
    //             $mail->SMTPDebug = 0;
    //             $mail->isSMTP();
    //             $mail->Host       = env('SMTPCredentialServer');  
    //             $mail->SMTPAuth   = true;
    //             $mail->Username   = env('SMTPCredentialUsername');
    //             $mail->Password   = env('SMTPCredentialPassword');
    //             $mail->Port       = env('SMTPCredentialPort');
            
    //             $mail->setFrom('info@masterstrokeonline.com', 'Master stroke');
    //             $mail->addAddress($user->email, $user->name);
    
    //             $url = url('/reset-password/')."/".$remember_token."?email=".$user->email;
    //             // $url = url('/password/reset/')."/".$remember_token."?email=".$user->email;
            
    //             $mail->SMTPOptions = array(
    //                 'ssl' => array(
    //                 'verify_peer' => false,
    //                 'verify_peer_name' => false,
    //                 'allow_self_signed' => true
    //               )
    //             ); 
    
    //             $html='<!DOCTYPE html>
    //                 <html>
    //                     <head>
    //                         <title></title>
    //                     </head>
    //                     <body>
    //                         <table align="center" width="570" cellpadding="0" cellspacing="0" >
    //                             <tr>
    //                                 <td>
    //                                     <h1>Hello!</h1>
    //                                     <p>You are receiving this email because we received a password reset request for your account.</p>
    //                                     <div style="text-align:center;">
    //                                         <a href="'.$url.'" style="border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3490dc;border-top:10px solid #3490dc;border-right:18px solid #3490dc;border-bottom:10px solid #3490dc;border-left:18px solid #3490dc" target="_blank">Reset Password</a>
    //                                     </div>
    //                                     <p>This password reset link will expire in 60 minutes.</p>
    //                                     <p>If you did not request a password reset, no further action is required.</p>
    //                                     <p>Regards,<br> Masterstroke</p>
    //                                     <hr/>
    //                                     <p style="box-sizing:border-box;color:#3d4852;line-height:1.5em;margin-top:0;text-align:left;font-size:12px">If you’re having trouble clicking the "Reset Password" button, copy and paste the URL below
    //                                         into your web browser: '.$url.'</p>
    //                                 </td>
    //                             </tr>
    //                         </table>
    //                     </body>
    //                 </html>';
                
    //             $mail->isHTML(true);
    //             $mail->Subject = 'Reset Password Notification';
    //             $mail->Body    = $html;
    //             $mail->AltBody    = $html;
            
    //             $mail->send();
    //             return redirect()->back()->withInput()->with('status', "We have e-mailed your password reset link!");
    //         } catch (Exception $e) {
    //             echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    //         }
    //     }else{
    //         return redirect()->back()->withInput()->with('status', "We have e-mailed your password reset link!");
    //     }
            
    // }

    public function updateForgotPassword(Request $request){
        
        $user = User::where("email",$request->email)->first();
        if($user){
            require public_path('f/demo_email/vendor/autoload.php');
            
            $mail = new PHPMailer(true);
            try {
                $remember_token = md5("email").time();
    
                User::where("email",$request->email)->update(['remember_token'=>$remember_token]);
               
                $url = url('/reset-password/')."/".$remember_token."?email=".$user->email;

                $email = $request->email;
                $userData['url']        = $url;
                $userData['email']      = $email;
                Mail::send('emails.forget-password-user',$userData,function($message) use($email){
                    $message->from('info@masterstrokeonline.com', 'Masterstroke');
                   $message->to($email)->cc('info@masterstrokeonline.com')
                   ->subject('Reset Password Notification');
               });
                return redirect()->back()->withInput()->with('status', "We have e-mailed your password reset link!");
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$e->ErrorInfo}";
            }
        }else{
            return redirect()->back()->withInput()->with('status', "We have e-mailed your password reset link!");
        }
            
    }

    public function resetPassword(Request $request,$token){
        $user = User::where("remember_token",$token)->first();
        
        // dd($user);
        
        $data['token'] = $token;
        $data['email'] = $request->email;

        if($user){
            $data['email_flag'] = 1;
        }else{
            $data['email_flag'] = 0;
        }
        return view('auth.reset_password',$data);
    }
    
    public function updateResetPassword(Request $request){
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required',
            'password' => 'same:password_confirmation'
        ]);


        $input = $request->all();
        // dd($input);
        $user = User::where("remember_token",$request->token)->first();

        if($user){
            $password = Hash::make($input['password']);
            User::where("id",$user->id)->update(['remember_token'=>"","password"=>$password]);
            return redirect()->route('login')->with('success','Password updated successfully');
        }
    }


}