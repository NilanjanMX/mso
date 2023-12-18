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
    //Number of Months Q13*12
    $number_of_months = $investment_period*12;
    //Exp Rate of Return (SIP) (1+Q10%)^(1/12)-1
    $expected_rate_of_return_sip = (1+$expected_rate_of_return1/100)**(1/12)-1;
    //Exp Rate of Return (Lumpsum) (1+Q12%)^(1/12)-1
    $expected_rate_of_returnlumpsum = (1+$expected_rate_of_return2/100)**(1/12)-1;
    //SIP Fund Value (1+AR31)*Q9*(((1+AR31)^(AR30)-1)/AR31)
    $sip_fund_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($number_of_months)-1)/$expected_rate_of_return_sip);
    //Lumpsum Fund Value Q11*(1+AR32)^AR30
    $lumpsum_fund_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**$number_of_months;
    //Total Fund Value AR33+AR34
    $total_fund_value = $sip_fund_value+$lumpsum_fund_value;
@endphp

@include('frontend.calculators.common.header')
        
<main class="mainPdf">
    

    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">Future Value Of Lumpsum + SIP @if(isset($clientname)) Proposal <br>For {{$clientname?$clientname:''}} @else Proposal @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="Width:50%;">
                        <strong>SIP Amount</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>Lumpsum Investment</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsum_investment)}}
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
                            <tr>
                                <td style="Width:50%;">SIP</td>
                                <td style="Width:50%;">{{$expected_rate_of_return1?number_format($expected_rate_of_return1, 2, '.', ''):0}} %</td>
                            </tr>
                            <tr>
                                <td style="Width:50%;">Lumpsum</td>
                                <td style="Width:50%;">{{$expected_rate_of_return2?number_format($expected_rate_of_return2, 2, '.', ''):0}} %</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        
        <h1 class="pdfTitie">Expected Future Value</h1>
        <div class="roundBorderHolder">
            <table class="table table-bordered text-center">
                <tbody>
                <tr>
                    <td style="Width:50%;"><strong>SIP Fund Value</strong></td>
                    <td style="Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_fund_value)}}</td>
                </tr>
                <tr>
                    <td style="Width:50%;"><strong>Lumpsum Fund Value</strong></td>
                    <td style="Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsum_fund_value)}}</td>
                </tr>
                <tr>
                    <td style="Width:50%;"><strong>Total Fund Value</strong></td>
                    <td style="Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($total_fund_value)}}</td>
                </tr>
                </tbody>
            </table>
        </div>
        
        {{-- comment or note section here --}}
        @include('frontend.calculators.common.comment_pdf')

    </div>
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Lumpsum_+_SIP')->first();
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
        
                @for($i=1;$i<=$investment_period;$i++)
                    @php
                        //Annual Investment AU76*12+AV76
                        if ($i==1){
                        $annual_investment = $sip_amount*12+$lumpsum_investment;
                        }else{
                            $annual_investment = $sip_amount*12;
                        }
                        //Cumulative Investment
                        $cumulative_investment +=$annual_investment;
                        //SIP End Value (1+AS76)*AU76*(((1+AS76)^(AR76*12)-1)/AS76)
                        $sip_end_value = (1+$expected_rate_of_return_sip)*$sip_amount*(((1+$expected_rate_of_return_sip)**($i*12)-1)/$expected_rate_of_return_sip);
                        //Lumpsum End Value AV76*(1+AT76)^(AR76*12)
                        $lumpsum_end_value = $lumpsum_investment*(1+$expected_rate_of_returnlumpsum)**($i*12);
                    @endphp
                    <tr>
                        <td>{{$i}}</td>
                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($cumulative_investment)}}</td>
                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_end_value)}}</td>
                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($lumpsum_end_value)}}</td>
                        <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($sip_end_value+$lumpsum_end_value)}}</td>
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
            
                    </tbody>
                </table>
                            </div>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_Lumpsum_+_SIP')->first();
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
