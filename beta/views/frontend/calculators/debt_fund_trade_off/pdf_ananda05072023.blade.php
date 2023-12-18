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
        
<main style="width: 806px;">
    <div style="padding: 0 0%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Debt Fund (Hold/Sell) Benefit Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
        <div class="roundBorderHolder">
            @if($formType == 1)
                @php 
                if($optionType == 'one'){
                $densu = explode('/',$purchase);
                //dd($densu);
                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                $purchaseDate = strtotime($dtNow);
                $densu = explode('/',$redeem);
                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                $redeemDate = strtotime($dtNow);
                $invest = $invest;
                $current = $current;
                }
                else{
                $densu = explode('/',$purchase1);
                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                $purchaseDate = strtotime($dtNow);
                $densu = explode('/',$redeem1);
                $dtNow = $densu[1].'/'.$densu[0].'/'.$densu[2];
                $redeemDate = strtotime($dtNow);
                $invest = $units * $nav;
                $current = $units * $currentnav;
                $redeem = $redeem1;
                $purchase = $purchase1;
                }
                
                $threeYears =date('m/d/Y', strtotime('+3 years +1days',$purchaseDate));
                $allThreeYearsDateSplit = explode('/',$threeYears);
                $mainThreeYears = $allThreeYearsDateSplit[1].'/'.$allThreeYearsDateSplit[0].'/'.$allThreeYearsDateSplit[2];
                $dateDiff =$redeemDate- $purchaseDate;
                $totalDays = round($dateDiff/(60 * 60 * 24));
                $taxShortTerm = $current - $invest;
                $taxPayable =  $taxShortTerm * $shortterm/100; 
                $netInHand = $current - $taxPayable;
                $postTax = pow(($netInHand/$invest),(365/$totalDays))-1;
                $postTax = $postTax * 100;
                $remainingDays = round((strtotime($threeYears)-$redeemDate)/(60 * 60 * 24));
                $expectedReturn = pow((1+$expected/100),(1/365))-1;
                $expectedValue = $current * pow((1+$expectedReturn),$remainingDays);
                $indexedCost = $invest*pow((1+$indexation/100),$ltcg);
                $taxValue = $expectedValue - $indexedCost;
                $taxPayableNew = $taxValue * ($longterm / 100);
                $netInHandNew = $expectedValue-$taxPayableNew;
                $remainingDaysNew = round((strtotime($threeYears)-$purchaseDate) / (60 * 60 * 24));
                //dd($remainingDaysNew);
                $postTaxPayable = pow(($netInHandNew/$invest),(365/$remainingDaysNew))-1;
                $postTaxPayable = $postTaxPayable * 100; 
                $irr =(pow(($netInHandNew/$netInHand),(365/$remainingDays))-1)*100;
                //dd($taxation);
                @endphp
                <br/>
                
                @if($taxation != "Long Term")<h3 style="text-align:center; font-size:18px;">If investment is redeemed TODAY</h3>@endif
                <table class="table table-bordered leftright" style="margin: 0 auto; width:70%">
                    <tbody>
                            <tr>
                            <td>
                                <strong>Purchase Date</strong>
                            </td>
                            <td>
                                    {{$purchase}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Redemption Date</strong>
                            </td>
                            <td>
                                    {{$redeem}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Investment Amount</strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($invest)}}
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Current Market Value</strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($current)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Taxable Short Term Capital Gain</strong>
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($current-$invest)}}
                            </td>
                        </tr>
                        
                        <tr style="background-color: #a9f3ff;">
                            <td>
                                <strong>Tax Payable</strong>
                            </td>
                            <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxPayable)}}
                            </td>
                        </tr>
                        
                        
                            
                        <tr>
                            <td>
                                <strong>Net In Hand</strong>
                            </td>
                            <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($netInHand)}}
                            </td>
                        </tr>
                        <tr style="background-color: #a9f3ff;">
                            <td>
                                <strong>Post-Tax Yield</strong>
                            </td>
                            <td>
                            {{sprintf('%0.2f', $postTax)}} %
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
                @if($taxation != "Long Term")
                <br/>
                <h3 style="text-align:center;font-size:16px;">If investment is redeemed on {{$mainThreeYears}}<br/>
                i.e., after  {{$remainingDays}} days, it will qualify as LTCG giving you benefit of Long Term Taxation</h3>
                <table class="table table-bordered leftright" style="margin: 0 auto; width:70%">
                    <tbody>
                        <tr>
                            <td>
                                <strong>Redemption Date</strong>
                            </td>
                            <td>
                                    <strong> {{$mainThreeYears}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Investment Amount</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($invest)}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Return for Remaining Period</strong>
                            </td>
                            <td>
                                <strong>  {{sprintf('%0.2f',$expected)}} %</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Assumed Indexation Rate</strong>
                            </td>
                            <td>
                                    <strong> {{sprintf('%0.2f',$indexation)}} %</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Redemption Amount</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($expectedValue)}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Long Term Capital Gain</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($expectedValue - $invest)}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Taxable Long Term Capital Gain</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxValue)}}</strong>
                            </td>
                        </tr>
                        <tr style="background-color: #a9f3ff;">
                            <td>
                                <strong>Tax Payable</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxPayableNew)}}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Net In Hand</strong>
                            </td>
                            <td>
                                <strong> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($expectedValue-$taxPayableNew)}}</strong>
                            </td>
                        </tr>
                        <tr style="background-color: #a9f3ff;">
                            <td>
                                <strong>Post-Tax Yield</strong>
                            </td>
                            <td>
                                <strong> {{sprintf('%0.2f', $postTaxPayable)}} %</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h3 style="text-align:center; font-size:14px;">If the redemption is made on {{$mainThreeYears}},i.e., after  {{$remainingDays}} days, <br/>
                the effective post-tax yield for the remaining period of investment will be {{sprintf('%0.2f', $irr)}} %.</h3>
                @endif
                @else
                @php
                $noOfYears1= $invyear + $invmonth/12;
                $noOfYears2= $after;
                $matuityAmount = $initial * pow((1+$expected/100),$noOfYears1);
                $matuityAmount3 = $initial * pow((1+$expected/100),$noOfYears2);
                $capGain = $matuityAmount-$initial;
                $capGain3 = $matuityAmount3-$initial;
                if($noOfYears1 < $noOfYears2)
                $indexCostInv = $initial;
                else
                $indexCostInv = $initial * pow((1+$expected/100),$noOfYears1);
                
                $indexCostInv3 = $initial * pow((1+$assumed/100),$noOfYears2);
                $taxPayable = ($matuityAmount - $indexCostInv)*($shortterm/100);
                $taxPayable3 = ($matuityAmount3 - $indexCostInv3)*($longterm/100);
                $taxReturn = $capGain - $taxPayable;
                $taxReturn3 = $capGain3 - $taxPayable3;
                $postTax = (pow((($initial+$taxReturn)/$initial),(1/$noOfYears1))-1)*100;
                $postTax3 = (pow((($initial+$taxReturn3)/$initial),(1/$noOfYears2))-1)*100;
                $balYear = $noOfYears2-$noOfYears1;
                $irrBal = pow((($initial + $taxReturn3)/($initial + $taxReturn)),(1/$balYear))-1;
                $irrBal = $irrBal * 100;
                $bd38 = $after * 12;
                $bd39 = $invyear * 12 + $invmonth;
                @endphp
                <br/>
                <table class="table table-bordered leftright" style="margin: 0 auto; width:90%">
                    <thead>
                        <tr>
                            <td style="text-align:center;"><strong>Investment Period</strong></td>
                            <td style="text-align:center;"><strong>{{$invyear}} Yr {{$invmonth}} Months</strong></td>
                            <td style="text-align:center;"><strong>If Held For {{$after}} Years</strong></td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <strong>Initial Investment</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial)}}
                            </td>
                            <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($initial)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Return</strong>
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $expected)}} %
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $expected)}} %
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Maturity Amount</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($matuityAmount)}}
                            </td style="text-align:right;">
                            <td>
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($matuityAmount3)}}
                            </td>
                        </tr>
                        
                    <tr>
                            <td>
                                <strong> Capital Gain</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($capGain)}}
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($capGain3)}}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong>Assumed Indexation Rate</strong>
                            </td>
                            <td style="text-align:right;">
                                @if($noOfYears1 > $noOfYears2)
                                {{sprintf('%0.2f', $assumed)}}  %
                                @else
                                N/A
                                @endif
                            </td>
                            <td style="text-align:right;">
                                
                                {{sprintf('%0.2f', $assumed)}}  %
                                
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> Indexed Cost of Investment</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($indexCostInv)}}
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($indexCostInv3)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> Taxable Income</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($matuityAmount-$indexCostInv)}}
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($matuityAmount3-$indexCostInv3)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Applicable Tax Rate</strong>
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $shortterm)}} %
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $longterm)}} %
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> Taxable Income</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxPayable)}}
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($taxPayable3)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong> Post-Tax Returns (Rs)</strong>
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($capGain-$taxPayable)}}
                            </td>
                            <td style="text-align:right;">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($capGain3-$taxPayable3)}}
                            </td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong> Post-Tax IRR (%)</strong>
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $postTax)}} %
                            </td>
                            <td style="text-align:right;">
                                    {{sprintf('%0.2f', $postTax3)}} %
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h3 style="text-align:center; font-size:14px;">If the investment horizon is increased from {{$invyear}} Year {{$invmonth}} Mnths to {{$after}} Years<br>
                effective post-tax yield for the additional {{$bd38-$bd39}} month's period of investment will be {{sprintf('%0.2f', $irrBal)}} %.</h3>
                <br />
                
            @endif
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
    
    @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','debt_fund')->first();
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