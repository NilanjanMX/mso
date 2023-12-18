@extends('layouts.frontend')
<style>
    .knoch {
        position: absolute;
        right: 26px;
        top: 15px;
    }
    .sreports {
        position: absolute;
        top: 0px;
        left: -4px;
    }
    .blog-bx.blogSection.calculatorSection .blog-txt h3 {
        height: 72px;
        margin-top: -8px;
    }
    .blog-bx.blogSection.calculatorSection .blog-txt h3 .premiumSetup {
        position: relative;
        display: inline-block;
        padding-top: 18px;
    }
</style>
@section('content')
@yield('css_before')
<link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">

<style>
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
    <div class="banner bannerForAll container pb-0">
        <div id="main-banner" class="owl-carousel owl-theme">
            <div class="item">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="py-4">Calculators CUM Client Proposals</h2>
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
                            <h3 class="subHeadding">{{$category_detail->description}}</h3>
                        </div>
                        <div class="col-md-6">
                            <div class="salesPresentersTopBtn mt-3">
                                <!--<a href="{{route('frontend.calculatorSampleReport',['type'=>'category','id'=>$category_id])}}" target="_blank" class="btn banner-btn float-right">Sample Reports</a>-->
                                <!--<a href="{{route('frontend.how-to-use-calculator')}}" target="_blank" class="btn banner-btn float-right mr-3">How to Use</a>-->
                                <a href="{{url('calculators/all')}}" class="btn banner-btn float-right">Sample Reports</a>
                                <a href="{{url('calculators/all')}}" class="btn banner-btn float-right mr-3">How to Use</a>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row webinarsCardAll calcBodyWithMargin">
                        <div class="col-md-12">
                            <div class="row">
                                @foreach($calculator_list as $key => $value)
                                    <div class="col-md-6">
                                        <div class="blog-bx blogSection calculatorSection">
                                            <div class="blog-img">
                                                <img class="img-fluid" src="{{asset('')}}img/calculatorIcon.png" alt="" />
                                            </div>
                                            <div class="blog-txt">
                                                <h3><div class="premiumSetup">{{$value->name}}
                                                @if($value->type == "Premium")
                                                    <a href="#" target="_blank" class="sreports">
                                                        <img class="img-fluid" src="{{asset('')}}/img/crown_icon.png" alt="">
                                                    </a>
                                                @endif</div></h3>
                                                <!--<a class="knoch" href="{{route('frontend.calculatorSampleReport',['type'=>'calculator','id'=>$value->calculator_id])}}" target="_blank">-->
                                                <!--    <img class="img-fluid" src="{{asset('')}}/img/sample_report_1.png" alt="">-->
                                                <!--</a>-->
                                                
                                                <!--<p>Suspendisse auctor diam, amet tincidunt tempus, morbi id et. Tincidunt dolor sapien pellentesque suspendisse sem malesuada suspendisse. Nunc massa, mattis pulvinar rhoncus faucibus dolor.</p>-->
                                                <div class="calculatorBtn">
                                                    <a class="btn banner-btn whitebg" 
                                                    href="@if($value->is_view) {{url('')}}/{{$value->url}} @else javascript:void(0); @endif" @if(Auth::user()) @if(!$value->is_view) onclick="openViewPermissionModal()"; @endif  @else onclick="openLoginPermissionModal()"; @endif>
                                                    Calculate Now</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

    @include('frontend.calculators.modal')

@endsection
