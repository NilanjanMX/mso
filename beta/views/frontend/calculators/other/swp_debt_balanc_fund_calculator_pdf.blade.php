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
            padding: 5px 8px;
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
            padding: 5px 8px;
            font-size: 15px;
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
            /*position: fixed;
            bottom: -10px;
            left: 0px;
            right: 0px;
            height: 50px;*/
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

                <div style="padding: 0 20%;">
                        <h1 style="background:#8edeff; color:#000;margin-bottom:30px !important;text-align:center;font-size:24px !important; padding: 10px;">SWP Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                     <?php
                    if($annuity=='Immediate_Annuity')
                    { ?>

                    <?php }else{ ?>
                        
                        <table style="margin-bottom:15px !important;">
                        <tbody>
                        <?php if(isset($current_age)){ ?>
                        <tr>
                            <td style="text-align:left;width: 60%;">
                                <strong>Age</strong>
                            </td>
                            <td style="width: 40%;">
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="text-align:left;padding-top:3px;padding-bottom:3px;">
                                <strong>Initial Investment</strong>
                            </td>
                            <td style="padding-top:3px;padding-bottom:3px;">
                                 {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Deferment Period</strong>
                            </td>
                            <td>
                                 {{$deferment_period}} Years
                            </td>
                        </tr>
                        </tbody>
                        </table>
                </div>


                        <div style="padding: 0 10%;">
                        <table>
                        <tbody>
                        <tr>
                            <td style="text-align:center;">
                                <strong>Asset Class</strong>
                            </td>
                            <td style="text-align:center;">
                                <strong>Debt</strong>
                            </td>
                            @if($balance)
                            <td style="text-align:center;">
                                <strong>Balance</strong>
                            </td>
                            @endif
                            @if($equity)
                            <td style="text-align:center;">
                                <strong>Equity</strong>
                            </td>
                            @endif
                        </tr>
                        <tr>
                            <td style="text-align:center;"><strong>% Allocation</strong></td>
                            <td>{{number_format($debt,2)}}%</td>
                            @if($balance)
                            <td>{{number_format($balance,2)}}%</td>
                            @endif
                            @if($equity)
                            <td>{{number_format($equity,2)}}%</td>
                            @endif
                        </tr>
                        <tr>
                            <td style="text-align:center;"><strong>Expected Return</strong></td>
                            <td>{{number_format($debt2,2)}}%</td>
                            @if($balance)
                            <td>{{number_format($balance2,2)}}%</td>
                            @endif
                            @if($equity)
                            <td>{{number_format($equity2,2)}}%</td>
                            @endif
                        </tr>
                        
                        </tbody>
                        </table>
                    <?php } ?>

                    <?php
                    if($annuity=='Immediate_Annuity')
                    { ?>

                    <?php }else{ ?>
                        <h1 style="color: #000;margin-bottom:15px!important;margin-top:20px!important;text-align:center;">SWP Period</h1>
                    <?php } ?>
                        
                      <table class="table table-bordered text-center" style="margin-bottom:20 !important;">
                        <tbody>
                        <?php if(isset($current_age)){ ?>
                    <?php
                    if($annuity=='Immediate_Annuity')
                    { ?>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Age</strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>

                    <?php }else{ ?>
                        <tr>
                            <td style="text-align:left;width: 60%;">
                                <strong>Age</strong>
                            </td>
                            <td style="width: 40%;">
                                {{$current_age?$current_age+$deferment_period+1:0}} Years
                            </td>
                        </tr>

                        <?php }}  ?>
                        <?php 
                        if($annuity=='Immediate_Annuity')
                        { ?>
                        <tr>
                            <td style="text-align:left;padding-top:3px;padding-bottom:3px;">
                                <strong>Initial Investment</strong>
                            </td>
                            <td style="padding-top:3px;padding-bottom:3px;">
                                 <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td style="text-align:left;padding-top:3px;padding-bottom:3px;">
                                <strong>Accumulated Fund Value</strong>
                            </td>
                            <td style="padding-top:3px;padding-bottom:3px;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($fund_value)}}
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="text-align:left;">
                                <strong>% Investment in Debt Fund</strong>
                            </td>
                            <td>
                                {{$debt_fund?number_format($debt_fund,2):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>% Investment in Balance Fund</strong>
                            </td>
                            <td>
                                {{$balance_fund?$balance_fund:0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Expected Return (Debt Fund)</strong>
                            </td>
                            <td>
                                {{$expected_return_debt_fund?number_format($expected_return_debt_fund,2):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Expected Return (Balance Fund)</strong>
                            </td>
                            <td>
                                {{$expected_return_balance_fund?number_format($expected_return_balance_fund,2):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Annual Withrawal</strong>
                            </td>
                            <td>
                                {{$annual_withdrawal_precent_investment?number_format($annual_withdrawal_precent_investment,2):0}} %
                            </td>
                        </tr>
                       <tr>
                            <td style="text-align:left;">
                                <strong>SWP Period</strong>
                            </td>
                            <td>
                                {{$swp_period?$swp_period:0}} Years
                            </td>
                        </tr>
                         <tr>
                            <td style="text-align:left;">
                                <strong>Rebalancing Period</strong>
                            </td>
                            <td>
                                {{$periodic_rebalance_period?$periodic_rebalance_period:0}} Years
                            </td>
                        </tr>

                        </tbody></table>
                </div>
                @php
                if($annuity=='Deferred_Annuity')
                {
                @endphp
                

                @php
                }
                @endphp
                @php
                    if($withdrawal=='Monthly')
                    {
                        $mo=12;
                    }elseif($withdrawal=='Quarterly')
                    {
                        $mo=4;
                    }elseif($withdrawal=='Half-Yearly')
                    {
                        $mo=2;
                    }elseif($withdrawal=='Yearly')
                    {
                        $mo=1;
                    }
                @endphp
                <div style="padding: 0 10%;">
                    <table>
                        <tr>
                            <td>
                                <h1 style="color: #000;font-size:14px;margin-bottom:5px !important;margin-top:5px;text-align:center;">Expected Portfolio Return</h1>
                                <div style="color: #000;border:1px black solid;font-size:20px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 5px;padding-bottom:5px;">{{$exp_portfolio_return?$exp_portfolio_return:0}}%</div>
                            </td>
                            <td>
                                <h1 style="color: #000;font-size:14px;margin-bottom:5px !important;margin-top:5px;text-align:center;">{{$withdrawal?$withdrawal:0}} SWP Amount</h1>
                                <div style="color: #000;border:1px black solid;font-size:20px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 5px;padding-bottom:5px;">{{$withdrawal_amount?custome_money_format($withdrawal_amount):0}}</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <h1 style="color: #000;font-size:14px;margin-bottom:5px !important;text-align:center;">Total Annuity Received</h1>

                                <div style="color: #000;border:1px black solid;font-size:20px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 5px;padding-bottom:5px;"> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$withdrawal_amount?custome_money_format($withdrawal_amount*$swp_period*$mo):0}}</div>
                            </td>
                            <td>
                                <h1 style="color: #000;font-size:14px;margin-bottom:5px !important;text-align:center;">Closing Fund Value</h1>
                                <div style="color: #000;border:1px black solid;font-size:20px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 5px;padding-bottom:5px;"> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$closed_val}}</div>
                            </td>                            
                        </tr>
                    </table>
                </div>
                    

                    @php
                    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SWP_Rebalance_Calculator')->first();
                    if(!empty($note_data1)){
                    @endphp
                    {!!$note_data1->description!!}
                    @php } @endphp

                    @include('frontend.calculators.common.footer')
                    <div style="page-break-after: always;"></div>

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
                    <h1 style="color: #000;font-size:16px;margin-bottom:10px !important;text-align:center;">Projected Annual Cash Flow & Fund Value</h1>
                    @php
                    if(isset($deferment_period)){ $deferment_period=$deferment_period; }else{ $deferment_period=0; }
                    @endphp
                    <table>
                        <tbody>
                        <tr>
                            <th style="text-align:center;" rowspan="2"><strong><?php if(isset($current_age)){ $age=$current_age; ?>Age<?php }else{ $age=0; ?>Year<?php } ?></strong></th>
                            <th style="text-align:center;" colspan="2"><strong>Debt Fund</strong></th>
                            <th style="text-align:center;" colspan="2"><strong>Balance Fund</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>Annual Withdrawal</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>End of Year Fund Value</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>Transfer From Balance To Debt Fund</strong></th>
                        </tr>
                        <tr>
                            <th style="text-align:center;"><strong>Beginning of Year</strong></th>
                            <th style="text-align:center;"><strong>End of Year</strong></th>
                            <th style="text-align:center;"><strong>Beginning of Year</strong></th>
                            <th style="text-align:center;"><strong>End of Year</strong></th>
                        </tr>

                    <?php for($i=1;$i<=$swp_period;$i++){ ?>

                    @php
                    if($annuity=='Immediate_Annuity')
                    {
                        $t9=$initial_investment;
                    }else{
                        $t9=$fund_value;
                    }
                    $j14=$debt_fund;
                    $be14=$t9*$j14/100;
                    $t10=$expected_return_debt_fund/100;
                    $t11=$expected_return_balance_fund/100;
                    $g20=$withdrawal_amount;
                    $be15=$t9-$be14;
                    $t23=$periodic_rebalance_period;

                    $at73=pow((1+$t10),(1/$year_value))-1;
                    $au73=$g20;
                    $av73=($au73*(1-pow(1+$at73,-$year_value)))/$at73;
                    if($i==1)
                    {
                        $as73=$be14;
                    }else{
                        $as73=(($as73-$av73)*pow((1+$at73),$year_value))+$be73;
                    }
                    
                    if($i==1)
                    {
                        $ax73=$be15;
                    }else{
                        $ax73=$az73-$be73;
                    }
                   


                    $ay73=pow((1+$t11),(1/$year_value))-1;
                    $az73=$ax73*pow(1+$ay73,$year_value);
                    $bd73=$be15;

                    if($i%$periodic_rebalance_period==0 && $i!=1)
                    {
                        $be73=$az73-$bd73;
                    }else{
                        $be73=0;
                    }
                    
                    $aw73=(($as73-$av73)*pow((1+$at73),$year_value))+$be73;
                    $ba73=$az73-$be73;
                    @endphp
                        <tr>
                            <td>{{$i+$age+$deferment_period}}</td>
                            <td style="text-align:right;">
                            {{custome_money_format($as73)}}</td>
                            <td>
                            @php
                            $j73=$aw73;
                            @endphp
                            {{custome_money_format($aw73)}}
                            </td>
                            <td style="text-align:right;">
                            {{custome_money_format($ax73)}}
                            </td>
                            <td style="text-align:right;">
                            @php
                            $t73=$ba73;
                            @endphp
                            {{custome_money_format($ba73)}}
                            </td>
                            <td style="text-align:right;">
                            {{custome_money_format($au73*$year_value)}}
                            </td>
                            <td style="text-align:right;">
                            {{$closing_bal=custome_money_format($j73+$t73)}}
                            </td>
                            <td style="text-align:right;">{{custome_money_format($be73)}}</td>
                        </tr>
                    


                    @if($i%25==0 && $swp_period>25 && $swp_period>$i)
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
                            <th style="text-align:center;" rowspan="2"><strong><?php if(isset($current_age)){ $age=$current_age; ?>Age<?php }else{ $age=0; ?>Year<?php } ?></strong></th>
                            <th style="text-align:center;" colspan="2"><strong>Debt Fund</strong></th>
                            <th style="text-align:center;" colspan="2"><strong>Balance Fund</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>Annual Withdrawal</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>End of Year Fund Value</strong></th>
                            <th style="text-align:center;" rowspan="2"><strong>Transfer From Balance To Debt Fund</strong></th>
                        </tr>
                        <tr>
                            <th style="text-align:center;"><strong>Beginning of Year</strong></th>
                            <th style="text-align:center;"><strong>End of Year</strong></th>
                            <th style="text-align:center;"><strong>Beginning of Year</strong></th>
                            <th style="text-align:center;"><strong>End of Year</strong></th>
                        </tr>

                    @endif 
                    
                    <?php } ?>
                    </tbody>
                    </table>

                    @php
                    $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SWP_Rebalance_Calculator')->first();
                    if(!empty($note_data2)){
                    @endphp
                    {!!$note_data2->description!!}
                    @php } @endphp
    </div>
   

    @include('frontend.calculators.common.footer')

    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>
