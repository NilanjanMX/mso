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
                <h1 class="pdfTitie">Portfolio Projection Report @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                @if($enter_loan_details == 1)
                    @php 
                            $clienPortValue = $amount;
                            $lumpsumInvest = $lumpsum;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                            $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                            $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                            $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                            
                                                   
                        @endphp
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                        <tr>
                                            <td style="">
                                                <strong>Current Portfolio Value</strong>
                                            </td>
                                            <td style="">
                                                  <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($clienPortValue)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="">
                                                <strong>Current Lumpsum Investment Every year</strong>
                                            </td>
                                            <td style="">
                                                  <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsumInvest)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="">
                                                <strong>Current Monthly SIP</strong>
                                            </td>
                                            <td style="">
                                                  <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($currentMonthlySip)}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="">
                                                <strong>Assumed Rate of Return</strong>
                                            </td>
                                            <td style="">
                                                {{$expectedRateOfReturn}}  %
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="">
                                                <strong>Period</strong>
                                            </td>
                                            <td style="">
                                                {{$tenure}}  Years
                                            </td>
                                        </tr>
                                    </tbody>
                            </table>
                        </div>

                    
                    
                
                    <h1 class="pdfTitie">Expected Portfolio Value</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                                    <tr>
                                        <td style="">
                                            <strong>Current Portfolio</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($currentPort)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Annual Lumpsum</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($anualLumpsum)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>SIP</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sipFv)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>TOTAL</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                        </td>
                                    </tr>
                                    
                                </tbody>
                        </table>
                    </div>
                    
                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_pdf')
                    
                @else
                    @php
                    $clienPortValue = $amount;
                        $lumpsumInvest = $ilumpsum;
                        $inclumpsumInvest = $lumpsum;
                        $icurrentMonthlySip = $isip;
                        $currentMonthlySip = $sip;
                        $expectedRateOfReturn = $rate;
                        $tenure = $period;
                        $totalMonths = $tenure * 12;
                        $returnAnual = $expectedRateOfReturn / 100;
                        $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                        $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                        $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                        $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                        $IncAnualLumpsum = (1+$returnAnual) * ($lumpsumInvest+$inclumpsumInvest) * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                        $incSipFv = (1+$returnMonthly) * ($currentMonthlySip + $icurrentMonthlySip)* ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                    @endphp
                    
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                                    <tr>
                                        <td style="">
                                            <strong>Current Portfolio Value</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($clienPortValue)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Current Lumpsum Investment Every Year</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsumInvest)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Increment in Annual Lumpsum</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($inclumpsumInvest)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Current Monthly SIP</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($currentMonthlySip)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Addition in SIP(New)</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($icurrentMonthlySip)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Assumed Rate of Return</strong>
                                        </td>
                                        <td style="">
                                            {{$expectedRateOfReturn}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Period</strong>
                                        </td>
                                        <td style="">
                                            {{$tenure}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                    </div>
                    
                    
                    
                    <h1 class="pdfTitie">Expected Portfolio Value</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Current Scenario</th>
                                    <th>Incremental Scenario</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="">
                                            <strong>Current Portfolio</strong>
                                        </td>
                                        <td style="">
                                             <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($currentPort)}}
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($currentPort)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>Annual Lumpsum</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($anualLumpsum)}}
                                        </td>
                                        <td style="">
                                             <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($IncAnualLumpsum)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="">
                                            <strong>SIP</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($sipFv)}}
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($incSipFv)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style=" ">
                                            <strong>TOTAL</strong>
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                        </td>
                                        <td style="">
                                              <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($incSipFv + $currentPort + $IncAnualLumpsum)}}
                                        </td>
                                    </tr>
                                    
                                </tbody>
                        </table>
                    </div>
                    
                    {{-- comment or note section here --}}
                    @include('frontend.calculators.common.comment_pdf')
                @endif
                
                
            </div>    
                
                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','portfolio_projection')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                    @php } @endphp

                    Report Date : {{date('d/m/Y')}}
                
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
            
            @if(isset($report) && $report=='detailed')
            
                
                
                
                    
                @if($enter_loan_details == 1)
                <div class="page-break"></div>
                @include('frontend.calculators.common.header')
                    <main class="mainPdf">
                    <h1 class="bluebar" style="background:{{$city_color}}">Annual Investment & Expected Fund Value</h1>
                    <div class="roundBorderHolder withBluebar">
                        <table>
                            <tbody>
                                <tr>
                                    <th style="background:{{$address_color_background}}">Year</th>
                                    <th style="background:{{$address_color_background}}">Annual Investment</th>
                                    <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                    <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                                </tr>
                        @php 
                            $annual_investment_total = 0;
                            $expected_fund_value = 0;
                        @endphp
                        @for($i=1;$i<=$period;$i++)
                            @php
                                if($i == 1){
                                    $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                }else{
                                    $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                }
                                $annual_investment_total = $annual_investment_total + $annual_investment;
    
                                $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);
    
                                $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                                $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;
                                $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                            @endphp
                            <tr>
                                    <td>{{$i}} </td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($annual_investment)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($annual_investment_total)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
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
                                                <th style="background:{{$address_color_background}}">Year</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                                <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                                            </tr>
                                    @endif
                                @endfor
            
                                    </tbody>
                                </table>
                                    </div>
                                
                @else
                </main>
                        <div class="page-break"></div>
                        @include('frontend.calculators.common.header')
                <main class="mainPdf">
                        <h1 class="bluebar" style="background:{{$city_color}}">Annual Investment & Expected Fund Value</h1>
                        <div class="roundBorderHolder withBluebar doubleLineTableTitle">    
                            <table>
                                <tbody>
                            <tr>
                                <th style="background:{{$address_color_background}}" rowspan="2">Year</th>
                                <th style="background:{{$address_color_background}}" colspan="3">Current Scenario</th>
                                <th style="background:{{$address_color_background}}" colspan="3">Incremental Scenario</th>
                            </tr>
                            <tr>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                            </tr>
                            @php 
                                $annual_investment_total = 0;
                                $ic_annual_investment_total = 0;
                            @endphp
                            @for($i=1;$i<=$period;$i++)
                                @php
                                    if($i == 1){
                                        $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                        $ic_annual_investment =  $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                    }else{
                                        $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                        $ic_annual_investment = $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                    }
                                    $annual_investment_total = $annual_investment_total + $annual_investment;
                                    $ic_annual_investment_total = $ic_annual_investment_total + $ic_annual_investment;
    
                                    $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;
    
                                    $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);
    
                                    $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                                    
                                    $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
    
                                    $eoy_value3 = (1+$expectedRateOfReturn/100)*$inclumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
    
                                    $eoy_value4 = (1+$rateofreturn)*$icurrentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                                @endphp
                                <tr>
                                        <td style="padding-left:2px;padding-right:2px;">{{$i}} </td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($annual_investment)}}</td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($annual_investment_total)}}</td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($ic_annual_investment)}}</td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($ic_annual_investment_total)}}</td>
                                        <td style="padding-left:2px;padding-right:2px;"><span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($eoy_value + $eoy_value1 + $eoy_value2 + $eoy_value3 +$eoy_value4)}}</td>
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
                                                <th style="background:{{$address_color_background}}" rowspan="2">Year</th>
                                                <th style="background:{{$address_color_background}}" colspan="3">Current Scenario</th>
                                                <th style="background:{{$address_color_background}}" colspan="3">Incremental Scenario</th>
                                            </tr>
                                            <tr>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                                <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                                <th style="background:{{$address_color_background}}">Expected Fund Value</th>
                                            </tr>
                                    @endif
                                @endfor
        
                                </tbody>
                            </table>
                                    </div>
                @endif

                @php
                $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','portfolio_projection')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
                @php } @endphp


                Report Date : {{date('d/m/Y')}}
            </main>
        
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
            @endif
                
            @include('frontend.calculators.suggested.pdf')
    
    </body>
</html>