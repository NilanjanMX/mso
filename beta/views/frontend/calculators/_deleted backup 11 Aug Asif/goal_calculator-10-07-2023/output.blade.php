
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
                    url: "{{ route('frontend.goal_calculator_output_save') }}",
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
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div>
        </div>
    </div>

    
    
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>

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
                            <a href="{{route('frontend.goal_calculator_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    
                    <h5 class="mb-3">Goal Planning Calculation @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                    
                    @foreach($list as $key => $value)

                        <h5 class="mb-3"> {{$value['purpose_of_investment']}} </h5>

                        @if($value['cost_type'] == 1)
                            @php 
                                $bg51 = $value['period'] * 12;
                                $bg52 = $value['amount'];                                
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Fund Required</strong>
                                        </td>
                                        <td>
                                             ₹ {{custome_money_format($value['amount'])}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Time Period</strong>
                                        </td>
                                        <td>
                                             {{$value['period']}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Assumed Rate of Return</h5>
                            <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">
                                            <strong>Debt</strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <strong>Hybrid</strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <strong>Equity</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($value['aror_debt'])
                                                {{number_format((float)$value['aror_debt'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($value['aror_hybrid'])
                                                {{number_format((float)$value['aror_hybrid'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($value['aror_equity'])
                                                {{number_format((float)$value['aror_equity'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                    
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Investment Options</h5>
                            <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Investment Option</strong>
                                        </td>
                                        <td>
                                            <strong>Asset Allocation</strong>
                                        </td>
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    </tr>
                                    @if($value['lumpsum_investment_mode'])
                                        @php 
                                            $bg53 = $value['aror_debt']*$value['lumpsum_debt']/100+$value['aror_hybrid']*$value['lumpsum_hybrid']/100+$value['aror_equity']*$value['lumpsum_equity']/100;
                                            $bg54 = $bg52/pow((1+$bg53/100),$value['period']);
                                        @endphp
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Lumpsum Investment</strong>
                                            </td>
                                            <td>
                                                @if($value['lumpsum_debt'] && $value['lumpsum_debt'] != "0")
                                                    <div>Debt - {{number_format((float)$value['lumpsum_debt'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['lumpsum_hybrid'] && $value['lumpsum_hybrid'] != "0")
                                                    <div>Hybrid - {{number_format((float)$value['lumpsum_hybrid'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['lumpsum_equity'] && $value['lumpsum_equity'] != "0")
                                                    <div>Equity - {{number_format((float)$value['lumpsum_equity'], 2, '.', '')}} %</div>
                                                @endif
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($bg54)}}
                                            </td>
                                        </tr>
                                    @endif

                                    @if($value['monthly_sip_investment_mode'])
                                        @php 
                                            $bg55 = pow((1+($value['aror_debt']*$value['monthly_sip_debt']/100+$value['aror_hybrid']*$value['monthly_sip_hybrid']/100+$value['aror_equity']*$value['monthly_sip_equity']/100)/100),(1/12))-1;
                                            $bg56 = ($bg52*$bg55)/(pow((1+$bg55),($bg51))-1);
                                        @endphp
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Monthly SIP</strong>
                                            </td>
                                            <td>
                                                @if($value['monthly_sip_debt'] && $value['monthly_sip_debt'] != "0")
                                                    <div>Debt - {{number_format((float)$value['monthly_sip_debt'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['monthly_sip_hybrid'] && $value['monthly_sip_hybrid'] != "0")
                                                    <div>Hybrid - {{number_format((float)$value['monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['monthly_sip_equity'] && $value['monthly_sip_equity'] != "0")
                                                    <div>Equity - {{number_format((float)$value['monthly_sip_equity'], 2, '.', '')}} %</div>
                                                @endif
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($bg56)}}
                                            </td>
                                        </tr>
                                    @endif

                                    @if($value['limited_period_monthly_investment_mode'])
                                        
                                        @if($value['limited_period_monthly_sip_period_1'] && $value['limited_period_monthly_sip_period_1'] != "0")
                                            @php
                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg58 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_1'])*12));
                                                $bg59 = ($bg58*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_1'])*12))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_1']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg59)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['limited_period_monthly_sip_period_2'] && $value['limited_period_monthly_sip_period_2'] != "0")
                                            @php
                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg60 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_2'])*12));
                                                $bg61 = ($bg60*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_2'])*12))-1);

                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_2']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg61)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['limited_period_monthly_sip_period_3'] && $value['limited_period_monthly_sip_period_3'] != "0")
                                            @php

                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;

                                                $bg62 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_3'])*12));
                                                $bg63 = ($bg62*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_3'])*12))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_3']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg63)}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif

                                    @if($value['lumpsum_monthly_sip_investment_mode'])
                                        
                                        @if($value['lumpsum_monthly_sip'] == 1)
                                            @php 
                                                $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                $bg69 = $bg52-$bg68;
                                                $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum  + Monthly SIP</strong>
                                                </td>
                                                <td></td>
                                                <td>
                                                     
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}} 
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg70)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['lumpsum_monthly_sip'] == 2)
                                            @php 
                                                $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);
                                                $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                $bg69 = $bg52-$bg68;
                                                $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                $bg71 = (1+$bg66)*$value['lumpsum_monthly_sip_amount']*((pow((1+$bg66),($bg51))-1)/$bg66);
                                                $bg72 = $bg52-$bg71;
                                                $bg73 = $bg72/pow((1+$bg64/100),$value['period']);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum  + Monthly SIP</strong>
                                                </td>
                                                <td></td>
                                                <td>
                                                     
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg73)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                    
                                </tbody>
                            </table>
                        @else
                            @php 
                                $bg51 = $value['period'] * 12;
                                $bg52 = $value['amount']*pow((1+$value['inflation']/100),$value['period']);                                
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Fund Required (Current Cost)</strong>
                                        </td>
                                        <td>
                                             ₹ {{custome_money_format($value['amount'])}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Assumed Inflation Rate</strong>
                                        </td>
                                        <td>
                                            {{number_format((float)$value['inflation'], 2, '.', '')}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Future Cost of Fund Required</strong>
                                        </td>
                                        <td>
                                             ₹ {{custome_money_format($bg52)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Time Period</strong>
                                        </td>
                                        <td>
                                             {{$value['period']}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Assumed Rate of Return</h5>
                            <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td style="text-align: center;">
                                            <strong>Debt</strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <strong>Hybrid</strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <strong>Equity</strong>
                                        </td>
                                    </tr>
                                     <tr>
                                        <td>
                                            @if($value['aror_debt'])
                                                {{number_format((float)$value['aror_debt'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($value['aror_hybrid'])
                                                {{number_format((float)$value['aror_hybrid'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($value['aror_equity'])
                                                {{number_format((float)$value['aror_equity'], 2, '.', '')}} %
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Investment Options</h5>
                            <table class="table table-bordered" style="margin: 0 auto; margin-bottom: 30px;">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Investment Option</strong>
                                        </td>
                                        <td>
                                            <strong>Asset Allocation</strong>
                                        </td>
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    </tr>
                                    @if($value['lumpsum_investment_mode'])
                                        @php 
                                            $bg53 = $value['aror_debt']*$value['lumpsum_debt']/100+$value['aror_hybrid']*$value['lumpsum_hybrid']/100+$value['aror_equity']*$value['lumpsum_equity']/100;
                                            $bg54 = $bg52/pow((1+$bg53/100),$value['period']);
                                        @endphp
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Lumpsum Investment</strong>
                                            </td>
                                            <td>
                                                @if($value['lumpsum_debt'] && $value['lumpsum_debt'] != "0")
                                                    <div>Debt - {{number_format((float)$value['lumpsum_debt'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['lumpsum_hybrid'] && $value['lumpsum_hybrid'] != "0")
                                                    <div>Hybrid - {{number_format((float)$value['lumpsum_hybrid'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['lumpsum_equity'] && $value['lumpsum_equity'] != "0")
                                                    <div>Equity - {{number_format((float)$value['lumpsum_equity'], 2, '.', '')}} %</div>
                                                @endif
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($bg54)}}
                                            </td>
                                        </tr>
                                    @endif

                                    @if($value['monthly_sip_investment_mode'])
                                        @php 
                                            $bg55 = pow((1+($value['aror_debt']*$value['monthly_sip_debt']/100+$value['aror_hybrid']*$value['monthly_sip_hybrid']/100+$value['aror_equity']*$value['monthly_sip_equity']/100)/100),(1/12))-1;
                                            $bg56 = ($bg52*$bg55)/(pow((1+$bg55),($bg51))-1);
                                        @endphp
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Monthly SIP</strong>
                                            </td>
                                            <td>
                                                @if($value['monthly_sip_debt'] && $value['monthly_sip_debt'] != "0")
                                                    <div>Debt - {{number_format((float)$value['monthly_sip_debt'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['monthly_sip_hybrid'] && $value['monthly_sip_hybrid'] != "0")
                                                    <div>Hybrid - {{number_format((float)$value['monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                @endif
                                                @if($value['monthly_sip_equity'] && $value['monthly_sip_equity'] != "0")
                                                    <div>Equity - {{number_format((float)$value['monthly_sip_equity'], 2, '.', '')}} %</div>
                                                @endif
                                            </td>
                                            <td>
                                                 ₹ {{custome_money_format($bg56)}}
                                            </td>
                                        </tr>
                                    @endif

                                    @if($value['limited_period_monthly_investment_mode'])
                                        @php 
                                            
                                        @endphp

                                        @if($value['limited_period_monthly_sip_period_1'] && $value['limited_period_monthly_sip_period_1'] != "0")
                                            @php
                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg58 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_1'])*12));
                                                $bg59 = ($bg58*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_1'])*12))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_1']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg59)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['limited_period_monthly_sip_period_2'] && $value['limited_period_monthly_sip_period_2'] != "0")
                                            @php
                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg60 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_2'])*12));
                                                $bg61 = ($bg60*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_2'])*12))-1);
                                            @endphp

                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_2']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg61)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['limited_period_monthly_sip_period_3'] && $value['limited_period_monthly_sip_period_3'] != "0")
                                            @php
                                                $bg57 = pow((1+($value['aror_debt']*$value['limited_period_monthly_sip_debt']/100+$value['aror_hybrid']*$value['limited_period_monthly_sip_hybrid']/100+$value['aror_equity']*$value['limited_period_monthly_sip_equity']/100)/100),(1/12))-1;
                                                $bg62 = $bg52/pow((1+$bg57),(($value['period']-$value['limited_period_monthly_sip_period_3'])*12));
                                                $bg63 = ($bg62*$bg57)/(pow((1+$bg57),(($value['limited_period_monthly_sip_period_3'])*12))-1);
                                            @endphp

                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP For {{$value['limited_period_monthly_sip_period_3']}} Years</strong>
                                                </td>
                                                <td>
                                                    @if($value['limited_period_monthly_sip_debt'] && $value['limited_period_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['limited_period_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_hybrid'] && $value['limited_period_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['limited_period_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['limited_period_monthly_sip_equity'] && $value['limited_period_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['limited_period_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg63)}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif

                                    @if($value['lumpsum_monthly_sip_investment_mode'])
                                        
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Lumpsum  + Monthly SIP</strong>
                                            </td>
                                            <td></td>
                                            <td>
                                                 
                                            </td>
                                        </tr>
                                        @if($value['lumpsum_monthly_sip'] == 1)
                                            @php 
                                                $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                $bg69 = $bg52-$bg68;
                                                $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg70)}}
                                                </td>
                                            </tr>
                                        @endif
                                        @if($value['lumpsum_monthly_sip'] == 2)
                                            @php 
                                                $bg64 = $value['aror_debt']*$value['lumpsum_monthly_sip_amount_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_amount_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_amount_equity']/100;

                                                $bg65 = $value['amount']/pow((1+$bg64/100),$value['period']);
                                                $bg66 = pow((1+($value['aror_debt']*$value['lumpsum_monthly_sip_debt']/100+$value['aror_hybrid']*$value['lumpsum_monthly_sip_hybrid']/100+$value['aror_equity']*$value['lumpsum_monthly_sip_equity']/100)/100),(1/12))-1;

                                                $bg67 = ($bg52*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                $bg68 = $value['lumpsum_monthly_sip_lumpsum_amount']*pow((1+$bg64/100),$value['period']);
                                                $bg69 = $bg52-$bg68;
                                                $bg70 = ($bg69*$bg66)/(pow((1+$bg66),($bg51))-1);

                                                $bg71 = (1+$bg66)*$value['lumpsum_monthly_sip_amount']*((pow((1+$bg66),($bg51))-1)/$bg66);
                                                $bg72 = $bg52-$bg71;
                                                $bg73 = $bg72/pow((1+$bg64/100),$value['period']);
                                            @endphp
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Lumpsum Investment</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_amount_debt'] && $value['lumpsum_monthly_sip_amount_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_amount_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_hybrid'] && $value['lumpsum_monthly_sip_amount_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_amount_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_amount_equity'] && $value['lumpsum_monthly_sip_amount_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_amount_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($bg73)}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="text-align: left;">
                                                    <strong>Monthly SIP</strong>
                                                </td>
                                                <td>
                                                    @if($value['lumpsum_monthly_sip_debt'] && $value['lumpsum_monthly_sip_debt'] != "0")
                                                        <div>Debt - {{number_format((float)$value['lumpsum_monthly_sip_debt'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_hybrid'] && $value['lumpsum_monthly_sip_hybrid'] != "0")
                                                        <div>Hybrid - {{number_format((float)$value['lumpsum_monthly_sip_hybrid'], 2, '.', '')}} %</div>
                                                    @endif
                                                    @if($value['lumpsum_monthly_sip_equity'] && $value['lumpsum_monthly_sip_equity'] != "0")
                                                        <div>Equity - {{number_format((float)$value['lumpsum_monthly_sip_equity'], 2, '.', '')}} %</div>
                                                    @endif
                                                </td>
                                                <td>
                                                     ₹ {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        @endif

                    @endforeach
                    
                    <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                    @include('frontend.calculators.suggested.output')
                    
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    
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
                            <a href="{{route('frontend.goal_calculator_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
        
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.goal_calculator_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')


@endsection

