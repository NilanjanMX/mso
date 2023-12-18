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
        //Numbers of month
        $number_of_months = $investment_period*12;
        //Monthly Debt return (1+T11%)^(1/12)-1
        $monthly_debit_return = pow((1+$debt_fund/100),(1/12))-1;
         //Monthly Equity return (1+T12%)^(1/12)-1
        $monthly_equity_return = pow((1+$equity_fund/100),(1/12))-1;
        //Assumed Initial Investment
        $assumed_initial_investment = 1;


        $future_value_of_debt_fund = 0;
        $future_value_of_equity_fund = 0;
        $debt_fund_value = 0;
        $equity_fund_value = 0;
        $total_fund_value = 0;

        $monthly_appreciation2 = 0;
        $assumed_initial_investment_required = 0;
        $irr = 0;

        $initial_investment = 0;

        if($monthly_transfer_mode=='CA'){
             //Monthly Appreciation T8*AT41
            $monthly_appreciation = $assumed_initial_investment*$monthly_debit_return;

            //Future Value of Debt Fund
            $future_value_of_debt_fund = $assumed_initial_investment;
            //Future Value of Equity Fund  AT44*(((1+AT43)^(AT41)-1)/AT43)
            $future_value_of_equity_fund = $monthly_appreciation*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);
            //Total Fund Value AT44+AT45
            $total_fund_value = $future_value_of_debt_fund+$future_value_of_equity_fund;

            //Initial Investment Required T8/AT47
            $assumed_initial_investment_required = $target_amount/$total_fund_value;
            //Debt Fund Value
            $debt_fund_value = $assumed_initial_investment_required;
            //Monthly Appreciation AT48*AT42
            $monthly_appreciation2 = $assumed_initial_investment_required*$monthly_debit_return;
            //Equity Fund Value AT49*(((1+AT43)^(AT41)-1)/AT43)
            $equity_fund_value = $monthly_appreciation2*((pow((1+$monthly_equity_return),$number_of_months)-1)/$monthly_equity_return);

            //Total Fund Value
            $total_fund_value = $debt_fund_value + $equity_fund_value;
            //IRR (AT52/AT48)^(1/T9)-1
            $irr = pow(($total_fund_value/$assumed_initial_investment_required),(1/$investment_period))-1;
        }else{

            if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP'){
                //Monthly Switch T8*AC15%
                $monthly_switch = $assumed_initial_investment*($fixed_percent/100);
            }else{
                 //Monthly Switch T8*AC15%
                $monthly_switch = $fixed_amount;
            }


       //
        $debt_transfer_amount = $monthly_switch;
        $debt_switch_amount = $monthly_switch;
        $equity_transfer_amount = $monthly_switch;
        $equity_switch_amount = $monthly_switch;

   for ($j=1;$j<=$number_of_months;$j++){

            if ($j==1){
                 $debt_bom_value = $assumed_initial_investment;
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
    $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
    }

                    $future_value_of_debt_fund = $debt_balance;
                    $future_value_of_equity_fund = $equity_eom_value;
                    $total_fund_value = $future_value_of_debt_fund + $future_value_of_equity_fund;

                    $initial_investment = $target_amount/$total_fund_value;
                    $assumed_initial_investment_required = $initial_investment;
                    //monthly Switch AY41*AC15%
                    $monthly_switch = $initial_investment*($fixed_percent/100);


                    /////////////////////////////////////////////////////////////////

                    $debt_transfer_amount = $monthly_switch;
                    $debt_switch_amount = $monthly_switch;

                    $equity_transfer_amount = $monthly_switch;
                    $equity_switch_amount = $monthly_switch;


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
    $debt_fund_value = $future_value_of_debt_fund;
    $equity_fund_value = $future_value_of_equity_fund;
   }
    @endphp

@include('frontend.calculators.common.header')
        
<main style="width: 806px;">
    <div style="padding: 0 0%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">STP Investment @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="text-align: left;width: 50%;">
                        <strong>Target Amount</strong>
                    </td>
                    <td style="text-align: left;width: 50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($target_amount)}}
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
                        <strong> Expected Rate of Return</strong>
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
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">
                Initial Investment Required</h1>
            <table>
                <tbody>
                <tr>
                    <td style="text-align: center;">
                        <strong><span
                                    style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($assumed_initial_investment_required)}}
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">
                Expected Future Value</h1>
            <table>
                <tbody>
                <tr>
                    <td style="text-align: left;width: 50%;">Debt Fund Value</td>
                    <td style="text-align: left;width: 50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($debt_fund_value)}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;width: 50%;">Equity Fund Value</td>
                    <td style="text-align: left;width: 50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($equity_fund_value)}}
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

    @if(isset($report) && $report=='detailed')
    <div style="padding: 0 0%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">STP Investment @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <div class="roundBorderHolder">
        <table>
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
                @for($j=1;$j<=$investment_period;$j++)
                    @php
                        if ($j==1){
                            $equity_fund_value_begining_of_year = 0;
                            }else{
                            $equity_fund_value_begining_of_year = $equity_fund_eoy;
                            }
                        //Equity Fund EOY AU78*(((1+AW78)^(AR78*12)-1)/AW78)
                        $equity_fund_eoy = $monthly_appreciation2*((pow((1+$monthly_equity_return),($j*12))-1)/$monthly_equity_return);
                        $total_value_eoy = $assumed_initial_investment_required + $equity_fund_eoy;
                        //IRR
                        $irr = (pow(($total_value_eoy/$assumed_initial_investment_required),(1/$j))-1)*100;
                    @endphp

                    <tr>
                        <td> {{$j}}</td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($assumed_initial_investment_required)}}
                        </td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_appreciation2*12)}}
                        </td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($equity_fund_value_begining_of_year)}}
                        </td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($equity_fund_eoy)}}
                        </td>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_value_eoy)}}
                        </td>
                        <td>{{$irr?number_format((float)$irr, 2, '.', ''):0}} %</td>
                    </tr>

                    @if($j%25==0 && $investment_period>25 && $investment_period>$j)
            </tbody>
        </table>
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
                <th>Debt Fund Value <br>at the beginning <br>of year</th>
                <th>Transfer to<br> Equity every<br> year</th>
                <th>Equity Fund Value<br> at the beginning<br> of year</th>
                <th>Equity Fund Value<br> at the end of year</th>
                <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                <th style="width: 50px;">IRR</th>
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
        </div>
    </div>
    @endif
    
    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','STP_Required_For_Target_Future_Value')->first();
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