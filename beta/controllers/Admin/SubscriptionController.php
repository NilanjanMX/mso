<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Membership;
use App\User;
use App\Models\PackageCreationDropdown;
use App\Models\UserBilling;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade as PDF;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id){
        //dd($user_id);
        //$data = Membership::where('user_id',$user_id)->latest()->get();
        //dd($request);
        if ($request->ajax()) {
            //dd($user_id);
            $user_id = $request->get('user_id');
            //dd($id);
            $data = Membership::select(['memberships.*','users.user_type'])->LeftJoin('users','users.id', '=', 'memberships.user_id')->where('memberships.user_id',$user_id)->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('package_id', function($row){
                    $name = "";
                    if($row->flag == 1){
                        $name = "Premium Membership Trial";
                    }else{
                        $package = PackageCreationDropdown::where('id',$row->package_id)->first();
                        if($package){
                            $name = $package->name;
                        }
                    }
                    return $name;
                })                
                ->addColumn('subscription_type', function($row){
                    $subscription_type = ucfirst($row->subscription_type);
                    return $subscription_type;
                })                
                ->addColumn('amount', function($row){
                    $amount = $row->amount/1.18;
                    return $amount;
                })
                ->addColumn('status', function ($row) {
                    
                    if((strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($row->expire_at)))) && $row->is_active == 1){
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
                    if($row->user_type == "C"){
                        $btn .= '<a href="'.route('webadmin.subscriptionEmail',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-success btn-sm ml-1">Send</a>';
                    }
                    $btn .= '<a href="'.route('webadmin.subscriptionDownload',['id'=> $row->id ]).'" onclick="return confirm(\'Are you sure?\')"  class="edit btn btn-danger btn-sm ml-1">Invoice</a>';
                    return $btn;
                })
                ->rawColumns(['action','subscription_type','duration','status','expire_at'])
                ->make(true);
        }
        $data['user_id'] = $user_id;
        return view('admin.subscription.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id)
    {
        $data['user_id'] = $user_id;
        $data['package_list'] = PackageCreationDropdown::where('is_active',1)->get();
        // dd($data);
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
            'total_user' => 'required',
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
        // //dd($subscription_id);

        $membershipData = array(
            'user_id' => $input['user_id'],
            'package_id' => $input['package_id'],
            'subscription_id' => $subscription_id,
            'subscription_type' => $input['subscription_type'],
            'amount' => $input['amount']*1.18,
            'duration' => $input['total_user'],
            'duration_name' => $duration_name,
            'membership_via' => $input['membership_via'],
            'created_at' => $input['created_at'],
            'expire_at' => $input['expire_at'],
            'is_paid' => isset($input['is_paid'])?$input['is_paid']:0,
            'is_active' => isset($input['status'])?1:0
        );
        
        $membership = Membership::create($membershipData);
        if ($membership){
            $user = User::where('id',$input['user_id'])->first();
                
            $p_detail = PackageCreationDropdown::where("id",$input['package_id'])->first();
            $insertData = [
                "package_id"=>$input['package_id'],
                "number_user"=>$input['total_user'],
                "permission_sales_presenter"=>$p_detail->sales_presenters,
                "permission_calculators_proposals"=>$p_detail->client_proposals,
                "permission_investment_suitablity_profiler"=>$p_detail->investment_suitability_profiler,
                "permission_marketing_banners"=>$p_detail->marketing_banners,
                "permission_marketing_video"=>$p_detail->marketing_videos,
                "permission_premade_sales_presenter"=>$p_detail->pre_made_sales_presenters,
                "permission_trail_calculators"=>$p_detail->trail_calculator,
                "permission_scanner"=>$p_detail->scanner,
                "permission_famous_quotes"=>$p_detail->famous_quotes
            ];
            $user->update($insertData);
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
        $data['package_list'] = PackageCreationDropdown::where('is_active',1)->get();
        $data['subscription']->amount = $data['subscription']->amount/1.18;
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
            'total_user' => 'required',
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

        // $membershipData = array(
        //     //'user_id' => $input['user_id'],
        //     //'subscription_id' => $subscription_id,
        //     'subscription_type' => $input['subscription_type'],
        //     'amount' => $input['amount'],
        //     'duration' => $duration,
        //     'duration_name' => $duration_name,
        //     'membership_via' => $input['membership_via'],
        //     'created_at' => $input['created_at'],
        //     'expire_at' => $input['expire_at'],
        //     'is_active' => isset($input['status'])?1:0
        // );

        $membershipData = array(
            'package_id' => $input['package_id'],
            'subscription_type' => $input['subscription_type'],
            'amount' => $input['amount']*1.18,
            'duration' => $input['total_user'],
            'duration_name' => $duration_name,
            'membership_via' => $input['membership_via'],
            'created_at' => $input['created_at'],
            'expire_at' => $input['expire_at'],
            'is_paid' => isset($input['is_paid'])?$input['is_paid']:0,
            'is_active' => isset($input['status'])?1:0
        );

        $res = $previousSubscription->update($membershipData);
        if ($res){
            $user = User::where('id',$previousSubscription->user_id)->first();
            $p_detail = PackageCreationDropdown::where("id",$input['package_id'])->first();
            $insertData = [
                "package_id"=>$input['package_id'],
                "permission_sales_presenter"=>$p_detail->sales_presenters,
                "permission_calculators_proposals"=>$p_detail->client_proposals,
                "permission_investment_suitablity_profiler"=>$p_detail->investment_suitability_profiler,
                "permission_marketing_banners"=>$p_detail->marketing_banners,
                "permission_marketing_video"=>$p_detail->marketing_videos,
                "permission_premade_sales_presenter"=>$p_detail->pre_made_sales_presenters,
                "permission_trail_calculators"=>$p_detail->trail_calculator,
                "permission_scanner"=>$p_detail->scanner,
                "permission_famous_quotes"=>$p_detail->famous_quotes
            ];
            $user->update($insertData);
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

    public function email(Request $request,$id){
        $previousSubscription = Membership::where('id',$id)->first();
        // dd($user_detail);
        if (!$previousSubscription){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }
        $user_detail = User::where('id',$previousSubscription->user_id)->first();
        $email = $user_detail->email;
        $name = $user_detail->name;
        $phone_no = $user_detail->phone_no;
        $amount = $previousSubscription->amount;
        $date = "";
        $messageData = ['email'=>$email,'name'=>$name,'phone_no'=>$phone_no,'amount'=>$amount,'date'=>$date,"subscription_id"=>$previousSubscription->id];
        //return view('emails.corporate_subscription',$messageData);
        // return view('emails.corporate_subscription',$messageData);
        Mail::send('emails.corporate_subscription',$messageData,function($message) use($email){
             $message->from('info@masterstrokeonline.com', 'Masterstroke');
            $message->to($email)->cc('info@masterstrokeonline.com')
            ->subject('Membership with Masterstrokeonline');
        });
        // $res = $previousSubscription->delete();

        // if ($res){
        //     toastr()->success('Subscription successfully deleted.');
        //     return redirect()->back();
        // }

        // return redirect()->back()->withInput();
        toastr()->success('Subscription email successfully.');
        return redirect()->back();
    }
    
    

    public function AmountInWords(float $amount){
        $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
        // Check if there is any number after decimal
        $amt_hundred = null;
        $count_length = strlen($num);
        $x = 0;
        $string = array();
        $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
            3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
            7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
            13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
            16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
            19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
            70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
        while( $x < $count_length ) {
            $get_divider = ($x == 2) ? 10 : 100;
            $amount = floor($num % $get_divider);
            $num = floor($num / $get_divider);
            $x += $get_divider == 10 ? 1 : 2;
            if ($amount) {
                $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
                $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
                $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
                '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
                '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
            }
            else $string[] = null;
        }
        $implode_to_Rupees = implode('', array_reverse($string));
        $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
        " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
        return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }

    public function downloads(Request $request,$id){
        $data['membership_detail'] = Membership::where('id',$id)->first();
        if (!$data['membership_detail']){
            toastr()->warning('Something went wrong, please try again later.');
            return redirect()->back();
        }

        $user = User::where('id',$data['membership_detail']->user_id)->first();
        $data['user_billing_detail'] = UserBilling::where('user_id',$user->id)->first();
        $data['invoice_number'] = $id;
        $data['invoice_name'] = "ONLINE MEMBERSHIP FOR 1 YEAR SUBSCRIPTION";
        $amount = $data['membership_detail']->amount;
        $data['total_amount'] = $amount;
        if($amount){
            $cgst = $amount - $amount / 1.18 ;
            $orignal_amount = $amount / 1.18;
            $cgst = $amount - $orignal_amount;

            $data['membership_detail']->orignal_amount = number_format($orignal_amount, 2, '.', '');
            $data['membership_detail']->cgst = number_format($cgst/2, 2, '.', '');
            $data['membership_detail']->sgst = number_format($cgst/2, 2, '.', '');

            $round_off= $amount - $data['membership_detail']->orignal_amount - $data['membership_detail']->cgst - $data['membership_detail']->cgst;
            $data['membership_detail']->round_off = number_format($round_off , 2, '.', '');
            $data['membership_detail']->amount_in_word = "INR ".$this->AmountInWords($amount)." Only";
            $data['total_amount'] = number_format((float)$data['total_amount'], 2, '.', '');
            $data['membership_detail']->total_amount_in_word = "INR ".$this->AmountInWords($data['total_amount'])." Only";
        }else{
            $data['membership_detail']->cgst = 0;
            $data['membership_detail']->sgst = 0;
            $data['membership_detail']->orignal_amount = 0;
            $data['membership_detail']->round_off = 0;
            $data['membership_detail']->amount_in_word = "";
                $data['membership_detail']->total_amount_in_word = "";
        }
        if($data['user_billing_detail']){
            if($data['user_billing_detail']->gst_zone == "outside"){
                // return view('frontend.invoice.outside_wb',$data);
                $pdf = PDF::loadView('frontend.invoice.outside_wb', $data);
                return $pdf->download(time().'_invoice.pdf');
            }else{
                // return view('frontend.invoice.outside_wb',$data);
                $pdf = PDF::loadView('frontend.invoice.wb', $data);
                return $pdf->download(time().'_invoice.pdf');
            }
        }else{
            toastr()->success('Billing address not updated.');
            return redirect()->back();
        }
        // dd($data['membership_detail']);
        
    }


}
