@extends('layouts.frontend')

@section('content')
<style type="text/css">
    .top-tab {
        margin-bottom: 61px;
    }
    /*.newsletter {*/
    /*    margin-top: 104px;*/
    /*    margin-bottom: -24px;*/
    /*}*/
    .stationery-btn .banner-btn {
        padding: 10px 15px !important;
    }
    

    .vidpos02 {
        left: -20px;
        top: 187px;
        width: 100px;
        }
    .vidpos04 {
        left: -53px;
        top: 580px;
    }
    .vidpos03 {
        right: 0;
        left: -30px;
        top: 1000px;
        width: 130px;
    }
    .vidpos05 {
        right: -65px;
        top: 1000px;
        width: 130px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 130px;
    }
    .visp {
        right: 0px;
        top: 600px;
        width: 560px;
    }
    .visp3 {
        left: 0px;
        top: 420px;
        width: 250px;
        opacity: 0.6;
    }
</style>
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi visp3" style="" src="{{asset('')}}img/videopageart3.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->

<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item shoppingCartBannaer">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Hi First Name</h2>
                    <p>Your profile page, subscription details, membership points, order history, and more.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/ProfileBanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div>

<section class="main-sec">
    <div class="container">
	    <div class="row">
	        <div class="colProfileLeft"></div>
	        <div class="colProfileRight">
	            <div class="orderRight">
	                <h3 class="userHeadding">Subscriptions</h3>
	            </div>
	        </div>
	    </div>
        <div class="row">
            <div class="colProfileLeft">
                @include('frontend.account.left_menu')
            </div>
            <div class="colProfileRight">     
                
                <div class="orderRight">
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif
    
    
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger alert-block">
                            <button type="button" class="close" data-dismiss="alert">×</button> 
                                <strong>{{ $message }}</strong>
                        </div>
                    @endif 
                    <div class="stage">
                        <div class="">
                            <div class="row">
                                @if($is_open_subscription)
                                    <div class="col-lg-4 mb-2">
                                        <a href="{{route('frontend.membershipcart')}}" class="btn banner-btn">Renewal</a>
                                    </div>
                                @else
                                    <div class="col-lg-4 mb-2">
                                        &nbsp;
                                    </div>
                                @endif
                                @if((strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($membership_detail->expire_at)))) && 0 )
                                    <div class="col-lg-4 mb-2 subscriptionBtnRight">
                                        @if($is_update_status)
                                            <a href="{{route('account.upgradePremiumMembershipTrial')}}" class="btn banner-btn" style="width: 240px;">Premium Membership Trial</a>
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                @else
                                    <div class="col-lg-4 mb-2">
                                        &nbsp;
                                    </div>
                                @endif
                                    
                                <div class="col-lg-4 mb-2 subscriptionBtnRight">
                                    @if($is_update_status)
                                        <a href="{{route('account.upgradePackage')}}" class="btn banner-btn">Upgrade</a>
                                    @else
                                        <a href="javascript:void(0);" onclick="javascript:alert('You are already using Highest Package');" class="btn banner-btn">Upgrade</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="">
                                    
                                    <div class="">
                                        <div class="table-responsive orderTable subscriptionTable">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th ><span>Subscription</span></th>
                                                        <th ><span>Package Name</span></th>
                                                        <th><span>Status</span></th>
                                                        <th><span>Valid till</span></th>
                                                        <th><span>Amount Paid</span></th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
    
                                                <tbody>
                                                    @foreach($membership_plan as $plan)
                                                        <tr class="order">
                                                            <td>
                                                                <a href="#">#000{{$plan->id}}</a>
                                                            </td>
                                                            <td>
                                                                <?php if($plan->duration_name){ ?>
                                                                    {{$plan->name}}
                                                                <?php }else{ ?>
                                                                    Extra User
                                                                <?php } ?>
                                                                
                                                            </td>
                                                            <td>
                                                                <?php if($plan->duration_name){ ?>
                                                                    @if(strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($plan->expire_at))) )
                                                                        @if($plan->is_active == 1)
                                                                            <span class="badge badge-success">Active</span>
                                                                        @else
                                                                            <span class="badge badge-danger">Inactive</span>
                                                                        @endif
                                                                    @else
                                                                        <span class="badge badge-danger">Inactive</span>
                                                                    @endif
                                                                <?php }else{ ?>
                                                                    Active
                                                                <?php } ?>
                                                            </td>
                                                            <td>
                                                               {{date('M d, Y', strtotime($plan->expire_at))}}
                                                            </td>
                                                            <td>
                                                                Rs {{number_format($plan->amount,2)}}
                                                            </td>
                                                            <td>
                                                                <a href="{{url('account/subscription')}}/{{$plan->id}}" class="subscriptionView">View</a>
                                                                
                                                            </td>
                                                        </tr>
                                                    @endforeach
    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="subscriptionBtnAll">
                            <div class="row">
                                @if($is_open_subscription)
                                    <div class="col-lg-4 mb-2">
                                        <a href="{{route('frontend.membershipcart')}}" class="btn banner-btn">Renewal</a>
                                    </div>
                                @else
                                    <div class="col-lg-4 mb-2">
                                        &nbsp;
                                    </div>
                                @endif
                                @if(strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($membership_detail->expire_at))))
                                    <div class="col-lg-4 mb-2 subscriptionBtnRight">
                                        @if($is_update_status)
                                            <a href="{{route('account.upgradePremiumMembershipTrial')}}" class="btn banner-btn" style="width: 240px;">Premium Membership Trial</a>
                                        @else
                                            &nbsp;
                                        @endif
                                    </div>
                                @else
                                    <div class="col-lg-4 mb-2">
                                        &nbsp;
                                    </div>
                                @endif
                                    
                                <div class="col-lg-4 mb-2 subscriptionBtnRight">
                                    @if($is_update_status)
                                        <a href="{{route('account.upgradePackage')}}" class="btn banner-btn">Upgrade</a>
                                    @else
                                        <a href="javascript:void(0);" onclick="javascript:alert('You are already using Highest Package');" class="btn banner-btn">Upgrade</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="btm-shape-prt">
        <img class="img-fluid" src="images/shape2.png" alt="" />
    </div>
</section>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
