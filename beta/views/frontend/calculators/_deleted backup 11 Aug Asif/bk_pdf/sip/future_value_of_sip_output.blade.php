@extends('layouts.frontend')
@section('js_after')

@endsection
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>
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
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h5 class="mb-3">SIP @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Monthly SIP Amount</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>SIP Period  </strong>
                            </td>
                            <td>
                                {{$period?$period:0}} Years
                            </td>
                        </tr>
                        @if(isset($include_step_up) && $include_step_up=='yes')
                        <tr>
                            <td>
                                <strong> Step-Up % Every Year  </strong>
                            </td>
                            <td>
                                {{$step_up_rate?number_format($step_up_rate, 2, '.', ''):0}} %
                            </td>
                        </tr>
                        @endif
                        </tbody></table>
                    @if(isset($include_step_up) && $include_step_up=='yes')
                        <h5>Total Investment</h5>
                        <table class="table table-bordered text-center">
                            <tbody><tr>
                                <td>
                                    <strong>Normal SIP</strong>
                                </td>
                                <td>
                                    <strong>Step-Up SIP</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ₹ {{custome_money_format($amount*$period*12)}}
                                </td>
                                <td>
                                    ₹ {{custome_money_format($stepup_amount)}}
                                </td>
                            </tr>
                            </tbody></table>
                    @else
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Total Investment</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ₹ {{custome_money_format($amount*$period*12)}}
                                </td>
                            </tr>
                            </tbody></table>
                    @endif

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;">Expected Future Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
                            @if(isset($include_step_up) && $include_step_up=='yes')
                                <tr>
                                    <td><strong>Mode</strong></td>
                                    <td>
                                        Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                    <td>
                                        Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Normal SIP</strong></td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Step-Up SIP</strong></td>
                                    <td>
                                        <strong>₹ {{custome_money_format($stepup_senario1_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($stepup_senario2_amount)}} </strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        Scenario 1 @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                    <td>
                                        Scenario 2 @ {{$interest1?number_format($interest2, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                    </td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario2_amount)}} </strong>
                                    </td>
                                </tr>
                            @endif
                        @else
                            @if(isset($include_step_up) && $include_step_up=='yes')
                                <tr>
                                    <td><strong>Mode</strong></td>
                                    <td>
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Normal SIP</strong></td>
                                    <td>
                                        <strong>₹ {{custome_money_format($senario1_amount)}} </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Step-Up SIP</strong></td>
                                    <td>
                                        <strong>₹ {{custome_money_format($stepup_senario1_amount)}} </strong>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td>
                                        @ {{$interest1?number_format($interest1, 2, '.', ''):0}}%
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($senario1_amount)}}
                                    </td>
                                </tr>
                            @endif
                        @endif
                        </tbody></table>

                    @if(isset($report) && $report=='detailed')
                    <h5>
                        @if(isset($include_step_up) && $include_step_up=='yes')
                            Normal SIP <br>
                        @endif
                        Year-Wise Projectd Value</h5>
                    <table class="table table-bordered text-center" style="background: #fff;">
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
                                        @if($i==1)
                                            ₹ {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($i==1)
                                            ₹ {{$amount?custome_money_format($amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                    <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                </tr>
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
                                        @if($i==1)
                                            ₹ {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>
                                        @if($i==1)
                                            ₹ {{$amount?custome_money_format($amount*12):0}}
                                        @else
                                            --
                                        @endif
                                    </td>
                                    <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                </tr>
                            @endfor
                        @endif
                        </tbody>
                    </table>
                        @if(isset($report) && $report=='detailed' && isset($include_step_up) && $include_step_up=='yes')
                            <h5>Step - Up SIP<br>Year-Wise Projectd Value</h5>
                            <table class="table table-bordered text-center" style="background: #fff;">
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
                                                ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                            </td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                            <td>₹ {{custome_money_format($previous_amount_int2)}}</td>
                                        </tr>
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
                                                ₹ {{$change_amount?custome_money_format($change_amount):0}}
                                            </td>
                                            <td>
                                                ₹ {{$change_amount?custome_money_format($change_amount*12):0}}
                                            </td>
                                            <td>₹ {{custome_money_format($previous_amount_int1)}}</td>
                                        </tr>
                                    @endfor
                                @endif
                                </tbody>
                            </table>
                        @endif
                    <p>*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    <a href="#" class="btn btn-primary btn-round">Save</a>
                    <a href="{{route('frontend.futureValueOfSipOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
