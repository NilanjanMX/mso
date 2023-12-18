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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Future Value Of Lumpsum + SIP @if(isset($clientname)) Proposal <br>For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
    <table>
        <tbody>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>SIP Amount</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_amount)}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>Lumpsum Investment</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment)}}
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
                    <tr>
                        <td style="text-align: left;Width:50%;">SIP</td>
                        <td style="text-align: left;Width:50%;">{{$expected_rate_of_return1?number_format($expected_rate_of_return1, 2, '.', ''):0}} %</td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">Lumpsum</td>
                        <td style="text-align: left;Width:50%;">{{$expected_rate_of_return2?number_format($expected_rate_of_return2, 2, '.', ''):0}} %</td>
                    </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    @if(isset($note) && $note!='')
                        <h5 class="text-center">Comments</h5>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered text-center">
                            <tbody><tr>
                                <td style="width: 50%;">
                                    <strong>{{$note}}</strong>
                                </td>
                            </tr>
                            </tbody></table>
                        </div>
                    @endif
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Expected Future Value</h1>
        <table class="table table-bordered text-center">
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">SIP Fund Value</td>
                <td style="text-align: left;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_fund_value)}}</td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">Lumpsum Fund Value</td>
                <td style="text-align: left;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_fund_value)}}</td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">Total Fund Value</td>
                <td style="text-align: left;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_fund_value)}}</td>
            </tr>
            </tbody>
        </table>

    </div>
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Lumpsum_+_SIP')->first();
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
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_end_value)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_end_value)}}</td>
                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_end_value+$lumpsum_end_value)}}</td>
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

        </tbody>
    </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_Lumpsum_+_SIP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        @include('frontend.calculators.common.footer')

@endif
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>
