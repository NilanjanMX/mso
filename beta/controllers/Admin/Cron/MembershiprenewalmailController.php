<?php

namespace App\Http\Controllers\Admin\Cron;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\SchemecodeData;
use App\Models\Membership;
use App\Models\Option;
use App\Models\Autorenewal;

use Illuminate\Support\Facades\Mail;

class MembershiprenewalmailController extends Controller
{
    public function auto_email_process_renewal_before(){
        
        $autorenewal = Autorenewal::where('id',1)->first();
        //dd($autorenewal);
        if($autorenewal->autorenewal == 1){
            $direct = $autorenewal->direct;
            $icici = $autorenewal->icici;
            
            $membership_via = array();
            
            if($direct == 1){
                array_push($membership_via,'Direct');
            }
            
            if($icici == 1){
                array_push($membership_via,'ICICI');
            }
            
            //dd($membership_via);
            
            $option = Option::get();
            $amount =  $option[19]->option_value;
            $date=strtotime(date('Y-m-d')); 
            $expire_at = date('Y-m-d',strtotime('-15 days',$date));
            $expire_date = date('d/m/Y',strtotime($expire_at));
            //dd($expire_at);
            $memberships = Membership::where('expire_at',$expire_at)->where('subscription_type','paid')->whereIn('membership_via',$membership_via)->get();
            
            //dd($memberships);
            
            foreach($memberships as $membership){
                
                $user_id = $membership->user_id;
                
                $user = User::where('id',$user_id)->first();
                $email = $user->email;
                //$email = 'subhasishsamanta28@gmail.com';
                $name = $user->name;
                $phone_no = $user->phone_no;
                $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'expire_date'=>$expire_date];
        		Mail::send('emails.membership-alert-before',$messageData,function($message) use($email){
        		     $message->from('info@masterstrokeonline.com', 'Masterstroke');
        			$message->to($email)
        			->subject('Masterstrokeonline Membership Renewal');
        		});
        		
            }
        }
        
    }
    
    
    public function auto_email_process_renewal_after(){
        $autorenewal = Autorenewal::where('id',1)->first();
        //dd($autorenewal);
        if($autorenewal->autorenewal == 1){
            $direct = $autorenewal->direct;
            $icici = $autorenewal->icici;
            
            $membership_via = array();
            
            if($direct == 1){
                array_push($membership_via,'Direct');
            }
            
            if($icici == 1){
                array_push($membership_via,'ICICI');
            }
        $option = Option::get();
        $amount =  $option[19]->option_value;
        $date=strtotime(date('Y-m-d')); 
        $expire_at = date('Y-m-d',strtotime('+15 days',$date));
        //dd($expire_at);
        $expire_date = date('d/m/Y',strtotime($expire_at));
        $memberships = Membership::where('expire_at',$expire_at)->where('subscription_type','paid')->whereIn('membership_via',$membership_via)->get();
        
        //dd($memberships);
        
        foreach($memberships as $membership){
            
            $user_id = $membership->user_id;
            
            $user = User::where('id',$user_id)->first();
            $email = $user->email;
            //$email = 'subhasishsamanta28@gmail.com';
            $name = $user->name;
            $phone_no = $user->phone_no;
            $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'expire_date'=>$expire_date];
    		Mail::send('emails.membership-alert-after',$messageData,function($message) use($email){
    		     $message->from('info@masterstrokeonline.com', 'Masterstroke');
    			$message->to($email)
    			->subject('Masterstrokeonline Membership Renewal');
    		});
    		
        }
        }
        
    }
    
    
}
