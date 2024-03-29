@extends('frontend.layouts.app')

@section('content')
@section('style')
<style>
     table.ui-datepicker-calendar {
        display: none;
    }

    .ui-datepicker-header.ui-widget-header.ui-helper-clearfix.ui-corner-all {
        display: none;
    }
    /* Sticky */
    .header {
        padding: 10px 16px;
        background: #fff;
        color: #f1f1f1;
        top:0;
    }

   /* .content {
        padding: 16px;
    }*/
    .sticky {
        position: fixed;
        top: 0;
        width: 77%;
        z-index:999;
    }

    .sticky + .content {
        padding-top: 102px;
    }

    ul.dropdown-menu.inner.show {
        background: #fafafa;
        z-index:7;
    }

    .dropdown-menu.show {
        display: block;
        border: 1px solid #d4dbdc;
        padding: 10px;
        background: #fafafa;
        z-index:7;
    }
    .dropdown-menu {
        display: none;
    }

    .bootstrap-select.show-tick .dropdown-menu li a span.text {        
        margin-left: 14px; 
        width: 100%;
    }
    div#bs-select-1 {
        overflow: hidden;
    }

</style>
@endsection('style')


<div class="rightPanel">
    <div class="task-table">
        <div class="header" id="stickyHeader">
            <table cellpadding="0" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th style="width:30%">Modules</th>
                        <th style="width:23%">In App</th>
                        <th>Push</th>
                        <th>Mail</th>
                    </tr>
                </thead>
            </table>
        </div>
        @php 
        $notification_type_inapp=1;
        $notification_type_push=2;
        $notification_type_mail=3;
        $action_for_myself=1;
        $action_for_assignedTeam=2;
        
        
        @endphp
        @foreach($notification_modules as $modules)
        @php 
        $myself_inapp_setting= $modules->module_name.'myself_inapp_setting';
        $myself_push_setting= $modules->module_name.'myself_push_setting';
        $myself_mail_setting= $modules->module_name.'myself_mail_setting';

        $team_inapp_setting= $modules->module_name.'team_inapp_setting';
        $team_push_setting= $modules->module_name.'team_push_setting';
        $team_mail_setting= $modules->module_name.'team_mail_setting';
       


        /* if(!empty($$myself_inapp_setting && $$myself_inapp_setting->frequency_bandwidth!=NULL)){
            $myself_inapp_frequencywidth = \Carbon\Carbon::parse($$myself_inapp_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $myself_inapp_frequencywidth='00:00';  
        }   
        if(!empty($$myself_push_setting && $$myself_push_setting->frequency_bandwidth!=NULL)){
            $myself_push_frequencywidth = \Carbon\Carbon::parse($$myself_push_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $myself_push_frequencywidth='00:00';  
        }  

        if(!empty($$myself_mail_setting  && $$myself_mail_setting->frequency_bandwidth!=NULL)){
            $myself_mail_frequencywidth = \Carbon\Carbon::parse($$myself_mail_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $myself_mail_frequencywidth='00:00';  
        }

     
        if(!empty($$team_inapp_setting && $$team_inapp_setting->frequency_bandwidth!=NULL)){
            $team_inapp_frequencywidth = \Carbon\Carbon::parse($$team_inapp_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $team_inapp_frequencywidth='00:00';  
        }   
        if(!empty($$team_push_setting && $$team_push_setting->frequency_bandwidth!=NULL)){
            $team_push_frequencywidth = \Carbon\Carbon::parse($$team_push_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $team_push_frequencywidth='00:00';  
        } 

        if(!empty($$team_mail_setting && $$team_mail_setting->frequency_bandwidth!=NULL)){
            $team_mail_frequencywidth = \Carbon\Carbon::parse($$team_mail_setting->frequency_bandwidth)->format('h:i A');
        }else{
            $team_mail_frequencywidth='00:00';  
        } */

             
        @endphp
             
        <div style="padding-bottom:20px; border-bottom:1px solid #000;">
            <table cellpadding="0" cellspacing="0" width="100%">
                <!--<thead>
                    <tr>
                        <th>Modules</th>
                        <th>In App</th>
                        <th>Push</th>
                        <th>Mail</th>
                    </tr>
                </thead>-->
                <tbody>
                    <tr>
                        <td colspan="4" style="text-align:center; font-weight:700; background-color: #F7F7F7 !important; font-size:20px; border-radius:30px;">{{$modules->module_name}}</td>
                    </tr>
                    <tr>
                        <td rowspan="5" style="width: 28%;"><b>Actions for Myself</b></td>
                        <!--Myself In App Create Section--->
                    </tr>
                    <?php
                     $myselfEditInappDefault = empty($$myself_inapp_setting) ? 1 : ($$myself_inapp_setting->edit_notification == 1 ? 1 : 0);

                     $myselfEditPushDefault = empty($$myself_push_setting) ? 1 : ($$myself_push_setting->edit_notification == 1 ? 1 : 0);

                     $myselfDueDateInappDefault = empty($$myself_inapp_setting) ? 1 : ($$myself_inapp_setting->due_date == 1 ? 1 : 0);

                     $myselfDueDatePushDefault = empty($$myself_push_setting) ? 1 : ($$myself_push_setting->due_date == 1 ? 1 : 0);
                    
                    ?>
                    @if($modules->module_name=="Task" || $modules->module_name=="Opportunity" || $modules->module_name=='Contacts')

                    
                    <tr>
                        <!--Myself In App Edit Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="edit_inapp{{$modules->id}}"
                                onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_myself}},'edit_notification')" <?php // if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->edit_notification==1){ echo 'checked' ;}
                                if($myselfEditInappDefault==1){ echo 'checked';}?>>
                                <label for="edit_inapp{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                   
                        <!--Myself Push Edit Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="edit_push{{$modules->id}}"
                                 onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_myself}},'edit_notification')" <?php //if(!empty($$myself_push_setting) &&$$myself_push_setting->edit_notification==1){ echo 'checked' ;}
                                 if($myselfEditPushDefault==1){ echo 'checked';}?>>
                                <label for="edit_push{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                        <!--Myself Mail Edit Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="edit_mail{{$modules->id}}"
                                onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_myself}},'edit_notification')" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->edit_notification==1){ echo 'checked' ;}?>>
                                <label for="edit_mail{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    @else
                    <tr></tr>

                    @endif
                    <tr></tr>
                    <tr></tr>
                    <tr>
                        <!--Myself In App Due Date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="due_date_inapp{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_myself}},'due_date')" <?php // if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->due_date==1){ echo 'checked' ;}
                                if($myselfDueDateInappDefault==1){echo 'checked';}?>>
                                <label for="due_date_inapp{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                         <!--Myself Push Due Date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="due_date_push{{$modules->id}}"
                                onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_myself}},'due_date')" <?php // if(!empty($$myself_push_setting) &&$$myself_push_setting->due_date==1){ echo 'checked' ;}
                                if($myselfDueDatePushDefault==1){echo 'checked';}?>>
                                <label for="due_date_push{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                         <!--Myself Mail Due Date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="due_date_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_myself}},'due_date')" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->due_date==1){ echo 'checked' ;}?>>
                                <label for="due_date_mail{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>                        
                        <td><b>Frequency</b></td>
                         <!--Myself In App Frequency Section--->                      

                        <td>
                            <select name="" id="frequency_inapp{{$modules->id}}" class="seetingselectbox" onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_inapp}}', '{{$action_for_myself}}', 'frequency')">
                                <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(empty($$myself_inapp_setting) ||$$myself_inapp_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php if(!empty($$myself_inapp_setting) &&$$myself_inapp_setting->frequency=='immediatly'){ echo 'selected' ;}?>>Immediately</option>
                            </select>
                        </td>
                         <!--Myself Push Frequency Section--->
                        <td>
                            <select name="" id="frequency_push{{$modules->id}}" class="seetingselectbox" onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_push}}', '{{$action_for_myself}}', 'frequency')">
                                <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(empty($$myself_push_setting) || $$myself_push_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$myself_push_setting) &&$$myself_push_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$myself_push_setting) &&$$myself_push_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$myself_push_setting) &&$$myself_push_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php if(!empty($$myself_push_setting) &&$$myself_push_setting->frequency=='immediatly'){ echo 'selected' ;}?>>Immediately</option>
                            </select>
                        </td>
                         <!--Myself Mail Frequency Section--->
                        <td>
                            <select name="" id="frequency_mail{{$modules->id}}" class="seetingselectbox" 
                            onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_mail}}', '{{$action_for_myself}}', 'frequency')">
                            <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(empty($$myself_mail_setting) ||$$myself_mail_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php if(!empty($$myself_mail_setting) &&$$myself_mail_setting->frequency=='immediatly'){ echo 'selected' ;}?>>Immediately</option>
                            </select>
                        </td>
                    </tr>
                    @if ($modules->module_name == "Meeting")
                        
                    <tr>
                        <td><b>Frequency Bandwidth</b></td>
                        
                         <!--Myself In App Frequency Bandwidth Section--->
                         <td></td>
                         <td></td>
                        <td>
                            <div class="input-item"> 
                                @php
                                $frequency_bandwidth = explode(',',$$myself_mail_setting->frequency_bandwidth);
                                @endphp
                                <select name="frequency_width[]"
                                    class="selectpicker form-control seetingselectbox" multiple 
                                    onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_mail}}', '{{$action_for_myself}}', 'frequency_bandwidth')" aria-label="Default select example">
                                    @if (!$frequency_bandwidth)
                                        <option value="30M" {{in_array( "30M", ["30M"]) ? 'selected' : ''}} >30 Minute</option>
                                        <option value="1H">1 Hour</option>
                                        <option value="1D" selected>1 Day</option>    
                                    @else    
                                    <option value="30M" 
                                    {{in_array( "30M",$frequency_bandwidth) ? 'selected' : ''}}>30 Minute</option>
                                    <option value="1H" {{in_array("1H",$frequency_bandwidth) ? 'selected' : ''}}>1 Hour</option>
                                    <option value="1D" {{in_array("1D",$frequency_bandwidth) ? 'selected' : ''}}>1 Day</option>
                                    @endif                           
                                </select>

                            </div>
                        </td>
                    
                        <!--Myself Push Frequency Bandwidth Section--->
                     {{-- <td>
                            <div class="inputfld">
                               <input type="text" class="textfld timefld cursor-pointer timePicker" name="frequency_width" placeholder="00:00" value="{{$myself_push_frequencywidth}}" data-module-id="{{$modules->id}}" data-notification-type="{{$notification_type_push}}" data-action-for="{{$action_for_myself}}" autocomplete="off" onkeydown="return false;" >
                            </div>
                        </td> --}}
                        <!--Myself Mail Frequency Bandwidth Section--->
                        {{-- <td>
                            <div class="inputfld">
                            <input type="text" class="textfld timefld cursor-pointer timePicker" name="frequency_width" placeholder="00:00" value="{{$myself_mail_frequencywidth}}" data-module-id="{{$modules->id}}" data-notification-type="{{$notification_type_mail}}" data-action-for="{{$action_for_myself}}" autocomplete="off" onkeydown="return false;" >
                            </div>
                        </td> --}}
                    </tr>
                    @endif
                    
                </tbody>
            </table>
        </div>
        <div style="padding-top:20px; padding-bottom: 20px; border-bottom:1px solid #000;">
        @if($modules->module_name=="Task" || $modules->module_name=="Opportunity" || $modules->module_name=='Contacts')
            <table cellpadding="0" cellspacing="0" width="100%">
                
                <tbody>
                    <tr>
                        <td colspan="4" style="height:0; background-color: #000 !important; line-height:0; padding:0;">&nbsp;</td>
                    </tr>                  
                    <tr>
                        <td rowspan="5" style="width: 28%;"><b>Actions for AssignedTeam </b></td>
                          <!--Team In App Create Section--->
                        {{--<td>
                            <div class="check-group">
                                <input type="checkbox" id="team_create_inapp{{$modules->id}}" 
                                onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_assignedTeam}},'create_notification')" <?php if(!empty($$team_inapp_setting) &&$$team_inapp_setting->create_notification==1 ){ echo 'checked' ;}?>>
                                <label for="team_create_inapp{{$modules->id}}" class="chklebel">
                                    <span>Create</span>
                                </label>
                            </div>
                        </td>
                          <!--Team Push Create Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_create_push{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_assignedTeam}},'create_notification')" <?php if(!empty($$team_push_setting) &&$$team_push_setting->create_notification==1 ){ echo 'checked' ;}?>>
                                <label for="team_create_push{{$modules->id}}" class="chklebel">
                                    <span>Create</span>
                                </label>
                            </div>
                        </td>
                        <!--Team Mail Create Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_create_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_assignedTeam}},'create_notification')" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->create_notification==1 ){ echo 'checked' ;}?>>
                                <label for="team_create_mail{{$modules->id}}" class="chklebel">
                                    <span>Create</span>
                                </label>
                            </div>
                        </td>--}}
                    </tr>
                    <tr>
                        <?php
                        //To check default check assign option
                        $teamAssignInappDefault = empty($$team_inapp_setting) ? 1 : ($$team_inapp_setting->assign_notification == 1 ? 1 : 0);

                        $teamAssignPushDefault = empty($$team_push_setting) ? 1 : ($$team_push_setting->assign_notification == 1 ? 1 : 0);

                         $teamAssignMailDefault = empty($$team_mail_setting) ? 1 : ($$team_mail_setting->assign_notification == 1 ? 1 : 0);
                                              
                         ?>
                         <!--Team In App Assign Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_assign_inapp{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_assignedTeam}},'assign_notification')" <?php //if(!empty($$team_inapp_setting) &&$$team_inapp_setting->assign_notification==1 ){ echo 'checked' ;}
                                if($teamAssignInappDefault==1){echo 'checked';}
                                ?>>
                                <label for="team_assign_inapp{{$modules->id}}" class="chklebel">
                                    <span>Assign</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Push Assign Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_assign_push{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_assignedTeam}},'assign_notification')" <?php //if(!empty($$team_push_setting) &&$$team_push_setting->assign_notification==1 ){ echo 'checked' ;}
                                 if($teamAssignPushDefault==1){echo 'checked';}
                                ?>>
                                <label for="team_assign_push{{$modules->id}}" class="chklebel">
                                    <span>Assign</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Mail Assign Section--->
                         
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_assign_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_assignedTeam}},'assign_notification')" <?php //if(!empty($$team_mail_setting) &&$$team_mail_setting->assign_notification==1 ){ echo 'checked' ;}
                                if($teamAssignMailDefault==1){echo 'checked'; }
                                ?>>
                                <label for="team_assign_mail{{$modules->id}}" class="chklebel">
                                    <span>Assign</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                         <!--Team In App Edit Section--->
                         <?php
                        //To check default check edit option
                        $teamEditInappDefault = empty($$team_inapp_setting) ? 1 : ($$team_inapp_setting->edit_notification == 1 ? 1 : 0);

                        $teamEditPushDefault = empty($$team_push_setting) ? 1 : ($$team_push_setting->edit_notification == 1 ? 1 : 0);
                                          
                         ?>
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_edit_inapp{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_assignedTeam}},'edit_notification')" <?php //if(!empty($$team_inapp_setting) &&$$team_inapp_setting->edit_notification==1 ){ echo 'checked' ;}
                                if($teamEditInappDefault==1){echo 'checked' ;}
                               ?>>
                                <label for="team_edit_inapp{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Push Edit Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_edit_push{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_assignedTeam}},'edit_notification')" <?php //if(!empty($$team_push_setting) &&$$team_push_setting->edit_notification==1 ){ echo 'checked' ;}
                                if($teamEditPushDefault==1){echo 'checked' ;}?>>
                                <label for="team_edit_push{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Mail Edit Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_edit_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_assignedTeam}},'edit_notification')" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->edit_notification==1 ){ echo 'checked' ;}?>>
                                <label for="team_edit_mail{{$modules->id}}" class="chklebel">
                                    <span>Edit after assignment</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                       <?php
                        //To check default check delete option
                        $teamDeleteInappDefault = empty($$team_inapp_setting) ? 1 : ($$team_inapp_setting->delete_notification == 1 ? 1 : 0);

                        $teamDeletePushDefault = empty($$team_push_setting) ? 1 : ($$team_push_setting->delete_notification == 1 ? 1 : 0);
                                          
                         ?>
                        <!--Team In App Delete Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_delete_inapp{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_assignedTeam}},'delete_notification')" <?php //if(!empty($$team_inapp_setting) &&$$team_inapp_setting->delete_notification==1 ){ echo 'checked' ;}
                                if($teamDeleteInappDefault==1){ echo 'checked' ;} ?>>
                                <label for="team_delete_inapp{{$modules->id}}" class="chklebel">
                                    <span>Delete after assignment</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Push Delete Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_delete_push{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_assignedTeam}},'delete_notification')" <?php //if(!empty($$team_push_setting) &&$$team_push_setting->delete_notification==1 ){ echo 'checked' ;}
                                if($teamDeletePushDefault==1){ echo 'checked' ;}?>>
                                <label for="team_delete_push{{$modules->id}}" class="chklebel">
                                    <span>Delete after assignment</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Mail Delete Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_delete_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_assignedTeam}},'delete_notification')" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->delete_notification==1 ){ echo 'checked' ;}?>>
                                <label for="team_delete_mail{{$modules->id}}" class="chklebel">
                                    <span>Delete after assignment</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    
                    <tr>
                    <?php
                        //To check default check delete option
                        $teamDueDateInappDefault = empty($$team_inapp_setting) ? 1 : ($$team_inapp_setting->due_date == 1 ? 1 : 0);

                        $teamDueDatePushDefault = empty($$team_push_setting) ? 1 : ($$team_push_setting->due_date == 1 ? 1 : 0);
                                          
                         ?>
                         <!--Team In App Due date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_duedate_inapp{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_inapp}} , {{$action_for_assignedTeam}},'due_date')" <?php //if(!empty($$team_inapp_setting) &&$$team_inapp_setting->due_date==1 ){ echo 'checked' ;}
                                if($teamDueDateInappDefault==1){echo 'checked';}?>>
                                <label for="team_duedate_inapp{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                         <!--Team Push Due date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_duedate_push{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_push}} , {{$action_for_assignedTeam}},'due_date')" <?php //if(!empty($$team_push_setting) &&$$team_push_setting->due_date==1 ){ echo 'checked' ;}
                                if($teamDueDatePushDefault==1){echo 'checked';}?> >
                                <label for="team_duedate_push{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                          <!--Team Mail Due date Section--->
                        <td>
                            <div class="check-group">
                                <input type="checkbox" id="team_duedate_mail{{$modules->id}}" onChange="notificationPermission(this, {{$modules->id}}, {{$notification_type_mail}} , {{$action_for_assignedTeam}},'due_date')" <?php if(!empty($$team_mail_setting) && $$team_mail_setting->due_date==1 ){ echo 'checked' ;}?>>
                                <label for="team_duedate_mail{{$modules->id}}" class="chklebel">
                                    <span>Due Date</span>
                                </label>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Frequency</b></td>
                          <!--Team In App Frequency Section--->
                        <td>
                            <select name="" id="team_frequency_inapp{{$modules->id}}" class="seetingselectbox" onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_inapp}}', '{{$action_for_assignedTeam}}', 'frequency')">
                                <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(!empty($$team_inapp_setting) &&$$team_inapp_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$team_inapp_setting) &&$$team_inapp_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$team_inapp_setting) &&$$team_inapp_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$team_inapp_setting) &&$$team_inapp_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php // if(!empty($$team_inapp_setting) &&$$team_inapp_setting->frequency=='immediatly'){ echo 'selected' ;}
                                 if(empty($$team_inapp_setting) || $$team_inapp_setting->frequency=='immediatly'){ echo 'selected'; }?>>Immediately</option>
                            </select>
                        </td>
                        <!--Team Push Frequency Section--->
                        <td>
                            <select name="" id="team_frequency_push{{$modules->id}}" class="seetingselectbox" onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_push}}', '{{$action_for_assignedTeam}}', 'frequency')">
                                <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(!empty($$team_push_setting) &&$$team_push_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$team_push_setting) &&$$team_push_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$team_push_setting) &&$$team_push_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$team_push_setting) &&$$team_push_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php //if(!empty($$team_push_setting) &&$$team_push_setting->frequency=='immediatly'){ echo 'selected' ;}
                                if(empty($$team_push_setting) || $$team_push_setting->frequency=='immediatly'){ echo 'selected'; }?>>Immediately</option>
                            
                            </select>
                        </td>
                        <!--Team Mail Frequency Section--->
                        <td>
                            <select name="" id="team_frequency_mail{{$modules->id}}" class="seetingselectbox" onchange="notificationPermission(this, '{{$modules->id}}', '{{$notification_type_mail}}', '{{$action_for_assignedTeam}}', 'frequency')">
                            <option value="" hidden selected>Select</option>
                                <option value="daily" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->frequency=='daily'){ echo 'selected' ;}?>>Daily</option>
                                <option value="no" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->frequency=='no'){ echo 'selected' ;}?>>No</option>
                                <option value="weekly" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->frequency=='weekly'){ echo 'selected' ;}?>>Weekly </option>
                                <option value="monthly" <?php if(!empty($$team_mail_setting) &&$$team_mail_setting->frequency=='monthly'){ echo 'selected' ;}?>>Monthly</option>
                                <option value="immediatly" <?php //if(!empty($$team_mail_setting) &&$$team_mail_setting->frequency=='immediatly'){ echo 'selected' ;}
                                if(empty($$team_mail_setting) || $$team_mail_setting->frequency=='immediatly'){ echo 'selected'; }?>>Immediately</option>
                            </select>
                        </td>
                    </tr>
                    <!--<tr>
                        <td><b>Frequency Bandwidth</b></td>-->
                        <!--Team In App Frequency Bandwidth Section--->
                        <!--<td>
                            {{-- <div class="inputfld"> 
                                <input type="text" class="textfld timefld cursor-pointer timePicker" name="frequency_width" placeholder="00:00" value="{{$team_inapp_frequencywidth}}" data-module-id="{{$modules->id}}" data-notification-type="{{$notification_type_inapp}}" data-action-for="{{$action_for_assignedTeam}}" autocomplete="off" onkeydown="return false;" >
                            </div> --}}
                        </td>-->
                        <!--Team Push Frequency Bandwidth Section--->
                        <!--<td>
                            <div class="inputfld">                               
                                {{-- <input type="text" class="textfld timefld cursor-pointer timePicker" name="frequency_width" placeholder="00:00" value="{{$team_push_frequencywidth}}" data-module-id="{{$modules->id}}" data-notification-type="{{$notification_type_push}}" data-action-for="{{$action_for_assignedTeam}}" autocomplete="off" onkeydown="return false;" > --}}
                            </div>
                        </td>-->
                        <!--Team Mail Frequency Bandwidth Section--->
                        <!--<td>
                            <div class="inputfld">
                               
                                {{-- <input type="text" class="textfld timefld cursor-pointer timePicker" name="frequency_width" placeholder="00:00" value="{{$team_mail_frequencywidth}}" data-module-id="{{$modules->id}}" data-notification-type="{{$notification_type_push}}" data-action-for="{{$action_for_assignedTeam}}" autocomplete="off" onkeydown="return false;" > --}}
                            </div>
                        </td>
                    </tr>-->
                    
                </tbody>
            </table>
            @endif
        </div>
       
        @endforeach
    </div>
    <!--<div class="agendabuttonarea leadsbutton">
        <button type="button" id="cancelBtn" class="canelButton">Cancel</button>
        <button type="submit" class="darkbuttonarea">Save</button>
    </div>-->
</div>
<div id="spinner">
    <img src="{{ asset('assets/images/ajax-loader.gif') }}">
</div>

@endsection
@section('script')
<script>
     $(document).ready(function() {
        let hour = 0;
        let minute = 0;
        $(".timePicker").datetimepicker({
            timeInput: false,
            controlType: 'select',
            timeFormat: "hh:mm TT",
            onSelect: function(selectedDateTime) {       
                console.log("Selected Date/Time: ", selectedDateTime);
            }, 
            beforeShow: function(input, inst) {            
                $(this).data('initial-value', $(this).val());
            },
            onClose: function(dateText, inst) {           
            $(this).datetimepicker("hide");
            var initialValue = $(this).data('initial-value');           
            if ($(this).val() !== initialValue) {
                var moduleId = $(this).data('module-id');
                var notificationType = $(this).data('notification-type');
                var actionFor = $(this).data('action-for');
                notificationPermission(this, moduleId, notificationType, actionFor, 'frequency_bandwidth');
            }
  
        },          
            dateFormat: '', // Set the date format to an empty string

        });
    });

    function notificationPermission(dis, module_id, notification_type, action_for, action_type) {
       

        //check checkbox checked or not of related module
        var type = (notification_type == 1) ? 'inapp' : ((notification_type == 2) ? 'push' : 'mail');

        if(action_for==2){
            var is_assign = $('#team_assign_' + type + module_id).prop('checked') ? 1 : 0;
            var is_edit = $('#team_edit_' + type + module_id).prop('checked') ? 1 : 0;
            var is_delete = $('#team_delete_' + type + module_id).prop('checked') ? 1 : 0;
            var is_duedate = $('#team_duedate_' + type + module_id).prop('checked') ? 1 : 0;

            var frequency=  $('#team_frequency_' + type + module_id).val();

        }else{
            var is_edit = $('#edit_' + type + module_id).prop('checked') ? 1 : 0;
            var is_duedate = $('#due_date_' + type + module_id).prop('checked') ? 1 : 0;
            var frequency=  $('#frequency_' + type + module_id).val();
            var is_assign = 0;           
            var is_delete =  0;
           
        }      
       
        var action_value=0;
        if(action_type=='frequency'){
            action_value= $(dis).val();
           
        }
        if(action_type=='frequency_bandwidth'){
            action_value= $(dis).val();
            
        }
        else{
            var isCheckboxChecked = $(dis).is(":checked");        
            if(isCheckboxChecked){
                action_value=1;
            }
        }
       
        var token = $("input[name='_token']").val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({

            url: "{{ route('user.save_notification_setting') }}",
            type: "POST",
            data: {
                _token: token,
                module_id: module_id,
                notification_type: notification_type,
                action_for: action_for,
                action_type: action_type,
                action_value:action_value,
                is_assign: is_assign,
                is_edit:is_edit,
                is_delete :is_delete,
                is_duedate: is_duedate,
                frequency:frequency

            },
            dataType: 'JSON',
            //processData: false,
            //contentType: false,
            beforeSend: function() {
                loader_show();
            },
            success: function(response) {
                loader_hide();
                $('#datetimepicker').prop('disabled', false);

                toastr.options = {
                    "closeButton": true,
                    "progressBar": true
                }
                toastr.success(response.message);
                window.setTimeout(function() {
                    // location.reload();
                }, 1000);
            },
            error: function(xhr) {
                console.log(xhr.responseText);
            }
        });

    }

    window.onscroll = function() {stickyFunction()};

        var header = document.getElementById("stickyHeader");
        var sticky = header.offsetTop;

        function stickyFunction() {
        if (window.pageYOffset > sticky) {
            header.classList.add("sticky");
        } else {
            header.classList.remove("sticky");
        }
    }
  
</script>
@endsection('script')
