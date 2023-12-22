@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">Order Details</h2>
            </div>
        </div>
    </div>
    <a href="#" class="btn-chat">Chat With Us</a>
</div>
<section class="main-sec">
	<div class="container">
    	<div class="row">
            <div class="col-md-8 offset-md-2">     
                <div class="stage">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>Order ID</td>
                                                <td>#{{$orderDetails[0]['invoice_id']}}</td>
                                            </tr>
                                            <tr>
                                                <td>Amount</td>
                                                <td>₹ {{number_format($orderDetails[0]['payable_amount'],2)}}</td>
                                            </tr>
                                            <tr>
                                                <td>Date</td>
                                                <td>{{date('M d, Y', strtotime($orderDetails[0]['order_date']))}}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{{ucfirst($orderDetails[0]['status'])}}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <h4>Order Product</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                              <th>PRODUCT</th>
                                              <th>QUANTITY</th>
                                              <th>AMOUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($orderDetails[0]['orderitems']))
                                                @php
                                                $subtotal = 0;
                                                $total = 0;
                                                @endphp
                                                @foreach($orderDetails[0]['orderitems'] as $orderitems)
                                                @php
                                                $subtotal = $subtotal + $orderitems['price'];
                                                $total = $total+$subtotal-$orderDetails[0]['coupon_amount'];
                                                @endphp
                                                <tr>
                                                    <td>{{$orderitems['product_name']}}</td>
                                                    <td>{{$orderitems['quantity']}}</td>
                                                    <td>₹ {{number_format($orderitems['price'],2)}} </td>
                                                </tr>
                                                
                                                @endforeach
                                                
                                                <tr>
                                                    
                                                    <td colspan="2" style="text-align:right;"><strong>Subtotal</strong></td>
                                                    <td><strong>₹ {{number_format($subtotal,2)}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:right;"><strong>Discount</strong></td>
                                                    <td><strong>₹ {{number_format($orderDetails[0]['coupon_amount'],2)}}</strong></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align:right;"><strong>Total</strong></td>
                                                    
                                                    <td><strong>₹ {{number_format($total,2)}}</strong></td>
                                                </tr>
                                                
                                            @endif
                                        </tbody>
                                    </table>
                                    <h4>Billing address</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <p>
                                                @if(isset($orderDetails[0]['billing_address']['name']))
                                                    Name: {{$orderDetails[0]['billing_address']['name']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['company_name']))
                                                    Company Name: {{$orderDetails[0]['billing_address']['company_name']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['country']))
                                                    Country: {{$orderDetails[0]['billing_address']['country']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['street_name']))
                                                    Street Name: {{$orderDetails[0]['billing_address']['street_name']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['city']))
                                                    City: {{$orderDetails[0]['billing_address']['city']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['state']))
                                                    State: {{$orderDetails[0]['billing_address']['state']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['zip_code']))
                                                    Zip Code: {{$orderDetails[0]['billing_address']['zip_code']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['phone_no']))
                                                    Phone No: {{$orderDetails[0]['billing_address']['phone_no']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['email']))
                                                    Email: {{$orderDetails[0]['billing_address']['email']}} <br>
                                                @endif
                                                @if(isset($orderDetails[0]['billing_address']['additional_info']))
                                                    Additional Info: {{$orderDetails[0]['billing_address']['additional_info']}} <br>
                                                @endif
                                            </p>
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
    <div class="btm-shape-prt">
    	<img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">
    </div>
</section>

@endsection
