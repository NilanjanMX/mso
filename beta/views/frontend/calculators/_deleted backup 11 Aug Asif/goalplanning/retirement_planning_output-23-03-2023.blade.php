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
                        url: "{{ route('frontend.retirementPlanningOutputSave') }}",
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

        //Accumulation Period (Months) (AD41-AD40)*12
        $accumulation_period_months = ($retirement_age-$current_age)*12;
        //Distribution Period (Months) (AD42-AD41)*12
        $distribution_period_months = ($age_at_which_annuity_ends-$retirement_age)*12;
        //Monthly Return During Accumulation Phase(1) (1+AG48%)^(1/12)-1
        $monthly_return_during_accumulation_phase1 = (1+$accumulation_phase_interest_rate_1/100)**(1/12)-1;
        //Monthly Return During Accumulation Phase(2) (1+AN48%)^(1/12)-1
        $monthly_return_during_accumulation_phase2 = (1+$accumulation_phase_interest_rate_2/100)**(1/12)-1;
        //Monthly Inflation During Accumulation Phase (1+O48%)^(1/12)-1
        $monthly_inflation_during_accumulation_phase = (1+$expected_inflation_rate_till_retirement/100)**(1/12)-1;

        //Monthly Return During Distribution Phase(1) (1+AG49%)^(1/12)-1
        $monthly_return_during_distribution_phase1 = (1+$distribution_phase_interest_rate_1/100)**(1/12)-1;
        //Monthly Return During Distribution Phase(2) (1+AN49%)^(1/12)-1
        $monthly_return_during_distribution_phase2 = (1+$distribution_phase_interest_rate_2/100)**(1/12)-1;
        //Monthly Inflation Rate Post Retirement (1+O49%)^(1/12)-1
        if($expected_inflation_rate_post_retirement=='0')
        {
            $monthly_inflation_rate_post_retirement = '0';
        }else{
            $monthly_inflation_rate_post_retirement = (1+$expected_inflation_rate_post_retirement/100)**(1/12)-1;
        }
       
        //1st Month Annuity Amount Post Retirement AF43*(1+BF37)^BF33
        $first_month_annuity_post_retirement = $current_monthly_expense*(1+$monthly_inflation_during_accumulation_phase)**$accumulation_period_months;
         
        //Retirement Corpus Required for Annuity(1) (BF41+BF41*BF40)*(1-(1+BF40)^(BF34-1)*(1+BF38)^(-BF34+1))/(BF38-BF40)+BF41
        $retirement_corpus_required_for_annuity1 = ($first_month_annuity_post_retirement+$first_month_annuity_post_retirement*$monthly_inflation_rate_post_retirement)*(1-(1+$monthly_inflation_rate_post_retirement)**($distribution_period_months-1)*(1+$monthly_return_during_distribution_phase1)**(-$distribution_period_months+1))/($monthly_return_during_distribution_phase1-$monthly_inflation_rate_post_retirement)+$first_month_annuity_post_retirement;
        
        //Retirement Corpus Required for Annuity(2) (BF41+BF41*BF40)*(1-(1+BF40)^(BF34-1)*(1+BF39)^(-BF34+1))/(BF39-BF40)+BF41
        
        if($monthly_inflation_rate_post_retirement!='0')
        {
            $retirement_corpus_required_for_annuity2 = ($first_month_annuity_post_retirement+$first_month_annuity_post_retirement*$monthly_inflation_rate_post_retirement)*(1-(1+$monthly_inflation_rate_post_retirement)**($distribution_period_months-1)*(1+$monthly_return_during_distribution_phase2)**(-$distribution_period_months+1))/($monthly_return_during_distribution_phase2-$monthly_inflation_rate_post_retirement)+$first_month_annuity_post_retirement;
        }else{
            $retirement_corpus_required_for_annuity2='0';
        }
        
        
        
        //Retirement Corpus Required for Balance(1) AF44/(1+BF38)^BF34
        $retirement_corpus_required_for_balance1 = $balance_required_at_end_of_annuity/(1+$monthly_return_during_distribution_phase1)**$distribution_period_months;
        //Retirement Corpus Required for Balance(2)
        $retirement_corpus_required_for_balance2 = $balance_required_at_end_of_annuity/(1+$monthly_return_during_distribution_phase2)**$distribution_period_months;
        //Future Value of Current Investment AJ57*(1+AG58%)^(AD41-AD40)
        $future_value_of_current_investment = $current_market_value_of_investment*(1+$expected_rate_of_return_current_investment/100)**($retirement_age-$current_age);

        //Other Amount Receivable on Retirement
        $other_amount_receivable_on_retirement = $other_amount_receivable_on_retirement;
        //Corpus required at the time of Retirement(1) BF42+BF44-BF46-BF47
        $corpus_required_at_the_time_of_retirement1 = $retirement_corpus_required_for_annuity1+$retirement_corpus_required_for_balance1-$future_value_of_current_investment-$other_amount_receivable_on_retirement;
        //Corpus required at the time of Retirement(2)
        $corpus_required_at_the_time_of_retirement2 = $retirement_corpus_required_for_annuity2+$retirement_corpus_required_for_balance2-$future_value_of_current_investment-$other_amount_receivable_on_retirement;
        //Total Retirement Corpus (1) BF42+BF44
        $total_retirement_corpus1 = $retirement_corpus_required_for_annuity1+$retirement_corpus_required_for_balance1;
        //Total Retirement Corpus (2)
        $total_retirement_corpus2 = $retirement_corpus_required_for_annuity2+$retirement_corpus_required_for_balance2;
        //Fund Value at Retirement BF46+BF47
        $fund_value_at_retirement = $future_value_of_current_investment+$other_amount_receivable_on_retirement;

        //Balance After 5 Years(1)
        $balance_after_5_years1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**($accumulation_period_months-60);
        $balance_after_5_years2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**($accumulation_period_months-60);

        //Balance After 10 Years(1) BF48/(1+BF35)^(BF33-120)
        $balance_after_10_years1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**($accumulation_period_months-120);
        $balance_after_10_years2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**($accumulation_period_months-120);
        //Projected Monthly Expenses At Retirement
        $projected_monthly_expenses_at_retirement = $first_month_annuity_post_retirement;
        //Retirement Corpus Required (Scenario 1)
        $retirement_corpus_required_scenario1 = $corpus_required_at_the_time_of_retirement1;
        //Retirement Corpus Required (Scenario 2)
        $retirement_corpus_required_scenario2 = $corpus_required_at_the_time_of_retirement2;
        //SIP Till Retirement(1) (BF48*BF35)/((1+BF35)*((1+BF35)^((AD41-AD40)*12)-1))
        $sip_till_retirement1 = ($corpus_required_at_the_time_of_retirement1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(($retirement_age-$current_age)*12)-1));
        //SIP Till Retirement(2) (BF49*BF36)/((1+BF36)*((1+BF36)^((AD41-AD40)*12)-1))
     
        if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
         $sip_till_retirement2 = ($corpus_required_at_the_time_of_retirement2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(($retirement_age-$current_age)*12)-1));
        }else{
            $sip_till_retirement2='';
        }
        //SIP For 5 Years(1) IF(AD41-AD40<5,"NA",(BF54*BF35)/((1+BF35)*((1+BF35)^(60)-1)))

        if (($retirement_age-$current_age)<5){
            $sip_for_5_years1 = "NA";
        }else{
            $sip_for_5_years1 = ($balance_after_5_years1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(60)-1));
        }
        if (($retirement_age-$current_age)<5){
            $sip_for_5_years2 = "NA";
        }else{
            if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
            $sip_for_5_years2 = ($balance_after_5_years2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(60)-1));
            }else{
                $sip_for_5_years2 = '';
            }
            }


        //SIP For 10 Years(1) IF(AD41-AD40<10,"NA",(BF56*BF35)/((1+BF35)*((1+BF35)^(120)-1)))
        if (($retirement_age-$current_age)<10){
            $sip_for_10_years1 = "NA";
        }else{
            $sip_for_10_years1 = ($balance_after_10_years1*$monthly_return_during_accumulation_phase1)/((1+$monthly_return_during_accumulation_phase1)*((1+$monthly_return_during_accumulation_phase1)**(120)-1));
        }

        if (($retirement_age-$current_age)<10){
            $sip_for_10_years2 = "NA";
        }else{
            if (isset($monthly_return_during_accumulation_phase2) && $monthly_return_during_accumulation_phase2 > 0){
            $sip_for_10_years2 = ($balance_after_10_years2*$monthly_return_during_accumulation_phase2)/((1+$monthly_return_during_accumulation_phase2)*((1+$monthly_return_during_accumulation_phase2)**(120)-1));
            }else{
                    $sip_for_10_years2 = 0;
            }
            }

        //Lumpsum Investment(1) BF48/(1+BF35)^((AD41-AD40)*12)
        $limpsum_investment1 = $corpus_required_at_the_time_of_retirement1/(1+$monthly_return_during_accumulation_phase1)**(($retirement_age-$current_age)*12);
        $limpsum_investment2 = $corpus_required_at_the_time_of_retirement2/(1+$monthly_return_during_accumulation_phase2)**(($retirement_age-$current_age)*12);

        //echo $limpsum_investment2; die();
    @endphp

    <section  class="main-sec">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="javascript:history.back()" class="btn btn-primary btn-round" ><i class="fa fa-angle-left"></i> Back</a>

                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.retirementPlanningOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    <h5 class="mb-3 text-center">Retirement @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h5>

                    <table class="table table-bordered text-left">
                        <tbody>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Current Age</strong>
                            </td>
                            <td>
                                {{$current_age?$current_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Retirement Age:</strong>
                            </td>
                            <td>
                                {{$retirement_age?$retirement_age:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Annuity Ends at Age:</strong>
                            </td>
                            <td>
                                {{$age_at_which_annuity_ends?$age_at_which_annuity_ends:0}} Years
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Current Monthly Expense:</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($current_monthly_expense)}}
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 50%;">
                                <strong>Balance Required at Age {{$age_at_which_annuity_ends?$age_at_which_annuity_ends:0}}:</strong>
                            </td>
                            <td>
                                ₹ {{custome_money_format($balance_required_at_end_of_annuity)}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td colspan="2">
                                <strong>Expected Inflation Rate</strong>
                            </td>
                            @if($accumulation_phase_interest_rate_2>0)
                            <td>
                                <strong>Assumed Return</strong>
                            </td>
                            <td>
                                <strong>Scenario 1</strong>
                            </td>

                            <td>
                                <strong>Scenario 2</strong>
                            </td>
                            @else
                                <td colspan="2">
                                    <strong>Assumed Return</strong>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>Pre Retirement</td>
                            <td>{{$expected_inflation_rate_till_retirement?number_format($expected_inflation_rate_till_retirement, 2, '.', ''):0}} %</td>
                            <td>Accumulation Phase</td>
                            <td>{{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</td>
                            @if($accumulation_phase_interest_rate_2>0)
                            <td>{{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</td>
                            @endif
                        </tr>
                        <tr>
                            <td >Post Retirement</td>
                            <td>{{$expected_inflation_rate_post_retirement?number_format($expected_inflation_rate_post_retirement, 2, '.', ''):0}} %</td>
                            <td>Distribution Phase</td>
                            <td>{{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</td>
                            @if($distribution_phase_interest_rate_2>0)
                            <td>{{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</td>
                            @endif
                        </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Projected Monthly Expense at Retirement</strong>
                            </td>
                            <td><strong>₹ {{custome_money_format($projected_monthly_expenses_at_retirement)}}</strong></td>
                        </tr>
                        <tr>
                            <td rowspan="2" style="vertical-align: middle;">
                                <strong>Total Retirement Corpus Required</strong>
                            </td>
                            <td style="padding: 0;">
                                <table width="100%">
                                    @if($accumulation_phase_interest_rate_2>0)
                                    <tr>
                                        <td>
                                            <strong>Scenario 1</strong>
                                        </td>

                                        <td>
                                            <strong>Scenario 2</strong>
                                        </td>

                                    </tr>
                                    @endif
                                    <tr>
                                        <td>₹ {{custome_money_format($total_retirement_corpus1)}}</td>
                                        @if($accumulation_phase_interest_rate_2>0)
                                        <td>₹ {{custome_money_format($total_retirement_corpus2)}}</td>
                                        @endif
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Current Market Value of Investment</strong>
                            </td>
                            <td>
                                @if($current_market_value_of_investment>0)
                                ₹  {{custome_money_format($current_market_value_of_investment)}}
                                @else
                                    ₹ 0
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Assumed Rate of Return</strong>
                            </td>
                            <td>{{$expected_rate_of_return_current_investment?number_format($expected_rate_of_return_current_investment, 2, '.', ''):0}} % </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Expected Future Value of Current Investment</strong>
                            </td>
                            <td>₹  {{custome_money_format($future_value_of_current_investment)}} </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Other Amount Receivable on Retirement</strong>
                            </td>
                            <td>₹  {{custome_money_format($other_amount_receivable_on_retirement)}} </td>
                        </tr>
                        </tbody>
                    </table>

                    <!--- 08.06.2020 ---->
                    @if(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus1 && ($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus2 && $accumulation_phase_interest_rate_2>0)
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                <td>
                                    <strong>Scenario 1</strong>
                                </td>
                                <td>
                                    <strong>Scenario 2</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h5>Available Investment Options:</h5>
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td colspan="2" style="padding: 20px;">
                                    <strong style="font-size: 18px;">You don't need any further investment for the above Goal!</strong>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    @elseif(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus1)
                            <table class="table table-bordered text-center">
                                <tbody>
                                    <tr>
                                        <td rowspan="2" style="vertical-align: middle">
                                            <strong>Balance Retirement Corpus Required</strong>
                                        </td>
                                        @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <strong>Scenario 1</strong>
                                        </td>
                                        <td>
                                            <strong>Scenario 2</strong>
                                        </td>
                                        @else
                                            <td>
                                                <strong>Amount</strong>
                                            </td>
                                        @endif
                                    </tr>
                                    <tr>
                                        <td>
                                            You don't need any further investment for the above Goal!
                                        </td>
                                        @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            ₹ {{custome_money_format($total_retirement_corpus2)}}
                                        </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                            <h5>Available Investment Options:</h5>
                            <table class="table table-bordered text-center">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Investment Option</strong>
                                    </td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>
                                            <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                        </td>
                                        <td>
                                            <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                        </td>
                                    @else
                                        <td>
                                            <strong>Amount</strong>
                                        </td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Monthly SIP Till Age {{$retirement_age}}</td>
                                    <td rowspan="4" style="vertical-align: middle;"><strong>You don't need any further investment for the above Goal!</strong></td>
                                    {{--<td>₹  {{custome_money_format($sip_till_retirement1)}} </td>--}}
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>₹  {{custome_money_format($sip_till_retirement2)}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Monthly SIP For 5 Years</td>
                                    {{--<td>₹  {{custome_money_format($sip_for_5_years1)}} </td>--}}
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>₹  {{custome_money_format($sip_for_5_years2)}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Monthly SIP For 10 Years</td>
                                    {{--<td>₹  {{custome_money_format($sip_for_10_years1)}} </td>--}}
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>₹  {{custome_money_format($sip_for_10_years2)}}</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Lumpsum Investment</td>
                                    {{--<td>₹  {{custome_money_format($limpsum_investment1)}} </td>--}}
                                    @if($accumulation_phase_interest_rate_2>0)
                                        <td>₹ {{custome_money_format($limpsum_investment2)}}</td>
                                    @endif
                                </tr>
                                </tbody>
                            </table>

                    @elseif(($future_value_of_current_investment+$other_amount_receivable_on_retirement)>=$total_retirement_corpus2 && $accumulation_phase_interest_rate_2>0)
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                <td>
                                    <strong>Scenario 1</strong>
                                </td>
                                <td>
                                    <strong>Scenario 2</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ₹ {{custome_money_format($total_retirement_corpus1)}}
                                </td>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <h5>Available Investment Options:</h5>
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>
                                        <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                    </td>
                                @else
                                    <td>
                                        <strong>Amount</strong>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age {{$retirement_age}}</td>
                                <td>₹  {{custome_money_format($sip_till_retirement1)}} </td>
                                <td rowspan="4" style="vertical-align: middle;"><strong>You don't need any further investment for the above Goal!</strong></td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹  {{custome_money_format($sip_for_5_years1)}} </td>
                                {{--<td>₹  {{custome_money_format($sip_for_5_years2)}}</td>--}}
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹  {{custome_money_format($sip_for_10_years1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    {{--<td>₹  {{custome_money_format($sip_for_10_years2)}}</td>--}}
                                @endif
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹  {{custome_money_format($limpsum_investment1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    {{--<td>₹ {{custome_money_format($limpsum_investment2)}}</td>--}}
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    @else
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>
                                        <strong>Scenario 1</strong>
                                    </td>
                                    <td>
                                        <strong>Scenario 2</strong>
                                    </td>
                                @else
                                    <td>
                                        <strong>Amount</strong>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>
                                    ₹  {{custome_money_format($total_retirement_corpus1-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>
                                        ₹  {{custome_money_format($total_retirement_corpus2-$future_value_of_current_investment-$other_amount_receivable_on_retirement)}}
                                    </td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                        <h5>Available Investment Options:</h5>
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>
                                        <strong>Option 1 @ {{$accumulation_phase_interest_rate_1?number_format($accumulation_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                                    </td>
                                    <td>
                                        <strong>Option 2 @ {{$accumulation_phase_interest_rate_2?number_format($accumulation_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                                    </td>
                                @else
                                    <td>
                                        <strong>Amount</strong>
                                    </td>
                                @endif
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age {{$retirement_age}}</td>
                                <td>₹  {{custome_money_format($sip_till_retirement1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>₹  {{custome_money_format($sip_till_retirement2)}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹  {{custome_money_format($sip_for_5_years1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>₹  {{custome_money_format($sip_for_5_years2)}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹  {{custome_money_format($sip_for_10_years1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>₹  {{custome_money_format($sip_for_10_years2)}}</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹  {{custome_money_format($limpsum_investment1)}} </td>
                                @if($accumulation_phase_interest_rate_2>0)
                                    <td>₹ {{custome_money_format($limpsum_investment2)}}</td>
                                @endif
                            </tr>
                            </tbody>
                        </table>
                    @endif

                    <!--- end ---->

                    @php
                        $total_months = ($age_at_which_annuity_ends-$retirement_age)*12;
                        $age = $retirement_age;
                        $year_monthly_annuity1 = 0;
                        $year_monthly_annuity2 = 0;
                    @endphp

                    <!--- Scenario 1 ---->
                    {{--<table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                <td>
                                    <strong>Scenario 1</strong>
                                </td>
                                <td>
                                    <strong>Scenario 2</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                                <td>
                                    ₹ 10,5000
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong>Option 1 @ 10 %</strong>
                                </td>
                                <td>
                                    <strong>Option 2 @ 12 %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age 60</td>
                                <td rowspan="4" style="vertical-align: middle;"><strong>You don't need any further investment for the above Goal!</strong></td>
                                <td>₹ 1000</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹  1000</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ 1000</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ 1000</td>
                            </tr>
                        </tbody>
                    </table>--}}

                    <!--- Scenario 2 ---->
                    {{--<table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                <td>
                                    <strong>Scenario 1</strong>
                                </td>
                                <td>
                                    <strong>Scenario 2</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ₹ 10,5000
                                </td>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td>
                                    <strong>Investment Option</strong>
                                </td>
                                <td>
                                    <strong>Option 1 @ 10 %</strong>
                                </td>
                                <td>
                                    <strong>Option 2 @ 12 %</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>Monthly SIP Till Age 60</td>
                                <td>₹ 1000</td>
                                <td rowspan="4" style="vertical-align: middle;"><strong>You don't need any further investment for the above Goal!</strong></td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 5 Years</td>
                                <td>₹  1000</td>
                            </tr>
                            <tr>
                                <td>Monthly SIP For 10 Years</td>
                                <td>₹ 1000</td>
                            </tr>
                            <tr>
                                <td>Lumpsum Investment</td>
                                <td>₹ 1000</td>
                            </tr>
                        </tbody>
                    </table>--}}

                    <!--- Scenario 3 ---->
                    {{--<table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td rowspan="2" style="vertical-align: middle">
                                    <strong>Balance Retirement Corpus Required</strong>
                                </td>
                                <td>
                                    <strong>Scenario 1</strong>
                                </td>
                                <td>
                                    <strong>Scenario 2</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                                <td>
                                    You don't need any further investment for the above Goal!
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                                <td colspan="2" style="padding: 20px;">
                                    <strong style="font-size: 18px;">You don't need any further investment for the above Goal!</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>--}}

                    <h1 style="color: #131f55;font-size:22px;margin-top:20px;margin-bottom:10px;text-align: center;">Monthly Annuity & Yearwise Projected Value</h1>
                    <table class="table table-bordered text-center">
                        <tbody>
                        <tr>
                            @if($accumulation_phase_interest_rate_2>0)
                            <td rowspan="2" style="vertical-align: middle;">
                                <strong>Age</strong>
                            </td>
                            <td colspan="2">
                                <strong>Scenario 1 @ {{$distribution_phase_interest_rate_1?number_format($distribution_phase_interest_rate_1, 2, '.', ''):0}} %</strong>
                            </td>
                            <td colspan="2">
                                <strong>Scenario 2 @ {{$distribution_phase_interest_rate_2?number_format($distribution_phase_interest_rate_2, 2, '.', ''):0}} %</strong>
                            </td>
                            @else

                            @endif
                        </tr>
                        <tr>
                            @if($accumulation_phase_interest_rate_2<=0)
                                <th>
                                    <strong>Age</strong>
                                </th>
                            @endif
                            <td><strong>Monthly Annuity</strong></td>
                            <td><strong>Balance EOY</strong></td>
                            @if($accumulation_phase_interest_rate_2>0)
                            <td><strong>Monthly Annuity</strong></td>
                            <td><strong>Balance EOY</strong></td>
                            @endif
                        </tr>
                        @for($m=1;$m<=$total_months;$m++)
                            @php

                                //Option 1
                                //Retirement Corpus
                                if ($m==1){
                                     $retirement_corpus1 = $total_retirement_corpus1;
                                }else{
                                     $retirement_corpus1 = $eom_value1;
                                }

                                //Monthly Annuity IF(BH34>=BG34,BF58,0)
                                if ($total_months>=$m && $m==1){
                                    $monthly_annuity1 = $projected_monthly_expenses_at_retirement;
                                }elseif ($total_months>=$m && $m>1){
                                    $monthly_annuity1 = $monthly_annuity1+$monthly_annuity1*$monthly_inflation_rate_post_retirement;
                                }
                                else{
                                    $monthly_annuity1 = 0;
                                }

                                $year_monthly_annuity1 += $monthly_annuity1;

                                $balance_left1 = $retirement_corpus1-$monthly_annuity1;
                                //$monthly_return_during_distribution_phase1
                                //EOM Value IF(BL34>=BK34,BL34+BL34*BM34,0)
                                if ($balance_left1>=$monthly_inflation_rate_post_retirement){
                                    $eom_value1 =$balance_left1+$balance_left1*$monthly_return_during_distribution_phase1;
                                }else{
                                    $eom_value1 = 0;
                                }
                                 //Option 1 End

                                 //Option 2
                                //Retirement Corpus
                                if ($m==1){
                                     $retirement_corpus2 = $total_retirement_corpus2;
                                }else{
                                     $retirement_corpus2 = $eom_value2;
                                }

                                //Monthly Annuity IF(BH34>=BG34,BF58,0)
                                if ($total_months>=$m && $m==1){
                                    $monthly_annuity2 = $projected_monthly_expenses_at_retirement;
                                }elseif ($total_months>=$m && $m>1){
                                    $monthly_annuity2 = $monthly_annuity2+$monthly_annuity2*$monthly_inflation_rate_post_retirement;
                                }
                                else{
                                    $monthly_annuity2 = 0;
                                }

                                $year_monthly_annuity2 += $monthly_annuity2;

                                $balance_left2 = $retirement_corpus2-$monthly_annuity2;
                                //$monthly_return_during_distribution_phase1
                                //EOM Value IF(BL34>=BK34,BL34+BL34*BM34,0)
                                if ($balance_left2>=$monthly_inflation_rate_post_retirement){
                                    $eom_value2 =$balance_left2+$balance_left2*$monthly_return_during_distribution_phase2;
                                }else{
                                    $eom_value2 = 0;
                                }
                                 //Option 2 End


                            @endphp

                            @if($m%12==0)
                                @php
                                    $age++;
                                @endphp
                                <tr>
                                    <td>{{$age}}</td>
                                    <td>₹ {{custome_money_format($year_monthly_annuity1/12)}}</td>
                                    <td>₹ {{custome_money_format($eom_value1)}}</td>
                                    @if($accumulation_phase_interest_rate_2>0)
                                    <td>₹ {{custome_money_format($year_monthly_annuity1/12)}}</td>
                                    <td>₹ {{custome_money_format($eom_value2)}}</td>
                                        @endif
                                </tr>
                                @php
                                    $year_monthly_annuity1 = 0;
                                @endphp
                            @endif


                        @endfor



                        </tbody>
                    </table>
                    <p style="text-align: left; margin-top: 20px;">
                        * Monthly annuity will increase every month due to effect of inflation. For simplicity, the amount shown above under monthly annuity is the monthly average of total annuity payments received during the year. <br><br>
                        * The Annuity is received on the 1st day of each month starting the 1st month.
                    </p>
                    @include('frontend.calculators.suggested.output')
                    <p style="text-align: left;">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>
                    <a href="javascript:history.back()" class="btn btn-primary btn-round save_only" ><i class="fa fa-angle-left"></i> Back</a>
                    @if($calculator_permissions['is_save'])
                        <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($calculator_permissions['is_download'])
                        <a href="{{route('frontend.retirementPlanningOutputDownloadPdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round">Download / Print</a>
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
    </section>

    @include('frontend.calculators.modal')

@endsection
