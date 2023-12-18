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

        //SIP Period (Month)
        $sip_period_months = $sip_period*12;
         //Annuity Period (Months) T9*12
        $annuity_period_months = $annuity_period*12;
         //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
       
        $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
        
        //Lumpsum For Balance (1) T15/(1+AV31)^(AV30)
        $lumpsum_for_balance1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
        //Lumpsum For Annuity (1) (X30*(1-(1+AV31)^(-AV30)))/AV31

        if($include_inflation=='yes')
        {
            $av37 = (1+$expected_inflation_rate/100)**(1/12)-1;
            $lumpsum_for_annuity1 = $sip_amount*((1-((1+$av37)**($annuity_period_months))*((1+$distribution_monthly_return1)**(-$annuity_period_months)))/($distribution_monthly_return1-$av37));
        }else{
            $lumpsum_for_annuity1 = ($sip_amount*(1-(1+$distribution_monthly_return1)**(-$annuity_period_months)))/$distribution_monthly_return1;
        }
        


        //Annuity Purchase Amount (1) AV33+AV35
        $annuity_purchase_amount1 = $lumpsum_for_balance1+$lumpsum_for_annuity1;
        
        
        //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
        $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
        //SIP Required (1) AV37/((1+AV40)*((1+AV40)^(AV39)-1)/AV40)

        if($deferment=='yes')
        {
            $av43=$deferment_period*12;
            $av46=$annuity_purchase_amount1/(1+$accumulation_monthly_return1)**$av43;
            $sip_required1 = $av46/((1+$accumulation_monthly_return1)*((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
        }else{
            $sip_required1 = $annuity_purchase_amount1/((1+$accumulation_monthly_return1)*((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1); 
        }
        

        if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0){
             //Distribution Monthly Return (2)
             $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
             //Lumpsum For Balance (2) T15/(1+AV31)^(AV30)
             $lumpsum_for_balance2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
             //Lumpsum For Annuity (2) (X30*(1-(1+AV31)^(-AV30)))/AV31

            if($include_inflation=='yes')
            {
                $av37 = (1+$expected_inflation_rate/100)**(1/12)-1;
                $lumpsum_for_annuity2 = $sip_amount*((1-((1+$av37)**($annuity_period_months))*((1+$distribution_monthly_return2)**(-$annuity_period_months)))/($distribution_monthly_return2-$av37));
            }else{
                $lumpsum_for_annuity2 = ($sip_amount*(1-(1+$distribution_monthly_return2)**(-$annuity_period_months)))/$distribution_monthly_return2;
            }


             //Annuity Purchase Amount (2) AV33+AV35
            $annuity_purchase_amount2 = $lumpsum_for_balance2+$lumpsum_for_annuity2;
            //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
            $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
             //SIP Required (2) AV37/((1+AV40)*((1+AV40)^(AV39)-1)/AV40)

            if($deferment=='yes')
            {
                $av43=$deferment_period*12;
                $av47=$annuity_purchase_amount2/(1+$accumulation_monthly_return2)**$av43;
                $sip_required2 = $av47/((1+$accumulation_monthly_return2)*((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
            }else{
                $sip_required2 = $annuity_purchase_amount2/((1+$accumulation_monthly_return2)*((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
            }

        }

    if(isset($sip_period) && $sip_period>0)
    {
            $sp=$sip_period;
    }else{
            $sp=0;
    }

    if(isset($deferment_period) && $deferment_period>0)
    {
            $dp=$deferment_period;
    }else{
            $dp=0;
    }

    @endphp
    
    
    @include('frontend.calculators.common.header')
    
<main class="mainPdf">
    
    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">Monthly SWP Calculation @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
    @php
                    if(isset($current_age) && $current_age>0)
                    {
                        $count_sec=$current_age;
                    }else{
                        $count_sec=0;
                    }
                    if(isset($deferment_period) && $deferment_period>0)
                    {
                        $dif_sec=$deferment_period;
                    }else{
                        $dif_sec=0;
                    }
                    @endphp
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                            @if(isset($current_age) && $current_age>0)
                            <tr>
                                <td style="">
                                    <strong>Current Age</strong>
                                </td>
                                <td>
                                    {{$current_age}} Years
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="">
                                    <strong>Target Monthly SWP</strong>
                                </td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="">
                                    <strong> SIP Period</strong>
                                </td>
                                <td>
                                    {{$sip_period?$sip_period:0}} Years
                                </td>
                            </tr>
                            @if(isset($deferment_period) && $deferment_period)
                            <tr >
                                <td style="">
                                    <strong>Deferment Period</strong>
                                </td>
                                <td>
                                    {{$deferment_period}} Years
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td style="">
                                    <strong>SWP Period</strong>
                                </td>
                                <td>
                                    {{$annuity_period?$annuity_period:0}} Years
                                </td>
                            </tr>
                            @if(isset($balance_required))
                                <tr>
                                    <td style="">
                                        <strong>Balance Required</strong>
                                    </td>
                                    <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required)}}
                                    </td>
                                </tr>
                            @endif
                            </tbody></table>
                    </div>
                            
                    <h1 class="pdfTitie">Accumulated Corpus Required</h1>
                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                    <div class="roundBorderHolder">
                        <table >
                            <tbody>
                            
                                <tr>
                                    <td>
                                        <strong>Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}} </strong>
                                    </td>
                                    <td>
                                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount2)}} </strong>
                                    </td>
                                </tr>
                           
                            </tbody>
                        </table>
                    </div>

                     @else
                            
                        <h5 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}}</h5>

                    @endif
                    
                    
                    <h1 class="pdfTitie">Monthly SIP Required</h1>
                    
                        @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                        <div class="roundBorderHolder">
                            <table >
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required1)}} </strong>
                                    </td>
                                    <td>
                                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required2)}} </strong>
                                    </td>
                                </tr>
                                 </tbody>
                            </table>
                        </div>
                        @else
                            
                             <h5 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required1)}}</h5>

                        @endif

                        <h1 class="pdfTitie">Total Withdrawal</h1>
                        <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                             <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                {{custome_money_format($av50_new)}}
                             </h5>
                        <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                {{custome_money_format($av50_new)}}
                             </h5>
                        <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                {{custome_money_format($sip_amount*$annuity_period_months)}}
                             </h5>
                        <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                {{custome_money_format($sip_amount*$annuity_period_months)}}
                             </h5>
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                {{custome_money_format($av50_new)}}
                             </h5>
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($sip_amount*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                {{custome_money_format($av50_new)}}
                             </h5>
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                {{custome_money_format($sip_amount*$annuity_period_months)}}
                             </h5>
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
                                {{custome_money_format($sip_amount*$annuity_period_months)}}
                             </h5>
                        <?php } ?>
                       
                    </div>
                    
                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_pdf')
            </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
                
                    

                    @if(isset($report) && $report=='detailed')
                    <div class="page-break"></div>
                        @include('frontend.calculators.common.header')
                    <main class="mainPdf">
                        <h1 class="bluebar" style="background:{{$city_color}}">Accumulation Phase <br>Projected Annual Investment Value</h1>
                        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                            <table>
                                <tbody>
                                @php
                                $ci=0;
                                if($deferment=='yes')
                                {
                                    $s_count=$sip_period+$deferment_period;
                                }else{
                                    $s_count=$sip_period;
                                }
                                @endphp
                                @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" colspan="2" sty;vertical-align: middle;le="vertical-align: middle;">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                    </tr>
    
                                    @for($i=1;$i<=$s_count;$i++)
                                        @php
                                            //Year End Value (1+AV65)*AS65*(((1+AV65)^(AU65*12)-1)/AV65)
    
                                            if($i<=$sip_period)
                                            {
                                                $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_required1*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                            }else{
                                                $year_end_value1 = $av46*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                            }
    
                                            if($i<=$sip_period)
                                            {
                                                $year_end_value2 = (1+$accumulation_monthly_return2)*$sip_required2*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);
                                            }else{
                                                $year_end_value2 = $av47*(1+$accumulation_monthly_return2)**(($i-$sip_period)*12);
                                            }
                                            
                                            
                                        @endphp
                                        <tr>
                                            <td>{{$i+$count_sec}}</td>
                                            @if($i>$sip_period)
                                            <td>--</td>
                                            @else
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required1*12)}}</td>
                                            @endif
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                                            @if($i>$sip_period)
                                            <td>--</td>
                                            @else
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required2*12)}}</td>
                                            @endif
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value2)}}</td>
                                        </tr>
                                         @if($i%25==0 && $s_count>25 && $s_count>$i)
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
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" colspan="2">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                    </tr>
                                @endif
                                    @endfor
                                @else
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                    </tr>
                                    @for($i=1;$i<=$s_count;$i++)
                                        @php
                                            //Year End Value (1+AV65)*AS65*(((1+AV65)^(AU65*12)-1)/AV65)
                                           if($i<=$sip_period)
                                            {
                                                $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_required1*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                                            }else{
                                                $year_end_value1 = $av46*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                                            }
    
                                        @endphp
                                        <tr>
                                            <td>{{$i+$count_sec}}</td>
                                            @if($i>$sip_period)
                                            <td>--</td>
                                            @else
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required1*12)}}</td>
                                            @endif
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                                        </tr>
                                         @if($i%25==0 && $s_count>25 && $s_count>$i)
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
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle;">Annual Investment</th>
                                    <th style="background:{{$address_color_background}}">Year End Value</th>
                                </tr>
                                @endif
                                @endfor
                            @endif
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

                        <h1 class="bluebar" style="background:{{$city_color}}">Distribution Phase <br>Annual Withdrawal & Projected Investment Value</h1>
                        @if($include_inflation=='yes')
                        @php
                        $aw107=0;
                        $bd107=0;
                        $ax107=0;
                        $be107=0;

                        $h107=0;
                        $w107=0;

                        $yr=1;
                        @endphp
                        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                            <table>
                                <tbody>
                                <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
    
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        @endif
                                    </tr>
                                @for($i=1;$i<=$annuity_period*12;$i++)
                                @php
                                if($i==1)    
                                {
                                    $aw107=$sip_amount;
                                    $au107=$annuity_purchase_amount1+$annuity_purchase_amount1*$distribution_monthly_return1;
    
                                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    {
                                        $bd107=$sip_amount;
                                        $bb107=$annuity_purchase_amount2+$annuity_purchase_amount2*$distribution_monthly_return2;
                                    }
                                    
    
                                }else{
                                    $aw107=$aw107+$aw107*$av37;
                                    $au107=$ax107+$ax107*$distribution_monthly_return1;
    
                                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    {
                                        $bd107=$bd107+$bd107*$av37;
                                        $bb107=$be107+$be107*$distribution_monthly_return2;
                                    }
                                }       
                                
                                $ax107=$au107-$aw107;
                                $h107+=$aw107;
    
                                if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                {
                                    $be107=$bb107-$bd107;
                                    $w107+=$bd107;
                                }
                                if($i%12==0)
                                {
                                @endphp
                                <tr>
                                        <td>{{$yr+$count_sec+$sp+$dp}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($h107/12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ax107)}}</td>
    
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
    
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($w107/12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($be107)}}</td>
    
                                        @endif
                                </tr>
                                @if($yr%25==0 && $annuity_period>25 && $annuity_period>$yr)
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
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    @endif
                                </tr>
                                </tbody>
                            @endif
                            @php 
                            $h107=0;
                            $w107=0;
                            $yr++;
                            } @endphp
                            @endfor
                        </table>
                    </div>
                        @else
                        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                        <table>
                            <tbody>
                            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                </tr>

                                @for($i=1;$i<=$annuity_period;$i++)
                                    @php
                                        //Year End Balance (AS109*(1+AU109)^(AR109*12)-(AW109*((1+AU109)^(AR109*12)-1)/AU109))
                                        $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($sip_amount*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                        $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($sip_amount*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));
                                    @endphp
                                    <tr>
                                        <td>{{$i+$count_sec+$sp+$dp}}</td>
                                        @if($include_taxation=='yes')
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required1)}}</td>
                                        @else
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}</td>
                                        @endif
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                                        @if($include_taxation=='yes')
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_required2)}}</td>
                                        @else
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}</td>
                                        @endif
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance2)}}</td>
                                    </tr>
                                    @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
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
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    </tr>
                                @endif
                                    @endfor
                                @else
                                    <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};" colspan="2"> @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
    
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}};">Year End Balance</th>
                                    </tr>
    
                                    @for($i=1;$i<=$annuity_period;$i++)
                                        @php
                                            //Year End Balance (AS109*(1+AU109)^(AR109*12)-(AW109*((1+AU109)^(AR109*12)-1)/AU109))
                                            $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($sip_amount*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                        @endphp
                                        <tr>
                                            <td>{{$i+$count_sec+$sp+$dp}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                                        </tr>
                                         @if($i%25==0 && $annuity_period>25 && $annuity_period>$i)
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
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};" colspan="2"> @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
    
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}};">Year End Balance</th>
                                    </tr>
                                @endif
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                                    </div>
                                    </main>
                            @include('frontend.calculators.common.watermark')
                            @if($footer_branding_option == "all_pages")
                                @include('frontend.calculators.common.footer')
                            @endif
                        @endif
                        
                        
                        @if($include_taxation=='yes')
                        <div class="page-break"></div>
                            @include('frontend.calculators.common.header')
                            <main class="mainPdf">
                        <h1 class="bluebar" style="background:{{$city_color}}">Annual Tax & Post-Tax Annual Withdrawal</h1>
                        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                            <table>
                                <tbody>
                                 <tr>
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @endif
                                </tr>
                                @if($include_inflation=='yes')
    
                                 @php
                                    $yr=1;
                                    $l147=0;
                                    $q147=0;
                                    $g103=0;
    
                                    $w103=0;
                                    $ab107=0;
                                    $ag107=0;
                                    for($i=1;$i<=$annuity_period*12;$i++)
                                    {
    
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    if($i==1)
                                    {
                                        $ax=$sip_amount;
                                    }else{
                                        $ax=$ax+$ax*$av41_inf;
                                    }
    
                                    $av37=(1+($accumulation_phase_interest_rate_1/100))**(1/12)-1;
                                    //$az=$ax/(1+$av37)**$i;
                                    $az=$ax/(1+$distribution_monthly_return1)**$i;
    
                                    $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bd63=$ax-$az;
                                    }else{
                                        $bd63=$ax-$bc63;
                                    }
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                    }else{
                                        $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                    }
    
                                    $bh63=$ax-$bg63;
                                    $g103+=$ax;
                                    $l147+=$bg63;
                                    $q147+=$bh63;
                                    
                                   //--------ggggggggg
    
                                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    {
    
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    if($i==1)
                                    {
                                        $bo=$sip_amount;
                                    }else{
                                        $bo=$bo+$bo*$av41_inf;
                                    }
    
                                    $w103+=$bo;
    
                                    //$bl63=(1+($accumulation_phase_interest_rate_2/100))**(1/12)-1;
                                    //$bq63=$bo/(1+$bl63)**$i;
    
                                    $bq63=$bo/(1+$distribution_monthly_return2)**$i;
    
                                    $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bu63=$bo-$bq63;
                                    }else{
                                        $bu63=$bo-$bt63;
                                    }
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                    }else{
                                        $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                    }
    
                                    $ab107+=$bx63;
                                    $by63=$bo-$bx63;
                                    $ag107+=$by63;
    
                                    }
    
                                    if($i%12==0)
                                    {
                                @endphp
                                <!-- nnnnnnnnnnnnnn -->
                                <tr>
                                    <td>{{$count_sec+$dif_sec+$yr+$sp}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($g103)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($w103)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                    @endif
                                </tr>
                                @if($yr%25==0 && $annuity_period>25 && $annuity_period>$yr)
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
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @endif
                                </tr>
                                @endif
                                @php
                                        $g103=0;
                                        $q147=0;
                                        $l147=0;
                                        
                                        $w103=0;
                                        $ag107=0;
                                        $ab107=0;
                                        $yr++;
                                    }
                                    }
                                @endphp
    
                                @else
                                @php
                                    $yr=1;
                                    $l147=0;
                                    $q147=0;
                                    $g103=0;
    
                                    $w103=0;
                                    $ab107=0;
                                    $ag107=0;
                                    for($i=1;$i<=$annuity_period*12;$i++)
                                    {
                                    //zzzzzzzzzzzz
                                    
                                    $ax=$sip_amount;
                                    
                                   
    
                                    $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                    $az=$ax/(1+$av37)**$i;
    
                                    $bc63=$az*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bd63=$ax-$az;
                                    }else{
                                        $bd63=$ax-$bc63;
                                    }
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bg63=$bd63*($applicable_short_term_tax_rate/100);
                                    }else{
                                        $bg63=$bd63*($applicable_long_term_tax_rate/100);
                                    }
    
                                    $bh63=$ax-$bg63;
    
    
                                    $g103+=$ax;
                                    $l147+=$bg63;
                                    $q147+=$bh63;
                                    
                                   //--------
    
                                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    {
    
                                   
                                    $bo=$sip_amount;
                                   
    
                                    $w103+=$bo;
    
                                    $bl63=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                    $bq63=$bo/(1+$bl63)**$i;
    
                                    $bt63=$bq63*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bu63=$bo-$bq63;
                                    }else{
                                        $bu63=$bo-$bt63;
                                    }
    
                                    if($yr<=$for_period_upto)
                                    {
                                        $bx63=$bu63*($applicable_short_term_tax_rate/100);
                                    }else{
                                        $bx63=$bu63*($applicable_long_term_tax_rate/100);
                                    }
    
                                    $ab107+=$bx63;
                                    $by63=$bo-$bx63;
                                    $ag107+=$by63;
    
                                    }
    
                                    if($i%12==0)
                                    {
                                @endphp
                                <!-- nnnnnnnnnnnnnn -->
                                <tr>
                                    <td>{{$count_sec+$dif_sec+$yr+$sp}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                    @endif
                                </tr>
                                @if($yr%25==0 && $annuity_period>25 && $annuity_period>$yr)
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
                                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                        <th style="background:{{$address_color_background}};">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}};">Tax Payable</th>
                                        <th style="background:{{$address_color_background}};">Post - Tax Withdrawal</th>
                                        @endif
                                </tr>
                                @endif
                                @php
                                        $g103=0;
                                        $q147=0;
                                        $l147=0;
                                        
                                        $w103=0;
                                        $ag107=0;
                                        $ab107=0;
                                        $yr++;
                                    }
                                    }
                                @endphp
    
                                @endif
    
                                </tbody>
                            </table>
                                    </div>


                        @endif
                        @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                        if(!empty($note_data2)){
                    @endphp
                        {!!$note_data2->description!!}
                    @php } @endphp
                    
                    Report Date : {{date('d/m/Y')}}
                        </main>
                        @include('frontend.calculators.common.watermark')
                    
                        @if($footer_branding_option == "all_pages" || !((isset($suggest) && session()->has('suggested_scheme_list'))))
                            @include('frontend.calculators.common.footer')
                        @endif
                    @endif
    @include('frontend.calculators.suggested.pdf')

</body>
</html>