<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Goal Calculator</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('frontend.calculators.common.pdf_style')
</head>
    <body class="styleApril">
        
            @foreach($list as $key => $value)
                <main style="width: 806px;">
                    @include('frontend.calculators.common.header')

                    <div style="padding: 0 0%;">
                        @if($key == 0)
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center; background-color: #a9f3ff; padding: 10px 0;">Goal Planning Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        @endif
                        <h1 style="background-color: #131f45;margin-bottom:20px !important;color:#fff !important;font-size:20px;padding:10px;text-align:center;">{{$value['purpose_of_investment']}} </h1>

                        @if($value['cost_type'] == 1)
                            @php 
                                $bg51 = $value['period'] * 12;
                                $bg52 = $value['amount'];                                
                            @endphp
                            <table style="">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;text-align: left;">
                                            <strong>Fund Required</strong>
                                        </td>
                                        <td style="width: 50%;">
                                             <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['amount'])}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;">
                                            <strong>Time Period</strong>
                                        </td>
                                        <td>
                                             {{$value['period']}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            </br></br>
                            <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Assumed Rate of Return</h1>
                            <table style="margin-bottom:15px !important;">
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
                            </br></br>
                            <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Investment Options</h1>
                            <table style="margin-bottom:15px !important;">
                                <tbody>
                                    <tr>
                                        <td width="40%">
                                            <strong>Investment Option</strong>
                                        </td>
                                        <td width="30%">
                                            <strong>Asset Allocation</strong>
                                        </td>
                                        <td width="30%">
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
                                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg54)}}
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
                                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg56)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg59)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg61)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg63)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg70)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg73)}}
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
                                                     <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
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
                            
                            <table style="margin-bottom:15px !important;">
                                <tbody>
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Fund Required (Current Cost)</strong>
                                            </td>
                                            <td>
                                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($value['amount'])}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Assumed Inflation Rate</strong>
                                            </td>
                                            <td>
                                                {{number_format((float)$value['inflation'], 2, '.', '')}} %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Future Cost of Fund Required</strong>
                                            </td>
                                            <td>
                                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($bg52)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left;">
                                                <strong>Time Period</strong>
                                            </td>
                                            <td>
                                                 {{$value['period']}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>

                            </br></br>
                            <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Assumed Rate of Return</h1>
                            <table style="margin-bottom:15px !important;">
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
                            </br></br>
                            <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Investment Options</h1>
                            <table style="margin-bottom:15px !important;">
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
                                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg54)}}
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
                                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg56)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg59)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg61)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg63)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_monthly_sip_lumpsum_amount'])}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg70)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($bg73)}}
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
                                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_monthly_sip_amount'])}}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif 
                                </tbody>
                            </table>
                        @endif
                    </div>
                        
                    <br>
                    <br>
                    <div>
                        @php
                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','goal_calculator')->first();
                            if(!empty($note_data2)){
                            @endphp
                            {!!$note_data2->description!!}
                        @php } @endphp
                    </div>
                </main>

                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif

                @if($key+1 < count($list))
                    <div class="page-break"></div>
                @endif

            @endforeach
            
                
            @include('frontend.calculators.suggested.pdf')
            
        

        
    </body>
</html>