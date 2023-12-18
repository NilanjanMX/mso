@extends('layouts.frontend')

    @php
        
        //rate1 = (1+Q10%)^(1/12)-1 (Q10 = senario 1)
        //rate2 = (1+Q11%)^(1/12)-1 (Q10 = senario 2)
        $number_of_months = $period * 12;
        $rate1_percent = pow(1 + $interest1 / 100, 1 / 12) - 1;
        
        //senario1_amount = (1+AV32)*Q7*(((1+AV32)^(AV31)-1)/AV32)
        $senario1_amount = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $number_of_months) - 1) / $rate1_percent);
        if (isset($interest2)) {
            $rate2_percent = pow(1 + $interest2 / 100, 1 / 12) - 1;
            $senario2_amount = (1 + $rate2_percent) * $amount * ((pow(1 + $rate2_percent, $number_of_months) - 1) / $rate2_percent);
        }
        
        if (isset($steuup)) {
            dd('l');
        }
        
        //Step UP (Q7*12)*(((1+Q13%)^(Q8)-1)/((1+Q13%)-1))
        if (isset($include_step_up) && $include_step_up == 'yes' && $stepup == 1) {
            //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
            $ap1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, 12) - 1) / $rate1_percent);
            $stepup_amount = ($amount * 12 * (pow(1 + $step_up_rate / 100, $period) - 1)) / (1 + $step_up_rate / 100 - 1);
            //One = (AV34/(Q10%-Q13%))*((1+Q10%)^(Q8)-(1+Q13%)^(Q8))
            //$stepup_senario1_amount = (1+$rate1_percent)*$stepup_amount*((pow((1+$rate1_percent),($number_of_months))-1)/$rate1_percent);
            if ($interest1 == $step_up_rate) {
                $stepup_senario1_amount = $ap1 * $period * pow(1 + $interest1 / 100, $period - 1);
            } else {
                $stepup_senario1_amount = ($ap1 / ($interest1 / 100 - $step_up_rate / 100)) * (pow(1 + $interest1 / 100, $period) - pow(1 + $step_up_rate / 100, $period));
            }
        
            if (isset($interest2)) {
                //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
                $ap2 = (1 + $rate2_percent) * $amount * ((pow(1 + $rate2_percent, 12) - 1) / $rate2_percent);
        
                if ($interest2 == $step_up_rate) {
                    $stepup_senario2_amount = $ap2 * $period * pow(1 + $interest1 / 100, $period - 1);
                } else {
                    $stepup_senario2_amount = ($ap2 / ($interest2 / 100 - $step_up_rate / 100)) * (pow(1 + $interest2 / 100, $period) - pow(1 + $step_up_rate / 100, $period));
                }
            }
        }
        $calcType = 1;
        if ($stepup == 2 && $include_step_up == 'yes') {
            $calcType = 2;
            //dd("fo");
            $ap1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, 12) - 1) / $rate1_percent);
            if (isset($interest2)) {
                //(1+AV32)*Q7*(((1+AV32)^(12)-1)/AV32)
                $ap2 = (1 + $rate2_percent) * $amount * ((pow(1 + $rate2_percent, 12) - 1) / $rate2_percent);
            }
        
            $totalInvestment = $amount * $period * 12;
            $totalInvestmentSip = ($period / 2) * (2 * ($amount * 12) + ($period - 1) * ($step_up_amount * 12));
        
            $p1 = $ap1;
            if (isset($rate2_percent)) {
                $p2 = $ap2;
            }
            $n1 = $period;
            $n2 = $period;
            $c1 = (1 + $rate1_percent) * $step_up_amount * ((pow(1 + $rate1_percent, 12) - 1) / $rate1_percent);
            $c2 = (1 + $rate1_percent) * $step_up_amount * ((pow(1 + $rate1_percent, 12) - 1) / $rate1_percent);
            $k1 = $interest1 / 100;
            if (isset($interest2)) {
                $k2 = $interest2 / 100;
            }
        
            $factor1 = $p1;
            if (isset($rate2_percent)) {
                $factor1nxt = $p2;
            }
        
            $factor2 = (pow(1 + $k1, $n1) - 1) / $k1;
            if (isset($interest2)) {
                $factor2nxt = (pow(1 + $k2, $n2) - 1) / $k2;
            }
            $factor3 = $c1;
            if (isset($interest2)) {
                $factor3nxt = $c2;
            }
            $factor4 = (pow(1 + $k1, $n1 + 1) - ($n1 + 1) * $k1 - 1) / pow($k1, 2);
            if (isset($interest2)) {
                $factor4nxt = (pow(1 + $k2, $n2 + 1) - ($n2 + 1) * $k2 - 1) / pow($k2, 2);
            }
            $factor5 = pow($k1, 2);
            if (isset($interest2)) {
                $factor5nxt = pow($k2, 2);
            }
            $stepup_amount = ($period / 2) * (2 * ($amount * 12) + ($period - 1) * ($step_up_amount * 12));
        
            $year = 1;
            $year1 = 1;
            $sipStartAmount = $amount;
            $endValue = $ap1;
            if (isset($interest2)) {
                $endValue1 = $ap2;
            }
            $annualIncr = 0;
            $monthlySipAmt = $amount;
            while ($year <= $period) {
                $val = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $year * 12) - 1) / $rate1_percent);
                $endValue = $val;
                if (isset($rate2_percent)) {
                    $val = (1 + $rate2_percent) * $amount * ((pow(1 + $rate2_percent, $year * 12) - 1) / $rate2_percent);
                    $endValue1 = $val;
                }
                $year++;
                $annualIncr = $step_up_amount;
            }
            $senario1_amount = $endValue;
            if (isset($rate2_percent)) {
                $senario2_amount = $endValue1;
            }
            $opbal1 = 0;
            $opbal2 = 0;
            $sipamt = 0;
            $annualIncr = $step_up_amount;
            while ($year1 <= $period) {
                //echo($year1."/".$opbal1." ");
                $yearlySipVal1 = (1 + $rate1_percent) * $sipamt * ((pow(1 + $rate1_percent, 1 * 12) - 1) / $rate1_percent);
                if (isset($rate2_percent)) {
                    $yearlySipVal2 = (1 + $rate2_percent) * $sipamt * ((pow(1 + $rate2_percent, 1 * 12) - 1) / $rate2_percent);
                }
        
                $lumpsumEndVal = $opbal1 * pow(1 + $rate1_percent, 12);
                $endVal1 = $yearlySipVal1 + $lumpsumEndVal;
        
                if (isset($rate2_percent)) {
                    $lumpsumEndVal1 = $opbal2 * pow(1 + $rate2_percent, 12);
                    $endVal2 = $yearlySipVal2 + $lumpsumEndVal1;
                }
        
                $monthlySipAmt += $annualIncr;
                $sipamt += $annualIncr;
        
                $opbal1 = $endVal1;
                if (isset($rate2_percent)) {
                    $opbal2 = $endVal2;
                }
        
                $year1++;
            }
        
            $totalFundValueStepUp1 = round($endValue + $endVal1);
            if (isset($rate2_percent)) {
                $totalFundValueStepUp2 = round($endValue1 + $endVal2);
            }
            $stepup_senario1_amount = $totalFundValueStepUp1;
            if (isset($rate2_percent)) {
                $stepup_senario2_amount = $totalFundValueStepUp2;
            }
        
            // dd($totalFundValueStepUp2);
        }
        
    @endphp

@section('js_after')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
    </script>
    <script type="text/javascript">
        @php
            $previous_amount_int1 = $amount;
            $previous_amount_int2 = $amount;
            $change_amount = $amount;
            $rate1_percent = pow(1 + $interest1 / 100, 1 / 12) - 1;
            $rate2_percent1 = 0;
            if(isset($interest2)){
                $rate2_percent1 = pow(1 + $interest2 / 100, 1 / 12) - 1;
            }
            $cumulative_investment = 0;
            
        @endphp
        @if(isset($is_graph) && $is_graph)
            

            google.charts.load('current', {
                'packages': ['corechart']
            });
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {                    
                
                @if(isset($include_step_up) && $include_step_up == 'yes' && isset($step_up_rate))
                    document.getElementById("curve_chart2").style.display = "block";
                    @if(isset($interest2))
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %',
                                'Exp Future Value@ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                                            
                                    $previous_amount_int2 = (1 + $rate2_percent1) * $amount * ((pow(1 + $rate2_percent1, $i * 12) - 1) / $rate2_percent1);
                                    
        
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($previous_amount_int1);
                                    $vaiue2 = round($previous_amount_int2);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>,<?php echo $vaiue2;?>],
                            @endfor
                        ]);
                    @else
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                    
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($previous_amount_int1);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>],
                            @endfor
                        ]);
                    @endif
    
                    var options = {
                        title: '',
                        curveType: 'function',
                        legend: { position: 'bottom',textStyle: {fontSize: 10} },
                        vAxis: {title: "Amount (in Rs)",textStyle: {fontSize: 10}},
                        hAxis: {title: "Year",textStyle: {fontSize: 10}},
                    };
    
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    
                    google.visualization.events.addListener(chart, 'ready', function() {
                        var imgUri = chart.getImageURI();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('frontend.calculators_line_chart') }}",
                            data: {
                                "img": imgUri,
                                "type":1
                            },
                            success: function(data) {
    
                            }
                        });
                    });
    
                    chart.draw(data, options);
                
                    @php
                        $previous_amount_int1 = $amount;
                        $previous_amount_int2 = $amount;
                        $change_amount = $amount;
                        $rate1_percent = pow(1 + $interest1 / 100, 1 / 12) - 1;
                        $rate2_percent1 = 0;
                        if(isset($interest2)){
                            $rate2_percent1 = pow(1 + $interest2 / 100, 1 / 12) - 1;
                        }
                        $cumulative_investment = 0;
                    @endphp
                    
                    @if(isset($interest2))
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment (Step-Up SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %',
                                'Exp Future Value@ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %'
                            ],
    
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    if ($interest1 == $step_up_rate) {
                                        $previous_amount_int1 = $ap1 * $i * pow(1 + $interest1 / 100, $i - 1);
                                    } else {
                                        $previous_amount_int1 = ($ap1 / ($interest1 / 100 - $step_up_rate / 100)) * (pow(1 + $interest1 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                    }
                                    
                                    if ($interest2 == $step_up_rate) {
                                        $previous_amount_int2 = $ap2 * $i * pow(1 + $interest2 / 100, $i - 1);
                                    } else {
                                        $previous_amount_int2 = ($ap2 / ($interest2 / 100 - $step_up_rate / 100)) * (pow(1 + $interest2 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                    }
                                    if ($i == 1) {
                                        $change_amount = $amount;
                                    } else {
                                        $change_amount = $change_amount + ($change_amount * $step_up_rate) / 100;
                                    }
                                    $cumulative_investment = $cumulative_investment + $change_amount * 12;
                                    $vaiue1 = round($previous_amount_int1);
                                    $vaiue2 = round($previous_amount_int2);
    
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>,<?php echo $vaiue2;?>],
                            @endfor
                        ]);
                    @else
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment (Step-Up SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %'
                            ],
    
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    if ($interest1 == $step_up_rate) {
                                        $previous_amount_int1 = $ap1 * $i * pow(1 + $interest1 / 100, $i - 1);
                                    } else {
                                        $previous_amount_int1 = ($ap1 / ($interest1 / 100 - $step_up_rate / 100)) * (pow(1 + $interest1 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                    }
                                    
                                    if ($i == 1) {
                                        $change_amount = $amount;
                                    } else {
                                        $change_amount = $change_amount + ($change_amount * $step_up_rate) / 100;
                                    }
                                    $cumulative_investment = $cumulative_investment + $change_amount * 12;
                                    $vaiue1 = round($previous_amount_int1);
    
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>],
                            @endfor
                        ]);
                    @endif

                    var options = {
                        title: '',
                        curveType: 'function',
                        legend: {
                            position: 'bottom',textStyle: {fontSize: 10}
                        },
                        vAxis: {title: "Amount (in Rs)",textStyle: {fontSize: 10}},
                        hAxis: {title: "Year",textStyle: {fontSize: 10}},
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));

                    google.visualization.events.addListener(chart, 'ready', function() {
                        var imgUri = chart.getImageURI();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('frontend.calculators_line_chart') }}",
                            data: {
                                "img": imgUri,
                                "type":2
                            },
                            success: function(data) {

                            }
                        });
                    });

                    chart.draw(data, options);

                @elseif (isset($report) && $report == 'detailed' && $calcType == 2)
                    document.getElementById("curve_chart2").style.display = "block";
                    @if(isset($interest2))
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %',
                                'Exp Future Value@ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $ev1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                                    
                                    $ev2 = (1 + $rate2_percent1) * $amount * ((pow(1 + $rate2_percent1, $i * 12) - 1) / $rate2_percent1);
                                    
        
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($ev1);
                                    $vaiue2 = round($ev2);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>,<?php echo $vaiue2;?>],
                            @endfor
                        ]);
                    @else
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $ev1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                    
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($ev1);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>],
                            @endfor
                        ]);
                    @endif
    
                    var options = {
                        title: '',
                        curveType: 'function',
                        legend: { position: 'bottom',textStyle: {fontSize: 10} },
                        vAxis: {title: "Amount (in Rs)",textStyle: {fontSize: 10}},
                        hAxis: {title: "Year",textStyle: {fontSize: 10}},
                    };
    
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    
                    google.visualization.events.addListener(chart, 'ready', function() {
                        var imgUri = chart.getImageURI();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('frontend.calculators_line_chart') }}",
                            data: {
                                "img": imgUri,
                                "type":1
                            },
                            success: function(data) {
    
                            }
                        });
                    });
    
                    chart.draw(data, options);
                
                     @php
                        $year = 1;
                        $year1 = 1;
                        $sipStartAmount = $amount;
                        $endValue = $ap1;
                        if (isset($interest2)) {
                            $endValue1 = $ap2;
                        } else {
                            $endValue1 = 0;
                        }
                        $monthlySipAmt = $amount;
                        $opbal1 = 0;
                        $opbal2 = 0;
                        $sipamt = 0;
                        $annualIncr = $step_up_amount;
                        
                        if ($stepup == 1) {
                            $actualPeriod = $period + 10;
                        } elseif ($stepup == 2) {
                            $actualPeriod = $period;
                        }
                        $cumulative_investment = 0;
                    @endphp
                    
                    @if(isset($interest2))
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment (Step-Up SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %',
                                'Exp Future Value@ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %'
                            ],
    
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    
                                    $val = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                    $endValue = $val;

                                    $val = (1 + $rate2_percent1) * $amount * ((pow(1 + $rate2_percent1, $i * 12) - 1) / $rate2_percent1);
                                    $endValue1 = $val;

                                    $yearlySipVal1 = (1 + $rate1_percent) * $sipamt * ((pow(1 + $rate1_percent, 1 * 12) - 1) / $rate1_percent);
                                    $yearlySipVal2 = (1 + $rate2_percent1) * $sipamt * ((pow(1 + $rate2_percent1, 1 * 12) - 1) / $rate2_percent1);

                                    $lumpsumEndVal = $opbal1 * pow(1 + $rate1_percent, 12);
                                    $endVal1 = $yearlySipVal1 + $lumpsumEndVal;

                                    $lumpsumEndVal1 = $opbal2 * pow(1 + $rate2_percent1, 12);
                                    $endVal2 = $yearlySipVal2 + $lumpsumEndVal1;

                                    $cumulative_investment = $cumulative_investment + $monthlySipAmt * 12;
                                    $vaiue1 = round($endValue + $endVal1);
                                    $vaiue2 = round($endValue1 + $endVal2);
    
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>,<?php echo $vaiue2;?>],
                            @endfor
                        ]);
                    @else
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment (Step-Up SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %'
                            ],
    
                            @for ($i = 1; $i <= $period; $i++)
                                @php 
                                    $val = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                    $endValue = $val;
                                    
                                    $yearlySipVal1 = (1 + $rate1_percent) * $sipamt * ((pow(1 + $rate1_percent, 1 * 12) - 1) / $rate1_percent);
                                    
                                    $lumpsumEndVal = $opbal1 * pow(1 + $rate1_percent, 12);
                                    $endVal1 = $yearlySipVal1 + $lumpsumEndVal;
                                    
                                    $cumulative_investment = $cumulative_investment + $monthlySipAmt * 12;
                                    $vaiue1 = round($endValue + $endVal1);
    
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>],
                                    
                                @php 
                                    $monthlySipAmt += $annualIncr;
                                    $sipamt += $annualIncr;
                                    $opbal1 = $endVal1;
                                @endphp
                            @endfor
                        ]);
                    @endif
                    
                    console.log(data);

                    var options = {
                        title: '',
                        curveType: 'function',
                        legend: {
                            position: 'bottom',textStyle: {fontSize: 10}
                        },
                        vAxis: {title: "Amount (in Rs)",textStyle: {fontSize: 10}},
                        hAxis: {title: "Year",textStyle: {fontSize: 10}},
                    };

                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart2'));

                    google.visualization.events.addListener(chart, 'ready', function() {
                        var imgUri = chart.getImageURI();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('frontend.calculators_line_chart') }}",
                            data: {
                                "img": imgUri,
                                "type":2
                            },
                            success: function(data) {

                            }
                        });
                    });

                    chart.draw(data, options);
                @else
                    @if(isset($interest2))
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %',
                                'Exp Future Value@ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                                            
                                    $previous_amount_int2 = (1 + $rate2_percent1) * $amount * ((pow(1 + $rate2_percent1, $i * 12) - 1) / $rate2_percent1);
                                    
        
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($previous_amount_int1);
                                    $vaiue2 = round($previous_amount_int2);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>,<?php echo $vaiue2;?>],
                            @endfor
                        ]);
                    @else
                        var data = google.visualization.arrayToDataTable([
                            ['Year',
                                "Cumulative Investment(Normal SIP)",
                                'Exp Future Value@ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %'
                            ],
        
                            @for ($i = 1; $i <= $period; $i++)
                                @php
                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                    
                                    $cumulative_investment = $cumulative_investment + $amount * 12;
                                    
                                    $vaiue1 = round($previous_amount_int1);
                                @endphp
                                    ['<?php echo $i; ?>', <?php echo round($cumulative_investment);?>,<?php echo $vaiue1; ?>],
                            @endfor
                        ]);
                    @endif
    
                    var options = {
                        title: '',
                        curveType: 'function',
                        legend: {
                            position: 'bottom',textStyle: {fontSize: 10}
                        },
                        vAxis: {title: "Amount (in Rs)",textStyle: {fontSize: 10}},
                        hAxis: {title: "Year",textStyle: {fontSize: 10}},
                    };
    
                    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    
                    google.visualization.events.addListener(chart, 'ready', function() {
                        var imgUri = chart.getImageURI();
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        });
                        $.ajax({
                            type: 'POST',
                            url: "{{ route('frontend.calculators_line_chart') }}",
                            data: {
                                "img": imgUri,
                                "type":1
                            },
                            success: function(data) {
    
                            }
                        });
                    });
    
                    chart.draw(data, options);
                @endif
            }
        @endif
    </script>

    <script>
        jQuery(document).ready(function() {
            jQuery('#save_cal_btn').click(function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var title = jQuery('#save_title').val();
                if (title.trim() == '') {
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').addClass('alert-danger');
                    jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
                } else {
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').html('');
                    jQuery('#save_title').val('');
                    jQuery.ajax({
                        url: "{{ route('frontend.futureValueOfSipOutputSave') }}",
                        method: 'get',
                        data: {
                            title: title
                        },
                        success: function(result) {
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').addClass('alert-success');
                            jQuery('#save_cal_msg').html('Data Successfully Saved');
                            setTimeout(function() {
                                $('#saveOutput').modal('toggle');
                                jQuery('#save_cal_msg').removeClass(
                                    'alert-success alert-danger');
                                jQuery('#save_cal_msg').html('');
                            }, 500);
                            jQuery('.save_only').hide();
                            jQuery('.view_save_only').show();
                        }
                    });
                }

            });
        });
    </script>
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
    

    <div class="banner styleApril">
        <div class="container">
            @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Future Value Of Sip</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if ($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i
                                class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{ route('frontend.futureValueOfSipIndex') }}?action=back"
                            class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    @if ($calculator_permissions['is_save'])
                        @if ($edit_id)
                            <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only"
                                data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only"
                                data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)" class="btn btn-primary btn-round"
                            onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if ($permission['is_download'])
                        @if ($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();"
                                class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{ route('frontend.futureValueOfSipOutputPdfDownload') }}" target="_blank"
                                id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid"
                            onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{ route('frontend.view_saved_files') }}"
                        class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <div class="outputTableHolder">
                        <h1 class="midheading">SIP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{ $clientname ? $clientname : '' }} @else  @endif</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td style="width: 50%;">
                                            <strong>Monthly SIP Amount</strong>
                                        </td>
                                        <td style="width: 50%;">
                                            ₹ {{ custome_money_format($amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>SIP Period </strong>
                                        </td>
                                        <td>
                                            {{ $period ? $period : 0 }} Years
                                        </td>
                                    </tr>
                                    @if (isset($include_step_up) && $include_step_up == 'yes')
                                        <tr>
                                            <td>
                                                @if ($stepup == 1)
                                                    <strong> Step-Up % Every Year </strong>
                                                @else
                                                    <strong> Step-Up Amount Every Year </strong>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($stepup == 1)
                                                    {{ $step_up_rate ? number_format($step_up_rate, 2, '.', '') : 0 }} %
                                                @elseif($stepup == 2)
                                                    ₹ {{ custome_money_format($step_up_amount) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if (!isset($interest2))
                                        <tr>
                                            <td>
                                                <strong>Assumed Rate of Return </strong>
                                            </td>
                                            <td>
                                                {{ $interest1 ? number_format($interest1, 2, '.', '') : 0 }} %
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if (isset($include_step_up) && $include_step_up == 'yes')
                            <h1 class="midheading">Total Investment</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%;">
                                                <strong>Normal SIP</strong>
                                            </td>
                                            <td style="width: 50%;">
                                                <strong>Step-Up SIP</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                ₹ {{ custome_money_format($amount * $period * 12) }}
                                            </td>
                                            <td>
                                                ₹ {{ custome_money_format($stepup_amount) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <h1 class="midheading">Total Investment</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>₹ {{ custome_money_format($amount * $period * 12) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <h1 class="midheading">Expected Future Value</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                    @if (isset($interest2))
                                        @if (isset($include_step_up) && $include_step_up == 'yes')
                                            <tr>
                                                <td><strong>Mode</strong></td>
                                                <td>
                                                    Scenario 1 @ {{ $interest1 ? number_format($interest1, 2, '.', '') : 0 }} %
                                                </td>
                                                <td>
                                                    Scenario 2 @ {{ $interest1 ? number_format($interest2, 2, '.', '') : 0 }} %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>

                                                <td>
                                                    <strong>₹ {{ custome_money_format($senario1_amount) }} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($senario2_amount) }} </strong>
                                                </td>

                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($stepup_senario1_amount) }} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($stepup_senario2_amount) }} </strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    Scenario 1 @ {{ $interest1 ? number_format($interest1, 2, '.', '') : 0 }} %
                                                </td>
                                                <td>
                                                    Scenario 2 @ {{ $interest1 ? number_format($interest2, 2, '.', '') : 0 }} %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($senario1_amount) }} </strong>
                                                </td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($senario2_amount) }} </strong>
                                                </td>
                                            </tr>
                                        @endif
                                    @else
                                        @if (isset($include_step_up) && $include_step_up == 'yes')
                                            <tr>
                                                <td style="width: 50%;"><strong>Mode</strong></td>
                                                <td style="width: 50%;">
                                                    @ {{ $interest1 ? number_format($interest1, 2, '.', '') : 0 }} %
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Normal SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($senario1_amount) }} </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Step-Up SIP</strong></td>
                                                <td>
                                                    <strong>₹ {{ custome_money_format($stepup_senario1_amount) }} </strong>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td>
                                                    ₹ {{ custome_money_format($senario1_amount) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        
                        
                        
                        {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            
                        <div class="description-text">
                            @php
                            $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_SIP')->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                            @php } @endphp
                            Report Date : {{date('d/m/Y')}}
                        </div>
                        
                        @if (isset($report) && $report == 'detailed' && $calcType == 1)
                            <h1 class="midheading">
                                @if (isset($include_step_up) && $include_step_up == 'yes')
                                    Normal SIP <br>
                                @endif
                                Year-Wise Projected Value
                            </h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                        @if (isset($interest2))
                                            <tr>
                                                <th style="vertical-align: middle;">Year</th>
                                                <th style="vertical-align: middle;">Monthly Investment</th>
                                                <th style="vertical-align: middle;">Annual Investment</th>
                                                <th>Year End Value <br> Scenario 1 <br> @
                                                    {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %</th>
                                                <th>Year End Value <br> Scenario 2 <br> @
                                                    {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %</th>
                                            </tr>
                                            @php
                                                $previous_amount_int1 = $amount;
                                                $previous_amount_int2 = $amount;
                                            @endphp

                                            @for ($i = 1; $i <= $period; $i++)
                                                @php
                                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                                    
                                                    $previous_amount_int2 = (1 + $rate2_percent1) * $amount * ((pow(1 + $rate2_percent1, $i * 12) - 1) / $rate2_percent1);
                                                @endphp
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        {{-- @if ($i == 1)
                                            ₹ {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif --}}
                                                        ₹ {{ $amount ? custome_money_format($amount) : 0 }}
                                                    </td>
                                                    <td>
                                                        {{-- @if ($i == 1)
                                            ₹ {{$amount?    ($amount*12):0}}
                                        @else
                                            --
                                        @endif --}}
                                                        ₹ {{ $amount ? custome_money_format($amount * 12) : 0 }}
                                                    </td>
                                                    <td>₹ {{ custome_money_format($previous_amount_int1) }}</td>

                                                    <td>₹ {{ custome_money_format($previous_amount_int2) }}</td>
                                                </tr>
                                            @endfor
                                        @else
                                            <tr>
                                                <th>Year</th>
                                                <th>Monthly Investment</th>
                                                <th>Annual Investment</th>
                                                <th>Year End Value @
                                                    {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %</th>
                                            </tr>
                                            @php
                                                $previous_amount_int1 = $amount;
                                            @endphp

                                            @for ($i = 1; $i <= $period; $i++)
                                                @php
                                                    $previous_amount_int1 = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $i * 12) - 1) / $rate1_percent);
                                                @endphp
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        {{-- @if ($i == 1)
                                            ₹ {{$amount?custome_money_format($amount):0}}
                                        @else
                                            --
                                        @endif --}}
                                                        ₹ {{ $amount ? custome_money_format($amount) : 0 }}
                                                    </td>
                                                    <td>
                                                        {{-- @if ($i == 1)
                                            ₹ {{$amount?custome_money_format($amount*12):0}}
                                        @else
                                            --
                                        @endif --}}
                                                        ₹ {{ $amount ? custome_money_format($amount * 12) : 0 }}
                                                    </td>
                                                    <td>₹ {{ custome_money_format($previous_amount_int1) }}</td>
                                                </tr>
                                            @endfor
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            @if (isset($report) && $report == 'detailed' && isset($include_step_up) && $include_step_up == 'yes' && $calcType != 2)
                                <h1 class="midheading">Step - Up SIP<br>Year-Wise Projected Value</h1>
                                <div class="roundBorderHolder">
                                    <table class="table table-bordered text-center" style="background: #fff;">
                                        <tbody>
                                            @if (isset($interest2))
                                                <tr>
                                                    <th style="vertical-align: middle;">Year</th>
                                                    <th style="vertical-align: middle;">Monthly Investment</th>
                                                    <th style="vertical-align: middle;">Annual Investment</th>
                                                    <th style="vertical-align: middle;">Year End Value <br> Scenario 1 <br>
                                                        @ {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %
                                                    </th>

                                                    <th style="vertical-align: middle;">Year End Value <br> Scenario 2 <br>
                                                        @ {{ $interest2 ? number_format((float) $interest2, 2, '.', '') : 0 }} %
                                                    </th>
                                                </tr>
                                                @php
                                                    $previous_amount_int1 = $amount;
                                                    $previous_amount_int2 = $amount;
                                                    $change_amount = $amount;
                                                @endphp

                                                @for ($i = 1; $i <= $period; $i++)
                                                    @php
                                                        //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                                                        if ($interest1 == $step_up_rate) {
                                                            $previous_amount_int1 = $ap1 * $i * pow(1 + $interest1 / 100, $i - 1);
                                                        } else {
                                                            $previous_amount_int1 = ($ap1 / ($interest1 / 100 - $step_up_rate / 100)) * (pow(1 + $interest1 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                                        }
                                                        
                                                        if ($interest2 == $step_up_rate) {
                                                            $previous_amount_int2 = $ap2 * $i * pow(1 + $interest2 / 100, $i - 1);
                                                        } else {
                                                            $previous_amount_int2 = ($ap2 / ($interest2 / 100 - $step_up_rate / 100)) * (pow(1 + $interest2 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                                        }
                                                        if ($i == 1) {
                                                            $change_amount = $amount;
                                                        } else {
                                                            $change_amount = $change_amount + ($change_amount * $step_up_rate) / 100;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>
                                                            ₹ {{ $change_amount ? custome_money_format($change_amount) : 0 }}
                                                        </td>
                                                        <td>
                                                            ₹
                                                            {{ $change_amount ? custome_money_format($change_amount * 12) : 0 }}
                                                        </td>
                                                        <td>₹ {{ custome_money_format($previous_amount_int1) }}</td>
                                                        <td>₹ {{ custome_money_format($previous_amount_int2) }}</td>
                                                    </tr>
                                                @endfor
                                            @else
                                                <tr>
                                                    <th>Year</th>
                                                    <th>Monthly Investment</th>
                                                    <th>Annual Investment</th>
                                                    <th>Year End Value @
                                                        {{ $interest1 ? number_format((float) $interest1, 2, '.', '') : 0 }} %
                                                    </th>
                                                </tr>
                                                @php
                                                    $previous_amount_int1 = $amount;
                                                @endphp

                                                @for ($i = 1; $i <= $period; $i++)
                                                    @php
                                                        //(AY119/(BA119-BC119))*((1+BA119)^(AW119)-(1+BC119)^(AW119))
                                                        if ($interest1 == $step_up_rate) {
                                                            $previous_amount_int1 = $ap1 * $i * pow(1 + $interest1 / 100, $i - 1);
                                                        } else {
                                                            $previous_amount_int1 = ($ap1 / ($interest1 / 100 - $step_up_rate / 100)) * (pow(1 + $interest1 / 100, $i) - pow(1 + $step_up_rate / 100, $i));
                                                        }
                                                        
                                                        if ($i == 1) {
                                                            $change_amount = $amount;
                                                        } else {
                                                            $change_amount = $change_amount + ($change_amount * $step_up_rate) / 100;
                                                        }
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td>
                                                            ₹ {{ $change_amount ? custome_money_format($change_amount) : 0 }}
                                                        </td>
                                                        <td>
                                                            ₹
                                                            {{ $change_amount ? custome_money_format($change_amount * 12) : 0 }}
                                                        </td>
                                                        <td>₹ {{ custome_money_format($previous_amount_int1) }}</td>
                                                    </tr>
                                                @endfor
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                        
                        
                        
                        @if (isset($report) && $report == 'detailed' && $calcType == 2)
                            <h1 class="midheading">Normal SIP</br>Year-Wise Projected Value</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                        <tr>
                                            <th>Year</th>
                                            <th>Monthly Investment</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value Scenario 1 @ {{ number_format($interest1, 2, '.', '') }} %
                                            </th>
                                            @if (isset($rate2_percent))
                                                <th>Year End Value Scenario 2 @ {{ number_format($interest2, 2, '.', '') }}
                                                    %</th>
                                            @endif
                                        </tr>
                                        @php
                                            $yr = 1;
                                            $spAmt = $amount;
                                            while ($yr <= $period) {
                                                $ev1 = (1 + $rate1_percent) * $spAmt * ((pow(1 + $rate1_percent, $yr * 12) - 1) / $rate1_percent);
                                                if (isset($rate2_percent)) {
                                                    $ev2 = (1 + $rate2_percent) * $spAmt * ((pow(1 + $rate2_percent, $yr * 12) - 1) / $rate2_percent);
                                                    echo '<tr><td>' . $yr . '</td><td>₹ ' . custome_money_format($spAmt) . '</td><td>₹ ' . custome_money_format(round($spAmt * 12)) . '</td><td>₹ ' . custome_money_format(round($ev1)) . '</td><td>₹ ' . custome_money_format(round($ev2)) . '</td></tr>';
                                                } else {
                                                    echo '<tr><td>' . $yr . '</td><td>₹ ' . custome_money_format($spAmt) . '</td><td>₹ ' . custome_money_format(round($spAmt * 12)) . '</td><td>₹ ' . custome_money_format(round($ev1)) . '</td></tr>';
                                                }
                                                $yr++;
                                            }
                                        @endphp
                                    </tbody>
                                </table>
                            </div>

                            <h1 class="midheading">Step - Up SIP</br>Year-Wise Projected Value</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center" style="background: #fff;">
                                    <tbody>
                                        <tr>
                                            <th>Year</th>
                                            <th>Monthly Investment</th>
                                            <th>Annual Investment</th>
                                            <th>Year End Value Scenario 1 @ {{ number_format($interest1, 2, '.', '') }} %
                                            </th>
                                            @if (isset($interest2))
                                                <th>Year End Value Scenario 2 @ {{ number_format($interest2, 2, '.', '') }}
                                                    %</th>
                                            @endif
                                        </tr>

                                        @php
                                            $year = 1;
                                            $year1 = 1;
                                            $sipStartAmount = $amount;
                                            $endValue = $ap1;
                                            if (isset($rate2_percent)) {
                                                $endValue1 = $ap2;
                                            } else {
                                                $endValue1 = 0;
                                            }
                                            $monthlySipAmt = $amount;
                                            $opbal1 = 0;
                                            $opbal2 = 0;
                                            $sipamt = 0;
                                            $annualIncr = $step_up_amount;
                                            
                                            if ($stepup == 1) {
                                                $actualPeriod = $period + 10;
                                            } elseif ($stepup == 2) {
                                                $actualPeriod = $period;
                                            }
                                            
                                            while ($year <= $actualPeriod) {
                                                $val = (1 + $rate1_percent) * $amount * ((pow(1 + $rate1_percent, $year * 12) - 1) / $rate1_percent);
                                                $endValue = $val;
                                                if (isset($rate2_percent)) {
                                                    $val = (1 + $rate2_percent) * $amount * ((pow(1 + $rate2_percent, $year * 12) - 1) / $rate2_percent);
                                                    $endValue1 = $val;
                                                }
                                                $yearlySipVal1 = (1 + $rate1_percent) * $sipamt * ((pow(1 + $rate1_percent, 1 * 12) - 1) / $rate1_percent);
                                                if (isset($rate2_percent)) {
                                                    $yearlySipVal2 = (1 + $rate2_percent) * $sipamt * ((pow(1 + $rate2_percent, 1 * 12) - 1) / $rate2_percent);
                                                }
                                            
                                                $lumpsumEndVal = $opbal1 * pow(1 + $rate1_percent, 12);
                                                $endVal1 = $yearlySipVal1 + $lumpsumEndVal;
                                                if (isset($rate2_percent)) {
                                                    $lumpsumEndVal1 = $opbal2 * pow(1 + $rate2_percent, 12);
                                                    $endVal2 = $yearlySipVal2 + $lumpsumEndVal1;
                                                } else {
                                                    $endVal2 = 0;
                                                }
                                                if (isset($rate2_percent)) {
                                                    echo '<tr><td>' . $year1 . '</td><td>₹ ' . custome_money_format($monthlySipAmt) . '</td><td>₹ ' . custome_money_format(round($monthlySipAmt * 12)) . '</td><td>₹ ' . custome_money_format(round($endValue + $endVal1)) . '</td><td>₹ ' . custome_money_format(round($endValue1 + $endVal2)) . '</td></tr>';
                                                } else {
                                                    echo '<tr><td>' . $year1 . '</td><td>₹ ' . custome_money_format($monthlySipAmt) . '</td><td>₹ ' . custome_money_format(round($monthlySipAmt * 12)) . '</td><td>₹ ' . custome_money_format(round($endValue + $endVal1)) . '</td></tr>';
                                                }
                                                $monthlySipAmt += $annualIncr;
                                                $sipamt += $annualIncr;
                                                $opbal1 = $endVal1;
                                                if (isset($rate2_percent)) {
                                                    $opbal2 = $endVal2;
                                                }
                                                $year1++;
                                                $year++;
                                            }
                                            
                                            $totalFundValueStepUp1 = round($endValue + $endVal1);
                                            if (isset($rate2_percent)) {
                                                $totalFundValueStepUp2 = round($endValue1 + $endVal2);
                                            }
                                            $stepup_senario1_amount = $totalFundValueStepUp1;
                                            if (isset($rate2_percent)) {
                                                $stepup_senario2_amount = $totalFundValueStepUp2;
                                            }
                                        @endphp
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        
                        <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only. Report Date :-->
                        <!--    {{ date('d/m/Y') }}</p>-->
                        <div class="description-text">
                        @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Future_Value_of_SIP')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                        </div>
                            
                        @if ($is_graph)
                            <h1 class="midheading">Graphic Representation</h1>
                            <div id="curve_chart"></div>
                            <div id="curve_chart2" style="margin-top:20px;display:none;"></div>
                        @endif
                        


                        @include('frontend.calculators.suggested.output')
                    </div>

                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if ($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i
                                    class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{ route('frontend.futureValueOfSipIndex') }}?action=back"
                                class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif

                        @if ($calculator_permissions['is_save'])
                            @if ($edit_id)
                                <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only"
                                    data-toggle="modal" data-target="#saveOutput">Update</a>
                            @else
                                <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only"
                                    data-toggle="modal" data-target="#saveOutput">Save</a>
                            @endif
                        @else
                            <a href="javascript:void(0)" class="btn btn-primary btn-round"
                                onclick="openSavePermissionModal();">Save</a>
                        @endif

                        @if ($permission['is_download'])
                            @if ($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();"
                                    class="btn btn-primary btn-round btn-solid">Download / Print</a>
                            @else
                                <a href="{{ route('frontend.futureValueOfSipOutputPdfDownload') }}" target="_blank"
                                    id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                            @endif
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid"
                                onclick="openDownloadPermissionModal();">Download / Print</a>
                        @endif

                        <a id="view_save_only" href="{{ route('frontend.view_saved_files') }}"
                            class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{ asset('') }}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{ route('frontend.futureValueOfSipOutputPdfDownload') }}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
