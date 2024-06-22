<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Helpers\Helper;
use App\Models\Task;
use App\Models\ActivityLogs;
use App\Models\User;
use App\Models\Contact;
use App\Models\TaskStatus;
use App\Models\Notification;
use App\Models\OpportunityType;
use App\Models\ReportSetting;
use App\Models\TaskPoke;
use App\Models\Taskassignee;
use App\Models\TagForTask;
use App\Models\TaskTag;
use App\Models\UserCreatorTypeFilter;
use Illuminate\Support\Facades\Response;
use DB;
use App\Models\WorkflowStep;
use Carbon\Carbon;
use PDF;
use Mail;
use App\Models\Lead;

class TaskController extends Controller
{
     /*****************************************************/
    # TaskController
    # Function name : __construct() 
    # Author        :
    # Purpose       : To apply middleware, specifically to check the authorization of the authenticated user before allowing access to the controller's methods. 
    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            $userid = auth()->user()->id;
            $checkAuthorization= Helper::checkAuthorization($userid);
            if($checkAuthorization){
                return $next($request);
            }else{
                return \Redirect::route('user.dashboard')->with('message',"You are no longer accessible for this item, or this item has been deleted.");
            }
        });
    }
    /*****************************************************/
    # TaskController
    # Function name : index()
    # Author        :  
    # Purpose       : show task list with all data
    # Params        : Request $request
    /*****************************************************/
    public function index(Request $request)
    {    
         $user_id = auth()->user()->id;
        //  echo $request->userFilter;die;
         $userDetails = User::findOrFail($user_id);
         $get_task_creators_filter = UserCreatorTypeFilter::where('created_by', auth()->user()->id)->where('creator_filter_type',3)->first();
         if(!empty($get_task_creators_filter->creator_filter) && $get_task_creators_filter->creator_filter!=0){
            $data['opportunity_creator_filter'] = $get_task_creators_filter->creator_filter;
        }else{
            $data['opportunity_creator_filter'] = $user_id; 
        }
        
        $data['page_title'] = "Task Dashboard";
        if(auth()->user()->added_by ==0){
            $add_on_users = User::where('added_by', $user_id)->where('is_deleted', 0)->where('status', 1)->orderBy('name')->get();
            $data['edit_task']= array(11);
            $data['assign_task']= array(29);
        }else{
            $permissionData = \App\Models\RolePermission::where(['module_functionality_id' => 11, 'role_id' => $userDetails->role_id,'main_user_id' =>$userDetails->added_by, 'status' => 'A', 'is_deleted' => 0])->pluck('module_functionality_id')->toArray();
            $data['edit_task']= $permissionData;
            $data['assign_task']= \App\Models\RolePermission::where(['module_functionality_id' => 29, 'role_id' => $userDetails->role_id,'main_user_id' =>$userDetails->added_by, 'status' => 'A', 'is_deleted' => 0])->pluck('module_functionality_id')->toArray();

            if($userDetails->group_id ==null || $userDetails->group_id =='' ){
                $add_on_users = User::where('added_by', $user_id)->where('is_deleted', 0)->where('status', 1)->orderBy('name')->get();
            }else{
                $add_on_users = User::where('group_id', $userDetails->group_id)->where('added_by','!=',0)->whereNotNull('group_id')->where('is_deleted', 0)->where('status', 1)->orderBy('name')->get();
            }
        }
        if ($request->ajax()) {
            // \DB::enableQueryLog();
            $tasksQuery = Task::query()
                 ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
                 ->leftjoin('leads', 'leads.id', '=', 'tasks.lead_id')
                 ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
                 ->leftjoin('users', 'users.id', '=', 'tasks.created_by')
                 ->leftJoin('users as assignee', 'assignee.id', '=', 'tasks.assignee_id')
                 ->leftJoin('task_tags', 'task_tags.task_id', '=', 'tasks.id') // Joining the pivot table
                 ->leftJoin('tag_for_tasks', 'tag_for_tasks.id', '=', 'task_tags.task_tag_id') // Joining the tag table
                 ->select('tasks.*', 'contacts.first_name', 'contacts.last_name','leads.first_name as lead_first_name','leads.last_name as lead_last_name','task_status.name as task_status','users.name as task_creator','assignee.name as assignee_name',DB::raw('GROUP_CONCAT(tag_for_tasks.custom_tag ORDER BY tag_for_tasks.custom_tag SEPARATOR ", ") as tags'))
                 ->groupBy('tasks.id')
                ->where(function($q) use ($request,$user_id){                  
                    $add_on_user = $request->userFilter;
                    if ($add_on_user) {
                        $explode_user = explode(',', $add_on_user);
                        $cleaned_users = array_map(function ($user) {
                            return intval(trim($user));
                        }, $explode_user);
                        $q->whereIn('tasks.created_by', $cleaned_users)
                        ->orWhere('tasks.assignee_id',$user_id);
                        
                    }else{
                        $q->where('users.id', 0);
                        // $q->where('tasks.created_by', 0)
                        // ->orWhere('tasks.assignee_id',0);
                    }
                    
                 })
                ->where('tasks.is_deleted', 0)
                ->when($request->task_status, function ($q) use ($request) {
                    $task_status = $request->task_status;
                    if ($task_status == 'overdue') {
                        // echo "vvvv";//die;   
                        $q->whereDate('tasks.deadline', '<', now());
                        // $q->where('tasks.status','!=', 2);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($task_status == 'upcoming') {
                        // echo "aaaa";//die;    
                        $now = Carbon::now();
                        // $q->where('tasks.status','!=', 2);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                        $q->whereBetween("deadline", [
                            $now->format('Y-m-d'), 
                            $now->addDays(90)->format('Y-m-d')
                        ]);
                    }
                })
                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 'overdue') {
                        $q->whereDate('tasks.deadline', '<', now());
                        // $q->where('tasks.status','!=', 2);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($filter_by == 'pending') {
                        // $q->where('tasks.status', 1);
                        // $q->where('tasks.status','!=', 2);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($filter_by == 'upcoming') {
                        $now = Carbon::now();
                        $q->whereIn('tasks.status', [2,4],'and', true);
                        $q->whereBetween("deadline", [
                            $now->format('Y-m-d'), 
                            $now->addDays(90)->format('Y-m-d')
                        ]);
                    }
                })
                ->when($request->assigneeFilter, function ($q) use ($request) {
                    $assigneeFilterArr = json_decode($request->assigneeFilter);
                    // $opp_type = $request->opp_type;
                    // $q->where('opportunities.opportunity_type', $opp_type);
                    if(!empty($assigneeFilterArr)){
                        $q->whereIn('tasks.assignee_id', array_values($assigneeFilterArr));
                    }
                    
                
                })
                ->when($request->tagFilter, function ($q) use ($request) {
                    $tagFilterArr = json_decode($request->tagFilter);
                    if(!empty($tagFilterArr)){
                        $q->whereIn('task_tags.task_tag_id', array_values($tagFilterArr));
                    }
                })
                ->when($request->un_assigned, function ($q) use ($request) {
                    $q->where('tasks.assignee_id','=',NULL);
                })
                ->when($request->current_task, function ($q) use ($request) {
                    $current_task = $request->current_task;

                    if ($current_task == 'pending') {
                        // $q->where('tasks.status', 1);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($current_task == 'completed') {
                        $q->where('tasks.status', 2);
                        // $q->where('tasks.is_bookmark','!=' ,'1');
                    }
                    if ($current_task == 'priority') {
                        $q->where('tasks.is_bookmark', '1');
                        $q->where('tasks.status','!=', 2);
                    }
                })
                ->when($request->search_term, function ($q) use ($request) {                    
                    if ($request->search_term != '') {                        
                        $search_txt = $request->search_term;
                        // session(['task_srchTxt' =>  $request->search_term]); 
                        // $q->where('tasks.tittle', 'LIKE','%'.$request->search_term.'%');
                        $q->where(function ($q) use ($search_txt) {
                                // $q->where('tasks.tittle', 'LIKE',  $search_txt . '%')
                                     $q->where('contacts.first_name', 'LIKE', '%'. $search_txt . '%')
                                       ->orWhere('contacts.last_name', 'LIKE','%'. $search_txt.'%' )
                                       ->orWhere('leads.last_name', 'LIKE',  '%'. $search_txt.'%' )
                                       ->orWhere('leads.first_name', 'LIKE', '%'. $search_txt.'%' )
                                        ->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',   $search_txt . '%')
                                        ->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE',  $search_txt . '%')
                                        ->orWhere('tasks.tittle', 'LIKE', '%'. $search_txt.'%' );

                                        
                                        $txt = str_replace(" ", "%", $search_txt);
                                        $q->orWhere('tasks.tittle', 'LIKE',  '%'.$txt.'%' );
                                        $q->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',  '%'. $txt. '%');
                                        $q->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE', '%'. $txt. '%');

                        });
                    }
                })
                // ->when(session()->has('task_sess_srch_Txt'), function ($q) use ($request) { 
                //     $search_txt= '%' . session('task_sess_srch_Txt') . '%'; 
                //     session()->forget('task_sess_srch_Txt');
                //     session()->forget('task_srchTxt');
                //     $q->where(function ($q) use ($search_txt) {
                //         // $q->where('tasks.tittle', 'LIKE',  $search_txt . '%')
                //         $q->where('contacts.first_name', 'LIKE', '%'. $search_txt . '%')
                //         ->orWhere('contacts.last_name', 'LIKE','%'. $search_txt.'%' )
                //         ->orWhere('leads.last_name', 'LIKE',  '%'. $search_txt.'%' )
                //         ->orWhere('leads.first_name', 'LIKE', '%'. $search_txt.'%' )
                //         ->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',   $search_txt . '%')
                //         ->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE',  $search_txt . '%')
                //         ->orWhere('tasks.tittle', 'LIKE', '%'. $search_txt.'%' );

                //         $txt = str_replace(" ", "%", $search_txt);
                //         $q->orWhere('tasks.tittle', 'LIKE',  '%'.$txt.'%' );
                //         $q->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',  '%'. $txt. '%');
                //         $q->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE', '%'. $txt. '%');
                //     });
                   
                // })
                // ->when(session()->has('unassign_session'), function ($q){
                //     $q->where('tasks.assignee_id','=',NULL);
                //     session()->forget('unassign_session');
                // })
                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                }, function ($q) {
                    $q->orderBy('row_order', 'asc');
                    // $q->orderBy('tasks.id', 'desc');
                })
                ->latest();

                $tasks = !empty($request->record_count)
                ? $tasksQuery->paginate($request->record_count)
                : $tasksQuery->get();
                //->paginate($request->record_count ? $request->record_count : 10);

                 //Get Contact HOF
                // foreach ($tasks as $task) {
                //     $family_member = DB::table('contact_family_member')
                //         ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
                //         ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
                //         ->where('contact_family_member.contact_id', $task->contact_id)
                //         ->where('contact_family_member.family_member_id', '!=', $task->contact_id)
                //         ->where('contact_family_member.hof', '1')->get()->toArray();
                //     $task->family_member = (!empty($family_member)) ? $family_member : '';
                // }

                // if ($tasks->count() == 0) session()->forget('task_srchTxt');

                $data['data_count']= $tasks->count();
                //$data['total_count'] = $tasks->total();

                $data['total_count']=!empty($request->record_count)
                ?  $tasks->total()
                : '';
                // dd(\DB::getQueryLog());

            return view('frontend.task.task_data', compact('tasks','get_task_creators_filter'), $data)->render();
        }else{
            $tasks = Task::query()
                    ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
                    ->leftjoin('leads', 'leads.id', '=', 'tasks.lead_id')
                    ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
                    ->leftjoin('users', 'users.id', '=', 'tasks.created_by')
                    ->leftJoin('users as assignee', 'assignee.id', '=', 'tasks.assignee_id')
                    ->leftJoin('task_tags', 'task_tags.task_id', '=', 'tasks.id') // Joining the pivot table
                    ->leftJoin('tag_for_tasks', 'tag_for_tasks.id', '=', 'task_tags.task_tag_id') // Joining the tag table
                    ->select('tasks.*', 'contacts.first_name', 'contacts.last_name','leads.first_name as lead_first_name','leads.last_name as lead_last_name','task_status.name as task_status','users.name as task_creator','assignee.name as assignee_name',DB::raw('GROUP_CONCAT(tag_for_tasks.custom_tag ORDER BY tag_for_tasks.custom_tag SEPARATOR ", ") as tags'))
                    ->groupBy('tasks.id')
                    ->where(function ($q) use ($request,$user_id,$data) {
                     $userFilter=  $data['opportunity_creator_filter'];
                     if ($userFilter) {
                         $explode_user = explode(',', $userFilter);
                         $cleaned_users = array_map(function ($user) {
                             return intval(trim($user));
                         }, $explode_user);
                         if(!in_array(-1, $cleaned_users)){              
                         $q->whereIn('tasks.created_by', $cleaned_users);
                         }else{
                             $q->whereIn('tasks.created_by', $cleaned_users)
                             //$q->where('opportunities.created_by', $user_id)
                             ->orWhere('tasks.assignee_id',$user_id);
                         }
                     }else{
                         $q->where('tasks.created_by', $user_id)
                         ->orWhere('tasks.assignee_id',$user_id);
                     }
                     

                        // $add_on_user = $data['opportunity_creator_filter'];
                        // if ($add_on_user) {
                        //     $explode_user = explode(',', $add_on_user);
                        //     $cleaned_users = array_map(function ($user) {
                        //         return intval(trim($user));
                        //     }, $explode_user);
                        //     $q->whereIn('tasks.created_by', $cleaned_users)
                        //     ->orWhere('tasks.assignee_id',$user_id);
                            
                        // }else{
                        //     $q->where('users.id', 0);
                        //     // $q->where('tasks.created_by', 0)
                        //     // ->orWhere('tasks.assignee_id',0);
                        // }
                    })
                    
                    ->where('tasks.is_deleted', 0)
                    ->orderBy('tasks.row_order', 'ASC')
                    ->latest()
                    ->paginate(25);
                    // echo "<pre>";print_r($tasks);die;

        //Get Contact HOF
        // foreach ($tasks as $task) {
        //     $family_member = DB::table('contact_family_member')
        //         ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
        //         ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
        //         ->where('contact_family_member.contact_id', $task->contact_id)
        //         ->where('contact_family_member.family_member_id', '!=', $task->contact_id)
        //         ->where('contact_family_member.hof', '1')->get()->toArray();
        //     $task->family_member = (!empty($family_member)) ? $family_member : '';
        // }
        $data['data_count']= $tasks->count();
        $data['total_count'] = $tasks->total();
        }
        if(auth()->user()->added_by ==0){
            $data['added_user'] = User::where(function($q) use ($request){
                                        $q->where('id',auth()->user()->id)
                                            ->orWhere('added_by',auth()->user()->id);
                                        })
                                        ->where('is_deleted',0)
                                        ->where('status', 1)->orderBy('name','ASC')->get();
        }else{
            if($userDetails->role_id ==1){
                $data['added_user'] = User::where('group_id', $userDetails->group_id)->where('added_by','!=',0)->whereNotNull('group_id')->where('is_deleted',0)
                ->where('status', 1)->orderBy('name')->get();
            }else{
                $data['added_user'] = User::where('id', $user_id)->where('is_deleted',0)
            ->where('status', 1)->orderBy('name')->get();
            }
        }
        if(auth()->user()->added_by ==0){
            $mainUserId =auth()->user()->id;
        }else{
            $mainUserId =auth()->user()->added_by;
        }
        // $data['tag_for_task'] = TagForTask::where('created_by', $user_id)->where('is_deleted',0)
        //                      ->where('status', 1)->orderBy('custom_tag')->get();
           $data['tag_for_task'] = TagForTask::where(function($q) use ($request,$user_id,$mainUserId){
                                    $q->where('created_by',$user_id)
                                        ->orWhere('main_user_id',$mainUserId);
                                    })
                                    ->where('is_deleted',0)
                                    ->where('status', 1)
                                    ->orderBy('custom_tag')
                                    ->get();                     
        
        return view('frontend.task.list', compact('tasks', 'add_on_users','get_task_creators_filter'), $data);
    }
    // public function hofContact(Request $request){
    //     $contactId = $request->input('contactId');
    //     $family_member = DB::table('contact_family_member')
    //                     ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
    //                     ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
    //                     ->where('contact_family_member.contact_id', $contactId)
    //                     ->where('contact_family_member.family_member_id', '!=', $contactId)
    //                     ->where('contact_family_member.hof', '1')->get()->toArray();
    //     print_r($family_member);die;                

    // }
    public function create(Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $cntctId = base64_decode($request->contact);
        $data['contact_detail']=''; 
        $data['cntctId']=''; 
        $data['previousURL'] = $request->headers->get('referer');     
        if(!empty($cntctId)){
            $data['cntctId'] = $cntctId;
            $data['contact_detail'] = Contact::join('users', 'contacts.created_by', '=', 'users.id')->select('contacts.id', 'contacts.first_name', 'contacts.last_name','contacts.profile_pic','users.name as creator_name')->where('contacts.id', $cntctId)
            ->get();        
        }  
        $data['page_title'] = "Create Task";
        $data['is_backBtn'] = 1;
        $data['task_status'] = TaskStatus::where('is_deleted',0)->where('status',1)->where('id','!=',2)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        return view('frontend.task.create', $data);
    }
    public function searchContact(Request $request)
    {
        $created_by = auth()->user()->id;
        $contactData = Contact::select("id", DB::raw("CONCAT(first_name, ' ', last_name) as value"))
                      ->where('created_by', $created_by)
                      ->where(function ($query) use ($request) {
                        $query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',   $request->search . '%');
                        })
                      ->get();            
    
        return response()->json($contactData);
    }
    public function saveTask(Request $request)
    {
        
        $created_by = auth()->user()->id;
        $previousURL = $request->previousURL;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $randomAlphabet = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        $uniqueNumber = round(microtime(true))+rand(1000,9999);
        $task_unique_id= $randomAlphabet.$uniqueNumber;
             if($userDetails->added_by ==0){
                $mainUserId=$created_by;
             }else{
                $mainUserId=$userDetails->added_by;
             }
             if($userDetails->role_id ==1){
                $leaderId = $created_by;
             }else{
                $groupId = $userDetails->group_id;
                $leaderData= User::where('status',1)->where('is_deleted',0)->where('group_id',$groupId)->where('role_id',1)->first();
                if(!empty($leaderData)){
                    $leaderId = $leaderData->id;
                }else{
                    $leaderId = $mainUserId;
                }

             }
       
            $deadline = $request->input('deadline');        
            $task = new Task;
            if($request->input('contact_id') !=''){
                $lead_arr=explode("-",$request->input('contact_id'));
                if($lead_arr[0] =='lead'){
                    $task->lead_id = $lead_arr[1];
                }else{
                    $task->contact_id = $lead_arr[1];
                }
            } else {
                return response()->json(['message' => 'Contact name is required', 'status' => 'failed']);
            }
            
            $task->tittle = $request->input('title');
            $date = str_replace('/', '-', $deadline);
            if(!empty($deadline)){
                $task->deadline = date('Y-m-d', strtotime($date));
            }        
            $task->assignee_id = $request->input('assignee_id');
            if(auth()->user()->role_id ==2 &&  $request->input('assignee_id') !=''){
                $roleDetails = User::findOrFail($request->input('assignee_id'));
                if($roleDetails->role_id ==1){
                    $msg = 'You can not assign leader';
                    $status = 'error';
                    return response()->json(['message' => $msg, 'status' => $status]);
                }
            }
            $task->assigned_by = $created_by;
            $task->priority = $request->input('priority');
            $task->notes = $request->input('notes');
            $task->is_bookmark = $request->input('is_bookmark');
            $task->created_by = $created_by;
            $task->main_user_id  =$mainUserId;
            $task->leader_id  =$leaderId;
            $task->task_unique_id = $task_unique_id; 
            $task->status  =$request->input('status');
            $task->save();
            $taskID = $task->id;
            // add tags 
            $tags = $request->input('tag');
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $tagData = array(
                        'task_id' => $task->id,
                        'task_tag_id' => $tag,
                        'created_by' => $created_by
                    );
                    TaskTag::insert($tagData);
                }
            }
            // Add assigneee in task_assignee table 
            $taskassignee = new Taskassignee;
            $taskassignee->task_id= $task->id;
            $taskassignee->assignee_id= ($task->assignee_id==null)? 0 :$task->assignee_id ;
            $taskassignee->assigned_by= $created_by;
            $taskassignee->created_by= $created_by;
            $taskassignee->save();
            $assigneeName = $task->assignee_id ?? null;
            if ($task->assignee_id) {
                $assigne = User::where('id', $task->assignee_id)->first();
                if ($assigne) {
                    $assigneeName = $assigne->name;
                    $this->notifyAssignTask($task);
                }
            }

            //Send creation mail to assigned user 
            //$this->notifyTaskCreation($task);               
            // $this->notifyAssignTask($task);
            if($request->input('contact_id') ==''){
                // $activityhtml = "<p>A Task has been created by <span class='blue-bld-txt'>".$user_name."</span></p>";
            }else{
                $contact_id = base64_encode($request->input('contact_id'));
                $contact_arr=explode("-",$request->input('contact_id'));
                if($contact_arr[0] =='lead'){
                    $contactDetails = Lead::findOrFail($contact_arr[1]);
                    $contact_id =base64_encode($contactDetails->id);
                    // $contact_name =$contactDetails->first_name .' '.$contactDetails->last_name;
                    // $activityhtml = '<p>A Task has been created by <span class="blue-bld-txt">'.$user_name.'</span> for</p> <a href="'.url('lead-detail/'.$contact_id).'">'.$contact_name.'</a>';
                }else{
                    $contactDetails = Contact::findOrFail($contact_arr[1]);
                    $contact_id =base64_encode($contactDetails->id);
                    $contact_name =$contactDetails->first_name .' '.$contactDetails->last_name;
                    // $activityhtml = '<p>A Task has been created by <span class="blue-bld-txt">'.$user_name.'</span> for</p> <a href="'.url('contact-view/'.$contact_id).'">'.$contact_name.'</a>';
                }
            }
            
            
            $activityhtml = Helper::createTaskMsg( $user_name, $task, $assigneeName);

            $activity = new ActivityLogs;
            $activity->acitivity_description =$activityhtml;
            $activity->main_user_id =$mainUserId;
            $activity->module = 3;
            $activity->task_id = $taskID;
            $activity->task_module_case = 1;
            $activity->user_id = $created_by;
            $activity->save();
            $msg = 'Task saved successfully!!';
            $status = 'success';
      
        return response()->json(['message' => $msg, 'status' => $status,'previousURL'=>$previousURL]);
    }
    public function taskEdit($id = null,  Request $request)
    {
        $user_id = auth()->user()->id;
        // if (session()->has('task_srchTxt')) {
        //     $searchKey = session('task_srchTxt');
        //     //Set task_sess_srch_Txt to redirect to task list page with search filter
        //     session(['task_sess_srch_Txt' =>  $searchKey]);
        // }
        
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
            $data['assign_task']= array(29);
        }else{
            $created_by =$userDetails->added_by;
            $data['assign_task']= \App\Models\RolePermission::where(['module_functionality_id' => 29, 'role_id' => $userDetails->role_id,'main_user_id' =>$userDetails->added_by, 'status' => 'A', 'is_deleted' => 0])->pluck('module_functionality_id')->toArray();
            
        }
        $task_id = base64_decode($id);
        $task = Task::where('id', $task_id)->get();
        $conversation = TaskPoke::query()
                        ->leftjoin('users', 'users.id', '=', 'task_pokes.poke_from_id')
                        ->select('task_pokes.*','users.name as sender_name','users.profile_image','users.id as user_id')
                        ->where('task_pokes.task_id', $task_id)
                        ->where(function($query) use ($user_id) {
                            $query->where('task_pokes.poke_from_id', $user_id)
                                  ->orWhere('task_pokes.poke_to_id', $user_id);
                        })
                        ->get();
        if($task[0]->created_by == $user_id){
            $assignTrail = Taskassignee::query()
                            ->leftjoin('users', 'users.id', '=', 'task_assignees.assigned_by')
                            ->leftJoin('users as assignee', 'assignee.id', '=', 'task_assignees.assignee_id')
                            ->leftJoin('users as oldassignee', 'oldassignee.id', '=', 'task_assignees.old_assignee_id')
                            ->select('task_assignees.*','users.name as assigneer_name','users.profile_image','assignee.name as assignee_name','oldassignee.name as old_assignee_name','assignee.profile_image as assignee_image','oldassignee.profile_image as old_assignee_image')
                            ->where('task_assignees.task_id', $task_id)
                            ->get(); 

        } else{
            $assignTrail = Taskassignee::query()
                            ->leftjoin('users', 'users.id', '=', 'task_assignees.assigned_by')
                            ->leftJoin('users as assignee', 'assignee.id', '=', 'task_assignees.assignee_id')
                            ->leftJoin('users as oldassignee', 'oldassignee.id', '=', 'task_assignees.old_assignee_id')
                            ->select('task_assignees.*','users.name as assigneer_name','users.profile_image','assignee.name as assignee_name','oldassignee.name as old_assignee_name','assignee.profile_image as assignee_image','oldassignee.profile_image as old_assignee_image')
                            ->where('task_assignees.task_id', $task_id)
                            ->where(function($query) use ($user_id) {
                                $query->where('task_assignees.assignee_id', $user_id)
                                    ->orWhere('task_assignees.assigned_by', $user_id);
                            })
                            ->get(); 

        }               
                       
        $data['page_title'] = "Edit Task";
        $data['previousURL'] = $request->headers->get('referer');
        $data['is_backBtn'] = 1;
        $data['task_status'] = TaskStatus::where('is_deleted',0)->where('status',1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['task_tags'] = TaskTag::query()->join('tag_for_tasks', 'task_tags.task_tag_id', '=', 'tag_for_tasks.id' )
                            ->select('task_tags.*', 'tag_for_tasks.custom_tag')
                            ->where('tag_for_tasks.is_deleted', 0)
                            ->where('task_tags.task_id', $task_id)
                            ->get();
        // return view('frontend.task.edit-task')->with('task', $task);
        return view('frontend.task.edit-task',compact('task','conversation','assignTrail'), $data);
    }
    public function updateTask(Request $request)
    {
        // echo $request->input('contact_id');die;
        // echo $request->input('assignee_id');die;
        if($request->input('contact_id') ==''){
            if($request->input('contact_id') ==''){
                if ($request->pre_contact_id == '') {
                    return response()->json(['message' => 'Contact name is required', 'status' => 'failed']);
                }
            }
        }
        
        $created_by = auth()->user()->id;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $task_module_case = 0;
             if($userDetails->added_by ==0){
                $mainUserId=$created_by;
             }else{
                $mainUserId=$userDetails->added_by;
             }
        $task_id = $request->input('task_id');
        
            $deadline = $request->input('deadline');
            // $task_status = $request->input('status');
            $oldtask = Task::find($task_id);
            $task = Task::find($task_id);
            $oldTask = json_decode(json_encode($task));
            $oldDate = $oldTask->deadline!= null ? date('d/m/Y', strtotime($oldTask->deadline)) : null;
            $contactStatus = 0;
            if($task->created_by != $created_by){
                $priority =$task->priority;
            }else{
                $priority =$request->input('priority');
            }
            if($request->input('status')==2 && $task->created_by != $created_by){
                // $completehtml ="A Task($task->tittle) has been completed by $user_name.This task is under review.";
                $user = auth()->user();
                $date = date("d-m-Y");
                $time = date("H:i");
                $timePeriod = date("a");
                if(!empty($task) && $task->contact_id!=''){
                    $contact = Contact::Select('first_name','last_name')->where('id',$task->contact_id)->first();
                    if ($contact) {
                        $contactName = $contact->first_name.' '.$contact->last_name;
                        $contct_msg= 'for ' .$contactName;
                    } else {
                        $contct_msg= '';    
                    }
                }
                else{
                    $contct_msg= '';
                }
                // dd($task);
                $completehtml = "$user->name has marked a task assigned by you, titled $task->tittle for $contct_msg  as ‘Completed’ on ".date('d-m-Y h:i:A');
                $notification = new Notification;
                $notification->title =$completehtml;
                $notification->module_type = 1;
                $notification->is_requested = 1;
                $notification->module_id = $task->id;
                $notification->user_id = $task->created_by;
                $notification->created_by = $created_by;
                $notification->save();
                $task->is_under_review = 1;
                $task->save();

                $this->reviewLog($completehtml, $mainUserId, $task->id, $created_by);
                $last_update= $oldtask->updated_at->format('Y-m-d H:i:s');
                $current_update= $task->updated_at->format('Y-m-d H:i:s');
                // if($task->is_under_review!=1){
                //     $this->notifyModificationTask($oldtask, $task);
                // }
                
                if($last_update!=$current_update) {
                    $this->notifyModificationTask($oldtask, $task);
                }
                $msg = 'This task is under review';
                $status = 'success';
                return response()->json(['message' => $msg, 'status' => $status]);

            }
            
            if($request->input('contact_id') !=''){
                $lead_arr=explode("-",$request->input('contact_id'));
                if($lead_arr[0] =='lead'){
                    if ($lead_arr[1] == $oldTask->lead_id) {
                        $contactStatus = 1;
                    }
                    $task->lead_id = $lead_arr[1];
                    $task->contact_id = null;
                }else{
                    if ($lead_arr[1] == $oldTask->contact_id) {
                        $contactStatus = 1;
                    }
                    $task->contact_id = $lead_arr[1];
                    $task->lead_id =0;
                }
            }
            // $task->contact_id = $request->input('contact_id');
            if(!empty($deadline)){
                $date = str_replace('/', '-', $deadline);
                $task->deadline = date("Y-m-d", strtotime($date));
            }
            
            if($task->assigned_by ==$request->input('assignee_id') && $request->input('assignee_id') !=$created_by && $request->input('assignee_id') !=''){
                // if($task->assigned_by ==$request->input('assignee_id') && $request->input('assignee_id') !=$created_by && $request->input('assignee_id') !=''){
                $msg = 'You can not reassign this user';
                $status = 'error';
                return response()->json(['message' => $msg, 'status' => $status]);
            }
            if(($request->input('assignee_id') != $task->assignee_id) && ($task->assignee_id !=NULL) && $request->input('assignee_id') !=''){
                $task->assigned_by = $created_by;
            }
            
            if(auth()->user()->role_id ==2 && $request->input('assignee_id') !=''){
                $roleDetails = User::findOrFail($request->input('assignee_id'));
                if($roleDetails->role_id ==1){
                    $msg = 'You can not assign leader';
                    $status = 'error';
                    return response()->json(['message' => $msg, 'status' => $status]);
                }
            }
            if($request->input('status')==2 ){
                $task->is_under_review = 0;
            }
            // echo $request->input('priority');die;
            $task->priority = $priority;
            $task->tittle = $request->input('title');
            // $task->status = $task_status;
            $task->notes = $request->input('notes');
            $task->status  =$request->input('status');
            $task->updated_by = $created_by;
            $task->assignee_id = $request->input('assignee_id');//(!empty($request->input('assignee_id')))? $request->input('assignee_id') : $task->assignee_id ;
            $task->save();         
            $task->assignee_id = (!empty($request->input('assignee_id')))? $request->input('assignee_id') : $task->assignee_id ;
            $task->save();
            TaskTag::where('task_id', $task_id)->delete();
            $tags = $request->input('tag');
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    $tagData = array(
                        'task_id' => $task_id,
                        'task_tag_id' => $tag,
                        'created_by' => $created_by
                    );
                    TaskTag::insert($tagData);
                }
            }         
            //task create based on workflow step
            $workflow_step_id = $request->input('workflow_step_id');
            if(!empty($workflow_step_id) && $request->input('status')==2){
                $step_arr = [];
                $step_detail= DB::table('workflow_steps')
                ->leftjoin('workflow_templates', 'workflow_templates.id', '=', 'workflow_steps.workflow_template_id')
                ->leftjoin('workflows', 'workflows.id', '=', 'workflow_steps.workflow_id')
                ->select('workflow_steps.*', 'workflow_templates.sequential', 'workflows.contact_id as workflow_contact_id','workflows.lead_id as workflow_lead_id')
                ->where('workflow_steps.id', $workflow_step_id)
                ->get();

                $step_list= DB::table('workflow_steps')->where('workflow_id', $step_detail[0]->workflow_id )->get();
                $is_sequence= $step_detail[0]->sequential;
                
                $contact_id= (!empty($step_detail[0]->workflow_contact_id))? $step_detail[0]->workflow_contact_id: NULL;

                $lead_id=(!empty($step_detail[0]->workflow_lead_id)) ? $step_detail[0]->workflow_lead_id :NULL;

                foreach ($step_list as $step) {            
                    $step_arr[] = $step->id;
                }
                $key = array_search($workflow_step_id, $step_arr);
                $next_step_id = '';
                // Check if there's a next value in the array
                if ($key !== false && isset($step_arr[$key + 1])) {
                    $next_step_id = $step_arr[$key + 1];
                }
                if ($is_sequence == 'Y' && $next_step_id != '') {             
                    $next_step = WorkflowStep::find($next_step_id);

                    $created_by = auth()->user()->id;
                    $userDetails = User::findOrFail($created_by);
                    if ($userDetails->added_by == 0) {
                        $mainUserId = $created_by;
                    } else {
                        $mainUserId = $userDetails->added_by;
                    }
                    $deadline = $next_step->deadline_date;
                    $date = str_replace('/', '-', $deadline);
                    if ($next_step->step_category == '1') {                   
                        $task = new Task;
                    // $task->contact_id = $next_step->contact_id;
                        $task->contact_id= $contact_id;
                        $task->lead_id= $lead_id;
                        $task->workflow_step_id = $next_step->id;
                        $task->tittle = $next_step->step_name;
                        if (!empty($deadline)) {
                            $task->deadline = date('Y-m-d', strtotime($date));
                        }
                        $task->status = 3 ;// Default status in progress
                        $task->assignee_id = $next_step->assignee_id;
                        $task->priority = $next_step->priority;
                        $task->notes = $next_step->description;
                        $task->created_by = $created_by;
                        $task->main_user_id  = $mainUserId;
                        $task->save();
                    }
                }

                //For Hybrid
                if ($is_sequence == 'H') {
                    $based_on_step = WorkflowStep::where('based_on_step_id', $workflow_step_id)->get();
                    $created_by = auth()->user()->id;
                    $userDetails = User::findOrFail($created_by);
                    if ($userDetails->added_by == 0) {
                        $mainUserId = $created_by;
                    } else {
                        $mainUserId = $userDetails->added_by;
                    }
                    foreach ($based_on_step as $next_step) {
                        $deadline = $next_step->deadline_date;
                        $date = str_replace('/', '-', $deadline);

                        if ($next_step->step_category == '1') {
                            $task = new Task;
                        // $task->contact_id = $next_step->contact_id;
                            $task->contact_id= $contact_id;
                            $task->workflow_step_id = $next_step->id;
                            $task->tittle = $next_step->step_name;
                            if (!empty($deadline)) {
                                $task->deadline = date('Y-m-d', strtotime($date));
                            }
                            $task->status = 3 ;// Default status in progress
                            $task->assignee_id = $next_step->assignee_id;
                            $task->priority = $next_step->priority;
                            $task->notes = $next_step->description;
                            $task->created_by = $created_by;
                            $task->main_user_id  = $mainUserId;
                            $task->save();
                        }
                    }
                }
                $stepdetails = WorkflowStep::findOrFail($workflow_step_id);
                $stepdetails->is_completed= '1';
                $stepdetails->save();
            }

            //End task creation step

            if($request->input('contact_id') ==''){
                // $activityhtml = "<p>A Task has been updated by <span class='blue-bld-txt'>".$user_name."</span></p>";
            }else{
                $contact_id = base64_encode($request->input('contact_id'));
                $contact_arr=explode("-",$request->input('contact_id'));
                if($contact_arr[0] =='lead'){
                    $contactDetails = Lead::findOrFail($contact_arr[1]);
                    $contact_id =base64_encode($contactDetails->id);
                    $contact_name =$contactDetails->first_name .' '.$contactDetails->last_name;
                    // $activityhtml = '<p>A Task has been updated by <span class="blue-bld-txt">'.$user_name.'</span> for</p> <a href="'.url('lead-detail/'.$contact_id).'">'.$contact_name.'</a>';
                }else{
                    $contactDetails = Contact::findOrFail($contact_arr[1]);
                    $contact_id =base64_encode($contactDetails->id);
                    $contact_name =$contactDetails->first_name .' '.$contactDetails->last_name;
                    // $activityhtml = '<p>A Task has been updated by <span class="blue-bld-txt">'.$user_name.'</span> for</p> <a href="'.url('contact-view/'.$contact_id).'">'.$contact_name.'</a>';
                }     

            }
            $activityhtml = "";
            // check only assignee_id change or not
            if ($request->title == $oldTask->tittle && ($request->contact_id == null || 
            $contactStatus == 0) && $task->priority == $oldTask->priority && 
            $request->assignee_id != null && $request->assignee_id != $oldTask->assignee_id
            && $request->notes == $oldTask->notes && $request->deadline == $oldDate  
            ) {
                
                if ($oldTask->assignee_id == null) {
                    $assigneDetails = User::findOrFail($request->assignee_id);
                    $task_module_case = 9;
                    $activityhtml = Helper::assignedMsg( $user_name, $task_id, null, $assigneDetails->name, $task);
                } else {
                    if ($oldTask->assignee_id == $userDetails->id) {
                        // means Assigned User change/assign another user
                        $task_module_case = 2;
                        $assigneDetails1 = User::findOrFail($oldTask->assignee_id);
                        $assigneDetails2 = User::findOrFail($request->assignee_id);
                        $activityhtml = Helper::assignedUserAssignAnotherUserMsg( $user_name, $task_id, $assigneDetails1->name, $assigneDetails2->name );
                    } else {
                        $task_module_case = 9;
                        $assigneDetails1 = User::findOrFail($oldTask->assignee_id);
                        $assigneDetails2 = User::findOrFail($request->assignee_id);
                        $activityhtml = Helper::assignedMsg( $user_name, $task_id, $assigneDetails1->name, $assigneDetails2->name, $task);

                    }
                }
                $this->notifyAssignTask($task);
                
            }
            //  check only contact_id change or not
            elseif ($request->title == $oldTask->tittle && 
            ($request->contact_id != null && $contactStatus == 0) && 
            $request->priority == $oldTask->priority && 
            ($request->assignee_id == null || $request->assignee_id == $oldTask->assignee_id)
            && $request->notes == $oldTask->notes && $request->deadline == $oldDate
            ) {
                $lead_arr=explode("-",$request->input('contact_id'));
                
                if ($oldTask->lead_id) {
                    $lead = Lead::where('id', $oldTask->lead_id)->first();
                    $contact1 = $lead->first_name ." ".$lead->last_name;
                } else {
                    $contact = Contact::where('id', $oldTask->contact_id)->first();
                    $contact1 = $contact->first_name ." ".$contact->last_name;
                }
                
                if($lead_arr[0] == 'lead'){
                   $lead = Lead::where('id', $lead_arr[1])->first();
                   $contact2 = $lead->first_name ." ".$lead->last_name;

                } else {
                    $contact = Contact::where('id', $lead_arr[1])->first();
                    $contact2 = $contact->first_name ." ".$contact->last_name;
                }
                $activityhtml = Helper::contactTaskMsg($user_name, $task, $contact1, $contact2 );

                $task_module_case = 4;
                
            } else {
                $html = "";
                if ($request->title != $oldTask->tittle) {
                    $html .= "<li>$oldTask->tittle -> $request->title </li>";
                }
                if ($request->contact_id != null && $contactStatus == 0) {
                    $lead_arr=explode("-",$request->input('contact_id'));
                
                    if ($oldTask->lead_id) {
                        $lead = Lead::where('id', $oldTask->lead_id)->first();
                        if ($lead) {
                            $contact1 = $lead->first_name ." ".$lead->last_name;
                        } else {
                            $contact1 = '';
                        }
                    } else {
                        $contact = Contact::where('id', $oldTask->contact_id)->first();
                        if ($contact) {
                            $contact1 = $contact->first_name ." ".$contact->last_name;
                        } else {
                            $contact1 = '';
                        }
                    }
                    
                    if($lead_arr[0] == 'lead'){
                        $lead = Lead::where('id', $lead_arr[1])->first();
                        if ($lead) {
                            $contact2 = $lead->first_name ." ".$lead->last_name;
                        } else {
                            $contact2 = '';
                        }
                    } else {
                        $contact = Contact::where('id', $lead_arr[1])->first();
                        if ($contact) {
                            $contact2 = $contact->first_name ." ".$contact->last_name;
                        } else {
                            $contact2 = '';
                        }
                    }
                    $html .= "<li> $contact1 -> $contact2 </li>";
                }
                if ($task->priority != $oldTask->priority) {
                    $value1 = Helper::getTaskValue($oldTask->priority);
                    $value2 = Helper::getTaskValue($request->priority);
                    $html .= "<li> $value1 -> $value2 </li>";
                }
                $new_date = $request->deadline ? Carbon::createFromFormat('d/m/Y', $request->deadline)->format('Y-m-d') : null;
                if ($new_date != $oldTask->deadline) {
                    $html .= "<li> $oldTask->deadline -> $new_date </li>";
                }
                if ($request->status != $oldTask->status) {
                    $status1 = DB::table('task_status')->where('id', $oldTask->status)->first();
                    $status2 = DB::table('task_status')->where('id', $request->status)->first();
                    $html .= "<li>$status1->name -> $status2->name </li>";
                }
                if ($request->notes != $oldTask->notes) {
                    $html .= "<li> $oldTask->notes -> $request->notes </li>";
                }
                
                if ($html != '') {
                    $activityhtml = helper::updateTaskMsg($user_name, $task_id, $html, $task);
                    $task_module_case = 13;
                }
                $this->notifyModificationTask($oldtask, $task);
            }
    
            if ($activityhtml != '') {
                $activity = new ActivityLogs;
                $activity->acitivity_description =$activityhtml;
                $activity->main_user_id =$mainUserId;
                $activity->module = 3;
                $activity->task_id = $task_id;
                $activity->task_module_case = $task_module_case;
                $activity->user_id = $created_by;
                $activity->save();
            }

            //Notify about task modification  to assigned user 
            $last_update= $oldtask->updated_at->format('Y-m-d H:i:s');
            $current_update= $task->updated_at->format('Y-m-d H:i:s');           
            // if($last_update!=$current_update){                
            //     if ($userDetails->added_by != 0 && ($request->assignee_id != null && $request->assignee_id != $oldTask->assignee_id)) {
            //         $this->notifyAssignTask($task);
            //     } else {
            //         $this->notifyModificationTask($oldtask, $task);
            //     }
                
            // }
           // task assignee update in assignee table 
           if($request->assignee_id != null && $request->assignee_id != $oldTask->assignee_id){
                $taskassignee = new Taskassignee;
                $taskassignee->task_id= $task_id;
                $taskassignee->assignee_id= ($request->assignee_id==null)? 0 :$request->assignee_id ;
                $taskassignee->assigned_by= $created_by;
                $taskassignee->old_assignee_id= ($oldTask->assignee_id==null)?0 : $oldTask->assignee_id;
                $taskassignee->created_by= $created_by;
                $taskassignee->save();

           }
           if($request->assignee_id == null && $request->assignee_id ==''  && $oldTask->assignee_id !=null && $oldTask->assignee_id !=0){
                $taskassignee = new Taskassignee;
                $taskassignee->task_id= $task_id;
                $taskassignee->assignee_id= 0 ;
                $taskassignee->assigned_by= $created_by;
                $taskassignee->old_assignee_id= $oldTask->assignee_id;
                $taskassignee->created_by= $created_by;
                $taskassignee->save();
           }

           if($request->input('status')==2 && $task->created_by == auth()->user()->id){

                $notification = Notification::where('module_id',$task->id)->where('module_type',1)->where('is_read',0)->first();
                if (!empty($notification)) {
                    $notificationId = $notification->id;
                    Notification::where('id', $notificationId)->update([
                        'is_read' => 1,
                    ]);
                }
            }

            $request->session()->flash('message', 'Task updated successfully');
            $msg = 'Success';
            $status = 'success';
      

        return Response::json(['message' => $msg,'status' => $status]);
    }

    public function reviewLog($activityhtml, $mainUserId, $task_id, $created_by)
    {
        $user = auth()->user();
        $task = Task::find($task_id);
        
        if($task->contact_id!=''){
            $ContactDetail = Contact::Select('first_name','last_name')->where('id',$task->contact_id)->first();
            if ($ContactDetail) {
                $contactName = $ContactDetail->first_name.' '.$ContactDetail->last_name;
                $contct_msg= 'for ' .$contactName;
            } else {
                $contct_msg= '';    
            }
        }
        // check if the user is addon user
        if ($user->added_by != 0) {
            $activityhtml = " <span class='blue-bld-txt'>$user->name</span> has marked a task assigned #DYNAMIC_NAME#, titled $task->tittle $contct_msg as ‘Completed’.";
        } 
        $activity = new ActivityLogs;
        $activity->acitivity_description =$activityhtml;
        $activity->main_user_id =$mainUserId;
        $activity->module = 3;
        $activity->task_id = $task_id;
        $activity->task_module_case = 15; // for addon user comnplete task. that under review. this is not in MSO CRM Phase II. It add later
        $activity->user_id = $created_by;
        $activity->save();
    }

    public function assignTask(Request $request)
    {
        $assigned_by = auth()->user()->id;
        $userDetails = User::findOrFail($assigned_by);
                if ($userDetails->added_by == 0) {
                    $mainUserId = $assigned_by;
                } else {
                    $mainUserId = $userDetails->added_by;
                }
        $task_id = $request->input('task_id');
        $oldtask = Task::find($task_id);
        $assignee_id = $request->input('assignee_id');
        if(auth()->user()->role_id ==2){
            $userDetails = User::findOrFail($assignee_id);
            if($userDetails->role_id ==1){
                $msg = 'You can not asssign leader';
                $status = 'error';
                return response()->json(['message' => $msg, 'status' => $status]);
            }
        }
        
        $task = Task::find($task_id);
        if($task->assigned_by ==$assignee_id && $request->input('assignee_id') !=''){
            $msg = 'You can not reassigned this user';
            $status = 'error';
        }else{
            $task->assignee_id = $assignee_id;
            $task->assigned_by = $assigned_by;
            $task->save();
            $assigneDetails = User::findOrFail($assignee_id);
            
            //$statusDetail =TaskStatus::findOrFail($task->status);
            $assigneeName =$assigneDetails->name;
            $assigneeEmail =$assigneDetails->email;

            //Send mail to assigned user
            $this->notifyAssignTask($task);  
            
            
           // $activityhtml = "<p>A Task has been assigned  to ".$assigneeName."  </p>";
            /*$userData['email'] = $assigneeEmail;
            $userData['name'] = $assigneeName;
            $userData['followUp'] = $task->deadline;
            $userData['taskName'] = $task->tittle;
            $userData['createdBy'] = $userDetails->name;
            $userData['status'] = $statusDetail->name;
            $url = '<a href="' . \URL::route('user.task') . '">'.$request->input('title').'</a>';
            $userData['urlParam']   = $url;
            Mail::send('email_template.assignee_task_email', ['user' => $userData], function ($m) use ($userData) {
                $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                $m->to($userData['email'], $userData['name'])->subject("M-Edge");
            });*/

            $activityhtml = Helper::assignedMsg(auth()->user()->name, $task_id, $assigneeName,'', $task);

            $activity = new ActivityLogs;
            $activity->acitivity_description =$activityhtml;
            $activity->main_user_id =$mainUserId;
            $activity->user_id = $assigned_by;
            $activity->task_module_case = 9;
            $activity->task_id = $task_id;
            $activity->save(); 
            if($assignee_id != null){
                $taskassignee = new Taskassignee;
                $taskassignee->task_id= $task_id;
                $taskassignee->assignee_id= ($request->assignee_id==null)? 0 :$request->assignee_id ;
                $taskassignee->assigned_by= $assigned_by;
                $taskassignee->old_assignee_id= ($oldtask->assignee_id==null)?0 : $oldtask->assignee_id;
                $taskassignee->created_by= $assigned_by;
                $taskassignee->save();

           }        

            $msg = 'Task assigned successfully';
            $status = 'success';

        }
        
        return response()->json(['message' => $msg, 'status' => $status]);
        // return response()->json(['message' => $msg]);
    }
    public function bookmarkTask(Request $request)
    {
        $task_id = $request->input('task_id');
        $is_bookmark = $request->input('is_bookmark');
        $task = Task::find($task_id);
        $task->is_bookmark = $is_bookmark;
        $task->save();
        if ($is_bookmark == 1) {
            $msg = 'Task bookmarked successfully';
        } else {
            $msg = 'Bookmark removed successfully';
        }
        return response()->json(['message' => $msg]);
    }
    public function updateTaskPosition(Request $request)
    {
        $order = $request->input('order');
        $page= $request->input('page');
    
        foreach ($order as $index => $itemId) {
            $item = Task::find($itemId);
            $item->row_order = (($page-1)*10)+ ($index + 1); 
            $item->save();
        }
        $msg = 'Order updated successfully';    
        return response()->json(['message' => $msg]);
    }
    public function completeTask(Request $request)
    {
        $taskArray = $request->input('taskArray');
        $status = $request->input('status');
        $created_by = auth()->user()->id;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $notificationSave ='';
        if($request->input('status')==2 ){
            for ($i = 0; $i < count($taskArray); $i++) {
                $task = Task::find($taskArray[$i]);
                $oldTask = Task::find($taskArray[$i]);
                if($task->created_by != $created_by){
                    // $completehtml = "A Task($task->tittle) has been completed by $user_name.This task is under review.";

                    $user = auth()->user();
                    $date = date("d-m-Y");
                    $time = date("H:i");
                    $timePeriod = date("a");

                    if(!empty($task) && $task->contact_id!=''){
                        $ContactDetail = Contact::Select('first_name','last_name')->where('id',$task->contact_id)->first();
                        if ($ContactDetail) {
                            $contactName = $ContactDetail->first_name.' '.$ContactDetail->last_name;
                            $contct_msg= 'for ' .$contactName;
                        } else {
                            $contct_msg= '';    
                        }
                    }
                    else{
                        $contct_msg= '';
                    }

                    $completehtml = "$user_name has marked a task assigned by you, titled $task->tittle $contct_msg as ‘Completed’ on ".date('d-m-Y h:i:A');
                    $notification = new Notification;
                    $notification->title =$completehtml;
                    $notification->module_type = 1;
                    $notification->module_id = $task->id;
                    $notification->is_requested = 1;
                    $notification->user_id = $task->created_by;
                    $notification->created_by = $created_by;
                    $notificationSave=$notification->save();
                    $task->is_under_review = 1;
                    $task->save();

                    if($userDetails->added_by ==0){
                        $mainUserId=$created_by;
                     }else{
                        $mainUserId=$userDetails->added_by;
                     }

                    $this->reviewLog($completehtml, $mainUserId, $task->id, $created_by);
                    $last_update= $oldTask->updated_at->format('Y-m-d H:i');
                    $current_update= $task->updated_at->format('Y-m-d H:i');
                    // if($last_update!=$current_update){
                    if($task->is_under_review != $oldTask->is_under_review ) {
                        $this->notifyModificationTask($oldTask, $task);
                    }
                }
                
            }
              if($notificationSave){
                $msg = 'This task is under review';
                return response()->json(['message' => $msg]);
              }
                
        }
        //task create based on workflow step
        $workflow_step_id = $request->input('workflow_step_id');
        if(!empty($workflow_step_id) && $status==2){
          $step_arr = [];
          $step_detail= DB::table('workflow_steps')
          ->leftjoin('workflow_templates', 'workflow_templates.id', '=', 'workflow_steps.workflow_template_id')
          ->leftjoin('workflows', 'workflows.id', '=', 'workflow_steps.workflow_id')
          ->select('workflow_steps.*', 'workflow_templates.sequential', 'workflows.contact_id as workflow_contact_id','workflows.lead_id as workflow_lead_id')
          ->where('workflow_steps.id', $workflow_step_id)
          ->get();

          $step_list= DB::table('workflow_steps')->where('workflow_id', $step_detail[0]->workflow_id )->get();
          $is_sequence= $step_detail[0]->sequential;
        
          $contact_id= (!empty($step_detail[0]->workflow_contact_id))? $step_detail[0]->workflow_contact_id: NULL;

          $lead_id=(!empty($step_detail[0]->workflow_lead_id)) ? $step_detail[0]->workflow_lead_id :NULL;


            foreach ($step_list as $step) {            
                $step_arr[] = $step->id;
            }
            $key = array_search($workflow_step_id, $step_arr);
            $next_step_id = '';
            // Check if there's a next value in the array
            if ($key !== false && isset($step_arr[$key + 1])) {
                $next_step_id = $step_arr[$key + 1];
            }
            if ($is_sequence == 'Y' && $next_step_id != '') {             
                $next_step = WorkflowStep::find($next_step_id);

                $created_by = auth()->user()->id;
                $userDetails = User::findOrFail($created_by);
                if ($userDetails->added_by == 0) {
                    $mainUserId = $created_by;
                } else {
                    $mainUserId = $userDetails->added_by;
                }
                $deadline = $next_step->deadline_date;
                $date = str_replace('/', '-', $deadline);
                if ($next_step->step_category == '1') {                   
                    $task = new Task;
                // $task->contact_id = $next_step->contact_id;
                    $task->contact_id= $contact_id;
                    $task->lead_id= $lead_id;
                    $task->workflow_step_id = $next_step->id;
                    $task->tittle = $next_step->step_name;
                    if (!empty($deadline)) {
                        $task->deadline = date('Y-m-d', strtotime($date));
                    }
                    $task->status = 3 ;// Default status in progress
                    $task->assignee_id = $next_step->assignee_id;
                    $task->priority = $next_step->priority;
                    $task->notes = $next_step->description;
                    $task->created_by = $created_by;
                    $task->main_user_id  = $mainUserId;
                    $task->save();
                }
            }

            //For Hybrid
            if ($is_sequence == 'H') {
                $based_on_step = WorkflowStep::where('based_on_step_id', $workflow_step_id)->get();
                $created_by = auth()->user()->id;
                $userDetails = User::findOrFail($created_by);
                if ($userDetails->added_by == 0) {
                    $mainUserId = $created_by;
                } else {
                    $mainUserId = $userDetails->added_by;
                }
                foreach ($based_on_step as $next_step) {
                    $deadline = $next_step->deadline_date;
                    $date = str_replace('/', '-', $deadline);

                    if ($next_step->step_category == '1') {
                        $task = new Task;
                       // $task->contact_id = $next_step->contact_id;
                        $task->contact_id= $contact_id;
                        $task->workflow_step_id = $next_step->id;
                        $task->tittle = $next_step->step_name;
                        if (!empty($deadline)) {
                            $task->deadline = date('Y-m-d', strtotime($date));
                        }
                        $task->status = 3 ;// Default status in progress
                        $task->assignee_id = $next_step->assignee_id;
                        $task->priority = $next_step->priority;
                        $task->notes = $next_step->description;
                        $task->created_by = $created_by;
                        $task->main_user_id  = $mainUserId;
                        $task->save();
                    }
                }
            }
        }

        //End task creation step
        $created_by = auth()->user()->id;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
             if($userDetails->added_by ==0){
                $mainUserId=$created_by;
             }else{
                $mainUserId=$userDetails->added_by;
             }

        for ($i = 0; $i < count($taskArray); $i++) {
            $task = Task::find($taskArray[$i]);
            $oldTask = Task::find($taskArray[$i]);
            $task->status = $status;
            $task->is_under_review = 0;
            $task->save();
            $notification = Notification::where('module_id',$taskArray[$i])->where('module_type',1)->where('is_read',0)->first();
            if(!empty($notification)){
                $notificationId = $notification->id;
                Notification::where('id', $notificationId)->update([
                    'is_read' => 1,
                ]);
            }

            $last_update= $oldTask->updated_at->format('Y-m-d H:i');
            $current_update= $task->updated_at->format('Y-m-d H:i');
            if($last_update!=$current_update){
                $this->notifyModificationTask($oldTask, $task);
            }
        }
        if ($status == 3) {
            $step_id =  $task->workflow_step_id;
            // $activityhtml = "<p>A Task has been marked incomplete by <span class='blue-bld-txt'>".$user_name."</span></p>";
            // $activity = new ActivityLogs;
            // $activity->acitivity_description =$activityhtml;
            // $activity->main_user_id =$mainUserId;
            // $activity->user_id = $created_by;
            // $activity->save();

            $oldStatus = 2; // complete status is 2
            $this->statusUpadte($task, auth()->user(), $oldStatus, $status );

            if( $step_id != null){
             // echo "aaaa";die;
             $stepdetails = WorkflowStep::findOrFail($step_id);
             $stepdetails->is_completed= '0';
             $response =$stepdetails->save();
           
            }
            $msg = 'Task Incomplete successfully';
        } else {
            $step_id =  $task->workflow_step_id;
            $user = auth()->user();
            $this->completestatusTask($user_name, $task->id, $step_id, $user);
            // $activityhtml = "<p>A Task has been marked completed by <span class='blue-bld-txt'>".$user_name."</span></p>";
            // $activity = new ActivityLogs;
            // $activity->acitivity_description =$activityhtml;
            // $activity->main_user_id =$mainUserId;
            // $activity->user_id = $created_by;
            // $activity->save();
            if( $step_id != null){
             // echo "aaaa";die;
             $stepdetails = WorkflowStep::findOrFail($step_id);
             $stepdetails->is_completed= '1';
             $response =$stepdetails->save();
            }
            $msg = 'Task Completed successfully';
            
        }

        if($request->input('status')==2 && $task->created_by == auth()->user()->id){

            $notification = Notification::where('module_id',$task->id)->where('module_type',1)->where('is_read',0)->first();
            if(!empty($notification)){
                $notificationId = $notification->id;
                Notification::where('id', $notificationId)->update([
                    'is_read' => 1,
                ]);
            }

            $this->notifyModificationTask($oldTask, $task);
        }
        
        return response()->json(['message' => $msg]);
    }
    public function taskDelete(Request $request)
    {
        $id = ($request->id);
        $created_by = auth()->user()->id;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
             if($userDetails->added_by ==0){
                $mainUserId=$created_by;
             }else{
                $mainUserId=$userDetails->added_by;
             }
        $details = Task::findOrFail($id);
        if ($details) {
            $details->deleted_at=Carbon::now();
            $details->deleted_by=auth()->user()->id;
            $details->is_deleted=1;
            $response =$details->save();

            $activity = new ActivityLogs;
            $activity->acitivity_description = Helper::taskDeleteMsg(auth()->user()->name, $id, $details);
            $activity->main_user_id =$mainUserId;
            $activity->task_module_case = 10;
            $activity->user_id = $created_by;
            $activity->is_delete_log = 1;
            $activity->task_id = $id;
            $activity->save();
            // update $activity->is_delete_log = 1 for all activity log where task_id is present.
            ActivityLogs::where('task_id', $id)->update(['is_delete_log'=> 1]);
            // return $response;

            //Send deletion mail to assigned user
            $this->notifyDeletionTask($details);            

            return response()->json(['message'=>'Task has been deleted successfully.']);
        } 
    }
    public function instant_task_update(Request $request)
    {
        // dd($request);
        $field_name = $request->input('field_name');
        $changed_value = $request->input('changed_value');
        $id = $request->input('taskId');
        $user = auth()->user();
        $userName = auth()->user()->name;

        $task = Task::find($id);
        $oldTask = Task::find($id);
        $oldTaskTitle = $task->tittle;
        $oldDeadline = $task->deadline;

        if ($field_name == 'deadline') {

            $date = str_replace('/', '-', $changed_value);

            $new_date = Carbon::createFromFormat('d/m/Y', $changed_value)->format('Y-m-d');
            if ($oldDeadline != $new_date) {
                $activity = new ActivityLogs;
                $activity->acitivity_description = Helper::taskDeadlineMsg($userName, $task, $oldDeadline, $new_date);
                $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
                $activity->user_id = $user->id;
                $activity->task_module_case = 6;
                $activity->task_id = $id;
                $activity->save();
            }
            // echo 'old date -'.$changed_value.'<br>';

            // dd(Carbon::createFromFormat('d/m/Y', $changed_value)->format('Y-m-d'));
            // $task->deadline = Carbon::createFromFormat('d/m/Y', $changed_value)->format('Y-m-d');
            $task->deadline = $new_date;
            // $task->deadline = '2023-11-20';
            $msg = 'Date Updated successfully';
        }

        if ($field_name == 'tittle') {
            $task->tittle = $changed_value;
            $msg = 'Title Updated successfully';
            $title1 = $oldTaskTitle;
            $title2 = $changed_value;

            if ($title1 != $title2) {
                $activity = new ActivityLogs;
                $activity->acitivity_description = Helper::taskTitleMsg($userName, $id, $title1, $title2, $user, $task);
                $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
                $activity->user_id = $user->id;
                $activity->task_id = $id;
                $activity->task_module_case = 3;
                $activity->save();
            }
        }

        $task->save();

        $last_update= $oldTask->updated_at->format('Y-m-d H:i:s');
        $current_update= $task->updated_at->format('Y-m-d H:i:s');
        if($last_update!=$current_update){
            $this->notifyModificationTask($oldTask, $task);
        }
        return response()->json(['message' => $msg]);
    }

    public function searchUser(Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $contactData = User::select("id", "name as value")
            ->where(function ($query) use ($request) {
                $query->where('name', 'LIKE', '%' . $request->search . '%');
            })
            ->where('added_by', auth()->user()->id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();
        }else{
            if( $userDetails->group_id =='' ||  $userDetails->group_id ==null){
                $contactData = User::select("id", "name as value")
                ->where(function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                })
                ->where('added_by', auth()->user()->id)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->orderBy('name', 'ASC')
                ->get();

            }else{
                $contactData = User::select("id", "name as value")
                ->where(function ($query) use ($request) {
                    $query->where('name', 'LIKE', '%' . $request->search . '%');
                })
                ->whereNotNull('group_id')
                ->where('group_id', $userDetails->group_id)
                ->where('added_by','!=',0)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->orderBy('name', 'ASC')
                ->get();

            }
            
        }
        

        return response()->json($contactData);
    }


    function generateTask(Request $request)
    {
        $user_id = auth()->user()->id;
        $request->userFilter;
        $userDetails = User::findOrFail($user_id);
        // if(auth()->user()->added_by ==0){
        //         $column_name ='tasks.main_user_id';
        //     }else{
        //         if($userDetails->role_id ==1){
        //             $column_name ='tasks.leader_id';
        //         }else{
        //             $column_name ='tasks.created_by';
        //         }
        //     }
            if($request->export_type == 'all')
            {
                $tasks = Task::query()
                 ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
                 ->leftjoin('leads', 'leads.id', '=', 'tasks.lead_id')
                 ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
                 ->join('users', 'tasks.created_by', '=', 'users.id')
                 ->leftJoin('users as assignee', 'assignee.id', '=', 'tasks.assignee_id')
                ->select('tasks.*', 'contacts.first_name', 'contacts.last_name','leads.first_name as lead_first_name','leads.last_name as lead_last_name','task_status.name as task_status','users.name as creator','assignee.name as assignee_name')
                // ->where('tasks.created_by', $user_id)
                // ->where($column_name, $user_id)
                ->where(function($q) use ($request,$user_id){                  
                    $add_on_user = $request->userFilter;
                    if ($add_on_user) {
                        $explode_user = explode(',', $add_on_user);
                        $cleaned_users = array_map(function ($user) {
                            return intval(trim($user));
                        }, $explode_user);
                        $q->whereIn('tasks.created_by', $cleaned_users)
                           ->orWhere('tasks.assignee_id',$user_id);
                    }else{
                        $q->where('tasks.created_by', $user_id)
                           ->orWhere('tasks.assignee_id',$user_id);
                    }
                   
                 })
                ->where('tasks.is_deleted', 0)
                ->when($request->task_status, function ($q) use ($request) {
                    $task_status = $request->task_status;
                    if ($task_status == 'overdue') {
                        $q->whereDate('deadline', '<', now());
                        // $q->where('tasks.status', 1);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($task_status == 'upcoming') {
                        $now = Carbon::now();
                        $q->whereIn('tasks.status', [2,4],'and', true);
                        $q->whereBetween("deadline", [
                            $now->format('Y-m-d'), 
                            $now->addDays(90)->format('Y-m-d')
                        ]);
                    }
                })
                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 'overdue') {
                        $q->whereDate('deadline', '<', now());
                        $q->where('tasks.status', 1);
                    }
                    if ($filter_by == 'pending') {
                        // $q->where('tasks.status', 1);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($filter_by == 'upcoming') {
                        $now = Carbon::now();
                        $q->whereBetween("deadline", [
                            $now->format('Y-m-d'), 
                            $now->addDays(90)->format('Y-m-d')
                        ]);
                    }
                })
                ->when($request->search_term, function ($q) use ($request) {                    
                    if ($request->search_term != '') {                        
                        $search_txt = $request->search_term;
                        session(['task_srchTxt' =>  $request->search_term]); 
                        // $q->where('tasks.tittle', 'LIKE','%'.$request->search_term.'%');
                        $q->where(function ($q) use ($search_txt) {
                                // $q->where('tasks.tittle', 'LIKE',  $search_txt . '%')
                                     $q->where('contacts.first_name', 'LIKE', '%'. $search_txt . '%')
                                       ->orWhere('contacts.last_name', 'LIKE','%'. $search_txt.'%' )
                                       ->orWhere('leads.last_name', 'LIKE',  '%'. $search_txt.'%' )
                                       ->orWhere('leads.first_name', 'LIKE', '%'. $search_txt.'%' )
                                        ->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',   $search_txt . '%')
                                        ->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE',  $search_txt . '%')
                                        ->orWhere('tasks.tittle', 'LIKE', '%'. $search_txt.'%' );

                                        
                                        $txt = str_replace(" ", "%", $search_txt);
                                        $q->orWhere('tasks.tittle', 'LIKE',  '%'.$txt.'%' );
                                        $q->orWhere(DB::raw('CONCAT(contacts.first_name, " ", contacts.last_name)'), 'LIKE',  '%'. $txt. '%');
                                        $q->orWhere(DB::raw('CONCAT(leads.first_name," ",leads.last_name)'), 'LIKE', '%'. $txt. '%');

                        });
                    }
                })
                ->when($request->current_task, function ($q) use ($request) {
                    $current_task = $request->current_task;

                    if ($current_task == 'pending') {
                        // $q->where('tasks.status', 1);
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($current_task == 'completed') {
                        $q->where('tasks.status', 2);
                        // $q->where('tasks.is_bookmark','!=' ,'1');
                    }
                    if ($current_task == 'priority') {
                        $q->where('tasks.is_bookmark', '1');
                        $q->where('tasks.status','!=', 2);
                    }
                })
                ->when($request->un_assigned, function ($q) use ($request) {
                    $q->where('tasks.assignee_id','=',NULL);
                })
                ->when($request->assigneeFilter, function ($q) use ($request) {
                    $assigneeFilterArr = json_decode($request->assigneeFilter);
                    // $opp_type = $request->opp_type;
                    // $q->where('opportunities.opportunity_type', $opp_type);
                    if(!empty($assigneeFilterArr)){
                        $q->whereIn('tasks.assignee_id', array_values($assigneeFilterArr));
                    }
                    
                
                })
                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                }, function ($q) {
                    $q->orderBy('row_order', 'asc');
                    // $q->orderBy('tasks.id', 'desc');
                })
                ->latest()->get();

                
            }
            else
            {
                $tasks = Task::query()
                 ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
                 ->leftjoin('leads', 'leads.id', '=', 'tasks.lead_id')
                 ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
                 ->join('users', 'tasks.created_by', '=', 'users.id')
                 ->leftJoin('users as assignee', 'assignee.id', '=', 'tasks.assignee_id')
                ->select('tasks.*', 'contacts.first_name', 'contacts.last_name','leads.first_name as lead_first_name','leads.last_name as lead_last_name','task_status.name as task_status','users.name as creator','assignee.name as assignee_name')
                // ->where('tasks.created_by', $user_id)
                // ->where($column_name, $user_id)
                ->where('tasks.is_deleted', 0)
                ->whereIn('tasks.id', $request->task_ids)
                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                }, function ($q) {
                    $q->orderBy('row_order', 'asc');
                    // $q->orderBy('tasks.id', 'desc');
                })
                ->latest()->get();
                
            }
            // print_r($tasks);die;
            // dd($tasks);
            

               

            if($request->doc_type == 'csv')
            {
                if (sizeof($tasks) > 0) {
                    for ($i = 0; $i < sizeof($tasks); $i++) {
                        $csvRecord[$i]['task_name']       = $tasks[$i]['tittle'];

                        if($tasks[$i]['contact_id']!=NULL or $tasks[$i]['contact_id']!='' or $tasks[$i]['contact_id']!=0)
                        {
                            $thiscontact = Contact::where('id',$tasks[$i]['contact_id'])->select('contacts.first_name','contacts.last_name')->first();
                            $thiscontact_count= Contact::where('id',$tasks[$i]['contact_id'])->select('contacts.first_name','contacts.last_name')->count();
                            // dd($portfolio_count);

                            if($thiscontact_count > 0)
                            {
                                $csvRecord[$i]['contact']       =$thiscontact->first_name.' '.$thiscontact->last_name;
                            }
                            else
                            {
                                // dd($portfolioss);
                                $csvRecord[$i]['contact']       ='';
                            }
                            
                        }
                        else
                        {
                            $thiscontact = Lead::where('id',$tasks[$i]['contact_id'])->select('leads.first_name','leads.last_name')->first();
                            $thiscontact_count= Lead::where('id',$tasks[$i]['contact_id'])->select('leads.first_name','leads.last_name')->count();
                            // dd($portfolio_count);

                            if($thiscontact_count > 0)
                            {
                                $csvRecord[$i]['contact']       =$thiscontact->first_name.' '.$thiscontact->last_name;
                            }
                            else
                            {
                                // dd($portfolioss);
                                $csvRecord[$i]['contact']       ='';
                            }
                        }

                        if($tasks[$i]['priority']!=NULL or $tasks[$i]['priority']!='')
                        {
                            if($tasks[$i]['priority'] == '1')
                                $csvRecord[$i]['value'] = 'High';
                            else
                            {
                                if($tasks[$i]['priority'] == '2')
                                    $csvRecord[$i]['value'] = 'Medium';
                                else
                                    $csvRecord[$i]['value'] = 'Low';
                            }
                                

                        }
                        else
                        {
                            $csvRecord[$i]['value'] = '';
                        }

                        if($tasks[$i]['deadline']!=NULL or $tasks[$i]['deadline']!='')
                        {
                            $csvRecord[$i]['deadline']       = Carbon::createFromFormat('Y-m-d', $tasks[$i]['deadline'])->format('d/m/Y');
                        }
                        else
                        {
                            $csvRecord[$i]['deadline']       = $tasks[$i]['deadline'];
                        }

                        

                        if($tasks[$i]['status']!=NULL or $tasks[$i]['status']!='')
                        {
                            $status = TaskStatus::where('id',$tasks[$i]['status'])->select('task_status.name')->first();
                            $status_count= TaskStatus::where('id',$tasks[$i]['status'])->select('task_status.name')->count();
                            // dd($portfolio_count);

                            if($status_count > 0)
                            {
                                $csvRecord[$i]['status']       =$status->name;
                            }
                            else
                            {
                                // dd($portfolioss);
                                $csvRecord[$i]['status']       =$tasks[$i]['status'];
                            }
                            
                        }
                        else
                        {
                            $csvRecord[$i]['status']       ='';
                        }

                        $csvRecord[$i]['task_creator']       = $tasks[$i]['creator'];
                        $csvRecord[$i]['assignee_name']       = $tasks[$i]['assignee_name'];
                        $csvRecord[$i]['is_bookmark']       = ($tasks[$i]['is_bookmark']==1)?'Yes':'No';


                    }
                } else {
                    $csvRecord = [];
                }

                // dd($csvRecord);


                 // Set the CSV response headers
                    $headers = array(
                        'Content-Type' => 'text/csv',
                        //'Content-Disposition' => 'attachment; filename="data.csv"',
                    );

                    // Create a callback function to write the data to the CSV file
                    $callback = function () use ($csvRecord) {
                        $output = fopen('php://output', 'w');
                        $heading = ['Task Title', 'Contact Name', 'Value', 'Follow-up', 'Status', 'Task Creator', 'Assigned To', 'Is Bookmark'];
                        if (sizeof($csvRecord) >= 1) {
                            fputcsv($output, $heading);
                            foreach ($csvRecord as $row) {
                                fputcsv($output, $row);
                            }
                        } else {
                            fputcsv($output, array('No Record Found!!')); /* if no record found */
                        }
                        fclose($output);
                    };

                    // Return the CSV file as a response
                    Helper::countExportDownload($user_id,4,'Task');
                    return Response::stream($callback, 200, $headers);
            }
            else
            {
                // echo $request->doc_type; die;
                if($request->doc_type == 'pdf')
                {
                    $reportData=ReportSetting::where('created_by', $user_id)->first();
                    if(empty($reportData)){
                        $reportSetting=ReportSetting::where('created_by', 0)->first(); //default custom design
                    }else{
                        $reportSetting=ReportSetting::where('created_by', $user_id)->first();
                    }
                    $userList = User::where('id',$user_id)->first();
                    if($userList->added_by ==0){
                        $userData = User::where('id',$user_id)->first();
                    }else{
                        $userData = User::where('id',$userList->added_by)->first();
                    }
                    $dateToday = Carbon::now()->format('d_m_Y');       
                    $pdf_file_name = 'task.pdf';  
                    $pdf = PDF::loadView('frontend.pdf.task_export', ['tasks' => $tasks,'reportSetting'=>$reportSetting,'userData'=>$userData ,'userList'=>$userList])->setPaper('A4', 'landscape');

                    // dd($pdf);
                    Helper::countExportDownload($user_id,4,'Task');
                    
                    return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
                    
                }
            }
        

       
    }

    function taskStatus(Request $request)
    {
        $taskId = $request->taskId;
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $getTask = Task::where('id', '=', $taskId)->first();

        // dd($getTask);
        // $statuses = TaskStatus::where('is_deleted',0)->where('row_order', '!=', NULL)->orderBy('row_order', 'asc')->get();
        $statuses = TaskStatus::where('is_deleted',0)->where('status',1)->where(function($q) use ($created_by){
                    $q->where('created_by', $created_by)
                    ->orWhere('created_by',0);
                    }) 
                   ->orderBy('row_order', 'asc')
                   ->get();

        $slectStatus = '<select id="swalSelect" class="swal2-input">';

        if($getTask['status'] == '' or $getTask['status'] == 0)
        {
            $slectStatus .= '<option value="" selected>Please select</option>';
        }
        else
        {
            $slectStatus .= '<option value="">Please select</option>';
        }



        if(!is_null($statuses))
        {
            foreach($statuses as $status)
            {
                if($getTask['status'] == $status->id)
                {
                    $slectStatus .= '<option value="'.$status->id.'" selected>'.$status->name.'</option>';
                }
                else
                {
                    $slectStatus .= '<option value="'.$status->id.'">'.$status->name.'</option>';
                }
            }
        }
        $slectStatus .= '</select>';


        return $slectStatus;
    }


    public function completeStatus(Request $request)
    {
        $created_by = auth()->user()->id;
        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $task_id = $request->input('task_id');
        $status = $request->input('status');
        $task = Task::find($task_id);
        $oldTask = Task::find($task_id);
        $oldStatus = $task->status;

        if($request->input('status')==2 && $task->created_by != $created_by){
                    // $completehtml = "A Task($task->tittle) has been completed by $user_name.This task is under review.";
                    $user = auth()->user();
                    $date = date("d-m-Y");
                    $time = date("H:i");
                    $timePeriod = date("a");

                    if(!empty($task) && $task->contact_id!=''){
                        $ContactDetail = Contact::Select('first_name','last_name')->where('id',$task->contact_id)->first();
                        if ($ContactDetail) {
                            $contactName = $ContactDetail->first_name.' '.$ContactDetail->last_name;
                            $contct_msg= 'for ' .$contactName;
                        } else {
                            $contct_msg= '';    
                        }
                    }
                    else{
                        $contct_msg= '';
                    }

                    $completehtml = "$user_name has marked a task assigned by you, titled $task->tittle $contct_msg as ‘Completed’ on ".date('d-m-Y h:i:A');
                    $notification = new Notification;
                    $notification->title =$completehtml;
                    $notification->module_type = 1;
                    $notification->module_id = $task->id;
                    $notification->is_requested = 1;
                    $notification->user_id = $task->created_by;
                    $notification->created_by = $created_by;
                    $notificationSave=$notification->save();
                    
                    if($userDetails->added_by ==0){
                        $mainUserId=$created_by;
                     }else{
                        $mainUserId=$userDetails->added_by;
                     }

                    $task->is_under_review = 1;
                    $task->save();
                    //Set new task status as 2 for check in notifyModificationTask function 
                    $task->status = 2;
                    $this->reviewLog($completehtml, $mainUserId, $task->id, $created_by);
                    
                    $last_update= $oldTask->updated_at->format('Y-m-d H:i:s');
                    $current_update= $task->updated_at->format('Y-m-d H:i:s');
                    if($last_update!=$current_update){
                        $this->notifyModificationTask($oldTask, $task);
                    }

                    $msg = 'This task is under review';

                    return response()->json(['message' => $msg]);

        }else{
            $task->status = $status;
            $task->is_under_review = 0;
            $task->save();
           // $this->notifyModificationTask($oldTask, $task);
            $notification = Notification::where('module_id',$task_id)->where('module_type',1)->where('is_read',0)->first();
            if(!empty($notification)){
                $notificationId = $notification->id;
                Notification::where('id', $notificationId)->update([
                    'is_read' => 1,
                ]);
            }
            $msg = 'Status update successfully';
        }

        if ($oldStatus != $status) {
            if ($task->created_by != $created_by) { 
                if ($userDetails->added_by == 0) {
                    // Main user update a add on user status
                    $activity = new ActivityLogs;
                    $activity->acitivity_description = Helper::taskStatusMsgForMainUser($user_name, $task_id, $oldStatus, $status, $task);
                    $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
                    $activity->user_id = $userDetails->id;
                    $activity->task_module_case = 7;
                    $activity->task_id = $task_id;
                    $activity->save();
                } else {// add on user status update
                    $activity = new ActivityLogs;
                    $activity->acitivity_description = Helper::taskStatusMsgForAddonUser($user_name, $task_id, $oldStatus, $status,$task);
                    $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
                    $activity->user_id = $userDetails->id;
                    $activity->task_module_case = 8;
                    $activity->task_id = $task_id;
                    $activity->save();
                }
                
            } else { // user his/her self status update
                if ($status == 2) {
                    $this->completestatusTask($user_name, $task_id, $task->workflow_step_id, $userDetails);
                } else {
                    $activity = new ActivityLogs;
                    $activity->acitivity_description = Helper::taskStatusMsgForMainUser($user_name, $task_id, $oldStatus, $status, $task);
                    $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
                    $activity->user_id = $userDetails->id;
                    $activity->task_module_case = 7;
                    $activity->task_id = $task_id;
                    $activity->save();
                }
            }
        }
        
        $last_update= $oldTask->updated_at->format('Y-m-d H:i:s');
        $current_update= $task->updated_at->format('Y-m-d H:i:s');
        if($last_update!=$current_update){
            $this->notifyModificationTask($oldTask, $task);
        }
       
        return response()->json(['message' => $msg]);
    }

    public function completestatusTask($user_name, $task_id, $workflow_step_id, $userDetails) 
    {
        $task = Task::where('id', $task_id)->first();
        if ($task->created_by == $userDetails->id) {
            $html = Helper::markTaskMsg($user_name, $task_id, $task);
            $task_module_case = 14;
        } else {
           $html = Helper::completedTaskMsg($user_name, $task_id, $workflow_step_id, $task);
           $task_module_case = $workflow_step_id ? 12 : 11;
        }
        
        $activity = new ActivityLogs;
        $activity->acitivity_description = $html;
        $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
        $activity->user_id = $userDetails->id;
        $activity->task_module_case = $task_module_case;
        $activity->task_id = $task_id;
        $activity->save();
    }

    public function statusUpadte($task, $userDetails, $oldStatus, $status )
    {
        if ($task->created_by != $userDetails->id) { 
            if ($userDetails->added_by == 0) {
                // Main user update a add on user status
                $activity = new ActivityLogs;
                $activity->acitivity_description = Helper::taskStatusMsgForMainUser($userDetails->name, $task->id, $oldStatus, $status, $task);
                $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
                $activity->user_id = $userDetails->id;
                $activity->task_module_case = 7;
                $activity->task_id = $task->id;
                $activity->save();
            } else {// add on user status update
                $activity = new ActivityLogs;
                $activity->acitivity_description = Helper::taskStatusMsgForAddonUser($userDetails->name, $task->id, $oldStatus, $status, $task);
                $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
                $activity->user_id = $userDetails->id;
                $activity->task_module_case = 8;
                $activity->task_id = $task->id;
                $activity->save();
            }
            
        } else {
            $activity = new ActivityLogs;
            $activity->acitivity_description = Helper::taskStatusMsgForMainUser($userDetails->name, $task->id, $oldStatus, $status, $task);
            $activity->main_user_id = $userDetails->added_by != 0 ? $userDetails->added_by : $userDetails->id;
            $activity->user_id = $userDetails->id;
            $activity->task_module_case = 7;
            $activity->task_id = $task->id;
            $activity->save();
        }
    }

    public function completeValue(Request $request)
    {
        $task_id = $request->input('task_id');
        $priority = $request->input('priority');
        $user = auth()->user();
        $task = Task::find($task_id);
        $oldtask = Task::find($task_id);

        $oldPriotity = $task->priority;

        if ($oldPriotity != $priority) {
            $activity = new ActivityLogs;
            $activity->acitivity_description = Helper::taskValueMsg($user->name, $task, $oldPriotity, $priority);
            $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
            $activity->user_id = $user->id;
            $activity->task_module_case = 5;
            $activity->task_id = $task_id;
            $activity->save();

            $this->notifyModificationTask($oldtask, $task);
        }

        $task->priority = $priority;
        $task->save();
        $msg = 'Value update successfully';
       
        return response()->json(['message' => $msg]);
    }


    /*****************************************************/
    # TaskController
    # Function name : notifyModificationTask()
    # Purpose       : Send inapp and email notification to  assignees about the modification of task. providing both the old and new details for comparison.
    # Params        : $oldtask, $task
    /*****************************************************/
    public function notifyModificationTask($oldtask, $task){
        $notification_module_id= \Config::get('constants.task_notification_module_id');
        $notification_type= 3;
        $action_for= 2;  // For assigned team
        $action_for_myself= 1; //For creator
        $notification_title= 'edit_notification';
        $frequency= 'immediatly';
        $push_notification_type=2;
        
        $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);

        $frequency= 'immediatly';

        $defaultMailConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);

        //Check email notification configuration if task modify by assigned user
        $emailNotificationForCreator = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for_myself,$notification_title, $frequency);

        $defaultMailStatusForCreator = Helper::defNotifConfigCheck($notification_module_id, $notification_type,$action_for_myself,$notification_title,$frequency);
        if($task->assignee_id  !='' || !empty($task->assignee_id)){
            
            if($emailConfigStatus==1 || $defaultMailConfigStatus==1 || $emailNotificationForCreator > 0){
                $oldContact_id = ($oldtask->contact_id!='')?$oldtask->contact_id:$oldtask->lead_id;               
            
                $oldStatusDetail =TaskStatus::findOrFail($oldtask->status);
            
                if($oldtask->contact_id!=''){
                    $oldContactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$oldContact_id)->first();
            
                }else{
                    $oldContactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$oldContact_id)->first();
                }   
                if(!empty($oldtask->assignee_id)){
                    $oldAssigneeDetails = User::findOrFail($oldtask->assignee_id);
                    $oldAssigneeEmail = $oldAssigneeDetails->email; 
                } else{
                   $oldAssigneeEmail='';
                }       
                
                $oldData['taskName'] = $oldtask->tittle;   
                $oldData['email'] = $oldAssigneeEmail;                
                $oldData['followUp'] = $oldtask->deadline;              
                $oldData['createdBy'] = auth()->user()->name;
                $oldData['status'] = $oldStatusDetail->name;
                $oldData['priority'] = ($oldtask->priority == 1) ? "High" : ($oldtask->priority == 2 ? "Medium" : "Low");
                $oldData['contactName'] = !empty($oldContactDetail->first_name)?$oldContactDetail->first_name.' '.$oldContactDetail->last_name:'';
                $oldData['contactNumber'] = !empty($oldContactDetail->mobile_number)? $oldContactDetail->mobile_code.' '.$oldContactDetail->mobile_number : '';
                $oldData['task_notes']=$oldtask->notes;
                
                //New Data            
                $newContact_id = ($task->contact_id!='')?$task->contact_id:$task->lead_id;                                       

                $newStatusDetail =TaskStatus::findOrFail($task->status);
                
                if( $task->contact_id!=''){
                    $newContactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$newContact_id)->first();
            
                }else{
                    $newContactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$newContact_id)->first();
                }    
                $newAssigneeDetails = User::findOrFail($task->assignee_id);         
                $newAssigneeEmail = $newAssigneeDetails->email; 
                $newData['taskName'] =  $task->tittle; 
                $newData['email'] = $newAssigneeEmail;                
                $newData['followUp'] = $task->deadline;              
            
                $newData['status'] = $newStatusDetail->name;
                $newData['priority'] = ($task->priority == 1) ? "High" : ($task->priority == 2 ? "Medium" : "Low");

                if ($oldtask->created_by!= auth()->user()->id) {
                    if ($newData['status'] == $oldData['status'] && $task->is_under_review !=  $oldtask->is_under_review) {
                        $newData['status'] = 'Completed (Under Review)';
                    }
                }
                
                $newData['task_id'] = $task->id;
                $newData['contactName'] = $newContactDetail->first_name.' '.$newContactDetail->last_name;
                $newData['contactNumber'] = $newContactDetail->mobile_code.' '.$newContactDetail->mobile_number;
                $newData['task_notes']=$task->notes;
                $oldData['task_unique_id']= !empty($oldtask->task_unique_id)?$oldtask->task_unique_id:
                $oldData['task_unique_id']= !empty($oldtask->task_unique_id)?$oldtask->task_unique_id:$oldtask->id;
                 //Send Email notification to assigned team
                //  dd($emailConfigStatus, $defaultMailConfigStatus, $oldtask->created_by == auth()->user()->id);
                if(($emailConfigStatus==1 || $defaultMailConfigStatus==1) &&   $oldtask->created_by == auth()->user()->id){
                   // $heading_msg= $oldData['createdBy']. ' has modified an task which has been assigned to you';
                   $heading_msg= $oldData['createdBy']. ' has modified a task assigned to you titled  '. $task->tittle;
                    if($task->status == 2 && $oldtask->status != 2){
                        $subType = "Completetion";
                    } else {
                        $subType = "Modification";
                    }
                    
                    Mail::send('email_template.task_modification_email', ['oldData' => $oldData, 'newData'=> $newData, 'heading_msg' => $heading_msg, 'show_manage_notif'=>0 ], function ($m) use ($oldData,$newData, $subType) {
                        $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                        // $m->to($newData['email'])->subject("M-Edge-Task Modification(".$oldData['task_unique_id'].")");
                        $m->to($newData['email'])->subject("M-Edge-Task $subType");
                    });
                }
                //Send Email notification to task creator
                $taskCreatorEmail = User::select('email')->where('id',$oldtask->created_by)->first();
                $creatorEmail = $taskCreatorEmail->email; 
                if(($emailNotificationForCreator==1 || $defaultMailStatusForCreator==1) && $oldtask->created_by!= auth()->user()->id){
                    // $heading_msg= auth()->user()->name. ' has modified an task which has been assigned by you';
                    $heading_msg= auth()->user()->name. ' has modified '. $task->tittle .' task which has been assigned by you';
                    // check user is addon user
                    if (auth()->user()->added_by !=0 ) {
                        if ($task->is_under_review == 1) {
                            $heading_msg= auth()->user()->name. ' has marked a task assigned by you, titled '. $task->tittle .'  as completed';
                        } 
                    }

                    if(($task->status == 2 || $task->is_under_review == 1) && ($oldtask->status != 2 || $oldtask->is_under_review != 1)){
                        $subType = "Completion (pending approval)";
                    } else {
                        $subType = "Modification";
                    }
                    
                    Mail::send('email_template.task_modification_email', ['oldData' => $oldData, 'newData'=> $newData, "heading_msg" => $heading_msg, 'show_manage_notif'=>1], function ($m) use ($creatorEmail, $oldData, $subType) {
                        $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                        $m->to($creatorEmail)->subject("M-Edge Task $subType");
                    });
                }
             
            }
        }

        //inapp notification
        $inapp_notification_type= 1;      

        $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title, $frequency);

        $defaultInAppConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title,$frequency);

        if($inAppConfigStatus==1 || $defaultInAppConfigStatus==1){
            $inapp_notification_flag=1;
        }else{
            $inapp_notification_flag=0;
        }

         //Push notification for assigned team   

         $pushConfigStatus = Helper::notificationConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title,$frequency);

         $defaultPushConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title, $frequency);
         if($pushConfigStatus==1 || $defaultPushConfigStatus==1){
          $push_notification_flag=1;
         }else{
          $push_notification_flag=0;
         }

        //Check notification configuration if task modify by assigned user
        $inAppNotificationForCreator = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for_myself,$notification_title, $frequency);

        
        $defaultInAppStatusForCreator = Helper::defNotifConfigCheck($notification_module_id, $inapp_notification_type,$action_for_myself,$notification_title,$frequency);

        if($inAppNotificationForCreator==1 || $defaultInAppStatusForCreator==1){
            $myselfInapp=1;
        }else{
            $myselfInapp=0;
        }

        //Push notification for Creator 

        $pushConfigStatus = Helper::notificationConfigCheck($notification_module_id, $push_notification_type,$action_for_myself,$notification_title,$frequency);

        $defaultPushConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $push_notification_type,$action_for_myself,$notification_title, $frequency);
        if($pushConfigStatus==1 || $defaultPushConfigStatus==1){
         $myPushFlag=1;
        }else{
         $myPushFlag=0;
        }
       
             
        if($task->assignee_id  !=''){
            $newContact_id = ($task->contact_id!='')?$task->contact_id:$task->lead_id;
            
            if( $task->contact_id!=''){
                $newContactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$newContact_id)->first();
        
            }else{
                $newContactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$newContact_id)->first();
            }

            $contact_name= !empty($newContactDetail) ?  $newContactDetail->first_name.' '.$newContactDetail->last_name : '';  
          
            // Send notification to assignee if task modify by creator $newContactDetail
            if(($inapp_notification_flag==1 ) &&   $task->created_by== auth()->user()->id){                

                // $html =   auth()->user()->name. " has Modify a task which has been assigned to you on " .date('d-m-Y h:i:A'). " Details are as follows :
                // Task ID - <$task->task_unique_id>	Task Title - <$task->tittle>	Contact_Name - <$contact_name>	Click on this to have a view & take actions on Task Dashboard.";
                $user = auth()->user();
                $date = date("d-m-Y");
                $time = date("H:i");
                $timePeriod = date("a");

                if ($task->status == 2 && $oldtask->status != 2) {
                    // $html = "$user->name has marked a task assigned to you titled $task->tittle  for $contact_name as ‘Completed’  on $date $time $timePeriod.";
                    $html = "A Task ($task->tittle) has been completed by $user->name ";
                } else {
                    $html = "$user->name has modified a task assigned to you titled $task->tittle  for $contact_name on ".date('d-m-Y h:i:A');
                }                
                
                $notification = new Notification;
                $notification->title =$html;
                $notification->module_type = \Config::get('constants.task_notification_module_id');
                //$notification->module_id = $details->id;
                $notification->link = \URL::route('user.task');
                $notification->user_id = $task->assignee_id;
                $notification->inapp_notification_flag= $inapp_notification_flag;
                $notification->push_notification_flag= $push_notification_flag;
                $notification->created_by = auth()->user()->id;
                $notification->save();
            } 
        }

       // Send notification to creator if task modify by assigned user
        if(($myselfInapp==1 || $myPushFlag==1)&& $oldtask->created_by!= auth()->user()->id){
            // $html =   auth()->user()->name. " has Modify a task which has been assigned by you on " .date('d-m-Y h:i:A'). " Details are as follows :
            //     Task ID - <$task->task_unique_id>	Task Title - <$task->tittle>	Contact_Name - <$contact_name>	Click on this to have a view & take actions on Task Dashboard.";
            if($task->status!=2 && $task->is_under_review != 1){
            $contact_str = $contact_name != '' ? "for $contact_name " : '';
            //$html = auth()->user()->name." has marked a task assigned by you, titled $task->tittle $contact_str as ‘Completed’ on ".date('d-m-Y h:i:A');

                $html = auth()->user()->name. ' has modified '. $task->tittle .' task which has been assigned by you';
                $notification = new Notification;
                $notification->title =$html;                
                $notification->link = \URL::route('user.task');
                $notification->user_id = $task->created_by;
                $notification->inapp_notification_flag= $myselfInapp;
                $notification->push_notification_flag= $myPushFlag;
                $notification->created_by = auth()->user()->id;
                $notification->save();
            }
        }
    }

    /*****************************************************/
    # TaskController
    # Function name : notifyTaskCreation()    
    # Purpose       :  Send inapp and email notification to  assignees about the creation of new task. 
    # Params        : $task
    /*****************************************************/
   /* public function notifyTaskCreation($task){
        $notification_module_id= 3;
        $notification_type= 3;
        $action_for= 2;
        $notification_title= 'create_notification';
        
        $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title);
        if($task->assignee_id !='' && $emailConfigStatus==1){
            $contact_id = ($task->contact_id!='')?$task->contact_id:$task->lead_id;    
           
            if($task->contact_id!=''){
                $contactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$contact_id)->first();
            
            }else{
                $contactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$contact_id)->first();
            }  
            $statusDetail =TaskStatus::findOrFail($task->status);
            $assigneeDetails = User::findOrFail($task->assignee_id);
            $assigneeEmail = $assigneeDetails->email;
            $assigneeName = $assigneeDetails->name;
            $userData['email'] = $assigneeEmail;
            $userData['name'] = $assigneeName;
            
            $userData['followUp'] = !empty($task->deadline)? date('d F, Y', strtotime($task->deadline)):'';
            $userData['priority'] = ($task->priority == 1) ? "High" : ($task->priority == 2 ? "Medium" : "Low");
            $userData['taskName'] = $task->tittle;
            $userData['contactName'] = $contactDetail->first_name.' '.$contactDetail->last_name;
            $userData['contactNumber'] = $contactDetail->mobile_code.' '.$contactDetail->mobile_number;
            $userData['createdBy'] = auth()->user()->name;
            $userData['task_notes']=$task->notes;
            $userData['status'] = $statusDetail->name;
            //$url = '<a href="' . \URL::route('user.task') . '">'.$request->input('title').'</a>';
            //$userData['urlParam']   = $url;
            Mail::send('email_template.task_creation_mail', ['user' => $userData], function ($m) use ($userData) {
                $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                $m->to($userData['email'], $userData['name'])->subject("M-Edge");
                });
        }
         //inapp notification
         $inapp_notification_type= 1;      

         $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title);
              
         if($task->assignee_id !='' && $inAppConfigStatus==1){
            $contact_name= $contactDetail->first_name.' '.$contactDetail->last_name;   
          
            $createhtml = auth()->user()->name. " has created a task for you  <$task->task_unique_id> - <$task->tittle> on " .date('d-m-Y h:i:A')." for contact <$contact_name> with <$statusDetail->name> status & <$task->deadline> follow up date. Click on this to have a view & take actions on Task Dashboard.";
                       
             $notification = new Notification;
             $notification->title =$createhtml;            
             $notification->link = \URL::route('user.task');
             $notification->user_id = $task->assignee_id;
             $notification->created_by = auth()->user()->id;
             $notification->save();
         } 

   }*/
    /*****************************************************/
    # TaskController
    # Function name : notifyAssignTask()    
    # Purpose       :  Send inapp and email notification to  assignees about the assigning new task. 
    # Params        : $task
    /*****************************************************/
    public function notifyAssignTask($task){
        $notification_module_id= \Config::get('constants.task_notification_module_id');
        $notification_type= 3;
        $action_for= 2;
        $notification_title= 'assign_notification';    
        $frequency= 'immediatly';       
        $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);

        $defaultMailConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);
      
        $contact_id = ($task->contact_id!='')?$task->contact_id:$task->lead_id; 

        if($task->contact_id!=''){
            $contactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$contact_id)->first();
    
        }else{
            $contactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$contact_id)->first();
        }

        if($emailConfigStatus==1 || $defaultMailConfigStatus==1){
            $statusDetail =TaskStatus::findOrFail($task->status);

            $assigneeDetails = User::findOrFail($task->assignee_id);
            $assigneeEmail = $assigneeDetails->email;
            $assigneeName = $assigneeDetails->name;  

            $userData['task_id'] = $task->id;
            $userData['email'] = $assigneeEmail;
            $userData['name'] = $assigneeName;
            $userData['followUp'] = $task->deadline;
            $userData['taskName'] = $task->tittle;
            $userData['createdBy'] = auth()->user()->name;
            $userData['status'] = $statusDetail->name;
            $userData['priority'] = ($task->priority == 1) ? "High" : ($task->priority == 2 ? "Medium" : "Low");

            $userData['contactName'] = $contactDetail ? $contactDetail->first_name.' '.$contactDetail->last_name : '';
            $userData['contactNumber'] = $contactDetail ? $contactDetail->mobile_code.' '.$contactDetail->mobile_number : '';
            $userData['task_notes']=$task->notes;
            $userData['task_unique_id']= !empty($task->task_unique_id)?$task->task_unique_id:$task->id;

            $url = '<a href="' . \URL::route('user.task') . '">'.$task->tittle.'</a>';
            $userData['urlParam']   = $url;
            Mail::send('email_template.task_assign_email', ['user' => $userData], function ($m) use ($userData) {
                $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                $m->to($userData['email'], $userData['name'])->subject("M-Edge Assigned Task");
            });
        }

         //inapp notification
         $inapp_notification_type= 1;      

         $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title, $frequency);

         $defaultInappConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title,$frequency);

         if($inAppConfigStatus==1 || $defaultInappConfigStatus==1){
            $inapp_notification_flag=1;
         }else{
                $inapp_notification_flag=0;
         }

          //Push notification
          $push_notification_type= 2;       

          $pushConfigStatus = Helper::notificationConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title,$frequency);

          $defaultPushConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title, $frequency);
          if($pushConfigStatus==1 || $defaultPushConfigStatus==1){
            $push_notification_flag=1;
          }else{
            $push_notification_flag=0;
          }
              
         if($inapp_notification_flag==1 ){
            $contact_name= $contactDetail ? $contactDetail->first_name.' '.$contactDetail->last_name : '';             
           
            // $assignhtml =   auth()->user()->name. " has assigned a Task for you on " .date('d-m-Y h:i:A'). " Details are as follows :
            //     Task ID - <$task->task_unique_id>	Task Type - <$task->tittle>	Contact_Name - <$contact_name >" ;

            $assignhtml = auth()->user()->name. " has assigned a Task to you titled $task->tittle	for $contact_name, on ".date('d-m-Y h:i:A');
                       
             $notification = new Notification;
             $notification->title =$assignhtml;
             $notification->module_type = \Config::get('constants.
             ');
             //$notification->module_id = $details->id;
             $notification->link = \URL::route('user.task');
             $notification->user_id = $task->assignee_id;
             $notification->inapp_notification_flag= $inapp_notification_flag;
             $notification->push_notification_flag= $push_notification_flag;
             $notification->created_by = auth()->user()->id;
             $notification->save();
         } 
    }

    /*****************************************************/
    # TaskController
    # Function name : notifyDeletionTask()    
    # Purpose       :  Send inapp and email notification to  assignees about the deletion of assigned task. 
    # Params        : $task
    /*****************************************************/

    public function notifyDeletionTask($details){
        $user_id= auth()->user()->id;
        $notification_module_id= \Config::get('constants.task_notification_module_id');
        $notification_type= 3;
        $action_for= 2;
        $notification_title= 'delete_notification';  
        $frequency= 'immediatly';

        $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);

        $defaultMailConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title,$frequency);      
      

        if($details->assignee_id  !=''){
            if(($emailConfigStatus==1 || $defaultMailConfigStatus==1) && $details->created_by= $user_id){
                $contact_id = ($details->contact_id!='')?$details->contact_id:$details->lead_id;               
            
                $statusDetail =TaskStatus::findOrFail($details->status);
            
                if($details->contact_id!=''){
                    $contactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$contact_id)->first();
            
                }else{
                    $contactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$contact_id)->first();
                }             
                $assigneeDetails = User::findOrFail($details->assignee_id);
                $assigneeEmail = $assigneeDetails->email;    
                $userData['email'] = $assigneeEmail;                
                $userData['followUp'] = $details->deadline;
                $userData['priority'] = ($details->priority == 1) ? "High" : ($details->priority == 2 ? "Medium" : "Low");
                $userData['taskName'] = $details->tittle;
                $userData['createdBy'] = auth()->user()->name;
                $userData['status'] = $statusDetail->name;
            
                $userData['contactName'] = !empty($contactDetail) ? $contactDetail->first_name.' '.$contactDetail->last_name : '';
                $userData['contactNumber'] = !empty($contactDetail) ? $contactDetail->mobile_code.' '.$contactDetail->mobile_number : '';
                $userData['task_notes']=$details->notes;
                $userData['task_unique_id']= !empty($details->task_unique_id)?$details->task_unique_id:$details->id;
                    
                Mail::send('email_template.task_deletion_email', ['user' => $userData], function ($m) use ($userData) {
                    $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                    // $m->to($userData['email'])->subject("M-Edge Deleted Task (".$userData['task_unique_id'].")");
                    $m->to($userData['email'])->subject("M-Edge Deleted Task");
                });
            }
        }
       
         //inapp notification
         $inapp_notification_type= 1;       

         $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title,$frequency);

         $defaultInappConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title,$frequency);

         if($inAppConfigStatus==1 || $defaultInappConfigStatus==1){
            $inapp_notification_flag=1;
         }else{
                $inapp_notification_flag=0;
         }

          //Push notification
          $push_notification_type= 2;       

          $pushConfigStatus = Helper::notificationConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title,$frequency);

          $defaultPushConfigStatus = Helper::defNotifConfigCheck($notification_module_id, $push_notification_type,$action_for,$notification_title, $frequency);
          if($pushConfigStatus==1 || $defaultPushConfigStatus==1){
            $push_notification_flag=1;
          }else{
            $push_notification_flag=0;
          }
              
         if($details->assignee_id  !=''){
            $contact_id = ($details->contact_id!='')?$details->contact_id:$details->lead_id;               
            
            $statusDetail =TaskStatus::findOrFail($details->status);
        
            if($details->contact_id!=''){
                $contactDetail = Contact::Select('first_name','last_name','mobile_code','mobile_number')->where('id',$contact_id)->first();
            }else{
                $contactDetail = Lead::Select('first_name','last_name','mobile_code','mobile_Numer')->where('id',$contact_id)->first();
            }

            if($inapp_notification_flag==1 ){
                $contact_name= !empty($contactDetail) ? $contactDetail->first_name.' '.$contactDetail->last_name : ''; 
                $conatct_msg = '';
                if ($contact_name != '') {
                    $conatct_msg = "for $contact_name";
                }           
                // $deletehtml = auth()->user()->name. " has deleted  <$details->task_unique_id> - <$details->tittle> on " .date('d-m-Y h:i:A')." $conatct_msg with <$statusDetail->name> status & <$details->deadline> follow up date. Click on this to have a view & take actions on Task Dashboard.";
                
                $deletehtml = "A task assigned to you titled $details->tittle $conatct_msg has been deleted by ".auth()->user()->name."  on ".date('d-m-Y h:i:A');
                $notification = new Notification;
                $notification->title =$deletehtml;
                $notification->module_type = \Config::get('constants.task_notification_module_id');
                //$notification->module_id = $details->id;
                $notification->link = \URL::route('user.task');
                $notification->user_id = $details->assignee_id;
                $notification->created_by = auth()->user()->id;
                $notification->inapp_notification_flag= $inapp_notification_flag;
                $notification->push_notification_flag= $push_notification_flag;
                $notification->save();
            } 
        }
    }
    /*****************************************************/
        # TaskController 
        # Function name :sendPokesToAssignee()
        # Author        :  
        # Purpose       : Creator poke to assignee with notes
        # Params        : Request $request
        /*****************************************************/
    public function sendPokes(Request $request)
    {
        $user_id= auth()->user()->id;
        $task_id = $request->input('taskId');
        $notes = $request->input('notes');
        $task = Task::find($task_id);
        if($task->is_cancel_thread ==1){
            $task->is_cancel_thread = 0;
            $task->save();
        }
        $taskPoke = new TaskPoke;
        if($user_id ==$task->assignee_id){
            $taskPoke->poke_to_id = $task->created_by;
        }else{
            $taskPoke->poke_to_id = $task->assignee_id;
        }
        $taskPoke->poke_from_id = $user_id;
        $taskPoke->task_id = $task_id ;
        $taskPoke->task_created_by = $task->created_by ;
        $taskPoke->notes = $notes;
        
        if($taskPoke->save()){
            if($user_id ==$task->assignee_id){
                $task->assignee_poked_to_creator = 1; 
            }else{
                $task->creator_poked_to_assignee = 1; 
                $task->first_poke = 1; //if creator poked first time .
            }
           
            $task->save();
        }
        $msg = 'you have poked successfully';
        return response()->json(['message' => $msg]);
    }
    public function getCreatorConversation($task_id)
    {
        $user_id= auth()->user()->id;
        $task = Task::find($task_id);
        if($user_id ==$task->assignee_id){
            $task->creator_poked_to_assignee = 0;
        }else{
            $task->assignee_poked_to_creator = 0;  //if any poke alert showing while clicking on notification creator button will be 1 to 0
        }
        $task->save();
        $conversation = TaskPoke::query()
                        ->leftjoin('users', 'users.id', '=', 'task_pokes.poke_from_id')
                        ->select('task_pokes.*','users.name as sender_name','users.profile_image','users.id as user_id')
                        ->where('task_pokes.task_id', $task_id)
                        ->where(function($query) use ($user_id) {
                            $query->where('task_pokes.poke_from_id', $user_id)
                                  ->orWhere('task_pokes.poke_to_id', $user_id);
                        })
                        ->get();
        return response()->json($conversation);
    }
    public function cancelThread(Request $request)
    {
        $user_id= auth()->user()->id;
        $task_id = $request->input('taskId');
        $notes = $request->input('notes');
        $task = Task::find($task_id);
        // $taskPokeData=TaskPoke::where('task_id',$task_id)->where('poke_to_id',$task->assignee_id)->where('is_close_thread',1)->orderBy('id','desc')->first();
        $taskPokeData=Task::where('id',$task_id)->where('is_cancel_thread',1)->first();
        if(!empty($taskPokeData)){
            $msg = 'Already follow up thread  cancelled successfully';
            return response()->json(['message' => $msg]);
        }
        $task->is_cancel_thread = 1;
        $task->save();
        $taskPoke = new TaskPoke;
        $taskPoke->poke_from_id = $user_id;
        $taskPoke->poke_to_id = $task->assignee_id;
        $taskPoke->task_id = $task_id ;
        $taskPoke->task_created_by = $task->created_by ;
        $taskPoke->is_close_thread = 1;
        $taskPoke->save();
        $msg = 'Follow up thread  cancelled successfully';
        return response()->json(['message' => $msg]);
    }
    public function getCustomTag(Request $request)
    {
        $user_id = auth()->user()->id;
        if(auth()->user()->added_by ==0){
            $mainUserId =auth()->user()->id;
        }else{
            $mainUserId =auth()->user()->added_by;
        }
        $customTag = TagForTask::select("id", 'custom_tag as value')
            ->where('is_deleted', '=', 0)
            ->where('status', '=', 1)
            ->where(function($q) use ($request,$user_id,$mainUserId){
                $q->where('created_by',$user_id)
                    ->orWhere('main_user_id',$mainUserId);
                })
            ->where(function ($query) use ($request) {
                $query->where('custom_tag', 'LIKE',  $request->search . '%');
            })
            ->get();
        return response()->json($customTag);
    }
     # TaskController
    # Function name : addTag()    
    # Purpose       : Add a new tag after ensuring unique tag. 
    # Params        : Request $request
    /*****************************************************/
    public function addTag(Request $request)
    {
       
        $created_by = auth()->user()->id;
        if(auth()->user()->added_by ==0){
            $mainUserId =auth()->user()->id;
        }else{
            $mainUserId =auth()->user()->added_by;
        }
        $input = $request->all();
        $tag = $input['tag'];

        $tag = Helper::cleanText($input['tag']);
        if (TagForTask::where('custom_tag', $tag)->where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$created_by])->exists()) {
            $msg = 'Tag name must be unique!!';
            return response()->json(['message' => $msg, 'tagId' => '']);
        } else {

            $CustomTag = new TagForTask;
            $CustomTag->custom_tag = $tag;
            $CustomTag->created_by = $created_by;
            $CustomTag->main_user_id = $mainUserId;
            $CustomTag->save();
            $tagId = $CustomTag->id;
            $msg = 'Tag added successfully';
            return response()->json(['message' => $msg, 'tagId' => $tagId]);
        }
    }
    public function removeTag(Request $request)
    {
        $user_id= auth()->user()->id;
        $taskId = $request->task_id;
        $tag = $request->tag;
        $tagArray =TagForTask::where('custom_tag',$tag) ->first();
        $tagId = $tagArray->id;
        DB::table('task_tags')
        ->where('task_id', $taskId)
        ->where('task_tag_id', $tagId)
        ->delete();
        return response()->json(['success' => true]);

                           
    }
        
    public function unassign_session()
    {
        if (request('unassigned') == 1) {
            session(['unassign_session' => request('unassigned')]);
        }else {
            session()->forget('unassign_session'); // Remove the session key 'unassign_session'
        }
        
        if (request('srch_txt') != '') { 
            session(['task_srchTxt' => request('srch_txt')]);
        } else {
            session()->forget('task_srchTxt');
        }
        
        return response()->json(['status' => 'true']);
    }

    
}
