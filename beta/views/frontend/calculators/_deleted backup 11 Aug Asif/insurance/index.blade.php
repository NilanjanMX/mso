@extends('layouts.frontend')

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
                        <h2 class="headline">MF VS INSURANCE</h2>
                        <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div>
                        <div class="row mar-top">

                            <div class="col-md-6">
                                <a href="{{route('frontend.termInsuranceSIP')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Term Insurance + SIP</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.termInsuranceSIPgoalBase')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Term Insurance + SIP (Goal Based)</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.insuranceTermCover')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Insurance vs. Term Cover With Annual SIP</h3>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
