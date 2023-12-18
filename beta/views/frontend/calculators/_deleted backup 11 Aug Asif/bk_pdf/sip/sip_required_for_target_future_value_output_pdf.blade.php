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
        table th{
            font-weight: bold;
            background: #a9f3ff;
        }
        h1{
            font-size: 18px !important;
        }

        .page-break {
            page-break-after: always;
        }
        @page { margin-top: 110px }
        header { position: fixed; top: -80px; left: 0px; right: 94px; height: 50px; }
        footer { position: fixed; bottom: -20px; left: 0px; right: 0px; height: 50px; }
    </style>
</head>
<body>

@php
    $number_of_months = $period*12;
    //rate1 = (1+Q10%)^(1/12)-1 (Q10 = senario 1)
    $rate1_percent = pow((1+($interest1/100)), (1/12))-1;
    //(Q7*AV33)/((1+AV33)*((1+AV33)^(AV32)-1))
    $senario1_monthly_amount = ($amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),($number_of_months))-1));
    //AV35*AV32
    $senario1_amount = $senario1_monthly_amount*$number_of_months;

    if (isset($interest2)){
        $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
        //(Q7*AV34)/((1+AV34)*((1+AV34)^(AV32)-1))
        $senario2_monthly_amount = ($amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),($number_of_months))-1));
        $senario2_amount = $senario2_monthly_amount*$number_of_months;;


    }

    if(isset($include_step_up) && $include_step_up=='yes'){
        //Step Up
    //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
    $ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
    //(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
    $stepup_senario1_found_value = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($period))-pow((1+$step_up_rate/100),($period)));
    //Q7/AV38
    $stepup_senario1_amount =$amount / $stepup_senario1_found_value;
    //(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
    $stepup_senario1_invest_amount = ($stepup_senario1_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));

        if (isset($interest2)){
            //Step Up
        //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
        $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
        //(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
         $stepup_senario2_found_value = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),($period))-pow((1+$step_up_rate/100),($period)));
        //Q7/AV38
         $stepup_senario2_amount =$amount / $stepup_senario2_found_value;
         //(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
         $stepup_senario2_invest_amount = ($stepup_senario2_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));
        }
    }
@endphp

<main style="width: 794px;">

<header>
    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
        <tbody><tr>
            <td style="text-align:left; border:0;" align="left">&nbsp;</td>
            <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png" alt=""></td>
        </tr>
        </tbody>
    </table>
</header>

    <h1 style="color: #000;font-size:16px;margin-bottom:20px;text-align:center;">SIP @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
    <table class="table table-bordered text-center">
        <tbody>
        <tr>
            <td style="text-align: left">
                <strong>Target Amount</strong>
            </td>
            <td style="text-align: left">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left" >
                <strong>SIP Period  </strong>
            </td>
            <td style="text-align: left">
                {{$period?$period:0}} Years
            </td>
        </tr>
        @if(isset($include_step_up) && $include_step_up=='yes')
            <tr>
                <td style="text-align: left">
                    <strong> Step-Up % Every Year  </strong>
                </td>
                <td style="text-align: left">
                    {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
                </td>
            </tr>
        @endif
        </tbody>
    </table>
    <h1 style="color: #000;font-size:16px;margin-bottom:20px;text-align:center;">Monthly SIP Required</h1>
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th><strong>Mode</strong></th>
                    <th>
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th>
                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td><strong>Normal SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_monthly_amount)}} </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Step-Up SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario2_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <th>
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th>
                        <strong>Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}}
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_monthly_amount)}}
                    </td>
                </tr>
            @endif
        @else
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th><strong>Mode</strong></th>
                    <th>
                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td><strong>Normal SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}} </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Step-Up SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <th>
                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}}
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
    </table>
    <h1 style="color: #000;font-size:16px;margin-bottom:20px;text-align:center;">Total Investment</h1>
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th><strong>Mode</strong></th>
                    <th>
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th>
                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td><strong>Normal SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Step-Up SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario2_invest_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <th>
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th>
                        <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %<strong>
                    </th>
                </tr>
                <tr>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                    </td>
                </tr>
            @endif
        @else
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th><strong>Mode</strong></th>
                    <th>
                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td><strong>Normal SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Step-Up SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <th>
                        <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                </tr>
            @endif
        @endif
        </tbody>
    </table>
        <footer>
        <p>
            * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
            * Returns are not guaranteed. The above is for illustration purpose only.
        </p>
        </footer>


@if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff;font-size:20px;padding:10px;text-align:center;">
            @if(isset($include_step_up) && $include_step_up=='yes')
                Normal SIP <br>
            @endif
            Year-Wise Projectd Value
        </h1>
        <table>
            <tbody>
            @if(isset($interest2))
                <tr>
                    <th style="vertical-align: middle" rowspan="2">Year</th>
                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
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

                @for($i=1;$i<=$period;$i++)
                    @php
                        //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                        $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                        $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario2_monthly_amount?custome_money_format($senario2_monthly_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                    </tr>
                @endfor
            @else
                <tr>
                    <th style="vertical-align: middle" rowspan="2">Year</th>
                    <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                </tr>
                @php
                    $previous_amount_int1 = $amount;
                @endphp

                @for($i=1;$i<=$period;$i++)
                    @php
                        //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                        $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                    </tr>
                @endfor
            @endif
            </tbody>
        </table>
        <footer>
            <p>*The above chart is approximate and for illustration purpose only</p>
        </footer>
        @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody><tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff;font-size:20px;padding:10px;text-align:center;">
            Step - Up SIP<br>Year-Wise Projectd Value
        </h1>
        <table class="table table-bordered text-center" style="background: #fff;">
            <tbody>
            @if(isset($interest2))
                <tr>
                    <th style="vertical-align: middle" rowspan="2">Year</th>
                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                </tr>
                @php
                    //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                    $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                    $ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                    $previous_amount_int1 = $amount;
                    $previous_amount_int2 = $amount;
                    $stepup_senario1_change_amount = $stepup_senario1_amount;
                    $stepup_senario2_change_amount = $stepup_senario2_amount;
                @endphp

                @for($i=1;$i<=$period;$i++)
                    @php

                        if ($i==1){
                            $stepup_senario1_change_amount = $stepup_senario1_amount;
                            $stepup_senario2_change_amount = $stepup_senario2_amount;
                        }else{
                            $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                            $stepup_senario2_change_amount = $stepup_senario2_change_amount+($stepup_senario2_change_amount*$step_up_rate/100);
                        }

                        //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                        $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));
                        $previous_amount_int2 = ($ap2_stepup/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$i)-pow((1+$step_up_rate/100),$i));

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$stepup_senario2_change_amount?custome_money_format($stepup_senario2_change_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$previous_amount_int2?custome_money_format($previous_amount_int2):0}}</td>
                    </tr>
                @endfor
            @else
                <tr>
                    <th style="vertical-align: middle" rowspan="2">Year</th>
                    <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Annual Investment</th>
                    <th>Year End Value</th>
                </tr>
                @php
                    //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                    $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                    //$ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                    $previous_amount_int1 = $amount;
                    //$previous_amount_int2 = $amount;
                    $stepup_senario1_change_amount = $stepup_senario1_amount;
                    //$stepup_senario2_change_amount = $stepup_senario2_amount;
                @endphp

                @for($i=1;$i<=$period;$i++)
                    @php

                        if ($i==1){
                            $stepup_senario1_change_amount = $stepup_senario1_amount;
                        }else{
                            $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                        }

                        //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                        $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                        </td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
                    </tr>
                @endfor
            @endif
            </tbody>
        </table>
        <footer>
            <p>*The above chart is approximate and for illustration purpose only</p>
        </footer>
        @endif

        @if(isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
            <div class="page-break"></div>
            <header>
                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                        <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                        <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png" alt=""></td>
                    </tr>
                    </tbody>
                </table>
            </header>
            <h1 style="background-color: #131f55;color:#fff;font-size:20px;padding:10px;text-align:center;">
                Cost of Delay in Starting Normal SIP
            </h1>
            <table class="table table-bordered text-center" style="background: #fff;">
                <tbody>
                @if(isset($interest2))
                    <tr>
                        <th style="vertical-align: middle" rowspan="2">Year</th>
                        <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                        <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                    </tr>
                    <tr>
                        <th>SIP Amount</th>
                        <th>Total Investment</th>
                        <th>SIP Amount</th>
                        <th>Total Investment</th>
                    </tr>
                    @for($i=1;$i<$period;$i++)
                        @php
                            $year_left = $period-$i;
                            //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                            $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                            $sipamount2 = ($amount*$rate2_percent) / ((1+$rate2_percent)*(pow((1+$rate2_percent),($year_left*12))-1));
                            $totalinvestment1 = $sipamount1*$year_left*12;
                            $totalinvestment2 = $sipamount2*$year_left*12;
                        @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sipamount1?custome_money_format($sipamount1):0}}
                                    </td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sipamount2?custome_money_format($sipamount2):0}}
                                    </td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$totalinvestment2?custome_money_format($totalinvestment2):0}}</td>
                                </tr>
                    @endfor
                @else
                    <tr>
                        <th style="vertical-align: middle" rowspan="2">Year</th>
                        <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    </tr>
                    <tr>
                        <th>SIP Amount</th>
                        <th>Total Investment</th>
                    </tr>
                    @for($i=1;$i<$period;$i++)
                                @php
                                    $year_left = $period-$i;
                                    //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                                    $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                                    $totalinvestment1 = $sipamount1*$year_left*12;
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$sipamount1?custome_money_format($sipamount1):0}}
                                    </td>
                                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
                                </tr>
                    @endfor
                @endif
                <footer>
                    <p>*The above chart is approximate and for illustration purpose only</p>
                </footer>
        @endif
@endif

</main>
</body>
</html>
