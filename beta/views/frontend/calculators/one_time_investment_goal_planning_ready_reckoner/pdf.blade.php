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
                <h1 class="pdfTitie">Lumpsum Investment Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
                <div class="roundBorderHolder">
                <table>
                    <tbody><tr>
                        <td style="width: 50%;">
                            <strong>Lumpsum Investment</strong>
                        </td>
                        <td style="width: 50%;">
                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($investment)}}
                        </td>
                    </tr>
                    
                    </tbody>
                </table>
                </div>
            </div>
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            
            <h1 class="pdfTitie">Future Value of Lumpsum Investment</h1>
            @php $cs=1; 
            if($period2!='0' && $period2){ 
                $cs++;
            }
            if($period3!='0' && $period3){ 
                $cs++;
            }
            if($period4!='0' && $period4){ 
                $cs++;
            }
            if($period5!='0' && $period5){ 
                $cs++;
            }
        
            @endphp
            <div class="roundBorderHolder">
            <table class="table text-center">
                <tbody>
                    <tr>
                        <th rowspan="2"><strong>Rate of Return</strong></th>
                        <th colspan="{{$cs}}"><strong>Investment Period (Years)</strong></th>
                    </tr>
                    <tr>
                        <th><strong>{{$period1?$period1:''}}</strong></th>
                        @php if($period2!='0' && $period2){ @endphp
                        <th><strong>{{$period2?$period2:''}}</strong></th>
                        @php } @endphp
                        @php if($period3!='0' && $period3){ @endphp
                        <th><strong>{{$period3?$period3:''}}</strong></th>
                        @php } @endphp
                        @php if($period4!='0' && $period4){ @endphp
                        <th><strong>{{$period4?$period4:''}}</strong></th>
                        @php } @endphp
                        @php if($period5!='0' && $period5){ @endphp
                        <th><strong>{{$period5?$period5:''}}</strong></th>
                        @php } @endphp
                    </tr>
                    <tr>
                        <td>
                            <strong>{{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0' && $period2){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0' && $period3){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0' && $period4){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0' && $period5){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php if($interest2!='0' && $interest2){ @endphp
                    <tr>
                        <td>
                            <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0' && $period2){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0' && $period3){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0' && $period4){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0' && $period5){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php } @endphp
                    @php if($interest3!='0' && $interest3){ @endphp
                    <tr>
                        <td>
                            <strong>{{$interest3?number_format((float)$interest3, 2, '.', ''):0}} %</strong>
                        </td>
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period1)))}} </strong>
                        </td>
                        @php if($period2!='0' && $period2){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period2)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period3!='0' && $period3){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period3)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period4!='0' && $period4){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period4)))}} </strong>
                        </td>
                        @php } @endphp
                        @php if($period5!='0' && $period5){ @endphp
                        <td>
                            <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period5)))}} </strong>
                        </td>
                        @php } @endphp
                    </tr>
                    @php } @endphp
                    <tr>
                        <td colspan="6" style="text-align: right;padding-right:30px;"><strong>(<span class="pdfRupeeIcon">&#8377;</span> in Lacs)</strong></td>
                    </tr>
            
                </tbody>
            </table>
            </div>
            
            
            
            

            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumpsum_Investment_Ready_Reckoner')->first();
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
            
            @include('frontend.calculators.suggested.pdf')
    
    </body>
</html>