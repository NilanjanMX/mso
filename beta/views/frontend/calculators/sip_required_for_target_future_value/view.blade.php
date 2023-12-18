@extends('layouts.frontend')
@section('js_after')
    <script>
        jQuery(document).ready(function(){
            jQuery('#save_cal_btn').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var title = jQuery('#save_title').val();
                if(title.trim()==''){
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').addClass('alert-danger');
                    jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
                }else{
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').html('');
                    jQuery('#save_title').val('');
                    jQuery.ajax({
                        url: "{{ route('frontend.sipRequiredForTargetFutureValue_save') }}",
                        method: 'get',
                        data: {
                            title: title
                        },
                        success: function(result){
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').addClass('alert-success');
                            jQuery('#save_cal_msg').html('Data Successfully Saved');
                            setTimeout(function () {
                                $('#saveOutput').modal('toggle');
                                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                                jQuery('#save_cal_msg').html('');
                            },500);
                            jQuery('.save_only').hide();
                            jQuery('.view_save_only').show();
                        }});
                }

            });
        });
    </script>
    @include('frontend.calculators.common.view_style')
    
@endsection
@section('content')
@php
$number_of_months = $period*12;
//rate1 = (1+Q10%)^(1/12)-1 (Q10 = senario 1)
$rate1_percent = pow((1+($interest1/100)), (1/12))-1;
//(Q7*AV33)/((1+AV33)*((1+AV33)^(AV32)-1))
$senario1_monthly_amount = ($amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),($number_of_months))-1));
//AV35*AV32
$senario1_amount = $senario1_monthly_amount*$number_of_months;

if (isset($interest2)){
    $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
    //(Q7*AV34)/((1+AV34)*((1+AV34)^(AV32)-1))
    $senario2_monthly_amount = ($amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),($number_of_months))-1));
    $senario2_amount = $senario2_monthly_amount*$number_of_months;;


}

if(isset($include_step_up) && $include_step_up=='yes'){
    //Step Up
//(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
$ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
//(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
$stepup_senario1_found_value = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($period))-pow((1+$step_up_rate/100),($period)));
//Q7/AV38
$stepup_senario1_amount =$amount / $stepup_senario1_found_value;
//(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
$stepup_senario1_invest_amount = ($stepup_senario1_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));

    if (isset($interest2)){
        //Step Up
    //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
    $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
    //(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
     $stepup_senario2_found_value = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),($period))-pow((1+$step_up_rate/100),($period)));
    //Q7/AV38
     $stepup_senario2_amount =$amount / $stepup_senario2_found_value;
     //(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
     $stepup_senario2_invest_amount = ($stepup_senario2_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));
    }
}
@endphp
<!-- <div class="banner bannerForAll container" style="padding-bottom: 0; margin-bottom: 15px;">
        <div id="main-banner" class="owl-carousel owl-theme">
            <div class="item">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="py-4">Premium Calculators</h2>
                        <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                        </p>
                        <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                        <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
                </div>
            </div>
        </div>
    </div> -->
    <div class="banner styleApril">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">SIP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                        <div class="roundBorderHolder">
                            
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SIP Period  </strong>
                                    </td>
                                    <td>
                                        {{$period?$period:0}} Years
                                    </td>
                                </tr>
                                @if(isset($include_step_up) && $include_step_up=='yes')
                                    <tr>
                                        <td >
                                            <strong> Step-Up % Every Year  </strong>
                                        </td>
                                        <td>
                                            {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                @endif
                                </tbody></table>
                        </div>
                            <h1 class="midheading">Monthly SIP Required</h1>
                            
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    @if(isset($interest2))
                                        @if(isset($include_step_up) && $include_step_up=='yes')
                                            <tr>
                                                <td><strong>Mode</strong></td>
                                                <td>
                                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                                <td>
                                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_monthly_amount)}} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario2_monthly_amount)}} </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario1_amount)}} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario2_amount)}} </strong>
                                                </td>
                                            </tr>
    
                                        @else
                                            <tr>
                                                <td>
                                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                                <td>
                                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    ₹ {{custome_money_format($senario1_monthly_amount)}}
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($senario2_monthly_amount)}}
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        @if(isset($include_step_up) && $include_step_up=='yes')
                                            <tr>
                                                <td><strong>Mode</strong></td>
                                                <td>
                                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_monthly_amount)}} </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario1_amount)}} </strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    ₹ {{custome_money_format($senario1_monthly_amount)}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                    </tbody></table>
                            </div>

                            <h1 class="midheading">Total Investment</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                    @if(isset($interest2))
                                        @if(isset($include_step_up) && $include_step_up=='yes')
                                            <tr>
                                                <td><strong>Mode</strong></td>
                                                <td>
                                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                                <td>
                                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario2_invest_amount)}} </strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                                <td>
                                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %<strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        @if(isset($include_step_up) && $include_step_up=='yes')
                                            <tr>
                                                <td><strong>Mode</strong></td>
                                                <td>
                                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            
                            <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                            
                            @if(isset($report) && $report=='detailed')
                                <h1 class="midheading">
                                    @if(isset($include_step_up) && $include_step_up=='yes')
                                        Normal SIP <br>
                                    @endif
                                    Year-Wise Projected Value</h1>
                                    <div class="roundBorderHolder">
                                        <table class="table table-bordered text-center">
                                            <tbody>
                                            @if(isset($interest2))
                                                <tr>
                                                    <th style="vertical-align: middle" rowspan="2">Year</th>
                                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                                </tr>
                                                <tr>
                                                    <th>Annual Investment</th>
                                                    <th>Year End Value</th>
                                                    <th>Annual Investment</th>
                                                    <th>Year End Value</th>
                                                </tr>
                                                @php
                                                    $previous_amount_int1 = $amount;
                                                    $previous_amount_int2 = $amount;
                                                @endphp
    
                                                @for($i=1;$i<=$period;$i++)
                                                    @php
                                                        //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                                                        $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                                        $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                                    @endphp
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>
                                                            ₹ {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                                                        </td>
                                                        <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                                        <td>
                                                            ₹ {{$senario2_monthly_amount?custome_money_format($senario2_monthly_amount*12):0}}
                                                        </td>
                                                        <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                                    </tr>
                                                @endfor
                                            @else
                                                <tr>
                                                    <th style="vertical-align: middle" rowspan="2">Year</th>
                                                    <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                </tr>
                                                <tr>
                                                    <th>Annual Investment</th>
                                                    <th>Year End Value</th>
                                                </tr>
                                                @php
                                                    $previous_amount_int1 = $amount;
                                                @endphp
    
                                                @for($i=1;$i<=$period;$i++)
                                                    @php
                                                        //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                                                        $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
    
                                                    @endphp
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>
                                                            ₹ {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                                                        </td>
                                                        <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                                    </tr>
                                                @endfor
                                        @endif
                                        </tbody>
                                    </table>
                                    </div>
                                    
                                    @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
                                        <h1 class="midheading">Step - Up SIP<br>Year-Wise Projected Value</h1>
                                        <div class="roundBorderHolder">
                                            <table class="table table-bordered text-center">
                                                <tbody>
                                                @if(isset($interest2))
                                                    <tr>
                                                        <th style="vertical-align: middle" rowspan="2">Year</th>
                                                        <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                        <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Annual Investment</th>
                                                        <th>Year End Value</th>
                                                        <th>Annual Investment</th>
                                                        <th>Year End Value</th>
                                                    </tr>
                                                    @php
                                                        //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                                                        $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                                                        $ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                                                        $previous_amount_int1 = $amount;
                                                        $previous_amount_int2 = $amount;
                                                        $stepup_senario1_change_amount = $stepup_senario1_amount;
                                                        $stepup_senario2_change_amount = $stepup_senario2_amount;
                                                    @endphp
    
                                                    @for($i=1;$i<=$period;$i++)
                                                        @php
    
                                                            if ($i==1){
                                                                $stepup_senario1_change_amount = $stepup_senario1_amount;
                                                                $stepup_senario2_change_amount = $stepup_senario2_amount;
                                                            }else{
                                                                $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                                                                $stepup_senario2_change_amount = $stepup_senario2_change_amount+($stepup_senario2_change_amount*$step_up_rate/100);
                                                            }
    
                                                            //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                                                            $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));
                                                            $previous_amount_int2 = ($ap2_stepup/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$i)-pow((1+$step_up_rate/100),$i));
    
                                                        @endphp
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>
                                                                ₹ {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                                                            </td>
                                                            <td>₹ {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
                                                            <td>
                                                                ₹ {{$stepup_senario2_change_amount?custome_money_format($stepup_senario2_change_amount*12):0}}
                                                            </td>
                                                            <td>₹ {{$previous_amount_int2?custome_money_format($previous_amount_int2):0}}</td>
                                                        </tr>
                                                    @endfor
                                                @else
                                                    <tr>
                                                        <th style="vertical-align: middle" rowspan="2">Year</th>
                                                        <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                    </tr>
                                                    <tr>
                                                        <th>Annual Investment</th>
                                                        <th>Year End Value</th>
                                                    </tr>
                                                    @php
                                                        //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                                                        $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                                                        //$ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                                                        $previous_amount_int1 = $amount;
                                                        //$previous_amount_int2 = $amount;
                                                        $stepup_senario1_change_amount = $stepup_senario1_amount;
                                                        //$stepup_senario2_change_amount = $stepup_senario2_amount;
                                                    @endphp
    
                                                    @for($i=1;$i<=$period;$i++)
                                                        @php
    
                                                            if ($i==1){
                                                                $stepup_senario1_change_amount = $stepup_senario1_amount;
                                                            }else{
                                                                $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                                                            }
    
                                                            //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                                                            $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));
    
                                                        @endphp
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>
                                                                ₹ {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                                                            </td>
                                                            <td>₹ {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
                                                        </tr>
                                                    @endfor
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if(isset($report) && $report=='detailed' && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                                        <h1 class="midheading">Cost of Delay in Starting Normal SIP</h1>
                                        @php
                                            $cost_delay_sip_amount1=0;
                                            $cost_delay_sip_amount2=0;
                                        @endphp
                                        <div class="roundBorderHolder">
                                            <table class="table table-bordered text-center">
                                                <tbody>
                                                @if(isset($interest2))
                                                    <tr>
                                                        <td colspan="5">
                                                            This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: middle" rowspan="2">Delay in No. of Year</th>
                                                        <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                        <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                                    </tr>
                                                    <tr>
                                                        <th>SIP Amount</th>
                                                        <th>Total Investment</th>
                                                        <th>SIP Amount</th>
                                                        <th>Total Investment</th>
                                                    </tr>
    
                                                        @for($i=1;$i<$period;$i++)
                                                            @php
                                                                $year_left = $period-$i;
                                                                //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                                                                $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                                                                $sipamount2 = ($amount*$rate2_percent) / ((1+$rate2_percent)*(pow((1+$rate2_percent),($year_left*12))-1));
                                                                $totalinvestment1 = $sipamount1*$year_left*12;
                                                                $totalinvestment2 = $sipamount2*$year_left*12;
                                                                if ($i==1){
                                                                    $cost_delay_sip_amount1=$sipamount1;
                                                                    $cost_delay_sip_amount2=$sipamount2;
                                                                }
                                                            @endphp
                                                            <tr>
                                                                <td>{{$i}}</td>
                                                                <td>
                                                                    ₹ {{$sipamount1?custome_money_format($sipamount1):0}}
                                                                </td>
                                                                <td>₹ {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
                                                                <td>
                                                                    ₹ {{$sipamount2?custome_money_format($sipamount2):0}}
                                                                </td>
                                                                <td>₹ {{$totalinvestment2?custome_money_format($totalinvestment2):0}}</td>
                                                            </tr>
                                                        @endfor
    
                                                @else
                                                    <tr>
                                                        <td colspan="4">
                                                            This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th style="vertical-align: middle" rowspan="2">Year</th>
                                                        <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                    </tr>
                                                    <tr>
                                                        <th>SIP Amount</th>
                                                        <th>Total Investment</th>
                                                    </tr>
    
                                                    @for($i=1;$i<$period;$i++)
                                                        @php
                                                            $year_left = $period-$i;
                                                            //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                                                            $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                                                            $totalinvestment1 = $sipamount1*$year_left*12;
                                                            if ($i==1){
                                                                    $cost_delay_sip_amount1=$sipamount1;
                                                                }
                                                        @endphp
                                                        <tr>
                                                            <td>{{$i}}</td>
                                                            <td>
                                                                ₹ {{$sipamount1?custome_money_format($sipamount1):0}}
                                                            </td>
                                                            <td>₹ {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
                                                        </tr>
                                                    @endfor
    
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                    @if(isset($interest2) && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                                        <p class="text-left">
                                            For example, If you delay your SIP by 1 year, your SIP amount will increase to ₹{{custome_money_format($cost_delay_sip_amount1)}} instead of ₹ {{custome_money_format($senario1_monthly_amount)}} in case of Scenario(1) and will increase to ₹{{custome_money_format($cost_delay_sip_amount2)}} instead of ₹ {{custome_money_format($senario2_monthly_amount)}} in case of Scenario(2).
                                        </p>
                                    @elseif(isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                                        <p class="text-left">
                                            For example, If you delay your SIP by 1 year, your SIP amount will increase to ₹{{custome_money_format($cost_delay_sip_amount1)}} instead of ₹ {{custome_money_format($senario1_monthly_amount)}}.
                                        </p>
                                    @endif
                                    
                                    <p style="text-align: left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                                    
                                    @if(isset($is_graph) && $is_graph)
                                        <h1 class="midheading">Graphic Representation</h1>
                                        @if(isset($pie_chart1) && $pie_chart1)
                                            <div style="text-align: center;" class="graphView">
                                                <img src="{{$pie_chart1}}" class="graphViewImg">
                                            </div>
                                        @endif
                                        @if(isset($pie_chart2) && $pie_chart2)
                                            <div style="text-align: center;margin-top:20px;" class="graphView">
                                                <img src="{{$pie_chart2}}" class="graphViewImg">
                                            </div>
                                        @endif
                                    @endif
                                    
                                    
                                @endif
                        
                        

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.sipRequiredForTargetFutureValue_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.sipRequiredForTargetFutureValue_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

    <div class="modal fade" id="mergeSalesPresentersOutput" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SALES PRESENTER SOFTCOPY SAVED LIST</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form target="_blank" action="{{route('frontend.sipRequiredForTargetFutureValue_merge_download')}}" method="get">
                        <input type="hidden" name="save_file_id" value="{{$id}}">
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>List Name</th>
                                <th>Valid Till</th>
                            </tr>
                            @if(isset($savelists) && count($savelists)>0)
                                @foreach($savelists as $svlist)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="saved_sp_list_id[]" value="{{$svlist['id']}}">
                                        </td>
                                        <td>{{$svlist['created_at']->format('d/m/Y - h:i A')}}</td>
                                        <td>{{$svlist['title']}} ({{$svlist->softcopies->count()}} images)</td>
                                        <td>{{date('d/m/Y - h:i A',strtotime($svlist['validate_at']))}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">SUGGESTED PRESENTATION LIST</h5>
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th style="text-align: left">List Name</th>
                            </tr>
                            @if(isset($suggestedlists) && count($suggestedlists)>0)
                                @foreach($suggestedlists as $sglist)
                                    <tr>
                                        <td>
                                            <input type="radio" name="saved_list_id" value="{{$sglist['id']}}">
                                        </td>
                                        <td style="text-align: left" >{{$sglist['title']}} ({{$sglist->softcopies->count()}} images)</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">WHERE YOU WANT TO MERGE?</h5>
                        <table>
                            <tbody>
                            <tr>
                                <td style="text-align: left">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="before" name="mergeposition">Before
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="after" name="mergeposition" checked>After
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @if($permission['is_cover'])
                            <h5 class="modal-title">&nbsp;</h5>
                            <table>
                                <tbody>
                                <tr>
                                    <td style="text-align: left">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="is_cover" onchange="changeCover(1);">With Cover
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="is_cover"  onchange="changeCover(0);" checked>Without Cover
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                        <h5 class="modal-title">&nbsp;</h5>
                        <div id="pdf_title_line_id" style="display: none;">
                            <div class="form-group">
                                <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_name" class="form-control" id="client_name" placeholder="Client Name" value="" maxlength="22">
                            </div>
                        </div>
                        <p></p>
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary btn-round" >Merge & Download</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

