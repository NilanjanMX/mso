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
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
         //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
         //Monthly SIP Amount (R7*AV28)/((1+AV28)*((1+AV28)^(AV27)-1))
        $monthly_sip_amount = ($goal_amount*$rate_of_return2)/((1+$rate_of_return2)*((1+$rate_of_return2)**($number_of_months)-1));

    @endphp

    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Term Insurance + SIP (Goal Based) @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Current Age</strong>
                            </td>
                            <td style="Width:50%;">
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Term Insurance / Goal Amount</strong>
                            </td>
                            <td style="Width:50%;">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($goal_amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Term Insurance Period</strong>
                            </td>
                            <td style="Width:50%;">
                                {{$term_insurance_period?$term_insurance_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Term Insurance Annual Premium</strong>
                            </td>
                            <td style="Width:50%;">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($term_insurance_annual_premium)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
        
            </div>
        
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Monthly SIP Required <br>
                    To Achieve Goal @ {{number_format($rate_of_return, 2, '.', '')}} %
                </h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <p style="text-align: left">
                If you take Term Cover of  <b><span class="pdfRupeeIcon">&#8377;</span></b> {{custome_money_format($goal_amount)}} and do Monthly SIP of <b><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}</b> you may be
                assured of minimum payout of<br> <b><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($goal_amount)}}</b> either on survival at age <b>{{$term_insurance_period+$current_age}}</b> or unfortunate event of death, subject to fund performance at Assumed rate of return mentioned herewith.
            </p>
            
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Term_Insurance_+_SIP_(Goal_Based)')->first();
            @endphp
            @if(!empty($note_data1))
                {!!$note_data1->description!!}
            @endif
            Report Date : {{date('d/m/Y')}}
            
            @include('frontend.calculators.common.watermark')
            @if($footer_branding_option == "all_pages")
                @include('frontend.calculators.common.footer')
            @endif
            
            @if(isset($report) && $report=='detailed')
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">
                    
                <h1 class="bluebar" style="background:{{$city_color}}">
                    Yearwise Projected Value
                </h1>
                <div class="roundBorderHolder withBluebar">
                    <table>
                        <tbody>
                        <tr>
                            <th style="background:{{$address_color_background}}">Age</th>
                            <th style="background:{{$address_color_background}};width: 20%;">Life Cover</th>
                            <th style="background:{{$address_color_background}}">Annual Investment (Insurance Premium + SIP)</th>
                            <th style="background:{{$address_color_background}}">Year End SIP Value @ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                            <!-- <th>Risk Cover + Fund Value<br>(In case of Death)</th> -->
                            <th style="background:{{$address_color_background}}">In case of Death<br> (Life Cover + SIP Value)</th>
                        </tr>
                        @for($i=1;$i<=$term_insurance_period;$i++)
                            @php
                                //Annual Investment (Insurance Premium + SIP) AS59+AT59*12
                                $annual_investment = $term_insurance_annual_premium+$monthly_sip_amount*12;
                                //Year End Value  (1+AW59)*AT59*(((1+AW59)^(AV59*12)-1)/AW59)
                                $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                                $current_age++;
                            @endphp
                            <tr>
                                <td>{{$current_age}}</td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($goal_amount)}}</td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($year_end_value)}}</td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($goal_amount+$year_end_value)}}</td>
                            </tr>
                            @if($i%25==0 && $term_insurance_period>25  && $term_insurance_period>$i)
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
                                                <th style="background:{{$address_color_background}}">Age</th>
                                                <th style="background:{{$address_color_background}};width: 20%;">Life Cover</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment (Insurance Premium + SIP)</th>
                                                <th style="background:{{$address_color_background}}">Year End SIP Value @ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                                                <!-- <th>Risk Cover + Fund Value<br>(In case of Death)</th> -->
                                                <th style="background:{{$address_color_background}}">In case of Death<br> (Life Cover + SIP Value)</th>
                                            </tr>
                                            @endif
                            @endfor
        
                            </tbody>
                        </table>
                                    </div>
                @php
                $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Term_Insurance_+_SIP')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
                @php } @endphp
                    
                Report Date : {{date('d/m/Y')}}
            @endif
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
        @include('frontend.calculators.suggested.pdf')
        
    </body>
</html>