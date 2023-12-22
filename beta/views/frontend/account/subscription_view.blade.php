@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">Subscription Details</h2>
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
                                                <td>Status</td>
                                                <td>
                                                    
                                                    @if(strtotime(date('d-m-Y')) <= strtotime(date('d-m-Y', strtotime($planDetails->expire_at))) )
                                                        @if($planDetails->is_active == 1)
                                                            <span class="badge badge-success">Active</span>
                                                        @else
                                                            <span class="badge badge-danger">Inactive</span>
                                                        @endif
                                                    @else
                                                        <span class="badge badge-danger">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Start Date</td>
                                                <td>{{date_format($planDetails->created_at,'M d, Y')}}</td>
                                            </tr>
                                            <tr>
                                                <td>Next Payment Date</td>
                                                <td>{{date('M d, Y', strtotime($planDetails->expire_at))}}</td>
                                            </tr>
                                            <tr>
                                                <td>End Date</td>
                                                <td>{{date('M d, Y', strtotime($planDetails->expire_at))}}</td>
                                            </tr>
                                            @if(date('d-m-Y') > date('d-m-Y', strtotime($planDetails->expire_at)) )
                                                <!-- <tr>
                                                    <td>Actions</td>
                                                    <td>
                                                        <button type="submit" class="btn btn-danger btn-round">Cancel</button>
                                                        <button type="submit" class="btn btn-primary btn-round">Renew Now</button>
                                                    </td>
                                                </tr> -->
                                            @endif

                                        </tbody>
                                    </table>
                                    <h4>Subscription Totals</h4>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                              <th>PRODUCT</th>
                                              <th>TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @if($planDetails->subscription_type == 'free') 
                                                        Free Membership
                                                    @else
                                                        Paid Membership
                                                    @endif

                                                </td>
                                                <td>₹ {{number_format($planDetails->amount,2)}} / {{$planDetails->duration}} User </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Subtotal:</strong></td>
                                                <td><strong>₹ {{number_format($planDetails->amount,2)}}</strong></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Total:</strong></td>
                                                <td><strong>₹ {{number_format($planDetails->amount,2)}} / {{$planDetails->duration}} User</strong></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <h4>Billing address</h4>
                                    <div class="card">
                                        <div class="card-body">
                                            <p>
                                                @if(isset($billingAddress->address1))
                                                    <i class="fas fa-map-pin"></i> {{$billingAddress->address1}} <br>
                                                @endif
                                                @if(isset($billingAddress->company_name))
                                                    <i class="fas fa-user"></i> {{$billingAddress->company_name}} <br>
                                                @endif
                                                @if(isset($billingAddress->phone_no))
                                                    <i class="fas fa-phone-alt"></i> +91 {{$billingAddress->phone_no}} <br>
                                                @endif
                                                @if(isset($billingAddress->email))
                                                    <i class="fas fa-envelope"></i> {{$billingAddress->email}}
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
