<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Result</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        @include('frontend.calculators.common.pdf_style')
    </head>
    <body class="styleApril">
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
    @include('frontend.calculators.common.header')
        
        <main style="width: 806px;">
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">SIP + STP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
                <div class="roundBorderHolder">

                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td style="width: 50%">
                                <strong>Target Amount</strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($target_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>Period</strong>
                            </td>
                            <td>
                                {{$investment_period?$investment_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%">
                                <strong>
                                    @if ($sip_or_stp == "sip") STP Investment @else SIP Amount @endif
                                </strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        @if ($sip_or_stp == "sip")
                            <tr>
                                <td style="width: 50%; vertical-align: middle;">
                                    <strong>Assumed Rate of Return</strong>
                                </td>
                                <td style="padding: 0px;">
                                    <table style="width: 100%;">
                                        <tbody>

                                        <tr>
                                            <td>Debt</td>
                                            <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                        </tr>
                                        <tr>
                                            <td>Equity</td>
                                            <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Assumed Return on SIP</strong>
                                </td>
                                <td>
                                    {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td style="width: 50%">
                                    <strong>Assumed Return on SIP</strong>
                                </td>
                                <td>
                                    {{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%; vertical-align: middle;">
                                    <strong>Assumed Rate of Return on STP</strong>
                                </td>
                                <td style="padding: 0px;">
                                    <table style="width: 100%;">
                                        <tbody>

                                        <tr>
                                            <td>Debt</td>
                                            <td>{{$debt_interest?number_format($debt_interest, 2, '.', ''):0}} %</td>
                                        </tr>
                                        <tr>
                                            <td>Equity</td>
                                            <td>{{$equity_interest?number_format($equity_interest, 2, '.', ''):0}} %</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                        @endif

                        </tbody>
                    </table>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">
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
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_required)}}
                                @else
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($required_stp_amount)}}
                                @endif
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <p style="text-align: left; margin-top: 20px;">
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        * Returns are not guaranteed. The above is for illustration purpose only.
                    </p>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Annual Investment & Yearwise Projected Value</h1>
                    <table class="table table-bordered text-center" style="background: #fff;">
                        <tbody>
                        <tr>
                            <th style="vertical-align: middle;">Year</th>
                            <th style="vertical-align: middle;">Annual Investment</th>
                            <th style="vertical-align: middle;">Cumulative Investment</th>
                            <th style="vertical-align: middle;">SIP Fund Value</th>
                            <th style="vertical-align: middle;">STP Fund Value</th>
                            <th style="vertical-align: middle;">Total Fund Value</th>
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

                        @endfor


                        </tbody>
                    </table>
                    <p style="text-align: left; margin-top: 20px;">
                        *The above chart is approximate and for illustration purpose only
                    </p>
                </div>
            </div>
            
            @if($is_note)
                <div style="padding: 0 0%;">
                    <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Comment</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                                <tr>
                                    <td>{{$note}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP/STP_Required_for_Target_Future_Value')->first();
            @endphp
            @if(!empty($note_data1))
                {!!$note_data1->description!!}
            @endif
            Report Date : {{date('d/m/Y')}}
            
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
        @include('frontend.calculators.suggested.pdf')
        </main>
    </body>
</html>