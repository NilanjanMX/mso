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

    //Annuity Period (Months) T9*12
    $annuity_period_months = $period*12;
    //Monthly Rate of Return (1)  (1+T11%)^(1/12)-1
    $monthly_rate_of_return1 = pow((1+$interest1/100),(1/12))-1 ;
    //Lumpsum For Balance (1) X32/(1+AV29)^AV28
    $lumsum_for_balance1 = $balance_required/(1+$monthly_rate_of_return1)**$annuity_period_months;
    //Lumpsum For Annuity (1) (X28*(1-(1+AV29)^(-AV28)))/AV29
    $lumsum_for_annuity_1 = ($required_monthly_annuity*(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months)))/$monthly_rate_of_return1;
    //Lumpsum Investment Required (1) AV31+AV33
    $lumpsum_investment_required_1 = $lumsum_for_balance1+$lumsum_for_annuity_1;
    if (isset($interest2)){
    //Monthly Rate of Return (2)  (1+T12%)^(1/12)-1
    $monthly_rate_of_return2 = pow((1+$interest2/100),(1/12))-1 ;
    //Lumpsum For Balance (2) X32/(1+AV29)^AV28
    $lumsum_for_balance2 = $balance_required/(1+$monthly_rate_of_return2)**$annuity_period_months;
    //Lumpsum For Annuity (2) (X28*(1-(1+AV29)^(-AV28)))/AV29
    $lumsum_for_annuity_2 = ($required_monthly_annuity*(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months)))/$monthly_rate_of_return2;
    //Lumpsum Investment Required (2) AV31+AV33
    $lumpsum_investment_required_2 = $lumsum_for_balance2+$lumsum_for_annuity_2;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Monthly Annuity Planning @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Target Monthly Annuity</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Annuity Period</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$period?$period:0}} Years
                </td>
            </tr>
            @if(!isset($interest2))
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Assumed Rate of Return </strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                    </td>
                </tr>
            @else
                <tr>
                    <td style="text-align: left;width: 50%;">
                        <strong>Assumed Rate of Return</strong>
                    </td>
                    <td style="padding: 0;text-align: left;width: 50%;">
                        @if(isset($interest2))
                            <table width="100%" style="margin: 0">
                                <tbody>
                                <tr>
                                    <td style="text-align: left;width: 50%;">
                                        Scenario 1
                                    </td>
                                    <td style="text-align: left;width: 50%;">
                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;width: 50%;">
                                        Scenario 2
                                    </td>
                                    <td style="text-align: left;width: 50%;">
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
            @if(isset($balance_required) && $balance_required>0)
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Balance Required</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_required)}}
                    </td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
    

    @if(!isset($interest2))
        <div style="padding: 0 20%;">
            @endif
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Initial Investment Required</h1>
            <table class="table table-bordered text-center">
                <tbody>
                @if(isset($interest2))
                    <tr>
                        <th style="width: 50%;">
                            Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                        </th>
                        <th style="width: 50%;">
                            Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                        </th>
                    </tr>
                    <tr>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required_1)}} </strong>
                        </td>
                        <td style="width: 50%;">
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsum_investment_required_2)}} </strong>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>
                            <strong>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($lumpsum_investment_required_1)}}
                            </strong>
                        </td>
                    </tr>
                @endif
                </tbody></table>

            @if(!isset($interest2))
        </div>
    @endif

    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumpsum_Investment_Required_For_Target_Monthly_Annuity')->first();
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
            Annual Wihdrawal & Projected Investment Value
        </h1>
        <table>
            <tbody>
            @if(isset($interest2))
                <tr>
                    <th rowspan="2">Year</th>
                    <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                </tr>
                <tr>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                </tr>

                @for($i=1;$i<=$period;$i++)
                    @php
                        //Year End Value 1 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                        $year_end_value_1 = ($lumpsum_investment_required_1*(1+$monthly_rate_of_return1)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                        //Year End Value 2 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                        $year_end_value_2 = ($lumpsum_investment_required_2*(1+$monthly_rate_of_return2)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_1)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_2)}}</td>
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
                <th colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                <th colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
            </tr>
            <tr>
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
            </tr>
            @endif
            @endfor
            @else
                <tr>
                    <th>Year</th>
                    <th>Monthly Annuity</th>
                    <th>Year End Balance</th>
                </tr>
                @for($i=1;$i<=$period;$i++)
                    @php
                        //Year End Value 1 (AS69*(1+AU69)^(AR69*12)-(AW69*((1+AU69)^(AR69*12)-1)/AU69))
                        $year_end_value_1 = ($lumpsum_investment_required_1*(1+$monthly_rate_of_return1)**($i*12)-($required_monthly_annuity*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_monthly_annuity)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value_1)}}</td>
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
                <th>Monthly Annuity</th>
                <th>Year End Balance</th>
            </tr>
            @endif

            @endfor
            @endif
            </tbody>
        </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumpsum_Investment_Required_For_Target_Monthly_Annuity')->first();
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