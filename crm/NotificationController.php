<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Validator;
use App\Helpers\Helper;

use DB;
use Mail;
use App\Models\NotificationSetting;
use App\Models\DailyDigestMail;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Notification;

//Phase two Developement
class NotificationController extends Controller
{    
     //Notification Setting
     public function notificationSetting()
     {
         $user_id = Auth()->user()->id;    
         $data['is_backBtn'] = 1;

         $data['notification_modules']=DB::table('notification_module')->where('status', 1)->where('is_deleted',0)->where('id','!=', 3)->get();

         foreach($data['notification_modules'] as $modules){
            $module_name= $modules->module_name;
            $myself_inapp_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',1)->where('action_for',1)->first();

            $myself_push_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',2)->where('action_for',1)->first();

            $myself_mail_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',3)->where('action_for',1)->first();

            $team_inapp_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',1)->where('action_for',2)->first();

            $team_push_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',2)->where('action_for',2)->first();
            
            $team_mail_notificationSetting=NotificationSetting::where('created_by', $user_id)->where('notification_module_id',$modules->id)->where('notification_type',3)->where('action_for',2)->first();

            

            $data[$module_name.'myself_inapp_setting']= $myself_inapp_notificationSetting;
            $data[$module_name.'myself_push_setting']= $myself_push_notificationSetting;
            $data[$module_name.'myself_mail_setting']= $myself_mail_notificationSetting;

            $data[$module_name.'team_inapp_setting']= $team_inapp_notificationSetting;
            $data[$module_name.'team_push_setting']= $team_push_notificationSetting;
            $data[$module_name.'team_mail_setting']= $team_mail_notificationSetting;

         }
         return view('frontend.user.notification.notification_setting', $data);
     }
 
     public function saveNotificationSetting(Request $request)
     {        
         $user_id = Auth()->user()->id;
         $data['page_title'] = "Overview";
         $data['is_backBtn'] = 1;
        $action_type=$request->input('action_type');
         $action_value = $request->input('action_value');
         $module_id= $request->input('module_id');
         $notification_type = $request->input('notification_type');
         $action_for= $request->input('action_for');

         $is_edit= $request->input('is_edit');
         $is_assign= $request->input('is_assign');
         $is_delete= $request->input('is_delete');
         $is_duedate= $request->input('is_duedate');
         $frequency = $request->input('frequency');
         $action_value = '';
         if($action_type=='frequency_bandwidth'){
            if($request->action_value) {

                $action_value = implode(",",$request->action_value);
            }
         }   
         if($is_duedate ==1 && $module_id ==2) {
            $this->addMeetingNotification($notification_type);
         }     
 
        // $action_column= ($action_type == 'create') ? "create_notification" : ($action_type == 'edit' ? "edit_notification" : ($action_type == 'delete' ? "delete_notification" : ($action_type == 'assign' ? "assign_notification" : ($action_type == 'due_date' ? "due_date" : ($action_type == 'frequency' ? "frequency" : ($action_type == 'frequency_bandwidth' ? "frequency_bandwidth" : ""))))));       
         
         $notificationData=NotificationSetting::where('created_by', $user_id)->where('notification_module_id', $module_id)->where('notification_type', $notification_type)->where('action_for', $action_for)->first();
             if(empty($notificationData)){
                
                 $notification = new NotificationSetting;
                 $notification->notification_module_id = $module_id;
                 $notification->notification_type = $notification_type;
                 $notification->action_for = $action_for;
                 $notification->{$action_type} = $action_value;  
                 
                 $notification->assign_notification = $is_assign;
                 $notification->edit_notification = $is_edit;              
                 $notification->delete_notification = $is_delete;
                 $notification->due_date = $is_duedate;
                 $notification->frequency = $frequency;
                 
                 $notification->created_by = $user_id;     
                 $notification->save();
             }else{
                 $settingId =$notificationData->id;
                 $notification = NotificationSetting::find($settingId);
                 $notification->notification_module_id = $module_id;    
                 $notification->notification_type = $notification_type;
                 $notification->action_for = $action_for;
                 $notification->{$action_type} = $action_value;    
                 
                $notification->assign_notification = $is_assign;
                $notification->edit_notification = $is_edit;
                $notification->delete_notification = $is_delete;
                $notification->due_date = $is_duedate;
                $notification->frequency = $frequency;
                
                $notification->save();
             }
         
             return Response::json(['message' => 'Settings updated successfully']);
     }
     protected function addMeetingNotification($notification_type)
     {
        $user_id = Auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        $overduemeeting = Meeting::query()
                            ->leftjoin('contacts', 'contacts.id', '=', 'meetings.contact_id')
                            ->leftjoin('leads', 'leads.id', '=', 'meetings.lead_id')
                            ->select('meetings.*', 'contacts.first_name', 'contacts.last_name','leads.first_name as lead_first_name','leads.last_name as lead_last_name')
                            ->where('meetings.created_by', $user_id)
                            ->where('meetings.is_deleted', 0)
                            ->where('meetings.is_completed',0)
                            ->where(DB::raw("CONCAT(meetings.meeting_date, ' ', meetings.meeting_time)"), '<', date("Y-m-d H:i:s"))
                            ->get();
        if($notification_type ==1 || $notification_type ==2){ //1= in app notification,2=push,3=mail
           
            if(!empty($overduemeeting)) {
                if($notification_type ==1){
                    $old_notification_data = Notification::where('created_by', $user_id)->where('module_type',2)->where('inapp_notification_flag',1)->delete();
                    $old_notification_data->delete();
                }
                
                foreach($overduemeeting as $duemeeting){
                    $notification = new Notification;
                    if($duemeeting->contact_id !=''){
                        $meetingTittle ="Meeting is due for".' '.$duemeeting->first_name.' '.$duemeeting->last_name ;
                    }else{
                        $meetingTittle ="Meeting is due for".' '.$duemeeting->lead_first_name.' '.$duemeeting->lead_last_name ;
                    }
                    $notification->title =$meetingTittle;
                    if($notification_type ==1){
                        $notification->inapp_notification_flag =1;
                    }else{
                        $notification->push_notification_flag =1;
                    }
                    $notification->user_id =$user_id;
                    $notification->module_type =2;
                    $notification->created_by =$user_id;
                    $notification->save();

                }
                return true;
            }                 

        }else{
            return true;
            // if(!empty($overduemeeting)){
            //     $userData['overduemeeting'] = $overduemeeting;
            //     $userData['email'] =$userDetails->email;
            //     $userData['name'] =$userDetails->name;
            //     try{
            //         Mail::send('email_template.due_meeting_notification', ['user' => $userData], function ($m) use ($userData) {
            //           $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
            //           $m->to($userData['email'], $userData['name'])->subject("M-Edge");
            //           return true;
            //         });
            //       } catch (Exception $e) {
            //         return back()->with('message','Something went wrong! Please try again');
            //       }
            // }

        }

     }
     public function dailymailSetting()
     {
         $user_id = Auth()->user()->id;    
         $data['is_backBtn'] = 1;
         $data['notification_modules']=DB::table('daily_digest_mail')->where('created_by','=', $user_id)->first();
         return view('frontend.user.notification.daily_mail_setting', $data);
     }
     public function saveDailymailSetting(Request $request)
     {        
         $user_id = Auth()->user()->id;
         $data['page_title'] = "Overview";
         $data['is_backBtn'] = 1;
         $mail_type=$request->input('mail_type');
         $user = User::find($user_id);
         $user->daily_mail_type = $mail_type;
         $user->save();
         $notificationData=DailyDigestMail::where('created_by', $user_id)->first();
             if(empty($notificationData)){
                
                 $notification = new DailyDigestMail;
                 $notification->mail_type = $mail_type;
                 $notification->created_by = $user_id;     
                 $notification->save();
             }else{
                 $settingId =$notificationData->id;
                 $notification = DailyDigestMail::find($settingId);
                 $notification->mail_type = $mail_type;
                 $notification->created_by = $user_id;   
                 $notification->save();
             }
         
             return Response::json(['message' => 'Schedule mail setting updated successfully']);
     }

}
