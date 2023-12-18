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
                        <h2 class="headline">SIP CALCULATOR</h2>
                        <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div>
                        <div class="row mar-top">
                            <div class="col-md-6">
                                <a href="{{route('frontend.futureValueOfSipIndex')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Future Value of SIP</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.sipRequiredForFutureValue')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>SIP Required For Target Future Value</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Future Value of Limited Period SIP</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.limitedPeriodSIPgoalPlanningCalculator')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Limited Period SIP Goal Planning Calculator</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.futureValueOfStepUpSIP')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Future Value of Step-Up SIP</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.futureValueOfStepUpSIPRequiredTarget')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Step-Up SIP Required For Target Future Value</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.sipFutureValueReadyRecokner')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>SIP Future Value Ready Reckoner</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.sipGoalPlanningReadyRecokner')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>SIP Goal Planning Ready Reckoner</h3>
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
