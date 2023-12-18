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
    $target_amount = $target_amount;
     $period = $investment_period;
     $period_in_months = $period * 12;

     $investment_type = $investment_type;
     $investment_amount = $investment_amount;

     $sip_interest_rate = $sip_interest_rate;
     $monthly_sip_interest_rate = (1+$sip_interest_rate/100)**(1/12)-1;

     $onetime_interest_rate = $onetime_interest_rate;
     $monthly_onetime_interest_rate = (1+$onetime_interest_rate/100)**(1/12)-1;

     if ($investment_type == "SIP") {
         $lumpsum_investment_amount = $investment_amount;
         $lumpsum_future_value = $investment_amount*(1+$monthly_onetime_interest_rate)**$period_in_months ;
         $required_sip_future_value = $target_amount - $lumpsum_future_value;
         $required_sip = ($required_sip_future_value * $monthly_sip_interest_rate) / ((1 + $monthly_sip_interest_rate) * (pow((1 + $monthly_sip_interest_rate), ($period_in_months)) - 1));
     }
     if ($investment_type == "lumpsum") {
         $sip_amount = $investment_amount;
         //(1+AR32)*Q12*(((1+AR32)^(AR31)-1)/AR32)
         $sip_future_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($period_in_months)-1)/$monthly_sip_interest_rate);
         $required_lumpsum_future_value = $target_amount - $sip_future_value;
         //AR36/(1+AR33)^AR31
         $required_onetime_investment = $required_lumpsum_future_value/(1+$monthly_onetime_interest_rate)**$period_in_months;
     }
@endphp

@include('frontend.calculators.common.header')
        
<main class="mainPdf">

    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">SIP + Lumpsum @if(isset($clientname)) Proposal <br>For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="Width:50%;">
                        <strong>Target Amount</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($target_amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>@if ($investment_type == "SIP") Lumpsum Investment @else SIP Amount @endif</strong></strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($investment_amount)}}
                    </td>
                </tr>
        
                <tr>
                    <td style="Width:50%;">
                        <strong>Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$investment_period?$investment_period:0}} Years
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>Assumed Rate of Return</strong>
                    </td>
                    <td style="Width:50%;padding: 0">
                        <table style="width: 100%;">
                            <tbody>
                            @if ($investment_type == "lumpsum")
                            <tr>
                                <td style="Width:50%;">SIP</td>
                                <td style="Width:50%;">{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                            </tr>
                            <tr>
                                <td style="Width:50%;">Lumpsum</td>
                                <td style="Width:50%;">{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                            </tr>
                            @else
                                <tr>
                                    <td style="Width:50%;">Lumpsum</td>
                                    <td style="Width:50%;">{{$onetime_interest_rate?number_format($onetime_interest_rate, 2, '.', ''):0}} %</td>
                                </tr>
                                <tr>
                                    <td style="Width:50%;">SIP</td>
                                    <td style="Width:50%;">{{$sip_interest_rate?number_format($sip_interest_rate, 2, '.', ''):0}} %</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    
        <h1 class="pdfTitie">
            @if ($investment_type == "SIP") Monthly SIP Required @else Lumpsum Investment Required @endif
        </h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="text-align: center;">
                        <strong>
                            @if ($investment_type == "SIP")
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($required_sip)}}
                            @else
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($required_onetime_investment)}}
                            @endif
                        </strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        
        {{-- comment or note section here --}}
        @include('frontend.calculators.common.comment_pdf')
    </div>
    
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP/Lumpsum_Investment_Required_for_Target_Future_Value')->first();
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
            Annual Investment & Yearwise Projected Value
        </h1>
        <div class="roundBorderHolder withBluebar">
            <table>
                <tbody>
        
                <tr>
                    <th style="background:{{$address_color_background}}">Year</th>
                    <th style="background:{{$address_color_background}}">Annual Investment</th>
                    <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                    <th style="background:{{$address_color_background}}">SIP Fund Value</th>
                    <th style="background:{{$address_color_background}}">Lumpsum Fund Value</th>
                    <th style="background:{{$address_color_background}}">Total Fund Value</th>
                </tr>
        
                @php
                    $cumulative_investment = 0;
                @endphp
        
                @php
                    $cumulative_investment = 0;
                @endphp
        
        
                @if ($investment_type == "SIP")
                    @for ($i = 1; $i <= $investment_period; $i++)
                        @php
                            $annual_investment = ($required_sip * 12);
                                    if ($i == 1) {
                                        $annual_investment = $lumpsum_investment_amount + ($required_sip * 12);
                                    }
        
                                    $cumulative_investment = $lumpsum_investment_amount + (($required_sip * 12) * $i);
        
                                    $sip_value = (1+$monthly_sip_interest_rate)*$required_sip*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                    $lumpsum_value = $lumpsum_investment_amount*(1+$monthly_onetime_interest_rate)**($i*12);
        
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsum_value)}}</td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_value + $lumpsum_value)}}</td>
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
                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                <th style="background:{{$address_color_background}}">SIP Fund Value</th>
                                <th style="background:{{$address_color_background}}">Lumpsum Fund Value</th>
                                <th style="background:{{$address_color_background}}">Total Fund Value</th>
                            </tr>
                            @endif
            @endfor

        @else
            @for ($i = 1; $i <= $investment_period; $i++)
                @php
                    $annual_investment = ($sip_amount * 12);
                                if ($i == 1) {
                                    $annual_investment = $required_onetime_investment + ($sip_amount * 12);
                                }

                                $cumulative_investment = $required_onetime_investment + (($sip_amount * 12) * $i);
                                $sip_value = (1+$monthly_sip_interest_rate)*$sip_amount*(((1+$monthly_sip_interest_rate)**($i*12)-1)/$monthly_sip_interest_rate);
                                $lumpsum_value = $required_onetime_investment*(1+$monthly_onetime_interest_rate)**($i*12);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_value)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsum_value)}}</td>
                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_value + $lumpsum_value)}}</td>
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
                                                <th style="background:{{$address_color_background}}">Cumulative Investment</th>
                                                <th style="background:{{$address_color_background}}">SIP Fund Value</th>
                                                <th style="background:{{$address_color_background}}">Lumpsum Fund Value</th>
                                                <th style="background:{{$address_color_background}}">Total Fund Value</th>
                                            </tr>
                                            @endif
                            @endfor
                        @endif
                
                
                
                        </tbody>
                    </table>
                                    </div>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP/Lumpsum_Investment_Required_for_Target_Future_Value')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
    </main>
        
        @include('frontend.calculators.common.watermark')
                    
        @if($footer_branding_option == "all_pages" || !((isset($suggest) && session()->has('suggested_scheme_list'))))
            @include('frontend.calculators.common.footer')
        @endif

@endif
    @include('frontend.calculators.suggested.pdf')

</body>
</html>
