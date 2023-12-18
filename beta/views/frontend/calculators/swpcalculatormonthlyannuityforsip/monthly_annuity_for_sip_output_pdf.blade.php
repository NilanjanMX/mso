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
    //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
    $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
    //Annuity Purchase Amount (1)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)

    if($deferment=='yes')
    {
        
        $annuity_purchase_amount1_pre = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
        $av35=$deferment_period*12;
        $annuity_purchase_amount1=$annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**$av35;
    }else{
        $annuity_purchase_amount1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($sip_period_months)-1)/$accumulation_monthly_return1);
    }
    


    //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
    $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
    //PV of Balance Required (1) T15/(1+AV35)^(AV33)
    $pv_of_balance_required1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
    //Balance Available for Annuity (1) AV31-AV37
    $balance_available_for_annuity1 = $annuity_purchase_amount1 - $pv_of_balance_required1;
    //Monthly Annuity Amount (1) (AV35*AV39)/(1-(1+AV35)^(-AV33))
    if($include_inflation=='yes')
    {
        if($distribution_phase_interest_rate_1==$expected_inflation_rate)
        {
            $monthly_annuity_amount1 = $balance_available_for_annuity1*(1+$distribution_monthly_return1)/$annuity_period_months;
        }else{
            $av46_inf=(1+$expected_inflation_rate/100)**(1/12)-1;
            $monthly_annuity_amount1 = $balance_available_for_annuity1/((1-((1+$av46_inf)**($annuity_period_months))*((1+$distribution_monthly_return1)**(-$annuity_period_months)))/($distribution_monthly_return1-$av46_inf));
        }
     
    }else{
     $monthly_annuity_amount1 = ($distribution_monthly_return1*$balance_available_for_annuity1)/(1-(1+$distribution_monthly_return1)**(-$annuity_period_months));
    }
   

    if (isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0){
        //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
        $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
        //Annuity Purchase Amount (2)  (1+AV29)*T9*(((1+AV29)^(AV28)-1)/AV29)

        if($deferment=='yes')
        {
            $annuity_purchase_amount2_pre = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
            $av35=$deferment_period*12;
            $annuity_purchase_amount2=$annuity_purchase_amount2_pre*(1+$accumulation_monthly_return2)**$av35;
        }else{
            $annuity_purchase_amount2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($sip_period_months)-1)/$accumulation_monthly_return2);
        }
        


        //Distribution Monthly Return (2)
         $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
         //PV of Balance Required (1) T15/(1+AV35)^(AV33)
        $pv_of_balance_required2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
        //Balance Available for Annuity (1) AV31-AV37
        $balance_available_for_annuity2 = $annuity_purchase_amount2 - $pv_of_balance_required2;
        //Monthly Annuity Amount (2) (AV35*AV39)/(1-(1+AV35)^(-AV33))
        

        if($include_inflation=='yes')
        {
            if($distribution_phase_interest_rate_2==$expected_inflation_rate)
            {
                $monthly_annuity_amount2 = $balance_available_for_annuity2*(1+$distribution_monthly_return2)/$annuity_period_months;
            }else{
                $av46_inf2=(1+$expected_inflation_rate/100)**(1/12)-1;
                $monthly_annuity_amount2 = $balance_available_for_annuity2/((1-((1+$av46_inf2)**($annuity_period_months))*((1+$distribution_monthly_return2)**(-$annuity_period_months)))/($distribution_monthly_return2-$av46_inf2));
            }
         
        }else{
            $monthly_annuity_amount2 = ($distribution_monthly_return2*$balance_available_for_annuity2)/(1-(1+$distribution_monthly_return2)**(-$annuity_period_months));
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
            <table class="table table-bordered text-center">
                <tbody>
                @if(isset($current_age) && $current_age>0)
                <tr>
                    <td style="Width:50%;">
                        <strong>Current Age</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$current_age}} Years
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="Width:50%;">
                        <strong>Monthly SIP Amount</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>SIP Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$sip_period?$sip_period:0}} Years
                    </td>
                </tr>
                @if(isset($deferment_period) && $deferment_period>0)
                <tr>
                    <td style="Width:50%;">
                        <strong>Deferment Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$deferment_period}} Years
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="Width:50%;">
                        <strong>SWP Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$annuity_period?$annuity_period:0}} Years
                    </td>
                </tr>
                @if(isset($balance_required))
                    <tr>
                        <td style="Width:50%;">
                            <strong>Balance Required</strong>
                        </td>
                        <td style="Width:50%;">
                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required)}}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            {{-- <table>
                <tbody>
                <tr>
                    <td style="Width:50%;">
                        <strong>Monthly SIP Amount</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>SIP Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$sip_period?$sip_period:0}} Years
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>Annuity Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$annuity_period?$annuity_period:0}} Years
                    </td>
                </tr>
                @if(isset($balance_required) && $balance_required>0)
                    <tr>
                        <td style="Width:50%;">
                            <strong>Balance Required</strong>
                        </td>
                        <td style="Width:50%;">
                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required)}}
                        </td>
                    </tr>
                @endif
                </tbody>
            </table> --}}
        </div>
    </div>
    
    

    @if(!isset($accumulation_phase_interest_rate_2) || $accumulation_phase_interest_rate_2 == '')
    <div style="padding: 0 0%;">
        <div class="roundBorderHolder withBluebarMrgn">
        	<table>
        		<tr>
        			<td style="width: 50%; ">
        				<strong>Assumed Rate of Return</strong>
        			</td>
        			<td style="width: 50%; padding: 0;">
        				<table>
        					<tr>
    	    					<td style="padding-left: 20px; text-align:left;border-bottom: 1px solid #458ff6;">Accumulation Phase</td>
    	    					<td style="padding: 6px 4px;text-align:right;border-bottom: 1px solid #458ff6;">
    	    						{{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
    	    					</td>
    	    				</tr>
    	    				<tr>
    	    					<td style="padding-left: 20px; text-align:left;">Distribution Phase</td>
    	    					<td style="padding: 6px 4px; text-align:right;">
    	    						{{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
    	    					</td>
    	    				</tr>
        				</table>
        				
        			</td>
        		</tr>
        	</table>
        </div>

    </div>
    @endif
    
    @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        <div style="padding: 0 0%;">
    @endif
            <h1 class="pdfTitie">Accumulated Corpus @if($deferment=='yes') @if(!isset($accumulation_phase_interest_rate_2)) @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} % @endif @endif</h1>
            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    
                        <tr>
                            <th style="width: 50%;">
                                Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
                            </th>
                            <th style="width: 50%;">
                                Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount2)}} </strong>
                            </td>
                        </tr>
                    
                    </tbody></table>
                
            </div>
            @else
                <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annuity_purchase_amount1)}}</h1>
            @endif

    @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2==0)
        </div>
    @endif

    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        <div style="padding: 0 0%;">
    @endif

            @if($include_inflation=='yes')

                <h1 class="pdfTitie">First Year Average Monthly SWP @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif  @endif</h1>

            @else

                <h1 class="pdfTitie">Monthly SWP Amount @if($deferment=='yes') @if(!isset($distribution_phase_interest_rate_2)) @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} % @endif @endif</h1>

            @endif
            
            {{-- <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                        <tr>
                            <th style="width: 50%;">
                                Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                            </th>
                            <th style="width: 50%;">
                                Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                            </th>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}} </strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong>
                                    <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($monthly_annuity_amount1)}}
                                </strong>
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div> --}}

            @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <th>
                                Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                            </th>
                            <th>
                                Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                            </th>
                        </tr>
                        <tr>
                            @if($include_inflation=='yes')
                            @php
                            
                            $av43_new=($monthly_annuity_amount1*(1-(1+$av46_inf)**12)/(1-(1+$av46_inf)))/12;
                        
                            @endphp
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($av43_new)}}</strong>
                            </td>
                            @else
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($monthly_annuity_amount1)}} </strong>
                            </td>
                            @endif
                            @if($include_inflation=='yes')
                            @php
                            $av43_new2=($monthly_annuity_amount2*(1-(1+$av46_inf)**12)/(1-(1+$av46_inf)))/12;
                            @endphp
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($av43_new2)}}</strong>
                            </td>
                            @else
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($monthly_annuity_amount2)}} </strong>
                            </td>
                            @endif
                        </tr>
                        </tbody>
                    </table>
                </div>
            @else

                <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($monthly_annuity_amount1)}}</h1>
                
            @endif
    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        </div>
    @endif

    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        <div style="padding: 0 0%;">
    @endif
            <h1 class="pdfTitie">Total Withdrawal</h1>
            <?php if($deferment=='yes' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                @if(isset($monthly_annuity_amount2))
                <div class="roundBorderHolder">
                    <table class="table text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td style="width: 50%;">
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
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td>
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
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
                                   
                                    <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                    @php
                                        $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                        $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                    @endphp
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span>  
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
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td style="width: 50%;">
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
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
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
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp
                                   
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                @php
                                    $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
                        @php
                            $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                        @endphp
                               
                        {{custome_money_format($av50_new)}}
                    </h1>
                @endif
            <?php }elseif ($deferment == 'yes' && $include_inflation == 'no' && $include_taxation == 'no'){?>
                @if(isset($monthly_annuity_amount2))
                <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp
                                   
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                    @php
                                        $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                    @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
                        @php
                            $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                        @endphp
                               
                        {{custome_money_format($av50_new)}}
                    </h1>
                @endif
            <?php }elseif ($deferment == 'no' && $include_inflation == 'yes' && $include_taxation == 'yes'){ ?>
                @if(isset($monthly_annuity_amount2))
                <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                <td style="width: 50%;">
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
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
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
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount1*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                @php
                                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                                    $av50_new=($monthly_annuity_amount2*((1+$av41_inf)**$annuity_period_months-1)/((1+$av41_inf)-1));
                                @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
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
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                 <td style="width: 50%;">
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp
                                   
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                @php
                                    $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
                        @php
                            $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                        @endphp
                               
                        {{custome_money_format($av50_new)}}
                    </h1>
                @endif
            <?php }elseif ($deferment == 'no' && $include_inflation == 'no' && $include_taxation == 'no'){ ?>
                @if(isset($monthly_annuity_amount2))
                <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    Scenario 1 @  {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                <td style="width: 50%;">
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                                @endif
                            </tr>
                            <tr>
                                 <td style="width: 50%;">
                                    @php
                                        $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                                    @endphp
                                   
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}}</strong>
                                </td>
                                @if(isset($monthly_annuity_amount2))
                                    @php
                                        $av50_new=($monthly_annuity_amount2*$annuity_period_months);
                                    @endphp
                                <td style="width: 50%;">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($av50_new)}} </strong>
                                </td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                    <h1 class="pdfBlueCell"><span class="pdfRupeeIcon">&#8377;</span> 
                        @php
                            $av50_new=($monthly_annuity_amount1*$annuity_period_months);
                        @endphp
                               
                        {{custome_money_format($av50_new)}}
                    </h1>
                @endif
            <?php } ?>

            
    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2==0)
        </div>
    @endif
    
    {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Monthly_Annuity_For_SIP')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
    @if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
                
        @include('frontend.calculators.common.header')
    <main class="mainPdf">
        <h1 class="bluebar" style="background:{{$city_color}}">
            Accumulation Phase <br>Projected Annual Investment Value
        </h1>
        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
            <table class="table table-bordered text-center">
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
                        <th style="background:{{$address_color_background}}">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        @if($deferment=='yes')
                        <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                        @endif
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                    </tr>
                   
                    @for($i=1;$i<=$s_count;$i++)
                    
                        @php
                            //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                           //$year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                         if($i<=$sip_period)
                         {
                            $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                         }else{
                            $year_end_value1 = $annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                         }
                           //Year End Value AT65*(1+AV65)^AU65
                           //$year_end_value2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);

                         if($i<=$sip_period)
                         {
                            $year_end_value2 = (1+$accumulation_monthly_return2)*$sip_amount*(((1+$accumulation_monthly_return2)**($i*12)-1)/$accumulation_monthly_return2);
                         }else{
                            $year_end_value2 = $annuity_purchase_amount2_pre*(1+$accumulation_monthly_return2)**(($i-$sip_period)*12);
                         }
                        @endphp
                        <tr>
                            <td>{{$i+$count_sec}}</td>
                            @if($i>$sip_period)
                            <td>--</td>
                            @else
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                            @endif
                            @if($deferment=='yes')
                            @php
                            if($i>$sip_period){
                                $ci=$ci;
                            }else{
                                $ci=($sip_amount*12)+$ci;
                            }
                            
                            @endphp
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ci)}}</td>
                            @endif
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value2)}}</td>
                        </tr>
                        @if ($i%24 == 1 && $i != $s_count && $i != 1)
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
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                                        @if($deferment=='yes')
                                        <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                        @endif
                                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                                    </tr>
                        @endif
                    @endfor
                @else
                    <tr>
                        <th style="background:{{$address_color_background}}; vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        @if($deferment=='yes')
                        <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                        @endif
                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                    </tr>
                    @for($i=1;$i<=$s_count;$i++)
                        @php
                            //Year End Value (1+AV64)*AT64*(((1+AV64)^(AU64*12)-1)/AV64)
                         if($i<=$sip_period)
                         {
                            $year_end_value1 = (1+$accumulation_monthly_return1)*$sip_amount*(((1+$accumulation_monthly_return1)**($i*12)-1)/$accumulation_monthly_return1);
                         }else{
                            $year_end_value1 = $annuity_purchase_amount1_pre*(1+$accumulation_monthly_return1)**(($i-$sip_period)*12);
                         }
                           
                        @endphp
                        <tr>
                            <td>{{$i+$count_sec}}</td>
                            @if($i>$sip_period)
                            <td>--</td>
                            @else
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount*12)}}</td>
                            @endif
                            @if($deferment=='yes')
                            @php
                            if($i>$sip_period){
                                $ci=$ci;
                            }else{
                                $ci=($sip_amount*12)+$ci;
                            }
                            
                            @endphp
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ci)}}</td>
                            @endif
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value1)}}</td>
                        </tr>
                        @if ($i%24 == 1 && $i != $s_count && $i != 1)
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
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th style="background:{{$address_color_background}}; vertical-align: middle;">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                                        @if($deferment=='yes')
                                        <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                        @endif
                                        <th style="background:{{$address_color_background}}">Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                    </tr>
                        @endif
                    @endfor
                @endif
                </tbody>
            </table>
        </div>

    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Monthly_Annuity_For_SIP')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
    @php } @endphp
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
    @if(isset($report) && $report=='detailed')
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
            <table class="table table-bordered text-center">
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
                @php
                    $val = $annuity_period*12;
                    $j = 0;
                @endphp
                @for($i=1;$i<=$val;$i++)
                @php
                
                if($i==1)    
                {
                    $aw107=$monthly_annuity_amount1;
                    $au107=$annuity_purchase_amount1+$annuity_purchase_amount1*$distribution_monthly_return1;

                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                    {
                        $bd107=$monthly_annuity_amount2;
                        $bb107=$annuity_purchase_amount2+$annuity_purchase_amount2*$distribution_monthly_return2;
                    }
                    

                }else{
                    $aw107=$aw107+$aw107*$av46_inf;
                    $au107=$ax107+$ax107*$distribution_monthly_return1;

                    if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                    {
                        $bd107=$bd107+$bd107*$av46_inf2;
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
                @php 
                $h107=0;
                $w107=0;
                $yr++;
                
                @endphp
                    @if ($j%23 == 1 && $j != $val && $j != 1)
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
                        <table class="table table-bordered text-center">
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
                    @endif
                @php
                $j++;
                    }
                @endphp
                @endfor
            </table>
            </div>
            @else
            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
            <table class="table table-bordered text-center">
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
                    @php
                        $j = 0;
                    @endphp
                    @for($i=1;$i<=$annuity_period;$i++)
                        @php
                            //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                            $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                            $year_end_balance2 = ($annuity_purchase_amount2*(1+$distribution_monthly_return2)**($i*12)-($monthly_annuity_amount2*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));

                        @endphp
                        <tr>
                            <td>{{$i+$count_sec+$sp+$dp}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance2)}}</td>
                        </tr>
                        @if ($j%23 == 1 && $j != $annuity_period && $j != 1)
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
                            <table class="table table-bordered text-center">
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
                        
                        @php
                            $j++;
                        @endphp
                    @endfor
                @else
                    <tr>
                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                        <th style="background:{{$address_color_background}}" colspan="2"> @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>

                    </tr>
                    <tr>
                        <th style="background:{{$address_color_background}}">Monthly SWP</th>
                        <th style="background:{{$address_color_background}}">Year End Balance</th>
                    </tr>
                    @php
                        $j = 0;
                    @endphp
                    @for($i=1;$i<=$annuity_period;$i++)
                        @php
                            //Year End Balance (AS106*(1+AU106)^(AR106*12)-(AW106*((1+AU106)^(AR106*12)-1)/AU106))
                            $year_end_balance1 = ($annuity_purchase_amount1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));

                        @endphp
                        <tr>
                            <td>{{$i+$count_sec+$sp+$dp}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_balance1)}}</td>
                        </tr>
                        @if ($j%23 == 1 && $j != $annuity_period && $j != 1)
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
                            <table class="table table-bordered text-center">    
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="2"> @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} %</th>
            
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Monthly SWP</th>
                                    <th style="background:{{$address_color_background}}">Year End Balance</th>
                                </tr>
                        @endif
                        
                        @php
                            $j++;
                        @endphp
                    @endfor
                @endif
                </tbody>
            </table>
            </div>
            @endif

            @if($include_taxation=='yes')
            <h1 class="midheading">Annual Tax & Post-Tax Withdrawal</h1>
            <div class="roundBorderHolder withBluebar">
            <table class="table table-bordered text-center">
                <tbody>
                 <tr>
                        <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                        @endif
                    </tr>
                    <tr>
                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                        <th style="background:{{$address_color_background}}">Tax Payable</th>
                        <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                        @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                        <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                        <th style="background:{{$address_color_background}}">Tax Payable</th>
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
                    $val = $annuity_period*12;
                    $j = 0;
                    for($i=1;$i<=$val;$i++)
                    {

                    $av41_inf=(1+($expected_inflation_rate/100))**(1/12)-1;
                    if($i==1)
                    {
                        $ax=$monthly_annuity_amount1;
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
                        $bo=$monthly_annuity_amount2;
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
                @php
                        $g103=0;
                        $q147=0;
                        $l147=0;
                        
                        $w103=0;
                        $ag107=0;
                        $ab107=0;
                        $yr++;
                    
                    @endphp
                        @if ($i%24 == 1 && $i != $val && $i != 1)
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
                            <table class="table table-bordered text-center">    
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                    <th style="background:{{$address_color_background}}">Tax Payable</th>
                                    <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                    <th style="background:{{$address_color_background}}">Tax Payable</th>
                                    <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                        @endif
                    @php
                    $j++;
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
                    $val = $annuity_period*12;
                    $j = 0;
                    for($i=1;$i<=$val;$i++)
                    // for($i=1;$i<=$annuity_period*12;$i++)
                    {
                    //zzzzzzzzzzzz
                    
                    $ax=$monthly_annuity_amount1;
                    
                   

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

                   
                    $bo=$monthly_annuity_amount2;
                   

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
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount1*12)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($l147)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($q147)}}</td>
                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_annuity_amount2*12)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ab107)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($ag107)}}</td>
                    @endif
                </tr>
                @php
                        $g103=0;
                        $q147=0;
                        $l147=0;
                        
                        $w103=0;
                        $ag107=0;
                        $ab107=0;
                        $yr++;
                    
                @endphp
                        @if ($i%24 == 1 && $i != $val && $i != 1)
                        
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
                            <table class="table table-bordered text-center">    
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle;" rowspan="2">@if(isset($current_age) && $current_age!=0) Age @else Year @endif</th>
                                    <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</th>
                                    @endif
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                    <th style="background:{{$address_color_background}}">Tax Payable</th>
                                    <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                    @if(isset($distribution_phase_interest_rate_2) && $distribution_phase_interest_rate_2>0)
                                    <th style="background:{{$address_color_background}}">Annual Withdrawal</th>
                                    <th style="background:{{$address_color_background}}">Tax Payable</th>
                                    <th style="background:{{$address_color_background}}">Post - Tax Withdrawal</th>
                                    @endif
                            </tr>
                        @endif
                @php
                $j++;
                        }
                    }
                @endphp

                @endif

                </tbody>
            </table>
            </div>

            @endif

            <p style="text-align: left;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
        

            
    @endif
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Monthly_Annuity_For_SIP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
    </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif

    @endif
    @include('frontend.calculators.suggested.pdf')

</body>
</html>