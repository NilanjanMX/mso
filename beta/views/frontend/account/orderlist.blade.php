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
	                <h3 class="userHeadding">Orders</h3>
	            </div>
	        </div>
	    </div>
	    
    	<div class="row">
            <div class="colProfileLeft">
                @include('frontend.account.left_menu')
            </div>
        <div class="colProfileRight">     
                
            <div class="orderRight">
                <div class="stage">
                        <div class="row">
                            
                            <div class="col-lg-12">
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
                            </div>
                            <div class="col-lg-12">
                                <div class="">
                                    <div class="">
                                        <div class="table-responsive orderTable">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th ><span>Order ID</span></th>
                                                        <th><span>Amount</span></th>
                                                        <th><span>Date</span></th>
                                                        <th><span>Status</span></th>
                                                        <!--<th>&nbsp;</th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(isset($orderDetails))
                                                        @foreach($orderDetails as $orderInfo)
                                                            <tr class="order">
                                                                <td>
                                                                    <a href="#">#{{$orderInfo['invoice_id']}}</a>
                                                                </td>
                                                                <td>Rs {{number_format($orderInfo['payable_amount'],2)}} </td>
                                                                <td>
                                                                   {{date('M d, Y', strtotime($orderInfo['created_at']))}}
                                                                </td>
                                                                <td>{{ucfirst($orderInfo['status'])}}</td>
                                                                <!--<td>-->
                                                                <!--    <a href="{{url('account/order')}}/{{$orderInfo['id']}}" class="btn btn-primary">View</a>-->
                                                                <!--    @if($billing_detail)-->
                                                                <!--        <a href="{{url('account/order-invioce-download')}}/{{$orderInfo['id']}}" class="btn btn-primary">Invioce</a>-->
                                                                <!--    @else-->
                                                                <!--        <a href="javascript:void(0);" class="btn btn-secondary" onclick="javascript:alert('Please update the billing details to download the invoice');">Invioce</a-->
                                                                <!--    @endif-->
                                                                <!--</td>-->
                                                            </tr>
                                                        @endforeach
                                                        
                                                    @endif
    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
    <!--<div class="btm-shape-prt">-->
    <!--	<img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">-->
    <!--</div>-->
</section>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
