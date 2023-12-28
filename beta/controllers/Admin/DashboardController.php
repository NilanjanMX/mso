<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use App\Models\Membership;
class DashboardController extends Controller
{
    public function index(){
        //dd(date('Y-m-d'));
        $data = [];
        return view('admin.dashboard.index',$data);
    }
    public function stat(){
        //dd(date('Y-m-d'));
        $data['user_count'] = User::count();
        $users = User::select('id')->get();
        $member = 0;
        $non_member = 0;
        $icici = 0;
        $direct = 0;
        $monthwise_icici = 0;
        $monthwise_direct = 0;
        $renewalmember_icici_count = 0;
        $renewalmember_direct_count = 0;
        $renewalmember_1_month_icici_count = 0;
        $renewalmember_1_month_direct_count = 0;
        $member_last_7_days_icici_count = 0;
        $member_last_7_days_direct_count = 0;
        $member_last_15_days_icici_count = 0;
        $member_last_15_days_direct_count = 0;
        $member_last_30_days_icici_count = 0;
        $member_last_30_days_direct_count = 0;
        
        //dd($startDate);
        foreach($users as $user){
            $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
            if($membership < 1){
                $non_member++;
            }else{
                $member++;
                // Total Member(ICICI/Direct)
                $member_icici_direct = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($member_icici_direct < 1){
                    $direct++;
                    
                }else{
                    $icici++;
                }
                //End Total Member(ICICI/Direct)
                
                // MonthWise Member (ICICI/Direct)
                $startDate = date('Y-m-01');
                $endDate = date('Y-m-d');
                $member_icici = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($member_icici > 0){
                    $monthwise_icici++;
                }
                $member_direct = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($member_direct > 0){
                    $monthwise_direct++;
                }
                //End MonthWise Member (ICICI/Direct)
                
                //Upcoming Renewal Number(15 Days/1Month) both ICICI/Direct
                $startDate = date('Y-m-d');
                $date=strtotime(date('Y-m-d'));
                $endDate = date('Y-m-d',strtotime('+15 days',$date));
                $renewalmember_15_icici = Membership::where('user_id', $user->id)->whereBetween('expire_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($renewalmember_15_icici > 0){
                    $renewalmember_icici_count++;
                }
                $renewalmember_15_direct = Membership::where('user_id', $user->id)->whereBetween('expire_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($renewalmember_15_direct > 0){
                    $renewalmember_direct_count++;
                }
                
                $startDate = date('Y-m-d');
                $date=strtotime(date('Y-m-d'));
                $endDate = date('Y-m-d',strtotime('+30 days',$date));
                $renewalmember_1_month_icici = Membership::where('user_id', $user->id)->whereBetween('expire_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($renewalmember_1_month_icici > 0){
                    $renewalmember_1_month_icici_count++;
                }
                $renewalmember_1_month_direct = Membership::where('user_id', $user->id)->whereBetween('expire_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($renewalmember_1_month_direct > 0){
                    $renewalmember_1_month_direct_count++;
                }
                
                //End Upcoming Renewal Number(15 Days/1Month) both ICICI/Direct
                
                // Member Number (Last 7Days/15 Days/1 Month)
                
                
                $date=strtotime(date('Y-m-d'));
                $startDate = date('Y-m-d',strtotime('-7 days',$date));
                $endDate = date('Y-m-d');
                $member_last_7_days_icici = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($member_last_7_days_icici > 0){
                    $member_last_7_days_icici_count++;
                }
                $member_last_7_days_direct = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($member_last_7_days_direct > 0){
                    $member_last_7_days_direct_count++;
                }
                
                $date=strtotime(date('Y-m-d'));
                $startDate = date('Y-m-d',strtotime('-15 days',$date));
                $endDate = date('Y-m-d');
                $member_last_15_days_icici = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($member_last_15_days_icici > 0){
                    $member_last_15_days_icici_count++;
                }
                $member_last_15_days_direct = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($member_last_15_days_direct > 0){
                    $member_last_15_days_direct_count++;
                }
                
                $date=strtotime(date('Y-m-d'));
                $startDate = date('Y-m-d',strtotime('-30 days',$date));
                $endDate = date('Y-m-d');
                $member_last_30_days_icici = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','ICICI')->count();
            
                if($member_last_30_days_icici > 0){
                    $member_last_30_days_icici_count++;
                }
                $member_last_30_days_direct = Membership::where('user_id', $user->id)->whereBetween('created_at', [$startDate, $endDate])->where('subscription_type','paid')->where('is_active',1)->where('membership_via','Direct')->count();
                if($member_last_30_days_direct > 0){
                    $member_last_30_days_direct_count++;
                }
                
                // End Member Number (Last 7Days/15 Days/1 Month)
                
            }
            
            
            
        }
        
        // Order
        
        // Total Sales Amount from Store Page (Last 7Days/15 Days/1 Month
        $date=strtotime(date('Y-m-d'));
        $startDate = date('Y-m-d',strtotime('-7 days',$date));
        $endDate = date('Y-m-d');
        $salesamount_last_7_days = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status','success')->sum('payable_amount');
            
        //dd($salesamount_last_7_days);
        
        $date=strtotime(date('Y-m-d'));
        $startDate = date('Y-m-d',strtotime('-15 days',$date));
        $endDate = date('Y-m-d');
        $salesamount_last_15_days = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status','success')->sum('payable_amount');
        
        $date=strtotime(date('Y-m-d'));
        $startDate = date('Y-m-d',strtotime('-30 days',$date));
        $endDate = date('Y-m-d');
        $salesamount_last_30_days = Order::whereBetween('created_at', [$startDate, $endDate])->where('payment_status','success')->sum('payable_amount');
        
        
        $data['member'] = $member;
        $data['non_member'] = $non_member;
        $data['icici'] = $icici;
        $data['direct'] = $direct;
        $data['monthwise_icici'] = $monthwise_icici;
        $data['monthwise_direct'] = $monthwise_direct;
        $data['renewalmember_icici_count'] = $renewalmember_icici_count;
        $data['renewalmember_direct_count'] = $renewalmember_direct_count;
        $data['renewalmember_1_month_icici_count'] = $renewalmember_1_month_icici_count;
        $data['renewalmember_1_month_direct_count'] = $renewalmember_1_month_direct_count;
        $data['member_last_7_days_icici_count'] = $member_last_7_days_icici_count;
        $data['member_last_7_days_direct_count'] = $member_last_7_days_direct_count;
        $data['member_last_15_days_icici_count'] = $member_last_15_days_icici_count;
        $data['member_last_15_days_direct_count'] = $member_last_15_days_direct_count;
        $data['member_last_30_days_icici_count'] = $member_last_30_days_icici_count;
        $data['member_last_30_days_direct_count'] = $member_last_30_days_direct_count;
        
        $data['salesamount_last_7_days'] = $salesamount_last_7_days;
        $data['salesamount_last_15_days'] = $salesamount_last_15_days;
        $data['salesamount_last_30_days'] = $salesamount_last_30_days;
        return view('admin.dashboard.stat',$data);
    }
}
