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
        $number_of_months = $period*12;
        //rate1 = (1+Q10%)^(1/12)-1 (Q10 = senario 1)
        $rate1_percent = pow((1+($interest1/100)), (1/12))-1;
        //(Q7*AV33)/((1+AV33)*((1+AV33)^(AV32)-1))
        $senario1_monthly_amount = ($amount*$rate1_percent)/((1+$rate1_percent)*(pow((1+$rate1_percent),($number_of_months))-1));
        //AV35*AV32
        $senario1_amount = $senario1_monthly_amount*$number_of_months;
    
        if (isset($interest2)){
            $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
            //(Q7*AV34)/((1+AV34)*((1+AV34)^(AV32)-1))
            $senario2_monthly_amount = ($amount*$rate2_percent)/((1+$rate2_percent)*(pow((1+$rate2_percent),($number_of_months))-1));
            $senario2_amount = $senario2_monthly_amount*$number_of_months;;
    
    
        }
    
        if(isset($include_step_up) && $include_step_up=='yes'){
            //Step Up
        //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
        $ap1 = (1+$rate1_percent)*1*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
        //(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
        $stepup_senario1_found_value = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($period))-pow((1+$step_up_rate/100),($period)));
        //Q7/AV38
        $stepup_senario1_amount =$amount / $stepup_senario1_found_value;
        //(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
        $stepup_senario1_invest_amount = ($stepup_senario1_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));
    
            if (isset($interest2)){
                //Step Up
            //(1+AV34)*AV32*(((1+AV34)^(12)-1)/AV34)
            $ap2 = (1+$rate2_percent)*1*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
            //(AV36/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
             $stepup_senario2_found_value = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),($period))-pow((1+$step_up_rate/100),($period)));
            //Q7/AV38
             $stepup_senario2_amount =$amount / $stepup_senario2_found_value;
             //(AV42*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
             $stepup_senario2_invest_amount = ($stepup_senario2_amount*12)*((pow((1+$step_up_rate/100),$period)-1)/((1+$step_up_rate/100)-1));
            }
        }
    @endphp         
    @include('frontend.calculators.common.header')
        
        <main class="mainPdf">
            <div style="padding: 0 0%;">
                <h1 class="pdfTitie">SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <div class="roundBorderHolder">
                    <table>
                        <tbody>
                        <tr>
                            <td style="Width:50%;">
                                <strong>Target Amount</strong>
                            </td>
                            <td style="Width:50%;">
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="Width:50%;">
                                <strong>SIP Period  </strong>
                            </td>
                            <td style="Width:50%;">
                                {{$period?$period:0}} Years
                            </td>
                        </tr>
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            <tr>
                                <td style="Width:50%;">
                                    <strong> Step-Up % Every Year  </strong>
                                </td>
                                <td style="Width:50%;">
                                    {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
                                </td>
                            </tr>
                        @endif
                        @if(!isset($interest2))
                            <tr>
                                <td style="Width:50%;">
                                    <strong>Expected Rate of Return </strong>
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
            
            <h1 class="pdfTitie">Monthly SIP Required</h1>
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($interest2))
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            <tr>
                                <th><strong>Mode</strong></th>
                                <th>
                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th>
                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td><strong>Normal SIP</strong></td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}} </strong>
                                </td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_monthly_amount)}} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Step-Up SIP</strong></td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                                </td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario2_amount)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th>
                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th>
                                    <strong>Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}}
                                </td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_monthly_amount)}}
                                </td>
                            </tr>
                        @endif
                    @else
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            <tr>
                                <th style="width: 50%"><strong>Normal SIP</strong></th>
                                <th style="width: 50%"><strong>Step-Up SIP</strong></th>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}} </strong>
                                </td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                                </td>
                            </tr>
    
                            {{--<tr>
                                <th style="width: 50%"><strong>Mode</strong></th>
                                <th style="width: 50%">
                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%"><strong>Normal SIP</strong></td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%"><strong>Step-Up SIP</strong></td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                                </td>
                            </tr>--}}
                        @else
                            <tr>
                                <th>
                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_monthly_amount)}}
                                </td>
                            </tr>
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>
            <h1 class="pdfTitie">Total Investment</h1>
            <div class="roundBorderHolder">
                <table class="table text-center">
                    <tbody>
                    @if(isset($interest2))
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            <tr>
                                <th><strong>Mode</strong></th>
                                <th>
                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th>
                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td><strong>Normal SIP</strong></td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Step-Up SIP</strong></td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                                </td>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario2_invest_amount)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th style="width: 50%">
                                    <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                                <th style="width: 50%">
                                    <strong>Scenario 2 @ {{$interest2?number_format($interest2, 2, '.', ''):0}} %<strong>
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                                </td>
                            </tr>
                        @endif
                    @else
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            <tr>
                                <th style="width: 50%"><strong>Normal SIP</strong></th>
                                <th style="width: 50%"><strong>Step-Up SIP</strong></th>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                                </td>
                            </tr>
    
    
                            {{--<tr>
                                <th style="width: 50%"><strong>Mode</strong></th>
                                <th style="width: 50%">
                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td style="width: 50%"><strong>Normal SIP</strong></td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%"><strong>Step-Up SIP</strong></td>
                                <td style="width: 50%">
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($stepup_senario1_invest_amount)}} </strong>
                                </td>
                            </tr>--}}
                        @else
                            <tr>
                                <th>
                                    <strong> @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                                </th>
                            </tr>
                            <tr>
                                <td>
                                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                                </td>
                            </tr>
                        @endif
                    @endif
                    </tbody>
                </table>
            </div>
            
            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
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

            <h1 class="bluebar" style="background:{{$city_color}}">
                @if(isset($include_step_up) && $include_step_up=='yes')
                    Normal SIP <br>
                @endif
                Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                <table>
                    <tbody>
                    @if(isset($interest2))
                        <tr>
                            <th style="background:{{$address_color_background}}; vertical-align: middle" rowspan="2">Year</th>
                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                        </tr>
                        <tr>
                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                            <th style="background:{{$address_color_background}}">Year End Value</th>
                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                            <th style="background:{{$address_color_background}}">Year End Value</th>
                        </tr>
                        @php
                            $previous_amount_int1 = $amount;
                            $previous_amount_int2 = $amount;
                        @endphp
        
                        @for($i=1;$i<=$period;$i++)
                            @php
                                //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                                $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                $previous_amount_int2 = (1+$rate2_percent)*$senario2_monthly_amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                                </td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{$senario2_monthly_amount?custome_money_format($senario2_monthly_amount*12):0}}
                                </td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                            </tr>
        
        
                            @if($i%25==0 && $period>25 && $period>$i && $period>$i)
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
                                            <th style="background:{{$address_color_background}}; vertical-align: middle" rowspan="2">Year</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                        </tr>
                                        <tr>
                                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                                            <th style="background:{{$address_color_background}}">Year End Value</th>
                                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                                            <th style="background:{{$address_color_background}}">Year End Value</th>
                                        </tr>
                            @endif
                        @endfor
                            @if($period%25!=0 && $period>25 )
                                
                        @endif
                @else
                    {{--<tr>
                        <th style="vertical-align: middle" rowspan="2">Year</th>
                        <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    </tr>--}}
                    <tr>
                        <th style="background:{{$address_color_background}}">Year</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value</th>
                    </tr>
                    @php
                        $previous_amount_int1 = $amount;
                    @endphp
    
                    @for($i=1;$i<=$period;$i++)
                        @php
                            //(1+AZ73)*AX73*(((1+AZ73)^(AW73*12)-1)/AZ73)
                            $previous_amount_int1 = (1+$rate1_percent)*$senario1_monthly_amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
    
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$senario1_monthly_amount?custome_money_format($senario1_monthly_amount*12):0}}
                            </td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
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
                                            <th style="background:{{$address_color_background}}; vertical-align: middle">Year</th>
                                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                                            <th style="background:{{$address_color_background}}">Year End Value</th>
                                        </tr>
                         @endif
    
                    @endfor
                                        @if($period%25!=0 && $period>25 )
                                        
                                        @endif
                @endif
                    </tbody>
                </table>
            </div>
            
            @php
            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
            if(!empty($note_data2)){
            @endphp
            {!!$note_data2->description!!}
            
            Report Date : {{date('d/m/Y')}}
            @php } @endphp
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
        
            
            @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
            <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
                <main class="mainPdf">
                <h1 class="bluebar" style="background:{{$city_color}}">
                Step - Up SIP<br>Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                <table>
                    <tbody>
                    @if(isset($interest2))
                        <tr>
                            <th style="background:{{$address_color_background}};vertical-align: middle" rowspan="2">Year</th>
                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                            <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                        </tr>
                        <tr>
                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                            <th style="background:{{$address_color_background}}">Year End Value</th>
                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                            <th style="background:{{$address_color_background}}">Year End Value</th>
                        </tr>
                        @php
                            //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                            $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                            $ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                            $previous_amount_int1 = $amount;
                            $previous_amount_int2 = $amount;
                            $stepup_senario1_change_amount = $stepup_senario1_amount;
                            $stepup_senario2_change_amount = $stepup_senario2_amount;
                        @endphp
        
                        @for($i=1;$i<=$period;$i++)
                            @php
        
                                if ($i==1){
                                    $stepup_senario1_change_amount = $stepup_senario1_amount;
                                    $stepup_senario2_change_amount = $stepup_senario2_amount;
                                }else{
                                    $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                                    $stepup_senario2_change_amount = $stepup_senario2_change_amount+($stepup_senario2_change_amount*$step_up_rate/100);
                                }
        
                                //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                                $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));
                                $previous_amount_int2 = ($ap2_stepup/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$i)-pow((1+$step_up_rate/100),$i));
        
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                                </td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
                                <td>
                                    <span class="pdfRupeeIcon">&#8377;</span> {{$stepup_senario2_change_amount?custome_money_format($stepup_senario2_change_amount*12):0}}
                                </td>
                                <td><span class="pdfRupeeIcon">&#8377;</span> {{$previous_amount_int2?custome_money_format($previous_amount_int2):0}}</td>
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
                                                <th style="background:{{$address_color_background}};vertical-align: middle" rowspan="2">Year</th>
                                                <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                            </tr>
                                            <tr>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Year End Value</th>
                                                <th style="background:{{$address_color_background}}">Annual Investment</th>
                                                <th style="background:{{$address_color_background}}">Year End Value</th>
                                            </tr>
                             @endif
        
                        @endfor
                                            @if($period%25!=0 && $period>25 )
                                                    
                                        @endif
                @else
                   {{-- <tr>
                        <th style="vertical-align: middle" rowspan="2">Year</th>
                        <th colspan="2"> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    </tr>--}}
                    <tr>
                        <th style="background:{{$address_color_background}}">Year</th>
                        <th style="background:{{$address_color_background}}">Annual Investment</th>
                        <th style="background:{{$address_color_background}}">Year End Value</th>
                    </tr>
                    @php
                        //(1+BB117)*AX117*(((1+BB117)^(12)-1)/BB117)
                        $ap1_stepup = (1+$rate1_percent)*$stepup_senario1_amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
                        //$ap2_stepup = (1+$rate2_percent)*$stepup_senario2_amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
                        $previous_amount_int1 = $amount;
                        //$previous_amount_int2 = $amount;
                        $stepup_senario1_change_amount = $stepup_senario1_amount;
                        //$stepup_senario2_change_amount = $stepup_senario2_amount;
                    @endphp
    
                    @for($i=1;$i<=$period;$i++)
                        @php
    
                            if ($i==1){
                                $stepup_senario1_change_amount = $stepup_senario1_amount;
                            }else{
                                $stepup_senario1_change_amount = $stepup_senario1_change_amount+($stepup_senario1_change_amount*$step_up_rate/100);
                            }
    
                            //(AZ117/(BD117-BF117))*((1+BD117)^(AW117)-(1+BF117)^(AW117))
                            $previous_amount_int1 = ($ap1_stepup/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$i)-pow((1+$step_up_rate/100),$i));
    
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <span class="pdfRupeeIcon">&#8377;</span> {{$stepup_senario1_change_amount?custome_money_format($stepup_senario1_change_amount*12):0}}
                            </td>
                            <td><span class="pdfRupeeIcon">&#8377;</span> {{$previous_amount_int1?custome_money_format($previous_amount_int1):0}}</td>
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
                                            <th style="background:{{$address_color_background}};vertical-align: middle;">Year</th>
                                            <th style="background:{{$address_color_background}}">Annual Investment</th>
                                            <th style="background:{{$address_color_background}}">Year End Value</th>
                                        </tr>
                         @endif
                    @endfor
                                        @if($period%25!=0 && $period>25)
                                        
                                        @endif
                @endif
                </tbody>
            </table>
        </div>
            @php
            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
            if(!empty($note_data2)){
            @endphp
            {!!$note_data2->description!!}
            
            Report Date : {{date('d/m/Y')}}
            @php } @endphp
        </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
            @endif
    
                        @if(isset($report) && $report=='detailed' && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                <div class="page-break"></div>
                
                @include('frontend.calculators.common.header')
        <main class="mainPdf">

                <h1 class="bluebar" style="background:{{$city_color}}">
                    Cost of Delay in Starting Normal SIP
                </h1>
                            @php
                                $cost_delay_sip_amount1=0;
                                $cost_delay_sip_amount2=0;
                            @endphp
                    <div class="roundBorderHolder withBluebar doubleLineTableTitle">
                        <table>
                            <tbody>
                            @if(isset($interest2))
                                <tr>
                                    <td colspan="5">
                                        This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.
                                    </td>
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}};vertical-align: middle" rowspan="2">Delay in No. of Year</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                </tr>
                                <tr>
                                    <th style="background:{{$address_color_background}}">SIP Amount</th>
                                    <th style="background:{{$address_color_background}}">Total Investment</th>
                                    <th style="background:{{$address_color_background}}">SIP Amount</th>
                                    <th style="background:{{$address_color_background}}">Total Investment</th>
                                </tr>
                                @for($i=1;$i<$period;$i++)
                                    @php
                                        $year_left = $period-$i;
                                        //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                                        $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                                        $sipamount2 = ($amount*$rate2_percent) / ((1+$rate2_percent)*(pow((1+$rate2_percent),($year_left*12))-1));
                                        $totalinvestment1 = $sipamount1*$year_left*12;
                                        $totalinvestment2 = $sipamount2*$year_left*12;
                                        if ($i==1){
                                                                $cost_delay_sip_amount1=$sipamount1;
                                                                $cost_delay_sip_amount2=$sipamount2;
                                                            }
                                    @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                    <span class="pdfRupeeIcon">&#8377;</span> {{$sipamount1?custome_money_format($sipamount1):0}}
                                                </td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
                                                <td>
                                                    <span class="pdfRupeeIcon">&#8377;</span> {{$sipamount2?custome_money_format($sipamount2):0}}
                                                </td>
                                                <td><span class="pdfRupeeIcon">&#8377;</span> {{$totalinvestment2?custome_money_format($totalinvestment2):0}}</td>
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
                                                    <!--<tr>-->
                                                    <!--    <td colspan="5">-->
                                                    <!--        This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.-->
                                                    <!--    </td>-->
                                                    <!--</tr>-->
                                                    <tr>
                                                        <th style="background:{{$address_color_background}};vertical-align: middle" rowspan="2">Delay in No. of Year</th>
                                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 1 @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                                        <th style="background:{{$address_color_background}}" colspan="2">Scenario 2 @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="background:{{$address_color_background}}">SIP Amount</th>
                                                        <th style="background:{{$address_color_background}}">Total Investment</th>
                                                        <th style="background:{{$address_color_background}}">SIP Amount</th>
                                                        <th style="background:{{$address_color_background}}">Total Investment</th>
                                                    </tr>
                                             @endif
                    
                                        @endfor
                                        @if($period%25!=0 && $period>25 )
                                    
                                    
                        @endif
                    @else
                        <!--<tr>-->
                        <!--    <td colspan="3">-->
                        <!--        This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.-->
                        <!--    </td>-->
                        <!--</tr>-->
    
                        <tr>
                            <th style="background:{{$address_color_background}};vertical-align: middle">Delay in No. of Year</th>
                            <th style="background:{{$address_color_background}}">SIP Amount</th>
                            <th style="background:{{$address_color_background}}">Total Investment</th>
                        </tr>
                        @for($i=1;$i<$period;$i++)
                                    @php
                                        $year_left = $period-$i;
                                        //(AY160*AZ160)/((1+AZ160)*((1+AZ160)^(AX160*12)-1))
                                        $sipamount1 = ($amount*$rate1_percent) / ((1+$rate1_percent)*(pow((1+$rate1_percent),($year_left*12))-1));
                                        $totalinvestment1 = $sipamount1*$year_left*12;
                                        if ($i==1){
                                                        $cost_delay_sip_amount1=$sipamount1;
                                                    }
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            <span class="pdfRupeeIcon">&#8377;</span> {{$sipamount1?custome_money_format($sipamount1):0}}
                                        </td>
                                        <td><span class="pdfRupeeIcon">&#8377;</span> {{$totalinvestment1?custome_money_format($totalinvestment1):0}}</td>
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
                                                <!--<tr>-->
                                                <!--    <td colspan="3">-->
                                                <!--        This illustration explains the increase in SIP amount due to delay in starting your SIP to achieve the target amount.-->
                                                <!--    </td>-->
                                                <!--</tr>-->
    
                                                <tr>
                                                    <th style="background:{{$address_color_background}};vertical-align: middle">Delay in No. of Year</th>
                                                    <th style="background:{{$address_color_background}}">SIP Amount</th>
                                                    <th style="background:{{$address_color_background}}">Total Investment</th>
                                                </tr>
                                        @endif
    
                        @endfor
    
                        @if($period%25!=0 && $period>25 )
                            
                            
                        @endif
    
    
                @endif
                        </tbody>
                                </table>
                            </div>
                            
                    @if(isset($interest2) && isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                        <p style="text-align: left;background:{{$address_color_background}};padding: 5px;">
                            For example, If you delay your SIP by 1 year, your SIP amount will increase to <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($cost_delay_sip_amount1)}} instead of <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($senario1_monthly_amount)}} in case of Scenario(1) and will increase to <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($cost_delay_sip_amount2)}} instead of <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($senario2_monthly_amount)}} in case of Scenario(2).
                        </p>
                                @elseif(isset($include_cost_delay_report) && $include_cost_delay_report=='yes')
                        <p style="text-align: left;background:{{$address_color_background}};padding: 5px;">
                            For example, If you delay your SIP by 1 year, your SIP amount will increase to <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($cost_delay_sip_amount1)}} instead of <span class="pdfRupeeIcon">&#8377;</span>{{custome_money_format($senario1_monthly_amount)}}.
                        </p>
                    @endif
                    
                    
                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','SIP_Required_For_Target_Future_Value')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                        
                        Report Date : {{date('d/m/Y')}}
                        @php } @endphp
                        
                                </main>
                @include('frontend.calculators.common.watermark')
                @if($footer_branding_option == "all_pages")
                    @include('frontend.calculators.common.footer')
                @endif
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
                    
                    @if($footer_branding_option == "all_pages" || !((isset($suggest) && session()->has('suggested_scheme_list'))))
                        @include('frontend.calculators.common.footer')
                    @endif
                @endif
            @endif

            @include('frontend.calculators.suggested.pdf')
    </body>
</html>