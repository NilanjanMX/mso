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
    $target_amount = $target_amount;
        $period = $investment_period;
        $amount = $amount;
        $sip_or_stp = $sip_or_stp;
        $sip_interest_rate = $sip_interest_rate;
        $debt_interest = $debt_interest;
        $equity_interest = $equity_interest;
        $monthly_transfer_mode = $monthly_transfer_mode;

        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = (1+$debt_interest/100)**(1/12)-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_interest/100),(1/12))-1;
        //Exp rate of return (1+Q16%)^(1/12)-1;
        $rate_of_return = (1+$sip_interest_rate/100)**(1/12)-1;


        if ($sip_or_stp == "sip") {
                //Monthly Appreciation T8*AT41
                $monthly_appreciation = $amount*$monthly_debit_return;
                $future_value_of_debt_fund = $amount;
                //AR37*(((1+AR36)^(AR33)-1)/AR36)
                $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $balance_required = $target_amount - $total_stp_value;

                $monthly_sip_required = ($balance_required*$rate_of_return)/((1+$rate_of_return)*((1+$rate_of_return)**($number_of_months)-1));
            }else{
                $assumed_initial_investment = 1;
                  //Monthly Appreciation T8*AT41
                $monthly_appreciation = $assumed_initial_investment*$monthly_debit_return;

                 $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
                 $balance_required = $target_amount - $sip_value;


                 $future_value_of_debt_fund = $assumed_initial_investment;
                 $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($number_of_months)-1)/$monthly_equity_return);
                $total_stp_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                $required_stp_amount = $balance_required / $total_stp_value;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">SIP + STP @if(isset($clientname)) Proposal <br>For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
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
                    <strong>Period</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$investment_period?$investment_period:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>@if ($sip_or_stp == "sip") STP Investment @else SIP Amount @endif</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                </td>
            </tr>
            @if ($sip_or_stp == "sip")
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Assumed Rate of Return</strong>
                    </td>
                    <td style="text-align: left;Width:50%;padding: 0">
                        <table style="width: 100%;">
                            <tbody>

                            <tr>
                                <td style="text-align: left;Width:50%;">Debt</td>
                                <td style="text-align: left;Width:50%;">{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;Width:50%;">Equity</td>
                                <td style="text-align: left;Width:50%;">{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Assumed Return on SIP</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                    </td>
                </tr>
            @else
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Assumed Return on SIP</strong>
                    </td>
                    <td style="text-align: left;Width:50%;">
                        {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;Width:50%;">
                        <strong>Assumed Rate of Return on STP </strong>
                    </td>
                    <td style="text-align: left;Width:50%;padding: 0">
                        <table style="width: 100%;">
                            <tbody>

                            <tr>
                                <td style="text-align: left;Width:50%;">Debt</td>
                                <td style="text-align: left;Width:50%;">{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                            </tr>
                            <tr>
                                <td style="text-align: left;Width:50%;">Equity</td>
                                <td style="text-align: left;Width:50%;">{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                            </tr>

                            </tbody>
                        </table>
                    </td>
                </tr>

            @endif

            </tbody>
        </table>
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">
            @if ($sip_or_stp == "sip")
                Monthly SIP Required
            @else
                STP Investment Required
            @endif
        </h1>
        <table class="table table-bordered text-center">
            <tbody>
            <tr>
                <td>
                    @if ($sip_or_stp == "sip")
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_required)}}
                            @else
                                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($required_stp_amount)}}
                    @endif
                </td>
            </tr>
            </tbody>
        </table>

    </div>
    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP/STP_Required_for_Target_Future_Value')->first();
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
                <th style="width: 18%">Annual Investment</th>
                <th style="width: 18%">Cumulative Investment</th>
                <th style="width: 18%">SIP Fund Value</th>
                <th style="width: 18%">STP Fund Value</th>
                <th style="width: 18%">Total Fund Value</th>
            </tr>

            @php
                $cumulative_investment = 0;
            @endphp

            @for ($i = 1; $i <= $investment_period; $i++)
                @if ($sip_or_stp == "sip")
                    @php
                        $annual_investment = ($monthly_sip_required * 12);
                            if ($i == 1) {
                                $annual_investment = $amount + ($monthly_sip_required * 12);
                            }

                            $cumulative_investment = $amount + (($monthly_sip_required * 12) * $i);

                        $sip_value = (1+$rate_of_return)*$monthly_sip_required*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);

                        $future_value_of_debt_fund = $amount;
                        //Future Value of Equity Fund AR37*(((1+AR35)^(AR32)-1)/AR35)
                        $future_value_of_equity_fund = $monthly_appreciation*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);

                        //STP Fund Value =AR38+AR39
                        $stp_value = $future_value_of_debt_fund+$future_value_of_equity_fund;
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stp_value)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $stp_value)}}</td>
                    </tr>

                @else
                    @php
                        $annual_investment = ($amount * 12);
                        if ($i == 1) {
                            $annual_investment = $required_stp_amount + ($amount * 12);
                        }
                        $cumulative_investment = $required_stp_amount + (($amount * 12) * $i);
                        $sip_value = (1+$rate_of_return)*$amount*(((1+$rate_of_return)**($i*12)-1)/$rate_of_return);
                        $stp_value = $required_stp_amount + ($required_stp_amount*$monthly_debit_return)*(((1+$monthly_equity_return)**($i*12)-1)/$monthly_equity_return);

                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stp_value)}}</td>
                        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sip_value + $stp_value)}}</td>
                    </tr>

                @endif


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
                <th style="width: 18%">Annual Investment</th>
                <th style="width: 18%">Cumulative Investment</th>
                <th style="width: 18%">SIP Fund Value</th>
                <th style="width: 18%">STP Fund Value</th>
                <th style="width: 18%">Total Fund Value</th>
            </tr>
            @endif
            @endfor




            </tbody>
        </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP/STP_Required_for_Target_Future_Value')->first();
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
