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
                        <h2 class="headline">SWP CALCULATOR</h2>
                        <div class="rt-btn-prt">
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div>
                        <div class="row mar-top">

                            <div class="col-md-6">
                                <a href="{{route('frontend.swp_debt_balanc_fund_calculator')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>SWP Rebalance Calculator</h3>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{route('frontend.bank_fd_vs_mutual_fund_swp')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Bank FD vs. Mutual Fund SWP</h3>
                                </div>
                                </a>
                            </div>

                            <div class="col-md-6">
                                <a href="{{route('frontend.monthlyAnnuityForLumpsumInvestment')}}">
                                <div class="calculator-bx">
                                    <div class="cal-ico">
                                        <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                        <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                    </div>
                                    <h3>Monthly SWP For Lumpsum Investment</h3>
                                </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuity')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Lumpsum Investment Required For Target Monthly SWP</h3>
                                    </div>
                                </a>
                            </div>
                           <!--  <div class="col-md-6">
                                <a href="{{route('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriod')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Monthly Annuity For Lumpsum Investment With Deferment Period</h3>
                                    </div>
                                </a>
                            </div> -->
                            <!-- <div class="col-md-6">
                                <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityWithDeferment')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Lumpsum Investment Required For Target Monthly Annuity With Deferment Period</h3>
                                    </div>
                                </a>
                            </div> -->
                            <div class="col-md-6">
                                <a href="{{route('frontend.monthlyAnnuityForSIP')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>Monthly SWP For SIP</h3>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a href="{{route('frontend.sipRequiredForTargetMonthlyAnnuity')}}">
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>SIP Required For Target Monthly SWP</h3>
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
