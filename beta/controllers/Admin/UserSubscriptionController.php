<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Membership;
use Carbon\Carbon;
use App\User;

class UserSubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        //dd($user_id);
        //$data = Membership::where('user_id',$user_id)->latest()->get();
        //dd($request);

        // 

        // dd($data);
        if ($request->ajax()) {
            $currentDateTime = Carbon::now();
            $newDateTime = Carbon::now()->addDays(30);
            $data = Membership::select(['memberships.*','users.name'])->leftJoin('users', 'users.id', '=' , 'memberships.user_id')->where('memberships.is_active','1')->where('memberships.expire_at', '<=', $newDateTime)->groupBy('memberships.user_id')->get();
            
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('subscription_type', function($row){
                    $subscription_type = ucfirst($row->subscription_type);
                    return $subscription_type;
                })
                ->addColumn('duration', function($row){
                    $duration = $row->duration.' '.ucfirst($row->duration_name);
                    return $duration;
                })
                ->addColumn('status', function ($row) {
                    if($row->is_active == 1){
                        $status = '<p style="font-weight: 700;color: #61d41a;">Active</p>';
                    }else{
                        $status = '<p style="font-weight: 700;color: #ef5350;">Inactive</p>';
                    }
                    return $status;
                })
                ->addColumn('expire_at', function($row){
                    $expire_at = date('d-m-Y',strtotime($row->expire_at));
                    return $expire_at;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'.route('webadmin.subscriptionEdit',["id"=>$row->id]).'" class="edit btn btn-primary btn-sm mr-1">Edit</a>';
                    $btn .= '<a href="'.route('webadmin.subscriptionDelete',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Delete</a>';
                    return $btn;
                })
                
                ->rawColumns(['action','subscription_type','duration','status','expire_at'])
                ->make(true);
        }
        $data = [];
        return view('admin.package_creation.subscription',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        $data['user_id'] = $user_id;
        return view('admin.subscription.add',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'subscription_type' => 'required',
            'amount' => 'required',
            'created_at' => 'required',
            'expire_at' => 'required',
        ]);
        $input = $request->all();
        $date2=date_create($input['expire_at']);
        $date1=date_create($input['created_at']);
        $diff=date_diff($date1,$date2);
        //dd($diff->format("%R%a days"));
        $duration = $diff->days;

        $duration_name = 'days';
    
        //$expire_at = date('Y-m-d',strtotime('+1 year',$date));
        
        $subscription_count = Membership::count();
        $subscription_last_id = Membership::max('subscription_id');
        if(!empty($subscription_count)){
            $subscription_id = $subscription_last_id+1;
        }else{
            $subscription_id = 00001;
        }
        //dd($subscription_id);

        $membershipData = array(
            'user_id' => $input['user_id'],
            'subscription_id' => $subscription_id,
            'subscription_type' => $input['subscription_type'],
            'amount' => $input['amount'],
            'duration' => $duration,
            'duration_name' => $duration_name,
            'membership_via' => $input['membership_via'],
            'created_at' => $input['created_at'],
            'expire_at' => $input['expire_at'],
            'is_active' => isset($input['status'])?1:0
        );

        $membership = Membership::create($membershipData);
        if ($membership){
            toastr()->success('New membership created successfully.');
            return redirect()->back();
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['subscription'] = Membership::where('id',$id)->first();
        return view('admin.subscription.edit',$data);

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
        $request->validate([
            'subscription_type' => 'required',
            'amount' => 'required',
            'created_at' => 'required',
            'expire_at' => 'required',
        ]);
        $previousSubscription = Membership::where('id',$id)->first();
        if (!$previousSubscription){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }
        $input = $request->all();
        $date2=date_create($input['expire_at']);
        $date1=date_create($input['created_at']);
        $diff=date_diff($date1,$date2);
        //dd($diff->format("%R%a days"));
        $duration = $diff->days;

        $duration_name = 'days';

        $membershipData = array(
            //'user_id' => $input['user_id'],
            //'subscription_id' => $subscription_id,
            'subscription_type' => $input['subscription_type'],
            'amount' => $input['amount'],
            'duration' => $duration,
            'duration_name' => $duration_name,
            'membership_via' => $input['membership_via'],
            'created_at' => $input['created_at'],
            'expire_at' => $input['expire_at'],
            'is_active' => isset($input['status'])?1:0
        );

        $res = $previousSubscription->update($membershipData);
        if ($res){
            toastr()->success('Subscription successfully updated.');
            return redirect()->back();
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request,$id){
        $previousSubscription = Membership::where('id',$id)->first();
        if (!$previousSubscription){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }
        $res = $previousSubscription->delete();
        if ($res){
            toastr()->success('Subscription successfully deleted.');
            return redirect()->back();
        }

        return redirect()->back()->withInput();
    }
}
