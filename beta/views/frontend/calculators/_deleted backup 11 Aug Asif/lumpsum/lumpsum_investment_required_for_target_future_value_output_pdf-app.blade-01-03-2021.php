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
            text-align: center;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Lumpsum Investment @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
    <table>
        <tbody><tr>
            <td style="width: 50%;text-align: left">
                <strong>Target Amount</strong>
            </td>
            <td style="width: 50%;text-align: left;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;text-align: left;">
                <strong>Investment Period</strong>
            </td>
            <td style="width: 50%;text-align: left;">
                {{$period?$period:0}} Years
            </td>
        </tr>
        @if(!isset($interest2))
            <tr>
                <td style="width:50%;text-align: left">
                    <strong>Assumed Rate of Return </strong>
                </td>
                <td style="width:50%;text-align: left;">
                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                </td>
            </tr>
        @else
            <tr>
                <td style="width: 50%;text-align: left">
                    <strong>Assumed Rate of Return</strong>
                </td>
                <td style="padding:0; width: 50%;">
                    @if(isset($interest2))
                        <table width="100%" style="margin: 0">
                            <tbody>
                            <tr>
                                <td style="width: 50%;text-align: left">
                                    Scenario 1
                                </td>
                                <td style="width: 50%;text-align: left">
                                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;text-align: left;">
                                    Scenario 2
                                </td>
                                <td style="width: 50%;text-align: left;">
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
        @endif
        </tbody></table>
    </div>
    @if(!isset($interest2))
        <div style="padding: 0 20%;">
    @endif
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Initial Investment Required</h1>
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))
            <tr>
                <th style="width: 50%">
                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                </th>
                <th style="width: 50%">
                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                </th>
            </tr>
            <tr>
                <td style="width: 50%">
                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest1/100)), $period)))}} </strong>
                </td>
                <td style="width: 50%">
                    <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest2/100)), $period)))}} </strong>
                </td>
            </tr>
        @else
            <tr>
                <td>
                    <strong>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest1/100)), $period)))}}
                    </strong>
                </td>
            </tr>

        @endif
        </tbody></table>
    @if(!isset($interest2))
        </div>
    @endif
    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
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
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Projected Annual Investment Value</h1>
    <table>
        <tbody>
        @if(isset($interest2))
            <tr>
                <th rowspan="2">Year</th>
                <th colspan="2">Scenario 1  @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
            </tr>
            <tr>
                <th >Annual Investment</th>
                <th >Year End Value</th>
                <th >Annual Investment</th>
                <th>Year End Value</th>
            </tr>
            @php
                $previous_amount_int1 = $amount/pow((1+($interest1/100)), $period);
                $previous_amount_int2 = $amount/pow((1+($interest2/100)), $period);
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
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest1/100)), $period)))}}
                        @else
                            --
                        @endif
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                    <td>
                        @if($i==1)
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest2/100)), $period)))}}
                        @else
                            --
                        @endif
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                </tr>


                @if($i%25==0 && $period>25 && $period>$i)
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
                                    <th rowspan="2">Year</th>
                                    <th colspan="2">Scenario 1  @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
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
                <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
            </tr>
            @php
                $previous_amount_int1 = $amount/pow((1+($interest1/100)), $period);
            @endphp

            @for($i=1;$i<=$period;$i++)
                @php
                    $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $interest1/100);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>
                        @if($i==1)
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($amount/pow((1+($interest1/100)), $period)))}}
                        @else
                            --
                        @endif
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                </tr>


                @if($i%25==0 && $period>25 && $period>$i)
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
                                    <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                </tr>
                                @endif
            @endfor
        @endif
        </tbody>
    </table>
    @php
    $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
    if(!empty($note_data2)){
    @endphp
    {!!$note_data2->description!!}
    @php } @endphp
    
        <p>Report Date : {{date('d/m/Y')}}</p>
        @include('frontend.calculators.common.footer')
@endif

@include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>
