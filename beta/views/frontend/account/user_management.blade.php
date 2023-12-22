@extends('layouts.frontend')
@section('js_after')
<script>
    var number_user = "{{$user_detail->number_user}}";
    function openModal(){
        $('#upgrade_user').modal('show');
    }

    function numberMinus() {
        var number_val = document.getElementById('number').innerHTML;
        number_val = parseInt(number_val);
        if(number_val>1){
            number_val = number_val -1;
            // var total_price = parseInt(price)+parseInt(price_per_user)*(number_val-1);
            document.getElementById('number').innerHTML = number_val;
            document.getElementById('user_number').value = number_val;
            // document.getElementById('total_price_'+key_name).innerHTML = total_price;
        }
    }
    function numberPlus() {
        var number_val = document.getElementById('number').innerHTML;
        number_val = parseInt(number_val);
        // var total_price = parseInt(price)+parseInt(price_per_user)*number_val;
        number_val = number_val +1;
        document.getElementById('number').innerHTML = number_val;
        document.getElementById('user_number').value = number_val;
        // document.getElementById('total_price_'+key_name).innerHTML = total_price;
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
	            <div class="userManagementRight">
                    <h3 class="userHeadding">User Management</h3>
	            </div>
	        </div>
	    </div>
        <div class="row">
            <div class="colProfileLeft">
                @include('frontend.account.left_menu')
            </div>

            <div class="colProfileRight">
                <div class="userManagementRight">  
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
                        <div class="userManagementTop">
                            <form action="" method="get" class="userManagementSearch">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search by name" name="search_text" value="{{$search_text}}">
                                        <div class="input-group-append">
                                            <button class="btn btn-search" type="submit">
                                                <img src="{{asset('f/images/serchIconUser.png')}}" class="img-fluid serchIcon" alt="">
                                            </button>  
                                        </div>
                                </div>
                                <!--<div class="userManagementCount invoiceListTitle">{{count($user_list)+1}} of {{$user_detail->number_user}} user</div>-->
                            </form>
                            <div class="userAddUpgrade">
                                @if($user_detail->number_user > count($user_list)+1)
                                <a href="{{route('account.add_user_management')}}" class="addUserBtn">
                                    <div class="addUserBtnImg">
                                        <img src="{{asset('f/images/img/addUserIcon.png')}}" class="img-fluid addUserIcon" alt="">
                                    </div>
                                    <div class="addUserBtnText">Add User</div>
                                </a>
                                @endif
                                <?php if($package_detail->price > 0){ ?>
                                    <a href="javascript:void();" class="btn banner-btn userBtn" onclick="openModal();">Upgrade</a>
                                <?php }else{ ?>
                                    <a href="javascript:void();" class="btn banner-btn userBtn" onclick="javascript:alert('No Of users can not be updated under this subscription , please upgrade your subscription to available this feature');">Add User</a>
                                <?php } ?>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                @foreach($user_list as $key=>$value)
                                    <div class="card invoiceCard userManagementCard">
                                        <div class="card-body">
                                            <ul class="invoiceList list-unstyled mb-0">
                                                <li class="d-inline-block invoiceListText">
                                                    {{$value->first_name}} {{$value->last_name}}
                                                </li>
                                                <li class="d-inline-block invoiceListText mx-1">|</li>
                                                <li class="d-inline-block invoiceListText" style="color: #25a8e0;">{{$value->email}}</li>
                                            </ul>
                                            <ul class="userManagementIconList list-unstyled mb-0">
                                                <li class="d-inline-block userManagementIcon" data-toggle="modal" onclick='opensetting(<?php echo json_encode($value);?>);' data-backdrop="false">
                                                    <a href="javascript:void(0);">
                                                        <img src="{{asset('f/images/img/settingIcon.png')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                <li class="d-inline-block userManagementIcon">
                                                    <a href="{{route('account.resend_user_management',['id'=> $value->id ])}}" onclick="javascript:return confirm('Do you really want to resend email?');">
                                                        <img src="{{asset('f/images/img/mailIcon.png')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                <li class="d-inline-block userManagementIcon">
                                                    <a href="{{route('account.edit_user_management',['id'=> $value->id ])}}">
                                                        <img src="{{asset('f/images/img/editIcon.png')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                                <?php if($value->is_active){ ?>
                                                    <li class="d-inline-block userManagementIcon">
                                                        <a href="{{route('account.block_user_management',['id'=> $value->id ])}}" onclick="javascript:return confirm('Do you really want to block?');">
                                                            <img src="{{asset('f/images/img/unblockIcon.png')}}" class="img-fluid" alt="">
                                                        </a>
                                                    </li>
                                                <?php }else{ ?>
                                                    <li class="d-inline-block userManagementIcon">
                                                        <a href="{{route('account.block_user_management',['id'=> $value->id ])}}" onclick="javascript:return confirm('Do you really want to unblock?');">
                                                            <img src="{{asset('f/images/img/blockIcon.png')}}" class="img-fluid" alt="">
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                                
                                                <li class="d-inline-block userManagementIcon">
                                                    <a href="{{route('account.delete_user_management',['id'=> $value->id ])}}" onclick="javascript:return confirm('Do you really want to delete?');">
                                                        <img src="{{asset('f/images/img/cross.png')}}" class="img-fluid" alt="">
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    </div>
    <!--<div class="btm-shape-prt">-->
    <!--    <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />-->
    <!--</div>-->
</section>

<div class="modal fade" id="permissionModal">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Permission</h4>
          <button type="button" class="close" data-dismiss="modal">
              <img src="{{asset('f/images/img/closeModal.png')}}" class="img-fluid" alt="">
          </button>
        </div>
        <form action="{{route('account.save_user_permission')}}" method="post">
            @csrf
            <input type="hidden" name="permission_user_id" id="permission_user_id" value="1">
            <!-- Modal body -->
            <div class="modal-body">
                <ul class="linkAccountList mb-0" id="permission_body_div">

                </ul>
            </div>
            
            <!-- Modal footer -->
            <div class="modal-footer">
              <button type="submit" class="btn btn-submit btn-block">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="upgrade_user" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header" style="padding: 14px 15px;">
            <h5 class="modal-title userHeadding mb-0" id="exampleModalLabel">Add Users</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{route('account.upgrade_number_of_user')}}" method="post">
            @csrf
              <div class="modal-body">
                <div class="countPlan">
                    <span id="minus1" onclick="numberMinus()"><img src="{{asset('f/images/img/minusIcon.png')}}" class="img-fluid" alt=""></span>
                    <span class="countNumberPlan" id="number" name="number">
                        1
                    </span>
                    <input type="hidden" name="user_number" value="1" id="user_number">
                    <span id="plus1"  onclick="numberPlus()"><img src="{{asset('f/images/img/addIcon.png')}}" class="img-fluid" alt=""></span>
                </div>
              </div>
              <div class="modal-footer" style="justify-content: center;padding: 10px 15px 26px;">
                <button type="submit" class="btn banner-btn" style="padding: 8px 36px !important;">Update</button>
              </div>
          </form>
        </div>
      </div>
    </div>
<style>
.invalid-feedback {
    display:block;
}
</style>

<script type="text/javascript">
    var login_user_detail = <?php echo json_encode($user_detail) ?>;
    console.log(login_user_detail);
    function opensetting(user_detail){
        console.log(user_detail);
        var iHtml = ``;
        if(login_user_detail.permission_sales_presenter){
            if(user_detail.permission_sales_presenter){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Sales Presenters</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_sales_presenter" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Sales Presenters</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_sales_presenter">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_calculators_proposals){
            
            if(user_detail.permission_calculators_proposals){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Clients Proposals</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_calculators_proposals" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Clients Proposals</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_calculators_proposals">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_investment_suitablity_profiler){
            if(user_detail.permission_investment_suitablity_profiler){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Investment Suitablity Profiler</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_investment_suitablity_profiler" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Investment Suitablity Profiler</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_investment_suitablity_profiler">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_marketing_banners){
            if(user_detail.permission_marketing_banners){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Marketing Banners</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_marketing_banners" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Marketing Banners</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_marketing_banners" >
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_marketing_video){
            if(user_detail.permission_marketing_video){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Marketing Video</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_marketing_video" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Marketing Video</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_marketing_video">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_premade_sales_presenter){
            if(user_detail.permission_premade_sales_presenter){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Pre-made Sales Presenter</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_premade_sales_presenter" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Pre-made Sales Presenter</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_premade_sales_presenter">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }

        if(login_user_detail.permission_trail_calculators){
            if(user_detail.permission_trail_calculators){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Trail Calculators</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_trail_calculators" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Trail Calculators</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_trail_calculators">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_scanner){
            if(user_detail.permission_scanner){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Scanner</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_scanner" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Scanner</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_scanner">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_famous_quotes){
            if(user_detail.permission_famous_quotes){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Other Downloads</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_famous_quotes" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Other Downloads</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_famous_quotes">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        if(login_user_detail.permission_premium_calculator){
            if(user_detail.permission_premium_calculator){
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Premium Calculator</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_premium_calculator" checked="">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }else{
                iHtml = iHtml+`<li>
                            <span style="width: calc(100% - 48px);">Premium Calculator</span>
                            <label class="switch">
                                <input type="checkbox" name="permission_premium_calculator">
                                <span class="slider round"></span>
                            </label>
                        </li>`;
            }
        }
        document.getElementById('permission_body_div').innerHTML = iHtml;
        document.getElementById('permission_user_id').value = user_detail.id;
        $("#permissionModal").modal('show');

    }
</script>

@endsection
