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
                        url: "{{ route('frontend.futureValueOfLumpsumSipOutputSave') }}",
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
        //Number of Months Q13*12
        $number_of_months = $investment_period*12;
        //Exp Rate of Return (SIP) (1+Q10%)^(1/12)-1
        $expected_rate_of_return_sip = (1+$expected_rate_of_return1/100)**(1/12)-1;
        //Exp Rate of Return (Lumpsum) (1+Q12%)^(1/12)-1
        $expected_rate_of_returnlumpsum = (1+$expected_rate_of_return2/100)**(1/12)-1;
        //SIP Fund Value (1+AR31)*Q9*(((1+AR31)^(AR30)-1)/AR31)
        $sip_fund_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($number_of_months)-1)/$expected_rate_of_return_sip);
        //Lumpsum Fund Value Q11*(1+AR32)^AR30
        $lumpsum_fund_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**$number_of_months;
        //Total Fund Value AR33+AR34
        $total_fund_value = $sip_fund_value+$lumpsum_fund_value;
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
                        <a href="{{route('frontend.futureValueOfLumpsumSipOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Future Value Of Lumpsum + SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h5>

                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>SIP Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sip_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Lumpsum Investment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($lumpsum_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Period</strong>
                            </td>
                            <td>
                                {{$investment_period?$investment_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%; vertical-align: middle;">
                                <strong>Assumed Rate of Return</strong>
                            </td>
                            <td style="padding: 0px;">
                                <table style="width: 100%;">
                                    <tbody>
                                    <tr>
                                        <td>SIP</td>
                                        <td>{{$expected_rate_of_return1?number_format($expected_rate_of_return1, 2, '.', ''):0}} %</td>
                                    </tr>
                                    <tr>
                                        <td>Lumpsum</td>
                                        <td>{{$expected_rate_of_return2?number_format($expected_rate_of_return2, 2, '.', ''):0}} %</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">Expected Future Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>SIP Fund Value</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($sip_fund_value)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Lumpsum Fund Value</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($lumpsum_fund_value)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Total Fund Value</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($total_fund_value)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p style="text-align: left; margin-top: 20px;">
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        * Returns are not guaranteed. The above is for illustration purpose only.
                    </p>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Annual Investment & Yearwise Projected Value</h1>
                    <table class="table table-bordered text-center" style="background: #fff;">
                        <tbody>
                        <tr>
                            <th style="vertical-align: middle;">Year</th>
                            <th style="vertical-align: middle;">Annual Investment</th>
                            <th style="vertical-align: middle;">Cumulative Investment</th>
                            <th style="vertical-align: middle;">SIP Fund Value</th>
                            <th style="vertical-align: middle;">Lumpsum Fund Value</th>
                            <th style="vertical-align: middle;">Total Fund Value</th>
                        </tr>
                            @php
                                $cumulative_investment = 0;
                            @endphp

                            @for($i=1;$i<=$investment_period;$i++)
                                @php
                                    //Annual Investment AU76*12+AV76
                                    if ($i==1){
                                    $annual_investment = $sip_amount*12+$lumpsum_investment;
                                    }else{
                                        $annual_investment = $sip_amount*12;
                                    }
                                    //Cumulative Investment
                                    $cumulative_investment +=$annual_investment;
                                    //SIP End Value (1+AS76)*AU76*(((1+AS76)^(AR76*12)-1)/AS76)
                                    $sip_end_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($i*12)-1)/$expected_rate_of_return_sip);
                                    //Lumpsum End Value AV76*(1+AT76)^(AR76*12)
                                    $lumpsum_end_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**($i*12);
                                @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>₹ {{custome_money_format($annual_investment)}}</td>
                                <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                <td>₹ {{custome_money_format($sip_end_value)}}</td>
                                <td>₹ {{custome_money_format($lumpsum_end_value)}}</td>
                                <td>₹ {{custome_money_format($sip_end_value+$lumpsum_end_value)}}</td>
                            </tr>
                            @endfor

                        </tbody>
                    </table>
                    <p style="text-align: left; margin-top: 20px;">
                        *The above chart is approximate and for illustration purpose only
                    </p>

                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.futureValueOfLumpsumSipOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
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
