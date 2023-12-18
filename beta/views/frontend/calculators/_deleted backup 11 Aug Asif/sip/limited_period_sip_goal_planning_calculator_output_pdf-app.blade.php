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
            padding: 6px 20px;
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
    $total_number_of_months = $sip_period*12;
        //(1+Q11%)^(1/12)-1
        $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
        //Q8/((1+Q12%)^Q10)
        $senario1_fund_amount = $amount/(pow((1+$interest1/100),$deferment_period)) ;
        //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
        $senario1_monthly_sip_amount = ($senario1_fund_amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),$total_number_of_months)-1));
        //AV36*AV31
        $senario1_totalinvestment = $senario1_monthly_sip_amount*$total_number_of_months;

        $senario2_amount = 0;
         if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
            //Q8/((1+Q12%)^Q10)
            $senario2_fund_amount = $amount/(pow((1+$interest2/100),$deferment_period)) ;
            //(AV34*AV32)/((1+AV32)*((1+AV32)^(AV31)-1))
            $senario2_monthly_sip_amount = ($senario2_fund_amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),$total_number_of_months)-1));
            //AV36*AV31
            $senario2_totalinvestment = $senario2_monthly_sip_amount*$total_number_of_months;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Limited Period
            SIP @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Target Amount</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <strong>SIP Period</strong>
                </td>
                <td style="text-align: left;">
                    {{$sip_period?$sip_period:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;">
                    <strong>Deferment Period</strong>
                </td>
                <td style="text-align: left;">
                    {{$deferment_period?$deferment_period:0}} Years
                </td>
            </tr>
            @if(!isset($interest2))
                <tr>
                    <td style="text-align: left;">
                        <strong>Assumed Rate of Return </strong>
                    </td>
                    <td style="text-align: left;">
                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                    </td>
                </tr>
            @endif

            </tbody>
        </table>

    </div>
    @if(!isset($interest2))
        <div style="padding: 0 20%;">
    @endif
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Monthly SIP
        Required</h1>
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))

            <tr>
                <th>
                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                </th>
                <th>
                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                </th>
            </tr>
            <tr>
                <td>
                    <strong><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_sip_amount)}}
                    </strong>
                </td>
                <td>
                    <strong><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_monthly_sip_amount)}}
                    </strong>
                </td>
            </tr>
        @else
            <tr>
                <td >
                    <strong>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_sip_amount)}}
                    </strong>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    @if(!isset($interest2))
        </div>
    @endif
    @if(!isset($interest2))
        <div style="padding: 0 20%;">
    @endif
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Total
        Investment</h1>
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))

            <tr>
                <th>
                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                </th>
                <th>
                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                </th>
            </tr>
            <tr>
                <td>
                    <strong><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_totalinvestment)}}
                    </strong>
                </td>
                <td>
                    <strong><span
                                style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_totalinvestment)}}
                    </strong>
                </td>
            </tr>
        @else
            <tr>
                <td>
                    <strong>
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_totalinvestment)}}
                    </strong>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    @if(!isset($interest2))
        </div>
    @endif
    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Limited_Period_SIP_Goal_Planning_Calculator')->first();
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
            Year-Wise Projected Value
        </h1>
        <table>
            <tbody>
            @if(isset($interest2))
                            <tr>
                                <th rowspan="2" style="vertical-align: middle">Year</th>
                                <th colspan="2">
                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </th>
                                <th colspan="2">
                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                </th>
                            </tr>
                            <tr>
                                <th>Annual Investment</th>
                                <th>Year End Value</th>
                                <th>Annual Investment</th>
                                <th>Year End Value</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $amount;
                                $previous_amount_int2 = $amount;
                            @endphp
                            @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                @php

                                    if ($sip_period>=$i){
                                      //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                      $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                    }else{
                                     //(BF70*(1+BD71)^12)
                                      $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                    }
                                    if ($sip_period>=$i){
                                      $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_sip_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                    }else{
                                     //(BE69*(1+BC70)^12)
                                      $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                    }
                                @endphp

                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}
                                    </td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario2_monthly_sip_amount?custome_money_format($senario2_monthly_sip_amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}
                                    </td>
                                </tr>

                                @if($i%25==0 && $sip_period+$deferment_period>25 && $sip_period+$deferment_period>$i)
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
                                                <th rowspan="2" style="vertical-align: middle">Year</th>
                                                <th colspan="2">
                                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                </th>
                                                <th colspan="2">
                                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                                </th>
                                            </tr>
                                            <tr>
                                                <th>Annual Investment</th>
                                                <th>Year End Value</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value</th>
                                            </tr>
                                    @endif
                        @endfor
            @else
                <tr>
                    <th>Year</th>
                    <th>Annual Investment</th>
                    <th>Year End Value @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</th>
                </tr>
                @php
                    $previous_amount_int1 = $amount;
                @endphp
                            @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                @php

                                    if ($sip_period>=$i){
                                      //(1+BD70)*BB70*(((1+BD70)^(AZ70*12)-1)/BD70)
                                      $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_sip_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                    }else{
                                     //(BF70*(1+BD71)^12)
                                      $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                    }

                                @endphp

                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario1_monthly_sip_amount?custome_money_format($senario1_monthly_sip_amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}
                                    </td>

                                </tr>

                                @if($i%25==0 && $sip_period+$deferment_period>25 && $sip_period+$deferment_period>$i)
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
                                            <th>Year End Value @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</th>
                                        </tr>
                                @endif
                        @endfor
            @endif
            </tbody>
        </table>
        <p style="margin-top: 0;">@php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Limited_Period_SIP_Goal_Planning_Calculator')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp</p>
        @include('frontend.calculators.common.footer')
    @endif
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>
