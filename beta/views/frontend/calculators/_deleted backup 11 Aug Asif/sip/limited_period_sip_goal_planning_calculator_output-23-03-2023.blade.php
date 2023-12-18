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
                        url: "{{ route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputSave') }}",
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
        $total_number_of_months = $sip_period*12;

        //(1+Q11%)^(1/12)-1
        $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
        //Q8/((1+Q12%)^Q10)
        $senario1_fund_amount = $amount/(pow((1+$interest1/100),$deferment_period)) ;
        //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
        $senario1_monthly_sip_amount = ($senario1_fund_amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),$total_number_of_months)-1));
        //AV36*AV31
        $senario1_totalinvestment = $senario1_monthly_sip_amount*$total_number_of_months;

        $senario2_amount = 0;
         if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
            //Q8/((1+Q12%)^Q10)
            $senario2_fund_amount = $amount/(pow((1+$interest2/100),$deferment_period)) ;
            //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
            $senario2_monthly_sip_amount = ($senario2_fund_amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),$total_number_of_months)-1));
            //AV36*AV31
            $senario2_totalinvestment = $senario2_monthly_sip_amount*$total_number_of_months;
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
                        <a href="{{route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Limited Period SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
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
                                <strong>SIP Period</strong>
                            </td>
                            <td>
                                {{$sip_period?$sip_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Deferment Period </strong>
                            </td>
                            <td>
                                {{$deferment_period?$deferment_period:0}} Years
                            </td>
                        </tr>

                        </tbody></table>
                    <h5 class="text-center">Monthly SIP Required</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
                            <tr>
                                <th>
                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </th>
                                <th>
                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($senario1_monthly_sip_amount)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($senario2_monthly_sip_amount)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th>
                                    @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                </th>
                                <td>
                                    <strong> ₹ {{custome_money_format($senario1_monthly_sip_amount)}}</strong>
                                </td>
                            </tr>
                        @endif
                        </tbody></table>

                    <h5 class="text-center">Total Investment</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
                            <tr>
                                <th>
                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </th>
                                <th>
                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <strong> ₹ {{custome_money_format($senario1_totalinvestment)}}</strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($senario2_totalinvestment)}}</strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th>
                                     @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($senario1_totalinvestment)}}</strong>
                                </td>
                            </tr>
                        @endif
                        </tbody></table>
                    @if(isset($report) && $report=='detailed')
                        <h5 class="text-center">Year-Wise Projected Value</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @if(isset($interest2))
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle">Year</th>
                                    <th colspan="2">
                                        Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </th>
                                    <th colspan="2">
                                        Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                    </th>
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
                                @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                    @php

                                        if ($sip_period>=$i){
                                          //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                          $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                        }else{
                                         //(BF70*(1+BD71)^12)
                                          $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                        }
                                        if ($sip_period>=$i){
                                          $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_sip_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                        }else{
                                         //(BE69*(1+BC70)^12)
                                          $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            @if($i<=$sip_period)
                                                ₹ {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                        <td>
                                            @if($i<=$sip_period)
                                                ₹ {{$senario2_monthly_sip_amount?custome_money_format($senario2_monthly_sip_amount*12):0}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle">Year</th>
                                    <th colspan="2">
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </th>
                                </tr>
                                <tr>
                                    <th>Annual Investment</th>
                                    <th>Year End Value</th>
                                </tr>
                                @php
                                    $previous_amount_int1 = $amount;
                                @endphp
                                @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                    @php

                                        if ($sip_period>=$i){
                                          //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                          $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                        }else{
                                         //(BF70*(1+BD71)^12)
                                          $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            @if($i<=$sip_period)
                                                ₹ {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>₹ {{custome_money_format($previous_amount_int1)}}</td>

                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        <p>*The above chart is approximate and for illustration purpose only</p>
                    @endif

                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
