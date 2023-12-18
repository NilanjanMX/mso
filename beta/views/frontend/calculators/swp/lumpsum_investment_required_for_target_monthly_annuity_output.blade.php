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
                        url: "{{ route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputSave') }}",
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

        //Annuity Period (Months) T9*12
        $annuity_period_months = $period*12;
        //Monthly Rate of Return (1)  (1+T11%)^(1/12)-1
        $monthly_rate_of_return1 = pow((1+$interest1/100),(1/12))-1 ;
        //Lumpsum For Balance (1) X32/(1+AV29)^AV28
        $lumsum_for_balance1 = $balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months;
        //Lumpsum For Annuity (1) (X28*(1-(1+AV29)^(-AV28)))/AV29
        $lumsum_for_annuity_1 = ($required_monthly_annuity*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1;
        //Lumpsum Investment Required (1) AV31+AV33
        $lumpsum_investment_required_1 = $lumsum_for_balance1+$lumsum_for_annuity_1;
        if (isset($interest2)){
        //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
        $monthly_rate_of_return2 = pow((1+$interest2/100),(1/12))-1 ;
        //Lumpsum For Balance (2) X32/(1+AV29)^AV28
        $lumsum_for_balance2 = $balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months;
        //Lumpsum For Annuity (2) (X28*(1-(1+AV29)^(-AV28)))/AV29
        $lumsum_for_annuity_2 = ($required_monthly_annuity*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2;
        //Lumpsum Investment Required (2) AV31+AV33
        $lumpsum_investment_required_2 = $lumsum_for_balance2+$lumsum_for_annuity_2;
        }

    @endphp

    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Monthly Annuity Planning @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Target Monthly Annuity</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($required_monthly_annuity)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Annuity Period</strong>
                            </td>
                            <td>
                                {{$period?$period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;">
                                <strong>Assumed Rate of Return</strong>
                            </td>
                            <td style="padding: 0;">
                                @if(isset($interest2))
                                    <table width="100%">
                                        <tbody><tr>
                                            <td>
                                                Scenario 1
                                            </td>
                                            <td>
                                                {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                Scenario 2
                                            </td>
                                            <td>
                                                {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>

                                @else
                                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                @endif
                            </td>
                        </tr>
                        @if(isset($balance_required) && $balance_required>0)
                            <tr>
                                <td>
                                    <strong>Balance Required</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($balance_required)}}
                                </td>
                            </tr>
                        @endif
                        </tbody></table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Initial Investment Required</h1>
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
                                    <strong>₹ {{custome_money_format($lumpsum_investment_required_1)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($lumpsum_investment_required_2)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                </td>
                                <td>
                                    ₹ {{custome_money_format($lumpsum_investment_required_1)}}
                                </td>
                            </tr>
                        @endif
                        </tbody></table>

                    @if(isset($report) && $report=='detailed')
                        <h5 class="text-center">Annual Wihdrawal & Projected Investment Value</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @if(isset($interest2))
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Year</th>
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$period;$i++)
                                    @php
                                        //Year End Value 1 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                                        $year_end_value_1 = ($lumpsum_investment_required_1*(1+$monthly_rate_of_return1)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                        //Year End Value 2 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                                        $year_end_value_2 = ($lumpsum_investment_required_2*(1+$monthly_rate_of_return2)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>₹ {{custome_money_format($required_monthly_annuity)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value_1)}}</td>
                                        <td>₹ {{custome_money_format($required_monthly_annuity)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value_2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Year</th>
                                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @for($i=1;$i<=$period;$i++)
                                    @php
                                        //Year End Value 1 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                                        $year_end_value_1 = ($lumpsum_investment_required_1*(1+$monthly_rate_of_return1)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));

                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>₹ {{custome_money_format($required_monthly_annuity)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value_1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
