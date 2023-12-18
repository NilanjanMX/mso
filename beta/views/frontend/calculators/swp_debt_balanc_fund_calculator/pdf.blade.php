<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('frontend.calculators.common.pdf_style')
</head>
<body class="styleApril">
    @include('frontend.calculators.common.header')
<main class="mainPdf">
    

                <div style="padding: 0 0%;">
                        <h1 class="pdfTitie">SWP Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                     <?php
                    if($annuity=='Immediate_Annuity')
                    { ?>

                    <?php }else{ ?>
                    <div class="roundBorderHolder">
                        <table>
                        <tbody>
                        <?php if(isset($current_age)){ ?>
                        <tr>
                            <td style="width: 60%;">
                                <strong>Age</strong>
                            </td>
                            <td style="width: 40%;">
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td style="padding-top:3px;padding-bottom:3px;">
                                <strong>Initial Investment</strong>
                            </td>
                            <td style="padding-top:3px;padding-bottom:3px;">
                                 {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="">
                                <strong>Deferment Period</strong>
                            </td>
                            <td>
                                 {{$deferment_period}} Years
                            </td>
                        </tr>
                        </tbody>
                        </table>
                    </div>
                </div>


                        <div style="padding: 0 0%;">
                            <div class="roundBorderHolder" style="margin-top:20 !important;">
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
                            </div>
                    <?php } ?>

                    <?php
                    if($annuity=='Immediate_Annuity')
                    { ?>

                    <?php }else{ ?>
                        <h1 class="pdfTitie">SWP Period</h1>
                    <?php } ?>
                        <div class="roundBorderHolder">    
                          <table class="table table-bordered text-center">
                            <tbody>
                            <?php if(isset($current_age)){ ?>
                        <?php
                        if($annuity=='Immediate_Annuity')
                        { ?>
                            <tr>
                                <td style="">
                                    <strong>Age</strong>
                                </td>
                                <td>
                                    {{$current_age?$current_age:0}} Years
                                </td>
                            </tr>
    
                        <?php }else{ ?>
                            <tr>
                                <td style="width: 60%;">
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
                                <td style="padding-top:3px;padding-bottom:3px;">
                                    <strong>Initial Investment</strong>
                                </td>
                                <td style="padding-top:3px;padding-bottom:3px;">
                                     <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($initial_investment)}}
                                </td>
                            </tr>
                            <?php }else{ ?>
                            <tr>
                                <td style="padding-top:3px;padding-bottom:3px;">
                                    <strong>Accumulated Fund Value</strong>
                                </td>
                                <td style="padding-top:3px;padding-bottom:3px;">
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($fund_value)}}
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td style="">
                                    <strong>% Investment in Debt Fund</strong>
                                </td>
                                <td>
                                    {{$debt_fund?number_format($debt_fund,2):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <strong>% Investment in Balance Fund</strong>
                                </td>
                                <td>
                                    {{$balance_fund?$balance_fund:0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <strong>Expected Return (Debt Fund)</strong>
                                </td>
                                <td>
                                    {{$expected_return_debt_fund?number_format($expected_return_debt_fund,2):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <strong>Expected Return (Balance Fund)</strong>
                                </td>
                                <td>
                                    {{$expected_return_balance_fund?number_format($expected_return_balance_fund,2):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <strong>Annual Withrawal</strong>
                                </td>
                                <td>
                                    {{$annual_withdrawal_precent_investment?number_format($annual_withdrawal_precent_investment,2):0}} %
                                </td>
                            </tr>
                           <tr>
                                <td style="">
                                    <strong>SWP Period</strong>
                                </td>
                                <td>
                                    {{$swp_period?$swp_period:0}} Years
                                </td>
                            </tr>
                             <tr>
                                <td style="">
                                    <strong>Rebalancing Period</strong>
                                </td>
                                <td>
                                    {{$periodic_rebalance_period?$periodic_rebalance_period:0}} Years
                                </td>
                            </tr>
    
                            </tbody></table>
                        </div>
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
                <div style="padding: 0 0%;">
                    <div class="roundBorderHolder" style="margin-top:20 !important;">
                        <table>
                            <tr>
                                <td>
                                    <h1 class="pdfTitie">Expected Portfolio Return</h1>
                                    <h5 class="pdfBlueCell">{{$exp_portfolio_return?$exp_portfolio_return:0}}%</h5>
                                </td>
                                <td>
                                    <h1 class="pdfTitie">{{$withdrawal?$withdrawal:0}} SWP Amount</h1>
                                    <h5 class="pdfBlueCell">{{$withdrawal_amount?custome_money_format($withdrawal_amount):0}}</h5>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h1 class="pdfTitie">Total Annuity Received</h1>
    
                                    <h5 class="pdfBlueCell"> <span class="pdfRupeeIcon">&#8377;</span> {{$withdrawal_amount?custome_money_format($withdrawal_amount*$swp_period*$mo):0}}</h5>
                                </td>
                                <td>
                                    <h1 class="pdfTitie">Closing Fund Value</h1>
                                    <h5 class="pdfBlueCell"> <span class="pdfRupeeIcon">&#8377;</span> {{$closed_val}}</h5>
                                </td>                            
                            </tr>
                        </table>
                    </div>
                </div>
                 
    
                    @php
                    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SWP_Rebalance_Calculator')->first();
                    if(!empty($note_data1)){
                    @endphp
                    <div style="margin-top:15px !important;">
                        {!!$note_data1->description!!}
                    </div>
                    @php } @endphp
                    
                    
                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_pdf')
                </main>

                    @include('frontend.calculators.common.watermark')
                    @if($footer_branding_option == "all_pages")
                        @include('frontend.calculators.common.footer')
                    @endif
                    <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">   
                    <h1 class="bluebar" style="background:{{$city_color}}">Projected Annual Cash Flow & Fund Value</h1>
                    @php
                    if(isset($deferment_period)){ $deferment_period=$deferment_period; }else{ $deferment_period=0; }
                    @endphp
                    <div class="roundBorderHolder withBluebar">
                        <table>
                            <tbody>
                            <tr>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong><?php if(isset($current_age)){ $age=$current_age; ?>Age<?php }else{ $age=0; ?>Year<?php } ?></strong></th>
                                <th style="background:{{$address_color_background}}" colspan="2"><strong>Debt Fund</strong></th>
                                <th style="background:{{$address_color_background}}" colspan="2"><strong>Balance Fund</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>Annual Withdrawal</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>End of Year Fund Value</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>Transfer From Balance To Debt Fund</strong></th>
                            </tr>
                            <tr>
                                <th style="background:{{$address_color_background}}"><strong>Beginning of Year</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>End of Year</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Beginning of Year</strong></th>
                                <th style="background:{{$address_color_background}};border-right: 1px solid #458ff6;"><strong>End of Year</strong></th>
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
                    </div>

                    </main>
                    @include('frontend.calculators.common.watermark')
                    @if($footer_branding_option == "all_pages")
                        @include('frontend.calculators.common.footer')
                    @endif
                    <div class="page-break"></div>
                                        
                    @include('frontend.calculators.common.header')
                    
                    <main class="mainPdf">
                        <div class="roundBorderHolder withBluebar withBluebarMrgn">
                            <table>
                            <tbody>
                            <tr>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong><?php if(isset($current_age)){ $age=$current_age; ?>Age<?php }else{ $age=0; ?>Year<?php } ?></strong></th>
                                <th style="background:{{$address_color_background}}" colspan="2"><strong>Debt Fund</strong></th>
                                <th style="background:{{$address_color_background}}" colspan="2"><strong>Balance Fund</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>Annual Withdrawal</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>End of Year Fund Value</strong></th>
                                <th style="background:{{$address_color_background}}" rowspan="2"><strong>Transfer From Balance To Debt Fund</strong></th>
                            </tr>
                            <tr>
                                <th style="background:{{$address_color_background}}"><strong>Beginning of Year</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>End of Year</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Beginning of Year</strong></th>
                                <th style="background:{{$address_color_background}};border-right: 1px solid #458ff6;"><strong>End of Year</strong></th>
                            </tr>
    
                        @endif 
                        
                        <?php } ?>
                        </tbody>
                        </table>
                    </div>

                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SWP_Rebalance_Calculator')->first();
                        if(!empty($note_data2)){
                    @endphp
                        {!!$note_data2->description!!}
                    @php } @endphp
                </main>
   
                    @include('frontend.calculators.common.watermark')
                    @if($footer_branding_option == "all_pages")
                        @include('frontend.calculators.common.footer')
                    @endif

    @include('frontend.calculators.suggested.pdf')


</body>
</html>
