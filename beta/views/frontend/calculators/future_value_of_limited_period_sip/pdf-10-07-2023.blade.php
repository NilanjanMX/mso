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
        $total_number_of_months = $sip_period*12;
        $totalinvestment = $amount*$sip_period*12;
        //(1+Q11%)^(1/12)-1
        $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
        //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
        $senario1_fund_amount = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($total_number_of_months))-1)/$rate1_percent);
        //AV33*(1+Q11%)^Q9
        $senario1_amount = $senario1_fund_amount*pow((1+$interest1/100),$deferment_period);
        $senario2_amount = 0;
         if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
            //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
            $senario2_fund_amount = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($total_number_of_months))-1)/$rate2_percent);
            //AV33*(1+Q11%)^Q9
            $senario2_amount = $senario2_fund_amount*pow((1+$interest2/100),$deferment_period);
            }
    @endphp     
    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Future Value of Limited Period SIP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Monthly SIP Amount</strong>
                            </td>
                            <td style="width: 50%;">
                                <span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>SIP Period</strong>
                            </td>
                            <td style="width: 50%;">
                                {{$sip_period?$sip_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Deferment Period</strong>
                            </td>
                            <td style="width: 50%;">
                                {{$deferment_period?$deferment_period:0}} Years
                            </td>
                        </tr>
                        @if(!isset($interest2))
                            <tr>
                                <td style="">
                                    <strong>Assumed Rate of Return </strong>
                                </td>
                                <td style="">
                                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
            
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Total Investment</h1>
                <div class="roundBorderHolder">
                    <table class="table text-center">
                        <tbody>
                        <tr>
                            <td>
                                <span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($totalinvestment)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @if(!isset($interest2))
                <div style="padding: 0 0%;">
            @endif
            <h1 class="pdfTitie">Expected Future Value</h1>
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($interest2))
    
                            <tr>
                                <td style="width: 50%">
                                    Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                </td>
                                <td style="width: 50%">
                                    Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                                <td>
                                    <strong><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                                </td>
                            </tr>
                    @else
                            <tr>
                                <td>
                                    <strong>
                                    <span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($senario1_amount)}}
                                    </strong>
                                </td>
                            </tr>
                    @endif
                    </tbody></table>
            </div>
            
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            @if(!isset($interest2))
                </div>
            @endif
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Limited_Period_SIP')->first();
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
                <h1 class="bluebar" style="background:{{$city_color}}">Year-Wise Projected Value</h1>
                <div class="roundBorderHolder withBluebar">
                    <table>
                        <tbody>
                        @if(isset($interest2))
                            <tr>
                                <th style="background:{{$address_color_background}}">Year</th>
                                <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                <th style="background:{{$address_color_background}}">Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $amount;
                                $previous_amount_int2 = $amount;
                            @endphp
                            @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                @php
                                    //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                    //
                                    if ($sip_period>=$i){
                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                    }else{
                                    //(BE69*(1+BC70)^12)
                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                    }
                                    if ($sip_period>=$i){
                                    $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                    }else{
                                    //(BE69*(1+BC70)^12)
                                    $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span class="pdfRupeeIcon">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span class="pdfRupeeIcon">&#8377;</span> {{$amount?custome_money_format($amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                                </tr>
                                
                                 @if($i%25==0 && $sip_period+$deferment_period>25 && $sip_period+$deferment_period>$i)
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
                                                <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                <th style="background:{{$address_color_background}}">Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            </tr>
                                @endif
                            
                            @endfor
                        @else
                            <tr>
                                <th style="background:{{$address_color_background}}">Year</th>
                                <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $amount;
                            @endphp
                            @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                @php
                                    //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                    //
                                    if ($sip_period>=$i){
                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                    }else{
                                    //(BE69*(1+BC70)^12)
                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                    }
    
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span class="pdfRupeeIcon">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($i<=$sip_period)
                                            <span class="pdfRupeeIcon">&#8377;</span> {{$amount?custome_money_format($amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                </tr>
                                
                                
                                @if($i%25==0 && $sip_period+$deferment_period>25 && $sip_period+$deferment_period>$i)
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
                                        <div class="roundBorderHolder withBluebar">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <th style="background:{{$address_color_background}}">Year</th>
                                                <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            </tr>
                                @endif
                                            
                            @endfor
                        @endif
                        </tbody>
                    </table>
                </div>
                <!--<p>*The above chart is approximate and for illustration purpose only</p>-->
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
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
            @include('frontend.calculators.suggested.pdf')
    </body>
</html>