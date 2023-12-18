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
            <div style="padding: 0 15%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Lumpsum Investment Need Based Ready Reckoner @if(isset($clientname)) <br>For {{$clientname?$clientname:''}} @endif</h1>
                <table>
                    <tbody><tr>
                        <td style="width: 50%;">
                            <strong>Target Amount</strong>
                        </td>
                        <td style="width: 50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($investment)}}
                        </td>
                    </tr>
                    </tbody>
                </table>

                <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Lumpsum Investment Required</h1>
                @php $cs=1; if($period2!='0'){ 
                    $cs++;
                }
                if($period3!='0'){ 
                    $cs++;
                }
                if($period4!='0'){ 
                    $cs++;
                }
                if($period5!='0'){ 
                    $cs++;
                }
            
                @endphp
                <table >
                    <tbody>
                    <tr>
                        <th rowspan="2"><strong>Rate of Return</strong></th>
                        <th colspan="{{$cs}}"><strong>Investment Period (Years)</strong></th>
                    </tr>
                    <tr>
                        <th><strong>{{$period1?$period1:''}}</strong></th>
                        @php if($period2!='0'){ @endphp
                        <th><strong>{{$period2?$period2:''}}</strong></th>
                        @php } @endphp
                        @php if($period3!='0'){ @endphp
                        <th><strong>{{$period3?$period3:''}}</strong></th>
                        @php } @endphp
                        @php if($period4!='0'){ @endphp
                        <th><strong>{{$period4?$period4:''}}</strong></th>
                        @php } @endphp
                        @php if($period5!='0'){ @endphp
                        <th><strong>{{$period5?$period5:''}}</strong></th>
                        @php } @endphp
                    </tr>
                    <tr>
                        <td>
                            <strong>{{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php if($interest2!='0'){ @endphp
                    <tr>
                        <td>
                            <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php } @endphp
                    @php if($interest3!='0'){ @endphp
                    <tr>
                        <td>
                            <strong>{{$interest3?number_format((float)$interest3, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0'){ @endphp
                        <td>
                            <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php } @endphp
                    <tr>
                        <td colspan="6" style="text-align: right"><strong>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> in Lacs)</strong></td>
                    </tr>
            
                    </tbody>
                </table>
                <p>
                    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                    * Returns are not guaranteed. The above is for illustration purpose only.
                </p>
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
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Term_Insurance_+_SIP_(Goal_Based)')->first();
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