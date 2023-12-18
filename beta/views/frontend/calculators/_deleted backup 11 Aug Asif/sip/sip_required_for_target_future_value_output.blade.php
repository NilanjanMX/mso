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
                        url: "{{ route('frontend.sipRequiredForFutureValueOutputSave') }}",
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
@endsection
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
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>

                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.sipRequiredForFutureValueOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <h5 class="mb-3 text-center">SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
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
                    <h5 class="text-center">Monthly SIP Required</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
                            @if(isset($include_step_up) && $include_step_up=='yes')
                                <tr>
                                    <th><strong>Mode</strong></th>
                                    <th>
                                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
                                    <th>
                                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                    </th>
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
                                    <th>
                                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
                                    <th>
                                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                    </th>
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
                                    <th><strong>Mode</strong></th>
                                    <th>
                                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
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
                                    <th>
                                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
                                </tr>
                                <tr>
                                    <td>
                                        ₹ {{custome_money_format($senario1_monthly_amount)}}
                                    </td>
                                </tr>
                            @endif
                        @endif
                        </tbody></table>

                    <h5 class="text-center">Total Investment</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
                            @if(isset($include_step_up) && $include_step_up=='yes')
                                <tr>
                                    <th><strong>Mode</strong></th>
                                    <th>
                                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
                                    <th>
                                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                    </th>
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
                                    <th>
                                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
                                    <th>
                                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %<strong>
                                    </th>
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
                                    <th><strong>Mode</strong></th>
                                    <th>
                                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
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
                                    <th>
                                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                    </th>
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

                    @if(isset($report) && $report=='detailed')
                    <h5 class="text-center">
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            Normal SIP <br>
                        @endif
                        Year-Wise Projected Value</h5>

                        <table class="table table-bordered text-center" style="background: #fff;">
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
                        @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
                            <h5 class="text-center">Step - Up SIP<br>Year-Wise Projected Value</h5>
                            <table class="table table-bordered text-center" style="background: #fff;">
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
                        @endif
                        @if(isset($report) && $report=='detailed' && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                            <h5 class="text-center">Cost of Delay in Starting Normal SIP</h5>
                            @php
                                $cost_delay_sip_amount1=0;
                                $cost_delay_sip_amount2=0;
                            @endphp
                            <table class="table table-bordered text-center" style="background: #fff;">
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
                        @endif
                        @if(isset($interest2) && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                            <p style="text-align: left">
                                For example, If you delay your SIP by 1 year, your SIP amount will increase to ₹{{custome_money_format($cost_delay_sip_amount1)}} instead of ₹ {{custome_money_format($senario1_monthly_amount)}} in case of Scenario(1) and will increase to ₹{{custome_money_format($cost_delay_sip_amount2)}} instead of ₹ {{custome_money_format($senario2_monthly_amount)}} in case of Scenario(2).
                            </p>
                        @elseif(isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                            <p style="text-align: left">
                                For example, If you delay your SIP by 1 year, your SIP amount will increase to ₹{{custome_money_format($cost_delay_sip_amount1)}} instead of ₹ {{custome_money_format($senario1_monthly_amount)}}.
                            </p>
                        @endif
                        <p style="text-align: left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.sipRequiredForFutureValueOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>
    @include('frontend.calculators.modal')

@endsection
