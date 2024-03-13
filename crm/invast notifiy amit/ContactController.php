<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactFamilyMember;
use App\Models\ContactCustomTag;
use App\Models\ContactNotes;
use App\Models\User;
use App\Models\ContactInvestment;
use App\Models\Agenda;
use App\Models\Opportunity;
use App\Models\Task;
use App\Models\Meeting;
use App\Models\CustomTag;
use Illuminate\Support\Facades\Response;
use App\Helpers\Helper;
use DB;
use Carbon\Carbon;
use App\Models\Lead;
use App\Models\CountryCode;
//use Validator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ActivityLogs;
use App\Models\ContactType;
use App\Models\MeetingSummery;
use App\Models\Relationship;
use App\Models\InvestmentStatus;
use App\Models\OpportunityTypeAdmin;
use App\Models\ReportSetting;
use App\Models\LoyaltyRank;
use App\Models\AccountType;
use App\Models\ClientPotential;
use App\Models\UserCreatorTypeFilter;
use PDF;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Mail;
use App\Models\Notification;


class ContactController extends Controller
{
    /*****************************************************/
    # ContactController
    # Function name : __construct() 
    # Author        :
    # Purpose       : To apply middleware, specifically to check the authorization of the authenticated user before allowing access to the controller's methods. 
    /*****************************************************/
    public function __construct()
    {   
        $this->middleware(function ($request, $next) {
            $userid = auth()->user()->id;
            $checkAuthorization= Helper::checkAuthorization($userid);
            if($checkAuthorization){
                return $next($request);
            }else{
                return \Redirect::route('user.dashboard');
            }
        });
    }

    /*****************************************************/
    # ContactController
    # Function name : index()
    # Author        :  
    # Purpose       : show contact list with all data
    # Params        : Request $request
    /*****************************************************/
    public function index(Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $userFilter = $request->input('userFilter');        

        $data['page_title'] = "Contact Module";
        $page_val= (!empty($request->page))?$request->page:1;
        $page_size= isset($request->record_count) ? $request->record_count : 25;

        // $data['added_user'] = User::where('added_by', $user_id)->where('is_deleted',0)
        // ->where('status', 1)->get();
        if(auth()->user()->added_by ==0){
            $data['edit_contact']= array(19);
            $data['added_user'] = User::where(function($q) use ($request){
                                        $q->where('id',auth()->user()->id)
                                            ->orWhere('added_by',auth()->user()->id);
                                        })
                                        ->where('is_deleted',0)
                                        ->where('status', 1)->orderBy('name','ASC')->get();
        }else{
            $data['edit_contact']= \App\Models\RolePermission::where(['module_functionality_id' => 19, 'role_id' => $userDetails->role_id,'main_user_id' =>$userDetails->added_by, 'status' => 'A', 'is_deleted' => 0])->pluck('module_functionality_id')->toArray();

            if($userDetails->role_id ==1){
                $data['added_user'] = User::where('group_id', $userDetails->group_id)->where('added_by','!=',0)->whereNotNull('group_id')->where('is_deleted',0)
                ->where('status', 1)->orderBy('name')->get();
            }else{
                $data['added_user'] = User::where('id', $user_id)->where('is_deleted',0)
            ->where('status', 1)->orderBy('name')->get();
            }
        }

        $get_contact_creators_filter = UserCreatorTypeFilter::where('created_by', auth()->user()->id)->where('creator_filter_type',5)->first();
        if(!empty($get_contact_creators_filter->creator_filter) && $get_contact_creators_filter->creator_filter!=0){
            $data['contact_creator_filter'] = $get_contact_creators_filter->creator_filter;
        }else{
            $data['contact_creator_filter'] = $user_id; 
        }
        
        if ($request->ajax()) {           
                        
            \DB::enableQueryLog();
            $contactsQuery = Contact::query()->leftjoin('users as addOnUsers', 'addOnUsers.id', '=', 'contacts.addon_user_id')
            ->leftJoin('users as createdByUsers', 'createdByUsers.id', '=', 'contacts.created_by')
                ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
                ->select('contacts.*', 'addOnUsers.name', 'createdByUsers.name as creator', 'contact_types.name as contact_type_name')
                ->where(function ($q) use ($request, $user_id) {
                    $add_on_user= $request->userFilter;
             
                    if ($add_on_user!=0) {                       
                        $add_on_user = $request->userFilter;
                        $explode_user = explode(',', $add_on_user);
                        $cleaned_users = array_map(function ($user) {
                           // return intval(trim($user));
                           return (trim($user));
                        }, $explode_user);

                        if (in_array('assign', $cleaned_users)) {
                            if (count($cleaned_users) == 1) {
                                $q->WhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                            } else {
                                $q->whereIn('contacts.created_by', $cleaned_users)
                                   ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                            }
                        }else{
                            $q->whereIn('contacts.created_by', $cleaned_users);                          
                        }
                        //$q->whereIn('contacts.created_by', $cleaned_users);
                       // $q->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                    }else{
                        $q->whereRaw('1 = 0'); //For empty result
                        /*if (auth()->user()->added_by == 0) {
                            $q->where('main_user_id', auth()->user()->id);
                        } else {*/                           
                           //$q->where('contacts.created_by', auth()->user()->id);
                        //}
                    }
                    
                })
                ->where('contacts.is_deleted', 0)
                //->where('contacts.created_by', $user_id)

                ->when($request->search_text, function ($q) use ($request) {
                    $searchText = '%' . $request->search_text . '%';
                    //$q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',   $searchText . '%');
                    $q->where(DB::raw('CONCAT(first_name, " ", IFNULL(last_name, ""))'), 'LIKE', $searchText);
                   
                })

                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                })

                ->when($request->contact_filter_type, function ($q) use ($request) {                   
                        $q->where('contacts.contact_type', $request->contact_filter_type);                  
                })                

                ->latest();

                $contacts = !empty($request->record_count)
                ? $contactsQuery->paginate($request->record_count)
                : $contactsQuery->get();
               // ->paginate($request->record_count ? $request->record_count : 10);
                // dd(\DB::getQueryLog());               
                //Get contact HOF
                /*foreach ($contacts as $contact) {
                   
                    $assigneeIds = $contact->assignee_ids; 
                    $assigneeIdsArray = explode(',', $assigneeIds);
                    $userNames = User::whereIn('id', $assigneeIdsArray)
                                    ->pluck('name');                           
                    $contact->userNames = (!empty($userNames)) ? $userNames : '';
                }*/
                $userIds = $contacts->pluck('assignee_ids')
                    ->flatMap(function ($ids) {
                        return explode(',', $ids);
                    })
                    ->unique()
                    ->toArray();
                    // Fetch user names for all user IDs
                    $userNames = User::whereIn('id', $userIds)
                    ->pluck('name', 'id');
                    // Map user names to contacts
                    $contacts->each(function ($contact) use ($userNames) {
                    $assigneeIds = explode(',', $contact->assignee_ids);
                    $contact->userNames = $userNames->only($assigneeIds)->values();//->implode(', ');
                });
              
          //  dd(\DB::getQueryLog());
            //$data['data_count'] = $page_val*$contacts->count();
             
            if(!empty($request->record_count)){
                $data['start']= ($page_val - 1) * $page_size + 1;
                $data['end']= ($contacts->count()<$page_size)?($contacts->total()):$page_val * $page_size;
            }else{
                $data['start']= '';
                $data['end']= '';
            }
            
         
            $data['total_count']=!empty($request->record_count)
            ?  $contacts->total()
            : '';
            return view('frontend.contact.contacts_data', compact('contacts'), $data)->render();
        }else{  
            $contacts = Contact::query()
                ->leftjoin('users as addOnUsers', 'addOnUsers.id', '=', '.addon_user_id')
                ->leftJoin('users as createdByUsers', 'createdByUsers.id', '=', 'contacts.created_by')
                
                ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
                ->select('contacts.*', 'addOnUsers.name', 'contact_types.name as contact_type_name', 'createdByUsers.name as creator')
                //commnted line no need to remove
                /*->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('contacts.main_user_id', auth()->user()->id);
                    } else {
                        $q->where('contacts.created_by', auth()->user()->id)
                        ->orWhere('contacts.addon_user_id', auth()->user()->id);
                    }
                })*/
                ->where(function ($q) use ($request,$user_id,$data) {
                // $userFilter = $request->userFilter;
                    $userFilter=  $data['contact_creator_filter'];                
                    
                    if ($userFilter) {
                        $explode_user = explode(',', $userFilter);
                        $cleaned_users = array_map(function ($user) {
                            //return intval(trim($user));
                            return (trim($user));
                        }, $explode_user);     
                        if(!in_array(-1, $cleaned_users)){                       
                            if (in_array('assign', $cleaned_users)) {
                                if (count($cleaned_users) == 1) {
                                    $q->WhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                                } else {
                                    $q->whereIn('contacts.created_by', $cleaned_users)
                                    ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                                }
                            }else{
                                $q->whereIn('contacts.created_by', $cleaned_users);                          
                            }
                        }else{                        
                            $q->whereIn('contacts.created_by', $cleaned_users)
                        // $q->where('contacts.created_by', auth()->user()->id)
                            ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                        }        
                        
                    }else{
                        $q->where('contacts.created_by', auth()->user()->id)
                        ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);                   
                    }
                })
                ->latest()
                
                ->where('contacts.is_deleted', 0)
                ->paginate(25);
            //Get contact family member
            /*foreach ($contacts as $contact) {           
                $assigneeIds = $contact->assignee_ids; 
                $assigneeIdsArray = explode(',', $assigneeIds);
                $userNames = User::whereIn('id', $assigneeIdsArray)
                                ->pluck('name');                           
                $contact->userNames = (!empty($userNames)) ? $userNames : '';
                
            }*/
            $userIds = $contacts->pluck('assignee_ids')
            ->flatMap(function ($ids) {
                return explode(',', $ids);
            })
            ->unique()
            ->toArray();
            // Fetch user names for all user IDs
            $userNames = User::whereIn('id', $userIds)
            ->pluck('name', 'id');
            // Map user names to contacts
            $contacts->each(function ($contact) use ($userNames) {
                $assigneeIds = explode(',', $contact->assignee_ids);
                $contact->userNames = $userNames->only($assigneeIds)->values();//->implode(', ');
            });
        }
        $data['contactType'] = ContactType::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();  
        
       // $data['data_count'] = $contacts->count();
        $data['total_count'] = $contacts->total();
        $data['start']= ($page_val - 1) * $page_size + 1;
        $data['end']= ($contacts->count()<$page_size)?($contacts->total()):$page_val * $page_size;

        $data['add_on_users']= Helper::add_on_users();      
        return view('frontend.contact.list', compact('contacts'), $data);
    }

    /*****************************************************/
    # ContactController
    # Function name : create()    
    # Purpose       : Set up the necessary data for displaying a form to add a new contact
    # Params        : Request $request, $id
    /*****************************************************/
    public function create(Request $request, $id = null)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $data['page_title'] = "Add a contact";
        $data['is_backBtn'] = 1;
        $data['cntctId'] = $request->contact;  
        if(!empty($data['cntctId'])){
            $data['previousURL'] = $request->headers->get('referer');
        }else{
            $data['previousURL'] =''; 
        }      
        $data['states'] = DB::table('states')->get();
        $data['contacts'] = Contact::select('id', 'first_name', 'last_name')->where('created_by', $user_id)->get();
        //$data['add_on_users'] = User::where('added_by', $user_id)->where('is_deleted', 0)->where('status', 1)->get();
        $data['add_on_users'] =$this->searchUser();
        $data['custom_tags'] = CustomTag::where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$user_id])->get();
        $data['contactType'] = ContactType::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['loyaltyRank'] = LoyaltyRank::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['accountType'] = AccountType::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['clientPotential'] = ClientPotential::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['relationships'] = Relationship::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
       
        $data['investment_status'] = InvestmentStatus::where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->get();

        $data['opprtunity_type'] = OpportunityTypeAdmin::where('is_deleted',0)
        ->where('status',1)
        ->whereIn('created_by',[0,$user_id])
        ->orderBy('row_order','ASC')->latest()->get();
        $data['country_code']= CountryCode::get();
        //Switch Lead
        if ($id != null) {
            $lead_id = base64_decode($id);
            $data['lead_detail'] =  Lead::find($lead_id);
            $data['cities'] = DB::table('cities')
                ->where('state_id', $data['lead_detail']->state_id)
                ->orderBy('city', 'ASC')
                ->get();
            $data['referred_by'] = Contact::select('id', 'first_name', 'last_name')->where('contacts.id', $data['lead_detail']->referred_by)->get();
        } else {
            $data['lead_detail'] = '';
        }
        return view('frontend.contact.create', $data);
    }

    
    /*****************************************************/
    # ContactController
    # Function name : saveContact()    
    # Purpose       :  Save the contact's information provided in the request, categorizing it into sections such as Personal, Contact, Account, and Investment.
    # Params        : Request $request
    /*****************************************************/

    public function saveContact(Request $request)
    {
       
        $created_by = auth()->user()->id;
        $main_user = auth()->user()->added_by == 0 ? $created_by : auth()->user()->added_by;
        $step = $request->input('steps');
        $contactId = '';
        $contact_id = $request->input('contact_id');
        $lead_id = $request->input('lead_id');
        $iscreate_new = $request->input('iscreate_new');
        $data['iscreate_new']=$iscreate_new;
        $data['previousURL']= $request->input('previousURL');

        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $hof_id = '';
        if ($userDetails->added_by == 0) {
            $mainUserId = $created_by;
        } else {
            $mainUserId = $userDetails->added_by;
        }

        $randomAlphabet = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
        $uniqueNumber = round(microtime(true))+rand(1000,9999);
        $contact_unique_id= $randomAlphabet.$uniqueNumber;

        try {
            if ($lead_id != '') {
                $this->convert_contact($lead_id);
                $msg = 'Contact saved successfully!!';
                $status = 'success';
            } else {
                if ($step == 'personal') {
                    $reletionship = $request->input('relation');
                    $family_member = $request->input('family_member_id');                  
                    $hof = $request->input('hof');
                    $dob = $request->input('dob');

                    if (!empty($contact_id)) {
                        $contact = Contact::find($contact_id);
                    } else {
                        $contact = new Contact;
                        $contact->contact_unique_id= $contact_unique_id;
                    }

                    $contact->first_name = $request->input('first_name');
                    $contact->last_name = $request->input('last_name');
                    if (!empty($dob)) {
                        $date = str_replace('/', '-', $dob);
                        $contact->dob  = date("Y-m-d", strtotime($date));
                    }
                    $contact->age = $request->input('age');
                    $contact->contact_type = $request->input('contact_type');
                    $contact->created_by = $created_by;
                    $contact->main_user_id = $main_user;
                    //if Hof is not self then contact type should be family member
                    if($family_member[0]!=0){
                        $contact->contact_type = 9;
                    }

                    //Upload Profile Image
                    if ($request->hasFile('profile_image')) {
                        $file     = $request->file('profile_image');
                        $fileName = 'profilePic-' . time() . '.' . $file->getClientOriginalExtension();
                        $path     = public_path('uploads/contacts/profile_image/');
                        $file->move($path, $fileName);
                        $contact->profile_pic = $fileName;
                    }
                    if ($contact->save()) {                       
                        if (!empty($contact_id)) {
                            ContactFamilyMember::where('contact_id', $contact_id)->delete();
                            $contactId = $contact_id;
                        } else {
                            $contactId = $contact->id;
                        }
                        //To Auto update if created new contact and back to prev contact
                        /*if($iscreate_new==0){
                            session(['sess_contact_id' => $contactId]);
                        }
                        if($iscreate_new==1){
                            $is_createhof=$request->input('create_hof');
                            $sess_contact_id = session('sess_contact_id');
                           
                            if($is_createhof==1){
                                $detail = ContactFamilyMember::where('contact_id', $sess_contact_id)->find();
                                $detail->update([
                                    'family_member_id' => $contactId,
                                    'hof_id' => $contactId,
                                ]);
                            }else{                     
                                $familydata = array(
                                    'contact_id' => $sess_contact_id,
                                    'family_member_id' => $contactId,
                                    'relationship' => '',
                                    'relationship_id' => '',
                                    'hof' => '',
                                    'hof_id' => $hof_id,
                                    'created_by' => $created_by
                                );
                                ContactFamilyMember::insert($familydata);
                            }
                        }*/

                        $i = 0;
                        if (is_array($family_member)) {
                            if ($contactId) {
                                $contact = Contact::find($contactId);
                                $contact->family_id = $contactId;
                                $contact->save();
                            }
                            foreach ($family_member as $key) {

                                if (!empty($family_member[$i]) || $family_member[$i] == '0') {
                                    if ($family_member[$i] == 0) {
                                        $familyMember = $contactId;
                                    } else {
                                        $familyMember = $family_member[$i];
                                    }
                                    if ($hof[$i] == 1) {
                                        $hof_id = $familyMember;
                                    }
                                    $contact = Contact::find($familyMember);
                                    $contact->family_id = $contactId;
                                    $contact->is_hof = $hof[$i];
                                    $contact->save();
                                    $explode_relationship= explode('-', $reletionship[$i]);
                                    if(is_array($explode_relationship)){
                                        $relationship_id =$explode_relationship[0];
                                        $relationship = isset($explode_relationship[1])?$explode_relationship[1]:''; 
                                    }else{
                                        $relationship_id ='';
                                        $relationship ='';
                                    }                                    

                                    $familydata = array(
                                        'contact_id' => $contactId,
                                        'family_member_id' => $familyMember,
                                        'relationship' => $relationship,
                                        'relationship_id' => $relationship_id,
                                        'hof' => $hof[$i],
                                        'hof_id' => $hof_id,
                                        'created_by' => $created_by
                                    );
                                    ContactFamilyMember::insert($familydata);

                                    //update contact HOF
                                    //If A HOF is self, B add A as family member then A HOF is B                    
                                    $family = ContactFamilyMember::where('contact_id', $family_member[$i])
                                    ->where('hof', '1')->first();

                                   
                                    if($family_member[0]!=0){
                                         //To check if B add HOF as A then B's hofid will be A's id but A's hof_id not updated as B's Id
                                        $existHof=  ContactFamilyMember::where('contact_id', $family_member[$i])
                                        ->where('family_member_id',  $family_member[$i])
                                        ->where('hof_id',  $family_member[$i])//To check if A HOF B then B cannot be the HOF of A
                                        ->where('hof', '1')->first();

                                        $familydata = array(
                                            'contact_id' => $familyMember,
                                            'family_member_id' => $contactId,
                                            'relationship' => $relationship,
                                            'relationship_id' => $relationship_id,
                                            'hof' => '0',
                                            'hof_id' => $familyMember,
                                            'created_by' => $created_by
                                        );
                                        ContactFamilyMember::insert($familydata);
                                    }else{
                                        $existHof =false;
                                    }                                   

                                    if ($family && !$existHof) {       
                                        $family->family_member_id = $contactId;
                                        $family->relationship = $relationship;
                                        $family->relationship_id = $relationship_id;
                                        $family->hof_id = $contactId;
                                        $family->save();

                                        //If a contact is mapped as Family Member of some other contact, then that previous contact's contact type should be automatically changed to "Family Member"

                                        $contact->contact_type = '9';
                                        $contact->save();
                                    }
                                }
                                $i++;
                            }
                        }
                    }
                }
                if ($step == 'contact') {
                    //$contact_id = $request->input('contact_id');
                    // $contact = Contact::find($contact_id);                   
                    
                    if (!empty($contact_id)) {
                        $contact = Contact::find($contact_id);
                    } else {
                        $contact = new Contact;
                        $contact->contact_unique_id= $contact_unique_id;
                        $contact->created_by = $created_by;
                        $contact->main_user_id = $main_user;
                    }
                    $assignee_ids=$request->input('assignee_id');
                    
                    $assignee_ids_string = isset($assignee_ids)?implode(',', $assignee_ids):'';
                    $contact->first_name = $request->input('first_name');
                    $contact->contact_type = $request->input('contact_type');                  
                    

                    $contact->mobile_code = $request->input('mobile_code');
                    $contact->mobile_number = $request->input('mobile_number');
                    $contact->lanline_code = $request->input('lanline_code');
                    $contact->land_number = $request->input('land_number');
                    $contact->email = $request->input('email');
                    $contact->referred_by = $request->input('referred_by_id');
                    $contact->residential_status = $request->input('residential_status');
                    //$contact->contact_type = $request->input('contact_type');
                    $contact->state_id = $request->input('state_id');
                    $contact->city_id = $request->input('city_id');
                    
                    $contact->address_one = $request->input('address_one');
                    $contact->address_two = $request->input('address_two');
                    $contact->pin_code = $request->input('pin_code');
                    
                    //$contact->addon_user_id = $request->input('assignee_id');
                    $contact->assignee_ids = $assignee_ids_string;                    
                    $contact->save();
                    $contactId = $contact->id;
                }
                if ($step == 'account') {
                    //$contact_id = $request->input('contact_id');
                    //$contact = Contact::find($contact_id);
                    if (!empty($contact_id)) {
                        $contact = Contact::find($contact_id);
                    } else {
                        $contact = new Contact;
                        $contact->contact_unique_id= $contact_unique_id;
                        $contact->created_by = $created_by;
                        $contact->main_user_id = $main_user;
                    }
                    $contact->first_name = $request->input('first_name');
                    $contact->contact_type = $request->input('contact_type');  
                    $contact->loyalty_rank = $request->input('loyalty_rank');
                    $contact->account_type = $request->input('account_type');                    
                    $contact->potential = $request->input('potential');
                    $contact->client_since = $request->input('client_since');
                    if ($contact->save()) {

                        if (!empty($contact_id)) {
                            ContactCustomTag::where('contact_id', $contact_id)->delete();
                            $contactId = $contact_id;
                        } else {
                            $contactId = $contact->id;
                        }

                        $tags = $request->input('tag');
                        if (!empty($tags)) {
                            foreach ($tags as $tag) {

                                $tagData = array(
                                    'contact_id' => $contactId,
                                    'custom_tag_id' => $tag,
                                    'created_by' => $created_by
                                );
                                ContactCustomTag::insert($tagData);
                            }
                        }
                    }
                    $contactId = $contact->id;
                }

                if ($step == 'investment') {
                    //$contact_id = $request->input('contact_id');
                    //$contact = Contact::find($contact_id);
                    if (!empty($contact_id)) {
                        $contact = Contact::find($contact_id);
                    } else {
                        $contact = new Contact;
                        $contact->contact_unique_id= $contact_unique_id;
                        $contact->created_by = $created_by;
                        $contact->main_user_id = $main_user;
                    }
                    $contact->first_name = $request->input('first_name');
                    $contact->contact_type = $request->input('contact_type'); 
                    if ($contact->save()) {

                        if (!empty($contact_id)) {
                            ContactInvestment::where('contact_id', $contact_id)->delete();
                            $contactId = $contact_id;
                        } else {
                            $contactId = $contact->id;
                        }
                        $schemes_name = $request->input('schemes_id');
                        $status = $request->input('status');
                        $remarks = $request->input('remarks');
                        if (!empty($schemes_name)) {
                            for ($i = 0; $i < count($schemes_name); $i++) {

                                $schemesData = array(
                                    'contact_id' => $contactId,
                                    'schemes_name' => $schemes_name[$i],
                                    'status' => $status[$i],
                                    'remarks'=>$remarks[$i],
                                    'created_by' => $created_by
                                );
                                ContactInvestment::insert($schemesData);
                            }
                        }
                    }
                }

                //Send creation mail to assigned user 
                $notification_module_id= 5;
                $notification_type= 3;
                $action_for= 2;
                $notification_title= 'create_notification';
            
                $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title);

                $inapp_notification_type= 1;      

                $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title);
                if($emailConfigStatus==1 || $inAppConfigStatus==1){
                    $this->notifyContactCreation($contact, $emailConfigStatus, $inAppConfigStatus); 
                }                                             

                //Activity
                $this->createLog($request,$contact);
                // $activityhtml = "<p>A Contact has been Created by <span class='blue-bld-txt'>" . $user_name . "</span></p>";
                // $activity = new ActivityLogs;
                // $activity->acitivity_description = $activityhtml;
                // $activity->main_user_id = $mainUserId;
                // $activity->save();
                $msg = 'Contact saved successfully!!';
                $status = 'success';
            }
        } catch (\Exception $e) {
            $msg = "Oops!! Something went wrong";
            $status = 'error';
        }
        return response()->json(['message' => $msg, 'status' => $status, 'contact_id' => $contactId, 'data'=>$data]);
    }

    /*****************************************************/
    # ContactController
    # Function name : notifyContactCreation()    
    # Purpose       :  Send inapp and email notification to  assignees about the creation of new contacts. 
    # Params        : $contact,$emailConfigStatus, $inAppConfigStatus
    /*****************************************************/
    public function notifyContactCreation($contact,$emailConfigStatus, $inAppConfigStatus){
              
        $assignee_ids= explode(',', $contact->assignee_ids);       
        
        if(!empty($assignee_ids[0]) ){         
                            
            $contactType =contactType::findOrFail($contact->contact_type);

            if($contact->referred_by!=''){
                $referredBy =Contact::Select('first_name', 'last_name')->where('id', $contact->referred_by)->first();
                $userData['refrrer_name']= $referredBy->first_name.' '.$referredBy->last_name;
            }else{
                $userData['refrrer_name'] ='';
            }
         
            $userData['assigned_team'] = User::whereIn('id', $assignee_ids)->pluck('name')->implode(', ');
            
                                
            $userData['contact_name'] = $contact->first_name.' '.$contact->last_name;
            $userData['contact_type'] = $contactType->name;     
            $userData['contact_email'] = $contact->email;                  
            $userData['createdBy'] = auth()->user()->name;
            $userData['dob'] =  $contact->dob; 
            
            $userData['loyalty_rank'] = $contact->loyalty_rank; 
            $account_type = DB::table('account_type')->select('name')->where('id',$contact->account_type)->first(); 
            $userData['account_type'] = !empty($account_type->name)?$account_type->name:'';
            $potential = DB::table('client_potentials')->select('name')->where('id',$contact->potential)->first();
            $userData['potential']= !empty($potential->name)?$potential->name:''; 
            /*$userData['tags'] = ContactCustomTag::query()->join('custom_tags', 'contacts_custom_tags.custom_tag_id', '=', 'custom_tags.id')
            ->select('custom_tags.custom_tag')            
            ->where('contacts_custom_tags.contact_id', $contact->id)
            ->get();*/
            
            $userData['address_one'] = $contact->address_one;
            $userData['address_two'] = $contact->address_two;
            $userData['client_since'] = $contact->client_since; 
            
           
            $userData['contactNumber'] = $contact->mobile_code.' '.$contact->mobile_number;

            
            $userData['address_one'] = $contact->address_one;
            $userData['address_two'] = $contact->address_two;

            if (preg_match('/^\d+$/', $contact->state_id)){
                $state= DB::table('states')->select('name')->where('id',$contact->state_id)->first();
                $state_name= $state->name;
            }else{
                $state_name= $contact->state_id;
            }
            if (preg_match('/^\d+$/', $contact->city_id)){
                $city= DB::table('cities')->select('city')->where('id',$contact->city_id)->first();
                $city_name= $city->city;
            }else{
                $city_name= $contact->city_id;
            }

            $userData['state'] = $state_name;
            $userData['city'] = $city_name;

            foreach($assignee_ids as $assignee){ 
                $assigneeDetails = User::findOrFail($assignee);                    
                $assigneeEmail = $assigneeDetails->email; 
                $userData['email'] = $assigneeEmail;    
                
                if($emailConfigStatus==1)  {
                    Mail::send('email_template.contact_creation_email', ['user' => $userData], function ($m) use ($userData) {
                        $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                        $m->to($userData['email'])->subject("M-Edge");
                    });
                }
              
                //inapp notification
                if($inAppConfigStatus==1){
                    $contact_name= $userData['contact_name'];

                    $createhtml = auth()->user()->name. " has assigned  a contact for you <$contact->contact_unique_id> - <$contact_name> on " .date('d-m-Y h:i:A').". Click on this to have a view & take actions on Contact Dashboard.";
                    $notification = new Notification;
                    $notification->title =$createhtml;
                    //$notification->module_type = 2;
                    //$notification->module_id = $details->id;
                    $notification->link = \URL::route('contacts');
                    $notification->user_id = $assignee ;
                    $notification->created_by = auth()->user()->id;
                    $notification->save();
                }
            }
        } 

        
    }
     

    public function createLog($request, $contact)
    {
        $user = auth()->user();
        $assigneeNames = '';
        if ($request->has('assignee_id') && count($request->input('assignee_id')) > 0 ) {
            foreach ($request->input('assignee_id') as $key => $assignee_id) {
                $assignee = User::find($assignee_id);
                if ($assignee) {
                    if ($assigneeNames == '') {
                        $assigneeNames = $assignee->name;
                    } else {
                        $assigneeNames .= ', ' . $assignee->name;
                    }
                }
            }
        }
        
        $contactName = $contact->first_name ." ".$contact->last_name;
        $activity = new ActivityLogs;
        $activity->acitivity_description = Helper::contactCreateMsg($user->name, $contact->contact_unique_id, $contactName, $assigneeNames);
        $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
        $activity->user_id = $user->id;
        $activity->contact_id = $contact->id;
        $activity->contact_module_case = 1;
        $activity->module = 5;
        $activity->save();
    }

    //Save Investment
   
    /*****************************************************/
    # ContactController
    # Function name : saveInvestment()    
    # Purpose       :  To save investment schemes, associating them with the authenticated user, and providing a response indicating the success of the operation along with the ID of the newly created investment.
    # Params        : Request $request
    /*****************************************************/
    
    public function saveInvestment(Request $request)
    {
        $schemes = $request->input('schemes');               
        $schemes_type = new OpportunityTypeAdmin;
        $schemes_type->name = $schemes;
        $schemes_type->created_by = auth()->user()->id;
        $schemes_type->save();   
        $investmentId = $schemes_type->id; 
        
        $msg = 'Schemes saved  successfully';
       
        return response()->json(['message' => $msg, 'investmentId' =>$investmentId]);
    }

    /*****************************************************/
    # ContactController
    # Function name : convert_contact()    
    # Purpose       :  To convert from lead to contact.
    # Params        : Request $request
    /*****************************************************/
    public function convert_contact(Request $request)
    {
        $lead_id = base64_decode( $request->input('lead_id'));      
        $lead_detail =  Lead::find($lead_id);
        $created_by = auth()->user()->id;
        $dob = $lead_detail->dob;
        $contact = new Contact;
        $contact->first_name = $lead_detail->first_name;
        $contact->last_name = $lead_detail->last_name;
        $contact->dob = $dob;
        $birthdate = Carbon::parse($dob);
        $contact->age = $birthdate->age;

        $contact->mobile_code = $lead_detail->mobile_code;
        $contact->mobile_number = $lead_detail->mobile_Numer;
        $contact->lanline_code = $lead_detail->land_line_code;
        $contact->land_number = $lead_detail->land_number;
        $contact->profile_pic = $lead_detail->profile_pic;
        $contact->email = $lead_detail->email;
        $contact->referred_by = $lead_detail->referred_by;
        $contact->contact_type = '1';
        $contact->city_id = $lead_detail->city_id;
        $contact->state_id = $lead_detail->state_id;
        $contact->main_user_id = $lead_detail->main_user_id;
        $contact->created_by = $created_by;
        $profilePic = $lead_detail->profile_pic;
        if ($profilePic != '') {
            $sourcePath = public_path('uploads/leads/profile_image/' . $profilePic);
            $destinationPath = public_path('uploads/contacts/profile_image/' . $profilePic);
            // Ensure the file exists
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destinationPath);
                $contact->profile_pic = $profilePic;
            }
        }
        $contact->save();
        $contactId = $contact->id;
        // if ($contactId) {
        //     $contact = Contact::find($contactId);
        //     $contact->family_id = $contactId;
        //     $contact->save();
        // }       
        if ($contactId) {
            $familydata = array(
                'contact_id' => $contactId,
                'family_member_id' => $contactId,
                'relationship' => 'self',
                'relationship_id' => 0,
                'hof' => '1',
                'hof_id' => $contactId,
                'created_by' => auth()->user()->id,
            );
            ContactFamilyMember::insert($familydata);
        }

        //Save lead convert date
        $lead_detail->customer_conversion_date = date('Y-m-d');
        $lead_detail->save();

        $this->convertCustomerLog($lead_detail);

        return response()->json(['success' => true, 'message'=> 'Lead Converted Successfully!']);
    }

    function convertCustomerLog($lead_detail)
    {
        $user = auth()->user();
        $activity = new ActivityLogs;
        $activity->acitivity_description = Helper::leadConvertCustomerMsg($user->name, $lead_detail);
        $activity->main_user_id =$user->added_by != 0 ? $user->added_by : $user->id;
        $activity->lead_module_case = 5;
        $activity->lead_id = $lead_detail->id;
        $activity->module = 4;
        $activity->user_id = $user->id;
        $activity->save();
    }

    /*****************************************************/
    # ContactController
    # Function name : getCities()    
    # Purpose       :   Fetch and return a list of cities based on the provided state ID in a JSON format.
    # Params        : Request $request
    /*****************************************************/

    public function getCities(Request $request)
    {
        $data['cities'] = DB::table('cities')
            ->where('state_id', $request->state_id)
            ->orderBy('city', 'ASC')
            ->get();
        return response()->json($data);
    }

    
    /*****************************************************/
    # ContactController
    # Function name : checkMobileNumber()    
    # Purpose       :  To validate whether a mobile number is unique among contacts in the database.
    # Params        : Request $request
    /*****************************************************/

    public function checkMobileNumber(Request $request)
    {
        $input = $request->all();
        //if ($input['page'] == 'edit') {
        $where = [
            ['mobile_number', Helper::cleanText($input['phone'])],
            ['id', '<>', Helper::cleanText($input['contactId'])],
        ];
        /*} else {
            $where = [
                ['mobile_number', Helper::cleanText($input['phone'])],
            ];
        }*/
        if (Contact::where($where)->exists()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : checkDuplicateEmail()    
    # Purpose       :  To ensure that when a new email is added  in the system, it doesn't conflict with existing email addresses.
    # Params        : Request $request
    /*****************************************************/

    public function checkDuplicateEmail(Request $request)
    {
        $input = $request->all();
        //if ($input['page'] == 'edit') {
        $where = [
            ['email', Helper::cleanText($input['email'])],
            ['id', '<>', Helper::cleanText($input['contactId'])],
            ['is_deleted', '!=', 1],
            ['email', '!=', ''],
            ['email', '!=', null],
        ];
        /*} else {
            $where = [
                ['email', Helper::cleanText($input['email'])],
            ];
        }*/
        if (Contact::where($where)->exists()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : contactNote()    
    # Purpose       :  Fetching and displaying contact notes for a specific contact.
    # Params        : Request $request, $id= null
    /*****************************************************/
    public function contactNote($id = null,  Request $request)
    {
        $data['page_title'] = "Client Notes";
        $data['is_backBtn'] = 1;
        $contact_id = base64_decode($id);
        $contactDetail = Contact::select('id', 'first_name', 'last_name', 'email', 'mobile_code', 'mobile_number', 'profile_pic', 'contact_type')->where('id', $contact_id)->get();
        $data['notes'] = ContactNotes::where('contact_id', $contact_id)->get();
        // print_r( $data['notes']);exit;

        return view('frontend.contact.contact_notes', compact('contactDetail'), $data);
    }
    
    /*****************************************************/
    # ContactController
    # Function name : saveNotes()    
    # Purpose       :  Saving contact notes 
    # Params        : Request $request
    /*****************************************************/
    public function saveNotes(Request $request)
    {

        $created_by = auth()->user()->id;

        try {
            $contact_id = $request->input('contact_id');
            //$contact = new ContactNotes;
            //$label = 'custom fields';
            $notes = $request->input('notes');
            $notesLabels = $request->input('notesLabel');
            $notesArr = json_decode($request->notesArr);
            if (!empty($notes)) {
                $contact = Contact::find($contact_id);
                $oldContactNotes = ContactNotes::where('contact_id', $contact_id)->get();
                $html = '';

                ContactNotes::where('contact_id', $contact_id)->delete();
                
                // track which item is update and delete
                if (count($oldContactNotes) > 0) {
                    foreach ($oldContactNotes as $key => $oldNote) {
                        $is_match = 0;
                        foreach ($notesArr as $key => $note) {
                            // dd($note, $oldNote);
                            if ($note->id == $oldNote->id) {
                                $is_match = 1;
                                if ($note->label != $oldNote->label) {
                                    $html .= "<li> $oldNote->label -> $note->label</li>";
                                }

                                if ($note->notes != $oldNote->notes) {
                                    $html .= "<li> $oldNote->notes -> $note->notes</li>";
                                }
                            }
                        }
                        if ($is_match == 0) {
                            $html .= "<li> $oldNote->label is deleted</li>";
                            $html .= "<li> $oldNote->notes is deleted</li>";
                        }
                    }
                }
                if (count($notesArr) > 0) {
                    foreach ($notesArr as $key => $note) {
                        if ($note->id == '') {
                            $html .= "<li> $note->label is created </li>";
                            $html .= "<li> $note->notes is created </li>";
                        }
                    }
                }
                
                for ($i = 0; $i < count($notes); $i++) {
                    $notesData = array(
                        'contact_id' => $contact_id,
                        'label' => $notesLabels[$i],
                        'notes' => $notes[$i],
                        'created_by' => $created_by
                    );

                    ContactNotes::insert($notesData);
                }

                if ($html != '') {
                    $this->notesActivityLog($contact, $html);
                }
            }
            $msg = 'Contact notes saved successfully!!';
            $status = 'success';
        } catch (\Exception $e) {
        $msg = "Oops!! Something went wrong";
        $status = 'error';
        }
        return response()->json(['message' => $msg, 'status' => $status]);
    }

    public function notesActivityLog($contact, $html)
    {
        $user = auth()->user();
        $activity = new ActivityLogs;
        $activity->acitivity_description = Helper::contactNotesMsg($user->name, $contact, $html);
        $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
        $activity->user_id = $user->id;
        $activity->contact_id = $contact->id;
        $activity->contact_module_case = 3;
        $activity->module = 5;
        $activity->save();

    }

    /*****************************************************/
    # ContactController
    # Function name : editContact()    
    # Purpose       : Set up the data for displaying a form to edit contact.
    # Params        : Request $request, $id=null
    /*****************************************************/

    public function editContact($id = null,  Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $contact_id = base64_decode($id);
        $data['page_title'] = "Edit contact";
        $data['is_backBtn'] = 1;
        $data['previousURL'] = $request->headers->get('referer');
        if (strpos($data['previousURL'], 'add-contacts') !== false) {
            $data['previousURL'] = null;
        }
        $data['addedContacts'] = Contact::select('id', 'first_name', 'last_name')->where('created_by', $user_id)->get();
        $data['contact'] = Contact::where('contacts.id', $contact_id)->get();
        $data['referred_by'] = Contact::select('id', 'first_name', 'last_name')->where('contacts.id', $data['contact'][0]->referred_by)->get();

        $data['investment_status'] = InvestmentStatus::where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->get();
       
        $data['opprtunity_type'] = OpportunityTypeAdmin::where('is_deleted',0)
        ->where('status',1)
        ->whereIn('created_by',[0,$user_id])
        ->orderBy('row_order','ASC')->latest()->get();
        
      
        // $data['tags'] = ContactCustomTag::select('id', 'custom_tag_id')->where('contact_id', $contact_id)->get();
        $data['tags'] = ContactCustomTag::query()->join('custom_tags', 'contacts_custom_tags.custom_tag_id', '=', 'custom_tags.id')
            ->select('contacts_custom_tags.*', 'custom_tags.custom_tag')
            ->where('custom_tags.is_deleted', 0)
            ->where('contacts_custom_tags.contact_id', $contact_id)
            ->get();
        $data['custom_tags'] = CustomTag::where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$user_id])->get();
        //There will not be any use case of converting HOF to Family Member
        $data['contactType'] = ContactType::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['loyaltyRank'] = LoyaltyRank::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['accountType'] = AccountType::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();
        $data['clientPotential'] = ClientPotential::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();

        $data['relationships'] = Relationship::where('is_deleted', 0)->where('status', 1)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();

        $data['investments'] = ContactInvestment::select('id', 'schemes_name', 'status','remarks')->where('contact_id', $contact_id)->get();

        $data['cities'] = DB::table('cities')
            ->where('state_id', $data['contact'][0]->state_id)
            ->orderBy('city', 'ASC')
            ->get();
        //$data['contact_family'] = ContactFamilyMember::with('contact:first_name')->where('contact_id', $contact_id)->get();
        $data['contact_family'] = ContactFamilyMember::with(['contact' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'profile_pic')
            ->where('contacts.is_deleted', 0);
        }])
            ->where('contact_id', $contact_id)
            ->where('is_deleted', 0)
            ->get();
        

        $data['states'] = DB::table('states')->get();
        $data['country_code']= CountryCode::get();      
        //$data['add_on_users'] = User::where('added_by', $user_id)->where('is_deleted', 0)->where('status', 1)->get();
        $data['add_on_users'] =$this->searchUser();
       
        return view('frontend.contact.edit_contact', $data);
    }

   
    /*****************************************************/
    # ContactController
    # Function name : updateContact()    
    # Purpose       :  Update the contact's information provided in the request, categorizing it into sections such as Personal, Contact, Account, and Investment.
    # Params        : Request $request
    /*****************************************************/

    public function updateContact(Request $request)
    {
        $created_by = auth()->user()->id;
        $contact_id = $request->input('contact_id');
        $step = $request->input('steps');
        //$previousURL = $request->input('previousURL');
        $data['previousURL']= $request->input('previousURL');
        $oldContact = Contact::select('first_name', 'last_name', 'email','mobile_number','mobile_code', 'contact_type','dob')->find($contact_id);

        $userDetails = User::findOrFail($created_by);
        $user_name = $userDetails->name;
        $hof_id = '';
        if ($userDetails->added_by == 0) {
            $mainUserId = $created_by;
        } else {
            $mainUserId = $userDetails->added_by;
        }

        $oldContact = json_decode(json_encode(Contact::find($contact_id)));
        $oldContact_family = ContactFamilyMember::with(['contact' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'profile_pic')
            ->where('contacts.is_deleted', 0);
        }])
            ->where('contact_id', $contact_id)
            ->where('is_deleted', 0)
        ->get();
        
        $oldContactInvestment = ContactInvestment::with(['opportunity_type','investment_status'])->where('contact_id', $contact_id)->get();

        $oldContactCustomTag = ContactCustomTag::join('custom_tags', 'custom_tags.id', '=', 'contacts_custom_tags.custom_tag_id')
        ->where('contacts_custom_tags.contact_id', $contact_id)
        ->get('custom_tags.custom_tag AS custom_tag')->pluck('custom_tag')->toarray();
        $oldContactCustomTag = implode(',',$oldContactCustomTag);

        try {
            if ($step == 'personal') {
               
                $reletionship = $request->input('relation');
                // print_r($reletionship);exit;
                $family_member_id = $request->input('family_member_id');
                $hof = $request->input('hof');
                $dob = $request->input('dob');
                $contact = Contact::find($contact_id);
                $contact->first_name = $request->input('first_name');
                $contact->last_name = $request->input('last_name');
                if (!empty($dob)) {
                    $date = str_replace('/', '-', $dob);
                    $contact->dob  = date("Y-m-d", strtotime($date));
                }else{
                    $contact->dob  = NULL; 
                }
                $contact->age = $request->input('age');
                $contact->contact_type = $request->input('contact_type');
                $fileName = '';
                 //if Hof is not self then contact type should be family member
               /* if($family_member_id[0]!=$contact_id){
                    $contact->contact_type = 9;
                }else{
                    $contact->contact_type = 1;  
                }*/

                //Upload Profile Image
                if ($request->hasFile('profile_image')) {
                    $file     = $request->file('profile_image');
                    $fileName = 'profilePic-' . time() . '.' . $file->getClientOriginalExtension();
                    $path     = public_path('uploads/contacts/profile_image/');

                    if ($file->move($path, $fileName)) {
                        //delete previous profile image
                        if (!empty($contact->profile_pic) && file_exists($path . $contact->profile_pic)) {
                            unlink($path . $contact->profile_pic);
                        }
                    }
                } else if (!empty($contact->profile_pic)) {
                    $fileName = $contact->profile_pic;
                }
                $contact->profile_pic = $fileName;

                if ($contact->save()) {
                    $family_members = ContactFamilyMember::where('contact_id', $contact_id)->get();
                    if ($family_members->count() > 0) {
                        foreach ($family_members as $family_member) {
                            $contact = Contact::find($family_member->family_member_id);
                            $contact->family_id = 0;
                            $contact->is_hof = 0;
                            $contact->save();
                        }
                    }

                    ContactFamilyMember::where('contact_id', $contact_id)->delete();

                    $i = 0;

                    if (is_array($family_member_id)) {

                        $contact = Contact::find($contact_id);
                        $contact->family_id = $contact_id;
                        $contact->is_hof = 0;
                        $contact->save();

                        foreach ($family_member_id as $key) {
                            if (!empty($family_member_id[$i])) {
                                /*if (!empty($reletionship[$i])) {
                                    $reletionship = $reletionship[$i];
                                } else {
                                    $reletionship = '';
                                }*/
                                if ($hof[$i] == 1) {
                                    $hof_id = $family_member_id[$i];
                                }
                                $explode_relationship= explode('-', $reletionship[$i]);
                                $relationship_id =$explode_relationship[0];
                                $relationship =$explode_relationship[1];
                                $familydata = array(
                                    'contact_id' => $contact_id,
                                    'family_member_id' => $family_member_id[$i],
                                    'relationship' => $relationship,
                                    'relationship_id' =>$relationship_id,
                                    'hof' => $hof[$i],
                                    'hof_id' => $hof_id,
                                    'created_by' => $created_by
                                );
                                ContactFamilyMember::insert($familydata);

                                $contact = Contact::find($family_member_id[$i]);
                                $contact->family_id = $contact_id;
                                $contact->is_hof = $hof[$i];
                               /* if($contact->id!=$contact_id){
                                    $contact->contact_type = 9;
                                } */                               
                                $contact->save();
                                //update contact HOF    
                                //$this->updateHOF($family_member_id[$i], $contact_id,$relationship,$relationship_id);   
                                //If A HOF is self, B add A as family member then A HOF is B                    
                                $family = ContactFamilyMember::where('contact_id', $family_member_id[$i])
                                ->where('hof', '1')->first();

                                //To Check contact HOF is not self
                                if($family_member_id[0]!= $contact_id){
                                    //To check if B add HOF as A then B's hofid will be A's id but A's hof_id not updated as B's Id
                                    $existHof=  ContactFamilyMember::where('contact_id', $family_member_id[$i])
                                    ->where('family_member_id',  $family_member_id[$i])
                                    ->where('hof_id',  $family_member_id[$i])//To check if A HOF B then B cannot be the HOF of A
                                    ->where('hof', '1')->first();

                                    $familydata = array(
                                        'contact_id' => $family_member_id[$i],
                                        'family_member_id' => $contact_id,
                                        'relationship' => $relationship,
                                        'relationship_id' => $relationship_id,
                                        'hof' => '0',
                                        'hof_id' => $family_member_id[$i],
                                        'created_by' => $created_by
                                    );
                                    ContactFamilyMember::insert($familydata);
                                }else{
                                    $existHof =false;
                                }                                   

                                if ($family && !$existHof) {       
                                    $family->family_member_id = $contact_id;
                                    $family->relationship = $relationship;
                                    $family->relationship_id = $relationship_id;
                                    $family->hof_id = $contact_id;
                                    $family->save();

                                    //If a contact is mapped as Family Member of some other contact, then that previous contact's contact type should be automatically changed to "Family Member"
                                    if($family_member_id[$i]!= $contact_id){
                                        $contact->contact_type = '9';
                                        $contact->save();
                                    }                                   
                                }                       
                               
                            }
                            $i++;
                        }
                    }
                }
            }
            if ($step == 'contact') {
                $contact = Contact::find($contact_id);
                $assignee_ids=$request->input('assignee_id');             
                $assignee_ids_string = isset($assignee_ids)?implode(',', $assignee_ids):'';  
                
                $contact->first_name = $request->input('first_name');
                $contact->contact_type = $request->input('contact_type'); 

                $contact->mobile_code = $request->input('mobile_code');
                $contact->mobile_number = $request->input('mobile_number');
                $contact->lanline_code = $request->input('lanline_code');
                $contact->land_number = $request->input('land_number');
                $contact->email = $request->input('email');
                $contact->referred_by = $request->input('referred_by_id');
                $contact->residential_status = $request->input('residential_status');
                //$contact->contact_type = $request->input('contact_type');
                $contact->state_id = $request->input('state_id');
                $contact->city_id = $request->input('city_id');
                $contact->address_one = $request->input('address_one');
                $contact->address_two = $request->input('address_two');
                $contact->pin_code = $request->input('pin_code');
                //$contact->addon_user_id = $request->input('assignee_id');
                $contact->assignee_ids = $assignee_ids_string;
                $contact->save();
            }

            if ($step == 'account') {
                $contact = Contact::find($contact_id);
                $contact->first_name = $request->input('first_name');
                $contact->contact_type = $request->input('contact_type');
                $contact->loyalty_rank = $request->input('loyalty_rank');
                $contact->account_type = $request->input('account_type'); 
                $contact->potential = $request->input('potential');
                $contact->client_since = $request->input('client_since');
                if ($contact->save()) {
                    ContactCustomTag::where('contact_id', $contact_id)->delete();
                    $tags = $request->input('tag');
                    if (!empty($tags)) {
                        foreach ($tags as $tag) {

                            $tagData = array(
                                'contact_id' => $contact_id,
                                'custom_tag_id' => $tag,
                                'created_by' => $created_by
                            );
                            ContactCustomTag::insert($tagData);
                        }
                    }
                }
            }

            if ($step == 'investment') {
                $contact = Contact::find($contact_id);   
                $contact->first_name = $request->input('first_name');
                $contact->contact_type = $request->input('contact_type');             
                if ($contact->save()) {
                    ContactInvestment::where('contact_id', $contact_id)->delete();
                    $schemes_name = $request->input('schemes_id');
                  //  print_r($schemes_name);exit;
                    $status = $request->input('status');
                    $remarks= $request->input('remarks');
                    if (!empty($schemes_name)) {
                        for ($i = 0; $i < count($schemes_name); $i++) {
                            $schemesData = array(
                                'contact_id' => $contact_id,
                                'schemes_name' => $schemes_name[$i],
                                'status' => $status[$i],
                                'remarks' => $remarks[$i],
                                'created_by' => $created_by
                            );
                            ContactInvestment::insert($schemesData);
                        }
                    }
                }
            }

            //Notify modification  to assigned user 
            $notification_module_id= 5;
            $notification_type= 3;
            $action_for= 2;
            $notification_title= 'edit_notification';
        
            $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title);

            $inapp_notification_type= 1;      

            $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title);
            if($emailConfigStatus==1 || $inAppConfigStatus==1){
                // invast notifiy amit
                $this->notifyModificationContact($contact, $oldContact,$emailConfigStatus,$inAppConfigStatus, $oldContactInvestment); // add $oldContactInvestment  
                // invast notifiy amit xxx
            }             

            // $activityhtml = "<p>A Contact has been Updated by <span class='blue-bld-txt'>" . $user_name . "</span></p>";
            // $activity = new ActivityLogs;
            // $activity->acitivity_description = $activityhtml;
            // $activity->main_user_id = $mainUserId;
            // $activity->save();
            $contact = json_decode(json_encode(Contact::find($oldContact->id)));
            // active log
            $this->updateLog($oldContact, $contact, $oldContactCustomTag, $oldContactInvestment, $contact_id, $oldContact_family);
            $msg = 'Contact saved successfully!!';
            $status = 'success';

            $msg = 'Contact saved successfully!!';
            $status = 'success';
        } catch (\Exception $e) {
            $msg = "Oops!! Something went wrong";
            $status = 'error';
        }
        return response()->json(['message' => $msg, 'status' => $status, 'data' =>$data]);
    }

    public function updateLog($oldContact, $contact, $oldContactCustomTag, $oldContactInvestment, $contact_id, $oldContact_family)
    {
        $update_hof_status = 0;
        $update_Investment_status = 0;

        $contact_family = ContactFamilyMember::with(['contact' => function ($query) {
                        $query->select('id', 'first_name', 'last_name', 'profile_pic')
                        ->where('contacts.is_deleted', 0);
                    }])
                        ->where('contact_id', $contact_id)
                        ->where('is_deleted', 0)
                        ->get();
        if (isset($contact_family) && isset($oldContact_family) ) {
            # check contact_family & oldContact_family are not null
            if (count($contact_family) > 0 && count($oldContact_family) > 0) {
                if ( $contact_family[0]->contact->id != $oldContact_family[0]->contact->id ) {
                    $full_name = $contact_family[0]->contact->full_name;
                    $old_full_name = $oldContact_family[0]->contact->full_name;
                    $update_hof_status = 1;
                }
            }
        }
    
        $contactInvestment = ContactInvestment::with(['opportunity_type','investment_status'])->where('contact_id', $contact_id)->get();
        // dd($contactInvestment->toArray(), $oldContactInvestment->toArray());
        $investmentStr = '';
        foreach ($contactInvestment as $key => $ci) {
            $is_match = 0;
            foreach ($oldContactInvestment as $key => $oci) {
                if ($oci->schemes_name == $ci->schemes_name) {

                    if ($oci->status != $ci->status) 
                    {
                        $update_Investment_status = 1;
                        $investmentStr .= 'Status : '.$oci->opportunity_type->name .'scheme status '. $oci->investment_status->name .'->'. $ci->investment_status->name;
                        $investmentStr .= "<br>";
                    }
    
                    if ($oci->remarks != $ci->remarks) 
                    {
                        $update_Investment_status = 1;
                        $investmentStr .= 'Remarks : '.$oci->opportunity_type->name .'scheme remarks '. $oci->remarks .'->'. $ci->remarks;
                        $investmentStr .= "<br>";
                    }
                    $is_match = 1;
                }
        
            }
            if ($is_match == 0) {
                $update_Investment_status = 1;
                $investmentStr .= 'New '. $ci->opportunity_type->name .' schame is added. Status is '. $ci->investment_status->name .' and Remaks is '. $ci->remarks;
        
                $investmentStr .= "<br>";
            }
        }

        $contactCustomTag = ContactCustomTag::join('custom_tags', 'custom_tags.id', '=', 'contacts_custom_tags.custom_tag_id')
        ->where('contacts_custom_tags.contact_id', $contact_id)
        ->get('custom_tags.custom_tag AS custom_tag')->pluck('custom_tag')->toarray();
        $contactCustomTag = implode(',',$contactCustomTag);

        $user = auth()->user();

        if (
            $oldContact->first_name         == $contact->first_name &&
            $oldContact->last_name          == $contact->last_name &&
            $oldContact->dob                == $contact->dob &&
            $oldContact->contact_type       == $contact->contact_type &&
            $oldContact->mobile_code        == $contact->mobile_code && 
            $oldContact->mobile_number      == $contact->mobile_number &&
            $oldContact->lanline_code       == $contact->lanline_code &&
            $oldContact->land_number        == $contact->land_number &&
            $oldContact->email              == $contact->email &&
            $oldContact->referred_by        == $contact->referred_by &&
            $oldContact->residential_status == $contact->residential_status &&
            $oldContact->state_id           == $contact->state_id &&
            $oldContact->city_id            == $contact->city_id &&
            $oldContact->address_one        == $contact->address_one &&
            $oldContact->address_two        == $contact->address_two &&
            $oldContact->pin_code           == $contact->pin_code &&
            $oldContact->assignee_ids       != $contact->assignee_ids &&
            $oldContact->loyalty_rank       == $contact->loyalty_rank  &&
            $oldContact->account_type       == $contact->account_type &&
            $oldContact->potential          == $contact->potential &&
            $oldContact->client_since       == $contact->client_since &&
            $oldContactCustomTag            == $contactCustomTag &&
            $update_Investment_status       == 0 &&
            $update_hof_status == 0
        ) {

            $data = $this->getAssign1AndAssign2($contact, $oldContact);
            $assignee1 = $data['assignee1'];
            $assignee2 = $data['assignee2'];
            $contactName = $data['contactName'];
            
            $activity = new ActivityLogs;
            $activity->acitivity_description = Helper::contactAssigneeMsg($user->name, $contact->contact_unique_id, $contactName, $assignee1, $assignee2);
            $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
            $activity->user_id = $user->id;
            $activity->contact_id = $contact->id;
            $activity->contact_module_case = 2;
            $activity->module = 5;
            $activity->save();

        } else {
            $html = "";
            if ($oldContact->first_name != $contact->first_name) {
                $html .= "<li> First name : $oldContact->first_name -> $contact->first_name </li>";
            }
            if ($oldContact->last_name != $contact->last_name) {
                $html .= "<li>Last name : $oldContact->last_name -> $contact->last_name </li>";
            }
            if ($oldContact->dob != $contact->dob) {
                $html .= "<li> Date of Birth : $oldContact->dob -> $contact->dob </li>";
            }
            if ($oldContact->contact_type != $contact->contact_type) {
                //$html .= "<li> Type of Contact : $oldContact->contact_type -> $contact->contact_type </li>";

                $contact_type1 = DB::table('contact_types')->where('id', $oldContact->contact_type)->first();
                $contact_type2 = DB::table('contact_types')->where('id', $contact->contact_type)->first();
                $contact_type1Name = '';
                $contact_type2Name = '';
                if ($contact_type1) {
                    $contact_type1Name = $contact_type1->name;
                }
                if ($contact_type2) {
                    $contact_type2Name = $contact_type2->name;
                }
                $html .= "<li> Type of Contact : $contact_type1Name -> $contact_type2Name </li>";
            }
            // Hof 
            if ($update_hof_status == 1) {
                $html .= "<li> HOF name : $old_full_name -> $full_name </li>";
            }
            if (($oldContact->mobile_code != $contact->mobile_code) || ($oldContact->mobile_number != $contact->mobile_number)) {
                $html .= "<li> Mobile Number : $oldContact->mobile_code $oldContact->mobile_number -> $contact->mobile_code $oldContact->mobile_number </li>";
            }
            if (($oldContact->lanline_code != $contact->lanline_code) || ($oldContact->land_number != $contact->land_number)) {
                $html .= "<li> Landline Number : $oldContact->lanline_code $oldContact->land_number -> $contact->lanline_code $oldContact->land_number </li>";
            }
            if ($oldContact->email != $contact->email) {
                $html .= "<li> Email ID : $oldContact->email -> $contact->email </li>";
            }
            if ($oldContact->state_id != $contact->state_id) {
                $states1 = DB::table('states')->where('id', $oldContact->state_id)->first();
                $states2 = DB::table('states')->where('id', $contact->state_id)->first();
                $states1Name = '';
                $states2Name = '';
                if ($states1) {
                    $states1Name = $states1->name;
                }
                if ($states2) {
                    $states2Name = $states2->name;
                }
                if ($states2Name != '') {
                    $html .= "<li> State : $states1Name -> $states2Name </li>";
                }
            }
            if ($oldContact->city_id != $contact->city_id) {
                $city1 = DB::table('cities')->where('id', $oldContact->city_id)->first();
                $city2 = DB::table('cities')->where('id', $contact->city_id)->first();
                $city1Name = '';
                $city2Name = '';
                if ($city1) {
                    $city1Name = $city1->city;
                }
                if ($city2) {
                    $city2Name = $city2->city;
                }
                if ($city2Name != '') {
                    $html .= "<li> State : $city1Name -> $city2Name </li>";
                }
            }
            if ($oldContact->address_one != $contact->address_one) {
                $html .= "<li> Address 1 : $oldContact->address_one -> $contact->address_one </li>";
            }
            if ($oldContact->address_two != $contact->address_two) {
                $html .= "<li> Address 2 : $oldContact->address_two -> $contact->address_two </li>";
            }
            if ($oldContact->pin_code != $contact->pin_code) {
                $html .= "<li> Pincode : $oldContact->pin_code -> $contact->pin_code </li>";
            }
            if ($oldContact->assignee_ids != $contact->assignee_ids) {
                $data = $this->getAssign1AndAssign2($contact, $oldContact);
                $assignee1 = $data['assignee1'];
                $assignee2 = $data['assignee2'];
                $contactName = $data['contactName'];
                $html .= "<li> Assign Team : $assignee1 -> $assignee2 </li>";
            }
            if ($oldContact->loyalty_rank  != $contact->loyalty_rank) {
                $loyalty_rank1 = DB::table('loyalty_rank')->where('id', $oldContact->loyalty_rank )->first('name');
                $loyalty_rank_name1 = $loyalty_rank1 != null ? $loyalty_rank1->name : '';

                $loyalty_rank2 = DB::table('loyalty_rank')->where('id', $contact->loyalty_rank )->first('name');
                $loyalty_rank_name2 = $loyalty_rank2 != null ? $loyalty_rank2->name : '';

                $html .= "<li> Loyalty Rank : $loyalty_rank_name1 -> $loyalty_rank_name2 </li>";
            }
            if ($oldContact->account_type  != $contact->account_type) {
                $account_type1 = DB::table('account_type')->where('id', $oldContact->account_type )->first('name');
                $account_type_name1 = $account_type1 != null ? $account_type1->name : '';

                $account_type2 = DB::table('account_type')->where('id', $contact->account_type )->first('name');
                $account_type_name2 = $account_type2 != null ? $account_type2->name : '';

                $html .= "<li> Type : $account_type_name1 -> $account_type_name2 </li>";
            }
            if ($oldContact->potential  != $contact->potential) {
                // client_potentials
                $client_potentials1 = DB::table('client_potentials')->where('id', $oldContact->potential )->first('name');
                $client_potentials_name1 = $client_potentials1 != null ? $client_potentials1->name : '';

                $client_potentials2 = DB::table('client_potentials')->where('id', $contact->potential )->first('name');
                $client_potentials_name2 = $client_potentials2 != null ? $client_potentials2->name : '';

                $html .= "<li> Potential of Client : $client_potentials_name1 -> $client_potentials_name2 </li>";
            }
            if ($oldContact->client_since  != $contact->client_since) {
                $html .= "<li> Client Since (year) : $oldContact->client_since -> $contact->client_since </li>";
            }
            if ($oldContactCustomTag != $contactCustomTag) {
                $html .= "<li> Custom Tags : $oldContactCustomTag -> $contactCustomTag </li>";
            }
            if ($update_Investment_status == 1) {
                $html .= "<li> Scheme : $investmentStr </li>";
            }

            if ($html != '') {

                $activity = new ActivityLogs;
                $activity->acitivity_description = Helper::contactUpdateMsg($user->name, $contact->contact_unique_id, $html);
                $activity->main_user_id = $user->added_by != 0 ? $user->added_by : $user->id;
                $activity->user_id = $user->id;
                $activity->contact_id = $contact->id;
                $activity->contact_module_case = 4;
                $activity->module = 5;
                $activity->save();
            }
        }

    
    }

    public function  getAssign1AndAssign2($contact, $oldContact) {
        $assignee1 = "";
        $assignee2 = "";
        $contactName = $contact->first_name ." ".$contact->last_name;

        if ($oldContact->assignee_ids == null) {
            $assignee1 = "unassignee";
            $assignArr = explode(',', $contact->assignee_ids);
            foreach ($assignArr as $key => $value) {
                $assignData = User::find($value);
                if ($assignData) {
                    if ($assignee2 == "") {
                        $assignee2 = $assignData->name;
                    } else {
                        $assignee2 .= ", " . $assignData->name;
                    }
                    
                }
            }
        } else {
            $assignArr = explode(',', $oldContact->assignee_ids);
            foreach ($assignArr as $key => $value) {
                $assignData = User::find($value);
                if ($assignData) {
                    if ($assignee1 == "") {
                        $assignee1 = $assignData->name;
                    } else {
                        $assignee1 .= ", " . $assignData->name;
                    }
                    
                }
            }
            $assignArr = explode(',', $contact->assignee_ids);
            foreach ($assignArr as $key => $value) {
                $assignData = User::find($value);
                if ($assignData) {
                    if ($assignee2 == "") {
                        $assignee2 = $assignData->name;
                    } else {
                        $assignee2 .= ", " . $assignData->name;
                    }
                    
                }
            }
        }

        return [
            'contactName' => $contactName,
            'assignee1' => $assignee1,
            'assignee2' => $assignee2,
        ];

    }

    //To Update HOF
    /*public function updateHOF($family_member_id, $contact_id,$relationship,$relationship_id){       
        $family = ContactFamilyMember::where('contact_id', $family_member_id)->where('hof', '1')->first();
        
        //To check if HOF is already                        
        $is_exist_hof = ContactFamilyMember::where('family_member_id', $family_member_id)->where('contact_id', $contact_id)->where('hof_id', $family_member_id)->where('hof', '1')->first();
      
         if ($family && empty($is_exist_hof)) {
             $family->family_member_id = $contact_id;
             $family->relationship = $relationship;
             $family->relationship_id = $relationship_id;
             $family->hof_id = $contact_id;
             $family->save();
         }
    }*/

   
    /*****************************************************/
    # ContactController
    # Function name : searchReferrredBy()    
    # Purpose       :   Search contacts i.e created by loggedin user, 
    # Params        : Request $request
    /*****************************************************/ 
    public function searchReferrredBy(Request $request)
    {
        $created_by = auth()->user()->id;
        $current_contact_id = isset($request->current_contact_id) ? $request->current_contact_id : '';

        //$contactData = Contact::select("id", 'profile_pic', DB::raw("CONCAT(first_name, ' ', last_name) as value"))

        $contactData =  Contact::select("id", 'profile_pic', 'mobile_code', 'mobile_number', DB::raw("IF(last_name = '' OR last_name IS NULL, first_name, CONCAT(first_name, ' ', last_name)) as value"))
            ->where('created_by', $created_by)
            ->where('id', '!=', $current_contact_id)
            ->where('is_deleted', 0)
            ->where(function ($query) use ($request) {
                //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE', '%' . $request->search . '%');
                $searchText = explode(' ', trim($request->search));
                $firstWord = $searchText[0] . '%';
                $query->where('contacts.first_name', 'LIKE', $firstWord);
            })
            ->get();
        return response()->json($contactData);
    }

    /*****************************************************/
    # ContactController
    # Function name : searchFamily()    
    # Purpose       :   Search contact for Main User: All created contact + add on user's contact+ assigned contact. For Add on user: All created contact + assigned contact. 
    # Params        : Request $request
    /*****************************************************/ 
    public function searchFamily(Request $request)
    {
        $user_id = auth()->user()->id;
        $current_contact_id = isset($request->current_contact_id) ? $request->current_contact_id : '';

        $contactData =  Contact::select("id", 'profile_pic', 'mobile_code', 'mobile_number','contact_type', DB::raw("IF(last_name = '' OR last_name IS NULL, first_name, CONCAT(first_name, ' ', last_name)) as value"))
            // ->where('created_by', $created_by)           
            ->where(function ($q) use ($request,$user_id) {
                if (auth()->user()->added_by == 0) {
                    $q->where('main_user_id', auth()->user()->id);
                } else {
                    $q->where('created_by', auth()->user()->id);
                }
                $q->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
            })
            ->where('id', '!=', $current_contact_id)
            ->where('is_deleted', 0)
            ->where(function ($query) use ($request) {
               
                $query->where(DB::raw('CONCAT(first_name, " ", IFNULL(last_name, ""))'), 'LIKE', '%' . $request->search . '%');
              
               /* $searchText = explode(' ', trim($request->search));
                $firstWord = $searchText[0] . '%';
                $query->where('contacts.first_name', 'LIKE', $firstWord);*/
            })
            ->get();
        return response()->json($contactData);
    }

    /*****************************************************/
    # ContactController
    # Function name : getCustomTag()    
    # Purpose       :  Fetch custom tags from the database based on a provided search term and return them as JSON.. 
    # Params        : Request $request
    /*****************************************************/ 
    
    public function getCustomTag(Request $request)
    {
        $customTag = CustomTag::select("id", 'custom_tag as value')
            ->where('is_deleted', '=', 0)
            ->where('status', '=', 1)
            ->where(function ($query) use ($request) {
                $query->where('custom_tag', 'LIKE',  $request->search . '%');
            })
            ->get();
        return response()->json($customTag);
    }

    
   /*****************************************************/
    # ContactController
    # Function name : csvToArray()    
    # Purpose       :  Parse CSV data into an associative array. 
    # Params        : $filename: Specifies the name of the CSV file to read from.$delimiter: Specifies the delimiter character used in the CSV file, defaulting to a comma (,).
    /*****************************************************/ 
    
    function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {

                // Skip rows that start with #
                if (isset($row[0]) && substr(trim($row[0]), 0, 1) === "#") {
                    continue;
                }
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }


    //Import CSV
    public function importCsv_bckup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Oops! Something went wrong', 'status' => 'error']);
        }
        $file = $request->file('csv_file');

        $customerArr = $this->csvToArray($file);
        $failedRows = [];
        $successfulInserts = 0;

        for ($i = 0; $i < count($customerArr); $i++) {

            $validator = Validator::make(
                [
                    'email' => $customerArr[$i]['email'],
                    'mobile_number' => $customerArr[$i]['mobile_number']
                ],
                [
                    'email' => 'required|unique:contacts,email',
                    'mobile_number' => 'required|unique:contacts,mobile_number',
                ]
            );

            if (!$validator->fails()) {
                $dateOfBirth = \Carbon\Carbon::createFromFormat('d-m-Y', $customerArr[$i]['dob'])->format('Y-m-d');
                $today = date("Y-m-d");
                $diff = date_diff(date_create($dateOfBirth), date_create($today));
                $age = $diff->format('%y');
                $record = [
                    'first_name' => $customerArr[$i]['first_name'],
                    'last_name'  => $customerArr[$i]['last_name'],
                    'dob'        => $dateOfBirth,
                    'age' => $age,
                    'profile_pic' => '',
                    'mobile_code' => '',
                    'mobile_number'  => $customerArr[$i]['mobile_number'],
                    'land_number'  => $customerArr[$i]['land_number'],
                    'email'  => $customerArr[$i]['email'],
                    'residential_status' => $customerArr[$i]['residential_status'],
                    'contact_type' => $customerArr[$i]['contact_type'],
                    'state_id' => $customerArr[$i]['state_id'],
                    'city_id' => $customerArr[$i]['city_id'],
                    'loyalty_rank' => $customerArr[$i]['loyalty_rank'],
                    'potential' => $customerArr[$i]['potential'],
                    'client_since' => $customerArr[$i]['client_since'],
                    'annual_income' => $customerArr[$i]['annual_income'],
                    'running_sip' => $customerArr[$i]['running_sip'],
                    'created_by' => auth()->user()->id,
                ];
                //echo '<pre>';print_r($record);exit;
                Contact::Create($record);
                $successfulInserts++;
            } else {
                //$failedRows[] =$customerArr[$i]['email'];
                $failedRows[] = [
                    'row_number' => $i + 6,
                    //'row_data' => $customerArr[$i], 
                    'errors' => $validator->errors()->all()
                ];
            }
        }

        return response()->json(['message' => 'Contact Upload Successfuly!', 'status' => 'success', 'failed_rows' => $failedRows, 'total_inserted_rows' => $successfulInserts]);
    }

   /*****************************************************/
    # ContactController
    # Function name : importExcel()    
    # Purpose       :  Parsing an Excel file, validating its contents, and importing the data into the database, handling both creation of new contacts and updating existing ones. 
    # Params        : Request $request
    /*****************************************************/ 
    public function importExcel(Request $request)
    {
        
        $file = $request->file('csv_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        $duplicateRows = [];
        $successfulInserts = 0;
        $updatedRows = 0;
        $created_by = auth()->user()->id;
        $main_user = auth()->user()->added_by == 0 ? $created_by : auth()->user()->added_by;
        $inc_id=0;
        //set_time_limit(900);
        for ($i = 2; $i < count($rows); $i++) {    
            set_time_limit(900);      
          
           // $contact = 0;
            $contactArr = [];
            $contactTypeIsFamily=0;
            $dateOfBirth = Null;
            $dob=Null;
            $age = Null;
             //echo $rows[$i][3];exit;
             $explode_das_dt= explode('-',$rows[$i][3]);
             $explode_slace_dt= explode('/',$rows[$i][3]);
            // print_r($explode_das_dt).'||'.print_r($explode_slace_dt);exit;
          
            if ($rows[$i][3]) { 
                
                //if((!empty($explode_das_dt[1]) &&  $explode_das_dt[1] > 12 )|| (!empty($explode_slace_dt[1]) &&  $explode_slace_dt[0] > 12 )){
                if((!empty($explode_das_dt[1]) &&  ($explode_das_dt[1] > 12 || $explode_das_dt[1] == 0))|| (!empty($explode_slace_dt[1]) &&  ($explode_slace_dt[0] > 12 || $explode_slace_dt[0] == 0))){
                    
                    $dateOfBirth='invalid';
                }else{

                //$dateObject = \DateTime::createFromFormat('d/m/Y', $rows[$i][3]);
                $dateFormats = ['d-m-Y', 'm-d-Y','m/d/Y', 'd/m/Y']; // Add more formats as needed
                $dateObject = null;

                foreach ($dateFormats as $format) {
                    $dateObject = \DateTime::createFromFormat($format, $rows[$i][3]);
                     //print_r($dateObject);
                    if ($dateObject instanceof \DateTime) {
                        break; 
                    }
                }
                //echo $dateObject;exit;
                if ($dateObject instanceof \DateTime) {                  
                    
                    $dateOfBirth = $dateObject->format('Y-m-d'); 
                    //echo $dateOfBirth;exit;                                    
                    $today = date("Y-m-d");
                    $diff = date_diff(date_create($dateOfBirth), date_create($today));
                    $age = $diff->format('%y');
                }else{
                    $dateOfBirth = 'invalid';                  
                    $age = '';
                }
            }
                $dob= ($dateOfBirth=='invalid')?null:$dateOfBirth;
            }


            $residential_status = trim($rows[$i][9]);
            $residential = ($residential_status == 'Resident') ? 1 : ($residential_status == 'Non-Resident' ? 2 : ($residential_status == 'Foreign National' ? 3 :  ""));
            $contact_type = trim($rows[$i][10]);
            $ctype_id= DB::table('contact_types')->whereIn('created_by',[0,$created_by])->where('name', $contact_type)->first();
            $ctype= (!empty($ctype_id) ? $ctype_id->id:'' );
            
            //$aum = trim($rows[$i][14]);
           // $annual_income = ($aum == 'Upto 1CR') ? '0-1' : ($aum == '1CR to 50CR' ? '1-5' : ($aum == '100CR to 500CR' ? '100-500' : ($aum == '500CR+' ? '500+' :  "")));
         
            $data = [
                'email' => $rows[$i][8],
                'mobile_number' => $rows[$i][5],
                'dob' => $dateOfBirth,

            ];
            $rules = [
                'email' => [
                    'nullable', // It can be null
                    function ($attribute, $value, $fail) {
                        if (
                            !empty($value) &&
                            DB::table('contacts')->where('email', $value)
                            ->where('is_deleted', 0)->exists()
                        ) {
                           // $fail('The ' . $attribute . ' has already been taken.');
                            $fail('Duplicate Email ID Detected');
                        }
                    },
                ],
                'mobile_number' => [
                    'nullable',
                    function ($attribute, $value, $fail) {                       
                        if (
                            !empty($value) &&
                            DB::table('contacts')->where('mobile_number', $value)->where('is_deleted', 0)->exists()
                        ) {                          
                            //$fail('The ' . $attribute . ' has already been taken.');
                            $fail('Duplicate Mobile number Detected');
                        }
                    },
                ],
                'dob' => [
                    'nullable',
                    function ($attribute, $value, $fail) { 
                        if (
                            $value=="invalid"
                        ) {                         
                            $fail('Invalid DOB Date format');
                        }
                    },
                ],
            ];
            $validator = Validator::make($data, $rules);
            if($validator->fails()){
                $duplicateRows[] = [
                    'row_number' => $i + 1,
                    'errors' => $validator->errors()->all()
                ];
            }

            //$prefix_cust_id = $rows[$i][0];
            $cust_id = $rows[$i][0];
            if ($cust_id != '') {
                //$cust_id = ltrim($prefix_cust_id, "T0");
               // $contact = Contact::where('created_by', auth()->user()->id)
                    //->where('contact_unique_id', $cust_id)->count();
                $contactArr = Contact::where('created_by', auth()->user()->id)
                    ->where('contact_unique_id', $cust_id)->get();
                $contactTypeIsFamily= ($contactArr[0]->contact_type==9)?1:0;
                
            }
           
            if (count($contactArr) > 0) {               
                $contactdata = array(                    
                    'first_name' => $rows[$i][1],
                    'last_name'  => $rows[$i][2],
                    //'dob'        => $dateOfBirth,
                    'dob'        => $dob,
                    'age' => $age,
                    'mobile_code'  => $rows[$i][4],
                    'mobile_number'  => $rows[$i][5],
                    'lanline_code'  => $rows[$i][6],
                    'land_number'  => $rows[$i][7],
                    'email'  => $rows[$i][8],
                    'residential_status' => $residential,
                    'contact_type' => ($contactTypeIsFamily==0)?$ctype:9,
                    'state_id' => $rows[$i][11],
                    'city_id' => $rows[$i][12],
                    'loyalty_rank' => $rows[$i][13],
                    'potential' => $rows[$i][14],
                    'client_since' => $rows[$i][15],
                    'account_type' => $rows[$i][16],
                    'address_one' => $rows[$i][17],
                    'address_two' => $rows[$i][18],
                    //'annual_income' => $rows[$i][14],
                    //'running_sip' => $rows[$i][15],
                );
                Contact::where('contact_unique_id', $cust_id)->update($contactdata);


                $updatedRows++;
            } else {
                /*$validator = Validator::make(
                    [
                        'email' => $rows[$i][6],
                        'mobile_number' => $rows[$i][4]
                    ],
                    [
                        'email' => 'unique:contacts,email',
                        'mobile_number' => 'unique:contacts,mobile_number',
                    ]
                );*/
               
                
                if (!empty($rows[$i][1])) {
                    $randomAlphabet = chr(rand(65, 90)) . chr(rand(65, 90)) . chr(rand(65, 90));
                    $uniqueNumber = round(microtime(true))+$inc_id;
                    $contact_unique_id= $randomAlphabet.$uniqueNumber;
                   $contact = Contact::create([
                        'contact_unique_id' =>$contact_unique_id,
                        'first_name' => $rows[$i][1],
                        'last_name'  => $rows[$i][2],
                        //'dob'        => $dateOfBirth,
                        'dob'        => $dob,
                        'age' => $age,
                        'mobile_code'  => $rows[$i][4],
                        'mobile_number'  => $rows[$i][5],
                        'lanline_code'  => $rows[$i][6],
                        'land_number'  => $rows[$i][7],
                        'email'  => $rows[$i][8],
                        'residential_status' => $residential,
                        'contact_type' => $ctype,
                        'state_id' => $rows[$i][11],
                        'city_id' => $rows[$i][12],
                        'loyalty_rank' => $rows[$i][13],
                        'potential' => $rows[$i][14],
                        'client_since' => $rows[$i][15],
                        //'annual_income' => $rows[$i][14],
                        //'running_sip' => $rows[$i][15],
                        'account_type' => $rows[$i][16],
                        'address_one' => $rows[$i][17],
                        'address_two' => $rows[$i][18],
                        'created_by' => $created_by,
                        'main_user_id' => $main_user,
                    ]);

                    $createdContactId = $contact->id;
                    if($ctype!=9){
                        ContactFamilyMember::create([
                            'contact_id' => $createdContactId,
                            'family_member_id'  => $createdContactId,
                            'relationship'        => 'self',
                            'hof' => '1',
                            'hof_id'  => $createdContactId,                        
                            'created_by' => $created_by,
                            'main_user_id' => $main_user,
                        ]);
                    }

                   $successfulInserts++;
                  
                }
                /* else {
                    $duplicateRows[] = [
                        'row_number' => $i + 1,
                        'errors' => $validator->errors()->all()
                    ];
                }*/
            }
            $random_increment = rand(1000, 9999)+$i;
            $inc_id += $random_increment;
            
        }
        return response()->json(['message' => 'Contact Upload Successfuly!', 'status' => 'success', 'duplicate_rows' => $duplicateRows, 'total_inserted_rows' => $successfulInserts, 'updated_rows' => $updatedRows]);
    }

   
     /*****************************************************/
    # ContactController
    # Function name : exportContacts()    
    # Purpose       :   Exporting contacts data from the database to an Excel file. 
    # Params        : Request $request
    /*****************************************************/ 
    public function exportContacts(Request $request)
    {       
        /*$contacts = Contact::query()->leftjoin('users', 'users.id', '=', '.addon_user_id')
            ->select('contacts.*', 'users.name')
            ->where(function ($q) use ($request) {
                if (auth()->user()->added_by == 0) {
                    $q->where('contacts.main_user_id', auth()->user()->id);
                } else {
                    $q->where('contacts.created_by', auth()->user()->id);
                }
            })
            ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [auth()->user()->id])
            ->where('contacts.is_deleted', 0)
            ->get();*/

            $user_id= auth()->user()->id;
            $contacts = Contact::query()
            ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')           
            ->leftjoin('users as addOnUsers', 'addOnUsers.id', '=', '.addon_user_id')
            ->leftJoin('users as createdByUsers', 'createdByUsers.id', '=', 'contacts.created_by')
            
            ->select('contacts.*', 'addOnUsers.name',  'createdByUsers.name as creator', 'contact_types.name as contact_type_name')
                        
            //->where(function ($q) use ($request,$user_id) {
               // $userFilter = $request->userFilter;
                
               /* if ($userFilter) {
                    $explode_user = explode(',', $userFilter);
                    $cleaned_users = array_map(function ($user) {
                        return intval(trim($user));
                    }, $explode_user);                
                    $q->whereIn('contacts.created_by', $cleaned_users);
                }else{*/
                    //$q->where('contacts.created_by', auth()->user()->id)
                   // ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                   
               // }
            //})
            ->where(function ($q) use ($request, $user_id) {
                $add_on_user= $request->userFilter;
         
                if ($add_on_user!=0) {                       
                    $add_on_user = $request->userFilter;
                    $explode_user = explode(',', $add_on_user);
                    $cleaned_users = array_map(function ($user) {
                       // return intval(trim($user));
                       return (trim($user));
                    }, $explode_user);

                    if (in_array('assign', $cleaned_users)) {
                        if (count($cleaned_users) == 1) {
                            $q->WhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                        } else {
                            $q->whereIn('contacts.created_by', $cleaned_users)
                               ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                        }
                    }else{
                        $q->whereIn('contacts.created_by', $cleaned_users);                          
                    }
                   
                }else{
                    $q->whereRaw('1 = 0'); //For empty result                    
                }
                
            })
            ->where('contacts.is_deleted', 0)
            //->where('contacts.created_by', $user_id)

            ->when($request->search_text, function ($q) use ($request) {
                $searchText = '%' . $request->search_text . '%';                
                $q->where(DB::raw('CONCAT(first_name, " ", IFNULL(last_name, ""))'), 'LIKE', $searchText);               
            })

            ->when($request->sort_by, function ($q) use ($request) {
                $sort_by = $request->sort_by;
                $order_by = $request->order_by;
                $q->orderBy($sort_by, $order_by);
            })

            ->when($request->contact_filter_type, function ($q) use ($request) {                   
                    $q->where('contacts.contact_type', $request->contact_filter_type);                  
            }) 
            ->latest()
            
            ->where('contacts.is_deleted', 0)
            ->get();

            foreach ($contacts as $cntct) {               
                $state= DB::table('states')->select('name')
                ->where('id', $cntct->state_id)->first();

                $city= DB::table('cities')->select('city')
                ->where('id', $cntct->city_id)->first();
                
              $cntct->state = !empty($state) ? $state->name : (($cntct->state_id!=0)?$cntct->state_id:'');

              $cntct->city = !empty($city) ? $city->city : (($cntct->city_id!=0)?$cntct->city_id:'');
            }          

        //For Drop Down
        $dataValidation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
        $dataValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $dataValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $dataValidation->setAllowBlank(false);
        $dataValidation->setShowInputMessage(true);
        $dataValidation->setShowErrorMessage(true);
        $dataValidation->setShowDropDown(true);
        $dataValidation->setFormula1('"Customer,Ex-Customer,Lead"');

        $residentialStatusValidation = new \PhpOffice\PhpSpreadsheet\Cell\DataValidation();
        $residentialStatusValidation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $residentialStatusValidation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $residentialStatusValidation->setAllowBlank(true);
        $residentialStatusValidation->setShowInputMessage(true);
        $residentialStatusValidation->setShowErrorMessage(true);
        $residentialStatusValidation->setShowDropDown(true);
        $residentialStatusValidation->setFormula1('"A resident,A resident not ordinarily resident (RNOR),A non-resident (NR)"');

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $commentText = "# This is a CSV file for Contacts. Please do not modify the header row.";
        $sheet->setCellValue('F1', $commentText);
        // Set A1 font to bold
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
        ];
        $sheet->getStyle('F1')->applyFromArray($styleArray);
        $sheet->getRowDimension(2)->setRowHeight(30);
        $sheet->setCellValue('A2', 'Cust_ID*');
        $sheet->setCellValue('B2', 'first_name*');
        $sheet->setCellValue('C2', 'last_name');
        $sheet->setCellValue('D2', 'dob');
        $sheet->setCellValue('E2', 'mobile_number');
        $sheet->setCellValue('F2', 'land_number');
        $sheet->setCellValue('G2', 'email');
        $sheet->setCellValue('H2', 'residential_status');
        $sheet->setCellValue('I2', 'contact_type');
        $sheet->setCellValue('J2', 'state_id');
        $sheet->setCellValue('K2', 'city_id');
        $sheet->setCellValue('L2', 'loyalty_rank');
        $sheet->setCellValue('M2', 'potential');
        $sheet->setCellValue('N2', 'client_since');
        //$sheet->setCellValue('O2', 'annual_income');
        //$sheet->setCellValue('P2', 'running_sip');

        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '28336b',
                ],
            ],
            'font'  => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
                'bold' => true,
            ],
            'alignment' => [
                'indent' => 1,
            ],
        ];
        $sheet->getStyle('A2:N2')->applyFromArray($headerStyle);
        $sheet->getColumnDimension('A')->setWidth(10);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(15);
        $sheet->getColumnDimension('K')->setWidth(15);
        $sheet->getColumnDimension('L')->setWidth(15);
        $sheet->getColumnDimension('M')->setWidth(15);
        $sheet->getColumnDimension('N')->setWidth(15);
        //$sheet->getColumnDimension('O')->setWidth(15);
        //$sheet->getColumnDimension('P')->setWidth(15);

        $row = 3;
        foreach ($contacts as $contact) {
            if ($contact->id < 10) {
                $prefixedId = 'T00' . $contact->id;
            } else {
                $prefixedId = 'T0' . $contact->id;
            }

            $formattedDob = '';
            if ($contact->dob) {
                $formattedDob = Carbon::createFromFormat('Y-m-d', $contact->dob)->format('m/d/Y');
            }


            //Residential Status Drop Down
            $sheet->getCell('H' . $row)->setDataValidation(clone $residentialStatusValidation);
            $residential_status = ($contact->residential_status == 1) ? "A resident" : ($contact->residential_status == 2 ? "A resident not ordinarily resident (RNOR)" : ($contact->residential_status == 3 ? "A non-resident (NR)" : ""));

            $sheet->setCellValue('H' . $row, $residential_status);
            if ($contact->residential_status == 1) {
                $sheet->setCellValue('H' . $row, "A resident");
            } elseif ($contact->residential_status == 2) {
                $sheet->setCellValue('H' . $row, "A resident not ordinarily resident (RNOR)");
            } elseif ($contact->residential_status == 3) {
                $sheet->setCellValue('H' . $row, "A non-resident (NR)");
            } else {
                $sheet->setCellValue('H' . $row, "");
            }

            // Contact type drop down
            $sheet->getCell('I' . $row)->setDataValidation(clone $dataValidation);
            $contact_type = ($contact->contact_type == 1) ? "Customer" : ($contact->contact_type == 2 ? "Ex-Customer" : ($contact->contact_type == 3 ? "Lead" : ""));
            $sheet->setCellValue('I' . $row, $contact_type);           
            $sheet->setCellValue('A' . $row, $prefixedId);
            $sheet->setCellValue('B' . $row, $contact->first_name);
            $sheet->setCellValue('C' . $row, $contact->last_name);
            $sheet->setCellValue('D' . $row, $formattedDob);
            //$sheet->setCellValue('D' . $row, $contact->dob);
            $sheet->setCellValue('E' . $row, $contact->mobile_number);
            $sheet->setCellValue('F' . $row, $contact->land_number);
            $sheet->setCellValue('G' . $row, $contact->email);
            //$sheet->setCellValue('H' . $row, $contact->residential_status);
            $sheet->setCellValue('I' . $row, $contact->contact_type_name);
            //$sheet->setCellValue('J' . $row, $contact->state_id);
            $sheet->setCellValue('J' . $row, $contact->state);
            $sheet->setCellValue('K' . $row, $contact->city);
            $sheet->setCellValue('L' . $row, $contact->loyalty_rank);
            $sheet->setCellValue('M' . $row, $contact->potential);
            $sheet->setCellValue('N' . $row, $contact->client_since);
            //$sheet->setCellValue('O' . $row, $contact->annual_income);
            //$sheet->setCellValue('P' . $row, $contact->running_sip);
            $row++;
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        $fileName = 'users.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return response()->download($temp_file, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

   
    /*****************************************************/
    # ContactController
    # Function name : viewContact()    
    # Purpose       : Set up the data for displaying a form to view contact
    # Params        : Request $request, $id=null
    /*****************************************************/
    public function viewContact($id = null,  Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = base64_decode($id);
        $data['contactId'] = $contact_id;
        $data['contact'] = Contact::query()->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
        ->select('contacts.id', 'contacts.first_name', 'contacts.last_name', 'contacts.profile_pic', 'contacts.mobile_code', 'contacts.mobile_number', 'contacts.email', 'contacts.contact_type', 'contacts.is_hof','contacts.created_by', 'contacts.contact_unique_id','contact_types.name as contact_type_name')->where('contacts.id', $contact_id)->get();

        //Get contact HOF name
        $data['contact_hof'] = DB::table('contact_family_member')
        ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
        ->select('contacts.first_name', 'contacts.last_name')
        ->where('contact_family_member.contact_id', $contact_id)
        ->where('contact_family_member.family_member_id', '!=', $contact_id)
        ->where('contact_family_member.hof', '1')->get();
      
        //If contact is HOF of any member than is_hof column is 1
        $is_hof = $data['contact'][0]->is_hof;

        $data['page_title'] = "Overview of Client Profile";
        $data['is_backBtn'] = 1;
        $data['is_backBtn_alert'] = 0;
        $data['agendas'] = Agenda::select('id', 'title', 'is_priority', 'is_meeting','created_at')
            ->where('contact_id', $contact_id)
            //->where('agendas.created_by', $user_id)
            ->where(function ($q) use ($request) {
                if (auth()->user()->added_by == 0) {
                    $q->where('agendas.main_user_id', auth()->user()->id);
                } else {
                    $q->where('agendas.created_by', auth()->user()->id);
                }
            })
            ->where('agendas.is_deleted', '=', 0)
            ->paginate(10);
        // \DB::enableQueryLog();

        //Opportunity        
        $member_array = '';
        if ($is_hof == 1) {
            $family_member = DB::table('contact_family_member')
                ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
                ->select('contact_family_member.family_member_id', 'contact_family_member.contact_id')
                ->where('contact_family_member.hof_id', $contact_id)
                //->where('contact_family_member.hof', '0')
                ->where('contact_family_member.contact_id','!=', $contact_id)
                ->get();

            $idsArray = [];

            foreach ($family_member as $member) {
                $idsArray[] = $member->family_member_id;
                $idsArray[] = $member->contact_id;
            }
            // Removing duplicates
            $uniqueIds = array_unique($idsArray);

            // If you want the array indexed sequentially
            $member_array = array_values($uniqueIds);

            //$family_member_id = (!blank($family_member)) ? $family_member[0]->family_member_id : '';
        }

        $data['opportunities'] = Opportunity::query()->leftjoin('contacts', 'contacts.id', '=', 'opportunities.contact_id')
            ->select('opportunities.*', 'contacts.first_name', 'contacts.last_name', 'opportunity_types.name as opportunity_type_name')
            ->leftjoin('opportunity_types', 'opportunity_types.id', '=', 'opportunities.opportunity_type')
            ->where('opportunities.is_deleted', 0)
            //->where('opportunities.contact_id', $contact_id)
            //contact family opportunity also display
            ->where(function ($query) use ($contact_id, $member_array) {
                if ($member_array != '') {
                    $query->where('opportunities.contact_id', $contact_id)
                        ->orWhereIn('opportunities.contact_id',  $member_array);
                } else {
                    $query->where('opportunities.contact_id', $contact_id)
                    ->orWhere('opportunities.assignee_id',$contact_id);
                }
            })

            ->where(function ($q) use ($request) {
                if (auth()->user()->added_by == 0) {
                    $q->where('opportunities.main_user_id', auth()->user()->id);
                } else {
                    $q->where('opportunities.created_by', auth()->user()->id);
                }
            })
            ->orderBy('row_order', 'asc')
            ->latest()
            ->paginate(10);

            foreach ($data['opportunities'] as $opportunity) {
                
                $family_member = DB::table('contact_family_member')
                ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
                ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
                ->where('contact_family_member.contact_id', $opportunity->contact_id)
                ->where('contact_family_member.family_member_id', '!=', $opportunity->contact_id)
                //->where('contact_family_member.hof', '0')
                ->get()->toArray();
                $opportunity->family_member = (!empty($family_member)) ? $family_member : '';
            }
       
        //dd(\DB::getQueryLog());           


        $data['tasks'] = Task::query()->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
        ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
            ->select('tasks.*', 'contacts.first_name', 'contacts.last_name', 'task_status.name as task_status')
            ->where('tasks.contact_id', $contact_id)
            ->where('tasks.is_deleted', 0)
            //->where('tasks.created_by', $user_id)
            ->where(function ($q) use ($request) {
                if (auth()->user()->added_by == 0) {
                    $q->where('tasks.main_user_id', auth()->user()->id);
                } else {
                    $q->where('tasks.created_by', auth()->user()->id)
                    ->orWhere('tasks.assignee_id',auth()->user()->id);
                }
            })
            //->orderBy('tasks.id', 'desc')
            ->latest()
            ->paginate(10);

        $data['meetings'] = Meeting::query()->with('contact')
            // ->whereDate('meeting_date', '>=', now())
            ->where('meetings.is_completed', 0)
            ->where(DB::raw("CONCAT(meetings.meeting_date, ' ', meetings.meeting_time)"), '>=', date("Y-m-d H:i:s"))
            ->notDeleted()
            ->where('contact_id', $contact_id)
            ->orderBy('row_order', 'ASC')
            //->where('created_by', $user_id)
            ->where(function ($q) use ($request) {
                if (auth()->user()->added_by == 0) {
                    $q->where('main_user_id', auth()->user()->id);
                } else {
                    $q->where('created_by', auth()->user()->id);
                }
            })
            ->latest()->paginate(10);
            //use for meeting summary list 
            $data['is_summary']=0;

        //$data['add_on_users'] = User::where('added_by', $contact_id)
            //->get();
        $data['add_on_users']=    Helper::add_on_users();  
        return view('frontend.contact.view_contact', $data);
    }

    /*****************************************************/
    # ContactController
    # Function name : contactOpportunity()    
    # Purpose       : Retrieving opportunities associated with a contact.
    # Params        : Request $request
    /*****************************************************/
    public function contactOpportunity(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->contact_id;

        $contact_hof = Contact::select('is_hof')->where('contacts.id', $contact_id)->get();

        $is_hof = $contact_hof[0]->is_hof;

        if ($request->ajax()) {
            \DB::enableQueryLog();

            $member_array = '';
            if ($is_hof == 1) {
                $family_member = DB::table('contact_family_member')
                    ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
                    ->select('contact_family_member.family_member_id', 'contact_family_member.contact_id')
                    ->where('contact_family_member.hof_id', $contact_id)
                    ->where('contact_family_member.contact_id','!=', $contact_id)
                    //->where('contact_family_member.hof', '0')
                    ->get();

                $idsArray = [];

                foreach ($family_member as $member) {
                    $idsArray[] = $member->family_member_id;
                    $idsArray[] = $member->contact_id;
                }
                // Removing duplicates
                $uniqueIds = array_unique($idsArray);

                // If you want the array indexed sequentially
                $member_array = array_values($uniqueIds);
            }

            $opportunities = Opportunity::query()->leftjoin('contacts', 'contacts.id', '=', 'opportunities.contact_id')
                ->leftjoin('opportunity_types', 'opportunity_types.id', '=', 'opportunities.opportunity_type')
                ->select('opportunities.*', 'contacts.first_name', 'contacts.last_name', 'opportunity_types.name as opportunity_type_name')
                ->where('opportunities.is_deleted', 0)

                //->where('opportunities.contact_id', $contact_id)           
                //contact family opportunity also display
                ->where(function ($query) use ($contact_id, $member_array) {
                    if ($member_array != '') {
                        $query->where('opportunities.contact_id', $contact_id)
                            ->orWhereIn('opportunities.contact_id',  $member_array);
                    } else {
                        $query->where('opportunities.contact_id', $contact_id);
                    }
                })
                ->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('opportunities.main_user_id', auth()->user()->id);
                    } else {
                        $q->where('opportunities.created_by', auth()->user()->id);
                    }
                })

                ->when($request->opportunity_status, function ($q) use ($request) {
                    $opportunity_status = $request->opportunity_status;
                    if ($opportunity_status == 'pending') {
                        //$q->where('opportunities.status', 4);
                        $q->where('opportunities.status', '!=', 1);
                        $q->where('opportunities.status', '!=', 9);
                    }
                    if ($opportunity_status == 'converted') {
                        $q->where('opportunities.status', 1);
                    }
                })

                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 'converted') {
                        $q->where('opportunities.status', 1);
                    }
                    if ($filter_by == 'pending') {
                        $q->where('opportunities.status', 4);
                    }
                    if ($filter_by == 'bookmark') {
                        $q->where('is_bookmark', '1');
                    }
                    if ($filter_by == 1) {
                        $oneMonthAgo = Carbon::now()->subMonth();
                        $q->where('opportunities.follow_up_date', '>=', $oneMonthAgo);
                    }
                    if ($filter_by == 2) {
                        $threeMonthAgo = Carbon::now()->subMonth(3);
                        $q->where('opportunities.follow_up_date', '>=', $threeMonthAgo);
                    }
                    if ($filter_by == 3) {
                        $sixMonthAgo = Carbon::now()->subMonth(6);
                        $q->where('opportunities.follow_up_date', '>=', $sixMonthAgo);
                    }
                    if ($filter_by == 4) {
                        $oneYearAgo = Carbon::now()->subYear();
                        $q->where('opportunities.follow_up_date', '>=', $oneYearAgo);
                    }
                })
                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                }, function ($q) {
                    $q->orderBy('row_order', 'asc');
                })
                ->latest()
                ->paginate($request->record_count ? $request->record_count : 10);


            $data['data_count'] = $opportunities->count();
            $data['total_count'] = $opportunities->total();
            //dd(\DB::getQueryLog());
            return view('frontend.contact.contact_opportunity_data', compact('opportunities'), $data)->render();
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : contactMeeting()    
    # Purpose       : Retrieving meetings and meetings summary associated with a contact.
    # Params        : Request $request
    /*****************************************************/
    public function contactMeeting(Request $request)
    {
        $contact_id = $request->contact_id;   
       
        if ($request->ajax()) {
            //\DB::enableQueryLog();
           
            $meeting_type = $request->upcoming_meeting;
            if($meeting_type=='summary'){
                $meetings =  MeetingSummery::query()
                ->leftjoin('meetings', 'meetings.id', '=', 'meeting_summary.meeting_id')
                ->select('meeting_summary.*', 'meetings.meeting_date', 'meetings.meeting_time')
                
                ->where('meeting_summary.is_deleted', 0) 
                ->where('meeting_summary.contact_id', $contact_id)    
                
                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 1) {
                        //$q->where('contacts.contact_type', '0');
                        $oneMonthAgo = Carbon::now()->subMonth();
                        $q->where('meetings.meeting_date', '>=', $oneMonthAgo);
                    }
                    if ($filter_by == 2) {
                        $threeMonthAgo = Carbon::now()->subMonth(3);
                        $q->where('meetings.meeting_date', '>=', $threeMonthAgo);
                    }
                    if ($filter_by == 3) {
                        //$q->where('contacts.contact_type', '2');
                        $sixMonthAgo = Carbon::now()->subMonth(6);
                        $q->where('meetings.meeting_date', '>=', $sixMonthAgo);
                    }
                    if ($filter_by == 4) {
                        $oneYearAgo = Carbon::now()->subYear();
                        $q->where('meetings.meeting_date', '>=', $oneYearAgo);
                    }
                })
                
                
                ->latest()
                ->paginate($request->record_count ? $request->record_count : 10);
                $data['is_summary']=1;
            
            }else{
                $data['is_summary']=0;
             
                $meetings = Meeting::query()->with('contact')
                    ->notDeleted()
                    ->when($request->status, function ($q) use ($request) {
                        $status = $request->status == 'active' ? 1 : 0;
                        $q->where('status', $status);
                    })
                    ->when($request->upcoming_meeting, function ($q) use ($request) {
                        $meeting_type = $request->upcoming_meeting;
                        if ($meeting_type == 'upcoming') {
                            //$q->whereDate('meeting_date', '>=', now());
                            $q->where('meetings.is_completed', 0)
                                ->where(DB::raw("CONCAT(meetings.meeting_date, ' ', meetings.meeting_time)"), '>=', date("Y-m-d H:i:s"));
                        }
                        /*if ($meeting_time == 'past') {
                            $q->whereDate('meeting_date', '<', now());
                        }*/
                        if ($meeting_type == 'completed') {
                            $q->where('meetings.is_completed', 1);
                        }
                        if ($meeting_type == 'overdue') {
                            $q->where('meetings.is_completed', 0)
                                ->where(DB::raw("CONCAT(meetings.meeting_date, ' ', meetings.meeting_time)"), '<', date("Y-m-d H:i:s"));
                        }
                    })
                    ->when($request->current_meeting, function ($q) use ($request) {
                        $current_meeting = $request->current_meeting;
                        if ($current_meeting == 'cur_date') {
                            $q->whereDate('meeting_date', now());
                        }
                        if ($current_meeting == 'cur_week') {
                            $now = Carbon::now();
                            $q->whereBetween("meeting_date", [
                                $now->startOfWeek()->format('Y-m-d'),
                                $now->endOfWeek()->format('Y-m-d')
                            ]);
                        }

                        if ($current_meeting == 'later') {
                            $now = Carbon::now();
                            $q->whereBetween("meetings.meeting_date", [
                                $now->addDays(1)->format('Y-m-d'),
                                $now->addDays(30)->format('Y-m-d')
                            ]);
                        }
                        /*if ($current_meeting == 'prev') {
                            $q->whereDate('meetings.meeting_date', '<', now());
                        }*/
                    })

                    ->when($request->filter_by, function ($q) use ($request) {
                        $filter_by = $request->filter_by;
                        if ($filter_by == 1) {
                            //$q->where('contacts.contact_type', '0');
                            $oneMonthAgo = Carbon::now()->subMonth();
                            $q->where('meeting_date', '>=', $oneMonthAgo);
                        }
                        if ($filter_by == 2) {
                            $threeMonthAgo = Carbon::now()->subMonth(3);
                            $q->where('meeting_date', '>=', $threeMonthAgo);
                        }
                        if ($filter_by == 3) {
                            //$q->where('contacts.contact_type', '2');
                            $sixMonthAgo = Carbon::now()->subMonth(6);
                            $q->where('meeting_date', '>=', $sixMonthAgo);
                        }
                        if ($filter_by == 4) {
                            $oneYearAgo = Carbon::now()->subYear();
                            $q->where('meeting_date', '>=', $oneYearAgo);
                        }
                    })
                    ->where('contact_id', $contact_id)
                    // ->where('created_by', auth()->user()->id)
                    /*->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('main_user_id', auth()->user()->id);
                    } else {
                        $q->where('created_by', auth()->user()->id);
                    }
                })*/
                    ->orderBy('row_order', 'ASC')
                    ->latest()
                    ->paginate($request->record_count ? $request->record_count : 10);
            }
            //dd(\DB::getQueryLog());
            $data['data_count'] = $meetings->count();
            $data['total_count'] = $meetings->total();
            
            return view('frontend.contact.contact_meeting_data', compact('meetings'), $data)->render();
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : contactAgenda()    
    # Purpose       : Retrieving Agenda associated with a contact.
    # Params        : Request $request
    /*****************************************************/
    public function contactAgenda(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->contact_id;

        if ($request->ajax()) {
            \DB::enableQueryLog();

            $agendas =   Agenda::select('agendas.*', 'contacts.first_name', 'contacts.contact_type')
                ->join('contacts', 'agendas.contact_id', '=', 'contacts.id',)
                ->where('agendas.contact_id', $contact_id)
                ->where('agendas.is_deleted', 0)
                //->where('agendas.created_by', $user_id)
                ->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('agendas.main_user_id', auth()->user()->id);
                    } else {
                        $q->where('agendas.created_by', auth()->user()->id);
                    }
                })
                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 'converted') {
                        $q->where('agendas.is_meeting', '1');
                    }
                    if ($filter_by == 1) {
                        //$q->where('contacts.contact_type', '0');
                        $oneMonthAgo = Carbon::now()->subMonth();
                        $q->where('agendas.created_at', '>=', $oneMonthAgo);
                    }
                    if ($filter_by == 2) {
                        $threeMonthAgo = Carbon::now()->subMonth(3);
                        $q->where('agendas.created_at', '>=', $threeMonthAgo);
                    }
                    if ($filter_by == 3) {
                        //$q->where('contacts.contact_type', '2');
                        $sixMonthAgo = Carbon::now()->subMonth(6);
                        $q->where('agendas.created_at', '>=', $sixMonthAgo);
                    }
                    if ($filter_by == 4) {
                        $oneYearAgo = Carbon::now()->subYear();
                        $q->where('agendas.created_at', '>=', $oneYearAgo);
                    }
                })
                // ->orderBy($request->sort_by, $request->order_by)
                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                }, function ($q) {
                    $q->orderBy('row_order', 'asc');
                })
                ->paginate($request->record_count ? $request->record_count : 10);

            return view('frontend.contact.agenda_data', compact('agendas'))->render();
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : contactTask()    
    # Purpose       : Retrieving Task associated with a contact.
    # Params        : Request $request
    /*****************************************************/
    public function contactTask(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->contact_id;

        if ($request->ajax()) {

            // \DB::enableQueryLog();
            $tasks = Task::query()->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
            ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
                ->select('tasks.*', 'contacts.first_name', 'contacts.last_name','task_status.name as task_status')
                ->where('tasks.contact_id', $contact_id)
                //->where('tasks.created_by', $user_id)
                ->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('tasks.main_user_id', auth()->user()->id);
                    } else {
                        $q->where('tasks.created_by', auth()->user()->id);
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
                ->when($request->filter_by, function ($q) use ($request) {
                    $filter_by = $request->filter_by;
                    if ($filter_by == 'overdue') {
                        $q->whereDate('deadline', '<', now());
                        $q->whereIn('tasks.status', [2,4],'and', true);
                    }
                    if ($filter_by == 'upcoming') {
                        $now = Carbon::now();
                        $q->whereIn('tasks.status', [2,4],'and', true);
                        $q->whereBetween("deadline", [
                            $now->format('Y-m-d'), 
                            $now->addDays(30)->format('Y-m-d')
                        ]);
                    }

                    if ($filter_by == 1) {
                        $oneMonthAgo = Carbon::now()->subMonth();
                        $q->where('tasks.deadline', '>=', $oneMonthAgo);
                    }
                    if ($filter_by == 2) {
                        $threeMonthAgo = Carbon::now()->subMonth(3);
                        $q->where('tasks.deadline', '>=', $threeMonthAgo);
                    }
                    if ($filter_by == 3) {
                        $sixMonthAgo = Carbon::now()->subMonth(6);
                        $q->where('tasks.deadline', '>=', $sixMonthAgo);
                    }
                    if ($filter_by == 4) {
                        $oneYearAgo = Carbon::now()->subYear();
                        $q->where('tasks.deadline', '>=', $oneYearAgo);
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
                        $q->where('tasks.is_bookmark','!=' ,'1');
                    }
                    if ($current_task == 'priority') {
                        $q->where('is_bookmark', '1');
                        $q->where('tasks.status','!=', 2);
                    }
                })
                ->latest()
                ->paginate($request->record_count ? $request->record_count : 10);

            $data['data_count'] = $tasks->count();
            $data['total_count'] = $tasks->total();
            //dd(\DB::getQueryLog());
            return view('frontend.contact.contact_task_data', compact('tasks'), $data)->render();
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : instant_update_task()    
    # Purpose       : Updates to different fields of the task based on the provided field_name.
    # Params        : Request $request
    /*****************************************************/
    public function instant_update_task(Request $request)
    {
        $field_name = $request->input('field_name');
        $changed_value = $request->input('changed_value');
        $id = $request->input('taskId');
        try {
            $task = Task::find($id);
            if ($field_name == 'deadline') {
                $date = str_replace('/', '-', $changed_value);
                $task->deadline = date("Y-m-d", strtotime($date));
            } else {
                $task->$field_name = $changed_value;
            }    
            $task->save(); 
            $msg = 'Updated successfully';
        } catch (\Exception $e) {
            $msg = "Oops!! Something went wrong";
        }
        return response()->json(['message' => $msg]);
    }
            
    /*****************************************************/
    # ContactController
    # Function name : viewContactProfile()    
    # Purpose       : Fetch and prepare all the necessary data to display a contact's profile 
    # Params        : Request $request, $id = null
    /*****************************************************/
    public function viewContactProfile($id = null, Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $data['page_title'] = "Contact details";
        $data['is_backBtn'] = 1;
        $contact_id = base64_decode($id);
        $data['contact'] = Contact::leftJoin('states', 'contacts.state_id', '=', 'states.id')
            ->leftJoin('cities', 'contacts.city_id', '=', 'cities.id')
            ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')          
            ->select('contacts.*', 'states.name as state_name', 'cities.city as city_name', 'contact_types.name as contact_type_name')
            ->where('contacts.id', $contact_id)
            ->get();

        $data['referred_by'] = Contact::select('id', 'first_name', 'last_name')->where('contacts.id', $data['contact'][0]->referred_by)->get();
        $data['contactType'] = ContactType::where('is_deleted', 0)->where('status', 1)->where('id', '!=', 9)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get(); 

        $data['tags'] = ContactCustomTag::query()->join('custom_tags', 'contacts_custom_tags.custom_tag_id', '=', 'custom_tags.id')
            ->select('contacts_custom_tags.*', 'custom_tags.custom_tag')
            ->where('custom_tags.is_deleted', 0)
            ->where('contacts_custom_tags.contact_id', $contact_id)
            ->get();
        $data['investments'] = ContactInvestment::select('id', 'schemes_name', 'remarks','status')->where('contact_id', $contact_id)->get();
        
        $data['contact_family'] = Helper::getContactFamily($contact_id);
        /*$data['contact_family'] = ContactFamilyMember::with(['contact' => function ($query) {
            $query->select('id', 'first_name', 'last_name', 'profile_pic')
            ->where('contacts.is_deleted', 0)
            ->where('contacts.is_hof', 0);
        }])
            ->where('contact_id', $contact_id)
            ->where('is_deleted', 0)
            
            ->get();*/

        $data['opprtunity_type'] = OpportunityTypeAdmin::where('is_deleted',0)
        ->where('status',1)
        ->whereIn('created_by',[0,$user_id])
        ->orderBy('row_order','ASC')->latest()->get();

        $data['cnttype'] = ContactType::select('name')->where('id', $data['contact'][0]->contact_type)->first();
        return view('frontend.contact.view_contact_profile', $data);
    }

        
    /*****************************************************/
    # ContactController
    # Function name : addTag()    
    # Purpose       : Add a new tag after ensuring unique tag. 
    # Params        : Request $request
    /*****************************************************/
    public function addTag(Request $request)
    {
        $created_by = auth()->user()->id;
        $input = $request->all();
        $tag = $input['tag'];

        $tag = Helper::cleanText($input['tag']);
        if (CustomTag::where('custom_tag', $tag)->where('status',1)->where('is_deleted',0)->whereIn('created_by',[0,$created_by])->exists()) {
            $msg = 'Tag name must be unique!!';
            return response()->json(['message' => $msg, 'tagId' => '']);
        } else {

            $CustomTag = new CustomTag;
            $CustomTag->custom_tag = $tag;
            $CustomTag->created_by = $created_by;
            $CustomTag->save();
            $tagId = $CustomTag->id;
            $msg = 'Tag added successfully';
            return response()->json(['message' => $msg, 'tagId' => $tagId]);
        }
    }
    
    /*****************************************************/
    # ContactController
    # Function name : switchProfile()    
    # Purpose       : For switching the contact type and returns a JSON response to indicate the success of the operation. 
    # Params        : Request $request
    /*****************************************************/

    public function switchProfile(Request $request)
    {
        $contact_id = $request->input('contact_id');
        $contact = Contact::find($contact_id);
        $contact->contact_type = $request->input('contact_type');
        $contact->save();
        $msg = 'Contact switched successfully';
        return response()->json(['message' => $msg, 'status' => 'success']);
    }
    
    /*****************************************************/
    # ContactController
    # Function name : detachMember()    
    # Purpose       : Detach a family member from a particular contact. 
    # Params        : Request $request
    /*****************************************************/

    public function detachMember(Request $request)
    {
        $contactId = $request->input('contactId');
        $member_id = $request->input('familyMemberId');
        $cntctType = $request->input('cntctType');          
        //$member = ContactFamilyMember::find($member_id);
        $members = ContactFamilyMember::where(function($query) use ($contactId, $member_id) {
            $query->where('contact_id', $contactId)
                  ->where('family_member_id', $member_id);
        })->orWhere(function($query) use ($member_id, $contactId) {
            $query->where('contact_id', $member_id)
                  ->where('family_member_id', $contactId);
        })->get();
        
       // $contact = Contact::find($member->family_member_id);
        $contact = Contact::find($member_id);       
        $contact->family_id = 0;
        $contact->is_hof = 0;
        $contact->contact_type = $cntctType;  
        $contact->save();
        //Change Contact Type
        /*$cntct = Contact::find($contactId);
        $cntct->contact_type = $cntctType;       
        $cntct->save();*/
        //set detached member HOF is self
        if(!empty($members[0])){
            $members[0]->contact_id = $member_id;
            $members[0]->family_member_id = $member_id;
            $members[0]->relationship = 'self';
            $members[0]->relationship_id = 0;
            $members[0]->hof = '1';
            $members[0]->hof_id = $member_id;
            $members[0]->save();
        }

        //Remove Relation
        if(!empty($members[1])){           
            $members[1]->is_deleted = 1;
            $members[1]->deleted_by = auth()->user()->id;
            $members[1]->deleted_at = date('Y-m-d H:i:s');
            $members[1]->save();
        }

       /* foreach ($members as $member) {
            $member->is_deleted = 1;
            $member->deleted_by = auth()->user()->id;
            $member->deleted_at = date('Y-m-d H:i:s');
            $member->save();
        }*/


        $msg = 'Family member detached successfully';
        return response()->json(['message' => $msg, 'status' => 'success']);
    }

    
    /*****************************************************/
    # ContactController
    # Function name : checkHof()    
    # Purpose       : To check HOF (E.g: If A is the Hof of B, then B cannot be the Hof of A). 
    # Params        : Request $request
    /*****************************************************/
    public function checkHof(Request $request)
    {
        $member_id = $request->input('memberId');
        $contact_id = $request->input('contactId');
        $where = [
            ['contact_id', '<>', $contact_id],
            ['contact_id', $member_id],
            ['family_member_id', $contact_id],
            ['hof', '1'],
        ];
        if (ContactFamilyMember::where($where)->exists()) {
            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }

   
    /*****************************************************/
    # ContactController
    # Function name : checkFamily()    
    # Purpose       :  To check family memeber (E.g: If A is the family memeber of B, then B cannot be the family memeber of A.)
    # Params        : Request $request
    /*****************************************************/
    public function checkFamily(Request $request)
    {
        $member_id = $request->input('memberId');
        $contact_id = $request->input('contactId');  
        $where = [
            ['contact_id', '<>', $contact_id],         
            ['family_member_id', $member_id],
            ['hof', '0'],
        ];
        if (ContactFamilyMember::where($where)->exists()) {
            return response()->json(['success' => true, 'msg'=>'Selected Contact already mapped with family member']);
        } else {
            //To check HOF's Hof will not allowed
            \DB::enableQueryLog();
           /* $members = ContactFamilyMember::where(function($query) use ($member_id) {
                $query->where('contact_id', $member_id)
                ->where('relationship_id', '!=',0)
                ->where('is_deleted',0);                      
            })->orWhere(function($query) use ($member_id) {
                $query->where('family_member_id', $member_id)
                ->where('relationship_id', '!=',0)
                ->where('is_deleted',0);
            })           
            ->get(); */ 
            //Check Contact has family member or not
           $isFamilyExist= Helper::isFamilyExist($member_id);
            
           if($isFamilyExist)
           {    
            return response()->json(['success' => true, 'msg'=>'']);        
                      
           }else{          
            return response()->json(['success' => false]);  
           }           
        }
    }

      
    /*****************************************************/
    # ContactController
    # Function name : generateAgendaPdfReport()    
    # Purpose       : Generation of PDF reports for Agenda based on user requests.
    # Params        : Request $request
    /*****************************************************/
    function generateAgendaPdfReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->input('contact_id');
        $agendaArray = $request->input('agendaArray');
        $action_type = $request->input('actionType');

        $agendas = Agenda::with('contact')
            ->whereIn('id', $agendaArray)
            ->where('is_deleted', '=', 0)
            ->latest()
            ->get();

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
        $isChosenCustomer = 0;

        $dateToday = Carbon::now()->format('d_m_Y');
        $pdf_file_name = 'agende_' . uniqid() . '_' . $dateToday . '.pdf';

         // Load the view and pass the data
         //$pdf = PDF::loadView('frontend.pdf.agenda', ['agendas' => $agendas]);
         $pdf = PDF::loadView('frontend.pdf.contact.contact_agenda_pdf', ['agendas' => $agendas, 'reportSetting'=>$reportSetting,'userData'=>$userData,'userList'=>$userList , 'isChosenCustomer' => $isChosenCustomer ]);
         
         if ($action_type == 'download') {
             return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
         }else{
            $pdfPath = public_path('client-reports.in/pdf/' . $pdf_file_name);
            $pdf->save($pdfPath);
            return response()->json(['file_name' => $pdf_file_name]);
         }
         
         //return response()->json(['fileName' => $pdf_file_name]);       
 
    }
     
    /*****************************************************/
    # ContactController
    # Function name : generateMeetingPdfReport()    
    # Purpose       : Generate PDF reports for meetings,or meeting summary  based on user request. 
    # Params        : Request $request
    /*****************************************************/
    function generateMeetingPdfReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->input('contact_id');
        $meetingArray = $request->input('meetingArray');
        $data['agenda_flag'] = 0;
        $data['summary_flag'] = 0;
        $action_type = $request->input('actionType');
        $meeting_type= $request->input('meeting_type');
        $dateToday = Carbon::now()->format('d_m_Y');

            
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
        $isChosenCustomer = 0;

        if($meeting_type=='summary'){
        $summaries =  MeetingSummery::query()->with('meeting')->with('contact')->notDeleted()
            ->whereIn('id', $meetingArray)              
            ->latest()
            ->get();
            $pdf_file_name = 'summary_' . uniqid() . '_' . $dateToday . '.pdf';
            // Load the view and pass the data
            $pdf = PDF::loadView('frontend.pdf.contact.contact_meeting_summary', ['summaries' => $summaries, 'reportSetting'=>$reportSetting,'userData'=>$userData ,'userList'=>$userList, 'isChosenCustomer'=> $isChosenCustomer]);

        }else{       
        
            $meetings = Meeting::query()->with('contact')
            ->whereIn('meetings.id', $meetingArray)
            ->notDeleted()
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();

            foreach ($meetings as $meeting) {
                $contact_type = DB::table('contact_types')                  
                    ->select('contact_types.name as contact_type_name')
                    ->where('contact_types.id', $meeting->contact->contact_type)
                    ->first();
                $meeting->contact_type = (!empty($contact_type)) ? $contact_type : '';
            } 
                        
            $pdf_file_name = 'meeting_' . uniqid() . '_' . $dateToday . '.pdf';
            // Load the view and pass the data
            // $pdf = PDF::loadView('frontend.pdf.meeting', ['meetings' => $meetings, 'flag' => $data]);

            $pdf = PDF::loadView('frontend.pdf.contact.contact_meeting_pdf', ['meetings' => $meetings, 'reportSetting'=>$reportSetting,'userData'=>$userData ,'userList'=>$userList, 'flag' => $data, 'isChosenCustomer'=> $isChosenCustomer]);
        }
        if ($action_type == 'download') {
            return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
        }else{            
            $pdfPath = public_path('client-reports.in/pdf/' . $pdf_file_name);
            $pdf->save($pdfPath);
            return response()->json(['file_name' => $pdf_file_name]);
        }
            
        //return response()->json(['fileName' => $pdf_file_name]);       

    }

   
    /*****************************************************/
    # ContactController
    # Function name : generateMeetingPdfReport()    
    # Purpose       : Creation of meeting summary reports in Excel format.
    # Params        : Request $request
    /*****************************************************/    
    public function generateSummaryExcelReport(Request $request)
    {
        $meetingArray = $request->input('meetingArray');
        $contact_id= $request->input('contactId');
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $contact_detail= Contact::select('contacts.id as cont_id', 'contacts.first_name', 'contacts.last_name','contacts.mobile_code','contacts.mobile_number')->where('id', $contact_id)->first();

        $sheet->setCellValue('A2', 'Client Name');  
        $sheet->setCellValue('B2', $contact_detail->first_name.' '.$contact_detail->last_name); 
        $sheet->setCellValue('C2', $contact_detail->mobile_code.'-'.$contact_detail->mobile_number); 

        $sheet->setCellValue('A3', 'Prepared By');  
        $sheet->setCellValue('B3',  auth()->user()->name); 
        $sheet->setCellValue('A4', 'Meeting Id'); 
        $sheet->setCellValue('B4', 'Meeting Date'); 
        $sheet->setCellValue('C4', 'Meeting Time'); 
        $sheet->setCellValue('D4', 'Meeting Summary'); 

         // Set A1 font to bold
         $boldStyle = [
            'font' => [
                'bold' => true,
            ],
        ];

        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => '3D5BFF',
                ],
            ],
            'font'  => [
                'color' => [
                    'argb' => 'FFFFFFFF',
                ],
                'bold' => true,
                'size' => 12,
            ],
            'alignment' => [
                'indent' => 1,
            ],
        ];

        $styleArray = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFE0',
                ],
            ],           
        ];

        $borderArray = [       

            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,  // or BORDER_MEDIUM, BORDER_THICK, etc.
                    'color' => ['argb' => 'FF000000'],  // ARGB value for black color
                ],
            ],
        ];
        
        //==set image==
        // Create a drawing object
        $drawing = new Drawing();

        // Set the image path
        
        $drawing->setPath(public_path('assets/images/new-logo.png'));
        // Set the cell where the top-left corner of the image should be placed
        $drawing->setCoordinates('A1');

        // Optionally, resize the image
        $drawing->setResizeProportional(true);
        $drawing->setWidthAndHeight(120, 80); // Set width and height, for example

        // Add the image to the worksheet
        $drawing->setWorksheet($sheet);
        
        $sheet->getRowDimension(1)->setRowHeight(50);
        $sheet->getRowDimension(7)->setRowHeight(20);
        $sheet->getStyle('B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B1', 'Meeting Summary Report'); 
        $sheet->getStyle('A2:A4')->applyFromArray($boldStyle);
        $sheet->getStyle('B4')->applyFromArray($boldStyle);
        $sheet->getStyle('C4')->applyFromArray($boldStyle);
        $sheet->getStyle('D4')->applyFromArray($boldStyle);

        
        $sheet->getStyle('A2:A4')->applyFromArray($styleArray);
        $sheet->getStyle('B4')->applyFromArray($styleArray);
        $sheet->getStyle('C4')->applyFromArray($styleArray);
        $sheet->getStyle('D4')->applyFromArray($styleArray);

        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
    
        $sheet->getColumnDimension('A')->setWidth(25);
        $sheet->getColumnDimension('B')->setWidth(25);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(25);

        $row = 5;  // Start writing from row 2

        foreach ($meetingArray as $meetingId) {
            $meeting_detail = MeetingSummery::select('meeting_id', 'summary_name')->where('id', $meetingId)->first();

            $meeting_id= $meeting_detail->meeting_id;                       
            $meetings = Meeting::query()
                ->select('meetings.*',  DB::Raw("CONCAT(meetings.meeting_date, ' ', meetings.meeting_time) AS meeting_date_time"))
                ->where('meetings.id', $meeting_id)             
                ->get();            
        
            $meeting_date_for_id = \Carbon\Carbon::parse($meetings[0]->meeting_date)->format('Ymd');
     
            $meeting_date = \Carbon\Carbon::parse($meetings[0]->meeting_date)->format('d/m/Y'); 
            $meeting_time = \Carbon\Carbon::parse($meetings[0]->meeting_time)->format('h:i A');
             
            $sheet->setCellValue('A'.$row, "MM".$meeting_date_for_id."/".$meetings[0]->id);           

            $sheet->setCellValue('B'.$row, $meeting_date);
            $sheet->setCellValue('C'.$row, $meeting_time);
            $sheet->setCellValue('D'.$row, $meeting_detail->summary_name); 

            $sheet->getRowDimension($row)->setRowHeight(20);

            // Start increase font size from row 2 till the highest row
            for ($j = 2; $j <= $row; $j++) {
                $rangeStyle = $sheet->getStyle("A{$j}:D{$j}")->getFont();
                $rangeStyle->setSize(13);
                $sheet->getStyle("A{$j}:D{$j}")->applyFromArray($borderArray);           
            }

            $row += 1; // Increase by 1 to give space for the next meeting data
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $fileName = 'meeting.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    function generateContacts_bckup(Request $request)
    {
        $user_id = auth()->user()->id;
        

        if(auth()->user()->added_by ==0){
                $column_name ='leads.main_user_id';
            }else{
                if($userDetails->role_id ==1){
                    $column_name ='leads.leader_id';
                }else{
                    $column_name ='leads.created_by';
                }
            }

            if($request->export_type == 'all')
            {
                $contacts = Contact::query()->leftjoin('users', 'users.id', '=', 'contacts.addon_user_id')
                       ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
                       ->select('contacts.*', 'users.name','contact_types.name as contact_type_name')
                ->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('main_user_id', auth()->user()->id);
                    } else {
                        $q->where('created_by', auth()->user()->id);
                    }
                })
                //->where('contacts.created_by', $user_id)

                ->when($request->search_text, function ($q) use ($request) {
                     $searchText = '%' . $request->search_text . '%';

                    $q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',   $searchText . '%');
                    /*$searchText = explode(' ', trim($request->search_text));
                    $firstWord = $searchText[0] . '%';
                    $q->where('contacts.first_name', 'LIKE', $firstWord);*/
                })

                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                })
                ->latest()
                // ->toSql();
                ->get();

                foreach ($contacts as $contact) {
                    $assigneeIds = $contact->assignee_ids; 
                    $assigneeIdsArray = explode(',', $assigneeIds);
                    $userNames = User::whereIn('id', $assigneeIdsArray)
                                    ->pluck('name')
                                    ->toArray();
                    $contact->commaSeparatedassignee = implode(', ', $userNames);
                    
                }
                //print_r($contacts);exit;
            }
            else
            {
                $contacts = Contact::query()->leftjoin('users', 'users.id', '=', 'contacts.addon_user_id')
                       ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
                       ->select('contacts.*', 'users.name','contact_types.name as contact_type_name')
                ->where(function ($q) use ($request) {
                    if (auth()->user()->added_by == 0) {
                        $q->where('main_user_id', auth()->user()->id);
                    } else {
                        $q->where('created_by', auth()->user()->id);
                    }
                })
                //->where('contacts.created_by', $user_id)

                ->when($request->search_text, function ($q) use ($request) {
                     $searchText = '%' . $request->search_text . '%';

                    $q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',   $searchText . '%');
                    /*$searchText = explode(' ', trim($request->search_text));
                    $firstWord = $searchText[0] . '%';
                    $q->where('contacts.first_name', 'LIKE', $firstWord);*/
                })

                ->when($request->sort_by, function ($q) use ($request) {
                    $sort_by = $request->sort_by;
                    $order_by = $request->order_by;
                    $q->orderBy($sort_by, $order_by);
                })
                

                ->whereIn('contacts.id', $request->contact_ids)
                
                
                ->latest()
                // ->toSql();
                ->get();
                foreach ($contacts as $contact) {
                    $assigneeIds = $contact->assignee_ids; 
                    $assigneeIdsArray = explode(',', $assigneeIds);
                    $userNames = User::whereIn('id', $assigneeIdsArray)
                                    ->pluck('name')
                                    ->toArray();
                    $contact->commaSeparatedassignee = implode(', ', $userNames);
                    
                }
            }
            

                // dd($leads);

        // foreach ($opportunities as $opportunity) {
        //     $family_member = DB::table('contact_family_member')
        //         ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
        //         ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
        //         ->where('contact_family_member.contact_id', $opportunity->contact_id)
        //         ->where('contact_family_member.hof', '0')->get()->toArray();
        //     $opportunity->family_member = (!empty($family_member)) ? $family_member : '';
        // }

            if($request->doc_type == 'csv')
            {
                if (sizeof($contacts) > 0) {
                    for ($i = 0; $i < sizeof($contacts); $i++) {
                        $csvRecord[$i]['customer_name']       = $contacts[$i]['first_name'].' '.$contacts[$i]['last_name'];

                        $csvRecord[$i]['contact_type_name']       = $contacts[$i]['contact_type_name'];

                        $csvRecord[$i]['name']       = $contacts[$i]->commaSeparatedassignee;

                        
                    }
                } else {
                    $csvRecord = [];
                }


                 // Set the CSV response headers
                    $headers = array(
                        'Content-Type' => 'text/csv',
                        //'Content-Disposition' => 'attachment; filename="data.csv"',
                    );

                    // Create a callback function to write the data to the CSV file
                    $callback = function () use ($csvRecord) {
                        $output = fopen('php://output', 'w');
                        $heading = ['Contact Name', 'Type of contact', 'Assigned Team'];
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
                    return Response::stream($callback, 200, $headers);
            }
            else
            {
                // echo $request->doc_type; die;
                if($request->doc_type == 'pdf')
                {
                    $dateToday = Carbon::now()->format('d_m_Y');       
                    $pdf_file_name = 'contact.pdf';  
                    $pdf = PDF::loadView('frontend.pdf.contact', ['contacts' => $contacts]);

                    // dd($pdf);
                    
                    return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
                    
                }
            }
        

       

        // Load the view and pass the data
        $pdf = PDF::loadView('frontend.pdf.agenda', ['agendas' => $agendas]);
        if ($action_type == 'download') {
            return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
        } else {
            $pdfPath = public_path('pdf/' . $pdf_file_name);
            $pdf->save($pdfPath);
            return response()->json(['file_name' => $pdf_file_name]);
        }
    }

   
    /*****************************************************/
    # ContactController
    # Function name : generateContacts()    
    # Purpose       : Export contact in CSV or PDF format based on input parameters.
    # Params        : Request $request
    /*****************************************************/   
    function generateContacts(Request $request)
    {
        $user_id = auth()->user()->id;  

        if($request->export_type == 'all')
        {                    
            $contacts = Contact::query()
            ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
            ->leftjoin('users as addOnUsers', 'addOnUsers.id', '=', '.addon_user_id')
           // ->leftJoin('users as createdByUsers', 'createdByUsers.id', '=', 'contacts.created_by')
            
            ->select('contacts.*', 'addOnUsers.name',  'contact_types.name as contact_type_name')
                        
            /*->where(function ($q) use ($request,$user_id) {                
                $q->where('contacts.created_by', auth()->user()->id)
                ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);              
            })               
            
            ->where('contacts.is_deleted', 0)

            ->when($request->search_text, function ($q) use ($request) {
                    $searchText = '%' . $request->search_text . '%';

                $q->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'LIKE',   $searchText . '%');                   
            })

            ->when($request->sort_by, function ($q) use ($request) {
                $sort_by = $request->sort_by;
                $order_by = $request->order_by;
                $q->orderBy($sort_by, $order_by);
            })*/            
            ->where(function ($q) use ($request, $user_id) {
                $add_on_user= $request->userFilter;
         
                if ($add_on_user!=0) {                       
                    $add_on_user = $request->userFilter;
                    $explode_user = explode(',', $add_on_user);
                    $cleaned_users = array_map(function ($user) {
                       // return intval(trim($user));
                       return (trim($user));
                    }, $explode_user);

                    if (in_array('assign', $cleaned_users)) {
                        if (count($cleaned_users) == 1) {
                            $q->WhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                        } else {
                            $q->whereIn('contacts.created_by', $cleaned_users)
                               ->orWhereRaw('FIND_IN_SET(?, contacts.assignee_ids)', [$user_id]);
                        }
                    }else{
                        $q->whereIn('contacts.created_by', $cleaned_users);                          
                    }
                   
                }else{
                    $q->whereRaw('1 = 0'); //For empty result                    
                }
                
            })
            ->where('contacts.is_deleted', 0)
            //->where('contacts.created_by', $user_id)

            ->when($request->search_text, function ($q) use ($request) {
                $searchText = '%' . $request->search_text . '%';                
                $q->where(DB::raw('CONCAT(first_name, " ", IFNULL(last_name, ""))'), 'LIKE', $searchText);               
            })

            ->when($request->sort_by, function ($q) use ($request) {
                $sort_by = $request->sort_by;
                $order_by = $request->order_by;
                $q->orderBy($sort_by, $order_by);
            })

            ->when($request->contact_filter_type, function ($q) use ($request) {                   
                    $q->where('contacts.contact_type', $request->contact_filter_type);                  
            })
            ->latest()
            ->get();

            foreach ($contacts as $contact) {
                $assigneeIds = $contact->assignee_ids; 
                $assigneeIdsArray = explode(',', $assigneeIds);
                $userNames = User::whereIn('id', $assigneeIdsArray)
                                ->pluck('name')
                                ->toArray();
                $contact->commaSeparatedassignee = implode(', ', $userNames);
                
            }
        }
        else
        {
            $contacts = Contact::query()->leftjoin('users', 'users.id', '=', 'contacts.addon_user_id')
                    ->leftjoin('contact_types', 'contact_types.id', '=', 'contacts.contact_type')
                    ->select('contacts.*', 'users.name','contact_types.name as contact_type_name')

            ->whereIn('contacts.id', $request->contact_ids)               
            
            ->latest()
            ->get();
            foreach ($contacts as $contact) {
                $assigneeIds = $contact->assignee_ids; 
                $assigneeIdsArray = explode(',', $assigneeIds);
                $userNames = User::whereIn('id', $assigneeIdsArray)
                                ->pluck('name')
                                ->toArray();
                $contact->commaSeparatedassignee = implode(', ', $userNames);
                
            }
        }

        if($request->doc_type == 'csv')
        {
            if (sizeof($contacts) > 0) {
                for ($i = 0; $i < sizeof($contacts); $i++) {
                    $csvRecord[$i]['customer_name']       = $contacts[$i]['first_name'].' '.$contacts[$i]['last_name'];

                    $csvRecord[$i]['contact_type_name']       = $contacts[$i]['contact_type_name'];

                    $csvRecord[$i]['name']       = $contacts[$i]->commaSeparatedassignee;

                    
                }
            } else {
                $csvRecord = [];
            }
            $headers = array(
                'Content-Type' => 'text/csv',
                //'Content-Disposition' => 'attachment; filename="data.csv"',
            );

            // Create a callback function to write the data to the CSV file
            $callback = function () use ($csvRecord) {
                $output = fopen('php://output', 'w');
                $heading = ['Contact Name', 'Type of contact', 'Assigned Team'];
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
            return Response::stream($callback, 200, $headers);
        }
        else
        {
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
                $pdf_file_name = 'contact.pdf';  
                $pdf = PDF::loadView('frontend.pdf.contact_export', ['contacts' => $contacts,'reportSetting'=>$reportSetting,'userData'=>$userData ,'userList'=>$userList])->setPaper('A4', 'landscape');
                
                return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);                
            }
        }
    }

    
    /*****************************************************/
    # ContactController
    # Function name : generateTaskPdfReport()    
    # Purpose       : Generate Task in PDF format based on input parameters.
    # Params        : Request $request
    /*****************************************************/    
    function generateTaskPdfReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->input('contact_id');
        $taskArray = $request->input('taskArray');
        $action_type = $request->input('actionType');

        $tasks = Task::query()
            ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
            ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
            ->select('tasks.*', 'contacts.first_name', 'contacts.last_name', 'task_status.name as task_status')
            ->whereIn('tasks.id', $taskArray)
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();

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
   
        $isChosenCustomer=0;

        $dateToday = Carbon::now()->format('d_m_Y');
        $pdf_file_name = 'task_' . uniqid() . '_' . $dateToday . '.pdf';
        // Load the view and pass the data
       // $pdf = PDF::loadView('frontend.pdf.task', ['tasks' => $tasks , 'isChosenCustomer'=>0]);

        $pdf = PDF::loadView('frontend.pdf.contact.contact_task_pdf', ['tasks' => $tasks, 'reportSetting'=>$reportSetting,'userData'=>$userData,'userList'=>$userList ,'isChosenCustomer' => $isChosenCustomer ]);

        if ($action_type == 'download') {
            return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
        } else {
            $pdfPath = public_path('client-reports.in/pdf/' . $pdf_file_name);
            $pdf->save($pdfPath);
            return response()->json(['file_name' => $pdf_file_name]);
        }
    }

   
    /*****************************************************/
    # ContactController
    # Function name : generateOpportunityPdfReport()    
    # Purpose       : Generate Opportunity in PDF format.
    # Params        : Request $request
    /*****************************************************/    
    function generateOpportunityPdfReport(Request $request)
    {
        $user_id= auth()->user()->id;
        $opportunityArray = $request->input('opportunityArray');
        $action_type = $request->input('actionType');

        $opportunities = Opportunity::query()
            ->leftjoin('opportunity_types', 'opportunity_types.id', '=', 'opportunities.opportunity_type')
            ->leftjoin('contacts', 'contacts.id', '=', 'opportunities.contact_id')
            ->leftJoin('users as assignee', 'assignee.id', '=', 'opportunities.assignee_id')
            ->whereIn('opportunities.id', $opportunityArray)
            ->select('opportunities.*', 'contacts.first_name', 'contacts.last_name', 'opportunity_types.name as opportunity_type_name', 'assignee.name as assignee_name')
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();

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
            $isChosenCustomer=0;

        $dateToday = Carbon::now()->format('d_m_Y');
        $pdf_file_name = 'opportunity_' . uniqid() . '_' . $dateToday . '.pdf';
        // Load the view and pass the data
       // $pdf = PDF::loadView('frontend.pdf.opportunity_list', ['opportunities' => $opportunities]);

       $pdf = PDF::loadView('frontend.pdf.contact.contact_opportunity_pdf', ['opportunities' => $opportunities, 'reportSetting'=>$reportSetting,'userData'=>$userData,'userList'=>$userList ,'isChosenCustomer' => $isChosenCustomer ]);
        if ($action_type == 'download') {
            return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
        } else {
            $pdfPath = public_path('client-reports.in/pdf/' . $pdf_file_name);
            $pdf->save($pdfPath);
            return response()->json(['file_name' => $pdf_file_name]);
        }
    }

 
    /*****************************************************/
    # ContactController
    # Function name : generateAgendaCsvReport()    
    # Purpose       : generating CSV report containing agenda details based on the provided agenda IDs.
    # Params        : Request $request
    /*****************************************************/  
    function generateAgendaCsvReport(Request $request)
    {
        $agendaArray = $request->input('agendaArray');
        \DB::enableQueryLog();
        $agendas = Agenda::with('contact')
            ->whereIn('id', $agendaArray)
            ->where('is_deleted', '=', 0)
            ->latest()
            ->get();


        //dd(\DB::getQueryLog());

        if (sizeof($agendas) > 0) {
            for ($i = 0; $i < sizeof($agendas); $i++) {
                if ($agendas[$i]['id'] < 10) {
                    $prefixedId = 'T00' . $agendas[$i]['id'];
                } else {
                    $prefixedId = 'T0' . $agendas[$i]['id'];
                }
                $csvRecord[$i]['sn']       = $prefixedId;
                $csvRecord[$i]['agenda_name']       = $agendas[$i]['title'];

                $csvRecord[$i]['contact_name']       = isset($agendas[$i]->contact['full_name']) ? $agendas[$i]->contact['full_name'] : '';
            }
        } else {
            $csvRecord = [];
        }

        // Set the CSV response headers
        $headers = array(
            'Content-Type' => 'text/csv',
            //'Content-Disposition' => 'attachment; filename="data.csv"',
        );

        // Create a callback function to write the data to the CSV file
        $callback = function () use ($csvRecord) {
            $output = fopen('php://output', 'w');
            $heading = ['SI No.','Agenda Name', 'Contact Name'];
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
        return Response::stream($callback, 200, $headers);
    }

    /*****************************************************/
    # ContactController
    # Function name : generateMeetingCsvReport()    
    # Purpose       : generating CSV report containing meeting details based on the provided meeting IDs.
    # Params        : Request $request
    /*****************************************************/ 
    function generateMeetingCsvReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $contact_id = $request->input('contact_id');
        $csv_heading =  ['Sl No.','Contact Name', 'Contact Category', 'Meeting Date', 'Meeting Time'];
        $meetingArray = $request->input('meetingArray');

        \DB::enableQueryLog();

        $meetings = Meeting::query()->with('contact')
            ->whereIn('meetings.id', $meetingArray)
            ->notDeleted()
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();
        foreach ($meetings as $meeting) {
            $contact_type = DB::table('contact_types')                  
                ->select('contact_types.name as contact_type_name')
                ->where('contact_types.id', $meeting->contact->contact_type)
                ->first();
            $meeting->contact_type = (!empty($contact_type)) ? $contact_type : '';
        } 


        //dd(\DB::getQueryLog());

        if (sizeof($meetings) > 0) {
            for ($i = 0; $i < sizeof($meetings); $i++) {
                if ($meetings[$i]['id'] < 10) {
                    $prefixedId = 'T00' . $meetings[$i]['id'];
                } else {
                    $prefixedId = 'T0' . $meetings[$i]['id'];
                }
                $slno = !empty($meetings[$i]['meeting_unique_id'])?$meetings[$i]['meeting_unique_id']:$prefixedId;
                $csvRecord[$i]['sn']       = $slno;

                $csvRecord[$i]['contact_name']       = isset($meetings[$i]->contact->full_name) ? $meetings[$i]->contact->full_name : '';

                $csvRecord[$i]['contact_type']       = isset($meetings[$i]->contact_type->contact_type_name)?$meetings[$i]->contact_type->contact_type_name: '';

                $csvRecord[$i]['meeting_date']       = (!empty($meetings[$i]['meeting_date'])) ? date('d/m/Y', strtotime($meetings[$i]['meeting_date'])) : '';

                $csvRecord[$i]['meeting_time']       = (!empty($meetings[$i]['meeting_time'])) ? date('h:i A', strtotime($meetings[$i]['meeting_time'])) : '';
            }
        } else {
            $csvRecord = [];
        }

        // Set the CSV response headers
        $headers = array(
            'Content-Type' => 'text/csv',
            //'Content-Disposition' => 'attachment; filename="data.csv"',
        );

        // Create a callback function to write the data to the CSV file
        $callback = function () use ($csvRecord, $csv_heading) {
            $output = fopen('php://output', 'w');
            $heading = $csv_heading;
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
        return Response::stream($callback, 200, $headers);
    }

  
    /*****************************************************/
    # ContactController
    # Function name : generateTaskCsvReport()    
    # Purpose       : generating CSV report containing task details based on the provided task IDs.
    # Params        : Request $request
    /*****************************************************/ 
    function generateTaskCsvReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $taskArray = $request->input('taskArray');

        $tasks = Task::query()
            ->leftjoin('contacts', 'contacts.id', '=', 'tasks.contact_id')
            ->leftjoin('task_status', 'task_status.id', '=', 'tasks.status')
            ->select('tasks.*', 'contacts.first_name', 'contacts.last_name', 'task_status.name as task_status')
            ->whereIn('tasks.id', $taskArray)
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();

        if (sizeof($tasks) > 0) {
            for ($i = 0; $i < sizeof($tasks); $i++) {
                if ($tasks[$i]['id'] < 10) {
                    $prefixedId = 'T00' . $tasks[$i]['id'];
                } else {
                    $prefixedId = 'T0' . $tasks[$i]['id'];
                }
                $csvRecord[$i]['sn']       = $prefixedId;

                $csvRecord[$i]['title']       = $tasks[$i]->tittle;

                $csvRecord[$i]['contact_name']       = isset($tasks[$i]->contact['full_name']) ? $tasks[$i]->contact['full_name'] : '';

                $csvRecord[$i]['value']       = ($tasks[$i]->priority == '1') ? 'High' : ($tasks[$i]->priority == '2' ? 'Medium' : ($tasks[$i]->priority == '3' ? 'Low' : ''));

                $csvRecord[$i]['deadline']       = isset($tasks[$i]->deadline) ? $tasks[$i]->deadline : '';
             
                $csvRecord[$i]['status']       = isset($tasks[$i]->status) ? $tasks[$i]->task_status : '';
            }
        } else {
            $csvRecord = [];
        }

        // Set the CSV response headers
        $headers = array(
            'Content-Type' => 'text/csv',
            //'Content-Disposition' => 'attachment; filename="data.csv"',
        );

        // Create a callback function to write the data to the CSV file
        $callback = function () use ($csvRecord) {
            $output = fopen('php://output', 'w');
            $heading = ['Sl No','Task Title', 'Contact Name', 'Value', 'Deadline', 'Status'];
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
        return Response::stream($callback, 200, $headers);
    }

   
    /*****************************************************/
    # ContactController
    # Function name : generateOpportunityCsvReport()    
    # Purpose       : generating CSV report containing opportunity details based on the provided opportunity IDs.
    # Params        : Request $request
    /*****************************************************/ 
    function generateOpportunityCsvReport(Request $request)
    {
        $user_id = auth()->user()->id;
        $opportunityArray = $request->input('opportunityArray');
        \DB::enableQueryLog();
        //$priorityValues = [];
        $opportunities = Opportunity::query()
            ->leftjoin('opportunity_types', 'opportunity_types.id', '=', 'opportunities.opportunity_type')
            ->leftjoin('contacts', 'contacts.id', '=', 'opportunities.contact_id')
            ->whereIn('opportunities.id', $opportunityArray)
            ->select('opportunities.*', 'contacts.first_name', 'contacts.last_name', 'opportunity_types.name as opportunity_type_name')
            ->orderBy('row_order', 'ASC')
            ->latest()
            ->get();

        foreach ($opportunities as $opportunity) {
            $family_member = DB::table('contact_family_member')
                ->leftjoin('contacts', 'contacts.id', '=', 'contact_family_member.family_member_id')
                ->select('contacts.first_name', 'contacts.last_name', 'contact_family_member.family_member_id', 'contact_family_member.relationship', 'contact_family_member.hof')
                ->where('contact_family_member.contact_id', $opportunity->contact_id)
                ->where('contact_family_member.hof', '0')->get()->toArray();
            $opportunity->family_member = (!empty($family_member)) ? $family_member : '';
        }
        //dd(\DB::getQueryLog());

        if (sizeof($opportunities) > 0) {
            for ($i = 0; $i < sizeof($opportunities); $i++) {
                if ($opportunities[$i]['id'] < 10) {
                    $prefixedId = 'T00' . $opportunities[$i]['id'];
                } else {
                    $prefixedId = 'T0' . $opportunities[$i]['id'];
                }
                $oppId = !empty($opportunities[$i]['opportunity_unique_id'])?$opportunities[$i]['opportunity_unique_id']:$prefixedId;

                $csvRecord[$i]['opp_id']       = $oppId;

                $csvRecord[$i]['opportunity_type']       = $opportunities[$i]['opportunity_type_name'];

                $csvRecord[$i]['contact_name']       = isset($opportunities[$i]->contact['full_name']) ? $opportunities[$i]->contact['full_name'] : '';
                $csvRecord[$i]['expected_value']       = $opportunities[$i]['expected_value'];

                $csvRecord[$i]['Priority']       = ($opportunities[$i]['priority'] == '1') ? "High" : (($opportunities[$i]['priority'] == "2") ? "Medium" : "Low");

                $csvRecord[$i]['follow_up_date']       = $opportunities[$i]['follow_up_date'];
                //$csvRecord[$i]['status']       = ($opportunities[$i]['status'] == '1') ? "On hold" : "Converted";

                $csvRecord[$i]['status']       = ($opportunities[$i]['status'] == '1') ? "Converted" : ($opportunities[$i]['status'] == '2' ? "In Progress" : ($opportunities[$i]['status'] == '3' ? "Not Interested" : ($opportunities[$i]['status'] == '4' ? "On hold" : ($opportunities[$i]['status'] == '5' ? "Potential" : ""))));


                $csvRecord[$i]['is_bookmark']       = ($opportunities[$i]['is_bookmark'] == '1') ? "Yes" : "No";
                $csvRecord[$i]['assignee_id']       = (!empty($opportunities[$i]->user->name)) ? $opportunities[$i]->user->name : " ";

                $csvRecord[$i]['opportunity in the name of']       =
                    (!empty($opportunities[$i]['family_member'])) ? $opportunities[$i]['family_member'][0]->first_name . ' ' . $opportunities[$i]['family_member'][0]->last_name : 'Self';
            }
        } else {
            $csvRecord = [];
        }

        // Set the CSV response headers
        $headers = array(
            'Content-Type' => 'text/csv',
            //'Content-Disposition' => 'attachment; filename="data.csv"',
        );

        // Create a callback function to write the data to the CSV file
        $callback = function () use ($csvRecord) {
            $output = fopen('php://output', 'w');
            $heading = ['Opp ID','Opportunity Type', 'Contact Name', 'Expected Value', 'Priority', 'Followup Date', 'Status', 'Is Bookmark', 'Assignee', 'opportunity in the name of'];
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
        return Response::stream($callback, 200, $headers);

    }

    
    /*****************************************************/
    # ContactController
    # Function name : deleteContact()    
    # Purpose       : Deleting a contact and its related family members from the database while keeping a record of who deleted them and when.
    # Params        : Request $request
    /*****************************************************/     
    public function deleteContact(Request $request)
    {             
        $id = ($request->id);
        try {
            $contact = Contact::find($id);
            $contact->is_deleted = 1;
            $contact->deleted_by = auth()->user()->id;
            $contact->deleted_at = date('Y-m-d H:i:s');
            $contact->save();

            $familyMembers = ContactFamilyMember::where('contact_id', $id)->orWhere('family_member_id', $id)->get();

            foreach ($familyMembers as $familyMember) {
                $familyMember->is_deleted = 1;
                $familyMember->deleted_by = auth()->user()->id;
                $familyMember->deleted_at = date('Y-m-d H:i:s');
                $familyMember->save();
            }

             //Send deletion mail to assigned user 
            $notification_module_id= 5;
            $notification_type= 3;
            $action_for= 2;
            $notification_title= 'delete_notification';

            $emailConfigStatus = Helper::notificationConfigCheck($notification_module_id, $notification_type,$action_for,$notification_title);

            $inapp_notification_type= 1;      

            $inAppConfigStatus = Helper::notificationConfigCheck($notification_module_id, $inapp_notification_type,$action_for,$notification_title);

            if($emailConfigStatus==1 || $inAppConfigStatus==1 ){
                $this->notifyDeletionContact($contact, $emailConfigStatus, $inAppConfigStatus);
            }
           
            
            return response()->json(['success' => true, 'message' => 'Contact has been deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Oops!! Something went wrong']);
        }

    }

    /*****************************************************/
    # ContactController
    # Function name : notifyDeletionContact()    
    # Purpose       :  Send inapp and email notification  to  assignees about the deletion of contacts. 
    # Params        : $contact,$emailConfigStatus, $inAppConfigStatus
    /*****************************************************/
    public function notifyDeletionContact($contact, $emailConfigStatus, $inAppConfigStatus){        
       
        $assignee_ids= explode(',', $contact->assignee_ids);       
        
        if($assignee_ids !=''){        
                            
            $contactType =contactType::findOrFail($contact->contact_type);                 
            
            $userData['contact_type'] = $contactType->name;     
            $userData['contact_email'] = $contact->email;                  
            $userData['createdBy'] = auth()->user()->name;
            $userData['dob'] =  $contact->dob;
        
            $userData['contactName'] = $contact->first_name.' '.$contact->last_name;
            $userData['contactNumber'] = $contact->mobile_code.' '.$contact->mobile_number;
            //$assignee_ids= explode(',', $contact->assignee_id);
          
            foreach($assignee_ids as $assignee){ 
                $assigneeDetails = User::findOrFail($assignee);                    
                $assigneeEmail = $assigneeDetails->email; 
                $userData['email'] = $assigneeEmail;                                   
                
                if($emailConfigStatus==1){
                    Mail::send('email_template.contact_deletion_email', ['user' => $userData], function ($m) use ($userData) {
                        $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                        $m->to($userData['email'])->subject("M-Edge");
                    });
                }            

                //inapp notification
                if($inAppConfigStatus==1){
                    $contact_name= $userData['contactName'];  
                    $html = auth()->user()->name. " has deleted a contact which has been assigned to you  <$contact->contact_unique_id> - <$contact_name> on " .date('d-m-Y h:i:A').". Click on this to have a view & take actions on Contact Dashboard.";
                   
                    $notification = new Notification;
                    $notification->title =$html;               
                    $notification->link = \URL::route('contacts');
                    $notification->user_id = $assignee ;
                    $notification->created_by = auth()->user()->id;
                    $notification->save();
                }
            }
        } 
        
    }

          
    /*****************************************************/
    # ContactController
    # Function name : bulkDeleteContact()    
    # Purpose       : Bulk deletion of contacts and their associated family members.
    # Params        : Request $request
    /*****************************************************/  
     public function bulkDeleteContact(Request $request)
     {             
         $contact_ids = ($request->contact_ids);
         $delete_type= $request->delete_type;
         //echo $delete_type;exit; 
         set_time_limit(900);                
         try {
           if($delete_type=='selected'){
                
                 // Batch update for contacts
                Contact::whereIn('id', $contact_ids)->update([
                    'is_deleted' => 1,
                    'deleted_by' => auth()->user()->id,
                    'deleted_at' => now(),
                ]);
                set_time_limit(900);

                // Batch update for family members
                ContactFamilyMember::whereIn('contact_id', $contact_ids)->update([
                    'is_deleted' => 1,
                    'deleted_by' => auth()->user()->id,
                    'deleted_at' => now(),
                ]); 
                set_time_limit(900);
                /*foreach ($contact_ids as $contact_id) {
                    $contact = Contact::find($contact_id);            
                    $contact->is_deleted = 1;
                    $contact->deleted_by = auth()->user()->id;
                    $contact->deleted_at = date('Y-m-d H:i:s');
                    $contact->save();
        
                    $familyMembers = ContactFamilyMember::where('contact_id', $contact_id)->get();
        
                    foreach ($familyMembers as $familyMember) {
                        $familyMember->is_deleted = 1;
                        $familyMember->deleted_by = auth()->user()->id;
                        $familyMember->deleted_at = date('Y-m-d H:i:s');
                        $familyMember->save();
                    }            
                }*/
            }else{
               // $contacts = Contact::where('created_by', auth()->user()->id)->get();
               
                // Batch update for contacts
                Contact::where('created_by', auth()->user()->id)->update([
                    'is_deleted' => 1,
                    'deleted_by' => auth()->user()->id,
                    'deleted_at' => now(),
                ]);
                set_time_limit(900); 
                // Batch update for family members
                ContactFamilyMember::whereIn('contact_id', function ($query) {
                    $query->select('id')->from('contacts')->where('created_by', auth()->user()->id);
                })->update([
                    'is_deleted' => 1,
                    'deleted_by' => auth()->user()->id,
                    'deleted_at' => now(),
                ]);
               /* foreach ($contacts as $contact) {                   
                    $contact->is_deleted = 1;
                    $contact->deleted_by = auth()->user()->id;
                    $contact->deleted_at = date('Y-m-d H:i:s');
                    $contact->save();

                    // Soft delete associated family members
                    $familyMembers = ContactFamilyMember::where('contact_id', $contact->id)->get();

                    foreach ($familyMembers as $familyMember) {
                        $familyMember->is_deleted = 1;
                        $familyMember->deleted_by = auth()->user()->id;
                        $familyMember->deleted_at = date('Y-m-d H:i:s');
                        $familyMember->save();
                    }
                } */ 

            }
             return response()->json(['success' => true, 'message' => 'Contact has been deleted successfully.']);
         } catch (\Exception $e) {
             return response()->json(['success' => false, 'message' => 'Oops!! Something went wrong']);
         }
 
     }

     
    /*****************************************************/
    # ContactController
    # Function name : updatecontactSample()    
    # Purpose       : Update a contact sample Excel file by clearing existing data and writing new contact types.
    # Params        : Request $request
    /*****************************************************/  
    public function updatecontactSample(Request $request)
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $created_by =$user_id;
        }else{
            $created_by =$userDetails->added_by;
        }
        $row = 3;
        $contactType = ContactType::where('is_deleted', 0)->where('status', 1)->where('id', '!=', 9)->whereIn('created_by',[0,$created_by])->orderBy('row_order','ASC')->latest()->get();  
        // Get the file path
        $filePath = public_path('uploads/contacts/contact_sample.xlsx');
    
        // Load the existing Excel file
        $spreadsheet = IOFactory::load($filePath);
        
        // Try to get the 'setting' worksheet
        $worksheet = $spreadsheet->getSheetByName('Settings');
    
        // Check if the 'setting' worksheet exists, if not create a new one
        if (!$worksheet) {
            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Settings');
            $spreadsheet->addSheet($worksheet, 0);
        }
    
        // Assuming the last row is where you want to insert the new data
        //$lastRow = $worksheet->getHighestRow() + 1;
        //$lastRowA = $worksheet->getHighestRow('B') + 1;
        $highestRowB = $worksheet->getHighestRow('B');
        $highestRowG = $worksheet->getHighestRow('G');
        
        // Clear data from column B starting from B3
        for ($i = 3; $i <= $highestRowB; $i++) {
            $worksheet->setCellValue('B' . $i, null);
        }
        
        // Now, write the data to the next available row
        foreach($contactType as $type){
            $worksheet->setCellValue('B'.$row , $type->name);
            $row++;
        }
        for ($i = 3; $i <= $highestRowG; $i++) {
            $worksheet->setCellValue('G' . $i, null);
        }
        $row=3;
       for($j=1980; $j <= date('Y'); $j++){
            $worksheet->setCellValue('G'.$row , $j);
            $row++;
        }        
    
        // Save the Excel file
        $writer = IOFactory::createWriter($spreadsheet, "Xlsx");
        $writer->save($filePath);   
        
        return response()->json(['success' => true]);
    }

    /*****************************************************/
    # ContactController
    # Function name : exportExcelNotes()    
    # Purpose       : Export contact notes related to a specified contact in Excel format.
    # Params        : Request $request
    /*****************************************************/  
    public function exportExcelNotes(Request $request)
    {
        $contact_id= base64_decode($request->input('contact_id'));      
        $notes = ContactNotes::where('contact_id', $contact_id)
        ->where('is_deleted', 0)->get();
        $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet(); 
        
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
            ];
            $sheet->getStyle('F1')->applyFromArray($styleArray);
            $sheet->getRowDimension(1)->setRowHeight(30);
            $sheet->setCellValue('A1', 'Heading');
            $sheet->setCellValue('B1', 'Description');         

            $headerStyle = [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => '28336b',
                    ],
                ],
                'font'  => [
                    'color' => [
                        'argb' => 'FFFFFFFF',
                    ],
                    'bold' => true,
                ],
                'alignment' => [
                    'indent' => 1,
                ],
            ];
            $sheet->getStyle('A1:B1')->applyFromArray($headerStyle);
            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(75);
            
        // Wrap text in all columns
        $sheet->getStyle('A:B')->getAlignment()->setWrapText(true);

        // Vertically align text in the middle for all columns
        $sheet->getStyle('A:B')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $row = 2;
            foreach ($notes as $note) {             
                $sheet->setCellValue('A' . $row, $note->label);
                $sheet->setCellValue('B' . $row, $note->notes);            
                $row++;
            }

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); 
            $fileName = 'notes.xlsx';
            $temp_file = tempnam(sys_get_temp_dir(), $fileName);

            $writer->save($temp_file);

            return response()->download($temp_file, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
    }

  
    /*****************************************************/
    # ContactController
    # Function name : exportPdfNotes()    
    # Purpose       : Export contact notes related to a specified contact in PDF format.
    # Params        : Request $request
    /*****************************************************/  
    function exportPdfNotes(Request $request)
    { 
        $user_id= auth()->user()->id;
        $contact_id= base64_decode($request->input('contact_id'));  
        $dateToday = Carbon::now()->format('d_m_Y');
        $pdf_file_name = 'contact_notes' . uniqid() . '_' . $dateToday . '.pdf';
        $notes = ContactNotes::where('contact_id', $contact_id)
        ->where('is_deleted', 0)->get();

        $reportData=ReportSetting::where('created_by', $user_id)->first();
        if(empty($reportData)){
            $reportSetting=ReportSetting::where('created_by', 0)->first(); //default custom design
        }else{
            $reportSetting=ReportSetting::where('created_by', $user_id)->first();
        }
        $userData = User::where('id',$user_id)->first();
        if($userData->added_by ==0){
            $userData = User::where('id',$user_id)->first();
        }else{
            $userData = User::where('id',$userData->added_by)->first();
        }
        $contactData = Contact::select('first_name', 'last_name','mobile_code','mobile_number','email', 'profile_pic')->where('id',$contact_id)->first();
      
         $pdf = PDF::loadView('frontend.pdf.contact_notes', ['notes' => $notes, 'reportSetting' =>$reportSetting,'userData'=>$userData, 'contactData' => $contactData ]);
        return $pdf->download($pdf_file_name)->header('File-Name', $pdf_file_name);
        
    }

  
    /*****************************************************/
    # ContactController
    # Function name : searchUser()    
    # Purpose       : Fetch assignee.
    # Params        : Request $request
    /*****************************************************/  
    public function searchUser()
    {
        $user_id = auth()->user()->id;
        $userDetails = User::findOrFail($user_id);
        if(auth()->user()->added_by ==0){
            $assigneeData = User::select("id", "name")           
            ->where('added_by', $user_id)
            ->where('id', '!=', $user_id)
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->orderBy('name', 'ASC')
            ->get();
        }else{
            if( $userDetails->group_id =='' ||  $userDetails->group_id ==null){
                $assigneeData = User::select("id", "name")                
                ->where('added_by', $user_id)
                ->where('id', '!=', $user_id)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->where('role_id', 2)
                ->orderBy('name', 'ASC')
                ->get();

            }else{
                $assigneeData = User::select("id", "name")                
                ->where('group_id', $userDetails->group_id)
                ->where('id', '!=', $user_id)
                ->where('is_deleted', 0)
                ->where('status', 1)
                ->where('role_id', 2)
                ->orderBy('name', 'ASC')
                ->get();
            }
            
        }
        return $assigneeData;       

        //return response()->json($contactData);
    }
    
    
    /*****************************************************/
    # ContactController
    # Function name : bulkAssignContact()    
    # Purpose       : Assign contacts to one or more assignees, catering to both bulk and selected assignment scenarios.
    # Params        : Request $request
    /*****************************************************/ 
    public function bulkAssignContact(Request $request)
    {
        $contact_ids = $request->input('contactArray');
        $assignee_id = $request->input('assignee_id');
        $assign_type= $request->input('assign_type');

        try {
            if($assign_type=='selected'){
                 foreach ($contact_ids as $contact_id) {
                     $contact = Contact::find($contact_id);  
                     if(!empty($contact->assignee_ids)){
                        $currentAssignees = explode(',', $contact->assignee_ids);
                        $currentAssignees[] =  $assignee_id;
                        $uniqueAssignees = array_unique($currentAssignees);
                        $newAssigneeIds = implode(',', $uniqueAssignees);                     
                        
                     }
                     else{
                        $newAssigneeIds=$assignee_id;
                     }
                            
                     $contact->assignee_ids = $newAssigneeIds;
                     $contact->save();        
                               
                 }
             }else{
                 $contacts = Contact::where('created_by', auth()->user()->id)->get();
                
                 foreach ($contacts as $contact) {
                    if(!empty($contact->assignee_ids)){
                       $currentAssignees = explode(',', $contact->assignee_ids);
                       $currentAssignees[] =  $assignee_id;
                       $uniqueAssignees = array_unique($currentAssignees);
                       $newAssigneeIds = implode(',', $uniqueAssignees);    
                    }
                    else{
                       $newAssigneeIds=$assignee_id;
                    }
                           
                    $contact->assignee_ids = $newAssigneeIds;
                    $contact->update(['assignee_ids'=>$newAssigneeIds]);
                         
                              
                }
             }
              return response()->json(['success' => true, 'message' => 'Contact has been assigned successfully.']);
          } catch (\Exception $e) {
              return response()->json(['success' => false, 'message' => 'Oops!! Something went wrong']);
          }    
    }

    /*****************************************************/
    # ContactController
    # Function name : checkdelete()    
    # Purpose       : Check contact deleted or not, and return the result as a JSON response.
    # Params        : Request $request
    /*****************************************************/ 

    public function checkdelete(Request $request){
        try{
            $contactId= base64_decode($request->input('contactId'));
            $contactIdType= $request->input('contactIdType');
            $table= ($contactIdType=='lead')?'leads':'contacts';

            $is_deleted= DB::table($table)->select('is_deleted')
                ->where('id', $contactId)->first();
                return response()->json($is_deleted);
        }catch (\Exception $e) {
                return response()->json(['error' => false, 'message' => 'Oops!! Something went wrong']);
        }
    }

    /*****************************************************/
    # ContactController
    # Function name : isFamilyExist()    
    # Purpose       : If a family exists for a given contact ID by checking the number of family members associated with that contact in the database. If there's more than one family member, it concludes that a family exists
    # Params        : Request $request
    /*****************************************************/   
    public function isFamilyExist(Request $request)
    {    
        $contactId = $request->input('contactId');        
        $isFamilyExist= Helper::isFamilyExist($contactId);
        if($isFamilyExist){
            return response()->json(['success' => true]);                      
        }else{
            return response()->json(['success' => false]);     
        }
    }
    // invast notifiy amit
    public function invasStr($oldStatusStr, $newStatusStr, $schemes_name){
        $str = "<tr><td style=' width: 33%; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000; padding: 5px; border-right: 1px solid #C6D7FF; vertical-align: top;'>$schemes_name</td>
        <td style='width: 33%; border-right: 1px solid #C6D7FF;  font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;'>$oldStatusStr </td>
        <td style='font-size: 12px; padding:5px 5px 5px 20px; vertical-align: top;'>$newStatusStr</td></tr>";

        return $str;
    }
    // invast notifiy amit xxx

    /*****************************************************/
    # ContactController
    # Function name : notifyModificationContact()    
    # Purpose       :  Send inapp and email notification  to  assignees about the modification of contacts. 
    # Params        : $contact,$oldContact,$emailConfigStatus,$inAppConfigStatus
    /*****************************************************/
    // invast notifiy amit
    public function notifyModificationContact($contact, $oldContact,$emailConfigStatus,$inAppConfigStatus, $oldContactInvestment){ // $oldContactInvestment
    
        $contactInvestment = ContactInvestment::with(['opportunity_type','investment_status'])->where('contact_id', $contact->id)->get();
        
        $investmentStr = '';
        $invasStatus = [];
        $invasRemarks = [];

        foreach ($contactInvestment as $key => $ci) {
            $is_match = 0;
            foreach ($oldContactInvestment as $key => $oci) {
                if ($oci->schemes_name == $ci->schemes_name) {

                    if ($oci->status != $ci->status) 
                    {
                        
                        $oldStatusStr = $oci->opportunity_type->name .' scheme status '. $oci->investment_status->name ;
                        $newStatusStr = $ci->opportunity_type->name .' scheme status '.$ci->investment_status->name;
                        $invasStatus[] = $this->invasStr($oldStatusStr, $newStatusStr, $oci->opportunity_type->name);
                    }
    
                    if ($oci->remarks != $ci->remarks) 
                    {
                        $investmentStr .= 'Remarks : '.$oci->opportunity_type->name .'scheme remarks '. $oci->remarks .'->'. $ci->remarks;
                        $investmentStr .= "<br>";

                        $oldRemarksStr = $oci->opportunity_type->name .' scheme remarks '. $oci->remarks;
                        $newRemarksStr = $ci->opportunity_type->name .' scheme remarks '.$ci->remarks;
                        $invasRemarks[] = $this->invasStr($oldRemarksStr, $newRemarksStr, $oci->opportunity_type->name);
                    }
                    $is_match = 1;
                }
        
            }
            if ($is_match == 0) {
                $update_Investment_status = 1;
                $investmentStr .= 'New '. $ci->opportunity_type->name .' schame is added. Status is '. $ci->investment_status->name .' and Remaks is '. $ci->remarks;
        
                $investmentStr .= "<br>";
            }
        }
        
        // invast notifiy amit xxx
        $assignee_ids= explode(',', $contact->assignee_ids);
        
        
        if($assignee_ids!='' ){

            $oldContactType =contactType::findOrFail($oldContact->contact_type);
                                
            $oldData['contact_name'] = $oldContact->first_name.' '.$oldContact->last_name;
            $oldData['contact_type'] = $oldContactType->name;     
            $oldData['contact_email'] = $oldContact->email;                  
            $oldData['createdBy'] = auth()->user()->name;
            $oldData['dob'] =   ($oldContact->dob!='')?date('d F, Y', strtotime($oldContact->dob)):'';         
           
            $oldData['contactNumber'] = $oldContact->mobile_code.' '.$oldContact->mobile_number;

            if($oldContact->referred_by!=''){
                $referredBy =Contact::Select('first_name', 'last_name')->where('id', $oldContact->referred_by)->first();
                $oldData['refrrer_name']= $referredBy->first_name.' '.$referredBy->last_name;
            }else{
                $oldData['refrrer_name'] ='';
            }
            $oldassignee_ids= explode(',', $oldContact->assignee_ids);
            $oldData['assigned_team'] = User::whereIn('id', $oldassignee_ids)->pluck('name')->implode(', ');

            $oldloyalty_rank=DB::table('loyalty_rank')->select('name')->where('id',$oldContact->loyalty_rank)->first();

            $oldData['loyalty_rank'] = !empty($oldloyalty_rank->name)?$oldloyalty_rank->name:''; 
            $oldaccount_type = DB::table('account_type')->select('name')->where('id',$oldContact->account_type)->first(); 
            $oldData['account_type'] = !empty($oldaccount_type->name)?$oldaccount_type->name:'';
            $oldpotential = DB::table('client_potentials')->select('name')->where('id',$oldContact->potential)->first();
            $oldData['potential']= !empty($oldpotential->name)?$oldpotential->name:''; 
                     
            $oldData['address_one'] = $oldContact->address_one;
            $oldData['address_two'] = $oldContact->address_two;
            $oldData['client_since'] = $oldContact->client_since; 
            
            $oldData['address_one'] = $oldContact->address_one;
            $oldData['address_two'] = $oldContact->address_two;    
            
            if (preg_match('/^\d+$/', $oldContact->state_id)){
                $oldstate= DB::table('states')->select('name')->where('id',$oldContact->state_id)->first();
                $oldstate_name= $oldstate->name;
            }else{
                $oldstate_name= $oldContact->state_id;
            }
            if (preg_match('/^\d+$/', $oldContact->city_id)){
                $oldcity= DB::table('cities')->select('city')->where('id',$oldContact->city_id)->first();
                $oldcity_name= $oldcity->city;
            }else{
                $oldcity_name= $oldContact->city_id;
            }

            $oldData['state'] = $oldstate_name;
            $oldData['city'] = $oldcity_name;
                            
            $newContactType =contactType::findOrFail($contact->contact_type);
                                
            $newData['contact_name'] = $contact->first_name.' '.$contact->last_name;
            $newData['contact_type'] = $newContactType->name;     
            $newData['contact_email'] = $contact->email;                  
            $newData['createdBy'] = auth()->user()->name;
            $newData['dob'] =  ($contact->dob!='')?date('d F, Y', strtotime($contact->dob)):'';        
           
            $newData['contactNumber'] = $contact->mobile_code.' '.$contact->mobile_number;

            
            if($contact->referred_by!=''){
                $referredBy =Contact::Select('first_name', 'last_name')->where('id', $contact->referred_by)->first();
                $newData['refrrer_name']= $referredBy->first_name.' '.$referredBy->last_name;
            }else{
                $newData['refrrer_name'] ='';
            }
            $oldassignee_ids= explode(',', $contact->assignee_ids);
            $newData['assigned_team'] = User::whereIn('id', $oldassignee_ids)->pluck('name')->implode(', ');

            $loyalty_rank=DB::table('loyalty_rank')->select('name')->where('id',$contact->loyalty_rank)->first();

            $newData['loyalty_rank'] = !empty($loyalty_rank->name)?$loyalty_rank->name:'';
            
            $account_type = DB::table('account_type')->select('name')->where('id',$contact->account_type)->first(); 
            $newData['account_type'] = !empty($account_type->name)?$account_type->name:'';
            $potential = DB::table('client_potentials')->select('name')->where('id',$contact->potential)->first();
            $newData['potential']= !empty($potential->name)?$potential->name:''; 
                     
            $newData['address_one'] = $contact->address_one;
            $newData['address_two'] = $contact->address_two;
            $newData['client_since'] = $contact->client_since; 
            
            $newData['address_one'] = $contact->address_one;
            $newData['address_two'] = $contact->address_two;

            if (preg_match('/^\d+$/', $contact->state_id)){
                $state= DB::table('states')->select('name')->where('id',$contact->state_id)->first();
                $state_name= $state->name;
            }else{
                $state_name= $contact->state_id;
            }
            if (preg_match('/^\d+$/', $contact->city_id)){
                $city= DB::table('cities')->select('city')->where('id',$contact->city_id)->first();
                $city_name= $city->city;
            }else{
                $city_name= $contact->city_id;
            }

            $newData['state'] = $state_name;
            $newData['city'] = $city_name;

            foreach($assignee_ids as $assignee){ 
                $assigneeDetails = User::findOrFail($assignee);                    
                $assigneeEmail = $assigneeDetails->email; 
                $newData['email'] = $assigneeEmail; 
                if($emailConfigStatus==1){
                    Mail::send('email_template.contact_modification_email', [
                        'newData' => $newData, 
                        'oldData' => $oldData,
                        // invast notifiy amit
                        'invasStatus' => $invasStatus,
                        'invasRemarks' => $invasRemarks,
                        // invast notifiy amit xxx
                    ], function ($m) use ($newData) {
                        $m->from(\Config::get('constants.fromEmail'), \Config::get('constants.fromName'));
                        $m->to($newData['email'])->subject("M-Edge");
                    });
                }

                 //inapp notification
                 if($inAppConfigStatus==1){
                    $contact_name= $newData['contact_name'];  
                    $html = auth()->user()->name. " has modify a contact which has been assigned to you  <$contact->contact_unique_id> - <$contact_name> on " .date('d-m-Y h:i:A').". Click on this to have a view & take actions on Contact Dashboard.";
                   
                    $notification = new Notification;
                    $notification->title =$html;               
                    $notification->link = \URL::route('contacts');
                    $notification->user_id = $assignee ;
                    $notification->created_by = auth()->user()->id;
                    $notification->save();
                }
            }
        } 
    }



}
