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
        //Annuity Period (Months) T9*12
        $annuity_period_months = $period*12;
        //Monthly Rate of Return (1) (1+T11%)^(1/12)-1
        if($deferment=='yes')
        {
        $av33=$deferment_period*12;
        $monthly_rate_of_return1 = pow((1+$distribution_phase_interest_rate_1/100),(1/12))-1 ;
        $av35=pow((1+$accumulation_phase_interest_rate_1/100),(1/12))-1 ;
        $av39=$initial_investment*pow((1+$av35),$av33);
        if (isset($distribution_phase_interest_rate_2)){
        $av36=pow((1+$accumulation_phase_interest_rate_2/100),(1/12))-1 ;
        $av40=$initial_investment*pow((1+$av36),$av33);
        }
        }else{
        $monthly_rate_of_return1 = pow((1+$interest1/100),(1/12))-1 ;
        }
    
        if (isset($expected_inflation_rate)){
        $av36_inf=pow((1+$expected_inflation_rate/100),(1/12))-1;
        }
    
    
        //PV of Balance Required (1) T13/(1+AV27)^(AV26)
        $pv_of_balance_required1 = $balance_required/(1+$monthly_rate_of_return1)**($annuity_period_months);
        //Balance Available for Annuity (1) T8-AV29
        $balance_available_for_annuity1 = $initial_investment-$pv_of_balance_required1;
        //Monthly SWP Amount (1) (AV27*AV31)/(1-(1+AV27)^(-AV26))
        if($deferment=='yes')
        {
        $av43=$av39-$pv_of_balance_required1;
    
        if (isset($expected_inflation_rate)){
        if($distribution_phase_interest_rate_1==$expected_inflation_rate)
        {
        $monthly_annuity_amount1 = $av43*(1+$monthly_rate_of_return1);
        }else{
        $monthly_annuity_amount1 =
        $av43/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
        }
        }else{
        $monthly_annuity_amount1 =
        ($monthly_rate_of_return1*$av43)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));
        }
    
        }else{
        if (isset($expected_inflation_rate)){
        if($interest1==$expected_inflation_rate)
        {
        $monthly_annuity_amount1 = $balance_available_for_annuity1*(1+$monthly_rate_of_return1);
        }else{
        $monthly_annuity_amount1 =
        $balance_available_for_annuity1/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return1)**(-$annuity_period_months)))/($monthly_rate_of_return1-$av36_inf));
        }
        }else{
        $monthly_annuity_amount1 =
        ($monthly_rate_of_return1*$balance_available_for_annuity1)/(1-(1+$monthly_rate_of_return1)**(-$annuity_period_months));
        }
        }
        if($deferment=='yes')
        {
        if (isset($distribution_phase_interest_rate_2)){
        //Monthly Rate of Return (2) (1+T12%)^(1/12)-1
        $monthly_rate_of_return2 = pow((1+$distribution_phase_interest_rate_2/100),(1/12))-1 ;
        //PV of Balance Required (2) T13/(1+AV27)^(AV26)
        $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
        //Balance Available for Annuity (2) T8-AV30
        $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
        //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))
    
        $av44=$av40-$pv_of_balance_required2;
    
        if (isset($expected_inflation_rate)){
        if($distribution_phase_interest_rate_2==$expected_inflation_rate)
        {
        $monthly_annuity_amount2 = $av44*(1+$monthly_rate_of_return2);
        }else{
        $monthly_annuity_amount2 =
        $av44/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
        }
        }else{
        $monthly_annuity_amount2 =
        ($monthly_rate_of_return2*$av44)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
        }
    
        }
        }else{
        if (isset($interest2)){
        //Monthly Rate of Return (2) (1+T12%)^(1/12)-1
        $monthly_rate_of_return2 = pow((1+$interest2/100),(1/12))-1 ;
        //PV of Balance Required (2) T13/(1+AV27)^(AV26)
        $pv_of_balance_required2 = $balance_required/(1+$monthly_rate_of_return2)**($annuity_period_months);
        //Balance Available for Annuity (2) T8-AV30
        $balance_available_for_annuity2 = $initial_investment-$pv_of_balance_required2;
        //Monthly SWP Amount (2) (AV28*AV32)/(1-(1+AV28)^(-AV26))
        if (isset($expected_inflation_rate)){
        if($interest1==$expected_inflation_rate)
        {
        $monthly_annuity_amount2 = $balance_available_for_annuity2*(1+$monthly_rate_of_return2);
        }else{
        $monthly_annuity_amount2 =
        $balance_available_for_annuity2/((1-((1+$av36_inf)**($annuity_period_months))*((1+$monthly_rate_of_return2)**(-$annuity_period_months)))/($monthly_rate_of_return2-$av36_inf));
        }
        }else{
        $monthly_annuity_amount2 =
        ($monthly_rate_of_return2*$balance_available_for_annuity2)/(1-(1+$monthly_rate_of_return2)**(-$annuity_period_months));
        }
        }
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
                                    <td colspan="2">
                                        {{$current_age}} Years
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="">
                                        <strong>Initial Investment</strong>
                                    </td>
                                    <td colspan="2">
                                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($initial_investment)}}
                                    </td>
                                </tr>
                                @if($deferment=='yes')
                                    <tr>
                                        <td style="">
                                            <strong>Deferment Period</strong>
                                        </td>
                                        <td colspan="2">
                                            {{$deferment_period?$deferment_period:0}} Years
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td style="">
                                        <strong>SWP Period</strong>
                                    </td>
                                    <td colspan="2">
                                        {{$period?$period:0}} Years
                                    </td>
                                </tr>
        
                                @if(isset($expected_inflation_rate) && $expected_inflation_rate>0)
                                    <tr>
                                        <td style="">
                                            <strong>Expected Inflation Rate</strong>
                                        </td>
                                        <td colspan="2">
                                            {{$expected_inflation_rate?number_format($expected_inflation_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    @if($include_taxation=='yes')
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">
                                            <strong>Capital Gain Tax Rate</strong>
                                        </td>
                                        <td>
                                            <strong>Short Term</strong>
                                        </td>
                                            <td>
                                            <strong>Long Term</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{$applicable_short_term_tax_rate?number_format($applicable_short_term_tax_rate, 2, '.', ''):0}} %
                                        </td>
                                            <td>
                                            {{$applicable_long_term_tax_rate?number_format($applicable_long_term_tax_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Expected Indexation Rate</strong>
                                        </td>
                                        <td colspan="2">
                                            {{$assumed_inflation_rate_for_indexation?number_format($assumed_inflation_rate_for_indexation, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    @endif
                                @else
        
                                    @if($include_taxation=='yes')
                                    <tr>
                                        <td style="">
                                            <strong>Assumed Indexation Rate</strong>
                                        </td>
                                        <td colspan="2">
                                            {{$assumed_inflation_rate_for_indexation?number_format($assumed_inflation_rate_for_indexation, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle;">
                                            <strong>Capital Gain Tax Rate</strong>
                                        </td>
                                        <td>
                                            <strong>Short Term</strong>
                                        </td>
                                            <td>
                                            <strong>Long Term</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            {{$applicable_short_term_tax_rate?number_format($applicable_short_term_tax_rate, 2, '.', ''):0}} %
                                        </td>
                                            <td>
                                            {{$applicable_long_term_tax_rate?number_format($applicable_long_term_tax_rate, 2, '.', ''):0}} %
                                        </td>
                                    </tr>
                                    @endif
        
                                @endif
                                
                                @if(isset($balance_required) && $balance_required != "")
                                    <tr>
                                        <td style="">
                                            <strong>Balance Required</strong>
                                        </td>
                                        <td colspan="2">
                                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required)}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody></table>
                        </div>
            </div>
            
            <div style="padding: 0 0%;">
                        @if($deferment=='yes')
                        <h1 class="pdfTitie">Accumulated Corpus @if($deferment=='yes') @if(!isset($accumulation_phase_interest_rate_2)) @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>
                        
                        @if(isset($accumulation_phase_interest_rate_2))
                            <div class="roundBorderHolder">
                                <table>
                                <tbody>
                                
                                    <tr>
                                        <th>
                                            <strong>Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                        </th>
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <th>
                                            <strong>Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                        </th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av39)}} </strong>
                                        </td>
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <td>
                                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av40)}} </strong>
                                        </td>
                                        @endif
                                    </tr>
                                
                                </tbody></table>
                            </div>
                            @else
                            <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av39)}}</h1>
                            @endif
    
                        @endif
                        </div>
            <div style="padding: 0 0%;">
                        
                        @if($include_inflation=='yes')
    
                        <h1 class="pdfTitie">First Year Average Monthly SWP @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>
    
                        @else
    
                        <h1 class="pdfTitie">Monthly SWP Amount @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @else @if(!isset($interest2)) @ {{$interest1?number_format($interest1, 2, '.', ''):0}} % @endif @endif</h1>
                        
                        @endif
    
                        @if($deferment=='no')
                        @if(isset($interest2))
                            <div class="roundBorderHolder">
                                <table>
                                    <tbody>
                                    
                                        <tr>
                                            <td>
                                                Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                            </td>
                                            <td>
                                                Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                            </td>
                                        </tr>
            
                                            <tr>
                                            @if($include_inflation=='yes')
                                            @php
                                            $av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
            
                                            @endphp
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new)}} </strong>
                                            </td>
                                            @else
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}} </strong>
                                            </td>
                                            @endif
                                            @if($include_inflation=='yes')
                                            @php
                                            $av43_new2=($monthly_annuity_amount2*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                            @endphp
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new2)}} </strong>
                                            </td>
                                            @else
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}} </strong>
                                            </td>
                                            @endif
                                            
                                        </tr>
                                    
                                    </tbody></table>
                            </div>
    
                        @else
    
                        @if($include_inflation=='yes')
                        @php
                        $av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                        @endphp
                        <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new)}}</h1>
                        @else
                        <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</h1>
                        @endif
    
    
                        @endif
                        @else
                        @if(isset($distribution_phase_interest_rate_2))
                            <div class="roundBorderHolder">
                                <table>
                                    <tbody>
                                    
                                        <tr>
                                            <td>
                                                Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                            </td>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <td>
                                                Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                            </td>
                                            @endif
                                        </tr>
                                        <tr>
                                            @if($include_inflation=='yes')
                                            @php
                                            $av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                            @endphp
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new)}} </strong>
                                            </td>
                                            @else
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}} </strong>
                                            </td>
                                            @endif
                                            @if(isset($distribution_phase_interest_rate_2))
                                            @if($include_inflation=='yes')
                                            @php
                                            $av43_new2=($monthly_annuity_amount2*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                                            @endphp
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new2)}} </strong>
                                            </td>
                                            @else
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}} </strong>
                                            </td>
                                            @endif
                                            @endif
                                        </tr>
                                        <!-- <tr>
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}} </strong>
                                            </td>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <td>
                                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}} </strong>
                                            </td>
                                            @endif
                                        </tr> -->
                                    
                                    </tbody></table>
                            </div>
                        @else
    
                        @if($include_inflation=='yes')
                        @php
                        $av43_new=($monthly_annuity_amount1*(1-(1+$av36_inf)**12)/(1-(1+$av36_inf)))/12;
                        @endphp
                            <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av43_new)}}</h1>
                        @else
                            <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</h1>
                        @endif
                        
    
    
                        @endif
                        @endif
    
                        <h1 class="pdfTitie">Total Withdrawal</h1>
    
                            
                        <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($accumulation_phase_interest_rate_2))
                                                <td>
                                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php } elseif ($deferment == 'yes' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($accumulation_phase_interest_rate_2))
                                                <td>
                                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php } elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($accumulation_phase_interest_rate_2))
                                                <td>
                                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($distribution_phase_interest_rate_2))
                                                <td>
                                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($interest2))
                                                <td>
                                                    Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                        $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'no'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($interest2))
                                                <td>
                                                    Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                @php
                                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                @php
                                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                                    $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                                @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'yes'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($interest2))
                                                <td>
                                                    Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    @php
                                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                                    @endphp
                                                    
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                    @php
                                                        $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                                    @endphp
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                            @if(isset($monthly_annuity_amount2))
                                <div class="roundBorderHolder">
                                    <table>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                </td>
                                                @if(isset($interest2))
                                                <td>
                                                    Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %
                                                </td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1 * $annuity_period_months)}}</strong>
                                                </td>
                                                @if(isset($monthly_annuity_amount2))
                                                <td>
                                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2 * $annuity_period_months)}} </strong>
                                                </td>
                                                @endif
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <h1 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span> 
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp 
                                    {{custome_money_format($av50_new)}}
                                </h1>
                            @endif
                        <?php } ?>
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_pdf')
    
                        </div>
                        
                @php
                    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Monthly_Annuity_For_Lumpsum_Investment')->first();
                    if(!empty($note_data1)){
                    @endphp
                    {!!$note_data1->description!!}
                @php } @endphp
                Report Date : {{date('d/m/Y')}}
            </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
                
                @if(isset($report) && $report=='detailed')
                
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
            <main class="mainPdf">
                @if($deferment=='yes')
                        
                <h1 class="bluebar" style="background:{{$city_color}}">Accumulation Phase <br> Projected Annual Investment Value</h1>
                <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                    <table>
                                <tbody>
                                    <tr>
                                            <th style="background:{{$address_color_background}}">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                                            <th style="background:{{$address_color_background}}">Year End Value @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
        
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}">Year End Value @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                            @endif
                                    </tr>
                                    @for($i=1;$i<=$deferment_period;$i++)
                                    @php
                                        $yev1=$initial_investment*(1+$accumulation_phase_interest_rate_1/100)**$i;
                                        if(isset($accumulation_phase_interest_rate_2))
                                        {
                                            $yev2=$initial_investment*(1+$accumulation_phase_interest_rate_2/100)**$i;
                                        }
                                    @endphp
                                    <tr>
                                            <td>{{$count_sec+$i}}</td>
                                            <td> @if($i==1) <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($initial_investment)}} @else -- @endif</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($yev1)}}</td>
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($yev2)}}</td>
                                            @endif
                                    </tr>
                                    @if($i%25==0 && $period>25 && $period>$i)
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
                                        <th style="background:{{$address_color_background}}">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
        
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}">Year End Value @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                </tr>
                                @endif
                                @endfor
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
                        
                <h1 class="bluebar" style="background:{{$city_color}}">Distribution Phase <br> Monthly Withdrawal & Projected Investment Value</h1>
                @if(isset($expected_inflation_rate))
                    <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                        <table>
                                <tbody>
                                <tr>
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    @if(isset($distribution_phase_interest_rate_2))
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    @endif
                                </tr>
                                        @php
                                            $ff1=0;
                                            $ff2=0;
                                            $z=1;
                                            for($i=1;$i<=$period*12;$i++)
                                            {
                                            if($i==1)
                                            {
                                                $as63=$av39;
                                            }else{
                                                $as63=$ax63;
                                            }
                                            
                                            $at63=$monthly_rate_of_return1;
                                            $au63=$as63+$as63*$at63;
                                            $av63=$av36_inf;
                                            if($i==1)
                                            {
                                                $aw63=$monthly_annuity_amount1;
                                            }else{
                                                $aw63=$aw63+$aw63*$av63;
                                            }
                                            $ax63=$au63-$aw63;
                                            $ff1+=$aw63;
            
                                            if(isset($distribution_phase_interest_rate_2))
                                            {
                                                if($i==1)
                                                {
                                                    $az63=$av40;
                                                }else{
                                                    $az63=$be63;
                                                }
                                                
                                                $ba63=$monthly_rate_of_return2;
                                                $bb63=$az63+$az63*$ba63;
                                                $bc63=$av36_inf;
                                                if($i==1)
                                                {
                                                    $bd63=$monthly_annuity_amount2;
                                                }else{
                                                    $bd63=$bd63+$bd63*$bc63;
                                                }
                                                $be63=$bb63-$bd63;
                                                $ff2+=$bd63;
                                            }
                                            
                                            
                                            if($i%12==0)
                                            {
                                            $ff1r=$ff1/12;
                                            if(isset($distribution_phase_interest_rate_2))
                                            {
                                                $ff2r=$ff2/12;
                                            }
                                            @endphp
                                                <tr>
                                                <td>{{$count_sec+$dif_sec+$z}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ff1r)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ax63)}}</td>
                                                @if(isset($distribution_phase_interest_rate_2))
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ff2r)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($be63)}}</td>
                                                @endif
                                                </tr>
            
                                            @if($z%25==0 && $period>25 && $period>$z)
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
                                                <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                                <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                                @if(isset($distribution_phase_interest_rate_2))
                                                <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                                @endif
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                            @endif
                                        </tr>
                                        @endif
                                        @php
                                        $z++;
                                        $ff1=0;
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                            $ff2=0;
                                        }
                                        }
                                        }
                                    @endphp
                            </tbody>
                            </table>
                </div>
                        
            </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
                
                @if($include_taxation=='yes')
                        
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
            <main class="mainPdf">
                            
                <h1 class="bluebar" style="background:{{$city_color}}">Annual Tax & Post-Tax Withdrawal </h1>
                <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                    <table>
                                    <tbody>
                                        <tr>
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                            @if(isset($accumulation_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                            @endif
                                    </tr>
                                    @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $g103=0;
        
                                        $w103=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $ax=$monthly_annuity_amount1;
                                        }else{
                                            $ax=$ax+$ax*$av41_inf;
                                        }
        
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
        
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $bo=$monthly_annuity_amount2;
                                        }else{
                                            $bo=$bo+$bo*$av41_inf;
                                        }
        
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
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($g103)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($w103)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @if($yr%25==0 && $period>25 && $period>$yr)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                        @if(isset($accumulation_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
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
                                </tbody>
                            </table>
                                </div>
                            
                            @endif
                        @else
                            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                                <table>
                                    <tbody>
                                    @if(isset($distribution_phase_interest_rate_2))
                                        <tr>
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                                //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                                //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                                $year_end_value_senario_2 = ($av40*(1+$monthly_rate_of_return2)**($i*12)-($monthly_annuity_amount2*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                            @endphp
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_2)}}</td>
                                            </tr>
                                            @if($i%25==0 && $period>25 && $period>$i)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        
                                    </tr>
    
                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                            $year_end_value_senario_1 = ($av39*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                            //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                            
                                        @endphp
                                        <tr>
                                            <td>{{$count_sec+$dif_sec+$i}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                            
                                        </tr>
                                        @if($i%25==0 && $period>25 && $period>$i)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                            
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
                
                            @if($include_taxation=='yes')
                            
                    <div class="page-break"></div>
                @include('frontend.calculators.common.header')
            <main class="mainPdf">
                            
                            <h1 class="bluebar" style="background:{{$city_color}}">Annual Tax & Post-Tax Withdrawal </h1>
                            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                            @if(isset($distribution_phase_interest_rate_2))
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                            @endif
                                    </tr>
                                    @php
                                        $yr=1;
                                        $l147=0;
                                        $q147=0;
                                        $ab107=0;
                                        $ag107=0;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        $av37=(1+($distribution_phase_interest_rate_1/100))**(1/12)-1;
                                        $ay107=$monthly_annuity_amount1/(1+$av37)**$i;
                                        $bb107=$ay107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                        if($yr<=$for_period_upto)
                                        {
                                            $bc=$monthly_annuity_amount1-$ay107;
                                        }else{
                                            $bc=$monthly_annuity_amount1-$bb107;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bf=$bc*($applicable_short_term_tax_rate/100);
                                        }else{
                                            $bf=$bc*($applicable_long_term_tax_rate/100);
                                        }
        
                                        $l147+=$bf;
                                        $bg107=$monthly_annuity_amount1-$bf;
                                        $q147+=$bg107;
                                        
                                        if(isset($distribution_phase_interest_rate_2))
                                        {
                                        $bm107=$monthly_annuity_amount2;
                                        $bk107=(1+($distribution_phase_interest_rate_2/100))**(1/12)-1;
                                        $bo107=$monthly_annuity_amount2/(1+$bk107)**$i;
                                        $br107=$bo107*(1+($assumed_inflation_rate_for_indexation/100))**($yr-1);
                                        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bs107=$bm107-$bo107;
                                        }else{
                                            $bs107=$bm107-$br107;
                                        }
        
                                        if($yr<=$for_period_upto)
                                        {
                                            $bv107=$bs107*$applicable_short_term_tax_rate/100;
                                        }else{
                                            $bv107=$bs107*$applicable_long_term_tax_rate/100;
                                        }
                                        $ab107+=$bv107;
                                        $bw107=$bm107-$bv107;
                                        $ag107+=$bw107;
                                        }
                                        if($i%12==0)
                                        {
                                    @endphp
                                    <tr>
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2*12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @if($yr%25==0 && $period>25 && $period>$yr)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                        @if(isset($distribution_phase_interest_rate_2))
                                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                        @endif
                                </tr>
                                @endif
                                @php
                                        $ag107=0;
                                        $ab107=0;
                                        $q147=0;
                                        $l147=0;
                                        $yr++;
                                    }
                                    }
                                @endphp
                                </tbody>
                            </table>
                            </div>
    
                            @endif
    
                            @endif
                            <!-- xxxxxxxxxxxx -->
                            
                        @else
                        </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
                        
                 <!--<div class="page-break"></div>-->
                @include('frontend.calculators.common.header')
            <main class="mainPdf">
    
                        <h1 class="bluebar" style="background:{{$city_color}}">Monthly Withdrawal & Projected Investment Value</h1>
    
                        @if(isset($expected_inflation_rate))
                        <div class="roundBorderHolder withBluebar">
                            <table>
                            <tbody>
                            <tr>
                                    <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    @if(isset($interest2))
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    @endif
                            </tr>
                            <tr>
                                <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                <th style="background:{{$address_color_background}}">Year End Balance</th>
                                @if(isset($interest2))
                                <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                <th style="background:{{$address_color_background}}">Year End Balance</th>
                                @endif
                            </tr>
                                    @php
                                        $ff1=0;
                                        $ff2=0;
                                        $z=1;
                                        for($i=1;$i<=$period*12;$i++)
                                        {
                                        if($i==1)
                                        {
                                            $as63=$initial_investment;
                                        }else{
                                            $as63=$ax63;
                                        }
                                        
                                        $at63=$monthly_rate_of_return1;
                                        $au63=$as63+$as63*$at63;
                                        $av63=$av36_inf;
                                        if($i==1)
                                        {
                                            $aw63=$monthly_annuity_amount1;
                                        }else{
                                            $aw63=$aw63+$aw63*$av63;
                                        }
                                        $ax63=$au63-$aw63;
                                        $ff1+=$aw63;
                                        if(isset($interest2))
                                        {
                                            if($i==1)
                                            {
                                                $az63=$initial_investment;
                                            }else{
                                                $az63=$be63;
                                            }
                                            
                                            $ba63=$monthly_rate_of_return2;
                                            $bb63=$az63+$az63*$ba63;
                                            $bc63=$av36_inf;
                                            if($i==1)
                                            {
                                                $bd63=$monthly_annuity_amount2;
                                            }else{
                                                $bd63=$bd63+$bd63*$bc63;
                                            }
                                            $be63=$bb63-$bd63;
                                            $ff2+=$bd63;
                                        }
                                        
                                        
                                        if($i%12==0)
                                        {
                                        $ff1r=$ff1/12;
                                        if(isset($interest2))
                                        {
                                            $ff2r=$ff2/12;
                                        }
                                        @endphp
                                            <tr>
                                            <td>{{$count_sec+$dif_sec+$z}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ff1r)}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ax63)}}</td>
                                            @if(isset($interest2))
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ff2r)}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($be63)}}</td>
                                            @endif
                                            </tr>
                                        @if($z%25==0 && $period>25 && $period>$z)
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
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @if(isset($interest2))
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        @if(isset($interest2))
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        @endif
                                    </tr>
                                    @endif
                                    @php
                                    $z++;
                                    $ff1=0;
                                    if(isset($interest2))
                                    {
                                        $ff2=0;
                                    }
                                    }
                                    }
                                @endphp
                        </tbody>
                        </table>
                                </div>
                        @else
                            <div class="roundBorderHolder withBluebar">
                                <table>
                                    <tbody>
                                    @if(isset($interest2))
                                        <tr>
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                            <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                            <th style="background:{{$address_color_background}}">Year End Balance</th>
                                        </tr>
        
                                        @for($i=1;$i<=$period;$i++)
                                            @php
                                                //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($initial_investment*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
                                                //Year End Value (AT67*(1+AV67)^(AR67*12)-(AX67*((1+AV67)^(AR67*12)-1)/AV67))
                                                $year_end_value_senario_2 = ($initial_investment*(1+$monthly_rate_of_return2)**($i*12)-($monthly_annuity_amount2*((1+$monthly_rate_of_return2)**($i*12)-1)/$monthly_rate_of_return2));
                                                
                                            @endphp
        
                                            <tr>
                                                <td>{{$count_sec+$dif_sec+$i}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}}</td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_2)}}</td>
                                            </tr>
                                            @if($i%25==0 && $period>25 && $period>$i)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        @if($deferment=='no')
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        @else
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    </tr>
                                    @for($i=1;$i<=$period;$i++)
                                        @php
                                            //Year End Value (AS67*(1+AU67)^(AR67*12)-(AW67*((1+AU67)^(AR67*12)-1)/AU67))
                                                $year_end_value_senario_1 = ($initial_investment*(1+$monthly_rate_of_return1)**($i*12)-($monthly_annuity_amount1*((1+$monthly_rate_of_return1)**($i*12)-1)/$monthly_rate_of_return1));
    
                                        @endphp
                                        <tr>
                                            <td>{{$count_sec+$dif_sec+$i}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value_senario_1)}}</td>
                                        </tr>
                                        @if($i%25==0 && $period>25 && $period>$i)
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
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        @if($deferment=='no')
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        @else
                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                                    </tr>
                                    @endif
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                                            </div>
                        @endif
                        
                        
    
                            </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
                
                    @if($include_taxation=='yes')
                            <div class="page-break"></div>
                @include('frontend.calculators.common.header')
            <main class="mainPdf">
                        
                            <h1 class="bluebar" style="background:{{$city_color}}">Annual Tax & Post-Tax Withdrawal</h1>
                            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                                <table class="small-font">
                                    <tbody>
                                        <tr>
                                            <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">Year</th>
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            @if(isset($interest2))
                                            <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            @endif
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}" style="width: 70px;">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                            @if(isset($interest2))
                                            <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                            <th style="background:{{$address_color_background}}" style="width: 70px;">Tax Payable</th>
                                            <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
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
                                        for($i=1;$i<=$period*12;$i++)
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $ax=$monthly_annuity_amount1;
                                        }else{
                                            $ax=$ax+$ax*$av41_inf;
                                        }
        
                                        $av37=(1+($interest1/100))**(1/12)-1;
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
                                        
                                        //--------ggggggggg
        
                                        if(isset($interest2))
                                        {
        
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        if($i==1)
                                        {
                                            $bo=$monthly_annuity_amount2;
                                        }else{
                                            $bo=$bo+$bo*$av41_inf;
                                        }
        
                                        $w103+=$bo;
        
                                        $bl63=(1+($interest2/100))**(1/12)-1;
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
                                        <td>{{$count_sec+$dif_sec+$yr}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($g103)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                        @if(isset($interest2))
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($w103)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                        @endif
                                    </tr>
                                    @if($yr%25==0 && $period>25 && $period>$yr)
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
                                                <table class="small-font">
                                    <tbody>
                                    <tr>
                                        <th style="background:{{$address_color_background}}" rowspan="2" style="vertical-align: middle;">Year</th>
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        @if(isset($interest2))
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}" style="width: 70px;">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                        @if(isset($interest2))
                                        <th style="background:{{$address_color_background}}" style="width: 70px;">Annual Withdrawal</th>
                                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
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
                                    for($i=1;$i<=$period*12;$i++)
                                    {
                                    //zzzzzzzzzzzz
                                    
                                    $ax=$monthly_annuity_amount1;
                                    
    
                                    $av37=(1+($interest1/100))**(1/12)-1;
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
    
                                    if(isset($interest2))
                                    {
    
                                    
                                    $bo=$monthly_annuity_amount2;
                                    
    
                                    $w103+=$bo;
    
                                    $bl63=(1+($interest2/100))**(1/12)-1;
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
                                    <td>{{$count_sec+$dif_sec+$yr}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                                    @if(isset($interest2))
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2*12)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                                    @endif
                                </tr>
                                @if($yr%25==0 && $period>25 && $period>$yr)
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
                                                <table class="small-font">
                                    <tbody>
                                    <tr>
                                        <th rowspan="2" style="vertical-align: middle;">Year</th>
                                        <th colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        @if(isset($interest2))
                                        <th colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        @endif
                                    </tr>
                                    <tr>
                                        <th>Annual Withdrawal</th>
                                        <th style="width: 70px;">Tax Payable</th>
                                        <th>Post - Tax Withdrawal</th>
                                        @if(isset($interest2))
                                        <th>Annual Withdrawal</th>
                                        <th style="width: 70px;">Tax Payable</th>
                                        <th>Post - Tax Withdrawal</th>
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
    
                            @endif
                            
                        @endif
                    </main>       
                
            
            <main class="mainPdf">
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Monthly_Annuity_For_Lumpsum_Investment')->first();
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
        
    </body>
</html>