@extends('layouts.frontend')
@section('js_after')
<script>
    $('#amfi_registered_no').click(function (e) {
        //e.preventDefault();
        //confirm("Are you sure want to delete this file?");
        if (confirm("This is a regulatory compliance requirement. Are you sure you want to proceed without this?")) {
            //return true;
            //alert("You pressed OK!");
            $("#amfi_registered_yes").attr('checked', false);
            $("#amfi_registered_no").attr('checked', true);
            
        }else {
            //return false;
            //alert("You pressed Cancel");
            $("#amfi_registered_yes").attr('checked', true);
            $("#amfi_registered_no").attr('checked', false);
            
        }
    });

    function validationForm(){
        var name_check = $("#name_check").is(':checked');
        var company_name_check = $("#company_name_check").is(':checked');

        if(name_check || company_name_check){
            return true;
        }else{
            alert("Please check name or company name");
            return false;
        }
        
    }

</script>
@endsection
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
	                <h3 class="userHeadding">Display Settings</h3>
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
                                    <div class="personalInfoForm displaySettingForm">
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
                                        
                                        <form action="{{route('account.display-settings.update',['id'=> $displayInfo->id])}}" method="post" enctype="multipart/form-data" onsubmit="return validationForm();">
                                        @csrf
                                        <div class="">
                                                <div class="alert alert-info formAlertTop" role="alert">Uncheck the fields you don’t want to display</div>
                                        </div>
                                        
                                        <div class="allFromRow">
                                            <div class="form-row">
                                                    <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <div class="formCheeckAll">
                                                                <div class="formCheeck">
                                                                    <!--<input type="checkbox" name="name_check" value="1" @if(isset($displayInfo['name_check']) && $displayInfo['name_check']==1) checked @endif id="name_check">-->
                                                                    <label class="membershipPt displaySettionCheck">
                                                                        <input type="checkbox" name="name_check" value="1" @if(isset($displayInfo['name_check']) && $displayInfo['name_check']==1) checked @endif id="name_check">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Name</label>&emsp;
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <!--<input type="checkbox" name="name_watermark" value="1" @if(isset($displayInfo['name_watermark']) && $displayInfo['name_watermark']==1) checked @endif>-->
                                                                    <label class="membershipPt displaySettionCheck">
                                                                        <input type="checkbox" name="name_watermark" value="1" @if(isset($displayInfo['name_watermark']) && $displayInfo['name_watermark']==1) checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Watermark</label>
                                                                </div>
                                                            </div>
                                                            <input type="text" name="first_name" class="form-control" value="{{old('first_name',isset($displayInfo->first_name)?$displayInfo->first_name:'')}}" Placeholder="First Name">
                                                            @if ($errors->has('first_name'))
                                                                <div class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('first_name') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>&nbsp;</label>
                                                            <input type="text" name="last_name" class="form-control" value="{{old('last_name',isset($displayInfo->last_name)?$displayInfo->last_name:'')}}" Placeholder="Last Name">
                                                            @if ($errors->has('last_name'))
                                                                <div class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('last_name') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <div class="formCheeckAll">
                                                                <div class="formCheeck">
                                                                    <!--<input type="checkbox" name="company_name_check" value="1" @if(isset($displayInfo['company_name_check']) && $displayInfo['company_name_check']==1) checked @endif id="company_name_check">-->
                                                                    <label class="membershipPt displaySettionCheck">
                                                                        <input type="checkbox" name="company_name_check" value="1" @if(isset($displayInfo['company_name_check']) && $displayInfo['company_name_check']==1) checked @endif id="company_name_check">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Company Name</label>&emsp;
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <!--<input type="checkbox" name="company_name_watermark" value="1" @if(isset($displayInfo['company_name_watermark']) && $displayInfo['company_name_watermark']==1) checked @endif>-->
                                                                    <label class="membershipPt displaySettionCheck">
                                                                        <input type="checkbox" name="company_name_watermark" value="1" @if(isset($displayInfo['company_name_watermark']) && $displayInfo['company_name_watermark']==1) checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Watermark</label>
                                                                </div>
                                                            </div>
                                                                
                                                            <input type="text" name="company_name" class="form-control" value="{{old('company_name',isset($displayInfo->company_name)?$displayInfo->company_name:'')}}">
                                                            @if ($errors->has('company_name'))
                                                                <div class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('company_name') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>AMFI-Registered Mutual Fund Distributor </label><br>
                                                            <div class="formCheeckAll">
                                                                <div class="formCheeck">
                                                                    <!--<input type="radio" name="amfi_registered" id="amfi_registered_yes" value="1" @if(isset($displayInfo['amfi_registered']) && $displayInfo['amfi_registered']==1) checked @endif>-->
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="amfi_registered" id="amfi_registered_yes" value="1" @if(isset($displayInfo['amfi_registered']) && $displayInfo['amfi_registered']==1) checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Yes</label>&emsp;
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <!--<input type="radio" name="amfi_registered" id="amfi_registered_no" value="0" @if(isset($displayInfo['amfi_registered']) && $displayInfo['amfi_registered']==0) checked @endif>-->
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="amfi_registered" id="amfi_registered_no" value="0" @if(isset($displayInfo['amfi_registered']) && $displayInfo['amfi_registered']==0) checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>No</label>
                                                                </div> 
                                                            </div>               
                                                                
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <div class="formCheeck">
                                                                <!--<input type="checkbox" name="phone_no_check" value="1" @if(isset($displayInfo['phone_no_check']) && $displayInfo['phone_no_check']==1) checked @endif>-->
                                                                <label class="membershipPt displaySettionCheck">
                                                                    <input type="checkbox" name="phone_no_check" value="1" @if(isset($displayInfo['phone_no_check']) && $displayInfo['phone_no_check']==1) checked @endif>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label>Phone Number</label>
                                                            </div>
                                                            <input type="text" name="phone_no" class="form-control" value="{{old('phone_no',isset($displayInfo->phone_no)?$displayInfo->phone_no:'')}}"  onkeypress="return isNumber(event)" maxlength="10">
                                                            @if ($errors->has('phone_no'))
                                                                <div class="invalid-feedback" role="alert">
                                                                    <strong>{{ $errors->first('phone_no') }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <div class="formCheeck">
                                                                <!--<input type="checkbox" name="email_check" value="1" @if(isset($displayInfo['email_check']) && $displayInfo['email_check']==1) checked @endif>-->
                                                                <label class="membershipPt displaySettionCheck">
                                                                    <input type="checkbox" name="email_check" value="1" @if(isset($displayInfo['email_check']) && $displayInfo['email_check']==1) checked @endif>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label>Email</label>
                                                            </div>
                                                            <input type="text" name="email" class="form-control" value="{{old('email',isset($displayInfo->email)?$displayInfo->email:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <div class="formCheeck">
                                                                <!--<input type="checkbox" name="website_check" value="1" @if(isset($displayInfo['website_check']) && $displayInfo['website_check']==1) checked @endif>-->
                                                                <label class="membershipPt displaySettionCheck">
                                                                    <input type="checkbox" name="website_check" value="1" @if(isset($displayInfo['website_check']) && $displayInfo['website_check']==1) checked @endif>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label>Website</label>
                                                            </div>
                                                            <input type="text" name="website" class="form-control" value="{{old('website',isset($displayInfo->website)?$displayInfo->website:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <div class="formCheeck">
                                                                <!--<input type="checkbox" name="address_check" value="1" @if(isset($displayInfo['address_check']) && $displayInfo['address_check']==1) checked @endif>-->
                                                                <label class="membershipPt displaySettionCheck">
                                                                    <input type="checkbox" name="address_check" value="1"  @if(isset($displayInfo['address_check']) && $displayInfo['address_check']==1) checked @endif>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label>Address Line 1</label>
                                                            </div>
                                                            <input type="text" name="address" class="form-control" maxLength="40" value="{{old('address',isset($displayInfo->address)?$displayInfo->address:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12">
                                                        <div class="form-group">
                                                            <div class="formCheeck">
                                                                <!--<input type="checkbox" name="address2_check" value="1" @if(isset($displayInfo['address2_check']) && $displayInfo['address2_check']==1) checked @endif>-->
                                                                <label class="membershipPt displaySettionCheck">
                                                                    <input type="checkbox" name="address2_check" value="1" @if(isset($displayInfo['address2_check']) && $displayInfo['address2_check']==1) checked @endif>
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label>Address Line 2</label>
                                                            </div>
                                                            <input type="text" name="address2" class="form-control" maxLength="35" value="{{old('address2',isset($displayInfo->address2)?$displayInfo->address2:'')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-row bellowColorBrand">
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Brand color 1</label>
                                                            <input type="color" name="name_color" class="form-control" value="{{old('name_color',isset($displayInfo->name_color)?$displayInfo->name_color:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Brand color 2</label>
                                                            <input type="color" name="company_name_color" class="form-control" value="{{old('company_name_color',isset($displayInfo->company_name_color)?$displayInfo->company_name_color:'')}}">
                                                        </div>
                                                    </div>
        
        
                                                </div>
                                                <div class="form-row bellowColorBrand">
                                                    <div class="col-lg-12 mt-2">
                                                        <h5 style="color:#000;">Cover Page Images</h5>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label>PDF Cover Image </label>
                                                            
                                                            @if(!empty($displayInfo->pdf_cover_image))
                                                            <img width="250px" src="{{asset('uploads/salespresentersoftcopy')}}/{{$displayInfo->pdf_cover_image}}"><br>
                                                            @endif
                                                            <a href="#" data-toggle="modal" data-target="#myModalLogos">Choose new one here</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-row bellowColorBrand">
                                                    <div class="col-lg-12 mt-2">
                                                        <h5 style="color:#000;">Marketing Banner Setting</h5>
                                                    </div>
        
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Name color</label>
                                                            <input type="color" name="address_color" class="form-control" value="{{old('address_color',isset($displayInfo->address_color)?$displayInfo->address_color:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Company Name Color</label>
                                                            <input type="color" name="website_color" class="form-control" value="{{old('website_color',isset($displayInfo->website_color)?$displayInfo->website_color:'')}}">
                                                        </div>
                                                    </div> 
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Footer Background Color </label>
                                                            <input type="color" name="phone_no_color" class="form-control" value="{{old('phone_no_color',isset($displayInfo->phone_no_color)?$displayInfo->phone_no_color:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 colorBrand">
                                                         <div class="form-group colorBrand">
                                                            <label>Footer Branding Color</label>
                                                            <input type="color" name="email_color" class="form-control" value="{{old('email_color',isset($displayInfo->email_color)?$displayInfo->email_color:'')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
                                               
                                                <div class="form-row bellowColorBrand">
                                                     <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>Marketing Banner Branding Option </label><br>
                                                            <div class="formCheeckAll d-block">
                                                                <div class="formCheeck">
                                                                    <!--<input type="radio" name="template" value="top_right_logo_centre_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='top_right_logo_centre_text') checked @endif>-->
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="template" value="top_right_logo_centre_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='top_right_logo_centre_text') checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Logo at Top right & Text at Bottom Centre Aligned.</label><br>
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <!--<input type="radio" name="template" value="no_logo_centre_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='no_logo_centre_text') checked @endif>-->
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="template" value="no_logo_centre_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='no_logo_centre_text') checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>No Logo & Text Centre Aligned</label><br>
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <!--<input type="radio" name="template" value="buttom_left_logo_right_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='buttom_left_logo_right_text') checked @endif>-->
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="template" value="buttom_left_logo_right_text" @if(isset($displayInfo['template']) && $displayInfo['template']=='buttom_left_logo_right_text') checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Logo at bottom left & Text on right</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>Select Sales Presenter Template </label><br>
                                                            <input type="radio" name="template_soft_copy" value="normal" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='normal') checked @endif>
                                                            <label>Default</label>&emsp;
                                                            <input type="radio" name="template_soft_copy" value="buttom_left_logo_right_text" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='buttom_left_logo_right_text') checked @endif>
                                                            <label>Bottom left logo right text</label><br>
                                                            <input type="radio" name="template_soft_copy" value="no_logo_centre_text" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='no_logo_centre_text') checked @endif>
                                                            <label>No logo centre text</label>
                                                        </div>
                                                    </div> -->
                                                    
                                                </div>
                                                
                                                 <div class="form-row bellowColorBrand">
                                                    
                                                    
                                                    <div class="col-lg-12 mt-2">
                                                        <h5 style="color:#000;">Calculator and MF Research Setting</h5>
                                                    </div>

                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Color 1</label>
                                                            <input type="color" name="city_color" class="form-control" value="{{old('city_color',isset($displayInfo->city_color)?$displayInfo->city_color:'')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3">
                                                         <div class="form-group colorBrand">
                                                            <label>Color 2</label>
                                                            <input type="color" name="address_color_background" class="form-control" value="{{old('address_color_background',isset($displayInfo->address_color_background)?$displayInfo->address_color_background:'')}}">
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                
                                                <div class="form-row bellowColorBrand">
                                                     <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>Calculator and MF Research Footer Branding Option </label><br>
                                                            <div class="formCheeckAll d-block">
                                                                <div class="formCheeck">
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="footer_branding_option" value="all_pages" @if(isset($displayInfo['footer_branding_option']) && $displayInfo['footer_branding_option']=='all_pages') checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>All Pages</label><br>
                                                                </div>
                                                                <div class="formCheeck">
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="footer_branding_option" value="last_page" @if(isset($displayInfo['footer_branding_option']) && $displayInfo['footer_branding_option']=='last_page') checked @endif>
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label>Last Page</label><br>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-lg-6">
                                                         <div class="form-group">
                                                            <label>Select Sales Presenter Template </label><br>
                                                            <input type="radio" name="template_soft_copy" value="normal" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='normal') checked @endif>
                                                            <label>Default</label>&emsp;
                                                            <input type="radio" name="template_soft_copy" value="buttom_left_logo_right_text" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='buttom_left_logo_right_text') checked @endif>
                                                            <label>Bottom left logo right text</label><br>
                                                            <input type="radio" name="template_soft_copy" value="no_logo_centre_text" @if(isset($displayInfo['template_soft_copy']) && $displayInfo['template_soft_copy']=='no_logo_centre_text') checked @endif>
                                                            <label>No logo centre text</label>
                                                        </div>
                                                    </div> -->
                                                    
                                                </div>
                                                
                                                <div class="form-row bellowColorBrand">
                                                    
                                                    <div class="col-lg-12 mt-2">
                                                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                                          <strong>Note:</strong> PDFs and Marketing Videos will have fixed display of Logo, Name, Company Name and Mobile No.
                                                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                          </button>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 text-center">
                                                        <div class="uploadedLogoBtn">
                                                            <button type="submit" class="btn banner-btn">Update Profile</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                
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
    <div class="btm-shape-prt">
        <img class="img-fluid" src="images/shape2.png" alt="" />
    </div>
</section>



<div id="myModalLogos" class="modal fade" role="dialog" style="padding-right: 400px;">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content" style="width: 900px;">
        <div class="modal-header">
           <h4 class="modal-title">Choose PDF Cover Image</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <form action="{{route('account.display-pdfcover.update',['id'=> $displayInfo->id])}}" method="post" name="update_pdf_cover" enctype="multipart/form-data">
                @csrf
                  <!--<img src="{{asset('f/images/instruction.png')}}" width="850px;">-->
                  <div class="row">
                      @if(!empty($coverImages))
                          @foreach($coverImages as $adminlogo)
                              <div class="col-md-4 text-center">
                                  <input type="radio" id="male{{$adminlogo->id}}" name="cover_image" value="{{$adminlogo->id}}">
                                  <label for="male{{$adminlogo->id}}"><img width="200px" src="{{asset('uploads/salespresentersoftcopy')}}/{{$adminlogo->image}}"></label>
                              </div>
                           @endforeach
                       @endif
                   </div>
                   <br>
                   <button type="submit" name="update_pdf_cover" id="update_pdf_cover" class="btn btn-primary btn-round">Update</button>
              </form>
        </div>
        <!-- <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div> -->
      </div>
  
    </div>
</div>
  
<style>
.invalid-feedback {
    display:block;
}
</style>

<script type="text/javascript">
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            return false;
        }
        return true;
    }
</script>

@endsection
