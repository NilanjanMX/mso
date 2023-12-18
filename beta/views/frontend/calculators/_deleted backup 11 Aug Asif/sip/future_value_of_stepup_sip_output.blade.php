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
                        url: "{{ route('frontend.futureValueOfStepUpSIPOutputSave') }}",
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
         //(1+AV32)*Q8*(((1+AV32)^(12)-1)/AV32)
        $ap1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
        //senario1_amount = (AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
        //(AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
       $senario1_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
        if (isset($interest2)){
            $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
            $ap2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
            $senario2_amount = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$period)-pow((1+$annual_increment/100),$period));
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
                        <a href="{{route('frontend.futureValueOfStepUpSIPOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Step-Up SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Monthly SIP Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($amount)}}
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
                        <tr>
                            <td>
                                <strong>SIP Period  </strong>
                            </td>
                            <td>
                                {{$period?$period:0}} Years
                            </td>
                        </tr>

                        </tbody></table>

                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Total Investment</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ₹ {{custome_money_format($total_investment)}}
                                </td>
                            </tr>
                            </tbody>
                        </table>


                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Expected Future Value</h1>
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
                                        <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                    </td>
                                </tr>
                        @else
                                <tr>
                                    <td>
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($senario1_amount)}}
                                    </td>
                                </tr>
                        @endif
                        </tbody></table>

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
                                        <th style="vertical-align: middle;">Year</th>
                                        <th style="vertical-align: middle;">Monthly Investment</th>
                                        <th style="vertical-align: middle;">Annual Investment</th>
                                        <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $amount;
                                        $previous_amount_int2 = $amount;
                                        $change_amount = $amount;
                                    @endphp

                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                                            $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),($i))-pow((1+$annual_increment/100),($i)));
                                            $previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                            if ($i==1){
                                                $change_amount = $amount;
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
                                            <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @php
                                        $previous_amount_int1 = $amount;
                                    @endphp

                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                                            $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),($i))-pow((1+$annual_increment/100),($i)));

                                            if ($i==1){
                                                $change_amount = $amount;
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
                        <a href="{{route('frontend.futureValueOfStepUpSIPOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
