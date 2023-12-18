@extends('layouts.frontend')

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

@section('js_after')
    <script>
        jQuery(document).ready(function(){
            jQuery('#save_cal_btn').click(function(e){
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                var title = jQuery('#save_title').val();
                if(title.trim()==''){
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').addClass('alert-danger');
                    jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
                }else{
                    jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                    jQuery('#save_cal_msg').html('');
                    jQuery('#save_title').val('');
                    jQuery.ajax({
                        url: "{{ route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_save') }}",
                        method: 'get',
                        data: {
                            title: title
                        },
                        success: function(result){
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').addClass('alert-success');
                            jQuery('#save_cal_msg').html('Data Successfully Saved');
                            setTimeout(function () {
                                $('#saveOutput').modal('toggle');
                                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                                jQuery('#save_cal_msg').html('');
                            },500);
                            jQuery('.save_only').hide();
                            jQuery('.view_save_only').show();
                        }});
                }

            });
        });
    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

        @if(isset($is_graph) && $is_graph)
            @php
                $previous_amount_int1 = $amount;
                $previous_amount_int2 = $amount;
                $cumulative_investment = 0;
            @endphp

            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                @if(isset($rate2_percent))
                    var data = google.visualization.arrayToDataTable([
                        ['Year',"Cumulative Investment", "Exp Future Value@ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}}%", "Exp Future Value@ {{$interest2?number_format((float)$interest2, 2, '.', ''):0}}%"],
    
                        @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                            @php
                                
                                if ($sip_period>=$i){
                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                }else{
                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                }
                                if ($sip_period>=$i){
                                    $previous_amount_int2 = (1+$rate2_percent)*$amount*((pow((1+$rate2_percent),($i*12))-1)/$rate2_percent);
                                }else{
    
                                    $previous_amount_int2 = ($previous_amount_int2*pow((1+$rate2_percent),12));
                                }
    
                                if($i<=$sip_period){
                                    $cumulative_investment = $cumulative_investment + ($amount * 12);
                                }
    
                                $vaiue1 = round($previous_amount_int1);
                                $vaiue2 = round($previous_amount_int2);
                            @endphp
                            ['<?php echo $i;?>',<?php echo $cumulative_investment;?>,<?php echo $vaiue1;?>,<?php echo $vaiue2;?>],
                        @endfor
                    ]);
                @else
                    var data = google.visualization.arrayToDataTable([
                        ['Year',"Cumulative Investment", "Exp Future Value@ {{$interest1?number_format((float)$interest1, 2, '.', ''):0}}%"],
    
                        @for($i=1;$i<=$sip_period+$deferment_period;$i++)
                            @php
                                
                                if ($sip_period>=$i){
                                    $previous_amount_int1 = (1+$rate1_percent)*$amount*((pow((1+$rate1_percent),($i*12))-1)/$rate1_percent);
                                }else{
                                    $previous_amount_int1 = ($previous_amount_int1*pow((1+$rate1_percent),12));
                                }
    
                                if($i<=$sip_period){
                                    $cumulative_investment = $cumulative_investment + ($amount * 12);
                                }
    
                                $vaiue1 = round($previous_amount_int1);
                            @endphp
                            ['<?php echo $i;?>',<?php echo $cumulative_investment;?>,<?php echo $vaiue1;?>],
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

                google.visualization.events.addListener(chart, 'ready', function () {
                    var imgUri = chart.getImageURI();
                     $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                    });
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('frontend.investment_analysis_image') }}",
                        data: {img:imgUri},
                        success: function (data) {
                        
                        }
                    });
                   
                });

                chart.draw(data, options);
            }
        @endif
    </script>
    <style>
        
    </style>
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
                    <h2 class="page-title">Future Value of Limited Period SIP</h2>
                </div>
            </div>
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    
                    @if($permission['is_save'])
                        @if($edit_id)
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h1 class="midheading">Future Value of Limited Period SIP @if(isset($clientname) && !empty($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                        <div class="roundBorderHolder">
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
                        </div>
                            <h1 class="midheading">Total Investment</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        ₹ {{custome_money_format($totalinvestment)}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                            <h1 class="midheading">Expected Future Value</h1>
                        <div class="roundBorderHolder">
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
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>-->
                        
                        <div class="description-text">
                            @php
                                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Future_Value_of_Limited_Period_SIP')->first();
                                if(!empty($note_data1)){
                            @endphp
                                {!!$note_data1->description!!}
                            @php } @endphp
                            Report Date : {{date('d/m/Y')}}
                        </div>
                        
                            @if(isset($report) && $report=='detailed')
                            <h1 class="midheading">Year-Wise Projected Value</h1>
                        <div class="roundBorderHolder">
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
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if($i<=$sip_period)
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
                                                @if($i<=$sip_period)
                                                    ₹ {{$amount?custome_money_format($amount):0}}
                                                @else
                                                    --
                                                @endif
                                            </td>
                                            <td>
                                                @if($i<=$sip_period)
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
                        </div>
                        <div class="description-text">
                            @php
                                $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                                if(!empty($note_data2)){
                            @endphp
                                {!!$note_data2->description!!}
                            @php } @endphp
                            Report Date : {{date('d/m/Y')}}
                        </div>
                            <!--<p class="text-left">*The above chart is approximate and for illustration purpose only</p>-->
                            @endif
                        @if ($is_graph)
                            <h1 class="midheading">Graphic Representation</h1>
                            <div id="curve_chart"></div>
                        @endif
                        

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif
                        @if($permission['is_save'])
                            @if($edit_id)
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                            @else
                                <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                            @endif
                        @else
                            <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                        @endif
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.limitedPeriodSIPfutureValueAfterDefermentPeriod_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
