<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .clearfix:after {
                content: "";
                display: table;
                clear: both;
            }

            a {
                color: #001028;
                text-decoration: none;
            }

            body {
                font-family: 'Poppins', sans-serif;
                position: relative;
                margin: 0px;
                color: #001028;
                font-size: 14px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                margin-bottom: 30px;
            }

            table th,
            table td {
                text-align: center;
                border: 1px solid #b8b8b8;
                padding: 5px 20px;
                font-weight: normal;
                color: #000;
            }

            table {
                margin: 0;
            }

            table th {
                font-weight: bold;
                background: #a9f3ff;
            }

            .table-bordered th, .table-bordered td{
                padding: 10px;
                font-size: 18px;
                text-align: center;
            }

            h1 {
                font-size: 20px !important;
                color: #131f55 !important;
                margin-bottom: 0 !important;
                margin-top: 15px !important;
            }

            .page-break {
                page-break-after: always;
            }

            @page {
                margin-top: 100px
            }

            header {
                position: fixed;
                top: -100px;
                left: 0px;
                right: 0px;
                height: 120px;
            }

            footer {
                position: fixed;
                bottom: -70px;
                left: -53px; 
                right: -53px;
                height: 144px;
            }
            footer p {
                font-size: 17px;
                color: #003063;
            }
            .watermark{
                font-size: 60px;
                color: rgba(0,0,0,0.10);
                position: absolute;
                top: -400px;
                left: 26%;
                z-index: 1;
                transform: rotate(-25deg);
                font-weight: 700;
            }
            
            .bluebar {
                background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;
            }



            .styleApril table th, .styleApril .table-bordered th {
                font-weight: bold;
                background: #fff;
                font-size: 16px;
            }
            .styleApril .roundBorderHolder {
                border: 1px solid #458ff6;
                overflow: hidden;
                border-radius: 12px;
            }
            .styleApril .withBluebar {
                border-top: none;
                border-radius: 0 0 12px 12px;
            }
            .styleApril .bluebar {
                border-radius: 12px 12px 0 0;
                font-weight: normal;
                font-size: 22px;
                border: 1px solid #458ff6;
                border-bottom: none;
            }
            .styleApril .roundBorderHolder .table {
                width: 101%;
                background-color: transparent;
                margin-left: -1px;
                margin-top: -1px;
                margin-bottom: -1px;
            }
            .styleApril table th,
            .styleApril table td {
                border: none;
                border-top: 1px solid #458ff6;
                border-right: 1px solid #458ff6;
                padding: 6px 5px;
                font-size: 15px;
            }
            .styleApril table table tr:first-child td {
                border-top: 0;
            }
            .styleApril table table tr td:first-child {
                border-left:0;
            }
            .styleApril table table tr td:last-child {
                border-right:0;
            }
            .styleApril table table tr:last-child td {
                border-bottom:0;
            }
            .styleApril .graphView {
                border: 2px solid #003063;
                border-radius:12px;
                overflow: hidden;
                padding: 5px;
            }
            .styleApril .graphHeading {
                color: #204a77;
                font-size: 25px;
                font-weight: normal;
                text-align: center;
                
            }
            .bluelinefooter {
                background:#003063;
            }
            .orangelinefooter {
                background:#dc5440;
                height: 30px;
                width: 50%;
            }
            .nobordernopadding {
                padding: 0px !important;
                border: 0px !important;
            }
            .tableh1heading {
                background-color: #131f45;
                color:#fff !important;
                font-size:20px;
                padding:10px;
                text-align:center;
                margin: 0;
                vertical-align: top;
            }
            .margintop30px {
                margin-top: 30px;
            }
    </style>
</head>
<body class="styleApril">

@php
    
    //rate1 = (1+Q10%)^(1/12)-1 (Q10 = senario 1)
    //rate2 = (1+Q11%)^(1/12)-1 (Q10 = senario 2)
    $number_of_months = $period*12;
    $rate1_percent = pow((1+($interest1/100)), (1/12))-1;

    //senario1_amount = (1+AV32)*Q7*(((1+AV32)^(AV31)-1)/AV32)
    $senario1_amount = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($number_of_months))-1)/$rate1_percent);
    if (isset($interest2)){
        $rate2_percent = pow((1+($interest2/100)), (1/12))-1;
        $senario2_amount = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($number_of_months))-1)/$rate2_percent);
    }
//------OLD CODE----
//Step UP (Q7*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
//if(isset($include_step_up) && $include_step_up=='yes'){
    //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
 //   $ap1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
 //   $stepup_amount = $amount*12 * (pow((1+$step_up_rate/100),($period))-1) / ((1+$step_up_rate/100)-1);
    //One = (AV34/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
    //$stepup_senario1_amount = (1+$rate1_percent)*$stepup_amount*((pow((1+$rate1_percent),($number_of_months))-1)/$rate1_percent);
 //   $stepup_senario1_amount = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$period)-pow((1+$step_up_rate/100),$period));

 //   if (isset($interest2)){
        //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
 //       $ap2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
   //     $stepup_senario2_amount = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$period)-pow((1+$step_up_rate/100),$period));
   // }
//}
//---CODE ENd-----

//dd($stepup);
if(isset($include_step_up) && $include_step_up=='yes' && $stepup == "1"){
        //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
        $ap1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
        $stepup_amount = $amount*12 * (pow((1+$step_up_rate/100),($period))-1) / ((1+$step_up_rate/100)-1);
        //One = (AV34/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
        //$stepup_senario1_amount = (1+$rate1_percent)*$stepup_amount*((pow((1+$rate1_percent),($number_of_months))-1)/$rate1_percent);
        if($interest1 == $step_up_rate)
        $stepup_senario1_amount = $ap1 * $period * pow((1+$interest1/100),($period-1));
        else
        $stepup_senario1_amount = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$period)-pow((1+$step_up_rate/100),$period));

        if (isset($interest2)){
            //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
            $ap2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
            
            if($interest2 == $step_up_rate)
            $stepup_senario2_amount = $ap2 * $period * pow((1+$interest2/100),($period-1));
            else
            $stepup_senario2_amount = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$period)-pow((1+$step_up_rate/100),$period));
        }
        }
        $calcType = 1;
        if($stepup == "2" && $include_step_up == "yes")
        {
        $calcType = 2;
        //dd("fo");
            $ap1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
             if (isset($interest2)){
            //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
            $ap2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
            
        }
            $totalInvestment = $amount * $period * 12;
            $totalInvestmentSip = $period/2*(2*($amount*12)+($period-1)*($step_up_amount*12));
            
            $p1 = $ap1;
            
            if (isset($rate2_percent))
            $p2 = $ap2;
            $n1 = $period;
            $n2 = $period;
            $c1 = (1+$rate1_percent) * $step_up_amount * ((pow((1+$rate1_percent),12)-1)/$rate1_percent);
            $c2 = (1+$rate1_percent) * $step_up_amount * ((pow((1+$rate1_percent),12)-1)/$rate1_percent);
            $k1 = $interest1/100;
            if (isset($interest2))
                $k2 = $interest2/100;
            
            $factor1 = $p1;
            if (isset($rate2_percent))
            $factor1nxt = $p2;
            
            $factor2 = (pow((1+$k1),$n1)-1)/$k1;
            if (isset($interest2))
            $factor2nxt = (pow((1+$k2),$n2)-1)/$k2;
            $factor3 = $c1;
            if (isset($interest2))
            $factor3nxt = $c2;
            $factor4 = (pow((1+$k1),($n1+1))-(($n1+1)*$k1)-1)/(pow($k1,2));
            if (isset($interest2))
            $factor4nxt = (pow((1+$k2),($n2+1))-(($n2+1)*$k2)-1)/(pow($k2,2));
            $factor5  = pow($k1,2);
            if (isset($interest2))
            $factor5nxt  = pow($k2,2);
            $stepup_amount = $period /2 * (2*($amount*12)+($period-1)*($step_up_amount * 12));
            
            $year = 1;
            $year1 = 1;
            $sipStartAmount = $amount;
            $endValue = $ap1;
            if (isset($interest2))
            $endValue1= $ap2;
            $annualIncr = 0;
            $monthlySipAmt = $amount;
            while($year <= $period)
            {
                $val = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($year * 12))-1)/$rate1_percent);
                $endValue = $val;
                if(isset($rate2_percent)){
                $val = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($year * 12))-1)/$rate2_percent);
                $endValue1 = $val;
                }
                $year++;
                $annualIncr = $step_up_amount;
            }
            $senario1_amount = $endValue;
            if(isset($rate2_percent))
            $senario2_amount = $endValue1;
            $opbal1 = 0;
            $opbal2 = 0;
            $sipamt = 0;
            $annualIncr = $step_up_amount;
            while($year1 <= $period)
            {
            //echo($year1."/".$opbal1." ");
                $yearlySipVal1 = (1+$rate1_percent)*$sipamt*((pow((1+$rate1_percent),(1 * 12))-1)/$rate1_percent);
                if (isset($rate2_percent))
                $yearlySipVal2 = (1+$rate2_percent)*$sipamt*((pow((1+$rate2_percent),(1 * 12))-1)/$rate2_percent);
                
                $lumpsumEndVal = $opbal1 * pow((1+$rate1_percent),12);
                $endVal1 = $yearlySipVal1 + $lumpsumEndVal;
                
                if (isset($rate2_percent)){
                $lumpsumEndVal1 = $opbal2 * pow((1+$rate2_percent),12);
                $endVal2 = $yearlySipVal2 + $lumpsumEndVal1;
                }
                
                $monthlySipAmt += $annualIncr;
                $sipamt += $annualIncr;
                
                $opbal1 = $endVal1;
                if (isset($rate2_percent))
                $opbal2 = $endVal2;
                
                $year1++;
                
                
            }
             
            $totalFundValueStepUp1 =round( $endValue + $endVal1);
            if (isset($rate2_percent))
            $totalFundValueStepUp2 =round( $endValue1 + $endVal2);
            $stepup_senario1_amount = $totalFundValueStepUp1;
            if (isset($rate2_percent))
            $stepup_senario2_amount = $totalFundValueStepUp2;
            
           // dd($totalFundValueStepUp2);
        }

@endphp


   
    <header>
        @include('frontend.calculators.common.header')
    </header>

    <footer>
        <br>
        @include('frontend.calculators.common.footer')
    </footer>

    <main>
    <div style="padding: 0 0%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <div class="roundBorderHolder">
        <table>
        <tbody><tr>
            <td style="text-align: left;Width:50%;">
                <strong>Monthly SIP Amount</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
            </td>
        </tr>
        <tr>
            <td style="text-align: left;Width:50%;">
                <strong>SIP Period</strong>
            </td>
            <td style="text-align: left;Width:50%;">
                {{$period?$period:0}} Years
            </td>
        </tr>
        @if(isset($include_step_up) && $include_step_up=='yes')
            <tr>
                <td style="text-align: left;Width:50%;">
                    @if($calcType == 1)
                    <strong> Step-Up % Every Year  </strong>
                    @else
                    <strong> Step-Up Amount Every Year  </strong>
                    @endif
                </td>
                <td style="text-align: left;Width:50%;">
                    @if($calcType == 1)
                    {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
                    @else
<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($step_up_amount)}}
@endif
                </td>
            </tr>
        @endif
        @if(!isset($interest2))
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Rate of Return </strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                </td>
            </tr>
        @endif
        </tbody>
    </table>
        </div>
    </div>

    @if(isset($note) && $note!='')
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Comments</h1>
    <div class="roundBorderHolder">
    <table class="table table-bordered text-center">
        <tbody><tr>
            <td style="width: 50%;">
                <strong>{{$note}}</strong>
            </td>
        </tr>
        </tbody></table>
    </div>
@endif

    @if(isset($include_step_up) && $include_step_up=='yes')
        @if(!isset($interest2))
            <div style="padding: 0 20%;">
         @endif
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Total Investment</h1>
        <div class="roundBorderHolder">
        <table class="table table-bordered text-center">
            <tbody>
            <tr>
                <th style="width: 50%;">
                    <strong>Normal SIP</strong>
                </th>
                <th style="width: 50%;">
                    <strong>Step-Up SIP</strong>
                </th>
            </tr>
            <tr>
                <td style="width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount*$period*12)}}
                </td>
                <td style="width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_amount)}}
                </td>
            </tr>
            </tbody>
        </table>
        </div>
            </div>
        @else
        <div style="padding: 0 20%;">
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Total Investment</h1>
            <div class="roundBorderHolder">
            <table class="table table-bordered text-center">
                <tbody>
                <tr>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount*$period*12)}}</strong>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        </div>

        @endif
    @if(!isset($interest2))
        <div style="padding: 0 20%;">
     @endif
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Expected Future Value</h1>
    <div class="roundBorderHolder">
    <table class="table table-bordered text-center">
        <tbody>
        @if(isset($interest2))
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th><strong>Mode</strong></th>
                    <th>
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th>
                        <strong>Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td><strong>Normal SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                    </td>
                </tr>
                <tr>
                    <td><strong>Step-Up SIP</strong></td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                    </td>
                    <td>
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario2_amount)}} </strong>
                    </td>
                </tr>
            @else
                <tr>
                    <th style="width: 50%;">
                        <strong>Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %</strong>
                    </th>
                    <th style="width: 50%;">
                        <strong>Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %</strong>
                    </th>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                    <td style="width: 50%;">
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario2_amount)}} </strong>
                    </td>
                </tr>
            @endif
        @else
            @if(isset($include_step_up) && $include_step_up=='yes')
                <tr>
                    <th style="width: 50%;"><strong>Normal SIP</strong></th>
                    <th style="width: 50%;"><strong>Step-Up SIP</strong></th>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}} </strong>
                    </td>
                    <td style="width: 50%;">
                        <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($stepup_senario1_amount)}} </strong>
                    </td>
                </tr>

            @else
                <tr>
                    <td style="width: 50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($senario1_amount)}}
                    </td>
                </tr>
            @endif
        @endif
        </tbody></table>
    </div>
    @if(!isset($interest2))
        </div>
    @endif
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_SIP')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
        @php } @endphp
        Report Date : {{date('d/m/Y')}}
        
    {{-- @include('frontend.calculators.common.footer') --}}
@if(isset($report) && $report=='detailed' && $calcType == 1)
        <div class="page-break"></div>
        
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
            @if(isset($include_step_up) && $include_step_up=='yes')
                Normal SIP <br>
            @endif
            Year-Wise Projected Value
        </h1>
        <div class="roundBorderHolder">
    <table>
        <tbody>
        @if(isset($interest2))
            <tr>
                <th>Year</th>
                <th>Monthly Investment</th>
                <th>Annual Investment</th>
                <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
            </tr>
            @php
                $previous_amount_int1 = $amount;
                $previous_amount_int2 = $amount;
            @endphp

            @for($i=1;$i<=$period;$i++)
                @php
                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                    $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>

                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}
                    </td>
                    <td>

                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount*12):0}}

                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                </tr>

                @if($i%25==0 && $period>25 && $period>$i)
                        </tbody>
                    </table>
        </div>
                        {{-- @include('frontend.calculators.common.footer') --}}
                        <div class="page-break"></div>
                        <div class="roundBorderHolder">
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th>
                                <th>Monthly Investment</th>
                                <th>Annual Investment</th>
                                <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                            </tr>
                            @endif
            @endfor
        @else
            <tr>
                <th>Year</th>
                <th>Monthly Investment</th>
                <th>Annual Investment</th>
                <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
            </tr>
            @php
                $previous_amount_int1 = $amount;
            @endphp

            @for($i=1;$i<=$period;$i++)
                @php
                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                @endphp
                <tr>
                    <td>{{$i}}</td>
                    <td>

                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount):0}}

                    </td>
                    <td>

                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$amount?custome_money_format($amount*12):0}}
                        
                    </td>
                    <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                </tr>



                @if($i%25==0 && $period>25 && $period>$i)
                                </tbody>
                            </table>
                        </div>
                            {{-- @include('frontend.calculators.common.footer') --}}
                            <div class="page-break"></div>
                            <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                <tr>
                                    <th>Year</th>
                                    <th>Monthly Investment</th>
                                    <th>Annual Investment</th>
                                    <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                </tr>
                @endif


            @endfor
        @endif
        </tbody>
    </table>
                            </div>
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        
        {{-- @include('frontend.calculators.common.footer') --}}
        @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
            <div class="page-break"></div>
           
            <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                Step - Up SIP<br>Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder">
            <table style="background: #fff;">
                <tbody>
                @if(isset($interest2))
                    <tr>
                        <th>Year</th>
                        <th>Monthly Investment</th>
                        <th>Annual Investment</th>
                        <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                        <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                    </tr>
                    @php
                        $previous_amount_int1 = $amount;
                        $previous_amount_int2 = $amount;
                        $change_amount = $amount;
                    @endphp

                    @for($i=1;$i<=$period;$i++)
                        @php
                            //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                            if($interest1 == $step_up_rate)
                                                $previous_amount_int1 = $ap1 * $i * pow((1+$interest1/100),($i-1));
                                            else
                                                $previous_amount_int1 = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($i))-pow((1+$step_up_rate/100),($i)));
                                                
                                                if($interest2 == $step_up_rate)
                                                    $previous_amount_int2 = $ap2 * $i * pow((1+$interest2/100),($i-1));
                                                else
                                                    $previous_amount_int2 = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),($i))-pow((1+$step_up_rate/100),($i)));
                            if ($i==1){
                                $change_amount = $amount;
                            }else{
                                $change_amount = $change_amount+($change_amount*$step_up_rate/100);
                            }
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$change_amount?custome_money_format($change_amount):0}}
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$change_amount?custome_money_format($change_amount*12):0}}
                            </td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int2)}}</td>
                        </tr>


                        @if($i%25==0 && $period>25  && $period>$i)
                                    </tbody>
                                </table>
            </div>
                                {{-- @include('frontend.calculators.common.footer') --}}
                                <div class="page-break"></div>
                                <div class="roundBorderHolder">
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value <br> Scenario 1 <br> @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                        <th>Year End Value <br> Scenario 2 <br> @ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @endif


                    @endfor
                @else
                    <tr>
                        <th>Year</th>
                        <th>Monthly Investment</th>
                        <th>Annual Investment</th>
                        <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                    </tr>
                    @php
                        $previous_amount_int1 = $amount;
                    @endphp

                    @for($i=1;$i<=$period;$i++)
                        @php
                            //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                                            if($interest1 == $step_up_rate)
                                                $previous_amount_int1 = $ap1 * $i * pow((1+$interest1/100),($i-1));
                                            else
                                            $previous_amount_int1 = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($i))-pow((1+$step_up_rate/100),($i)));

                            if ($i==1){
                                $change_amount = $amount;
                            }else{
                                $change_amount = $change_amount+($change_amount*$step_up_rate/100);
                            }
                        @endphp
                        <tr>
                            <td>{{$i}}</td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$change_amount?custome_money_format($change_amount):0}}
                            </td>
                            <td>
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$change_amount?custome_money_format($change_amount*12):0}}
                            </td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($previous_amount_int1)}}</td>
                        </tr>

                        @if($i%25==0 && $period>25  && $period>$i)
                                    </tbody>
                                </table>
                                </div>
                                {{-- @include('frontend.calculators.common.footer') --}}
                                <div class="page-break"></div>
                                <div class="roundBorderHolder">
                                <table>
                                    <tbody>
                                    <tr>
                                        <th>Year</th>
                                        <th>Monthly Investment</th>
                                        <th>Annual Investment</th>
                                        <th>Year End Value @ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</th>
                                    </tr>
                                    @endif



                    @endfor
                @endif
                </tbody>
            </table>
                                </div>
            @php
            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP')->first();
            if(!empty($note_data2)){
            @endphp
            {!!$note_data2->description!!}
            @php } @endphp
            {{-- @include('frontend.calculators.common.footer') --}}
        @endif
@endif
@if(isset($report) && $report=='detailed' && $calcType == 2)
                        
                        <div class="page-break"></div>
                        
            <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                Normal SIP<br>Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder">
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th><th>Monthly Investment</th><th>Annual Investment</th><th>Year End Value Scenario 1 @ {{number_format($interest1, 2, '.', '')}} %</th>
                                @if(isset($rate2_percent))
                                <th>Year End Value Scenario 2 @ {{number_format($interest2, 2, '.', '')}} %</th>
                                @endif
                            </tr>
                            @php
                            $yr = 1;
                            $spAmt = $amount;
                            @endphp
                            @while($yr <= ($period))
                            
                                @if($yr == 26)
                                
                                //dd("yoyo");
                                </tbody></table>
            </div>
                               
                               {{-- @include('frontend.calculators.common.footer') --}}
                                    <div class="page-break"></div>
                                    <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Normal SIP<br>Year-Wise Projected Value</h1>
                                    <div class="roundBorderHolder">
                                    <table style="background: #fff;">
                                        <tbody>
                                            <tr>
                                                <th>Year</th>
                                                <th>Monthly Investment</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value Scenario 1 @ {{number_format($interest1, 2, ".", "")}} %</th>
                                                @if(isset($interest2))
                                                <th>Year End Value Scenario 2 @ {{number_format($interest2, 2, ".", "")}} %</th>
                                                @endif
                                                </tr>;
                                
                                @endif
                                @php
                                $ev1 = (1+$rate1_percent)*$spAmt*((pow((1+$rate1_percent),($yr * 12))-1)/$rate1_percent);
                                if(isset($rate2_percent)){
                                $ev2 = (1+$rate2_percent)*$spAmt*((pow((1+$rate2_percent),($yr * 12))-1)/$rate2_percent);
                                
                                echo("<tr><td>".$yr."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($spAmt)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($spAmt * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($ev1))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($ev2))."</td></tr>");
                               }
                               else
                               {
                                    echo("<tr><td>".$yr."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($spAmt)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($spAmt * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($ev1))."</td></tr>");
                                }
                                $yr++;
                                @endphp
                            
                            @endwhile
                            </tbody></table>
                                    </div>
                            {{-- @include('frontend.calculators.common.footer') --}}
                        
                        <div class="page-break"></div>
                        
            <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                Step-Up SIP<br>Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder">
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th><th>Monthly Investment</th><th>Annual Investment</th><th>Year End Value Scenario 1 @ {{number_format($interest1, 2, '.', '')}} %</th>
                                @if(isset($interest2))
                                <th>Year End Value Scenario 2 @ {{number_format($interest2, 2, '.', '')}} %</th>
                                @endif
                            </tr>
                        
                        @php
                        $year = 1;
            $year1 = 1;
            $sipStartAmount = $amount;
            $endValue = $ap1;
            if (isset($rate2_percent))
            $endValue1= $ap2;
            else
            $endValue1 = 0;
            $monthlySipAmt = $amount;
            $opbal1 = 0;
            $opbal2 = 0;
            $sipamt = 0;
            $annualIncr = $step_up_amount;
            
            if($stepup == 1)
            $actualPeriod = $period + 10;
            else if($stepup == 2)
            $actualPeriod = $period;
            @endphp
            @while($year <= $actualPeriod)
            
                @if($year == 26)
                </tbody></table>
            </div>
                               
                {{-- @include('frontend.calculators.common.footer') --}}
                <div class="page-break"></div>
                        
            <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                Step-Up SIP<br>Year-Wise Projected Value
            </h1>
            <div class="roundBorderHolder">
                        <table>
                            <tbody>
                            <tr>
                                <th>Year</th><th>Monthly Investment</th><th>Annual Investment</th><th>Year End Value Scenario 1 @ {{number_format($interest1, 2, '.', '')}} %</th>
                                @if(isset($interest2))
                                <th>Year End Value Scenario 2 @ {{number_format($interest2, 2, '.', '')}} %</th>
                                @endif
                            </tr>
                @endif
            
                @php
                $val = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($year * 12))-1)/$rate1_percent);
                $endValue = $val;
                if (isset($rate2_percent)){
                $val = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($year * 12))-1)/$rate2_percent);
                $endValue1 = $val;
                }
                $yearlySipVal1 = (1+$rate1_percent)*$sipamt*((pow((1+$rate1_percent),(1 * 12))-1)/$rate1_percent);
                if (isset($rate2_percent))
                $yearlySipVal2 = (1+$rate2_percent)*$sipamt*((pow((1+$rate2_percent),(1 * 12))-1)/$rate2_percent);
                
                $lumpsumEndVal = $opbal1 * pow((1+$rate1_percent),12);
                $endVal1 = $yearlySipVal1 + $lumpsumEndVal;
                if (isset($rate2_percent)){
                $lumpsumEndVal1 = $opbal2 * pow((1+$rate2_percent),12);
                $endVal2 = $yearlySipVal2 + $lumpsumEndVal1;
                }
                else
                $endVal2 = 0;
                if (isset($rate2_percent))
                echo("<tr><td>".$year1."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($monthlySipAmt)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($monthlySipAmt * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($endValue + $endVal1))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round( $endValue1 + $endVal2))."</td></tr>");
                else
                echo("<tr><td>".$year1."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($monthlySipAmt)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($monthlySipAmt * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(round($endValue + $endVal1))."</td></tr>");
                $monthlySipAmt += $annualIncr;
                $sipamt += $annualIncr;
                $opbal1 = $endVal1;
                if (isset($rate2_percent))
                $opbal2 = $endVal2;
                $year1++;
                $year++;
                @endphp
                
            @endwhile
            
            
            
             @php
            $totalFundValueStepUp1 =round( $endValue + $endVal1);
            $totalFundValueStepUp2 =round( $endValue1 + $endVal2);
            $stepup_senario1_amount = $totalFundValueStepUp1;
            $stepup_senario2_amount = $totalFundValueStepUp2;
                        @endphp
                        </tbody>
                        </table>
            </div>
                        {{-- @include('frontend.calculators.common.footer') --}}
                        
                        @endif
                        @if(isset($is_graph))
                    <div class="page-break"></div>
                    
                    <h3 class="graphHeading">Graphic Representation</h3>
                    <div style="text-align: center;" class="graphView">
                        <img src="{{$pie_chart2}}" style="width: 800px">
                    </div>
    
                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                        if(!empty($note_data2)){
                    @endphp
                        {!!$note_data2->description!!}
                    @php } @endphp
                    
                    Report Date : {{date('d/m/Y')}}
                
                @endif
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>
