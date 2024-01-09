<?php

namespace App\Http\Controllers;

use App\Models\Displayinfo;
use App\Models\Membership;
use App\Models\PackageCreationSetting;
use App\Models\TriggerUser;
use App\Models\Notification;
use App\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class UserNotificationController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function renewalNotificationMail(){
        
        $packageCreationSetting = PackageCreationSetting::where('id','1')->first();
        
        echo $days1 = $packageCreationSetting->subcription_expiry_reminder_day;
        echo "<br>";
        echo $days2 = (int)($packageCreationSetting->subcription_expiry_reminder_day/2);
        echo "<br>";
        
        echo $date['current_date1'] = date('Y-m-d', strtotime('+'.$days1.' days'));
        echo "<br>";
        echo $date['current_date2'] = date('Y-m-d', strtotime('+'.$days2.' days'));
        echo "<br>";
        echo $date['current_date3'] = date('Y-m-d');
        echo "<br>";
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','paid')
                    ->where('memberships.expire_at', $date['current_date1'])
                    ->get();
        
        $dynamic_email = DB::table("dynamic_email")->where('id',2)->first();
        $renewal_discount = DB::table("renewal_discount_price")->where("type",1)->first();
        $renewal_discount_price = $renewal_discount->percent;
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at)),"discount"=>$renewal_discount_price];

            Mail::send('emails.renewalNotification',$messageData,function($message) use($email){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Renewal Notification');
            });
        }
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','paid')
                    ->where('memberships.expire_at', $date['current_date2'])
                    ->get();
                    
        $renewal_discount = DB::table("renewal_discount_price")->where("type",2)->first();
        $renewal_discount_price = $renewal_discount->percent;
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at)),"discount"=>$renewal_discount_price];

            Mail::send('emails.renewalNotification',$messageData,function($message) use($email){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Renewal Notification');
            });
        }
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','paid')
                    ->where('memberships.expire_at', $date['current_date3'])
                    ->get();
                    
        $renewal_discount = DB::table("renewal_discount_price")->where("type",3)->first();
        $renewal_discount_price = $renewal_discount->percent;
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at)),"discount"=>$renewal_discount_price];

            Mail::send('emails.renewalNotification',$messageData,function($message) use($email){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject('Renewal Notification');
            });
        }
        dd("OK");
        
    }
    
    public function premiumMembershipTrial(){
        
        $current_date = date('Y-m-d');
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->where('memberships.is_active','1')->where('memberships.flag','1')
                    ->where('memberships.subscription_type','free')->where('memberships.expire_at','<=', $current_date)
                    ->get();
                    
        dd($membership);
    }
    
    public function sendEmail($html,$email,$name,$userList,$attachmentList,$subject){
        
        require public_path('f/emailtest/vendor/autoload.php');
            
        $mail = new PHPMailer(true);
        try {
            
            //  dd($mail);
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host       = env('SMTPCredentialServer');  
            $mail->SMTPAuth   = true;
            $mail->Username   = env('SMTPCredentialUsername');
            $mail->Password   = env('SMTPCredentialPassword');
            $mail->Port       = env('SMTPCredentialPort');
            
            $mail->setFrom('info@masterstrokeonline.com', 'Master stroke');
            $mail->addAddress($email, $name);
            foreach($userList as $value){
                $mail->addCC($value['email'], $value['name']);
                
            }
            
            $mail->SMTPOptions = array(
                'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
              )
            ); 
           
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $html;
            $mail->AltBody    = $html;
            
            foreach($attachmentList as $value){
                $mail->addAttachment($value['link']);
                
            }
        
            $mail->send();
            
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
    
    public function sendMailToExpiredUser(){
        
        $current_date5 = date('Y-m-d', strtotime('+5 days'));
        $current_date10 = date('Y-m-d', strtotime('+10 days'));
        $current_date15 = date('Y-m-d', strtotime('+15 days'));
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','free')
                    ->where('memberships.expire_at', $current_date5)
                    ->get();
                    
        $dynamic_email = DB::table("dynamic_email")->where('type',5)->first();
        // dd($membership); 
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at))];
            $subject = $dynamic_email->subject;
            
            Mail::send('emails.expired_user',$messageData,function($message) use($email,$subject){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject($subject);
            });
        }
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','free')
                    ->where('memberships.expire_at', $current_date10)
                    ->get();
                    
        $dynamic_email = DB::table("dynamic_email")->where('type',6)->first();
                    
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at))];
            $subject = $dynamic_email->subject;
            
            Mail::send('emails.expired_user',$messageData,function($message) use($email,$subject){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject($subject);
            });
        }
        
        $membership = Membership::leftJoin('users', 'users.id', '=' , 'memberships.user_id')
                    ->select(['memberships.user_id','memberships.expire_at','users.name','users.email','memberships.expire_at'])
                    ->where('memberships.is_active','1')->where('memberships.subscription_type','free')
                    ->where('memberships.expire_at', $current_date15)
                    ->get();
                    
        $dynamic_email = DB::table("dynamic_email")->where('type',7)->first();
                    
        foreach($membership as $key => $value){
            $email = $value->email;
            $messageData = ['name'=>$value->name,'email_header'=>$dynamic_email->email_header,'email_footer'=>$dynamic_email->email_footer,"expire_at"=>date('d-m-Y', strtotime($value->expire_at))];
            $subject = $dynamic_email->subject;
            
            Mail::send('emails.expired_user',$messageData,function($message) use($email,$subject){
                 $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email)->cc('info@masterstrokeonline.com')
                ->subject($subject);
            });
        }
    }
    
    public function mailSendTodayOrderMembership(){
        // 
        $result = Order::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","orders.payable_amount","orders.created_at"])
                ->LeftJoin('users', 'users.id', '=', 'orders.user_id')->where("payable_amount","!=",0)
                ->whereYear('orders.created_at',date('Y'))->whereMonth('orders.created_at',date('m'))->whereDay('orders.created_at',date('d'))->latest()->get();
        $columnNames = [
            'S. No.',
            'Name',
            'Email id',
            'Contact No',
            'City',
            'Payment Date',
            'Amount',
            'GST No'
        ];
        
        $path = public_path("admin_demo/order.csv");
        
        $file = fopen($path, 'w');
        fputcsv($file, $columnNames);
        
        foreach ($result as $key => $row) {
            fputcsv($file, [
                $key+1,
                $row->name,
                $row->email,
                $row->phone_no,
                $row->city,
                date('d-m-Y',strtotime($row->created_at)),
                $row->payable_amount,
                $row->gst_number,
            ]);
        }
        
        fclose($file);
                
        $result = Membership::select(["users.name","users.email","users.phone_no","users.city","users.gst_number","memberships.package_id","memberships.amount","memberships.created_at","package_creation_dropdowns.name as package_name"])
                ->LeftJoin('users', 'users.id', '=', 'memberships.user_id')
                ->LeftJoin('package_creation_dropdowns', 'package_creation_dropdowns.id', '=', 'memberships.package_id')
                ->where("amount","!=",0)->whereYear('memberships.created_at',date('Y'))->whereMonth('memberships.created_at',date('m'))->whereDay('memberships.created_at',date('d'))->latest()->get();
        
        $filename = 'membership_admin.csv';
        $columnNames = [
            'S. No.',
            'Name',
            'Email id',
            'Contact No',
            'City',
            'Package',
            'Payment Date',
            'Amount',
            'GST No'
        ];
        $path1 = public_path("admin_demo/membership.csv");
        
        $file = fopen($path1, 'w');
        fputcsv($file, $columnNames);
        
        foreach ($result as $key => $row) {
            fputcsv($file, [
                $key+1,
                $row->name,
                $row->email,
                $row->phone_no,
                $row->city,
                $row->package_name,
                date('d-m-Y',strtotime($row->created_at)),
                $row->amount,
                $row->gst_number,
            ]);
        }
        
        fclose($file);
        
        $subject = "Today Order And Membership";
        
        $html = '<html>
            <head>
                <title>User</title>
            </head>
            <body>
                <p>Dear Admin,</p>
                <table>
                    <tr><td>Please find attachment for today store and membership</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>&nbsp;</td></tr>
                    <tr><td>Thanks & Regards,</td></tr>
                    <tr><td>Team-Masterstroke</td></tr>
                </table>
            </body>
        </html>';
        $email = "brijeshdalmia@gmail.com";
        //$email = "avijitsamanta@matrixnmedia.com";
        // $email = "randhir.zabingo@gmail.com";
        $name = "Brijesh";
        
        $userList = [
            ["email"=>"ayushmandalmia24@gmail.com","name"=>"Ayushman"],
            ["email"=>"info.masterstrokeonline@gmail.com","name"=>"Info"],
            ["email"=>"dhruba@dalmiawealth.in","name"=>"Dhruba"],
            ["email"=>"ayushman@masterstrokeonline.com","name"=>"Ayushman"]
        ];
        // $userList = [
        //     ["email"=>"randhirjha2212@gmail.com","name"=>"Ayushman"]
        // ];
        $attachmentList = [
            ["link"=>public_path('admin_demo/membership.csv'),"name"=>"Membership"],
            ["link"=>public_path('admin_demo/order.csv'),"name"=>"Order"]
        ];
        $messageData = [];

         Mail::send('emails.today_order_membership',$messageData,function($message) use($email,$subject,$attachmentList,$name,$userList){
                $message->from('info@masterstrokeonline.com', 'Masterstroke');
                $message->to($email,$name);
                foreach($userList as $value){
                    $message->cc($value['email'], $value['name']);
                }
                $message->subject($subject);
                if(count($attachmentList) > 0) {
                    foreach($attachmentList as $value){
                        $message->attach($value['link']);
                    }
                }
            });
     
        // if( count(Mail::failures()) > 0 ) {
        //     echo "There was one or more failures. They were: <br />";
         
        //     foreach(Mail::failures() as $email_address) {
        //         echo " - $email_address <br />";
        //      }
        //  } else {
        //      echo "No errors, all sent successfully!";
        //  }
        // $this->sendEmail($html,$email,$name,$userList,$attachmentList,$subject);
        dd("Ok");
        
    }

    public function update_user_data(){

        $mf_scanner_saved_filter = DB::table("mf_scanner_saved_filter")->get();
        // dd($mf_scanner_saved_filter);
        foreach ($mf_scanner_saved_filter as $key => $value) {
            echo $value->id."<br>";
            $data = unserialize($value->data);
            // dd($data);
            $data['rating'] = "5,4,3,2,1,0";
            // $response = [];

            // foreach ($data['response'] as $k1 => $v1) {
            //     if($k1 == 1){
            //         array_push($response, ['id'=>'39','name'=>'MSO Rating','is_checked'=>1,"key_name"=>'rating',"order"=>7,"type"=>1,"table_checkbox"=>0,"is_freeze"=>0]);
            //     }
            //     array_push($response, $v1);
            // }

            // $data['response'] = $response;


            $insertData = [
                "data"=>serialize($data)
            ];

            DB::table("mf_scanner_saved_filter")->where("id",$value->id)->update($insertData);
        }
        // dd($mf_scanner_saved_filter);
    }
    
    
    public function update_multi_user(){
        $data = DB::table("mf_scanner_old")->where("status",0)->get();
        
        foreach($data as $key => $value){
            DB::table("mf_scanner")->where('schemecode','=',$value->schemecode)->update(["status"=>0]);
        }
        dd($data);
    }
    
}