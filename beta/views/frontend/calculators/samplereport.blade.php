@extends('layouts.frontend')

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">SAMPLE REPORTS</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">


                <div class="col-md-12">
                    <div class="rt-pnl">
                        <h2 class="headline">{{$calculator_detail->name}} - Sample Reports</h2>
                        <div class="rt-btn-prt">
                            <a href="{{route('frontend.how-to-use-calculator')}}">How to Use</a>
                        </div>
                        <div class="row mar-top">

                        @if($samplereports)
                            @foreach($samplereports as $samplereport)
                                <div class="col-md-4">
                                <a href="{{url('samplereports-view/')}}/{{$samplereport['sample_pdf']}}" target="_blank">
                                    
                                    <div class="calculator-bx">
                                        <div class="cal-ico">
                                            <img class="img-fluid" src="{{asset('')}}/images/icon3.png" alt="">
                                            <span><img class="img-fluid" src="{{asset('')}}/images/icon3-h.png" alt=""></span>
                                        </div>
                                        <h3>{{$samplereport->title}}</h3>
                                    </div>
                                    </a>
                                </div>
                            @endforeach
                        @endif


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
