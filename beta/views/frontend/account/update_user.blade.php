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
                    <h2 class="py-4">User Upgrade</h2>
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
        <div class="stage">
            <div class="main-cart-bx cartAdjBox userUpdrade">
                <div class="lft-cart-prt">
                        <!--<div class="card planCard cartCard">-->
                        <div class="cart-table table-responsive">
                                <!--<table class="cartTable cartTableProduct">-->
                                <table class="table table-borderless center mb-0">
                                  <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                  </tr>
                                  <tr>
                                    <td>
                                        <div class="pro-detail">
                                            <div class="pro-txt">
                                                <a href="#"><img class="img-fluid" src="{{asset('')}}img/delCart.png" alt="" /></a>
                                                <h3>{{$package_name}}</h3>
                                                <!--<img style="visibility: hidden;" src="{{asset('f/images/img/planAsk.png')}}" class="img-fluid planAsk" alt="">-->
                                            </div>
                                        </div>
                                    </td>
                                    <td>Rs {{number_format((float)$total_price_per_user, 2, '.', '')}}</td>
                                    <td>
                                        <!--<img style="visibility: hidden;" src="{{asset('f/images/img/cartClose.png')}}" class="img-fluid cartClose" alt="">-->
                                        For {{$total_user}} User
                                    </td>
                                    <td>Rs {{number_format((float)$total_price_per_user, 2, '.', '')}}</td>
                                  </tr>
                                </table>
                                
                            </div>
                    </div>
                <div class="rt-cart-prt">
                    <!--<h2 class="headline">Order Summary</h2>-->
                    <ul class="mt-0">
                        <li>
                            <form class="couponForm" method="get" id="apply_coupon_form">
                                <div class="input-group couponInput">
                                            
                                            <input type="hidden" name="user_number" value="{{$total_user}}">
                                            <input type="text" class="form-control" placeholder="Have a coupon?" id="coupon_code" name="coupon_code" value="{{$coupon_code}}">
                                                <!--@if($coupon_code)-->
                                                <!--    <a href="javascript:void(0);" onclick="close_coupon_code();">-->
                                                <!--        <img src="https://masterstroke.5gsoftware.net/public/f/images/img/cross.png" class="img-fluid couponClose" alt="" >-->
                                                <!--    </a>-->
                                                <!--@endif-->
                                            <div class="input-group-append">
                                                <button type="submit" class="btn-explore mt-0">Apply</button>
                                            </div>
                                </div>
                            </form>
                            <span class="couponAlertMsg" id="coupon_error"></span>
                            <!--<div class="form-group">-->
                            <!--    <input type="text" name="coupon" id="coupon_code" class="form-control" placeholder="Have a coupon?">-->
                            <!--    <span id="coupon_error" style="color:red;text-transform: capitalize;font-weight: 400;font-size: 16px;"></span>-->
                            <!--</div>-->
                            <!--<a href="javascript:void(0)" class="btn-explore" id="coupon_apply">Apply</a>-->
                        </li>
                        <!--<li>TOTAL QTY <span>2</span></li>-->
                        <li style="margin: 52px 0px 20px;">Subtotal <span>Rs {{number_format((float)$total_price_per_user, 2, '.', '')}}</span></li>
                        @if($wallet_amount)
                            <li style="color: #7D7987;margin: 5px 0px 4px;">Apply Membership Points 
                                <span style="width: 22.5px;">
                                    <label class="membershipPt">
                                      <input type="checkbox" checked="checked" name="wallet_amount" id="wallet_amount" value="1" onchange="change_wallet_amount();">
                                      <span class="checkmark"></span>
                                    </label>
                                </span>
                            </li>
                            <li style="color: #458FF6;">Points Available <span>{{number_format((float)$wallet_amount, 2, '.', '')}}</span></li>
                            <li style="margin-top: 1px;">Discount <span id="apply_membership_point_id"></span></li>
                            <li style="margin: 9px 0px 18px;">Subtotal(After Discount) <span id="subtotal_apply_membership_point_id"></span></li>
                        @endif
                        <li>Total (Before Tax) <span id="total_before_tax_amount_id">Rs {{number_format((float)$total_price_per_user, 2, '.', '')}}</span></li>
                        <li>GST @18 % <span id="total_tax_amount_id">Rs {{number_format((float)$total_price_per_user*0.18, 2, '.', '')}}</span></li>
                        <li id="gst_number_view">
                            GST No: 
                            @if($user->gst_number)
                                {{$user->gst_number}}
                            @else
                                <a href="javascript:void(0);" onclick="openGSTModal();">Please Provide</a>
                            @endif
                        </li>
                    </ul>
                    <div class="total-prt">
                        <ul id="amount_price_view">                            
                            <li>Payable(Incl. GST) <span id="total_paybal_amount_id">Rs {{number_format((int)($total_amount_gst - $coupon_price), 2, '.', '')}}</span></li>
                        </ul>
                        <form action="{{route('account.membership_update_user')}}" method="post" style="height: 100%;">
                            @csrf()
                            <input type="hidden" name="package_id" value="{{$package_id}}">
                            <input type="hidden" name="total_user" value="{{$total_user}}">
                            <input type="hidden" name="total_amount" value="{{number_format((int)($total_amount_gst - $coupon_price), 2, '.', '')}}" id="total_amount">
                            <input type="hidden" name="wallet_amount_id" value="" id="wallet_amount_id">
                            <button type="submit" class="btn-explore btn-checkOut" style="">Check Out</button>
                        </form>
                    </div>
                </div>
            </div>            
        </div>
    </div>
    <!-- <div class="btm-shape-prt">
        <img class="img-fluid" src="images/shape2.png" alt="" />
    </div> -->
</section>

    <div class="modal fade" id="saveGSTModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">GST</h5>
                <button type="button" class="close"  onclick="closeGSTModal();">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="margin-bottom: 0px;">GST No</label>
                    <input type="text" name="gst_number" id="gst_number" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;" maxlength="15">
                </div>
                <div id="gst_number_error" class="form-group" style="margin-bottom: 0px;display: none;"> 
                    <small style="color: red;">Required</small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeGSTModal();">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveGST();">SAVE</button>
            </div>
        </div>
      </div>
    </div>
<style>
@media (max-width: 700px) {
    .cartCard th, .productGst {
        font-size: 12px !important;
        line-height: 18px;
    }
    .cartCard .productTitle, .cartCard .productPrice, .cartCard .productTotalPrice {
        font-size: 12px !important;
        line-height: 18px;
    }
    .cartCard th:last-child, .cartCard td:last-child {
        width: auto !important;
    }
    .couponForm {
        display: block !important;
    }
    .productCouponField {
        width: auto !important;
        background-color: #f0f1f5
    }
    .form-control, .productCouponTitle {
        width: auto !important;
        height: auto !important;
        min-height:auto !important;
    }
    .checkOut {
        font-size: 12px !important;
    }
}
</style>
@endsection

@section("js_after")
<script type="text/javascript">

    var cart_base_url = "{{url('/')}}";
    var g_total_amount = "{{$total_price_per_user - $coupon_price}}";
    var g_wallet_amount = "{{$wallet_amount}}";
    console.log(g_wallet_amount);
    Number.prototype.toFixedNoRounding = function(n) {
        const reg = new RegExp("^-?\\d+(?:\\.\\d{0," + n + "})?", "g")
        const a = this.toString().match(reg)[0];
        const dot = a.indexOf(".");
        if (dot === -1) { // integer, insert decimal dot and pad up zeros
            return a + "." + "0".repeat(n);
        }
        const b = n - (a.length - dot) + 1;
        return b > 0 ? (a + "0".repeat(b)) : a;
    }
    
    function change_wallet_amount(){
        var wallet_amount = $("#wallet_amount").attr('checked');
        var wallet_amount_view = (wallet_amount)?1:0;
        var total_amount = "";
        var total_amount_before_tax = 0;
        var total_tax_amount = 0;
        if(wallet_amount){
            wallet_amount = parseFloat(g_total_amount) - parseFloat(g_wallet_amount);
            console.log("4#"+wallet_amount);
            console.log("2#"+g_total_amount);
            console.log("3#"+parseFloat(g_wallet_amount));
            if(wallet_amount <= 0){
                wallet_amount = g_total_amount;
                total_amount = "0.00";
                total_amount_before_tax = "0.00";
                total_tax_amount = "0.00";
                console.log("1#"+wallet_amount);
            }else{
                total_amount = parseFloat(wallet_amount) * 1.18;
                total_amount_before_tax = parseFloat(wallet_amount);
                total_tax_amount = parseFloat(wallet_amount) * .18;
                console.log("8#"+total_amount);
                total_amount = parseFloat(total_amount).toFixedNoRounding(2);
                wallet_amount = parseFloat(g_wallet_amount).toFixedNoRounding(2);
            }
            console.log(wallet_amount);
            document.getElementById('wallet_amount_id').value = wallet_amount;
            document.getElementById('apply_membership_point_id').innerHTML = "Rs "+wallet_amount;
            document.getElementById('subtotal_apply_membership_point_id').innerHTML = "Rs "+total_amount;
            document.getElementById('total_before_tax_amount_id').innerHTML = "Rs "+total_amount_before_tax;
            document.getElementById('total_tax_amount_id').innerHTML = "Rs "+total_tax_amount;
            document.getElementById('total_amount').value = parseInt(total_amount);
            document.getElementById('total_paybal_amount_id').innerHTML = "Rs "+parseInt(total_amount);
        }else{
            total_amount = parseFloat(g_total_amount*1.18).toFixedNoRounding(2);
            total_tax_amount = parseFloat(g_total_amount*.18).toFixedNoRounding(2);
            total_amount_before_tax = parseFloat(g_total_amount).toFixedNoRounding(2);
            document.getElementById('wallet_amount_id').value = 0;
            document.getElementById('apply_membership_point_id').innerHTML = '';
            document.getElementById('subtotal_apply_membership_point_id').innerHTML = 'Rs '+total_amount;
            document.getElementById('total_before_tax_amount_id').innerHTML = 'Rs '+total_amount_before_tax;
            document.getElementById('total_tax_amount_id').innerHTML = 'Rs '+total_tax_amount;
            document.getElementById('total_paybal_amount_id').innerHTML = "Rs "+parseInt(total_amount);
            document.getElementById('total_amount').value = parseInt(total_amount);
        }

        $.ajax({
            url: cart_base_url+'/check-point/'+wallet_amount_view,
            //url: 'http://172.16.10.42/masterstrokeonline.myvtd.site/public'+'/coupon/'+coupon_code,
            type: 'get',
            //dataType: 'json',
            success: function(response){
                console.log(response);
                
            }
        });
    }

    function close_coupon_code(){
        console.log(document.getElementById('user_number'));
        console.log(document.getElementById('coupon_code'));
        document.getElementById('coupon_code').value = "";
        document.getElementById('apply_coupon_form').submit();
    }
    
    change_wallet_amount();

    function openGSTModal(){
        $("#saveGSTModal").modal("show");
    }

    function closeGSTModal(){
        $("#saveGSTModal").modal("hide");
    }

    function saveGST(){
        var all_data = {};
        all_data.gst_number = document.getElementById("gst_number").value;
        $.ajax({
            url: cart_base_url+'/account/update-gst-number',
            type: 'get',
            data: all_data,
            success: function(response){
                console.log(response);
                document.getElementById("gst_number_view").innerHTML = "GST No: "+response;
                $("#saveGSTModal").modal("hide");
            }
        });
    }
</script>
@endsection
