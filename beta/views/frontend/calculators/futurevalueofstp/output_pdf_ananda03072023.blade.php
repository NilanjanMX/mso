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
          /* font-size: 13px; */
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
        table.small-font td{
            font-size: 12px;
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
<main style="width: 780px; margin-left: 20px;">
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
    @php
        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = pow((1+$debt_fund/100),(1/12))-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_fund/100),(1/12))-1;
        $future_value_of_debt_fund = 0;
        $future_value_of_equity_fund = 0;
        $total_fund_value = 0;
        $irr = 0;
        if($monthly_transfer_mode=='CA'){
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
        }else{
            if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP'){
                //Monthly Switch T8*AC15%
                $monthly_switch = $initial_investment*($fixed_percent/100);
            }else{
                 //Monthly Switch T8*AC15%
                $monthly_switch = $fixed_amount;
            }

            $debt_transfer_amount = $monthly_switch;
                $debt_switch_amount = $monthly_switch;
                $equity_transfer_amount = $monthly_switch;
                $equity_switch_amount = $monthly_switch;
                $debt_balance = 0;
                $equity_eom_value = 0;
                $total_fund_value = 0;
                $irr = 0;

                    for ($j=1;$j<=$number_of_months;$j++){
                            if ($j==1){
                                 $debt_bom_value = $initial_investment;
                            }else{
                                $debt_bom_value = $debt_balance;
                            }

                            // AS80+AS80*AT80
                            $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debit_return;
                            if ($debt_eom_value>=$debt_transfer_amount){
                                $debt_monthly_transfer = $monthly_switch;
                            }else{
                                $debt_monthly_transfer = 0;
                            }
                            if ($debt_switch_amount>0){
                                $debt_switch_amount = $debt_monthly_transfer;
                            }else{
                                $debt_switch_amount =0;
                            }
                            //Balance AU80-AV80
                            $debt_balance = $debt_eom_value - $debt_switch_amount;
                       //Equity Calculation
                        if ($j==1){
                             $equity_bom_value = 0;
                        }else{
                            $equity_bom_value = $equity_eom_value;
                        }
                    // BA80+BA80*BC80+BB80
                      $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return+$debt_switch_amount;
                    //Total Value AW80+BD80
                    $total_value = $debt_balance + $equity_eom_value;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr = (pow(((1+($total_value-$initial_investment)/$initial_investment)),(12/$j))-1);
                    }

                    $future_value_of_debt_fund = $debt_balance;
                    $future_value_of_equity_fund = $equity_eom_value;
                    $total_fund_value = $future_value_of_debt_fund + $future_value_of_equity_fund;
        }
    @endphp

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">STP Investment @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
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
                        @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                            {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                        @else
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($fixed_amount)}}
                        @endif
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
        @if(isset($is_note))
                            <h1 class="midheading">Comments</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>{{$note}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
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

    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_Of_STP')->first();
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
        <h1 style="background-color: #131f55;color:#fff !important;font-size:16px;padding:5px;text-align:center;">Projected Annual Investment Value</h1>
        <table class="small-font">
            <tbody>
            <tr>
                <th>Year</th>
                <th>Debt Fund Value <br>at the beginning <br>of year</th>
                <th>Transfer to<br> Equity every<br> year</th>
                <th>Equity Fund Value<br> at the beginning<br> of year</th>
                <th>Equity Fund Value<br> at the end of year</th>
                <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                <th style="width: 60px;">IRR</th>
            </tr>
            @if($monthly_transfer_mode=='CA')
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
                        <table class="small-font">
                            <tbody>
                            <tr>
                                <th>Year</th>
                                <th>Debt Fund Value <br>at the beginning <br>of year</th>
                                <th>Transfer to<br> Equity every<br> year</th>
                                <th>Equity Fund Value<br> at the beginning<br> of year</th>
                                <th>Equity Fund Value<br> at the end of year</th>
                                <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                                <th style="width: 60px;">IRR</th>
                            </tr>
                    @endif


                @endfor

            @else
                @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP' || isset($fixed_transfer_mode) && $fixed_transfer_mode=='FA')
                    @php
                        $i = 1;
                        $debt_transfer_amount = $monthly_switch;
                        $debt_switch_amount = $monthly_switch;

                        $equity_transfer_amount = $monthly_switch;
                        $equity_switch_amount = $monthly_switch;
                        $ech_counter = 13;
                        $tmp_dbt_fnd_bgyear = 0;
                        $tmp_transfer_to_equity_every_year = 0;
                        $tmp_equity_fund_value_beg_of_year = 0;
                    @endphp

                    @for ($j=1;$j<=$number_of_months;$j++)
                        @php
                            if ($j==1){
                                 $debt_bom_value = $initial_investment;
                            }else{
                                $debt_bom_value = $debt_balance;
                            }

                            if (($ech_counter-12)==$j){
                                    if ($j==1){
                                        $tmp_dbt_fnd_bgyear = $initial_investment;
                                        $tmp_equity_fund_value_beg_of_year = 0;
                                    }else{
                                        $tmp_dbt_fnd_bgyear = $debt_balance;
                                        $tmp_equity_fund_value_beg_of_year = $equity_eom_value;
                                    }
                                }

                            // AS80+AS80*AT80
                            $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debit_return;

                            if ($debt_eom_value>=$debt_transfer_amount){
                                $debt_monthly_transfer = $monthly_switch;
                            }else{
                                $debt_monthly_transfer = 0;
                            }

                            if ($debt_switch_amount>0){
                                $debt_switch_amount = $debt_monthly_transfer;
                            }else{
                                $debt_switch_amount =0;
                            }
                            //Balance AU80-AV80
                            $debt_balance = $debt_eom_value - $debt_switch_amount;

                       //Equity Calculation
                        if ($j==1){
                             $equity_bom_value = 0;
                        }else{
                            $equity_bom_value = $equity_eom_value;
                        }
                    // BA80+BA80*BC80+BB80
                      $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return+$debt_switch_amount;
                    //Total Value AW80+BD80
                    $total_value = $debt_balance + $equity_eom_value;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr = (pow(((1+($total_value-$initial_investment)/$initial_investment)),(12/$j))-1)*100;

                        $tmp_transfer_to_equity_every_year = $tmp_transfer_to_equity_every_year + $debt_switch_amount;
                        @endphp

                        @if($j%12==0)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($tmp_dbt_fnd_bgyear)}}
                                </td>
                                <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($tmp_transfer_to_equity_every_year)}}
                                </td>
                                <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($tmp_equity_fund_value_beg_of_year)}}
                                </td>
                                <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($equity_eom_value)}}
                                </td>
                                <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($debt_balance+$equity_eom_value)}}
                                </td>
                                <td>
                                    {{$irr?number_format((float)$irr, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            @php
                                $ech_counter = $ech_counter+12;
                                $tmp_transfer_to_equity_every_year = 0;

                                $i++;
                            @endphp


                            @if($i%26==0 && $investment_period>26 && $investment_period>$i)
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
                                <table class="small-font">
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
                                    @endif


                        @endif

                    @endfor
                @endif

            @endif
            </tbody>
        </table>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_Of_STP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        Report Date : {{date('d/m/Y')}}
        @include('frontend.calculators.common.footer')
    @endif
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>
