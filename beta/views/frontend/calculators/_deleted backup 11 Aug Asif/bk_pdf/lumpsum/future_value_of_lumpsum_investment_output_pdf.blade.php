<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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

    <h1 style="color: #000;font-size:16px;margin-bottom:20px;text-align:center;">Lumpsum Investment @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
    <table>
        <tbody>
        <tr>
            <td style="text-align: left">
                <strong>Initial Investment</strong>
            </td>
            <td style="text-align: left">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left">
                <strong>Time Period</strong>
            </td>
            <td style="text-align: left">
                {{$period?$period:0}} Years
            </td>
        </tr>
        <tr>
            <td>
                <strong>Expected Rate of Return</strong>
            </td>
            <td style="padding: 0">
                @if(isset($interest2))
                    <table width="100%" style="margin: 0">
                        <tbody>
                        <tr>
                            <td>
                                Scenario 1
                            </td>
                            <td>
                                {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Scenario 2
                            </td>
                            <td>
                                {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        </tbody>
                    </table>

                @else
                    @ {{$interest1?number_format($interest1, 2, '.', ''):0}}% : <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{number_format(($amount*pow((1+($interest1/100)), $period)))}}
                @endif
            </td>
        </tr>
        </tbody></table>
    <h1 style="color: #000;font-size:16px;margin-bottom:20px;text-align:center;">Expected Future Value</h1>
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
                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount*pow((1+($interest1/100)), $period)))}} </strong>
                </td>
                <td>
                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount*pow((1+($interest2/100)), $period)))}} </strong>
                </td>
            </tr>
        @else
            <tr>
                <td>
                    @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                </td>
                <td>
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format(($amount*pow((1+($interest1/100)), $period)))}}
                </td>
            </tr>
        @endif
        </tbody></table>

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
    <h1 style="background-color: #131f55;color:#fff;font-size:20px;padding:10px;text-align:center;">Projected Annual Investment Value</h1>
    <table>
        <tbody>
        @if(isset($interest2))
            <tr>
                <th>Year</th>
                <th>Annual Investment</th>
                <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                <th>Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
            </tr>
            @php
                $previous_amount_int1 = $amount;
                $previous_amount_int2 = $amount;
            @endphp

            @for($i=1;$i<=$period;$i++)
                @php
                    $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $interest1/100);
                    $previous_amount_int2 = $previous_amount_int2+ ($previous_amount_int2* $interest2/100);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>
                        @if($i==1)
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                        @else
                            --
                        @endif
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                </tr>
            @endfor
        @else
            <tr>
                <th>Year</th>
                <th>Annual Investment</th>
                <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
            </tr>
            @php
                $previous_amount_int1 = $amount;
            @endphp

            @for($i=1;$i<=$period;$i++)
                @php
                    $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $interest1/100);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>
                        @if($i==1)
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                        @else
                            --
                        @endif
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                </tr>
            @endfor
        @endif
        </tbody>
    </table>
        <footer>
            <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
        </footer>
@endif
</main>
</body>
</html>
