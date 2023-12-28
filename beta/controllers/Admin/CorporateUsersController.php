<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;

use Maatwebsite\Excel\Facades\Excel;

use App\User;
use App\Models\Membership;
use App\Models\Userdataexport;
use App\Models\Usersubscriptionsexport;
use App\Models\Displayinfo;
use Auth;
use DB;
use Hash;

use App\Exports\ListExport;
use App\Exports\UsersdataExport;
use App\Exports\UserssubscriptiondataExport;



class CorporateUsersController extends Controller
{
    public function index(Request $request){
        // dd(Auth::user());
        // echo "<pre>"; print_r(session()->get('adminAuth')); exit;
        if ($request->ajax()) {
            $data = User::whereNull('user_id')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('logo', function ($row) {
                    if(!empty($row->company_logo)){
                    $url=asset("uploads/logo/$row->company_logo");
                    return '<img src='.$url.' border="0" width="40" class="img-rounded" align="center" />';
                    }else{
                        $url='';
                        return $url;
                    }
                })
                
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('webadmin.userEdit',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.userDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    $btn .= '<a href="'.route('webadmin.subscriptions',['id'=> $row->id ]).'" class="edit btn btn-primary btn-sm mr-1">Subscriptions</a>';
                    return $btn;
                })
                
                ->rawColumns(['logo','action'])
                ->make(true);
        }
        return view('admin.user.index');
    }

    public function create()
    {
        //$roles = Role::pluck('name','name')->all();
        return view('admin.user.register');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'company_logo' => 'image|mimes:jpeg,png,jpg',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm_password',
            'confirm_password' => 'required'
        ]);


        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $input['company_logo'] = '';
        if ($image = $request->file('company_logo')){
            $input['company_logo'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = public_path('/uploads/logo');
            $img = Image::make($image->getRealPath());
            $img->resize(null, 70, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$input['company_logo']);
            $destinationPath = public_path('/uploads/logo/original');
            $image->move($destinationPath, $input['company_logo']);
        }

        $user = User::create($input);
        $last_insert_id = $user->id;

        $displayinfoData = array(
            'user_id' => $last_insert_id,
            'name' => $input['name'],
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
            'expire_at' => $expire_at,
            'is_active' => 1
        );

        $membership = Membership::create($membershipData);

        //$user->assignRole($request->input('roles'));

        //return redirect('/login')->with('success','User created successfully. You get 15 days trial.');
        //return redirect()->route('users.index')->with('success','User created successfully');

        if ($membership){
            toastr()->success('User created successfully. You get 15 days trial.');
            return redirect()->route('webadmin.users');
        }

    }

    public function edit($id){
        $data['user'] = User::where('id',$id)->first();
        return view('admin.user.edit',$data);
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required',
            'phone_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10',
            'company_logo' => 'image|mimes:jpeg,png,jpg',
            //'email' => 'required|email|unique:users,email',
        ]);

        $user = User::where('id',$id)->first();
        if (!$user){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.users');
        }

        $input = $request->all();

        $saveData = [
            'name' => $input['name'],
            //'password' => $password,
            'phone_no' => $input['phone_no'],
            'city' => $input['city'],
            'company_name' => $input['company_name']
        ];

        if(session()->get('admmin_is_super')){
            $saveData['email'] = $input['email'];
        }

        if(!empty($input['password'])){
            $saveData['password'] = Hash::make($input['password']);
        }

        if ($image = $request->file('company_logo')){
            $saveData['company_logo'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/logo');
            $img = Image::make($image->getRealPath());
            $img->resize(null, 70, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$saveData['company_logo']);
            $destinationPath = public_path('/uploads/logo/original');
            $image->move($destinationPath, $saveData['company_logo']);

            if (file_exists(public_path('uploads/logo/original/'.$user['company_logo']))) {
                chmod(public_path('uploads/logo/original/'.$user['company_logo']), 0644);
                unlink(public_path('uploads/logo/original/'.$user['company_logo']));
            }
            if (file_exists(public_path('uploads/logo/'.$user['company_logo']))) {
                chmod(public_path('uploads/logo/'.$user['company_logo']), 0644);
                unlink(public_path('uploads/logo/'.$user['company_logo']));
            }

        }

        $res = $user->update($saveData);
        if ($res){
            toastr()->success('User successfully updated.');
            return redirect()->route('webadmin.users');
        }

        return redirect()->back()->withInput();
    }

    public function delete(Request $request,$id){
        $user = User::where('id',$id)->first();
        if (!$user){
            toastr()->warning('Something went wrong, please try again later.');
            redirect()->route('webadmin.users');
        }
        if(!empty($user['company_logo'])){
            if (file_exists(public_path('uploads/logo/original/'.$user['company_logo']))) {
                //chmod(public_path('uploads/logo/original/'.$user['company_logo']), 0644);
                unlink(public_path('uploads/logo/original/'.$user['company_logo']));
            }
            if (file_exists(public_path('uploads/logo/'.$user['company_logo']))) {
                //chmod(public_path('uploads/logo/'.$user['company_logo']), 0644);
                unlink(public_path('uploads/logo/'.$user['company_logo']));
            }
        }
        $res = $user->delete();
        if ($res){
            toastr()->success('User successfully deleted.');
            return redirect()->route('webadmin.users');
        }

        return redirect()->back()->withInput();
    }

    // Excel Export

    public function exportExcel()
    {
        return Excel::download(new ListExport, 'user-list-'.date('d-m-Y').'.xlsx');
    }
    // CSV Export
    public function exportCSV()
    {
        return Excel::download(new ListExport, 'user-list-'.date('d-m-Y').'.csv');
    }
    
    //User data - Excel/csv - Paid/non paid wise - period wise (for subscription and also expiry). In user database, Pls confirm what is given in column L,M,N
    public function exportusers_csv()
    {
        return view('admin.user.exportuserscsv');
    }
    
   
    
    
    /*public function exportusersCSV(Request $request)
    {
        $input = $request->all();
        //dd($input['subscription_type']);
        $from = date($input['date_from']);
        $to = date($input['date_to']);
        $users = User::whereBetween('created_at', [$from, $to])->get();
        //$users = User::where()->get();
        Userdataexport::truncate();
        $saveDataLabel = array(
                    'name' => 'Name',
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone_no' => 'Phone Number',
                    'city' => 'City',
                    'company_name' => 'company Name',
                    'membership_status' => 'Subscription Status',
                    'membership_via' => 'Membership Via',
                    'expire_at' => 'Expire At'
                );
        Userdataexport::create($saveDataLabel);
        //dd($users);
        foreach($users as $user){
            
            
            
              $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
              
                  if($membership < 1){
                      
                      $membership_status = 'Non Paid';
                      $membership = Membership::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                      $expire_at = date('d-m-Y', strtotime($membership->expire_at));
                      $membership_via = $membership['membership_via'];
                  }else{
                      
                      $membership_status = 'Paid';
                      $membership = Membership::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                      //$expire_at = $membership->expire_at;
                      $expire_at = date('d-m-Y', strtotime($membership->expire_at));
                      $membership_via = $membership['membership_via'];
                  }
                  
                  
                  
              
                    $saveData = array(
                        'name' => $user['name'],
                        'first_name' => $user['first_name'],
                        'last_name' => $user['last_name'],
                        'email' => $user['email'],
                        'phone_no' => $user['phone_no'],
                        'city' => $user['city'],
                        'company_name' => $user['company_name'],
                        'membership_status' => $membership_status,
                        'membership_via' => $membership_via,
                        'created_at' => $user['created_at'],
                        'expire_at' => $expire_at
                    );
                    
                    
                if($input['subscription_type'] == 'Paid' && $membership_status == 'Paid'){
                    $userdataexport = Userdataexport::create($saveData);
                }elseif($input['subscription_type'] == 'Non Paid' && $membership_status == 'Non Paid'){
                    $userdataexport = Userdataexport::create($saveData);
                }elseif($input['subscription_type'] == 'all'){
                    $userdataexport = Userdataexport::create($saveData);
                }
                
                
        
        }
        
        //dd("ok");
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UsersdataExport, 'user-data-list-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UsersdataExport, 'user-data-list-'.date('d-m-Y').'.xlsx');
        }
        
    }*/
    
    
    public function exportusersCSV(Request $request)
    {
        $input = $request->all();
        //dd($input);
        //dd($input['subscription_type']);
        $subscription_type =  $input['subscription_type'];
        $from = date($input['date_from']);
        $to = date($input['date_to']);
        if($subscription_type == 'Paid'){
            $memberships_between = Membership::whereBetween('created_at', [$from, $to])->where('subscription_type','paid')->get();
        }elseif($subscription_type == 'Non Paid'){
            $memberships_between = Membership::whereBetween('created_at', [$from, $to])->where('subscription_type','free')->get();
        }else{
            $memberships_between = Membership::whereBetween('created_at', [$from, $to])->get();
        }
        
        //dd($memberships_between);
        
        if(isset($memberships_between)){
            $user_ids = array();
            foreach($memberships_between as $memberships_between_data){
                array_push($user_ids,$memberships_between_data->user_id);
            }
        }
        
        
        
        $user_ids = array_unique($user_ids);
        //dd($user_ids);
        
        //$users = User::whereBetween('created_at', [$from, $to])->get();
        $users = User::WhereIn('id',$user_ids)->get();
        //dd($users);
        //$users = User::where()->get();
        Userdataexport::truncate();
        $saveDataLabel = array(
                    'name' => 'Name',
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone_no' => 'Phone Number',
                    'city' => 'City',
                    'company_name' => 'company Name',
                    'membership_status' => 'Subscription Status',
                    'membership_via' => 'Membership Via',
                    'new_or_renewal' => 'New or Renewal',
                    'expire_at' => 'Expire At'
                );
        Userdataexport::create($saveDataLabel);
        
        //dd($users);
        foreach($users as $user){
            
                /*$membership_paid_count = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                //$membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->count();
                //dd($membership_paid_count);
                  if($membership_paid_count < 1){
                      
                      $membership_status = 'Non Paid';
                      $membership = Membership::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                      //$expire_at = $membership->expire_at;
                      $expire_at = date('d-m-Y', strtotime($membership->expire_at));
                  }else{
                      
                      $membership_status = 'Paid';
                      $membership = Membership::where('user_id', $user->id)->orderBy('id', 'desc')->first();
                      //$expire_at = $membership->expire_at;
                      $expire_at = date('d-m-Y', strtotime($membership->expire_at));
                  }*/
                  
                $memberships_count = Membership::where('user_id', $user->id)->count();
                
                //$memberships = Membership::where('user_id', $user->id)->get();
                //print_r($memberships);
                //dd($memberships_count);
                if(isset($memberships_count) && $memberships_count > 0 ){
                    
                    $memberships = Membership::where('user_id', $user->id)->get();
                    //dd($memberships);
                    $count = 0;
                    //if(isset($memberships)){
                    foreach($memberships as $membershipdata){
                        
                        if($membershipdata['subscription_type'] == 'paid'){
                            $membership_status = 'Paid';
                        }else{
                            $membership_status = 'Non Paid';
                        }
                        
                        $expire_at = date('d-m-Y', strtotime($membershipdata['expire_at']));
                       
                        $membership_via = $membershipdata['membership_via'];
                        
                        if($count == 0){
                            $new_or_renewal = '';
                        }elseif($count ==1){
                            $new_or_renewal = 'New';
                        }else{
                            $new_or_renewal = 'Renewal';
                        }
                        
                        //dd($membership_via);
                      
                        $saveData = array(
                            'name' => $user['name'],
                            'first_name' => $user['first_name'],
                            'last_name' => $user['last_name'],
                            'email' => $user['email'],
                            'phone_no' => $user['phone_no'],
                            'city' => $user['city'],
                            'company_name' => $user['company_name'],
                            'membership_status' => $membership_status,
                            'membership_via' => $membership_via,
                            'new_or_renewal' => $new_or_renewal,
                            'created_at' => $user['created_at'],
                            'expire_at' => $expire_at
                        );
                        
                        //dd($saveData);
                        
                        if($input['subscription_type'] == 'Paid' && $membership_status == 'Paid'){
                            $userdataexport = Userdataexport::create($saveData);
                        }elseif($input['subscription_type'] == 'Non Paid' && $membership_status == 'Non Paid'){
                            $userdataexport = Userdataexport::create($saveData);
                        }elseif($input['subscription_type'] == 'all'){
                            
                            $userdataexport = Userdataexport::create($saveData);
                            
                        }
                        
                        $count++;
                    
                    }
                    
                //}
                }
                
            //dd("ok");
        }
        //dd("ok");
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UsersdataExport, 'user-data-list-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UsersdataExport, 'user-data-list-'.date('d-m-Y').'.xlsx');
        }
        
    }
    
    //exportuser_csv
    public function exportuser_subscription_csv()
    {
        $data['users'] = User::get();
        //dd($data);
        return view('admin.user.exportusercsv',$data);
    }
    
    public function exportusersubscriptionCSV(Request $request)
    {
        $input = $request->all();
        
        //dd($input['user_id']);
        
        $user = User::where('id',$input['user_id'])->first();
        
        $memberships = Membership::where('user_id', $user->id)->get();
        Usersubscriptionsexport::truncate();
        
        $saveDataLabel = array(
                    'name' => 'Name',
                    'first_name' => 'First Name',
                    'last_name' => 'Last Name',
                    'email' => 'Email',
                    'phone_no' => 'Phone Number',
                    'subscription_type' => 'Subscription Type',
                    'amount' => 'Amount',
                    'duration' => 'Duration',
                    'membership_via' => 'Membership Via',
                    'new_or_renewal' => 'New or Renewal',
                    'started_on' => 'Started On',
                    'expire_at' => 'Expire At'
                );
        Usersubscriptionsexport::create($saveDataLabel);
        $count = 0;
        //dd("ok");
        foreach($memberships as $membership){
            
            $expire_at = date('d-m-Y', strtotime($membership['expire_at']));
            $created_at = date('d-m-Y', strtotime($membership['created_at']));
            
            if($membership['subscription_type'] == 'paid' && $count == 0){
                $count = 1;
            }
            
            if($count == 0){
                $new_or_renewal = '';
            }elseif($count == 1){
                $new_or_renewal = 'New';
            }else{
                $new_or_renewal = 'Renewal';
            }
            
            //dd($new_or_renewal);
            //if($count == 0){
                $saveData = array(
                    'name' => $user['name'],
                    'first_name' => $user['first_name'],
                    'last_name' => $user['last_name'],
                    'email' => $user['email'],
                    'phone_no' => $user['phone_no'],
                    'subscription_type' => $membership['subscription_type'],
                    'amount' => $membership['amount'],
                    'duration' => $membership['duration'].' '.$membership['duration_name'],
                    'membership_via' => $membership['membership_via'],
                    'new_or_renewal' => $new_or_renewal,
                    'started_on' => $created_at,
                    'expire_at' => $expire_at
                );
            /*}else{
                $saveData = array(
                    'subscription_type' => $membership['subscription_type'],
                    'amount' => $membership['amount'],
                    'duration' => $membership['duration'].' '.$membership['duration_name'],
                    'membership_via' => $membership['membership_via'],
                    'new_or_renewal' => $new_or_renewal,
                    'started_on' => $created_at,
                    'expire_at' => $expire_at
                );
            }*/
            
            $userdataexport = Usersubscriptionsexport::create($saveData);
            $count++;
        }
        
        if($input['download_file_type'] == 'CSV'){
            return Excel::download(new UserssubscriptiondataExport, 'user-data-subscription-list-'.date('d-m-Y').'.csv');
        }else{
            return Excel::download(new UserssubscriptiondataExport, 'user-data-subscription-list-'.date('d-m-Y').'.xlsx');
        }
    }
    


}
