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
            height: 50px;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Insurance vs. Term Cover With Annual SIP Comparison @if(isset($clientname)) For {{$clientname?$clientname:''}} @else  @endif</h1>
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Insurance</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Annual Premium</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($insurance_policy_annual_premium)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Sum Assured / Death Benefit</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sum_assured)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Policy Term</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$policy_term?$policy_term:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Rate Of Return</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$rate_of_return_insurance?number_format($rate_of_return_insurance, 2, '.', ''):0}} %
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Expected Maturity Value</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_fund_value_insurance)}}
                </td>
            </tr>
            </tbody>
        </table>

        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Term Cover + Monthly SIP</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Sum Assured / Death Benefit</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sum_assured)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Term Policy Premium</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($equivalent_insurance_term_policy_premium)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Monthly SIP Amount</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Total Annual Outlay</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($insurance_policy_annual_premium)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Time Period</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$policy_term?$policy_term:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Rate Of Return</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$rate_of_return_investments?number_format($rate_of_return_investments, 2, '.', ''):0}} %
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Expected Fund Value</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($total_fund_value_investment)}}
                </td>
            </tr>
            </tbody>
        </table>
    </div>


    <p>
        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
        *Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}
    </p>
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
        Yearwise Projected Value
    </h1>
    <table>
        <tbody>
        <tr>
            <th>Year</th>
            <th>Annual Investment</th>
            <th>Life Cover</th>
            <th>Year End Value <br>@ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
            <th>Risk Cover + Fund Value<br>(In case of Death)</th>
        </tr>
        @for($i=1;$i<=$term_insurance_period;$i++)
            @php
                //Year End Value (1+AV66)*AT66*(((1+AV66)^(AU66*12)-1)/AV66)
                $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                //Risk Cover N66+V66
                $risk_cover_fund_value = $term_insurance_sum_assured+$year_end_value;
            @endphp
            <tr>
                <td>{{$i}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_amount*12)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($term_insurance_sum_assured)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($risk_cover_fund_value)}}</td>
            </tr>
            @if($i%25==0 && $term_insurance_period>25  && $term_insurance_period>$i)
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
                            <th>Life Cover</th>
                            <th>Year End Value <br>@ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                            <th>Risk Cover + Fund Value<br>(In case of Death)</th>
                        </tr>
                        @endif
        @endfor

        </tbody>
    </table>
    <p>
        *Returns are not guaranteed. The above  is  for illustration purpose only. Report Date : {{date('d/m/Y')}}
    </p>
        @include('frontend.calculators.common.footer')
@endif
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>