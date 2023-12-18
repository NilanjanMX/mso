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
        $total_number_of_months = $sip_period*12;
        $totalinvestment = $amount*$sip_period*12;
        //(1+Q11%)^(1/12)-1
        $rate1_percent  = pow((1+$interest1/100),(1/12))-1;
        //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
        $senario1_fund_amount = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($total_number_of_months))-1)/$rate1_percent);
        //AV33*(1+Q11%)^Q9
        $senario1_amount = $senario1_fund_amount*pow((1+$interest1/100),$deferment_period);
        $senario2_amount = 0;
         if (isset($interest2)){
            $rate2_percent  = pow((1+$interest2/100),(1/12))-1;
            //(1+AV31)*Q7*(((1+AV31)^(AV30)-1)/AV31)
            $senario2_fund_amount = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($total_number_of_months))-1)/$rate2_percent);
            //AV33*(1+Q11%)^Q9
            $senario2_amount = $senario2_fund_amount*pow((1+$interest2/100),$deferment_period);
            }
    @endphp
    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2">
                    <h5 class="mb-3">Limited Period SIP @if(isset($clientname)) Proposal For {{$clientname?$clientname:''}} @else Planning @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Monthly SIP Amount </strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($amount)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>SIP Period </strong>
                            </td>
                            <td>
                                {{$sip_period?$sip_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Deferment Period </strong>
                            </td>
                            <td>
                                {{$deferment_period?$deferment_period:0}} Years
                            </td>
                        </tr>

                        </tbody></table>
                    <h5>Total Investment</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                ₹ {{custome_money_format($totalinvestment)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;">Expected Future Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($interest2))
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
                        </tbody></table>
                    @if(isset($report) && $report=='detailed')
                        <h5>Year-Wise Projectd Value</h5>
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
                                @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                    @php
                                        //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                        //
                                        if ($sip_period>=$i){
                                          $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                        }else{
                                         //(BE69*(1+BC70)^12)
                                          $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                        }
                                        if ($sip_period>=$i){
                                          $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                        }else{
                                         //(BE69*(1+BC70)^12)
                                          $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            @if($i<=10)
                                                ₹ {{$amount?custome_money_format($amount):0}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if($i<=10)
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
                                @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                                    @php
                                        //(AX69>=AW69,(1+BC69)*BB69*(((1+BC69)^(AZ69*12)-1)/BC69),(BE68*(1+BC69)^12))
                                        //
                                        if ($sip_period>=$i){
                                          $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                        }else{
                                         //(BE69*(1+BC70)^12)
                                          $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                        }

                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            @if($i<=10)
                                                ₹ {{$amount?custome_money_format($amount):0}}
                                            @else
                                                --
                                            @endif
                                        </td>
                                        <td>
                                            @if($i<=10)
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
                        <p>*The above chart is approximate and for illustration purpose only</p>
                    @endif


                    <a href="#" class="btn btn-primary btn-round">Save</a>
                    <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriodOutputPdfDownload')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
