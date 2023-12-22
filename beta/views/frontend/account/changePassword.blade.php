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
	                <h3 class="userHeadding">Change Password</h3>
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
                                <div class="">
                                    <div class="personalInfoForm">
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
                                        <form method="POST" action="{{ route('change.password') }}">
                                            @csrf 
                    
                                            <div class="form-row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <!--<label for="password" class="col-md-4 col-form-label text-md-right">Current Password</label>-->
                            
                                                        <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password" placeholder="Current Password">
                                                        @if ($errors->has('current_password'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('current_password') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <!--<label for="password" class="col-md-4 col-form-label text-md-right">New Password</label>-->
                            
                                                        <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password" placeholder="New Password">
                                                        @if ($errors->has('new_password'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('new_password') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <!--<label for="password" class="col-md-4 col-form-label text-md-right">New Confirm Password</label>-->
                            
                                                        <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password" placeholder="New Confirm Password">
                                                        @if ($errors->has('new_confirm_password'))
                                                            <div class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('new_confirm_password') }}</strong>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="uploadedLogoBtn ml-auto">
                                                <button type="submit" class="btn banner-btn">Update Password</button>
                                            </div>
                                        </form>
    
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
    <!--	<img class="img-fluid" src="images/shape2.png" alt="" />-->
    <!--</div>-->
</section>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
