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

        //Accumulation Period (Months) (AD41-AD40)*12
        $accumulation_period_months = ($retirement_age-$current_age)*12;
        //Distribution Period (Months) (AD42-AD41)*12
        $distribution_period_months = ($age_at_which_annuity_ends-$retirement_age)*12;
        //Monthly Return During Accumulation Phase(1) (1+AG48%)^(1/12)-1
        $monthly_return_during_accumulation_phase1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1;
        //Monthly Return During Accumulation Phase(2) (1+AN48%)^(1/12)-1
        $monthly_return_during_accumulation_phase2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1;
        //Monthly Inflation During Accumulation Phase (1+O48%)^(1/12)-1
        $monthly_inflation_during_accumulation_phase = (1+$expected_inflation_rate_till_retirement/100)**(1/12)-1;
    
        //Monthly Return During Distribution Phase(1) (1+AG49%)^(1/12)-1
        $monthly_return_during_distribution_phase1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
        //Monthly Return During Distribution Phase(2) (1+AN49%)^(1/12)-1
        $monthly_return_during_distribution_phase2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
        //Monthly Inflation Rate Post Retirement (1+O49%)^(1/12)-1
        if($expected_inflation_rate_post_retirement=='0')
            {
                $monthly_inflation_rate_post_retirement = '0';
            }else{
                $monthly_inflation_rate_post_retirement = (1+$expected_inflation_rate_post_retirement/100)**(1/12)-1;
            }
        //1st Month Annuity Amount Post Retirement AF43*(1+BF37)^BF33
        $first_month_annuity_post_retirement = $current_monthly_expense*(1+$monthly_inflation_during_accumulation_phase)**$accumulation_period_months;
        //Retirement Corpus Required for Annuity(1) (BF41+BF41*BF40)*(1-(1+BF40)^(BF34-1)*(1+BF38)^(-BF34+1))/(BF38-BF40)+BF41
        $retirement_corpus_required_for_annuity1 = ($first_month_annuity_post_retirement+$first_month_annuity_post_retirement*$monthly_inflation_rate_post_retirement)*(1-(1+$monthly_inflation_rate_post_retirement)**($distribution_period_months-1)*(1+$monthly_return_during_distribution_phase1)**(-$distribution_period_months+1))/($monthly_return_during_distribution_phase1-$monthly_inflation_rate_post_retirement)+$first_month_annuity_post_retirement;
        //Retirement Corpus Required for Annuity(2) (BF41+BF41*BF40)*(1-(1+BF40)^(BF34-1)*(1+BF39)^(-BF34+1))/(BF39-BF40)+BF41
        if($monthly_inflation_rate_post_retirement!='0')
            {
                $retirement_corpus_required_for_annuity2 = ($first_month_annuity_post_retirement+$first_month_annuity_post_retirement*$monthly_inflation_rate_post_retirement)*(1-(1+$monthly_inflation_rate_post_retirement)**($distribution_period_months-1)*(1+$monthly_return_during_distribution_phase2)**(-$distribution_period_months+1))/($monthly_return_during_distribution_phase2-$monthly_inflation_rate_post_retirement)+$first_month_annuity_post_retirement;
            }else{
                $retirement_corpus_required_for_annuity2='0';
            }
        //Retirement Corpus Required for Balance(1) AF44/(1+BF38)^BF34
        $retirement_corpus_required_for_balance1 = $balance_required_at_end_of_annuity/(1+$monthly_return_during_distribution_phase1)**$distribution_period_months;
        //Retirement Corpus Required for Balance(2)
        $retirement_corpus_required_for_balance2 = $balance_required_at_end_of_annuity/(1+$monthly_return_during_distribution_phase2)**$distribution_period_months;
        //Future Value of Current Investment AJ57*(1+AG58%)^(AD41-AD40)
        $future_value_of_current_investment = $current_market_value_of_investment*(1+$expected_rate_of_return_current_investment/100)**($retirement_age-$current_age);
    
        //Other Amount Receivable on Retirement
        $other_amount_receivable_on_retirement = $other_amount_receivable_on_retirement;
        //Corpus required at the time of Retirement(1) BF42+BF44-BF46-BF47
        $corpus_required_at_the_time_of_retirement1 = $retirement_corpus_required_for_annuity1+$retirement_corpus_required_for_balance1-$future_value_of_current_investment-$other_amount_receivable_on_retirement;
        //Corpus required at the time of Retirement(2)
        $corpus_required_at_the_time_of_retirement2 = $retirement_corpus_required_for_annuity2+$retirement_corpus_required_for_balance2-$future_value_of_current_investment-$other_amount_receivable_on_retirement;
        //Total Retirement Corpus (1) BF42+BF44
        $total_retirement_corpus1 = $retirement_corpus_required_for_annuity1+$retirement_corpus_required_for_balance1;
        //Total Retirement Corpus (2)
        $total_retirement_corpus2 = $retirement_corpus_required_for_annuity2+$retirement_corpus_required_for_balance2;
        //Fund Value at Retirement BF46+BF47
        $fund_value_at_retirement = $future_value_of_current_investment+$other_amount_receivable_on_retirement;
    
        //Balance After 5 Years(1)
        $balance_after_5_years1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**($accumulation_period_months-60);
        $balance_after_5_years2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**($accumulation_period_months-60);
    
        //Balance After 10 Years(1) BF48/(1+BF35)^(BF33-120)
        $balance_after_10_years1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**($accumulation_period_months-120);
        $balance_after_10_years2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**($accumulation_period_months-120);
        //Projected Monthly Expenses At Retirement
        $projected_monthly_expenses_at_retirement = $first_month_annuity_post_retirement;
        //Retirement Corpus Required (Scenario 1)
        $retirement_corpus_required_scenario1 = $corpus_required_at_the_time_of_retirement1;
        //Retirement Corpus Required (Scenario 2)
        $retirement_corpus_required_scenario2 = $corpus_required_at_the_time_of_retirement2;
        //SIP Till Retirement(1) (BF48*BF35)/((1+BF35)*((1+BF35)^((AD41-AD40)*12)-1))
        $sip_till_retirement1 = ($corpus_required_at_the_time_of_retirement1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(($retirement_age-$current_age)*12)-1));
        //SIP Till Retirement(2) (BF49*BF36)/((1+BF36)*((1+BF36)^((AD41-AD40)*12)-1))
    
        if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
         $sip_till_retirement2 = ($corpus_required_at_the_time_of_retirement2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(($retirement_age-$current_age)*12)-1));
        }else{
            $sip_till_retirement2='';
        }
        //SIP For 5 Years(1) IF(AD41-AD40<5,"NA",(BF54*BF35)/((1+BF35)*((1+BF35)^(60)-1)))
    
        if (($retirement_age-$current_age)<5){
            $sip_for_5_years1 = "";
        }else{
            $sip_for_5_years1 = ($balance_after_5_years1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(60)-1));
        }
        if (($retirement_age-$current_age)<5){
            $sip_for_5_years2 = "";
        }else{
            if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
            $sip_for_5_years2 = ($balance_after_5_years2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(60)-1));
            }else{
                $sip_for_5_years2 = '';
            }
            }
    
    
        //SIP For 10 Years(1) IF(AD41-AD40<10,"NA",(BF56*BF35)/((1+BF35)*((1+BF35)^(120)-1)))
        if (($retirement_age-$current_age)<10){
            $sip_for_10_years1 = "";
        }else{
            $sip_for_10_years1 = ($balance_after_10_years1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(120)-1));
        }
    
        if (($retirement_age-$current_age)<10){
            $sip_for_10_years2 = "";
        }else{
            if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
            $sip_for_10_years2 = ($balance_after_10_years2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(120)-1));
            }else{
                    $sip_for_10_years2 = 0;
            }
            }
    
        //Lumpsum Investment(1) BF48/(1+BF35)^((AD41-AD40)*12)
        $limpsum_investment1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**(($retirement_age-$current_age)*12);
        $limpsum_investment2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**(($retirement_age-$current_age)*12);
    
        //echo $limpsum_investment2; die();
    @endphp      
    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Retirement @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Need Based Calculator @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Current Age:</strong>
                            </td>
                            <td style="Width:50%;">
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Retirement Age:</strong>
                            </td>
                            <td style="Width:50%;">
                                {{$retirement_age?$retirement_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Annuity Ends at Age:</strong>
                            </td>
                            <td style="Width:50%;">
                                {{$age_at_which_annuity_ends?$age_at_which_annuity_ends:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Current Monthly Expense:</strong>
                            </td>
                            <td style="Width:50%;">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($current_monthly_expense)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Balance Required at Age {{$age_at_which_annuity_ends?$age_at_which_annuity_ends:0}}:</strong>
                            </td>
                            <td style="Width:50%;">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($balance_required_at_end_of_annuity)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="roundBorderHolder withBluebarMrgn">
                        <table>
                            <tbody>
                            <tr>
                                <td  colspan="2">
                                    <strong>Expected Inflation Rate</strong>
                                </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>
                                        <strong>Assumed Return</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 1</strong>
                                    </td>
                    
                                    <td>
                                        <strong>Scenario 2</strong>
                                    </td>
                                @else
                                    <td colspan="2">
                                        <strong>Assumed Return</strong>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Pre Retirement</td>
                                <td>{{$expected_inflation_rate_till_retirement?number_format($expected_inflation_rate_till_retirement, 2, '.', ''):0}} %</td>
                                <td>Accumulation Phase</td>
                                <td>{{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>{{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Post Retirement</td>
                                <td>{{$expected_inflation_rate_post_retirement?number_format($expected_inflation_rate_post_retirement, 2, '.', ''):0}} %</td>
                                <td>Distribution Phase</td>
                                <td>{{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</td>
                                @if($distribution_phase_interest_rate_2>0)
                                    <td>{{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                </div>
                
                <div class="roundBorderHolder withBluebarMrgn">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <strong>Projected Monthly Expense at Retirement</strong>
                            </td>
                            <td><strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($projected_monthly_expenses_at_retirement)}}</strong> </td>
                        </tr>
                        <tr>
                            <td  style="vertical-align: middle">
                                <strong>Total Retirement Corpus Required</strong>
                            </td>
                            <td style="padding: 0">
                                <table width="100%">
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <tr>
                
                                            <td>
                                                <strong>Scenario 1</strong>
                                            </td>
                
                                            <td>
                                                <strong>Scenario 2</strong>
                                            </td>
                
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($total_retirement_corpus1)}}</td>
                                        @if($accumulation_phase_interest_rate_2>0)
                                        <td><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($total_retirement_corpus2)}}</td>
                                        @endif
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="roundBorderHolder withBluebarMrgn">
                    <table>
                        <tbody>
                        <tr>
                            <td style="">
                                <strong>Current Market Value of Investment</strong>
                            </td>
                            <td style="">
                                @if($current_market_value_of_investment>0)
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($current_market_value_of_investment)}}
                                @else
                                    <span class="pdfRupeeIcon">&#8377;</span> 0
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="">
                                <strong>Assumed Rate of Return</strong>
                            </td>
                            <td style="">{{$expected_rate_of_return_current_investment?number_format($expected_rate_of_return_current_investment, 2, '.', ''):0}} % </td>
                        </tr>
                        <tr>
                            <td style="">
                                <strong>Expected Future Value of Current Investment</strong>
                            </td>
                            <td style=""><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($future_value_of_current_investment)}} </td>
                        </tr>
                        <tr>
                            <td style="">
                                <strong>Other Amount Receivable on Retirement</strong>
                            </td>
                            <td style=""><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($other_amount_receivable_on_retirement)}} </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            </main>

            @include('frontend.calculators.common.watermark')
            @include('frontend.calculators.common.footer')
            
            <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">

                    @if(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus1 && ($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus2 && $accumulation_phase_interest_rate_2>0)
                        <div class="roundBorderHolder withBluebarMrgn">
                            <table>
                                <tbody>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle">
                                        <strong>Balance Retirement Corpus Required</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 1</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 2</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        You don't need any further investment for the above Goal!
                                    </td>
                                    <td>
                                        You don't need any further investment for the above Goal!
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h1 class="pdfTitie">Available Investment Options:</h1>
                        <div class="roundBorderHolder">
                            <table class="table text-center">
                                <tbody>
                                <tr>
                                    <td colspan="2" style="padding: 20px;">
                                        <strong style="font-size: 18px;">You don't need any further investment for the above Goal!</strong>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    @elseif(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus1)
                        <div class="roundBorderHolder withBluebarMrgn">
                            <table>
                                <tbody>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle">
                                        <strong>Balance Retirement Corpus Required</strong>
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <strong>Scenario 1</strong>
                                        </td>
                                        <td>
                                            <strong>Scenario 2</strong>
                                        </td>
                                    @else
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>
                                        You don't need any further investment for the above Goal!
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($total_retirement_corpus2-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                        </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h1 class="pdfTitie">Available Investment Options:</h1>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                <tr>
                                    <th>
                                        <strong>Investment Option</strong>
                                    </th>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <th>
                                            <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                        </th>
                                        <th>
                                            <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                        </th>
                                    @else
                                        <th>
                                            <strong>Amount</strong>
                                        </th>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP Till Age {{$retirement_age}}</td>
                                    <td rowspan="4" style="text-align: center;"><strong>You don't need any further investment for the above Goal!</strong></td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_till_retirement2)}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 5 Years</td>
    
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center">
                                            <span class="pdfRupeeIcon">&#8377;</span>
                                            {{($sip_for_5_years2 != "")?custome_money_format($sip_for_5_years2):"NA"}}
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 10 Years</td>
    
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_10_years2 != "")?custome_money_format($sip_for_10_years2):"NA"}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Lumpsum Investment</td>
    
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($limpsum_investment2 != "")?custome_money_format($limpsum_investment2):"NA"}}</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @elseif(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus2 && $accumulation_phase_interest_rate_2>0)
                        <div class="roundBorderHolder withBluebarMrgn">
                            <table>
                                <tbody>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle">
                                        <strong>Balance Retirement Corpus Required</strong>
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <strong>Scenario 1</strong>
                                        </td>
                                        <td>
                                            <strong>Scenario 2</strong>
                                        </td>
                                    @else
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($total_retirement_corpus1-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            You don't need any further investment for the above Goal!
                                        </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h1 class="pdfTitie">Available Investment Options:</h1>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                <tr>
                                    <th>
                                        <strong>Investment Option</strong>
                                    </th>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <th>
                                            <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                        </th>
                                        <th>
                                            <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                        </th>
                                    @else
                                        <th>
                                            <strong>Amount</strong>
                                        </th>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP Till Age {{$retirement_age}}</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_till_retirement1)}} </td>
    
                                    <td rowspan="4" style="vertical-align: middle;"><strong>You don't need any further investment for the above Goal!</strong></td>
    
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 5 Years</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_5_years1 != "")?custome_money_format($sip_for_5_years1):"NA"}}</td>
    
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 10 Years</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_10_years1 != "")?custome_money_format($sip_for_10_years1):"NA"}}</td>
    
                                </tr>
                                <tr>
                                    <td style="">Lumpsum Investment</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($limpsum_investment1 != "")?custome_money_format($limpsum_investment1):"NA"}}</td>
    
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="roundBorderHolder withBluebarMrgn">
                            <table>
                                <tbody>
                                <tr>
                                    <td rowspan="2" style="vertical-align: middle">
                                        <strong>Balance Retirement Corpus Required</strong>
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <strong>Scenario 1</strong>
                                        </td>
                                        <td>
                                            <strong>Scenario 2</strong>
                                        </td>
                                    @else
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($total_retirement_corpus1-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($total_retirement_corpus2-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                        </td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <h1 class="pdfTitie">Available Investment Options:</h1>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                <tr>
                                    <th>
                                        <strong>Investment Option</strong>
                                    </th>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <th>
                                            <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                        </th>
                                        <th>
                                            <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                        </th>
                                    @else
                                        <th>
                                            <strong>Amount</strong>
                                        </th>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP Till Age {{$retirement_age}}</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_till_retirement1)}} </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_till_retirement2)}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 5 Years</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_5_years1 != "")?custome_money_format($sip_for_5_years1):"NA"}}</td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_5_years2 != "")?custome_money_format($sip_for_5_years2):"NA"}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Monthly SIP For 10 Years</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_10_years1 != "")?custome_money_format($sip_for_10_years1):"NA"}}</td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($sip_for_10_years2 != "")?custome_money_format($sip_for_10_years2):"NA"}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td style="">Lumpsum Investment</td>
                                    <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($limpsum_investment1 != "")?custome_money_format($limpsum_investment1):"NA"}}</td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td style="text-align: center"><span class="pdfRupeeIcon">&#8377;</span> {{($limpsum_investment2 != "")?custome_money_format($limpsum_investment2):"NA"}}</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                    
                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_pdf')
                    
                    @php
                        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Retirement_Planning')->first();
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
            
            <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">
                    @php
                        $total_months = ($age_at_which_annuity_ends-$retirement_age)*12;
                        $age = $retirement_age;
                        $year_monthly_annuity1 = 0;
                        $year_monthly_annuity2 = 0;
                        $icount = 0;
                    @endphp

                    <h1 class="bluebar" style="background:{{$city_color}}">Monthly Annuity & Yearwise Projected Value</h1>
                    <div class="roundBorderHolder withBluebar">
                        <table>
                            <tbody>
                            
                                @if($accumulation_phase_interest_rate_2>0)
                                <tr>
                                <th style="vertical-align: middle;background:{{$address_color_background}}" rowspan="2">
                                    <strong>Age</strong>
                                </th>
                                <th style="background:{{$address_color_background}}" colspan="2">
                                    <strong>Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th style="background:{{$address_color_background}}" colspan="2">
                                    <strong>Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                </th>
                                </tr>
                                @else
    
                                @endif
                            
                            <tr>
                                @if($accumulation_phase_interest_rate_2<=0)
                                    <th style="vertical-align: middle;background:{{$address_color_background}}">
                                        <strong>Age</strong>
                                    </th>
                                @endif
                                <th style="background:{{$address_color_background}}"><strong>Monthly Annuity</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Balance EOY</strong></th>
                                @if($accumulation_phase_interest_rate_2>0)
                                <th style="background:{{$address_color_background}}"><strong>Monthly Annuity</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Balance EOY</strong></th>
                                @endif
                            </tr>
                            @for($m=1;$m<=$total_months;$m++)
                                @php
    
                                    //Option 1
                                    //Retirement Corpus
                                    if ($m==1){
                                         $retirement_corpus1 = $total_retirement_corpus1;
                                    }else{
                                         $retirement_corpus1 = $eom_value1;
                                    }
    
                                    //Monthly Annuity IF(BH34>=BG34,BF58,0)
                                    if ($total_months>=$m && $m==1){
                                        $monthly_annuity1 = $projected_monthly_expenses_at_retirement;
                                    }elseif ($total_months>=$m && $m>1){
                                        $monthly_annuity1 = $monthly_annuity1+$monthly_annuity1*$monthly_inflation_rate_post_retirement;
                                    }
                                    else{
                                        $monthly_annuity1 = 0;
                                    }
    
                                    $year_monthly_annuity1 += $monthly_annuity1;
    
                                    $balance_left1 = $retirement_corpus1-$monthly_annuity1;
                                    //$monthly_return_during_distribution_phase1
                                    //EOM Value IF(BL34>=BK34,BL34+BL34*BM34,0)
                                    if ($balance_left1>=$monthly_inflation_rate_post_retirement){
                                        $eom_value1 =$balance_left1+$balance_left1*$monthly_return_during_distribution_phase1;
                                    }else{
                                        $eom_value1 = 0;
                                    }
                                     //Option 1 End
    
                                     //Option 2
                                    //Retirement Corpus
                                    if ($m==1){
                                         $retirement_corpus2 = $total_retirement_corpus2;
                                    }else{
                                         $retirement_corpus2 = $eom_value2;
                                    }
    
                                    //Monthly Annuity IF(BH34>=BG34,BF58,0)
                                    if ($total_months>=$m && $m==1){
                                        $monthly_annuity2 = $projected_monthly_expenses_at_retirement;
                                    }elseif ($total_months>=$m && $m>1){
                                        $monthly_annuity2 = $monthly_annuity2+$monthly_annuity2*$monthly_inflation_rate_post_retirement;
                                    }
                                    else{
                                        $monthly_annuity2 = 0;
                                    }
    
                                    $year_monthly_annuity2 += $monthly_annuity2;
    
                                    $balance_left2 = $retirement_corpus2-$monthly_annuity2;
                                    //$monthly_return_during_distribution_phase1
                                    //EOM Value IF(BL34>=BK34,BL34+BL34*BM34,0)
                                    if ($balance_left2>=$monthly_inflation_rate_post_retirement){
                                        $eom_value2 =$balance_left2+$balance_left2*$monthly_return_during_distribution_phase2;
                                    }else{
                                        $eom_value2 = 0;
                                    }
                                     //Option 2 End
    
                                    
                                @endphp
    
                                @if($m%12==0)
                                    @php
                                        $age++;
                                        $icount++;
                                    @endphp
                                    <tr>
                                        <td>{{$age}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_monthly_annuity1/12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($eom_value1)}}</td>
                                        @if($accumulation_phase_interest_rate_2>0)
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_monthly_annuity1/12)}}</td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($eom_value2)}}</td>
                                            @endif
                                    </tr>
                                    @php
                                        $year_monthly_annuity1 = 0;
                                    @endphp
                                @endif
                                
                                
                                
                                
@if($icount >= 22 && $icount%22 == 0)
            @php $icount++; @endphp
                    </tbody>
                </table>
            </div>
            <!--<p style="text-align: left;">-->
            <!--    * Monthly annuity will increase every month due to effect of inflation. For simplicity, the amount shown above under monthly annuity is the monthly average of total annuity payments received during the year. <br><br>-->
            <!--    * The Annuity is received on the 1st day of each month starting the 1st month.-->
            <!--</p>-->
            <!--Report Date : {{date('d/m/Y')}}-->
            
        </main>


        @include('frontend.calculators.common.watermark')
            @if($footer_branding_option == "all_pages")
                @include('frontend.calculators.common.footer')
            @endif
            
            <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">
                    

                    <div class="roundBorderHolder withBluebar">
                        <table>
                            <tbody>
                            
                                @if($accumulation_phase_interest_rate_2>0)
                                <tr>
                                <th style="vertical-align: middle;background:{{$address_color_background}}" rowspan="2">
                                    <strong>Age</strong>
                                </th>
                                <th style="background:{{$address_color_background}}" colspan="2">
                                    <strong>Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th style="background:{{$address_color_background}}" colspan="2">
                                    <strong>Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                </th>
                                </tr>
                                @else
    
                                @endif
                            
                            <tr>
                                @if($accumulation_phase_interest_rate_2<=0)
                                    <th style="vertical-align: middle;background:{{$address_color_background}}">
                                        <strong>Age</strong>
                                    </th>
                                @endif
                                <th style="background:{{$address_color_background}}"><strong>Monthly Annuity</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Balance EOY</strong></th>
                                @if($accumulation_phase_interest_rate_2>0)
                                <th style="background:{{$address_color_background}}"><strong>Monthly Annuity</strong></th>
                                <th style="background:{{$address_color_background}}"><strong>Balance EOY</strong></th>
                                @endif
                            </tr>
            
@endif








                            @endfor
                            </tbody>
                        </table>
                    </div>
                    <p style="text-align: left;">
                        * Monthly annuity will increase every month due to effect of inflation. For simplicity, the amount shown above under monthly annuity is the monthly average of total annuity payments received during the year. <br><br>
                        * The Annuity is received on the 1st day of each month starting the 1st month.
                    </p>
                    Report Date : {{date('d/m/Y')}}
                    
                </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
        @include('frontend.calculators.suggested.pdf')
    
    </body>
</html>