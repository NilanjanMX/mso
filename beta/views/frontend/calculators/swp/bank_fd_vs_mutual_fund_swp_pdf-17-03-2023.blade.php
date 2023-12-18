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
            font-size: 16px;
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

                <div style="padding: 0 5%;">

                <h1 style="background:#8edeff; color:#000;margin-bottom:30px !important;text-align:center;font-size:24px !important; padding: 10px;">Bank FD vs Mutual Fund SWP Comparison @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                        

                         <table class="table table-bordered text-center" style="width: 99%;">
                        <tbody>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Initial Investment</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment)}}</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Investment Period</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;">{{$period?$period:0}} Years</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Expected Indexation Rate</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;">{{number_format($assumed_inflation_rate_for_indexation,2)}}%</td>
                        </tr>
                        <tr>
                            <td style="text-align:left;">
                                <strong>Applicable Tax Slab</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;">{{number_format($applicable_short_term_tax_rate,2)}}%</td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                                <strong>Applicable LTCG Tax Rate</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;">{{number_format($applicable_long_term_tax_rate,2)}}%</td>
                        </tr>

                        <tr>
                            <td style="text-align:left;">
                                <strong>LTCG Tax Rate Applicable After</strong>
                            </td>
                            <td colspan="2" style="border: 1px solid #b8b8b8;">{{$for_period_upto}} Years</td>
                        </tr>
                        
                        </tbody>
                    </table>

                    <h2 style="color: #000;font-size:18px;margin-bottom:16px !important;text-align:center;">{{$withdrawal_mode}} SWP Amount</h2>
                    @php

                    if($withdrawal_mode=='Yearly')
                    {
                      $cval=1;
                    }elseif($withdrawal_mode=='Half-Yearly')
                    {
                      $cval=2;
                    }elseif($withdrawal_mode=='Quarterly')
                    {
                      $cval=4;
                    }elseif($withdrawal_mode=='Monthly')
                    {
                      $cval=12;
                    }

                    if($withdrawal_mode=='Yearly')
                    {
                      $div=12;
                    }elseif($withdrawal_mode=='Half-Yearly')
                    {
                      $div=6;
                    }elseif($withdrawal_mode=='Quarterly')
                    {
                      $div=3;
                    }elseif($withdrawal_mode=='Monthly')
                    {
                      $div=1;
                    }

                    $t8=$initial_investment;
                    $t11=$fixed_deposit/100;
                    $t12=$debt_fund/100;
                    $t14=$assumed_inflation_rate_for_indexation/100;
                    $av33=pow((1+$t11),(1/$cval))-1;
                    $av34=pow((1+$t12),(1/$cval))-1;
                    $av35=$t8*$av33;
                    $av36=$t8*$av34;
                    $bg60=1;
                    @endphp
                   <table class="table table-bordered text-center">
                     <tbody>
                        <tr>
                            <th>
                                <strong>Bank FD @ {{number_format($fixed_deposit,2)}}%</strong>
                            </th>
                            <th>
                                <strong>Debt Fund @ {{number_format($debt_fund,2)}}%</strong>
                            </th>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #b8b8b8;">
                                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av35)}}</strong>
                            </td>
                            <td style="border: 1px solid #b8b8b8;">
                                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($av36)}}</strong>
                            </td>
                        </tr>
                     </tbody>
                    </table>

                   @php
                      $iHtml = "";
                      $yr=1;
                      $ival=$period*12;
                      $x=1;
                      $xx=1;
                      $sm=1;
                      $wd=0;
                      $at=0;
                      $nh=0;
                      $wd2=0;
                      $at2=0;
                      $nh2=0;
                      $last_eq=0;
                      $rwd=0;
                      $rat=0;
                      $rnh=0;
                      $rwd2=0;
                      $rat2=0;
                      $rnh2=0;
                      //$av36_n = round($av36);
                      //$av34_n = round($av34,4);
                      if($period < $for_period_upto){
                        $bs97 = ($initial_investment)-($av36/$av34)*(1-(1+$av34)**(-$ival));
                        $bu97 = $initial_investment - $bs97;
                        $debt_tax_payable = $bu97*$applicable_short_term_tax_rate/100;
                      }else{
                        //echo $assumed_inflation_rate_for_indexation;
                         $bs97 = ($initial_investment)-($av36/$av34)*(1-(1+$av34)**(-$ival));
                        $bt97 = round($bs97)*(1+$t14)**($period);
                        $bu97 = $initial_investment - $bt97;
                        $debt_tax_payable = $bu97*$applicable_long_term_tax_rate/100;
                      }
                      //exit;
                      @endphp

                      <?php for($i=1;$i<=$ival;$i=$i+$div){ ?>
                        @php 
                        $as60=$av33;
                        $at60=$t8;
                        $au60=$as60*$at60;
                        $av60=$au60;
                        $aw60=$applicable_short_term_tax_rate/100;
                        $ax60=$av60*$aw60;
                        $ay60=$av60-$ax60;

                        $az60=$for_period_upto;
                        $ba60=$i;

                        if($x>$cval) {
                           $bg60=$bg60+1;
                           if($withdrawal_mode=='Yearly')
                           {
                            $ba60=$yr;
                           }else{
                            $ba60=$xx;
                           }
                           $x=1;
                           $x++;
                        }else{
                           $bg60=$bg60;
                           if($withdrawal_mode=='Yearly')
                           {
                            $ba60=$bg60;
                           }else{
                            $ba60=$xx;
                           }
                           $x++;
                        }

                        $bb60=$t8;
                        $bc60=$av34;
                        $bd60=$bb60*$bc60;
                        $be60=$bd60;
                        $bf60=$be60/pow((1+$bc60),$ba60);

                       

                        $bh60=pow((1+$t14),(1/$cval))-1;
                        $bi60=$bf60*pow((1+$bh60),($bg60-1));

                        if($bg60<=$az60)
                        {
                            $bj60=$be60-$bf60;
                        }else{
                            $bj60=$be60-$bi60;
                        }

                        $bk60=$applicable_short_term_tax_rate/100;
                        $bl60=$applicable_long_term_tax_rate/100;

                        if($bg60<=$az60)
                        {
                              $bm60=$bj60*$bk60;
                        }else{
                              $bm60=$bj60*$bl60;
                        }

                        $bn60=$be60-$bm60;
                        
                        @endphp

                      @php 
                      if($sm==$cval) {
                         $wd+=$au60;
                         $at+=$ax60;
                         $nh+=$ay60;
                         $wd2+=$be60;
                         $at2+=$bm60;
                         $nh2+=$bn60;
                         $rat2=$rat2+$at2;
                         //echo "<br>";
                         $sm=1;
                        @endphp
                        <?php if($period==$yr){ ?>
                          <?php 
                          $last_eq=$last_eq+($at-$at2); 
                          $iHtml = $iHtml."<tr>
                            <td>
                                ".$yr."
                            </td>
                            <td>
                               ".custome_money_format($wd)."
                            </td>
                            <td>
                               ".custome_money_format($at)."
                            </td>
                            <td>
                               ".custome_money_format($nh)."
                            </td>
                             <td>
                               ".custome_money_format($wd2)."
                            </td>
                            <td>
                               ".custome_money_format($at2)."
                            </td>
                            <td>
                               ".custome_money_format($nh2)."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>";
                          // echo $iHtml; exit;
                          ?>
                          <?php 
                            $rwd=$wd;
                            $rat=$at;
                            $rnh=$nh;
                            $rwd2=$wd2;
                          ?>
                          <?php 
                          $bal_inv_cost=($at60)-($be60/$bc60)*(1-pow((1+$bc60),(-$yr*$cval)));
                           $index_cost=$bal_inv_cost*pow((1+$assumed_inflation_rate_for_indexation/100),($yr));
                           
                           if($period<=($for_period_upto-1))
                           {
                              $taxable_capital_gain=$initial_investment-$bal_inv_cost;
                              $tax_amount=$taxable_capital_gain*$applicable_short_term_tax_rate/100;
                           }elseif($period>($for_period_upto-1))
                           {
                              $taxable_capital_gain=$initial_investment-$index_cost;
                              $tax_amount=$taxable_capital_gain*$applicable_long_term_tax_rate/100;
                           }
                           
                           $eq2=$tax_amount+$at2;
                          $last_eq=$last_eq-$debt_tax_payable; 
                          $rnh2=$last_eq+($at-$eq2); 

                          $iHtml = $iHtml."<tr>
                            <td>
                                Final Redemption
                            </td>
                            <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                            <td>
                               0
                            </td>
                            <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                             <td>
                               ".custome_money_format($initial_investment)."
                            </td>
                            <td>
                               ".custome_money_format($debt_tax_payable)."
                            </td>
                            <td>
                               ".custome_money_format(($initial_investment-$debt_tax_payable))."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>";
                          ?>
                          

                        <?php }else{ ?>
                          <?php 
                            $last_eq=$last_eq+($at-$at2); 
                            $iHtml = $iHtml."<tr>
                            <td>
                                ".$yr."
                            </td>
                            <td>
                               ".custome_money_format($wd)."
                            </td>
                            <td>
                               ".custome_money_format($at)."
                            </td>
                            <td>
                               ".custome_money_format($nh)."
                            </td>
                             <td>
                               ".custome_money_format($wd2)."
                            </td>
                            <td>
                               ".custome_money_format($at2)."
                            </td>
                            <td>
                               ".custome_money_format($nh2)."
                            </td>
                            <td>
                                ".custome_money_format(($last_eq))."
                            </td>
                          </tr>

                            ";
                          ?>
                        <?php } ?>
                      @php
                      $wd=0;
                      $at=0;
                      $nh=0;
                      $wd2=0;
                      $at2=0;
                      $nh2=0;
                      $yr++;
                      }else{
                             $wd+=$au60;
                             $at+=$ax60;
                             $nh+=$ay60;
                             $wd2+=$be60;
                             $at2+=$bm60;
                             $nh2+=$bn60;
                             $sm++;
                       }
                      @endphp

                      <?php $xx++; }

                      // echo $iHtml;

                      //exit; ?>
                      <table class="table table-bordered text-center" style="margin-top: 40px;">

                        <tbody>
                        <tr>
                            <th>
                                <strong>Particulars</strong>
                            </th>
                            <th>
                                <strong>Bank FD</strong>
                            </th>
                            <th>
                                <strong>Debt Fund</strong>
                            </th>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #b8b8b8;">
                                <strong>Total Withdrawal & Redemption</strong>
                            </td>
                            <td style="border: 1px solid #b8b8b8;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment+$rwd*$period)}}
                            </td>
                            <td style="border: 1px solid #b8b8b8;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial_investment+$rwd2*$period)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #b8b8b8;">
                                <strong>Tax Payable</strong>
                            </td>
                            <td style="border: 1px solid #b8b8b8;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($rat*$period)}}
                            </td>
                            <td style="border: 1px solid #b8b8b8;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($rat2+$debt_tax_payable)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Post Tax Receipt</strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($initial_investment+$rwd*$period) - $rat*$period)}}
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(($initial_investment+$rwd2*$period) - ($rat2+$debt_tax_payable))}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    </div>

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

                <div style="padding: 0 5%;">

                    <h1 style="color: #000;font-size:16px;margin-bottom:10px !important;text-align:center;">Annual Tax & Post-Tax Annual Withdrawal</h1>

                     <table class="table table-bordered text-center">
                     <tbody>
                        <tr>
                            <th rowspan="2">
                               <strong>Year</strong>
                            </th>
                            <th colspan="3">
                                <strong>Fixed Deposit</strong>
                            </th>
                            <th colspan="3">
                                <strong>Debt Fund</strong>
                            </th>
                            <th rowspan="2">
                               <strong>Cumulative Tax <br> Saved in Debt Fund</strong>
                            </th>
                        </tr>
                        <tr>
                           
                            <th>
                                <strong>Withdrawal</strong>
                            </th>
                            <th>
                                <strong>Annual Tax</strong>
                            </th>
                            <th>
                                <strong>Net In Hand</strong>
                            </th>
                            <th>
                                <strong>Withdrawal</strong>
                            </th>
                            <th>
                                <strong>Annual Tax</strong>
                            </th>
                            <th>
                                <strong>Net In Hand</strong>
                            </th>
                        </tr>
                        {!!$iHtml!!}
                    </tbody>
                    </table>
    </div>

                    

                    <?php if(isset($benefit)){ 
                      $page_data = \App\Models\Calculator_note::where('category','debt_funds')->first();
                      if(!empty($page_data)){
                    ?>

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

                <div style="padding: 0 5%;">

                    <h1 style="color: #000;font-size:16px;margin-bottom:10px !important;text-align:center;">{{$page_data->name}}</h1>
                    
                    {!!$page_data->description!!}
                </div>

                    <?php }} ?>

                    <?php if(isset($tax_calculation)){ 
                      $page_data = \App\Models\Calculator_note::where('category','bank_fd_vs_mutual_fund_swp_display_tax_calculation')->first();
                      if(!empty($page_data)){
                    ?>
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
                        <div style="padding: 0 5%;">

                            <h1 style="color: #000;font-size:16px;margin-bottom:10px !important;text-align:center;">{{$page_data->name}}</h1>
                            
                            {!!$page_data->description!!}
                        </div>

                    <?php }} ?>


   

    @include('frontend.calculators.common.footer')

    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>
