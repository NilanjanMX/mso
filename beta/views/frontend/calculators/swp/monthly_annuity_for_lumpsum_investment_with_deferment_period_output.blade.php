@extends('layouts.frontend')
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
                        url: "{{ route('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputSave') }}",
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
        //Deferment Period (Months)
        $deferment_period_months = $deferment_period*12;
        //Annuity Period (Months) T9*12
        $annuity_period_months = $annuity_period*12;

        //Accumulation Monthly Return (1) (1+T13%)^(1/12)-1
        $accumulation_monthly_return1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1 ;
        //Annuity Purchase Amount (1)  T9*(1+AV29)^AV28
        $annuity_purchase_amount1 = $initial_investment*(1+$accumulation_monthly_return1)**$deferment_period_months;
        //Distribution Monthly Return (1) (1+AC13%)^(1/12)-1
        $distribution_monthly_return1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
        //PV of Balance Required (1) T15/(1+AV35)^(AV33)
        $pv_of_balance_required1 = $balance_required/(1+$distribution_monthly_return1)**($annuity_period_months);
        //Balance Available for Annuity (1) AV31-AV37
        $balance_available_for_annuity1 = $annuity_purchase_amount1 - $pv_of_balance_required1;
        //Monthly Annuity Amount (1) (AV35*AV39)/(1-(1+AV35)^(-AV33))
        $monthly_annuity_amount1 = ($distribution_monthly_return1*$balance_available_for_annuity1)/(1-(1+$distribution_monthly_return1)**(-$annuity_period_months));
        //echo $monthly_annuity_amount1; die();

        if (isset($accumulation_phase_interest_rate_2) && $distribution_phase_interest_rate_2 > 0){
            //Accumulation Monthly Return (2) (1+T13%)^(1/12)-1
            $accumulation_monthly_return2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1 ;
            //Annuity Purchase Amount (2)
            $annuity_purchase_amount2 = $initial_investment*(1+$accumulation_monthly_return2)**$deferment_period_months;
            //Distribution Monthly Return (2)
             $distribution_monthly_return2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
            //PV of Balance Required (1) T15/(1+AV35)^(AV33)
            $pv_of_balance_required2 = $balance_required/(1+$distribution_monthly_return2)**($annuity_period_months);
            //Balance Available for Annuity (1) AV31-AV37
            $balance_available_for_annuity2 = $annuity_purchase_amount2 - $pv_of_balance_required2;
            //Monthly Annuity Amount (2) (AV35*AV39)/(1-(1+AV35)^(-AV33))
            $monthly_annuity_amount2 = ($distribution_monthly_return2*$balance_available_for_annuity2)/(1-(1+$distribution_monthly_return2)**(-$annuity_period_months));

        }

    @endphp

    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Monthly Annuity Planning @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Initial Investment</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($initial_investment)}}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Deferment Period</strong>
                            </td>
                            <td>
                                {{$deferment_period?$deferment_period:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Annuity Period</strong>
                            </td>
                            <td>
                                {{$annuity_period?$annuity_period:0}} Years
                            </td>
                        </tr>
                        @if(isset($balance_required) && $balance_required>0)
                            <tr>
                                <td>
                                    <strong>Balance Required</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($balance_required)}}
                                </td>
                            </tr>
                        @endif
                        </tbody></table>
                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Accumulated Corpus</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2 >0)
                            <tr>
                                <td>
                                    Scenario 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($annuity_purchase_amount1)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($annuity_purchase_amount2)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}}%
                                </td>
                                <td>
                                    ₹ {{custome_money_format($annuity_purchase_amount1)}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">Monthly Annuity Amount</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        @if(isset($distribution_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                            <tr>
                                <td>
                                    Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %
                                </td>
                                <td>
                                    Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <strong>₹ {{custome_money_format($monthly_annuity_amount1)}} </strong>
                                </td>
                                <td>
                                    <strong>₹ {{custome_money_format($monthly_annuity_amount2)}} </strong>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td>
                                    @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}}%
                                </td>
                                <td>
                                    ₹ {{custome_money_format($monthly_annuity_amount1)}}
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    @if(isset($report) && $report=='detailed')
                        <h5 class="text-center">Accumulation Phase <br>Projected Annual Investment Value</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)

                                <tr>
                                    <th style="vertical-align: middle;">Year</th>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_2?number_format((float)$accumulation_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                                </tr>

                                @for($i=1;$i<=$deferment_period;$i++)
                                    @php
                                        //Year End Value AT65*(1+AV65)^AU65
                                       $year_end_value1 = $initial_investment*(1+$accumulation_phase_interest_rate_1/100)**$i;
                                       //Year End Value AT65*(1+AV65)^AU65
                                       $year_end_value2 = $initial_investment*(1+$accumulation_phase_interest_rate_2/100)**$i;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        @if($i==1)
                                         <td>₹ {{custome_money_format($initial_investment)}}</td>
                                        @else
                                         <td> -- </td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                        <td>₹ {{custome_money_format($year_end_value2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th style="vertical-align: middle;">Year</th>
                                    <th style="vertical-align: middle;">Annual Investment</th>
                                    <th>Year End Value <br> @ {{$accumulation_phase_interest_rate_1?number_format((float)$accumulation_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                </tr>
                                @for($i=1;$i<=$deferment_period;$i++)
                                    @php
                                        //Year End Value AT65*(1+AV65)^AU65
                                        $year_end_value1 = $initial_investment*(1+$accumulation_phase_interest_rate_1/100)**$i;
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        @if($i==1)
                                            <td>₹ {{custome_money_format($initial_investment)}}</td>
                                        @else
                                            <td> -- </td>
                                        @endif
                                        <td>₹ {{custome_money_format($year_end_value1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                        <h5 class="text-center">Distribution Phase <br>Annual Wihdrawal & Projected Investment Value</h5>
                        <table class="table table-bordered text-center" style="background: #fff;">
                            <tbody>
                            @if(isset($accumulation_phase_interest_rate_2) && $accumulation_phase_interest_rate_2>0)
                                <tr>
                                    <th style="vertical-align: middle;" rowspan="2">Year</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                    <th colspan="2">Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format((float)$distribution_phase_interest_rate_2, 2, '.', ''):0}} % </th>
                                </tr>
                                <tr>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @php
                                    $opening_monthly_balance_1 = $annuity_purchase_amount1;
									$opening_monthly_balance_2 = $annuity_purchase_amount2;
                                @endphp
                                @for($i=1;$i<=$annuity_period;$i++)
                                    @php
                                            $balance_eom_1 = ($opening_monthly_balance_1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));

                                            $balance_eom_2 = ($opening_monthly_balance_2*(1+$distribution_monthly_return2)**($i*12)-($monthly_annuity_amount2*((1+$distribution_monthly_return2)**($i*12)-1)/$distribution_monthly_return2));

                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td>₹ {{custome_money_format($balance_eom_1)}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount2)}}</td>
                                        <td>₹ {{custome_money_format($balance_eom_2)}}</td>
                                    </tr>
                                @endfor
                            @else
                                <tr>
                                    <th style="vertical-align: middle;" rowspan="2">Year</th>
                                    <th colspan="2">Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format((float)$distribution_phase_interest_rate_1, 2, '.', ''):0}} % </th>
                                </tr>
                                <tr>
                                    <th>Monthly Annuity</th>
                                    <th>Year End Balance</th>
                                </tr>
                                @php
                                    $opening_monthly_balance_1 = $annuity_purchase_amount1;
                                @endphp
                                @for($i=1;$i<=$annuity_period;$i++)
                                    @php
                                        $balance_eom_1 = ($opening_monthly_balance_1*(1+$distribution_monthly_return1)**($i*12)-($monthly_annuity_amount1*((1+$distribution_monthly_return1)**($i*12)-1)/$distribution_monthly_return1));
                                    @endphp
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>₹ {{custome_money_format($monthly_annuity_amount1)}}</td>
                                        <td>₹ {{custome_money_format($balance_eom_1)}}</td>
                                    </tr>
                                @endfor
                            @endif
                            </tbody>
                        </table>
                        <p style="text-align: left; margin-top: 20px;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    @endif
                    @include('frontend.calculators.suggested.output')
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>
                    <a id="save_only" href="javascript:void(0)" class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    <a href="{{route('frontend.monthlyAnnuityForlumpsumInvestmentWithDefermentPeriodOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

@endsection
