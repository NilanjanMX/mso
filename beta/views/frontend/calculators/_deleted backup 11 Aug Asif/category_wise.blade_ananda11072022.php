@extends('layouts.frontend')
<style>
    .knoch {
        position: absolute;
        right: 26px;
        top: 15px;
    }
    .sreports {
        position: absolute;
        right: 28px;
        bottom: 39px;
    }
    .calculator-bx h3 {
        padding-right: 32px !important;
    }
</style>
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <div class="rt-pnl">
                        <h2 class="headline">{{$category_detail->description}} &nbsp;</h2>
                        <div class="rt-btn-prt">
                            <a href="{{route('frontend.calculatorSampleReport',['type'=>'category','id'=>$category_id])}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div>
                        <div class="row mar-top">
                            @foreach($calculator_list as $key => $value)
                                <div class="col-md-6">
                                    <a href="@if($value->is_view) {{url('')}}/{{$value->url}} @else javascript:void(0); @endif" @if(!$value->is_view) onclick="openViewPermissionModal()"; @endif>
                                        <div class="calculator-bx">
                                            <div class="cal-ico">
                                                <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                                <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                            </div>
                                            <h3>{{$value->name}}</h3>
                                            <a class="knoch" href="{{route('frontend.calculatorSampleReport',['type'=>'calculator','id'=>$value->calculator_id])}}" target="_blank">
                                                <img class="img-fluid" src="{{asset('')}}/img/sample_report_1.png" alt="">
                                            </a>
                                            @if($value->type == "Premium")
                                                <a href="#" target="_blank" class="sreports">
                                                    <img class="img-fluid" src="{{asset('')}}/img/knoch_icon.png" alt="">
                                                </a>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
    </section>

    @include('frontend.calculators.modal')

@endsection
