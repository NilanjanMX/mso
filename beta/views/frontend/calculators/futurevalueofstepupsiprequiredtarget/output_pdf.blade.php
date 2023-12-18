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

    //rate2 = (1+Q11%)^(1/12)-1 (Q10 = senario 2)
    $number_of_months = $period*12;
    $rate1_percent = pow((1+($interest1/100)), (1/12))-1;
     //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
    $ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
    //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
    $senario1_stepup_fund_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
    //Q9/AV38
    $senario1_stepup_monthly_amount = $amount / $senario1_stepup_fund_amount;
    //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
    $senario1_total_investment_amount = ($senario1_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

    //senario1_amount = (AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
    //(AV34/(Q12%-Q9%))*((1+Q12%)^(Q10)-(1+Q9%)^(Q10))
   $senario1_amount = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$period)-pow((1+$annual_increment/100),$period));
    if (isset($interest2)){
        $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
         //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
        $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
        //(AV36/(Q13%-Q11%))*((1+Q13%)^(Q10)-(1+Q11%)^(Q10))
        $senario2_stepup_fund_amount = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$period)-pow((1+$annual_increment/100),$period));
        //Q9/AV38
        $senario2_stepup_monthly_amount = $amount / $senario2_stepup_fund_amount;
        //(AV40*12)*(((1+Q11%)^(Q10)-1)/((1+Q11%)-1))
        $senario2_total_investment_amount = ($senario2_stepup_monthly_amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1));

    }

//(Q8*12)*(((1+Q9%)^(Q10)-1)/((1+Q9%)-1))
$total_investment = ($amount*12)*((pow((1+$annual_increment/100),$period)-1)/((1+$annual_increment/100)-1))

@endphp

@include('frontend.calculators.common.header')
        
<main class="mainPdf">
    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">Step-Up SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody><tr>
                    <td style="Width:50%;">
                        <strong>Target Amount</strong>
                    </td>
                    <td style="Width:50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($amount)}}
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong>SIP Period</strong>
                    </td>
                    <td style="Width:50%;">
                        {{$period?$period:0}} Years
                    </td>
                </tr>
                <tr>
                    <td style="Width:50%;">
                        <strong> Step-Up % Every Year  </strong>
                    </td>
                    <td style="Width:50%;">
                        {{$annual_increment?number_format($annual_increment, 2, '.', ''):0}} %
                    </td>
                </tr>
                @if(!isset($interest2))
                    <tr>
                        <td style="Width:50%;">
                            <strong>Assumed Rate of Return </strong>
                        </td>
                        <td style="Width:50%;">
                            {{$interest1?number_format($interest1, 2, '.', ''):0}} %
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
    <h1 class="pdfTitie">Monthly SIP Required</h1>
    <div class="roundBorderHolder">
        <table class="table table-bordered text-center">
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
                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_stepup_monthly_amount)}} </strong>
                    </td>
                    <td style="width: 50%;">
                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_stepup_monthly_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <td>
                        <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_stepup_monthly_amount)}}</strong>
                    </td>
                </tr>
            @endif
        </table>
    </div>
    @if(!isset($interest2))
        </div>
    @endif

    @if(!isset($interest2))
        <div style="padding: 0 0%;">
            @endif
            <h1 class="pdfTitie">Total Investment</h1>
            <div class="roundBorderHolder">
                <table class="table table-bordered text-center">
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
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_total_investment_amount)}} </strong>
                            </td>
                            <td style="width: 50%;">
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_total_investment_amount)}} </strong>
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td>
                                <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_total_investment_amount)}}</strong>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
    
            @if(!isset($interest2))
                </div>
            @endif

    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Step_Up_SIP_Required_For_Target_Future_Value')->first();
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
            Year-Wise Projected Value
        </h1>
        <div class="roundBorderHolder withBluebar doubleLineTableTitle">
            <table>
                <tbody>
                @if(isset($interest2))
                    <tr>
                        <th style="background:{{$address_color_background}}" rowspan="2">Year</th>
                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                    </tr>
                    <tr>
                        <th style="background:{{$address_color_background}}">Monthly Investment</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value</th>
                        <th style="background:{{$address_color_background}}">Monthly Investment</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value</th>
                    </tr>
                    @php
                        $previous_amount_int1 = $senario1_stepup_monthly_amount;
                        $previous_amount_int2 = $senario2_stepup_monthly_amount;
                        $change_amount = $senario1_stepup_monthly_amount;
                        $change_amount2 = $senario2_stepup_monthly_amount;
                    @endphp

                    @for($i=1;$i<=$period;$i++)
                        @php
                            //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                            $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                            //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                            $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                            //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                            if ($i==1){
                                $change_amount = $senario1_stepup_monthly_amount;
                            }else{
                                $change_amount = $change_amount+($change_amount*$annual_increment/100);
                            }

                        //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                            $ap2 = (1+$rate2_percent)*$senario2_stepup_monthly_amount*((pow((1+$rate2_percent),12)-1)/$rate2_percent);
                            //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                            $previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),$i)-pow((1+$annual_increment/100),$i));
                            //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                            if ($i==1){
                                $change_amount2 = $senario2_stepup_monthly_amount;
                            }else{
                                $change_amount2 = $change_amount2+($change_amount2*$annual_increment/100);
                            }

                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount?custome_money_format($change_amount):0}}
                            </td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount?custome_money_format($change_amount*12):0}}
                            </td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount2?custome_money_format($change_amount2):0}}
                            </td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount2?custome_money_format($change_amount2*12):0}}
                            </td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                        </tr>



                        @if($i%25==0 && $period>25  && $period>$i)
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
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th style="background:{{$address_color_background}}" colspan="3">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    <tr>
                                        <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                        <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                                        <th style="background:{{$address_color_background}}">Year End Value</th>
                                    </tr>
                        @endif
        
        
                        @endfor
                        @else
                            <tr>
                                <th style="background:{{$address_color_background}}">Year</th>
                                <th style="background:{{$address_color_background}}">Monthly Investment</th>
                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                <th style="background:{{$address_color_background}}">Year End Value</th>
                            </tr>
                            @php
                                $previous_amount_int1 = $senario1_stepup_monthly_amount;
                                $change_amount = $senario1_stepup_monthly_amount;
                            @endphp
        
                            @for($i=1;$i<=$period;$i++)
                                @php
                                    //(AZ70/(BD70-BF70))*((1+BD70)^(AW70)-(1+BF70)^(AW70))
                                    $ap1 = (1+$rate1_percent)*$senario1_stepup_monthly_amount*((pow((1+$rate1_percent),12)-1)/$rate1_percent);
                                    //(AZ71/(BD71-BF71))*((1+BD71)^(AW71)-(1+BF71)^(AW71))
                                    $previous_amount_int1 = ($ap1/($interest1/100-$annual_increment/100))*(pow((1+$interest1/100),$i)-pow((1+$annual_increment/100),$i));
                                    //$previous_amount_int2 = ($ap2/($interest2/100-$annual_increment/100))*(pow((1+$interest2/100),($i))-pow((1+$annual_increment/100),($i)));
                                    if ($i==1){
                                        $change_amount = $senario1_stepup_monthly_amount;
                                    }else{
                                        $change_amount = $change_amount+($change_amount*$annual_increment/100);
                                    }
                                @endphp
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount?custome_money_format($change_amount):0}}
                                    </td>
                                    <td>
                                        <span class="pdfRupeeIcon">&#8377;</span> {{$change_amount?custome_money_format($change_amount*12):0}}
                                    </td>
                                    <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
        
                                </tr>
        
                                @if($i%25==0 && $period>25  && $period>$i)
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
                                                <th style="background:{{$address_color_background}}">Year End Value</th>
                                            </tr>
                                    @endif
            
                            @endfor
                            @endif
                            </tbody>
                        </table>
                                    </div>
            @php
            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Step_Up_SIP_Required_For_Target_Future_Value')->first();
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
