@extends('layouts.frontend')
@section('js_after')

@endsection
@section('content')

    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>
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
            }else{

            }
        }
    @endphp

    {{--<table class="table table-bordered text-center">
        <tr>
            <td colspan="7" align="center">
                Future Value of Debt Fund
            </td>
            <td colspan="4" align="center">
                Future Value of Equity Fund
            </td>
            <td colspan="3" align="center">
                IRR Calculation
            </td>
        </tr>
        <tr>
            <th>Month</th>
            <th>BOM Value</th>
            <th>EOM Value</th>
            <th>Switch</th>
            <th>Balance</th>
            <th>Transfer Amount</th>
            <th>Monthly Transfer</th>
            <th>Month</th>
            <th>BOM Value</th>
            <th>Switch Inn</th>
            <th>EOM Value</th>
            <th>Investment</th>
            <th>Total Value</th>
            <th>IRR</th>
        </tr>
    @php
        $debt_transfer_amount = $monthly_switch;
        $debt_switch_amount = $monthly_switch;

        $equity_transfer_amount = $monthly_switch;
        $equity_switch_amount = $monthly_switch;
    @endphp

    @for ($j=1;$j<=$number_of_months;$j++)
            @php
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
        $irr = (pow(((1+($total_value-$initial_investment)/$initial_investment)),(12/$j))-1)*100;
        @endphp
            <tr>
                <td>{{$j}}</td>
                <td>₹{{custome_money_format($debt_bom_value)}}</td>
                <td>₹{{custome_money_format($debt_eom_value)}}</td>
                <td>₹{{custome_money_format($debt_switch_amount)}}</td>
                <td>₹{{custome_money_format($debt_balance)}}</td>
                <td>₹{{custome_money_format($debt_transfer_amount)}}</td>
                <td>₹{{custome_money_format($debt_monthly_transfer)}}</td>
                <td>{{$j}}</td>
                <td>₹{{custome_money_format($equity_bom_value)}}</td>
                <td>₹{{custome_money_format($debt_switch_amount)}}</td>
                <td>₹{{custome_money_format($equity_eom_value)}}</td>
                <td>₹{{custome_money_format($initial_investment)}}</td>
                <td>₹{{custome_money_format($total_value)}}</td>
                <td>{{$irr?number_format($irr, 2, '.', ''):0}} %</td>
            </tr>
    @endfor
    </table>--}}
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <h5 class="mb-3">STP Investment Planning @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Initial Investment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Monthly Transfer Mode </strong>
                            </td>
                            <td>
                                @if($monthly_transfer_mode=='CA')
                                    Capital Appreciation
                                @else
                                    @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                                        {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                                    @else

                                     @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Period  </strong>
                            </td>
                            <td>
                                {{$investment_period?$investment_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td rowspan="2">
                                <strong>Assumed Rate of Return  </strong>
                            </td>
                            <td rowspan="2">
                                <table width="100%">
                                    <tr>
                                        <td>Debt Fund</td>
                                        <td>
                                            {{$debt_fund?number_format($debt_fund, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Equity Fund</td>
                                        <td>
                                            {{$equity_fund?number_format($equity_fund, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;">Projected Future Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>Debt Fund Value</td>
                            <td>
                                ₹ {{custome_money_format($future_value_of_debt_fund)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Equity Fund Value</td>
                            <td>
                                ₹ {{custome_money_format($future_value_of_equity_fund)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Total Fund Value</td>
                            <td>
                                ₹ {{custome_money_format($total_fund_value)}}
                            </td>
                        </tr>
                        <tr>
                            <td>Annualised Returns</td>
                            <td>
                                {{$irr?number_format($irr*100, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <p style="text-align: left">
                        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                        * Returns are not guaranteed. The above is for illustration purpose only.
                    </p>
                    @if(isset($report) && $report=='detailed')
                    <h5>Projected Annual Investment Value</h5>
                    <table class="table table-bordered text-center" style="background: #fff;">
                        <tbody>
                        <tr>
                            <th>Year</th>
                            <th>Debt Fund Value <br>at the beginning <br>of year</th>
                            <th>Transfer to<br> Equity every<br> year</th>
                            <th>Equity Fund Value<br> at the beginning<br> of year</th>
                            <th>Equity Fund Value<br> at the end of year</th>
                            <th>Total Value at the<br> end of year<br> (Debt+Equity)</th>
                            <th style="width: 45px;">IRR</th>
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
                                            ₹ {{$initial_investment?custome_money_format($initial_investment):0}}
                                        </td>
                                        <td>
                                            ₹ {{$monthly_appreciation?custome_money_format($monthly_appreciation*12):0}}
                                        </td>
                                        <td>
                                            ₹ {{$equity_fund_value_at_the_begining_of_year?custome_money_format($equity_fund_value_at_the_begining_of_year):0}}
                                        </td>
                                        <td>
                                            ₹ {{$equity_fund_value_at_the_end_of_year?custome_money_format($equity_fund_value_at_the_end_of_year):0}}
                                        </td>
                                        <td>
                                            ₹ {{$total_value_at_the_end_of_the_year?custome_money_format($total_value_at_the_end_of_the_year):0}}
                                        </td>
                                        <td>
                                            {{$irr?number_format((float)$irr, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                 @endfor

                        @else
                            @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
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
                                                    ₹ {{custome_money_format($tmp_dbt_fnd_bgyear)}}
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($tmp_transfer_to_equity_every_year)}}
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($tmp_equity_fund_value_beg_of_year)}}
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($equity_eom_value)}}
                                                </td>
                                                <td>
                                                    ₹ {{custome_money_format($debt_balance+$equity_eom_value)}}
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
                                        @endif

                                     @endfor
                                @endif

                             @endif


                        </tbody>
                    </table>
                        <p style="text-align: left">
                            *Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}
                        </p>
                    @endif

                    <a href="#" class="btn btn-primary btn-round">Save</a>
                    <a href="{{route('frontend.futureValueOfSTPOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{url('/')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
