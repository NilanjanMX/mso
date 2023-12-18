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
                        url: "{{ route('frontend.futureValueOfSipStp_save') }}",
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
                    <h2 class="page-title">Future Value Of SIP + STP</h2>
                </div>
            </div>
        </div>
    </div>
    @php
        $sip_amount = $sip_amount;
        $sip_interest_rate = $sip_interest_rate;
        $stp_amount = $stp_amount;
        $debt_interest = $debt_interest;
        $equity_interest = $equity_interest;
        $monthly_transfer_mode = $monthly_transfer_mode;
        $period = $investment_period;

        $number_of_months = $period*12;
        //Exp Return (SIP) (1+Q10%)^(1/12)-1
        $exp_return_sip_rate = (1+$sip_interest_rate/100)**(1/12)-1;
        //Exp Debt Return (STP) (1+Q12%)^(1/12)-1
        $exp_debt_return_stp = (1+$debt_interest/100)**(1/12)-1;
        //Exp Equity Return (STP) (1+Q13%)^(1/12)-1
        $exp_equite_return_stp = (1+$equity_interest/100)**(1/12)-1;
        //SIP Fund Value (1+AR33)*Q9*(((1+AR33)^(AR32)-1)/AR33)
        $sip_fund_value = (1+$exp_return_sip_rate)*$sip_amount*(((1+$exp_return_sip_rate)**($number_of_months)-1)/$exp_return_sip_rate);
        //Monthly Appreciation Q11*AR34
        $monthly_appreciation = $stp_amount*$exp_debt_return_stp;
        //Future Value of Debt Fund
        $future_value_of_debt_fund = $stp_amount;
        //Future Value of Equity Fund AR37*(((1+AR35)^(AR32)-1)/AR35)
        $future_value_of_equity_fund = $monthly_appreciation*(((1+$exp_equite_return_stp)**($number_of_months)-1)/$exp_equite_return_stp);
        //STP Fund Value =AR38+AR39
        $stp_fund_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
        //Total Fund Value AR36+AR40
        $total_fund_value = $sip_fund_value+$stp_fund_value;

    @endphp
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.futureValueOfSipStp_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.futureValueOfSipStp_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h1 class="midheading">SIP + STP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
                        <div class="roundBorderHolder">
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
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                        {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>STP Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($stp_amount)}}
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
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{$investment_period?$investment_period:0}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>Monthly Transfer Mode</strong>
                                    </td>
                                    <td>
                                        Capital Appreciation
                                    </td>
                                </tr>
        
                                </tbody>
                            </table>
                        </div>
        
                            <h1 class="midheading">
                                Expected Future Value
                            </h1>
                            
                        <div class="roundBorderHolder">
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
                                        <strong>STP Debt Fund Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($future_value_of_debt_fund)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 50%">
                                        <strong>STP Equity Fund Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($future_value_of_equity_fund)}}
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
                        </div>
                        
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            
                            <!--<p style="text-align: left;">-->
                            <!--    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>-->
                            <!--    * Returns are not guaranteed. The above is for illustration purpose only.-->
                            <!--</p>-->
                            
                            <div class="description-text">
                            @php
                            $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_SIP_+_STP')->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                            @php } @endphp
                            
                            </div>
                            @if ($report != "summary")
                            <h1 class="midheading"> Annual Investment & Yearwise Projected Value</h1>
                        <div class="roundBorderHolder">
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
                                            @php
                                                $annual_investment = ($sip_amount * 12);
                                                if ($i == 1) {
                                                    $annual_investment = $stp_amount + ($sip_amount * 12);
                                                }
        
                                                $cumulative_investment = $stp_amount + (($sip_amount * 12) * $i);
        
                                                $sip_value = (1+$exp_return_sip_rate)*$sip_amount*(((1+$exp_return_sip_rate)**($i*12)-1)/$exp_return_sip_rate);
        
                                                //Future Value of Equity Fund AR37*(((1+AR35)^(AR32)-1)/AR35)
                                                $future_value_of_equity_fund = $monthly_appreciation*(((1+$exp_equite_return_stp)**($i*12)-1)/$exp_equite_return_stp);
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
                                        @endfor
        
        
                                </tbody>
                            </table>
                        </div>
                        <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>-->
                        
                        <div class="description-text">
                        @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP_+_STP')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                        @php } @endphp
                        </div>
                        @endif
                            
                            @include('frontend.calculators.suggested.output') 
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.futureValueOfSipStp_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.futureValueOfSipStp_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.futureValueOfSipStp_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
