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
                        url: "{{ route('frontend.childEducationOutputSave') }}",
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

        //Number of Years
        $number_of_years = $fund_required_age - $current_age;
        //FV of Fund Required AF42*(1+AD45%)^BD40
        $fv_of_fund_required = $fund_required_amount*(1+$inflation_rate/100)**$number_of_years;
        //FV of Current Investment AF43*(1+AD44%)^BD40
        $fv_of_current_investment = $investment_amount*(1+$return_rate/100)**$number_of_years;
        //Balance Required BD41-BD42
        $balance_required = $fv_of_fund_required - $fv_of_current_investment;
        //Balance After 5 Years (1) IF(AD41-AD40<5,"NA",BD43/(1+AD46%)^(BD40-5))
        if ($number_of_years < 5){
            $balance_after_5_years1 = 'NA';
        }else{
            $balance_after_5_years1 = $balance_required/(1+$return_rate_1/100)**($number_of_years-5);
        }

        //Balance After 10 Years (1) IF(AD41-AD40<10,"NA",BD43/(1+AD46%)^(BD40-10))
        if ($number_of_years<10){
            $balance_after_10_years1 = 'NA';
        }else{
            $balance_after_10_years1 = $balance_required/(1+$return_rate_1/100)**($number_of_years-10);
        }

        //Number of Months (5 Year)
        $number_of_months_5year = 5*12;
        //Number of Months (10 Year)
        $number_of_months_10year = 10*12;
        //Number of Months (Till End) BD40*12
        $number_of_months_till_end = $number_of_years*12;
        //Monthly Rate of Return (1) (1+AD46%)^(1/12)-1
        $monthly_rate_of_return1 = (1+$return_rate_1/100)**(1/12)-1;
        //Lumpsum Investment Required (1) BD43/(1+AD46%)^(BD40)
        $limpsum_investment_required1 = $balance_required/(1+$return_rate_1/100)**($number_of_years);
        //SIP For 5 Years (1) IF(BD44="NA","NA",(BD44*BD51)/((1+BD51)*((1+BD51)^(BD48)-1)))
        if ($number_of_years<5){
            $sip_for_5_years1 = "NA";
        }else{
            $sip_for_5_years1 = ($balance_after_5_years1*$monthly_rate_of_return1)/((1+$monthly_rate_of_return1)*((1+$monthly_rate_of_return1)**($number_of_months_5year)-1));
        }
        //SIP For 10 Years (1) IF(BD46="NA","NA",(BD46*BD51)/((1+BD51)*((1+BD51)^(BD49)-1)))
        if ($number_of_years<10){
            $sip_for_10_years1 = "NA";
        }else{
            $sip_for_10_years1 = ($balance_after_10_years1*$monthly_rate_of_return1)/((1+$monthly_rate_of_return1)*((1+$monthly_rate_of_return1)**($number_of_months_10year)-1));
        }
        //SIP Till End (1) (BD43*BD51)/((1+BD51)*((1+BD51)^(BD50)-1))
        $sip_till_end1 = ($balance_required*$monthly_rate_of_return1)/((1+$monthly_rate_of_return1)*((1+$monthly_rate_of_return1)**($number_of_months_5year)-1));


        if (isset($return_rate_2)){
            if ($number_of_years < 5){
                $balance_after_5_years2 = 'NA';
            }else{
                $balance_after_5_years2 = $balance_required/(1+$return_rate_1/100)**($number_of_years-5);
            }

            if ($number_of_years<10){
                $balance_after_10_years2 = 'NA';
            }else{
                $balance_after_10_years2 = $balance_required/(1+$return_rate_1/100)**($number_of_years-10);
            }
            //Monthly Rate of Return (2) (1+AD46%)^(1/12)-1
            $monthly_rate_of_return2 = (1+$return_rate_2/100)**(1/12)-1;
            //Lumpsum Investment Required (2) BD43/(1+AD46%)^(BD40)
             $limpsum_investment_required2 = $balance_required/(1+$return_rate_2/100)**($number_of_years);
              //SIP For 5 Years (1) IF(BD44="NA","NA",(BD44*BD51)/((1+BD51)*((1+BD51)^(BD48)-1)))
                if ($number_of_years<5){
                    $sip_for_5_years2 = "NA";
                }else{
                    $sip_for_5_years2 = ($balance_after_5_years2*$monthly_rate_of_return2)/((1+$monthly_rate_of_return2)*((1+$monthly_rate_of_return2)**($number_of_months_5year)-1));
                }
                //SIP For 10 Years (1) IF(BD46="NA","NA",(BD46*BD51)/((1+BD51)*((1+BD51)^(BD49)-1)))
                if ($number_of_years<10){
                    $sip_for_10_years2 = "NA";
                }else{
                    $sip_for_10_years2 = ($balance_after_10_years2*$monthly_rate_of_return2)/((1+$monthly_rate_of_return2)*((1+$monthly_rate_of_return2)**($number_of_months_10year)-1));
                }

                //SIP Till End (2) (BD43*BD51)/((1+BD51)*((1+BD51)^(BD50)-1))
                $sip_till_end2 = ($balance_required*$monthly_rate_of_return2)/((1+$monthly_rate_of_return2)*((1+$monthly_rate_of_return2)**($number_of_months_5year)-1));
        }
    @endphp

    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.childEducationOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Child {{$fund_requirement_purpose}} @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Child Name</strong>
                            </td>
                            <td>
                                {{$child_name}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Child Age</strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Fund Required at Age</strong>
                            </td>
                            <td>
                                {{$fund_required_age?$fund_required_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Fund Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($fund_required_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Current Investment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($investment_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Rate of Return (CI)</strong>
                            </td>
                            <td>
                                {{$return_rate?number_format($return_rate, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected Inflation Rate</strong>
                            </td>
                            <td>
                                {{$inflation_rate?number_format($inflation_rate, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Return @if(isset($return_rate_2)) (Scenario 1) @endif</strong>
                            </td>
                            <td>
                                {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        @if(isset($return_rate_2))
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Return (Scenario 2)</strong>
                            </td>
                            <td>
                                {{$return_rate_1?number_format($return_rate_2, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Inflated Cost of Funds Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($fv_of_fund_required)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected FV of Current Investment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($fv_of_current_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Balance Fund Required</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($balance_required)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Available Investment Options:</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($return_rate_2))
                            <tr>
                                <td>
                                   <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong>Option 1 @ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %</strong>
                                </td>
                                <td>
                                    <strong>Option 2 @ {{$return_rate_2?number_format($return_rate_2, 2, '.', ''):0}} %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age {{$fund_required_age}}</td>
                                <td>₹ {{($sip_till_end1=='NA')?$sip_till_end1:custome_money_format($sip_till_end1)}}</td>
                                <td>₹ {{($sip_till_end2=='NA')?$sip_till_end2:custome_money_format($sip_till_end2)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹ {{($sip_for_5_years1=='NA')?$sip_for_5_years1:custome_money_format($sip_for_5_years1)}}</td>
                                <td>₹ {{($sip_for_5_years2=='NA')?$sip_for_5_years2:custome_money_format($sip_for_5_years2)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ {{($sip_for_10_years1=='NA')?$sip_for_10_years1:custome_money_format($sip_for_10_years1)}}</td>
                                <td>₹ {{($sip_for_10_years2=='NA')?$sip_for_10_years2:custome_money_format($sip_for_10_years2)}}</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ {{($limpsum_investment_required1=='NA')?$limpsum_investment_required1:custome_money_format($limpsum_investment_required1)}}</td>
                                <td>₹ {{($limpsum_investment_required2=='NA')?$limpsum_investment_required2:custome_money_format($limpsum_investment_required2)}}</td>
                            </tr>
                         @else
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong> @ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age</td>
                                <td>₹ {{($sip_till_end1=='NA')?$sip_till_end1:custome_money_format($sip_till_end1)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹ {{($sip_for_5_years1=='NA')?$sip_for_5_years1:custome_money_format($sip_for_5_years1)}}</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ {{($sip_for_10_years1=='NA')?$sip_for_10_years1:custome_money_format($sip_for_10_years1)}}</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ {{($limpsum_investment_required1=='NA')?$limpsum_investment_required1:custome_money_format($limpsum_investment_required1)}}</td>
                            </tr>
                         @endif
                        </tbody>
                    </table>
                    <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.childEducationOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{url('/')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
