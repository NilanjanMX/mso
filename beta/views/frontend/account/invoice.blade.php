@extends('layouts.frontend')

@section('content')
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">User Management</h2>
            </div>
        </div>
    </div>
    <a href="#" class="btn-chat">Chat With Us</a>
</div>
<section class="main-sec">
	<div class="container">
    	<div class="row">
            <div class="col-md-3">
                @include('frontend.account.left_menu')
            </div>

            <div class="col-md-9">     
                <div class="stage">
                    <div class="row">
                        <div class="col-lg-12">
                            @foreach($invoice_list as $key=>$value)
                                <div class="card invoiceCard">
                                    <div class="card-body">
                                        <ul class="invoiceList list-unstyled mb-0">
                                            <li class="d-inline-block invoiceListTitle">Invoice No:</li>
                                            <li class="d-inline-block invoiceListText"># {{$value->id}}</li>
                                            <li class="d-inline-block invoiceListText mx-1">|</li>
                                            <li class="d-inline-block invoiceListTitle">Invoice Date:</li>
                                            <li class="d-inline-block invoiceListText">{{$value->created_at}}</li>
                                            <li class="d-inline-block invoiceListText mx-1">|</li>
                                            <li class="d-inline-block invoiceListTitle">Invoice Amount:</li>
                                            <li class="d-inline-block invoiceListText">₹{{$value->amount}}</li>
                                        </ul>
                                        <div class="downloadIcon">
                                            <a href="#"><img src="{{asset('f/images/img/downloadIcon.png')}}" class="img-fluid downloadIconSet" alt=""></a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="btm-shape-prt">
    	<img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="" />
    </div>
</section>
<style>
.invalid-feedback {
    display:block;
}
</style>

@endsection
