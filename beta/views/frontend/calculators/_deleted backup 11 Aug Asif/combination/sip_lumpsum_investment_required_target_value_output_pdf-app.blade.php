<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
            border: 1px solid #b8b8b8;
            padding: 5px 20px;
            font-weight: normal;
            color: #000;
        }

        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
        }

        .table-bordered th, .table-bordered td{
            padding: 10px;
            font-size: 18px;
        }

        h1 {
            font-size: 20px !important;
            color: #131f55 !important;
            margin-bottom: 0 !important;
            margin-top: 15px !important;
        }

        .page-break {
            page-break-after: always;
        }


        @page {
            margin-top: 160px
        }

        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 94px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 70px;
        }

        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
        }
    </style>
</head>
<body>

@php
    $target_amount = $target_amount;
     $period = $investment_period;
     $period_in_months = $period * 12;

     $investment_type = $investment_type;
     $investment_amount = $investment_amount;

     $sip_interest_rate = $sip_interest_rate;
     $monthly_sip_interest_rate = (1+$sip_interest_rate/100)**(1/12)-1;

     $onetime_interest_rate = $onetime_interest_rate;
     $monthly_onetime_interest_rate = (1+$onetime_interest_rate/100)**(1/12)-1;

     if ($investment_type == "SIP") {
         $lumpsum_investment_amount = $investment_amount;
         $lumpsum_future_value = $investment_amount*(1+$monthly_onetime_interest_rate)**$period_in_months ;
         $required_sip_future_value = $target_amount - $lumpsum_future_value;
         $required_sip = ($required_sip_future_value * $monthly_sip_interest_rate) / ((1 + $monthly_sip_interest_rate) * (pow((1 + $monthly_sip_interest_rate), ($period_in_months)) - 1));
     }
     if ($investment_type == "lumpsum") {
         $sip_amount = $investment_amount;
         //(1+AR32)*Q12*(((1+AR32)^(AR31)-1)/AR32)
         $sip_future_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($period_in_months)-1)/$monthly_sip_interest_rate);
         $required_lumpsum_future_value = $target_amount - $sip_future_value;
         //AR36/(1+AR33)^AR31
         $required_onetime_investment = $required_lumpsum_future_value/(1+$monthly_onetime_interest_rate)**$period_in_months;
     }
@endphp

<main style="width: 760px; margin-left: 20px;">
    <SALESPRESENTER_BEFORE/>
    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">SIP + Lumpsum @if(isset($clientname)) Proposal <br>For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
    <table>
        <tbody>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>Target Amount</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($target_amount)}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>@if ($investment_type == "SIP") Lumpsum Investment @else SIP Amount @endif</strong></strong>
            </td>
            <td style="text-align: left;Width:50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($investment_amount)}}
            </td>
        </tr>

        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>Period</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                {{$investment_period?$investment_period:0}} Years
            </td>
        </tr>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>Assumed Rate of Return</strong>
            </td>
            <td style="text-align: left;Width:50%;padding: 0">
                <table style="width: 100%;">
                    <tbody>
                    @if ($investment_type == "lumpsum")
                    <tr>
                        <td style="text-align: left;Width:50%;">SIP</td>
                        <td style="text-align: left;Width:50%;">{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">Lumpsum</td>
                        <td style="text-align: left;Width:50%;">{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                    </tr>
                    @else
                        <tr>
                            <td style="text-align: left;Width:50%;">Lumpsum</td>
                            <td style="text-align: left;Width:50%;">{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                        </tr>
                        <tr>
                            <td style="text-align: left;Width:50%;">SIP</td>
                            <td style="text-align: left;Width:50%;">{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">
            @if ($investment_type == "SIP") Monthly SIP Required @else Lumpsum Investment Required @endif
        </h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: center;">
                    <strong>
                        @if ($investment_type == "SIP")
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_sip)}}
                        @else
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_onetime_investment)}}
                        @endif
                    </strong>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP/Lumpsum_Investment_Required_for_Target_Future_Value')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
        @php } @endphp
    
    @include('frontend.calculators.common.footer')

@if(isset($report) && $report=='detailed')
        <div class="page-break"></div>

        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
            Annual Investment & Yearwise Projected Value
        </h1>
    <table>
        <tbody>

        <tr>
            <th>Year</th>
            <th>Annual Investment</th>
            <th>Cumulative Investment</th>
            <th style="width: 20%">SIP Fund Value</th>
            <th>Lumpsum Fund Value</th>
            <th style="width: 20%">Total Fund Value</th>
        </tr>

        @php
            $cumulative_investment = 0;
        @endphp

        @php
            $cumulative_investment = 0;
        @endphp


        @if ($investment_type == "SIP")
            @for ($i = 1; $i <= $investment_period; $i++)
                @php
                    $annual_investment = ($required_sip * 12);
                            if ($i == 1) {
                                $annual_investment = $lumpsum_investment_amount + ($required_sip * 12);
                            }

                            $cumulative_investment = $lumpsum_investment_amount + (($required_sip * 12) * $i);

                            $sip_value = (1+$monthly_sip_interest_rate)*$required_sip*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                            $lumpsum_value = $lumpsum_investment_amount*(1+$monthly_onetime_interest_rate)**($i*12);

                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_value)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                </tr>

                @if($i%25==0 && $investment_period>25 && $investment_period>$i)
                        </tbody>
                    </table>
                        @include('frontend.calculators.common.footer')
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th>
                                <th>Annual Investment</th>
                                <th>Cumulative Investment</th>
                                <th style="width: 20%">SIP Fund Value</th>
                                <th>Lumpsum Fund Value</th>
                                <th style="width: 20%">Total Fund Value</th>
                            </tr>
                            @endif
            @endfor

        @else
            @for ($i = 1; $i <= $investment_period; $i++)
                @php
                    $annual_investment = ($sip_amount * 12);
                                if ($i == 1) {
                                    $annual_investment = $required_onetime_investment + ($sip_amount * 12);
                                }

                                $cumulative_investment = $required_onetime_investment + (($sip_amount * 12) * $i);
                                $sip_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                $lumpsum_value = $required_onetime_investment*(1+$monthly_onetime_interest_rate)**($i*12);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_value)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $lumpsum_value)}}</td>
                </tr>


                @if($i%25==0 && $investment_period>25 && $investment_period>$i)
                        </tbody>
                    </table>
                    @include('frontend.calculators.common.footer')
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th>
                                <th>Annual Investment</th>
                                <th>Cumulative Investment</th>
                                <th style="width: 20%">SIP Fund Value</th>
                                <th>Lumpsum Fund Value</th>
                                <th style="width: 20%">Total Fund Value</th>
                            </tr>
                            @endif
            @endfor
        @endif



        </tbody>
    </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP/Lumpsum_Investment_Required_for_Target_Future_Value')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        @include('frontend.calculators.common.footer')

@endif
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>
