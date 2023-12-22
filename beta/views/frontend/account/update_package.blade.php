@extends('layouts.frontend')

@section('content')

    <style>
        .btn-payNow, .btn-payNow:hover {
            padding: 11px 38px;
        }
        /*.newsletter {*/
        /*    margin-bottom:0;*/
        /*}*/
        .visp {
            right: 0;
            top: -69px;
            width: 640px;
        }
        .visp02 {
            left: 0;
            top: 532px;
        }
        
        .vidpos04 {
            left: -50px;
            top: 1564px;
            width: 100px;
        }
        .vidpos03 {
            right: -46px;
            top: 470px;
                }
        .vidpos02 {
            left: -46px;
            top: 187px;
            width: 100px;
        }
        .vidpos06 {
            left: -55px;
            top: 1353px;
            width: 120px;
        }
        .vidpos05 {
            right: -71px;
            top: 1571px;
            width: 150px;
        }
        .headPlan {
            font-family: 'Mulish';
            font-style: normal;
            font-weight: 800;
            font-size: 28px;
            line-height: 35px;
            text-align: center;
            color: #000000;
        }
        .newplanPrice {
            font-family: 'Mulish';
            font-style: normal;
            font-weight: 800;
            font-size: 36px;
            line-height: 46px;
            text-align: center;
            color: #4089ED;
            display: block;
        }
        .oldplanPrice {
            font-family: 'Mulish';
            font-style: normal;
            font-weight: 700;
            font-size: 24px;
            line-height: 30px;
            text-align: center;
            text-decoration-line: line-through;
            color: #CACACA;
            display: block;
        }
        .plan_topbtn {
            height: 46px;
        }
        .topplan0 , .topplan1, .topplan2 {
            padding-top: 10px;
            margin-top: 10px;
        }
        .topplan2 {
            padding-bottom: 4px;
        }
        .gstText {
            margin-bottom: 0;
        }
        .forClass {
            font-family: 'Mulish';
            font-style: normal;
            font-weight: 700;
            font-size: 30px;
            line-height: 46px;
            color: #003484;
        }
        .fordays {
            font-family: 'Mulish';
            font-style: normal;
            font-weight: 400;
            font-size: 30px;
            line-height: 46px;
            color: #003484;
        }
        .btn-payNow, .btn-payNow:hover {
            border-radius: 10px;
        }
        .allplnsb2 .btn-payNow {
            background: #003484;
        }
        .btn-payNow {
            width:auto;
        }
        .topplan1 .planPrice {
            margin-bottom:3px;
        }
        @media (max-width: 1199px) {
            .headPlan {
                font-size: 24px;
            }
        }
        @media (max-width: 991px) {
            .headPlan {
              font-size: 18px;
            }
            .oldplanPrice {
                font-size: 20px;
            }
            .newplanPrice {
                font-size: 30px;
                line-height: normal;
            }
        }
        /*@media (max-width: 776px) {*/
        @media (max-width: 767px) {
            .planHeading {
                font-size: 13px;
                line-height: 16px;
            }
            .planPrice {
                font-size: 17px !important;
                line-height: 18px;
                min-height: 50px;
                /*display: flex;*/
                /*align-items: center;*/
                /*justify-content: center;*/
            }
            .gstText {
                height: 43px;
            }
            .approvePlan {
                font-size: 13px;
                height: auto;
                line-height: 15px;
                position: relative;
            }
            .btn-payNow, .btn-payNow:hover {
                width: 97%;
                font-size: 10px;
                padding: 11px 0px;
            }
            .planTooltip {
                position: absolute;
                left: -14px;
                top: 16px;
                font-size: 13px;
            }
            .countPlanText {
                font-size: 12px;
                line-height: 14px;
            }
            .planHeading {
                margin-bottom: 1px;
                margin-top: 22px;
            }
            /*.gstText {*/
            /*    margin-bottom: 14px;*/
            /*}*/
            .headPlan {
              font-size: 14px;
            }
            .oldplanPrice {
              font-size: 18px;
            }
            .newplanPrice {
                font-size: 26px;
            }
            .planAsk {
                width: 13px;
                margin-left: 0px;
            }
        }
        @media (max-width: 575px) {
            .headPlan {
              line-height: 20px;
              height: 89px;
            }
        }
        @media (max-width: 489px) {
            .oldplanPrice {
              font-size: 16px;
            }
            .newplanPrice {
              font-size: 20px;
            }
        }
        @media (max-width: 413px) {
            .oldplanPrice {
              font-size: 14px;
            }
            .newplanPrice {
              font-size: 16px;
            }
        }
    </style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">{{isset($page['title'])?$page['title']:''}}</h2>
                </div>
            </div>
        </div>
        <a href="#" class="btn-chat">Chat With Us</a>
    </div>


    <section class="main-sec" style="padding-bottom: 55px;">
        <div class="container pt-5 mt-5" style="border-top: 2px solid #aeabb4;">
            <div class="row">
                <div class="col-md-12">     
                    <div class="stage">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card planCard pb-5">
                                        <div class="row">
    
                                            <div class="col-3">
                                                <div class=""></div>
                                            </div>
                                            <?php foreach ($package_list as $key => $value) { ?>
                                                <div class="col-3 planCol">
                                                     <div class="headPlan"> <div class="plan_topbtn"></div> {{$value->name}}</div>
                                                <div class="topplan<?php echo $key;?>">
                                                    <?php if($value->price){ ?>
                                                        <?php if($value->discount_price){ ?>
                                                            <div class="planPrice">
                                                                <span class="oldplanPrice">₹{{custome_money_format($value->price)}}</span>
                                                                <span class="newplanPrice">₹{{custome_money_format($value->price-$value->discount_price)}}</span>
                                                            </div>
                                                        <?php } else { ?>
                                                            <div class="planPrice">
                                                                <span class="newplanPrice">₹{{custome_money_format($value->price)}}</span>
                                                            </div>
                                                        <?php } ?>
                                                    <?php } else{ ?>
                                                    <?php } ?>
                                                    
                                                    <div class="gstText">
                                                        <?php if($value->price){ ?>
                                                            Per Year ( + 18% GST)
                                                        <?php } ?>
                                                    </div>
    
                                                </div>
    
                                                </div>
                                            <?php } ?>
                                        </div>
                                        
                                        
    
                                        <div class="activePlanList" style="">
                                            <div class="row">
                                                <div class="col-3 hplan">
                                                    <div class="approvePlan" style="">
                                                        <?php if($hint->add_on_price_hint){ ?>
                                                            <div class="planTooltip">
                                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->add_on_price_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>
                                                            <div> Add-On Price </div>
                                                            <!-- <div style="font-size: 10px;color: #9E9E9E;">
                                                                Per Year ( + 18% GST)
                                                            </div> -->
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                    <div class="approvePlan" style="">
                                                        <?php if($hint->total_amount_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->total_amount_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>
                                                            <div> Total Amount </div>
                                                            <!-- <div style="font-size: 10px;color: #9E9E9E;">
                                                                Per Year ( + 18% GST)
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="approvePlan" style="background: #85c9f0;">
                                                        
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext" style="width: 200px;">
                                                                    Credit of Existing membership<br>
                                                                    Days Used : {{$days_used}}<br>
                                                                    Price Paid : {{$price_paid}}<br>
                                                                    Balance Period : {{$balance_period}}<br>
                                                                    Balance Amount : {{$balance_amount}}<br>
                                                                </span>
                                                            </div>
                                                        
                                                        <div>
                                                            <div> Total Payable </div>
                                                            <div style="font-size: 10px;color: #9E9E9E;">
                                                                Per Year ( + 18% GST)
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="approvePlan">
                                                        <?php if($hint->number_of_user_hint){ ?>
                                                            <div class="planTooltip">
                                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->number_of_user_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>No of User </div> 
                                                    </div>
                                                    <div class="approvePlan">
                                                        <?php if($hint->sales_presenters_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->sales_presenters_hint}}</span>
                                                            </div>
                                                        <?php } ?> 
                                                        <div>Sales Presenters</div> 
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->marketing_banners_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->marketing_banners_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Marketing Banners </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->marketing_videos_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->marketing_videos_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Marketing Video </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->premade_sales_presenter_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->premade_sales_presenter_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Pre-made Sales Presenter </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->trail_calculator_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->trail_calculator_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Trail Calculators </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->client_proposals_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->client_proposals_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Clients Proposals </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->investment_suitability_profiler_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->investment_suitability_profiler_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Investment Suitablity Profiler </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->scanner_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->scanner_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>MF Research </div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->famous_quotes_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->famous_quotes_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Other Downloads</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->premium_calculator_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->premium_calculator_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Premium Calculator</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->welcome_letter_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->welcome_letter_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Welcome Letter</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->ready_made_portfolio_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->ready_made_portfolio_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>MSO Readymade Portfolio</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->client_communication_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->client_communication_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>Client Communication</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->mso_trigger_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->mso_trigger_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>MSO Triggers</div>
                                                    </div>

                                                    <div class="approvePlan">
                                                        <?php if($hint->model_portfolio_hint){ ?>
                                                            <div class="planTooltip">
                                                                <i class="fa fa-info-circle" aria-hidden="true"></i>
                                                                <span class="tooltiptext">{{$hint->model_portfolio_hint}}</span>
                                                            </div>
                                                        <?php } ?>
                                                        <div>MSO Model Portfolio</div>
                                                    </div>
                                                </div>
                                                <?php foreach ($package_list as $key => $value) { 
                                                    $total_amount = ($value->price - $value->discount_price) + ($value->price_per_user * ($user->number_user -1));
                                                    ?>
                                                    <div class="col-3 planCol allplnsb<?php echo $key;  ?>" style="border-bottom:0;">
                                                        <form action="{{route('frontend.membership_update_package')}}" method="get">
                                                            <input type="hidden" name="package_id" value="{{$value->id}}">
                                                            <div class="countPlanText">
                                                                <?php if($value->price_per_user){ ?>₹{{number_format((float)$value->price_per_user, 2, '.', '')}}
                                                                <?php } else{ ?>
                                                                    -
                                                                <?php } ?>
                                                            </div>
                                                            <div class="countPlanText" id="total_price_{{$key}}">
                                                                <?php if($value->price){ ?>
                                                                    ₹{{number_format((float)$total_amount, 2, '.', '')}}
                                                                <?php } else{ ?>
                                                                    -
                                                                <?php } ?>
                                                            </div>
                                                            <div class="countPlanText" id="total_price_{{$key}}" style="background: #85c9f0; color: #fff;">
                                                                <?php if($value->price){ ?>
                                                                    ₹{{number_format((float) ($total_amount - $old_amount), 2, '.', '')}}
                                                                <?php } else{ ?>
                                                                    -
                                                                <?php } ?>
                                                            </div>
                                                            <div class="countPlan">
                                                                <span class="countNumberPlan" id="number_{{$key}}" name="number_{{$key}}">
                                                                    {{$user->number_user}}
                                                                </span>
                                                                <input type="hidden" name="user_number" value="" id="user_number_{{$key}}">
                                                            </div>
                                                            <?php if($value->sales_presenters == 1){ ?>
                                                                <?php if($value->sales_presenters_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->sales_presenters_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanText">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->marketing_banners == 1){ ?>
                                                                <?php if($value->marketing_banners_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->marketing_banners_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanText">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->marketing_videos == 1){ ?>
                                                                <?php if($value->marketing_videos_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->marketing_videos_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->pre_made_sales_presenters == 1){ ?>
                                                                <?php if($value->premade_sales_presenter_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->premade_sales_presenter_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->trail_calculator == 1){ ?>
                                                                <?php if($value->trail_calculator_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->trail_calculator_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->client_proposals == 1){ ?>
                                                                <?php if($value->client_proposals_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->client_proposals_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->investment_suitability_profiler == 1){ ?>
                                                                <?php if($value->investment_suitability_profiler_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->investment_suitability_profiler_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            
                                                            <?php if($value->scanner == 1){ ?>
                                                                <?php if($value->scanner_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->scanner_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->famous_quotes == 1){ ?>
                                                                <?php if($value->famous_quotes_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->famous_quotes_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->premium_calculator == 1){ ?>
                                                                <?php if($value->premium_calculator_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->premium_calculator_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->welcome_letter == 1){ ?>
                                                                <?php if($value->welcome_letter_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->welcome_letter_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->ready_made_portfolio == 1){ ?>
                                                                <?php if($value->ready_made_portfolio_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->ready_made_portfolio_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            <?php if($value->client_communication == 1){ ?>
                                                                <?php if($value->client_communication_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->client_communication_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>


                                                            <?php if($value->mso_trigger == 1){ ?>
                                                                <?php if($value->mso_trigger_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->mso_trigger_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>


                                                            <?php if($value->model_portfolio == 1){ ?>
                                                                <?php if($value->model_portfolio_text) { ?>
                                                                    <div class="countPlanText">
                                                                        {{$value->model_portfolio_text}}
                                                                    </div>
                                                                <?php } else { ?>
                                                                    <div class="countPlanTick">
                                                                        <img src="{{asset('img/check.png')}}" class="img-fluid" alt="">
                                                                    </div>
                                                                <?php } ?>
                                                            <?php }else{ ?>
                                                                <div class="countPlanTick">
                                                                    <img src="{{asset('img/cross-mark.png')}}" class="img-fluid" alt="">
                                                                </div>
                                                            <?php } ?>
                                                            
                                                            <div class="payNowBtn">
                                                                <?php if($current_package->price < $value->price){ ?>
                                                                    <!--<div class="payNowBtn">-->
                                                                    <!--    <button type="submit" class="btn btn-block btn-payNow">-->
                                                                    <!--        Upgrade-->
                                                                    <!--    </button>-->
                                                                    <!--</div>-->
                                                                    <button type="submit" class="btn btn-block btn-payNow">
                                                                        Upgrade
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </form>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        
                                    </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>


    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js" integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function() {
            setTimeout(function(){ 
            jQuery(".hplan div.approvePlan").each(function(index) {
                var height01 = jQuery(".hplan div.approvePlan:eq("+index+")").height();
                var height02 = jQuery(".allplnsb0 form div:eq("+index+")").height();
                var height03 = jQuery(".allplnsb1 form div:eq("+index+")").height();
                var height04 = jQuery(".allplnsb2 form div:eq("+index+")").height();
                var maxVal = Math.max(height01, height02, height03, height04);
                console.log(maxVal);
                jQuery(".hplan div.approvePlan:eq("+index+")").css('height', maxVal);
                jQuery(".allplnsb0 form div:eq("+index+")").css('height', maxVal);
                jQuery(".allplnsb1 form div:eq("+index+")").css('height', maxVal);
                jQuery(".allplnsb2 form div:eq("+index+")").css('height', maxVal);
            });
            }, 3000);
        });
    </script>
@endsection
