@extends('layouts.frontend')

@section('content')
@yield('css_before')
<link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">

<style>
    .banner {
        padding-top: 131px;
        padding-bottom: 88px;
        border-bottom: 1px solid #ccc;
        margin-bottom: 53px;
    }
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
    left: -43px;
    top: 187px;
    width: 100px;
    }
    .vidpos04 {
        left: -53px;
        top: 1628px;
    }
    .vidpos03 {
        right: 0;
        left: -25px;
        top: 2052px;
        width: 100px;
    }
    .vidpos05 {
        right: -51px;
        top: 1230px;
        width: 120px;
    }
    /*.vidpos06 {*/
    /*    right: -65px;*/
    /*    top: 530px;*/
    /*    width: 150px;*/
    /*}*/
    .visp {
        right: -30px;
        top: 1030px;
        width: 790px;
    }
</style>
<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->
    <div class="banner container pb-0">
        <div id="main-banner" class="owl-carousel owl-theme">
            <div class="item">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="pt-4 pb-4">Calculators CUM Client Proposals</h2>
                        <p>A good proposal has a higher chance of getting converted into sales. Create customised investment proposals for your clients & prospects.</p>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/calculatorbanner.png" alt="" /></div>
                </div>
            </div>
        </div>
    </div>
    
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
            </div>
            <div class="allBlogTogether allCalcBody">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="subHeadding">Lumpsum</h3>
                    </div>
                    <div class="col-md-6">
                        <div class="salesPresentersTopBtn mt-3">
                            <!--<a href="{{route('frontend.samplereports')}}" target="_blank" class="btn banner-btn float-right">Sample Reports</a>-->
                            <!--<a href="{{route('frontend.how-to-use-calculator')}}" target="_blank" class="btn banner-btn float-right mr-3">How to Use</a>-->
                            <a href="{{url('calculators/all')}}" class="btn banner-btn float-right">Sample Reports</a>
                            <a href="{{url('calculators/all')}}" class="btn banner-btn float-right mr-3">How to Use</a>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>
                <div class="row webinarsCardAll">
                    <div class="col-md-12">
                        
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="blog-bx blogSection calculatorSection">
                                    <div class="blog-img">
                                        <img class="img-fluid" src="{{asset('')}}img/calculatorIcon.png" alt="" />
                                    </div>
                                    <div class="blog-txt">
                                        <h3>Future Value of Lump sum Investment</h3>
                                        <p>Calculate the expected value in future for a lump sum investment made today.</p>
                                        <div class="calculatorBtn">
                                            <a href="{{route('frontend.futureValueOfLumpsumInvestment')}}" class="btn banner-btn whitebg">Calculate Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="blog-bx blogSection calculatorSection">
                                    <div class="blog-img">
                                        <img class="img-fluid" src="{{asset('')}}img/calculatorIcon.png" alt="" />
                                    </div>
                                    <div class="blog-txt">
                                        <h3>Lumsum Investment Required for Target Future Value</h3>
                                        <p>Calculate the lump sum investment required today to achieve a future expected value.</p>
                                        <div class="calculatorBtn">
                                            <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetFutureValue')}}" class="btn banner-btn whitebg">Calculate Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="blog-bx blogSection calculatorSection">
                                    <div class="blog-img">
                                        <img class="img-fluid" src="{{asset('')}}img/calculatorIcon.png" alt="" />
                                    </div>
                                    <div class="blog-txt">
                                        <h3>Lumpsum Investment Ready Reckoner</h3>
                                        <p>This calculator gives future value ready reckoner table for Lump Sum investment made. You can choose upto 3 range of expected returns and 5 range of investment period.</p>
                                        <div class="calculatorBtn">
                                            <a href="{{route('frontend.oneTimeInvestmentReadyReckoner')}}" class="btn banner-btn whitebg">Calculate Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="blog-bx blogSection calculatorSection">
                                    <div class="blog-img">
                                        <img class="img-fluid" src="{{asset('')}}img/calculatorIcon.png" alt="" />
                                    </div>
                                    <div class="blog-txt">
                                        <h3>Lumpsum Investment Goal Planning Ready Reckoner</h3>
                                        <p>This calculator gives a ready reckoner table of how much Lump sum investment is required to achieve a future goal. You can choose upto 3 range of expected returns and 5 range of investment period.</p>
                                        <div class="calculatorBtn">
                                            <a href="{{route('frontend.oneTimeInvestmentGoalPlanningReadyReckoner')}}" class="btn banner-btn whitebg">Calculate Now</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
