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
                        url: "{{ route('frontend.termInsuranceSIPOutputSave') }}",
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
        //Balance Left For Monthly SIP  R7-R10
        $balance_left_for_monthly_sip = $annual_investment-$term_insurance_annual_premium;
        //Monthly SIP Amount AU28/12
        $monthly_sip_amount = $balance_left_for_monthly_sip/12;
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
        //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
        //Total Fund Value (1+AU31)*AU29*(((1+AU31)^(AU30)-1)/AU31)
        $total_fund_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($number_of_months)-1)/$rate_of_return2);
        //echo $total_fund_value; die();

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
                        <a href="{{route('frontend.termInsuranceSIPOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Term Insurance + SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Current Age </strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Annual Outlay</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($annual_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance Sum Assured  </strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($term_insurance_sum_assured)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance Period </strong>
                            </td>
                            <td>
                                {{$term_insurance_period?$term_insurance_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Term Insurance Annual Premium</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($term_insurance_annual_premium)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Balance Left For Monthly SIP</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($balance_left_for_monthly_sip)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Monthly SIP Amount</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                ₹ {{custome_money_format($monthly_sip_amount)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center">
                        Expected Fund Value <br>
                        @ {{number_format($rate_of_return, 2, '.', '')}} % At Age {{$term_insurance_period+$current_age}}
                    </h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                ₹ {{custome_money_format($total_fund_value)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Yearwise Projected Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <th>Age</th>
                            <th>Annual Outlay</th>
                            <th>Life Cover</th>
                            <th>Year End Value @ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                            <!-- <th>Risk Cover + Fund Value<br>(In case of Death)</th> -->
                            <th>Payout in case of Unfortunate Event</th>
                        </tr>
                        @for($i=1;$i<=$term_insurance_period;$i++)
                            @php
                            //Year End Value (1+AV66)*AT66*(((1+AV66)^(AU66*12)-1)/AV66)
                            $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                            //Risk Cover N66+V66
                            $risk_cover_fund_value = $term_insurance_sum_assured+$year_end_value;
                            $current_age++;
                            @endphp
                            <tr>
                                <td>{{$current_age}}</td>
                                <td>₹ {{custome_money_format($annual_investment)}}</td>
                                <td>₹ {{custome_money_format($term_insurance_sum_assured)}}</td>
                                <td>₹ {{custome_money_format($year_end_value)}}</td>
                                <td>₹ {{custome_money_format($risk_cover_fund_value)}}</td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>

                    <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

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
                        <a href="{{route('frontend.termInsuranceSIPOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
