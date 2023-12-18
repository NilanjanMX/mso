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
        $deferment_period = $investment_period - $payment_period;
    
        $totalinvestment = $amount*$payment_period;
        
        $rate1_percent  = pow((1+$interest1/100),1)-1;
        //AV21*(1+AV23)*(((1+AV23)^AV22)-1)/AV23
        $senario1_fund_amount = $amount*(1+$rate1_percent)*((pow((1+$rate1_percent),($payment_period))-1)/$rate1_percent);
        //AV33*(1+Q11%)^Q9
        $senario1_amount = $senario1_fund_amount*pow((1+$interest1/100),$deferment_period);
        $senario2_amount = 0;
            if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),1)-1;
            $senario2_fund_amount = $amount*(1+$rate2_percent)*((pow((1+$rate2_percent),($payment_period))-1)/$rate2_percent);
            $senario2_amount = $senario2_fund_amount*pow((1+$interest2/100),$deferment_period);
        }
    @endphp
    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">Annual Lumpsum  @if(isset($clientname) && !empty($clientname)) Calculation <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <strong>Annual Investment</strong>
                                </td>
                                <td style="width: 50%;">
                                    <span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($amount)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <strong>Investment Period</strong>
                                </td>
                                <td style="width: 50%;">
                                    {{$investment_period?$investment_period:0}} Years
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%;">
                                    <strong>Payment Period</strong>
                                </td>
                                <td style="width: 50%;">
                                    {{$payment_period?$payment_period:0}} Years
                                </td>
                            </tr>
                            @if(!isset($interest2))
                                <tr>
                                    <td style="left;Width:50%;">
                                        <strong>Assumed Rate of Return </strong>
                                    </td>
                                    <td style="width:50%;">
                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td style="width: 50%;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="padding: 0;width: 50%;">
                                        @if(isset($interest2))
                                            <table width="100%" style="margin: 0">
                                                <tbody>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        Scenario 1
                                                    </td>
                                                    <td style="width: 50%;">
                                                        {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 50%;">
                                                        Scenario 2
                                                    </td>
                                                    <td style="width: 50%;">
                                                        {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                    
                                        @else
                                            @ {{$interest1?number_format($interest1, 2, '.', ''):0}}% : <span class='pdfRupeeIcon'>&#8377;</span>{{number_format(($amount*pow((1+($interest1/100)), $deferment_period)))}}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                    
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
                            <td style="width: 50%;">
                                Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                            </td>
                            <td style="width: 50%;">
                                Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong>
                                <span class='pdfRupeeIcon'>&#8377;</span>{{custome_money_format($senario1_amount)}}
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
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Annual_Lump_sum_Investment')->first();
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
                    
                    <h1 class="bluebar" style="background:{{$city_color}}">Projected Annual Investment Value</h1>
                    <div class="roundBorderHolder withBluebar">
                    <table>
                        <tbody>
                        @if(isset($interest2))
                            <tr>
                                <th style="background:{{$address_color_background}}">Year</th>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $amount;
                                $previous_amount_int2 = $amount;
                            @endphp
    
                            @for($i=1;$i<=$investment_period;$i++)
                                @php
                                    if ($payment_period>=$i){
                                        $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),$i)-1)/$rate1_percent);
                                    }else{
                                        $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),1));
                                    }
                                    if ($payment_period>=$i){
                                        $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),$i)-1)/$rate2_percent);
                                    }else{
                                        $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),1));
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$payment_period)
                                            <span class='pdfRupeeIcon'>&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                    <td><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                                </tr>
                                @if($i%25==0 && $investment_period>25 && $investment_period>$i)
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
                                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            </tr>
                                @endif
                            @endfor
                        @else
                            <tr>
                                <th>Year</th>
                                <th>Annual Investment</th>
                                <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $amount;
                            @endphp
    
                            @for($i=1;$i<=$investment_period;$i++)
                                @php
                                    if ($payment_period>=$i){
                                        $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),$i)-1)/$rate1_percent);
                                    }else{
                                        $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),1));
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        @if($i<=$payment_period)
                                            <span class='pdfRupeeIcon'>&#8377;</span> {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td><span class='pdfRupeeIcon'>&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                </tr>
                                @if($i%25==0 && $investment_period && $investment_period>$i)
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
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            </tr>
                                @endif
            
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                            </div>
                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_Annual_Lump_sum_Investment')->first();
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
                
                    
                @if(isset($is_graph) && $is_graph)
                    <div class="page-break"></div>
                    @include('frontend.calculators.common.header')
                    <main class="mainPdf">
                    <h1 class="pdfTitie">Graphic Representation</h1>
                    <div class="graphView">
                        <img src="{{$pie_chart2}}" class="graphViewImg">
                    </div>
                    
                    </main>
                    @include('frontend.calculators.common.watermark')
                    
                    @if($footer_branding_option == "all_pages")
                        @include('frontend.calculators.common.footer')
                    @endif
                @endif
            @endif

            @include('frontend.calculators.suggested.pdf')
    </body>
</html>