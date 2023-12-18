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
            padding: 6px 15px;
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
    </style>
</head>
<body>
<main style="width: 770px; margin-left: 20px;">

    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;"
                                                                                          src="http://myvtd.site/html/masterstroke/images/logo.png"
                                                                                          alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>
    @php
        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = pow((1+$debt_fund/100),(1/12))-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_fund/100),(1/12))-1;
        //Monthly Appreciation T8*AT41
        $monthly_appreciation = $initial_investment*$monthly_debit_return;
        //Future Value of Debt Fund
        $future_value_of_debt_fund = $initial_investment;
        //Future Value of Equity Fund  AT43*(((1+AT42)^(AT40)-1)/AT42)
        $future_value_of_equity_fund = $monthly_appreciation*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);
        //Total Fund Value AT44+AT45
        $total_fund_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
        //IRR (AT46/T8)^(1/T9)-1
        $irr = pow(($total_fund_value/$initial_investment),(1/$investment_period))-1;
    @endphp

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">STP Investment Planning @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Initial Investment</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Monthly Transfer Mode</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    @if($monthly_transfer_mode=='CA')
                        Capital Appreciation
                    @else

                    @endif
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Period</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    {{$investment_period?$investment_period:0}} Years
                </td>
            </tr>
            <tr>
                <td rowspan="2" style="text-align: left;width: 50%; vertical-align: middle;">
                    <strong> Assumed Rate of Return</strong>
                </td>
                <td rowspan="2" style="text-align: left;width: 50%;padding: 0">
                    <table width="100%">
                        <tr>
                            <td style="text-align: left;width: 50%;">Debt Fund</td>
                            <td style="text-align: left;width: 50%;">
                                {{$debt_fund?number_format($debt_fund, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: left;width: 50%;">Equity Fund</td>
                            <td style="text-align: left;width: 50%;">
                                {{$equity_fund?number_format($equity_fund, 2, '.', ''):0}} %
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            </tbody>
        </table>
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Expected Future Value</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;width: 50%;">Debt Fund Value</td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($future_value_of_debt_fund)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">Equity Fund Value</td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($future_value_of_equity_fund)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">Total Fund Value</td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_fund_value)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">Annualised Returns</td>
                <td style="text-align: left;width: 50%;">
                    {{$irr?number_format($irr*100, 2, '.', ''):0}} %
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <p>
        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
        * Returns are not guaranteed. The above is for illustration purpose only.
    </p>
    <footer>
        <p style="margin-left:-10%;text-align: center;">
            Advisor Name <br>
            Advisor Company Name, +91 988XXXXX27
        </p>
    </footer>
    @if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png"  alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Projected Annual Investment Value</h1>
        <table>
            <tbody>
            <tr>
                <th>Year</th>
                <th>Debt Fund Value <br>at the beginning <br>of year</th>
                <th>Transfer to<br> Equity every<br> year</th>
                <th>Equity Fund Value<br> at the beginning<br> of year</th>
                <th>Equity Fund Value<br> at the end of year</th>
                <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                <th style="width: 50px;">IRR</th>
            </tr>
            @for($i=1;$i<=$investment_period;$i++)
                @php
                    if ($i==1){
                            $equity_fund_value_at_the_begining_of_year = 0;
                        }else{
                            $equity_fund_value_at_the_begining_of_year = $equity_fund_value_at_the_end_of_year;
                        }
                    //Equity Fund Value at the end of year AU79*(((1+AW79)^(AR79*12)-1)/AW79)
                    $equity_fund_value_at_the_end_of_year = $monthly_appreciation*((pow((1+$monthly_equity_return),($i*12))-1)/$monthly_equity_return);
                    //AT79+AX79
                    $total_value_at_the_end_of_the_year = $initial_investment+$equity_fund_value_at_the_end_of_year;
                    //IRR (AY79/AS79)^(1/AR79)-1
                    $irr = (pow(($total_value_at_the_end_of_the_year/$initial_investment),(1/$i))-1)*100;
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$initial_investment?custome_money_format($initial_investment):0}}
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$monthly_appreciation?custome_money_format($monthly_appreciation*12):0}}
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$equity_fund_value_at_the_begining_of_year?custome_money_format($equity_fund_value_at_the_begining_of_year):0}}
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$equity_fund_value_at_the_end_of_year?custome_money_format($equity_fund_value_at_the_end_of_year):0}}
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$total_value_at_the_end_of_the_year?custome_money_format($total_value_at_the_end_of_the_year):0}}
                    </td>
                    <td>
                        {{$irr?number_format((float)$irr, 2, '.', ''):0}} %
                    </td>
                </tr>
            @endfor
            </tbody>
        </table>
    @endif

    @if(isset($report) && $report=='detailed22')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;"
                                                                                              src="http://myvtd.site/html/masterstroke/images/logo.png"
                                                                                              alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Projected Annual Investment Value</h1>
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


                    @if($i%25==0 && $period>25 && $period>$i)
                            </tbody>
                        </table>
                        <footer>
                            <p style="margin-left:-10%;text-align: center;">
                                Advisor Name <br>
                                Advisor Company Name, +91 988XXXXX27
                            </p>
                        </footer>
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle"><img
                                                style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png"
                                                alt=""></td>
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
                                <th>Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
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

                    @if($i%25==0 && $period>25 && $period>$i)
            </tbody>
        </table>
        <footer>
            <p style="margin-left:-10%;text-align: center;">
                Advisor Name <br>
                Advisor Company Name, +91 988XXXXX27
            </p>
        </footer>
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img
                                style="display:inline-block;" src="http://myvtd.site/html/masterstroke/images/logo.png"
                                alt=""></td>
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

        <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
        <footer>
            <p style="margin-left:-10%;text-align: center;">
                Advisor Name <br>
                Advisor Company Name, +91 988XXXXX27
            </p>
        </footer>
    @endif
</main>
</body>
</html>
