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
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
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
            margin-top: 160px
        }

        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 94px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
        }

        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
        }
    </style>
</head>
<body>

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

//Step UP (Q7*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
if(isset($include_step_up) && $include_step_up=='yes'){
    //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
    $ap1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),(12))-1)/$rate1_percent);
    $stepup_amount = $amount*12 * (pow((1+$step_up_rate/100),($period))-1) / ((1+$step_up_rate/100)-1);
    //One = (AV34/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
    //$stepup_senario1_amount = (1+$rate1_percent)*$stepup_amount*((pow((1+$rate1_percent),($number_of_months))-1)/$rate1_percent);
    $stepup_senario1_amount = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),$period)-pow((1+$step_up_rate/100),$period));

    if (isset($interest2)){
        //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
        $ap2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),(12))-1)/$rate2_percent);
        $stepup_senario2_amount = ($ap2/($interest2/100-$step_up_rate/100))*(pow((1+$interest2/100),$period)-pow((1+$step_up_rate/100),$period));
    }
}

@endphp

<main style="width: 760px; margin-left: 20px;">
    <SALESPRESENTER_BEFORE/>
    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">SIP @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h1>
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
                    <strong> Step-Up % Every Year  </strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
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
    @if(isset($include_step_up) && $include_step_up=='yes')
        @if(!isset($interest2))
            <div style="padding: 0 20%;">
         @endif
        <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Total Investment</h1>
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
        @else
        <div style="padding: 0 20%;">
            <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Total Investment</h1>
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

        @endif
    @if(!isset($interest2))
        <div style="padding: 0 20%;">
     @endif
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Expected Future Value</h1>
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
    @if(!isset($interest2))
        </div>
    @endif
        @php
        $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_SIP')->first();
        if(!empty($note_data1)){
        @endphp
        {!!$note_data1->description!!}
        @php } @endphp
        
    @include('frontend.calculators.common.footer')
@if(isset($report) && $report=='detailed')
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
            @if(isset($include_step_up) && $include_step_up=='yes')
                Normal SIP <br>
            @endif
            Year-Wise Projected Value
        </h1>
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
                        @include('frontend.calculators.common.footer')
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
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
                            @include('frontend.calculators.common.footer')
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                        <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
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
        @php
        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP')->first();
        if(!empty($note_data2)){
        @endphp
        {!!$note_data2->description!!}
        @php } @endphp
        @include('frontend.calculators.common.footer')
        @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
            <div class="page-break"></div>
            <header>
                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                        <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                    </tr>
                    </tbody>
                </table>
            </header>
            <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                Step - Up SIP<br>Year-Wise Projected Value
            </h1>
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
                            $previous_amount_int1 = ($ap1/($interest1/100-$step_up_rate/100))*(pow((1+$interest1/100),($i))-pow((1+$step_up_rate/100),($i)));
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
                                @include('frontend.calculators.common.footer')
                                <div class="page-break"></div>
                                <header>
                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                        <tbody>
                                        <tr>
                                            <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                            <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </header>
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
                                @include('frontend.calculators.common.footer')
                                <div class="page-break"></div>
                                <header>
                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                        <tbody>
                                        <tr>
                                            <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                                            <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </header>
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
            @php
            $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP')->first();
            if(!empty($note_data2)){
            @endphp
            {!!$note_data2->description!!}
            @php } @endphp
            @include('frontend.calculators.common.footer')
        @endif
@endif
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>
