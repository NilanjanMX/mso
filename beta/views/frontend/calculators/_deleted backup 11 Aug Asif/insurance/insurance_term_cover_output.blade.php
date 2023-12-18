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
                        url: "{{ route('frontend.insuranceTermCoverOutputSave') }}",
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
        //Annual Investment V7-V10
        $annual_investment = $insurance_policy_annual_premium - $equivalent_insurance_term_policy_premium;
        //Monthly SIP Amount AU30/12
        $monthly_sip_amount = $annual_investment/12;
         //Number of Months R9*12
        $number_of_months = $policy_term*12;
          //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return = (1+$rate_of_return_investments/100)**(1/12)-1;
        //Total Fund Value (Investment) (1+AU33)*AU31*(((1+AU33)^(AU32)-1)/AU33)
        $total_fund_value_investment = (1+$rate_of_return)*$monthly_sip_amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
        //Total Fund Value (Insurance) (1+V12%)*(V7)*(((1+V12%)^(V9)-1)/V12%)
        $total_fund_value_insurance = (1+$rate_of_return_insurance/100)*($insurance_policy_annual_premium)*(((1+$rate_of_return_insurance/100)**($policy_term)-1)/($rate_of_return_insurance/100));
        //echo $total_fund_value_insurance; die();

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
                        <a href="{{route('frontend.insuranceTermCoverOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Insurance vs. Term Cover With Annual SIP Comparison @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @else  @endif</h5>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">
                        Insurance
                    </h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Annual Premium</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Sum Assured / Death Benefit</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sum_assured)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Policy Term</strong>
                            </td>
                            <td>
                                {{$policy_term?$policy_term:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Rate Of Return</strong>
                            </td>
                            <td>
                                {{$rate_of_return_insurance?number_format($rate_of_return_insurance, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected Maturity Value</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($total_fund_value_insurance)}}
                            </td>
                        </tr>


                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">
                        Term Cover + Monthly SIP
                    </h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Sum Assured / Death Benefit</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sum_assured)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Policy Premium</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($equivalent_insurance_term_policy_premium)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Monthly SIP Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($monthly_sip_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Total Annual Outlay</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Time Period</strong>
                            </td>
                            <td>
                                {{$policy_term?$policy_term:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Assumed Rate Of Return</strong>
                            </td>
                            <td>
                                {{$rate_of_return_investments?number_format($rate_of_return_investments, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Expected Fund Value</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($total_fund_value_investment)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p style="text-align: left; margin-top: 20px;">
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        *Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}
                    </p>

                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_output')
                    
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.insuranceTermCoverOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
