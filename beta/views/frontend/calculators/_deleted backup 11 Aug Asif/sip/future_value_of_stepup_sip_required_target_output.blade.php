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
                        url: "{{ route('frontend.futureValueOfStepUpSIPRequiredTargetOutputSave') }}",
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

        //rate2 = (1+Q11%)^(1/12)-1 (Q10 = senario 2)
        $number_of_months = $period*12;
        $rate1_percent = pow((1+($interest1/100)), (1/12))-1;
         //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
        $ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
        //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
        $senario1_stepup_fund_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
        //Q9/AV38
        $senario1_stepup_monthly_amount = $amount / $senario1_stepup_fund_amount;
        //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
        $senario1_total_investment_amount = ($senario1_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

        //senario1_amount = (AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
        //(AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
       $senario1_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
        if (isset($interest2)){
            $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
             //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
            $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
            //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
            $senario2_stepup_fund_amount = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$period)-pow((1+$annual_increment/100),$period));
            //Q9/AV38
            $senario2_stepup_monthly_amount = $amount / $senario2_stepup_fund_amount;
            //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
            $senario2_total_investment_amount = ($senario2_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

        }

    //(Q8*12)*(((1+Q9%)^(Q10)-1)/((1+Q9%)-1))
    $total_investment = ($amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1))

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
                        <a href="{{route('frontend.futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Step-Up SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
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
                        @if(isset($annual_increment) && $annual_increment!='')
                            <tr>
                                <td>
                                    <strong> Step-Up % Every Year  </strong>
                                </td>
                                <td>
                                    {{$annual_increment?number_format($annual_increment, 2, '.', ''):0}} %
                                </td>
                            </tr>
                        @endif
                        @if(!isset($interest2))
                            <tr>
                                <td >
                                    <strong>Assumed Rate of Return </strong>
                                </td>
                                <td>
                                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </td>
                            </tr>
                        @endif

                        </tbody></table>

                        <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Monthly SIP Required</h1>
                        <table class="table table-bordered text-center">
                            <tbody>
                            @if(isset($interest2))

                                <tr>
                                    <td>
                                        Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                    <td>
                                        Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario1_stepup_monthly_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_stepup_monthly_amount)}} </strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($senario1_stepup_monthly_amount)}}
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Total Investment</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))

                            <tr>
                                <td>
                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($senario1_total_investment_amount)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($senario2_total_investment_amount)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                </td>
                                <td>
                                    ₹ {{custome_money_format($senario1_total_investment_amount)}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    @if(isset($report) && $report=='detailed')
                    <h5 class="text-center">
                        Year-Wise Projected Value</h5>
                            <table class="table table-bordered text-center" style="background: #fff;">
                                <tbody>
                                @if(isset($interest2))
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">Year</th>
                                        <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $senario1_stepup_monthly_amount;
                                        $previous_amount_int2 = $senario2_stepup_monthly_amount;
                                        $change_amount = $senario1_stepup_monthly_amount;
                                        $change_amount2 = $senario2_stepup_monthly_amount;
                                    @endphp

                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                            $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                                            //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                            $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                                            //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                            if ($i==1){
                                                $change_amount = $senario1_stepup_monthly_amount;
                                            }else{
                                                $change_amount = $change_amount+($change_amount*$annual_increment/100);
                                            }

                                        //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                            $ap2 = (1+$rate2_percent)*$senario2_stepup_monthly_amount*((pow((1+$rate2_percent),12)-1)/$rate2_percent);
                                            //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                            $previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$i)-pow((1+$annual_increment/100),$i));
                                            //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                            if ($i==1){
                                                $change_amount2 = $senario2_stepup_monthly_amount;
                                            }else{
                                                $change_amount2 = $change_amount2+($change_amount2*$annual_increment/100);
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                            </td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                            <td>
                                                ₹ {{$change_amount2?custome_money_format($change_amount2):0}}
                                            </td>
                                            <td>
                                                ₹ {{$change_amount2?custome_money_format($change_amount2*12):0}}
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <th rowspan="2">Year</th>
                                        <th colspan="3"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $senario1_stepup_monthly_amount;
                                        $change_amount = $senario1_stepup_monthly_amount;
                                    @endphp

                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                            $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                                            //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                            $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                                            //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                            if ($i==1){
                                                $change_amount = $senario1_stepup_monthly_amount;
                                            }else{
                                                $change_amount = $change_amount+($change_amount*$annual_increment/100);
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                            </td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>

                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>

                    <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.futureValueOfStepUpSIPRequiredTargetOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
