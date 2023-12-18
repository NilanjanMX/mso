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
                        
            <div style="padding: 0 5%; padding-top:60px;">
                <h1 style="margin:0 auto; color: #000;font-size:16px;margin-bottom:10px !important; padding-top:40px; text-align:center; background-color: #a9f3ff; padding: 10px 0; width:90%;">@if(isset($details)) {{$details}} @else SWP Calculation @endif @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h1>
                <div>
                    @php 
                       $calctype = '';
                        if($investmentmode == 1 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                        //dd("first calc");
                        $calctype = 1;
                            
                                $swpAmt = $swpamount;
                            
                                
                                
                                
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                                
                            
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($initial * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            $totalMonths = $mainMonths;
                            
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  2;
                            
                                $swpAmt = $swpamount;
                            
                           
                            $mainMonths = 0;
                            $inter = 1;
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            //dd($balanceAvailable);
                        }
                      else  if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 3;
                            
                                $swpAmt = $swpamount;
                           // dd($swpAmt);
                                
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $initial * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $initial * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                       else if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                       // dd("fourth calc");
                        $calctype = 4;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($initial * ($swpamount/100))/12;
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                }
                            
                            $annualRateOfReturn = $monthlyRateOfReturn;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($initial - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("fifth calc");
                        $calctype = 5;
                            
                                $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                           
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 6;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 2 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 7;
                            
                                $swpAmt = $swpamount;
                            
                                
                                
                                
                            
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            
                        }
                       else if($investmentmode == 2 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  8;
                            
                                $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            //dd($balanceAvailable);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 9;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            //dd($accumulated);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 10;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 11;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 12;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                        
                        else if($investmentmode == 3 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 13;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  14;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 15;
                        
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($actotal1 == $inpercent)
                            {
                            //echo("Coming 1");
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                            //echo("Coming 2");
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            //dd($totalMonths." ".$total1);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 16;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 17;
                             $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 18;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 4 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 19;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  20;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 21;
                        
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($actotal1 == $inpercent)
                            {
                            //echo("Coming 1");
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                            //echo("Coming 2");
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            //dd($totalMonths." ".$total1);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 22;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 23;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 24;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        
                        else
                        {
                            dd("else");
                            
                        }
                        
                        $data = DB::table('aaswpcomprehensive')->select('*')->first();
                        
                        //dd($data);
                        
                        if($calctype == 3 || $calctype == 4 || $calctype == 5 || $calctype == 6 ||$calctype == 9 ||$calctype == 10 ||$calctype == 11 ||$calctype == 12 ||$calctype == 15 ||$calctype == 16 ||$calctype == 17 ||$calctype == 18 ||$calctype == 21 ||$calctype == 22 ||$calctype == 23 ||$calctype == 24)
                        {
                        
                            $footNote = $data->footer;
                        
                        }
                        
                    @endphp
                    @include('frontend.calculators.common.watermark')
                    @if($footer_branding_option == "all_pages")
                        @include('frontend.calculators.common.footer')
                    @endif
                    <table class="table table-bordered leftright" style="margin: 0 auto; width:60%">
                        <tbody>
                            @if($currentage > 0)
                                <tr>
                                    <td>
                                        <strong>Current Age</strong>
                                    </td>
                                    <td>
                                          {{$currentage}} Yrs
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td>
                                    @if($investmentmode==2)
                                    <strong>Annual Investment</strong>
                                    @elseif($investmentmode==3)
                                    <strong>SIP Investment</strong>
                                    @elseif($investmentmode==4)
                                    <strong>Lumpsum Investment</strong>
                                    @else
                                    <strong>Initial Investment</strong>
                                    @endif
                                </td>
                                <td>
                                     <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($initial)}}
                                </td>
                            </tr>
                            @if($investmentmode==4)
                                <tr>
                                    <td>
                                        <strong>SIP Amount</strong>
                                    </td>
                                    <td>
                                        <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($sipamt)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SIP Period</strong>
                                    </td>
                                    <td>
                                         {{$invperiod}} Yrs
                                    </td>
                                </tr>
                            @endif
                            @if($investmentmode==2 || $investmentmode==3)
                                <tr>
                                    <td>
                                        <strong>Payment Period</strong>
                                    </td>
                                    <td>
                                          {{$invperiod}} Yrs
                                    </td>
                                </tr>
                            @endif
                            @if($def==1)
                                <tr>
                                    <td>
                                        <strong>Deferment Period</strong>
                                    </td>
                                    <td>
                                          {{$defermentperiod}} Yrs
                                    </td>
                                </tr>
                            @endif

                             @if($calctype==2 || $calctype==5|| $calctype==6 ||$calctype==7 || $calctype==8 || $calctype==9 || $calctype==10 || $calctype==11 || $calctype==12 || $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18)
                            <tr>
                                <td>
                                    <strong>Accumulated Corpus</strong>
                                </td>
                                <td>
                                      <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($accumulated)}}
                                </td>
                            </tr>
                            @endif
                            @if($calctype == 4 || $calctype == 5)
                            <tr>
                                <td>
                                    <strong> {{$addonText}} SWP Amount</strong>
                                </td>
                                <td>
                                     <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($swpAmt)}}
                                </td>
                            </tr>
                            @endif
                            @if($annualincr == "2")
                            <tr>
                                <td>
                                    <strong> Annual Increment</strong>
                                </td>
                                <td>
                                    @if($incrtype == 0)
                                      {{$inpercent}} %
                                     @else
                                     <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($inamount)}}
                                     @endif
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <strong>{{$addonText}} SWP Amount</strong>
                                </td>
                                <td>
                                    <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($swpAmt)}}
                                    
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>SWP Period</strong>
                                </td>
                                <td>
                                    {{$swp}} Years
                                </td>
                            </tr>
                        </tbody>
                    </table>
                        
                    <h3 style="margin: 0 auto; margin-top:30px; margin-bottom:10px; color: #000;font-size:16px;text-align:center; background-color: #a9f3ff; padding: 10px 0; width:90%;">Suggested Asset Allocation</h3>

                    @if($calctype==2 || $calctype==5|| $calctype==6 ||$calctype==7 || $calctype==8 || $calctype==9 || $calctype==10 || $calctype==11 || $calctype==12 || $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18) 
                        <h6 style="color: #131f55;font-size:16px;margin-bottom:5px; text-align:center; margin-top:10px;">Accumulation Phase</h6> 
                            
                        <div style="width:60%; margin: 0 auto;">
                            <table class="table table-bordered" style="width:100%;">
                                <tr style="background-color: #a9f3ff;"><td><strong>Asset Class</strong></td><td><strong>Allocation</strong></td><td><strong>Assumed Return</strong></td></tr>
                                @if($acequity > 0)
                                    <tr>
                                            <td>
                                                <strong>Equity</strong>
                                            </td>
                                            <td>
                                                {{sprintf('%0.2f',$acequity)}} %
                                            </td>
                                            <td>
                                             @if($acequity > 0)   {{sprintf('%0.2f',$acequity1)}} % @else - @endif
                                            </td>
                                    </tr>
                                @endif
                                @if($achybrid > 0)
                                    <tr>
                                        <td>
                                            <strong>Hybrid</strong>
                                        </td>
                                        <td>
                                            {{sprintf('%0.2f',$achybrid)}} %
                                        </td>
                                        <td>
                                           @if($achybrid > 0)  {{sprintf('%0.2f',$achybrid1)}} % @else - @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($acdebt > 0)
                                    <tr>
                                        <td>
                                            <strong>Debt</strong>
                                        </td>
                                        <td>
                                            {{sprintf('%0.2f',$acdebt)}} %
                                        </td>
                                        <td>
                                           @if($acdebt1 > 0) {{sprintf('%0.2f',$acdebt1)}} % @else - @endif
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <strong>Portfolio</strong>
                                    </td>
                                    <td>
                                        <strong>{{sprintf('%0.2f',$actotal)}} %</strong>
                                    </td>
                                    <td>
                                        <strong>{{sprintf('%0.2f',$actotal1)}} %</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    @endif

                    @if($calctype==2 || $calctype==5|| $calctype==6 ||$calctype==7 || $calctype==8 || $calctype==9 || $calctype==10 || $calctype==11 || $calctype==12 || $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18) 
                        <h6 style="color: #131f55;font-size:16px;margin-bottom:5px; text-align:center; margin-top:10px;">Distribution Phase</h6>
                    @endif
                    <div style="width:60%; margin: 0 auto;">
                        <table class="table table-bordered" style="width:100%;">
                            <tr style="background-color: #a9f3ff;"><td><strong>Asset Class</strong></td><td><strong>Allocation</strong></td><td><strong>Assumed Return</strong></td></tr>
                            @if($equity > 0)
                            <tr>
                                    <td>
                                        <strong>Equity</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$equity)}} %
                                    </td>
                                    <td>
                                       @if($equity1 > 0) {{sprintf('%0.2f',$equity1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($hybrid > 0)
                                <tr>
                                    <td>
                                        <strong>Hybrid</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$hybrid)}} %
                                    </td>
                                    <td>
                                       @if($hybrid1 > 0) {{sprintf('%0.2f',$hybrid1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($debt > 0)
                                <tr>
                                    <td>
                                        <strong>Debt</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$debt)}} %
                                    </td>
                                    <td>
                                      @if($debt1 > 0)  {{sprintf('%0.2f',$debt1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        <strong>Portfolio</strong>
                                    </td>
                                    <td>
                                       <strong>{{sprintf('%0.2f',$total)}} %</strong>
                                    </td>
                                    <td>
                                        <strong>{{sprintf('%0.2f',$total1)}} %</strong>
                                    </td>
                                </tr>
                        </table>
                    </div>
                    <br/><br/>
                    <h3 style="color: #131f55; background-color: #a9f3ff; font-size: 18px; margin:auto; padding:10px; text-align:center;width: 50%;border: 1px solid #ccc;">Total Withdrawal</h3>

                        <h3 style="
                        margin: auto;
                        padding: 10px;
                        width: 50%;
                        border: 1px solid #ccc;
                        font-size: 18px;
                        text-align: center;
                    ">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($totalWithdrawal)}}
                    </h3>
                    <br/>
                    <h3 style="color: #131f55; background-color: #a9f3ff; font-size: 18px; margin:auto; padding:10px; text-align:center;width: 50%;border: 1px solid #ccc;">End Value</h3>
                        
                        
                    <h3 style="
                        margin: auto;
                        padding: 10px;
                        width: 50%;
                        border: 1px solid #ccc;
                        font-size: 18px;
                        text-align: center; ">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balanceAvailable)}}
                    </h3>
                        
                    @if($report == 2)
                        <div class="page-break"></div>
                        </div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;">&nbsp;</td>
                                    <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        
                            @if($calctype==1)
                        
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                         
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $month = 1;
                            $rowCount = 0;
                            $openingBal = $initial;
                            
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            //dd($monthlyRateOfReturn);
                            for($month = 1; $month <= ($swp * 12); $month++)
                            {
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                
                                if($month >= 12){
                                if($month % 12 ==0){
                                $rowCount ++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                    </div>
                                    <div class="page-break"></div>
                                    <header>
                                        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                            <tbody>
                                            <tr>
                                                <td style="text-align:left; border:0;">&nbsp;</td>
                                                <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </header>
                @include('frontend.calculators.common.footer')
                <div style="padding: 0 5%; padding-top:70px;">
                    <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                         
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                    $rowCount = 0;
                                }
                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                
                                }
                                
                                
                                }
                                
                                if($month % $inter == 0)
                                {
                                    $openingBal = $yearEnd;
                                }
                                
                                
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==2)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $rowCount = 0;
                            while($defPeriod >= $year)
                            {

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($lumpsumInv))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                    @endphp
                                    </table>
                                    </div>
                                    <div class="page-break"></div>
                                    <header>
                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                        <tbody>
                        <tr>
                            <td style="text-align:left; border:0;">&nbsp;</td>
                            <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                        </tr>
                        </tbody>
                    </table>
                </header>
                @include('frontend.calculators.common.footer')
                <div style="padding: 0 5%; padding-top:70px;">
                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                    $rowCount = 0;
                                    }
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
            
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>

                            
                        @include('frontend.calculators.common.footer')
            <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>Year</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $month = 1;
                            $addYear = $year-1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                    </div>
                                    <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>

                            
                        @include('frontend.calculators.common.footer')
            <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>Year</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                    $rowCount = 0;
                                }
                                echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                        @endif
                        @if($calctype==3)
                        <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            
                            <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average {{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                @php
                                $swpperiod = $swp * 12;
                                
                                //dd($swpperiod);
                                
                                $month = 1;
                                $age = $currentage;
                                $openingBal = $initial;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                //dd($inter);
                                while($month<=$swpperiod)
                                {
                                if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                        echo("chee");
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            
                            <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average {{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                        @php
                                        $rowCount = 0;
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        echo("<tr><td>".++$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                             @if($calctype==4)
                             <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:10px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $initial;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:10px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    echo("<tr><td>".$mainAge."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                        @endif
                        
                        @if($calctype==5)
                             <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                             
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $rowCount = 0;
                            while($defPeriod >= $year)
                            {

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($lumpsumInv))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            </div>
                            
                            <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            
                            <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                @php
                                $addYear = $year-1;
                                $swpperiod = $totalMonths;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=($swp * 12))
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                    
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            
                            <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                        @endif
                        @if($calctype==6)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>Year</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $age = $currentage;
                            $onetime = false;
                            $rowCount = 0;
                            while($defPeriod >= $year)
                            {
                                $rowCount++;
                                if($rowCount)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>Year</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($lumpsumInv))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
                            <div class="page-break"></div>
       
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                         </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($currentage + $year)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                        @endif
                        
                        @if($calctype==7)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                            <div class="page-break"></div>
        </div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                }
                                echo("<tr><td>".($age + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            
                             @if($calctype==8)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                if($year <= $payPer)
                                echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year+$currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </div>
                            </table>
                            <br/>
                            <div class="page-break"></div>

        </div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                }
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            </div>
                        @endif
                        
                        @if($calctype==9)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
                            <div class="page-break"></div>
        </div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $totalMonths;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swp * 12)
                                {
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==10)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">     
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($currentage + $year)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                            @endif
                            
                             @if($calctype==11)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==12)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==13)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                      <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                             
                             if($month % $inter == 0){   
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                      <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                    
                                }
                                echo("<tr><td>".($age+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                        </table>
                        </div>
                            @endif
                            @if($calctype==14)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                         <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                         <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        
                            <div class="page-break"></div>



                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">

                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                }
                                echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==15)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==16)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($currentage + $year)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                            @endif
                            @if($calctype==17)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==18)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                           
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                if($year <= $payPer)
                                echo("<tr><td>".($currentage+$year)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($currentage+$year)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                            @endif
                            @if($calctype==19)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $annualLumpsum = $initial;
                            $lumpsum = '';
                            $paymentPer = $invperiod;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $yearendVal = '';
                            $yearEndValLumpsum = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                            $amountInvested = $sipamt;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $lumpsum = $annualLumpsum;
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndValue = $yearendVal + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndValue))."</td></tr>");
                                
                                
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>Age</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                 <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>Age</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                }
                                echo("<tr><td>".($age+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==20)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            $annualLumpsum = $initial;
                            $lumpsum = $annualLumpsum;
                            $yearEndValLumpsum = '';
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                     </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            </div>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $rowCount = 0;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                }
                                echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($monthlySwp))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==21)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $lumpsum = $initial;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $yearendVal = '';
                            $yearEndValLumpsum = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                            $amountInvested = $sipamt;
                            $rowCount++;
                            if($rowCount == 26)
                            {
                                @endphp
                                </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                @php
                            }
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearendVal + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$age+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==22)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $lumpsum = $initial;
                            $yearendVal = '';
                            $rowCount = 0;
                            while($year<=$paymentPer)
                            {
                            $amountInvested = $sipamt;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearendVal + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            
                            
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        </div>
                            @endif
                            @if($calctype==23)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $lumpsum = $initial;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;
                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                $rowCount++;
                                if($rowCount == 26)
                                {
                                    @endphp
                                    </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                    @php
                                }
                                
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($year+$currentage)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                        }
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($avgMonth))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            </div>
                            @endif
                            @if($calctype==24)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $lumpsum = $initial;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $rowCount = 0;
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;
                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                $rowCount++;
                                if($rowCount == 26){
                                @endphp
                                </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr class="headerbg"><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                                @php
                                }
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($currentage+$year)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($amountInvested*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($currentage+$year)."</td><td>-</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                        @include('frontend.calculators.common.footer')
                        <div style="padding: 0 5%; padding-top:70px;">
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $rowCount = 0;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $rowCount++;
                                    if($rowCount == 26)
                                    {
                                        @endphp
                                        </table>
                                        </div>
                                        <div class="page-break"></div>
                                    <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                        <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0; padding-top: 60px;" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <div style="padding: 0 5%; padding-top:70px;">
                                 <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr class="headerbg"><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        @php
                                    }
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($annualSwp/12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        
                        </table>
                        </div>
                            @endif
                            @endif
                            @if(isset($footNote))
        * {{$footNote}}
        <br>
        @endif
                    * The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}<br>
                    
                
                    
        </div>
        <div style="padding: 0 5.5%;">
            @include('frontend.calculators.suggested.pdf_design_fix')
            </div>
        </main>
</body>
     </html>