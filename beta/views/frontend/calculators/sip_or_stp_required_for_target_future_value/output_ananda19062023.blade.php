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
                        url: "{{ route('frontend.sipStpRequiredForTargetFutureValue_save') }}",
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
    <style>
        
    </style>
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
    
    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">SIP / STP Required For Target Future Value</h2>
                </div>
            </div>
        </div>
    </div>
    @php

        $target_amount = $target_amount;
        $period = $investment_period;
        $amount = $amount;
        $sip_or_stp = $sip_or_stp;
        $sip_interest_rate = $sip_interest_rate;
        $debt_interest = $debt_interest;
        $equity_interest = $equity_interest;
        $monthly_transfer_mode = $monthly_transfer_mode;

        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = (1+$debt_interest/100)**(1/12)-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_interest/100),(1/12))-1;
        //Exp rate of return (1+Q16%)^(1/12)-1;
        $rate_of_return = (1+$sip_interest_rate/100)**(1/12)-1;


        if ($sip_or_stp == "sip") {
                //Monthly Appreciation T8*AT41
                $monthly_appreciation = $amount*$monthly_debit_return;
                $future_value_of_debt_fund = $amount;
                //AR37*(((1+AR36)^(AR33)-1)/AR36)
                $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $balance_required = $target_amount - $total_stp_value;

                $monthly_sip_required = ($balance_required*$rate_of_return)/((1+$rate_of_return)*((1+$rate_of_return)**($number_of_months)-1));
            }else{
                $assumed_initial_investment = 1;
                  //Monthly Appreciation T8*AT41
                $monthly_appreciation = $assumed_initial_investment*$monthly_debit_return;

                 $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
                 $balance_required = $target_amount - $sip_value;


                 $future_value_of_debt_fund = $assumed_initial_investment;
                 $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $required_stp_amount = $balance_required / $total_stp_value;
        }

    @endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.sipStpRequiredForTargetFutureValue_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    
                    @if($permission['is_save'])
                        @if($edit_id)
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.sipStpRequiredForTargetFutureValue_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h5 class="midheading">SIP + STP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h5>
                        <div class="roundBorderHolder">
                            
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($target_amount)}}
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
                                    <td style="width: 50%">
                                        <strong>
                                            @if ($sip_or_stp == "sip") STP Investment @else SIP Amount @endif
                                        </strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                @if ($sip_or_stp == "sip")
                                    <tr>
                                        <td style="width: 50%; vertical-align: middle;">
                                            <strong>Assumed Rate of Return</strong>
                                        </td>
                                        <td style="padding: 0px;">
                                            <table style="width: 100%;">
                                                <tbody>

                                                <tr>
                                                    <td>Debt</td>
                                                    <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>Equity</td>
                                                    <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Return on SIP</strong>
                                        </td>
                                        <td>
                                            {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td style="width: 50%">
                                            <strong>Assumed Return on SIP</strong>
                                        </td>
                                        <td>
                                            {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; vertical-align: middle;">
                                            <strong>Assumed Rate of Return on STP</strong>
                                        </td>
                                        <td style="padding: 0px;">
                                            <table style="width: 100%;">
                                                <tbody>

                                                <tr>
                                                    <td>Debt</td>
                                                    <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                                </tr>
                                                <tr>
                                                    <td>Equity</td>
                                                    <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>

                                @endif

                                </tbody>
                            </table>

                            <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">
                                @if ($sip_or_stp == "sip")
                                    Monthly SIP Required
                                @else
                                    STP Investment Required
                                @endif
                            </h1>
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        @if ($sip_or_stp == "sip")
                                            ₹ {{custome_money_format($monthly_sip_required)}}
                                        @else
                                            ₹ {{custome_money_format($required_stp_amount)}}
                                        @endif
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
                                    <th style="vertical-align: middle;">STP Fund Value</th>
                                    <th style="vertical-align: middle;">Total Fund Value</th>
                                </tr>
                                @php
                                    $cumulative_investment = 0;
                                @endphp
                                @for ($i = 1; $i <= $investment_period; $i++)
                                    @if ($sip_or_stp == "sip")
                                        @php
                                            $annual_investment = ($monthly_sip_required * 12);
                                                if ($i == 1) {
                                                    $annual_investment = $amount + ($monthly_sip_required * 12);
                                                }

                                                $cumulative_investment = $amount + (($monthly_sip_required * 12) * $i);

                                            $sip_value = (1+$rate_of_return)*$monthly_sip_required*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);

                                            $future_value_of_debt_fund = $amount;
                                            //Future Value of Equity Fund AR37*(((1+AR35)^(AR32)-1)/AR35)
                                            $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);

                                            //STP Fund Value =AR38+AR39
                                            $stp_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>₹ {{custome_money_format($annual_investment)}}</td>
                                            <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                            <td>₹ {{custome_money_format($sip_value)}}</td>
                                            <td>₹ {{custome_money_format($stp_value)}}</td>
                                            <td>₹ {{custome_money_format($sip_value + $stp_value)}}</td>
                                        </tr>

                                    @else
                                        @php
                                            $annual_investment = ($amount * 12);
                                            if ($i == 1) {
                                                $annual_investment = $required_stp_amount + ($amount * 12);
                                            }
                                            $cumulative_investment = $required_stp_amount + (($amount * 12) * $i);
                                            $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);
                                            $stp_value = $required_stp_amount + ($required_stp_amount*$monthly_debit_return)*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);

                                        @endphp
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>₹ {{custome_money_format($annual_investment)}}</td>
                                            <td>₹ {{custome_money_format($cumulative_investment)}}</td>
                                            <td>₹ {{custome_money_format($sip_value)}}</td>
                                            <td>₹ {{custome_money_format($stp_value)}}</td>
                                            <td>₹ {{custome_money_format($sip_value + $stp_value)}}</td>
                                        </tr>
                                    @endif

                                @endfor


                                </tbody>
                            </table>
                            <p style="text-align: left; margin-top: 20px;">
                                *The above chart is approximate and for illustration purpose only
                            </p>
                        </div>
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.sipStpRequiredForTargetFutureValue_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif
                        @if($permission['is_save'])
                            @if($edit_id)
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                            @else
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                            @endif
                        @else
                            <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                        @endif
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.sipStpRequiredForTargetFutureValue_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.sipStpRequiredForTargetFutureValue_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
