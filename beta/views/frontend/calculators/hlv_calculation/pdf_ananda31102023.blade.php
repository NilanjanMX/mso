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
                <h1 class="pdfTitie">Human Life Value Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
            
                
                @if($formType == 1)
                        @php 
                        $net = $anual - $personal;
                          if($expected == $discount)
                            $humanLifeValue = $net * ($retire-$current) / (1+$discount/100);
                          else
                            $humanLifeValue =  $net * (1-(pow(1+$expected/100,($retire-$current))*pow(1+$discount/100 , -($retire-$current))))/(($discount/100)-($expected/100));     
                        @endphp
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Current Age</strong>
                                        </td>
                                        <td>
                                              {{$current}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Retirement Age</strong>
                                        </td>
                                        <td>
                                            {{$retire}}  Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Discounting Rate</strong>
                                        </td>
                                        <td>
                                            {{$discount}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Annual Income</strong>
                                        </td>
                                        <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{$anual}}
                                        </td>
                                    </tr>
                                    @if($expected != '')
                                    <tr>
                                        <td>
                                            <strong>Expected Annual Increment</strong>
                                        </td>
                                        <td>
                                        @if($expected != '') {{$expected}} % @else 0 % @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @if($personal != '')
                                    <tr>
                                        <td>
                                            <strong>Annual Personal Expenses</strong>
                                        </td>
                                        <td>
                                        @if($personal != '') <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($personal)}} @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <h1 class="pdfTitie">Human Life Value</h1>

                        <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($humanLifeValue)}}
                        </h5>
                        
                        <p style="text-align:center;">*Income Replacement Method</p>
                    </div>
                        @else
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Current Age</strong>
                                        </td>
                                        <td>
                                              {{$current}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Retirement Age</strong>
                                        </td>
                                        <td>
                                            {{$retire}}  Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Age of Spouse</strong>
                                        </td>
                                        <td>
                                            {{$spouse}}  Years
                                        </td>
                                    </tr>
                                    @if(isset($annuity))
                                <tr>
                                        <td>
                                            <strong> Annuity Ends at Age</strong>
                                        </td>
                                        <td>
                                            {{$annuity}} Years
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <strong>Discounting Rate</strong>
                                        </td>
                                        <td>
                                            {{$discount}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Annual Income</strong>
                                        </td>
                                        <td>
                                           <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($anual)}}
                                        </td>
                                    </tr>
                                    @if($expected != '')
                                    <tr>
                                        <td>
                                            <strong>Expected Annual Increment</strong>
                                        </td>
                                        <td>
                                        @if($expected != '') {{$expected}} % @else 0 % @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    @if(isset($household))
                                    <tr>
                                            <td>
                                                <strong> Annual Household Expenses</strong>
                                            </td>
                                            <td>
                                               <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($household)}}
                                            </td>
                                        </tr>
                                        @endif
                                        
                                        @if(isset($anualincrement))
                                    <tr>
                                            <td>
                                                <strong> Expected Annual Increment</strong>
                                            </td>
                                            <td>
                                                {{$anualincrement}} %
                                            </td>
                                        </tr>
                                        @endif
                                        @if(isset($anualretire))
                                    <tr>
                                            <td>
                                                <strong> Annual Retirement Expenses For Spouse</strong>
                                            </td>
                                            <td>
                                               <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($anualretire)}}
                                            </td>
                                        </tr>
                                        @endif
                                         @if(isset($inflation))
                                    <tr>
                                            <td>
                                                <strong> Assumed Inflation Rate</strong>
                                            </td>
                                            <td>
                                                {{$inflation}} %
                                            </td>
                                        </tr>
                                        @endif
                                        @if(isset($rateofreturn))
                                    <tr>
                                            <td>
                                                <strong> Assumed Rate of Return (Distribution Period)</strong>
                                            </td>
                                            <td>
                                                {{$rateofreturn}} %
                                            </td>
                                        </tr>
                                        @endif
                                        @if(isset($annuity))
                                    <tr>
                                            <td>
                                                <strong> Annuity Ends at Age</strong>
                                            </td>
                                            <td>
                                                {{$annuity}} Years
                                            </td>
                                        </tr>
                                        @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td colspan="3" style="text-align:center; background-color: #a9f3ff;"><strong>Other Financial Goals</strong></td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:center;"><strong>Goal Name</strong></td>
                                        <td style="text-align:center; width: 150px;"><strong>Amount Required</strong></td>
                                        <td style="text-align:center; width: 150px;"><strong>Period Remaining</strong></td>
                                    </tr>
                                    @php
                                    $count = 0;
                                    
                                    foreach($special as $spec)
                                    {
                                        echo("<tr><td>".$spec[$count]."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($spec[$count+1])."</td><td>".$spec[$count+2]." Years</td></tr>");
                                        
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
                            <div class="page-break"></div>
                                    
                            @include('frontend.calculators.common.header')
                    <main class="mainPdf">
                        <h1 class="pdfTitie">Human Life Value Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    @if(isset($market))
                                        <tr>
                                            <td>
                                                <strong> Current Market Value of Investments</strong>
                                            </td>
                                            <td>
                                               <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($market)}}
                                            </td>
                                        </tr>
                                    @endif
                                    @if(isset($lifeinsure))
                                        <tr>
                                            <td>
                                                <strong> Current Life Insurance Cover + Accrued Bonus</strong>
                                            </td>
                                            <td>
                                               <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lifeinsure)}}
                                            </td>
                                        </tr>
                                    @endif
                                    @if(isset($anualsavings))
                                        <tr>
                                            <td>
                                                <strong> Current Annual Savings</strong>
                                            </td>
                                            <td>
                                               <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($anualsavings)}}
                                            </td>
                                        </tr>
                                    @endif
                                    @if(isset($assumedrate))
                                        <tr>
                                            <td>
                                                <strong> Assumed Rate of Return on Investment</strong>
                                            </td>
                                            <td>
                                                {{$assumedrate}} %
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="background-color: #a9f3ff;"> </td>
                                        <td style="background-color: #a9f3ff;"><strong> Shortfall / (Excess)</strong></td>
                                        <td style="background-color: #a9f3ff;"><strong>Value of Assets + Insurance Cover</strong></td>
                                        <td style="background-color: #a9f3ff;"><strong>Life Cover Required</strong></td>
                                    </tr>
                                
                                @php
                                $pv_total_need_year1 =0 ;
                                $pv_total_need_year5 =0 ;
                                $pv_total_need_year10 =0 ;
                                $earningLeft = $retire - $current;
                                if($retire > $current)
                                    $annuityPeriod = $annuity-$retire+($current-$spouse);
                                else
                                    $annuityPeriod = $annuity-$spouse;
                                    if($discount == $anualincrement){
                                    $pv_house_hold = $household * $earningLeft / (1+$discount/100);
                                    }
                                    else{
                                    $pv_house_hold = $household * (1-pow((1+$anualincrement/100),$earningLeft)*pow((1+$discount/100),-$earningLeft))/($discount/100-$anualincrement/100);
                                    }
                                    
                                    if($inflation == $rateofreturn){
                                    $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                    }
                                    else{
                                    $retirement_corpus = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                    }
                                    //dd(round($retirement_corpus));
                                    $pv_retirement_corpus = round($retirement_corpus)/pow((1+$discount/100),$earningLeft);
                                $count = 0;
                                    $tech = 0;
                                    foreach($special as $spec)
                                    {
                                        $goalAmount = $spec[$count+1]/pow((1+$discount/100),$spec[$count+2]);
                                        
                                        
                                        $pv_total_need_year1 += $goalAmount;
                                        $tech++;
                                    }
                                    
                                    $pv_total_need_year1 += $pv_house_hold + $pv_retirement_corpus;
                                    $currentAssetInsurance = $market + $lifeinsure;
                                    
                                    $valueOfAssets = $currentAssetInsurance;
                                    $lifeCoverRequired = $pv_total_need_year1 - $valueOfAssets;
                                    
                                    echo("<tr><td><strong>Present Day</strong></td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($pv_total_need_year1)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($valueOfAssets)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                    
                                    //FiveYears
                                    if($earningLeft >= 5){
                                    if($discount == $anualincrement){
                                    $pv_house_hold = $household * ($earningLeft-5)/(1+$discount/100);
                                    }
                                    else{
                                    $pv_house_hold = $household * (1-pow((1+$anualincrement/100),($earningLeft-5))*pow((1+$discount/100),(-$earningLeft+5)))/($discount/100-$anualincrement/100);
                                    }
                                    }
                                    else
                                    $pv_house_hold = 0;
                                    
                                    
                                    if($earningLeft >=5){
                                        $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                        }
                                    else{
                                        $retirement_corpus = $anualretire * ($annuityPeriod +$earningLeft-5)/(1+$rateofreturn/100);
                                        }
                                        
                                        if($earningLeft >=5){
                                        if($inflation == $rateofreturn)
                                        {
                                            $retirement_corpus2 = 0;
                                        }
                                        else{
                                            $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                            }
                                        }
                                    else{
                                        if($inflation == $rateofreturn)
                                        {
                                            $retirement_corpus2 = 0;
                                        }
                                        else{
                                        $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),($annuityPeriod+$earningLeft-5))*pow((1+$rateofreturn/100),(-$annuityPeriod-$earningLeft+5)))/($rateofreturn/100-$inflation/100);
                                        }
                                        }
                                        
                                        if($inflation != $rateofreturn)
                                        {
                                            $retirement_corpus = $retirement_corpus2;
                                        }
                                        
                                        if($earningLeft >=5)
                                            $pv_retirement_corpus = $retirement_corpus/pow((1+$discount/100),($earningLeft-5));
                                            else
                                            $pv_retirement_corpus = $retirement_corpus;
                                $count = 0;
                                    
                                    foreach($special as $spec)
                                    {
                                        if($spec[$count+2] >= 5){
                                        
                                        $goalAmount = $spec[$count+1]/pow((1+$discount/100),($spec[$count+2]-5));
                                        $pv_total_need_year5 += $goalAmount;
                                        }
                                    }
                                    
                                    $pv_total_need_year5 += $pv_house_hold + $pv_retirement_corpus;
                                    
                                    $valueOfOldInvest = $market*pow((1+$assumedrate/100),5);
                                
                                    if($earningLeft >=5)
                                    {
                                       $newInvestment = $anualsavings*((pow(1+$assumedrate/100,5)-1)/($assumedrate/100));
                                    }
                                    else
                                    {
                                       $newInvestment = ($anualsavings*((pow(1+$assumedrate/100,$earningLeft)-1)/($assumedrate/100)))* pow(1+$assumedrate/100, (5-$earningLeft));
                                    }
                                    //dd(round($newInvestment));
                                    $insuranceCover = $lifeinsure;
                                    $currentAssetInsurance = $valueOfOldInvest + round($newInvestment) + $insuranceCover;
                                    $valueOfAssets = $currentAssetInsurance;
                                    
                                    $lifeCoverRequired = $pv_total_need_year5 - $valueOfAssets;
                                    
                                    
                                    echo("<tr><td><strong>After 5 Years</strong></td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($pv_total_need_year5)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($valueOfAssets)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                    //EndFive years
                                    
                                    //Ten years
                                    if($earningLeft >= 10){
                                    if($discount == $anualincrement){
                                    $pv_house_hold = $household * ($earningLeft-10)/(1+$discount/100);
                                    }
                                    else{
                                    $pv_house_hold = $household * (1-pow((1+$anualincrement/100),($earningLeft-10))*pow((1+$discount/100),(-$earningLeft+10)))/($discount/100-$anualincrement/100);
                                    }
                                    }
                                    else
                                    $pv_house_hold = 0;
                                    
                                    
                                    if($earningLeft >=10)
                                        $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                    else
                                        $retirement_corpus = $anualretire * ($annuityPeriod +$earningLeft-10)/(1+$rateofreturn/100);
                                        
                                        if($earningLeft >=10){
                                        if($inflation == $rateofreturn)
                                        {
                                            $retirement_corpus2 = 0;
                                        }
                                        else{
                                            $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                            }
                                        }
                                    else{
                                        if($inflation == $rateofreturn)
                                        {
                                            $retirement_corpus2 = 0;
                                        }
                                        else{
                                        $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),($annuityPeriod+$earningLeft-10))*pow((1+$rateofreturn/100),(-$annuityPeriod-$earningLeft+10)))/($rateofreturn/100-$inflation/100);
                                        }
                                        }
                                        
                                        if($inflation != $rateofreturn)
                                        {
                                            $retirement_corpus = $retirement_corpus2;
                                        }
                                        
                                        if($earningLeft >=10)
                                            $pv_retirement_corpus = $retirement_corpus/pow((1+$discount/100),($earningLeft-10));
                                            else
                                            $pv_retirement_corpus = $retirement_corpus;
                                $count = 0;
                                    
                                    foreach($special as $spec)
                                    {
                                        if($spec[$count+2] >= 10){
                                        
                                        $goalAmount = $spec[$count+1]/pow((1+$discount/100),($spec[$count+2]-10));
                                        $pv_total_need_year10 += $goalAmount;
                                        }
                                    }
                                    
                                    $pv_total_need_year10 += $pv_house_hold + $pv_retirement_corpus;
                                    
                                    $valueOfOldInvest = $market*pow((1+$assumedrate/100),10);
                                    if($earningLeft >=10)
                                    {
                                       $newInvestment = $anualsavings*((pow(1+$assumedrate/100,10)-1)/($assumedrate/100));
                                    }
                                    else
                                    {
                                       $newInvestment = ($anualsavings*((pow(1+$assumedrate/100,$earningLeft)-1)/($assumedrate/100)))* pow(1+$assumedrate/100, (10-$earningLeft));
                                    }
                                    $insuranceCover = $lifeinsure;
                                    $currentAssetInsurance = $valueOfOldInvest + $newInvestment + $insuranceCover;
                                    $valueOfAssets = $currentAssetInsurance;
                                    
                                    $lifeCoverRequired = $pv_total_need_year10 - $valueOfAssets;
                                    
                                    
                                    echo("<tr><td><strong>After 10 Years</strong></td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($pv_total_need_year10)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($valueOfAssets)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                    //End of tenyears
                                @endphp
                                </tbody>
                            </table>
                        </div>
                        
                        
                        @endif
                        
                        
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','hlv_calculation')->first();
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