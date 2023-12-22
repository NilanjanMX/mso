@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">{{isset($page['title'])?$page['title']:''}}</h2>
            </div>
        </div>
    </div>
    <a href="#" class="btn-chat">Chat With Us</a>
</div>


<section class="main-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12">     
                <div class="stage">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card planCard cartCard">
                                <table class="cartTable cartTableProduct">
                                  <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th></th>
                                    <th>Total Price</th>
                                  </tr>
                                  <tr>
                                    <td>
                                        <div class="productTitle">{{$package_name}} <img style="visibility: hidden;" src="{{asset('f/images/img/planAsk.png')}}" class="img-fluid planAsk" alt=""></div>
                                    </td>
                                    <td>
                                        <div class="productPrice">₹{{number_format((float)$total_price_per_user, 2, '.', '')}}</div>
                                    </td>
                                    <td>
                                        <img style="visibility: hidden;" src="{{asset('f/images/img/cartClose.png')}}" class="img-fluid cartClose" alt="">
                                    </td>
                                    <td>
                                        <div class="productTotalPrice">₹{{number_format((float)$total_price_per_user, 2, '.', '')}}</div>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td colspan="3">
                                        <div class="productGst">For {{$user->number_user}} User</div>
                                    </td>
                                    <td></td>
                                  </tr>
                                </table>
                                <table class="cartTable cartTableCoupon">
                                  <tr>
                                    <td colspan="2" style="background-color: #f0f1f5;">
                                        <form action="" class="couponForm">
                                            
                                            <div class="productCouponField">
                                                <input type="hidden" name="package_id" value="{{$package_id}}">
                                                <input type="hidden" name="user_number" value="{{$total_user}}">
                                                <input type="text" class="form-control" id="" placeholder="Enter Your Coupon Code if you have" name="coupon_code" value="{{$coupon_code}}">
                                                <?php if($coupon_code){ ?>
                                                    <img src="https://masterstroke.5gsoftware.net/public/f/images/img/cross.png" class="img-fluid couponClose" alt="">
                                                <?php } ?>
                                            </div>
                                            <button type="submit" class="productCouponTitle">Apply Coupon Code</button>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="productTotalPrice">₹{{number_format((float)$coupon_price, 2, '.', '')}}</div>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td colspan="2">
                                        Apply Membership Point (₹ {{number_format((float)$wallet_amount, 2, '.', '')}})
                                        <span style="float: right;">
                                            <input type="checkbox" name="wallet_amount" id="wallet_amount" value="1" onchange="change_wallet_amount();">
                                        </span>
                                    </td>
                                    <td>
                                         <div class="productTotalPrice productTotalGrand" id="apply_membership_point_id"></div>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td colspan="2">
                                        <div class="productTitle">Total Payable Amount (GST Inclusive)</div>
                                    </td>
                                    <td>
                                        <div class="productTotalPrice productTotalGrand" id="total_paybal_amount_id">₹{{number_format((float)$total_price_per_user_gst, 2, '.', '')}}</div>
                                    </td>
                                  </tr>

                                  <tr>
                                    <td colspan="2">
                                    </td>
                                    <td>
                                        <form action="{{route('frontend.membership_update_payment')}}" method="post" style="height: 100%;">
                                            @csrf()
                                            <input type="hidden" name="package_id" value="{{$package_id}}">
                                            <input type="hidden" name="total_user" value="{{$user->number_user}}">
                                            <input type="hidden" name="total_amount" value="{{number_format((float)($total_price_per_user - $coupon_price)*1.18, 2, '.', '')}}" id="total_amount">
                                            <input type="hidden" name="wallet_amount_id" value="" id="wallet_amount_id">
                                            <button type="submit" class="checkOut" style="background-color: #131f55;cursor: pointer;height: 100%;width: 100%;">PROCEED TO CHECKOUT</button>
                                        </form>
                                    </td>
                                  </tr>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- <div class="btm-shape-prt">
        <img class="img-fluid" src="images/shape2.png" alt="" />
    </div> -->
</section>

    <div class="modal fade" id="errorAfterLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="successHead text-center">
                <!--<h5 class="modal-title" id="exampleModalLongTitle">Success</h5>-->
                <img src="{{asset('images/lock-icon.png')}}" alt="">
                <h3>Alert</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="success_model_body">
                    
                </p>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Ok</button>
            </div>
        </div>
      </div>
    </div>
@endsection

@section("js_after")

    <style>
        .productCouponTitle {
            font-size: 14px;
            color: #fff;
            text-align: center;
            background-color: #16a1db;
            width: 187px;
            height: 37px;
            cursor: pointer;
            padding: 5px;
            border-radius: 9px;
            margin: 0 auto;
        }
        .modal-content {
            border-radius: 15px !important;
        }
        .successHead {
            background: #16a1dc;
            text-align: center;
            padding: 20px 0 10px 0;
            border-radius: .7rem .7rem 0 0;
        }
        .successHead h3 {
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .successHead button.close {
            position: absolute;
            right: 10px;
            top: 10px;
        }
        #success_model_body {
            color: #444444;
            font-size: 12px;
            text-align: center;
            margin-top: 0.7rem;
            margin-bottom: 0.5rem;
        }
        #successModal .modal-footer {
            justify-content: center;
        }
        .btnblue {
            padding: .5rem 2rem;
            border-radius: 1.5rem;
            background: #141f55;
        }
    </style>
    <script type="text/javascript">

        var messege = "{{$message}}";

        if(messege){
            document.getElementById("success_model_body").innerHTML = messege;
            $("#errorAfterLoginModal").modal('show');
        }

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
            if(wallet_amount){
                wallet_amount = parseFloat(g_total_amount) - parseFloat(g_wallet_amount);
                console.log("2#"+g_total_amount);
                console.log("3#"+g_wallet_amount);
                console.log("4#"+wallet_amount);
                if(wallet_amount <= 0){
                    wallet_amount = g_total_amount;
                    total_amount = "0.00";
                    console.log("1#"+wallet_amount);
                }else{
                    total_amount = wallet_amount * 1.18;
                    console.log(total_amount);
                    total_amount = parseFloat(total_amount).toFixedNoRounding(2);
                    wallet_amount = parseFloat(g_wallet_amount).toFixedNoRounding(2);
                }
                console.log(wallet_amount);
                document.getElementById('wallet_amount_id').value = wallet_amount;
                document.getElementById('apply_membership_point_id').innerHTML = "₹"+wallet_amount;
                document.getElementById('total_amount').value = total_amount;
                document.getElementById('total_paybal_amount_id').innerHTML = "₹"+total_amount;
            }else{
                wallet_amount = parseFloat(g_total_amount) * 1.18;
                document.getElementById('wallet_amount_id').value = 0;
                document.getElementById('apply_membership_point_id').innerHTML = '';
                document.getElementById('total_paybal_amount_id').innerHTML = "₹"+wallet_amount.toFixedNoRounding(2);
                document.getElementById('total_amount').value = wallet_amount;
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
    </script>
@endsection
