@extends('layouts.frontend')
@section('js_after')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js">
    </script>
    <script type="text/javascript">
        // @if (isset($report) && isset($is_graph) && $report == 'detailed')
        //         @php
            //             $previous_amount_int1 = $amount/pow((1+($rate/100)), $period);
            //             // $previous_amount_int2 = $amount/pow((1+($interest2/100)), $period);
            //
        @endphp

        //         google.charts.load('current', {'packages':['corechart']});
        //         google.charts.setOnLoadCallback(drawChart);

        //         function drawChart() {
        //             var data = google.visualization.arrayToDataTable([
        //                 ['Year', 'Exp Future Value1  @ {{ $rate ? number_format((float) $rate, 2, '.', '') : 0 }} %'],

        //                 @for ($i = 1; $i <= $period; $i++)
        //                     @php
            //                         $previous_amount_int1 = $previous_amount_int1+ ($previous_amount_int1* $rate/100);
            //                         // $previous_amount_int2 = $previous_amount_int2+ ($previous_amount_int2* $interest2/100);
            
            //                         // if($i==1){
            //                         //     $vaiue1 = (($amount/pow((1+($interest1/100)), $period)));
            //                         //     $vaiue3 = (($amount/pow((1+($interest2/100)), $period)));
            //                         // }else{
            //                         //     $vaiue1 = 0;
            //                         //     $vaiue3 = 0;
            //                         // }
            
            //                         $vaiue1 = ($previous_amount_int1);
            //                         // $vaiue2 = ($previous_amount_int2)
            //
        @endphp
        //                     ['<?php echo $i; ?>',<?php echo $vaiue1; ?>],
        //                 @endfor
        //             ]);

        //             var options = {
        //               title: '',
        //               curveType: 'function',
        //               legend: { position: 'bottom' }
        //             };

        //             var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        //             google.visualization.events.addListener(chart, 'ready', function () {
        //                 var imgUri = chart.getImageURI();
        //                  $.ajaxSetup({
        //                         headers: {
        //                             'X-CSRF-TOKEN': '{{ csrf_token() }}'
        //                         }
        //                 });
        //                 $.ajax({
        //                     type: 'POST',
        //                     url: "{{ route('frontend.investment_analysis_image') }}",
        //                     data: {img:imgUri},
        //                     success: function (data) {

        //                     }
        //                 });

        //             });

        //             chart.draw(data, options);
        //         }
        //     @endif
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
                        url: "{{ route('frontend.portfolio_projection_output_save') }}",
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
            @if ("{{ session()->get('claculator_saved_file_name') }}")
                jQuery('#save_title').val("{{ session()->get('claculator_saved_file_name') }}");    
            @endif
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
                    <h2 class="page-title">Portfolio Projection Report</h2>
                </div>
            </div>
        </div>
    </div>

    <section class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    @if($edit_id)
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                @else
                    <a href="{{route('frontend.portfolio_projection')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.portfolio_projection_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif

                    <a id="view_save_only" href="{{ route('frontend.view_saved_files') }}"
                        class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                <div class="outputTableHolder">
                    <h1 class="midheading">Portfolio Projection Report @if (isset($clientname) && !empty($clientname)) <br>
                            For {{ $clientname ? $clientname : '' }}
                        @else
                        @endif
                    </h1>


                    @if ($enter_loan_details == 1)
                        @php
                            $clienPortValue = $amount;
                            $lumpsumInvest = $lumpsum;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow(1 + $returnAnual, 1 / 12) - 1;
                            $currentPort = $clienPortValue * pow(1 + $returnAnual, $tenure);
                            $anualLumpsum = (1 + $returnAnual) * $lumpsumInvest * ((pow(1 + $returnAnual, $tenure) - 1) / $returnAnual);
                            $sipFv = (1 + $returnMonthly) * $currentMonthlySip * ((pow(1 + $returnMonthly, $totalMonths) - 1) / $returnMonthly);
                            
                        @endphp
                        <div class="roundBorderHolder">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($clienPortValue) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Lumpsum Investment Every year</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($lumpsumInvest) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($currentMonthlySip) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                        {{ $expectedRateOfReturn }} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{ $tenure }} Years
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>

                        

                        <h1 class="midheading">Expected Portfolio Value</h1>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td style="">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($currentPort) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($anualLumpsum) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>SIP</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($sipFv) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($sipFv + $currentPort + $anualLumpsum) }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        </div>
                        
                        
                        
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                        <div class="description-text">
                        @php
                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','portfolio_projection')->first();
                            if(!empty($note_data2)){
                            @endphp
                            {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                        </div>
                        

                        @if (isset($report) && $report == 'detailed')
                            <h1 class="midheading">Annual Investment & Expected Fund Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th>Year</th>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                    </tr>
                                    @php
                                        $annual_investment_total = 0;
                                        $expected_fund_value = 0;
                                    @endphp
                                    @for ($i = 1; $i <= $period; $i++)
                                        @php
                                            if ($i == 1) {
                                                $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                            } else {
                                                $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                            }
                                            $annual_investment_total = $annual_investment_total + $annual_investment;
                                            
                                            $eoy_value = $clienPortValue * pow(1 + $expectedRateOfReturn / 100, $i);
                                            
                                            $eoy_value1 = (1 + $expectedRateOfReturn / 100) * $lumpsumInvest * ((pow(1 + $expectedRateOfReturn / 100, $i) - 1) / ($expectedRateOfReturn / 100));
                                            $rateofreturn = pow(1 + $expectedRateOfReturn / 100, 1 / 12) - 1;
                                            $eoy_value2 = (1 + $rateofreturn) * $currentMonthlySip * ((pow(1 + $rateofreturn, $i * 12) - 1) / $rateofreturn);
                                        @endphp
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>₹ {{ custome_money_format($annual_investment) }}</td>
                                            <td>₹ {{ custome_money_format($annual_investment_total) }}</td>
                                            <td>₹ {{ custome_money_format($eoy_value + $eoy_value1 + $eoy_value2) }}</td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            </div>
                        @endif
                    @else
                        @php
                            $clienPortValue = $amount;
                            $lumpsumInvest = $ilumpsum;
                            $inclumpsumInvest = $lumpsum;
                            $icurrentMonthlySip = $isip;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow(1 + $returnAnual, 1 / 12) - 1;
                            $currentPort = $clienPortValue * pow(1 + $returnAnual, $tenure);
                            $anualLumpsum = (1 + $returnAnual) * $lumpsumInvest * ((pow(1 + $returnAnual, $tenure) - 1) / $returnAnual);
                            $sipFv = (1 + $returnMonthly) * $currentMonthlySip * ((pow(1 + $returnMonthly, $totalMonths) - 1) / $returnMonthly);
                            $IncAnualLumpsum = (1 + $returnAnual) * ($lumpsumInvest + $inclumpsumInvest) * ((pow(1 + $returnAnual, $tenure) - 1) / $returnAnual);
                            $incSipFv = (1 + $returnMonthly) * ($currentMonthlySip + $icurrentMonthlySip) * ((pow(1 + $returnMonthly, $totalMonths) - 1) / $returnMonthly);
                        @endphp
                        <div class="roundBorderHolder">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($clienPortValue) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Lumpsum Investment Every Year</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($lumpsumInvest) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Increase in Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($inclumpsumInvest) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($currentMonthlySip) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Addition in SIP(New)</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($icurrentMonthlySip) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                        {{ $expectedRateOfReturn }} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Period</strong>
                                    </td>
                                    <td>
                                        {{ $tenure }} Years
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </div>
                        
                        

                        <h1 class="midheading">Expected Portfolio Value</h1>
                        <div class="roundBorderHolder">
                        <table class="table table-bordered">
                            <thead>
                                <th></th>
                                <th>Current Scenario</th>
                                <th>Incremental Scenario</th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($currentPort) }}
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($currentPort) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($anualLumpsum) }}
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($IncAnualLumpsum) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>SIP</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($sipFv) }}
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($incSipFv) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($sipFv + $currentPort + $anualLumpsum) }}
                                    </td>
                                    <td>
                                        ₹ {{ custome_money_format($incSipFv + $currentPort + $IncAnualLumpsum) }}
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        </div>
                        
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        
                        <div class="description-text">
                        @php
                            $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','portfolio_projection')->first();
                            if(!empty($note_data2)){
                            @endphp
                            {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                        </div>
                        

                        @if (isset($report) && $report == 'detailed')
                            <h1 class="midheading">Annual Investment & Expected Fund Value</h1>
                            <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <th rowspan="2">Year</th>
                                        <th colspan="3">Current Scenario</th>
                                        <th colspan="3">Incremental Scenario</th>
                                    </tr>
                                    <tr>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                        <th>Annual Investment</th>
                                        <th>Cumulative Investment</th>
                                        <th>Expected Fund Value</th>
                                    </tr>
                                    @php
                                        $annual_investment_total = 0;
                                        $ic_annual_investment_total = 0;
                                    @endphp
                                    @for ($i = 1; $i <= $period; $i++)
                                        @php
                                            if ($i == 1) {
                                                $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                                $ic_annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                            } else {
                                                $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                                $ic_annual_investment = $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                            }
                                            $annual_investment_total = $annual_investment_total + $annual_investment;
                                            $ic_annual_investment_total = $ic_annual_investment_total + $ic_annual_investment;
                                            
                                            $rateofreturn = pow(1 + $expectedRateOfReturn / 100, 1 / 12) - 1;
                                            
                                            $eoy_value = $clienPortValue * pow(1 + $expectedRateOfReturn / 100, $i);
                                            
                                            $eoy_value1 = (1 + $expectedRateOfReturn / 100) * $lumpsumInvest * ((pow(1 + $expectedRateOfReturn / 100, $i) - 1) / ($expectedRateOfReturn / 100));
                                            
                                            $eoy_value2 = (1 + $rateofreturn) * $currentMonthlySip * ((pow(1 + $rateofreturn, $i * 12) - 1) / $rateofreturn);
                                            
                                            $eoy_value3 = (1 + $expectedRateOfReturn / 100) * $inclumpsumInvest * ((pow(1 + $expectedRateOfReturn / 100, $i) - 1) / ($expectedRateOfReturn / 100));
                                            
                                            $eoy_value4 = (1 + $rateofreturn) * $icurrentMonthlySip * ((pow(1 + $rateofreturn, $i * 12) - 1) / $rateofreturn);
                                        @endphp
                                        <tr>
                                            <td>{{ $i }} </td>
                                            <td>₹ {{ custome_money_format($annual_investment) }}</td>
                                            <td>₹ {{ custome_money_format($annual_investment_total) }}</td>
                                            <td>₹ {{ custome_money_format($eoy_value + $eoy_value1 + $eoy_value2) }}</td>
                                            <td>₹ {{ custome_money_format($ic_annual_investment) }}</td>
                                            <td>₹ {{ custome_money_format($ic_annual_investment_total) }}</td>
                                            <td>₹
                                                {{ custome_money_format($eoy_value + $eoy_value1 + $eoy_value2 + $eoy_value3 + $eoy_value4) }}
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            </div>
                        @endif
                    @endif
                    {{-- @if (isset($is_graph) && $is_graph != '')
                        <h1 class="midheading">Graphic Representation</h1>
                        <div id="curve_chart" style="width: 100%; height: 500px"></div>
                    @endif  --}}

                    <!--<p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only. Report Date :-->
                    <!--    {{ date('d/m/Y') }}</p>-->
                    
                    
                    <div class="description-text">
                        @php
                        $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','portfolio_projection')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                        @php } @endphp
                        Report Date : {{date('d/m/Y')}}
                    </div>

                    @include('frontend.calculators.suggested.output')
                </div>
                <div class="text-center" style="padding:83px 0 20px 0;">
                    @if($edit_id)
                    <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                @else
                    <a href="{{route('frontend.portfolio_projection')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.portfolio_projection_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
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
        var base_url = "{{route('frontend.portfolio_projection_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
