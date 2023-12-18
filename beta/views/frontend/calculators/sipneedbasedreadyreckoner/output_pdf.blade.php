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

    @php
        if($period1 == ""){
            $period1 = 0;
        }
        if($period2 == ""){
            $period2 = 0;
        }
        if($period3 == ""){
            $period3 = 0;
        }
        if($period4 == ""){
            $period4 = 0;
        }
        if($period5 == ""){
            $period5 = 0;
        }
        
        if($interest1 == ""){
            $interest1 = 0;
        }
        if($interest2 == ""){
            $interest2 = 0;
        }
        if($interest3 == ""){
            $interest3 = 0;
        }
        
        //(1+D34)^(1/12)-1
        $return1 = (pow((1+$interest1/100),(1/12))-1);
        // (AT35*BD35)/((1+BD35)*((1+BD35)^(AY35*12)-1))/1000
        //$price1 = ((1+$return1)*$investment*((pow((1+$return1),($period1*12))-1)/$return1));
        $price1 = ($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1))/1000;
    @endphp

    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">SIP Need Based Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody><tr>
                    <td style="width: 50%;">
                        <strong>Target Amount</strong>
                    </td>
                    <td style="width: 50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($investment)}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <h1 class="pdfTitie">Monthly SIP Required</h1>
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
    <div class="roundBorderHolder">
        <table >
            <tbody>
            <tr>
                <th rowspan="2"><strong>Rate of Return</strong></th>
                <th colspan="{{$cs}}"><strong>SIP Period (Years)</strong></th>
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
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                </td>
                @php if($period2!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period3!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period4!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period5!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest1/100),(1/12))-1))/((1+(pow((1+$interest1/100),(1/12))-1))*(pow((1+(pow((1+$interest1/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                </td>
                @php } @endphp
            </tr>
            @php if($interest2!='0'){ @endphp
            <tr>
                <td>
                    <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                </td>
                @php if($period2!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                </td>
                 @php } @endphp
                @php if($period3!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                </td>
                 @php } @endphp
                @php if($period4!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                </td>
                
                 @php } @endphp
                @php if($period5!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest2/100),(1/12))-1))/((1+(pow((1+$interest2/100),(1/12))-1))*(pow((1+(pow((1+$interest2/100),(1/12))-1)),($period5*12))-1)))}} </strong>
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
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period1*12))-1)))}} </strong>
                </td>
                @php if($period2!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period2*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period3!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period3*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period4!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period4*12))-1)))}} </strong>
                </td>
                @php } @endphp
                @php if($period5!='0'){ @endphp
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format(($investment*(pow((1+$interest3/100),(1/12))-1))/((1+(pow((1+$interest3/100),(1/12))-1))*(pow((1+(pow((1+$interest3/100),(1/12))-1)),($period5*12))-1)))}} </strong>
                </td>
                @php } @endphp
            </tr>
            @php } @endphp
            <tr>
                <td colspan="6" style="text-align: right;padding-right: 30px;"><strong>(<span class="pdfRupeeIcon">&#8377;</span> in Thousands)</strong></td>
            </tr>
    
            </tbody>
        </table>
    </div>
    
    {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            

    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP_Goal_Planning_Ready_Recokner')->first();
    if(!empty($note_data1)){
    @endphp
    {!!$note_data1->description!!}
    @php } @endphp
</main>
    @include('frontend.calculators.common.footer')
    @include('frontend.calculators.suggested.pdf')

</body>
</html>
