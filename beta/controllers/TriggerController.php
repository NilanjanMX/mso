<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Trigger;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

use Auth;
use DB;
use Session;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class TriggerController extends Controller
{
    
    public function sendEmailAndNotification($value,$publish_date){
        // dd($value);
        $email = $value->email;
        $messageData = [
                    'trigger_name'=>$value->trigger_name,
                    'remarks'=>$value->remarks,
                    'trigger_condition'=>$value->trigger_condition,
                    'trigger_value'=>$value->trigger_value,
                    'navrs'=>$value->navrs,
                    'created_at'=>$value->created_at,
                    'trigger_hit_date'=>$publish_date
            ];
            
            // dd($messageData);
        $subject = "Your trigger has hit";
        return view('emails.trigger_email',$messageData);
       
        
        // dd($saveData);

        Notification::create($saveData);
        
    }
    
    public function sendEmail($messageData,$email,$name){
        
        // require public_path('f/emailtest/vendor/autoload.php');
            
        // $mail = new PHPMailer(true);
        // try {
        //     //  dd(env('SMTPCredentialPort'));
        //     $mail->SMTPDebug = 0;
        //     $mail->isSMTP();
        //     $mail->Host       = env('SMTPCredentialServer');  
        //     $mail->SMTPAuth   = true;
        //     $mail->Username   = env('SMTPCredentialUsername');
        //     $mail->Password   = env('SMTPCredentialPassword');
        //     $mail->Port       = env('SMTPCredentialPort');
            
        //     $mail->setFrom('info@masterstrokeonline.com', 'Master stroke');
        //     $mail->addAddress($email, $name);
        
        //     $mail->SMTPOptions = array(
        //         'ssl' => array(
        //         'verify_peer' => false,
        //         'verify_peer_name' => false,
        //         'allow_self_signed' => true
        //       )
        //     ); 
        //     $html='<!DOCTYPE html>
        //         <html>
        //             <head>
        //                 <title></title>
        //             </head>
        //             <body>
        //                 <table>
        //                     <tr><td>Dear Member,</td></tr>
        //                     <tr><td>&nbsp;</td></tr>
        //                     <tr><td> Your trigger for '.$messageData["trigger_type"].' has hit. Kindly login to masterstrokeonline.com to view the trigger.</td></tr>
        //                     <tr><td>'.$messageData["s_name"].'</td></tr>
        //                     <tr><td>'.$messageData["trigger_type"].'</td></tr>
        //                     <tr><td>'.$messageData["remarks"].'</td></tr>
        //                     <tr><td>&nbsp;</td></tr>
        //                     <tr><td>Value: '.$messageData["navrs"].'&nbsp;&nbsp;&nbsp; Range: '.$messageData["trigger_condition"].'</td></tr>
        //                     <tr><td>Set Date:'.$messageData["created_at"].'</td></tr>
        //                     <tr><td>Hit Date:'.$messageData["trigger_hit_date"].'</td></tr>
        //                     <tr><td>&nbsp;</td></tr>
        //                     <tr><td>&nbsp;</td></tr>
        //                     <tr><td>&nbsp;</td></tr>
        //                     <tr><td>Thanks & Regards,</td></tr>
        //                     <tr><td>Team-Masterstroke</td></tr>
        //                 </table>
        //             </body>
        //         </html>';
           
        //     $mail->isHTML(true);
        //     $mail->Subject = 'Your trigger has hit';
        //     $mail->Body    = $html;
        //     $mail->AltBody    = $html;
        
        //     $mail->send();
            
        // } catch (Exception $e) {
        //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        // }

        Mail::send('emails.trigger_email',$messageData,function($message) use($email,$name){
            $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to('nilanjan@matrixnmedia.com',$name);

            $message->subject("Your trigger has hit");
          
        });
 
    }
    
    public function sendSMS($mobile_number,$amount){
        $message = '"Your trigger for '.$amount.' has hit. Kindly login to masterstrokeonline.com to view the trigger."';
        $username = "masterstroke";
        $password = "Mstroke@2021";
        $sender = "MSTRKE";
        $template_id = "1007655862248081217";
        $endpoint = "http://api.bulksmsgateway.in/sendmessage.php?user=".urlencode($username)."&password=".urlencode($password)."&mobile=".urlencode($mobile_number)."&message=".urlencode($message)."&sender=".urlencode($sender)."&type=".urlencode('3')."&template_id=".urlencode($template_id);
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();

        $Scheme_details = json_decode($response->getBody());
    }
    
    public function trigger_cron(Request $request){
        $count = ($request->count)?$request->count:0;
        $count = (int) $count;
        $count = $count *25;
        if($count == 0){
            DB::table("trigger_users")->where("trigger_users.is_email_hit","=",0)->where("trigger_users.user_id","!=",0)->update(["mso_id"=>0]);
        }
        
        $publish_date = date("Y-m-d h:i:s");
        $triggerUser = DB::table("trigger_users")->select(["trigger_users.*","users.name","users.phone_no","users.email","triggers.name as triggers_name"])
                        ->LeftJoin('users', 'users.id', '=', 'trigger_users.user_id')
                        ->LeftJoin('triggers', 'triggers.type', '=', 'trigger_users.trigger_type')
                        ->where("trigger_users.user_id","!=",0)
                        ->where("trigger_users.is_email_hit","=",0)
                        ->skip($count)
                        ->take(25)
                        ->get();
                        
        
        if(count($triggerUser) == 0){
            DB::table("trigger_users")->where("trigger_users.mso_id","=",1)->where("trigger_users.user_id","!=",0)->update(["is_email_hit"=>1]);
        }   
        
        
        foreach ($triggerUser as $key => $value) {
            echo "<br>".$value->id."--".$value->trigger_type;
            $navrs = (float) $value->navrs;
            $s_name = "";
            $current_navrs = "";
            if($value->trigger_type == "nav-trigger"){
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["accord_scheme_details.s_name","accord_currentnav.navrs"])
                                    ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                    ->where("accord_scheme_details.schemecode",$value->scheme)->first();
                
                // dd($accord_scheme_details);
                
                $s_name = $accord_scheme_details->s_name;
                $current_navrs = (float) $accord_scheme_details->navrs;
            }else if($value->trigger_type == "index-trigger"){
                $accord_scheme_details = DB::table("accord_indicesmaster")
                                ->select(["accord_indicesmaster.INDEX_CODE as index_code","accord_indicesmaster.INDEX_NAME as index_n","accord_indicesmaster.EXCHANGE as EXCHANGE","accord_indicesmaster.INDEX_LNAME as index_name"])
                                ->where("INDEX_CODE",$value->select_index)->first();
                
                $s_name = $accord_scheme_details->index_name;
                if($accord_scheme_details->EXCHANGE == "BSE"){
                    $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$accord_scheme_details->index_code)->orderBy("DATE","DESC")->first();
                    if($indices_hst){
                        $current_navrs = (float) $indices_hst->CLOSE;
                    }
                }else if($accord_scheme_details->EXCHANGE == "NSE"){
                    $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$accord_scheme_details->index_n)->orderBy("DATE","DESC")->first();
                    if($indices_hst){
                        $current_navrs = (float) $indices_hst->CLOSE;
                    }
                }
            }else if($value->trigger_type == "aum-trigger"){
                if($value->specific_aum == "Scheme AUM"){
                    $accord_scheme_details = DB::table("accord_scheme_details")->select(["s_name","primary_fd_code"])->where("schemecode",$value->scheme)->first();
                    
                    $s_name = $accord_scheme_details->s_name;
                    
                    $accord_scheme_aum = DB::table("accord_scheme_aum")
                                ->where('schemecode','=',$accord_scheme_details->primary_fd_code)->orderBy("date","DESC")->first();
                                
                    $current_navrs = (float) $accord_scheme_aum->total;
                }else{
                    $accord_scheme_details = DB::table("accord_amc_mst")->select(["fund"])->where("amc_code",$value->select_amc)->first();
                    $accord_amc_paum = DB::table("accord_amc_paum")->where("amc_code",$value->select_amc)->orderBy("aumdate","DESC")->first();
                    // dd($accord_amc_paum);
                    $s_name = $accord_scheme_details->fund;
                    $current_navrs = (float) $accord_amc_paum->totalaum;
                }
            }else if($value->trigger_type == "category-performance-trigger"){
                $mf_scheme = DB::table("accord_sclass_mst")
                        ->select(['classname','mf_scanner_classcode.name as class_name'])
                        ->LeftJoin('mf_scanner_classcode', 'mf_scanner_classcode.classcode', '=', 'accord_sclass_mst.classcode')
                        ->where('accord_sclass_mst.classcode',$value->category_id)->first();
                $s_name = ($mf_scheme->classname)?$mf_scheme->classname:$mf_scheme->class_name;
                
                $mf_scanner_avg = DB::table("mf_scanner_avg")->where("classcode",$value->category_id)->where("plan_code",$value->plan_id)->first();
                $mf_scanner_avg = (array) $mf_scanner_avg;
                $current_navrs = (float) $mf_scanner_avg[$value->period_id];
            }else if($value->trigger_type == "scheme-performance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $s_name = $mf_scheme->s_name;
                
                $mf_scanner_avg = DB::table("mf_scanner")->where("schemecode",$value->mf_scheme)->first();
                $mf_scanner_avg = (array) $mf_scanner_avg;
                $current_navrs = (float) $mf_scanner_avg[$value->period_id];
                
            }else if($value->trigger_type == "quants-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $s_name = $mf_scheme->s_name;
                
                $mf_scanner_avg = DB::table("mf_scanner")->where("schemecode",$value->mf_scheme)->first();
                $mf_scanner_avg = (array) $mf_scanner_avg;
                $current_navrs = (float) $mf_scanner_avg[$value->select_quant];
                
            }else if($value->trigger_type == "scheme-performance-advance-trigger"){
                $mf_scheme = DB::table("mf_scanner")->select(["s_name"])->where("schemecode",$value->mf_scheme)->first();
                $s_name = $mf_scheme->s_name;
                if($value->trigger_condition == 1){
                    $current_navrs = -99999999999999999999999;
                }else{
                    $current_navrs = 99999999999999999999999;
                }
            }
            
            $current_navrs = number_format($current_navrs, 2, '.', '');
            
            if($value->trigger_condition == 1){
                if($navrs <= $current_navrs){
                    DB::table("trigger_users")->where("id",$value->id)->update(["mso_id"=>1,"trigger_hit_date"=>$publish_date]);
                    echo "--2--".$value->id;
                    
                    $trigger_user_settings = DB::table("trigger_user_settings")->where("user_id",$value->user_id)->first();
                    $notification = "E-mail";
                    if($trigger_user_settings){
                        $notification = $trigger_user_settings->notification;
                    }
                    
                    if($notification =="E-mail" || $notification == "Both"){
                        $messageData = [
                                    'trigger_name'=>$value->trigger_name,
                                    's_name'=>$s_name,
                                    'trigger_type'=>$value->triggers_name,
                                    'remarks'=>$value->remarks,
                                    'trigger_condition'=>($value->trigger_condition==1)?">=":"<=",
                                    'trigger_value'=>$value->trigger_value,
                                    'navrs'=>$value->navrs,
                                    'created_at'=>date('d/m/Y', strtotime($value->created_at)),
                                    'trigger_hit_date'=>date('d/m/Y', strtotime($publish_date))
                            ];
                        $this->sendEmail($messageData,$value->email,$value->name);
                    }
                    
                    if($notification =="SMS" || $notification == "Both"){
                        $this->sendSMS($value->phone_no,$value->trigger_name);
                    }
                    
                    $saveData = [
                        'title' => "Your trigger has hit",
                        'user_id' =>$value->user_id,
                        'description' => "Your trigger for ".$value->trigger_name." has hit. Kindly login to masterstrokeonline.com to view the trigger.",
                        'url' => url('trigger/completed')."?id=$value->id",
                        'created_at' => $publish_date,
                        'is_active' => 1
                    ];
                    Notification::create($saveData);
                }
            }else{
                if($navrs >= $current_navrs){
                    DB::table("trigger_users")->where("id",$value->id)->update(["mso_id"=>1,"trigger_hit_date"=>$publish_date]);
                    echo "--1--".$value->id;
                    
                    $trigger_user_settings = DB::table("trigger_user_settings")->where("user_id",$value->user_id)->first();
                    $notification = "E-mail";
                    if($trigger_user_settings){
                        $notification = $trigger_user_settings->notification;
                    }
                    
                    if($notification =="E-mail" || $notification == "Both"){
                        $messageData = [
                                    'trigger_name'=>$value->trigger_name,
                                    's_name'=>$s_name,
                                    'trigger_type'=>$value->triggers_name,
                                    'remarks'=>$value->remarks,
                                    'trigger_condition'=>($value->trigger_condition==1)?">=":"<=",
                                    'trigger_value'=>$value->trigger_value,
                                    'navrs'=>$value->navrs,
                                    'created_at'=>date('d/m/Y', strtotime($value->created_at)),
                                    'trigger_hit_date'=>date('d/m/Y', strtotime($publish_date))
                            ];
                        $this->sendEmail($messageData,$value->email,$value->name);
                    }
                    
                    if($notification =="SMS" || $notification == "Both"){
                        $this->sendSMS($value->phone_no,$value->trigger_name);
                    }
                    
                    $saveData = [
                        'title' => "Your trigger has hit",
                        'user_id' =>$value->user_id,
                        'description' => "Your trigger for ".$value->trigger_name." has hit. Kindly login to masterstrokeonline.com to view the trigger.",
                        'url' => url('trigger/completed')."?id=$value->id",
                        'created_at' => $publish_date,
                        'is_active' => 1
                    ];
                    Notification::create($saveData);
                }
            }
        }
        dd(count($triggerUser));
    }
    
    
    public function check_validation($value,$current_navrs,$s_name){
        $current_navrs = number_format($current_navrs, 2, '.', '');
            
        if($value->trigger_condition == 1){
            if($navrs <= $current_navrs){
                DB::table("trigger_users")->where("id",$value->id)->update(["mso_id"=>1,"trigger_hit_date"=>date("Y-m-d h:i:s")]);
                echo "--2--".$value->id;
                $messageData = [
                            'trigger_name'=>$value->trigger_name,
                            's_name'=>$s_name,
                            'trigger_type'=>$value->triggers_name,
                            'remarks'=>$value->remarks,
                            'trigger_condition'=>($value->trigger_condition==1)?">=":"<=",
                            'trigger_value'=>$value->trigger_value,
                            'navrs'=>$value->navrs,
                            'created_at'=>date('d/m/Y', strtotime($value->created_at)),
                            'trigger_hit_date'=>date('d/m/Y', strtotime($publish_date))
                    ];
                $this->sendEmail($messageData,$value->email,$value->name);
                
                $saveData = [
                    'title' => "Your trigger has hit",
                    'user_id' =>$value->user_id,
                    'description' => "Your trigger for ".$value->trigger_name." has hit. Kindly login to masterstrokeonline.com to view the trigger.",
                    'url' => url('trigger/completed')."?id=$value->id",
                    'created_at' => $publish_date,
                    'is_active' => 1
                ];
                Notification::create($saveData);
            }
        }else{
                if($navrs >= $current_navrs){
                    DB::table("trigger_users")->where("id",$value->id)->update(["mso_id"=>1,"trigger_hit_date"=>$publish_date]);
                    echo "--1--".$value->id;
                    $messageData = [
                                'trigger_name'=>$value->trigger_name,
                                's_name'=>$s_name,
                                'trigger_type'=>$value->triggers_name,
                                'remarks'=>$value->remarks,
                                'trigger_condition'=>($value->trigger_condition==1)?">=":"<=",
                                'trigger_value'=>$value->trigger_value,
                                'navrs'=>$value->navrs,
                                'created_at'=>date('d/m/Y', strtotime($value->created_at)),
                                'trigger_hit_date'=>date('d/m/Y', strtotime($publish_date))
                        ];
                    $this->sendEmail($messageData,$value->email,$value->name);
                    
                    $saveData = [
                        'title' => "Your trigger has hit",
                        'user_id' =>$value->user_id,
                        'description' => "Your trigger for ".$value->trigger_name." has hit. Kindly login to masterstrokeonline.com to view the trigger.",
                        'url' => url('trigger/completed')."?id=$value->id",
                        'created_at' => $publish_date,
                        'is_active' => 1
                    ];
                    Notification::create($saveData);
                }
            }
    }
    
    public function trigger_cron_new(Request $request){
        $mf_scanner_cron_id = 1;
        $mf_scanner_cron = DB::table('mf_scanner_cron')->where("id","=",$mf_scanner_cron_id)->first();
        $count = (int) $mf_scanner_cron->page_number;
        $count = $count *25;
        if($count == 0){
            DB::table("trigger_users")->where("trigger_users.is_email_hit","=",0)->where("trigger_users.user_id","!=",0)->update(["mso_id"=>0]);
        }
        
        if($mf_scanner_cron->status == 1){
            $triggerUser = DB::table("trigger_users")->select(["trigger_users.*","users.name","users.email","triggers.name as triggers_name"])
                        ->LeftJoin('users', 'users.id', '=', 'trigger_users.user_id')
                        ->LeftJoin('triggers', 'triggers.type', '=', 'trigger_users.trigger_type')
                        ->where("trigger_users.user_id","!=",0)
                        ->where("trigger_users.is_email_hit","=",0)
                        ->skip($count)
                        ->take(25)
                        ->get();
                        
            foreach ($triggerUser as $key => $value) {
                echo "<br>".$value->id."--".$value->trigger_type;
                $navrs = (float) $value->navrs;
                $s_name = "";
                $current_navrs = "";
                
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["accord_scheme_details.s_name","accord_currentnav.navrs"])
                                    ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                    ->where("accord_scheme_details.schemecode",$value->scheme)->first();
                
                if($accord_scheme_details){
                    $s_name = $accord_scheme_details->s_name;
                    $current_navrs = (float) $accord_scheme_details->navrs;
                    
                    $this->check_validation($value,$current_navrs,$s_name);
                }
            }
            
            if(count($triggerUser) == 0){
                DB::table("trigger_users")->where("trigger_users.trigger_cron_history_id","=",1)->where("trigger_users.user_id","!=",0)->update(["is_email_hit"=>1]);

                
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>0,"status"=>2]);
            }else{
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>$mf_scanner_cron->page_number+1]);
            }
        }else if($mf_scanner_cron->status == 2){
            $triggerUser = DB::table("trigger_users")->select(["trigger_users.*","users.name","users.email","triggers.name as triggers_name"])
                        ->LeftJoin('users', 'users.id', '=', 'trigger_users.user_id')
                        ->LeftJoin('triggers', 'triggers.type', '=', 'trigger_users.trigger_type')
                        ->where("trigger_users.user_id","!=",0)
                        ->where("trigger_users.is_email_hit","=",0)
                        ->skip($count)
                        ->take(25)
                        ->get();
                        
            foreach ($triggerUser as $key => $value) {
                echo "<br>".$value->id."--".$value->trigger_type;
                $navrs = (float) $value->navrs;
                $s_name = "";
                $current_navrs = "";
                
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["accord_scheme_details.s_name","accord_currentnav.navrs"])
                                    ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                    ->where("accord_scheme_details.schemecode",$value->scheme)->first();
                
                if($accord_scheme_details){
                    $s_name = $accord_scheme_details->index_name;
                    if($accord_scheme_details->EXCHANGE == "BSE"){
                        $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$accord_scheme_details->index_code)->orderBy("DATE","DESC")->first();
                        if($indices_hst){
                            $current_navrs = (float) $indices_hst->CLOSE;
                        }
                    }else if($accord_scheme_details->EXCHANGE == "NSE"){
                        $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$accord_scheme_details->index_n)->orderBy("DATE","DESC")->first();
                        if($indices_hst){
                            $current_navrs = (float) $indices_hst->CLOSE;
                        }
                    }
                    $this->check_validation($value,$current_navrs,$s_name);
                }
            }
            
            if(count($triggerUser) == 0){
                DB::table("trigger_users")->where("trigger_users.trigger_cron_history_id","=",1)->where("trigger_users.user_id","!=",0)->update(["is_email_hit"=>1]);
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>0,"status"=>3]);
            }else{
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>$mf_scanner_cron->page_number+1]);
            }
        }else if($mf_scanner_cron->status == 3){
            $triggerUser = DB::table("trigger_users")->select(["trigger_users.*","users.name","users.email","triggers.name as triggers_name"])
                        ->LeftJoin('users', 'users.id', '=', 'trigger_users.user_id')
                        ->LeftJoin('triggers', 'triggers.type', '=', 'trigger_users.trigger_type')
                        ->where("trigger_users.user_id","!=",0)
                        ->where("trigger_users.is_email_hit","=",0)
                        ->skip($count)
                        ->take(25)
                        ->get();
                        
            foreach ($triggerUser as $key => $value) {
                echo "<br>".$value->id."--".$value->trigger_type;
                $navrs = (float) $value->navrs;
                $s_name = "";
                $current_navrs = "";
                
                $accord_scheme_details = DB::table("accord_scheme_details")->select(["accord_scheme_details.s_name","accord_currentnav.navrs"])
                                    ->LeftJoin('accord_currentnav', 'accord_currentnav.schemecode', '=', 'accord_scheme_details.schemecode')
                                    ->where("accord_scheme_details.schemecode",$value->scheme)->first();
                
                if($accord_scheme_details){
                    $s_name = $accord_scheme_details->index_name;
                    if($accord_scheme_details->EXCHANGE == "BSE"){
                        $indices_hst = DB::table("accord_indices_hst_bsc")->where("SCRIPCODE",$accord_scheme_details->index_code)->orderBy("DATE","DESC")->first();
                        if($indices_hst){
                            $current_navrs = (float) $indices_hst->CLOSE;
                        }
                    }else if($accord_scheme_details->EXCHANGE == "NSE"){
                        $indices_hst = DB::table("accord_indices_hst_nsc")->where("SYMBOL",$accord_scheme_details->index_n)->orderBy("DATE","DESC")->first();
                        if($indices_hst){
                            $current_navrs = (float) $indices_hst->CLOSE;
                        }
                    }
                    $this->check_validation($value,$current_navrs,$s_name);
                }
            }
            
            if(count($triggerUser) == 0){
                DB::table("trigger_users")->where("trigger_users.trigger_cron_history_id","=",1)->where("trigger_users.user_id","!=",0)->update(["is_email_hit"=>1]);
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>0,"status"=>3]);
            }else{
                DB::table("mf_scanner_cron")->where("id","=",$mf_scanner_cron_id)->update(["page_number"=>$mf_scanner_cron->page_number+1]);
            }
        }else if($mf_scanner_cron->status == 4){
            
        }else if($mf_scanner_cron->status == 5){
            
        }else if($mf_scanner_cron->status == 6){
            
        }else if($mf_scanner_cron->status == 7){
            
        }else if($mf_scanner_cron->status == 8){
            
        }else if($mf_scanner_cron->status == 9){
            
        }else if($mf_scanner_cron->status == 10){
            
        }
    }

}