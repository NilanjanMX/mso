@extends('layouts.frontend')

@php
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['include_performance'] = old('include_performance');
    if(!$data['include_performance']){
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type');
    if(!$data['suggestedlist_type']){
        $data['suggestedlist_type'] = $suggestedlist_type;
    }

@endphp

@section('js_after')

    <script type="text/javascript">
        
         @if(isset($data['suggest']))
            @if($data['suggest'] == "1")
                $(document).ready(function() {
                    setTimeout(function(){
                        $('.include-performance-container').show(500);
                    },500)


                    @if($data['suggestedlist_type'] == "createlist")
                        $('.customlist-suggested-scheme-container').css('display','none');
                        $('.categorylist-suggested-scheme-container').css('display','none');
                        $('.createlist-suggested-scheme-container').css('display','block');
                    @elseif($data['suggestedlist_type'] == "customlist")
                        $('.customlist-suggested-scheme-container').css('display','block');
                        $('.categorylist-suggested-scheme-container').css('display','none');
                        $('.createlist-suggested-scheme-container').css('display','none');
                    @elseif($data['suggestedlist_type'] == "categorylist")
                        $('.customlist-suggested-scheme-container').css('display','none');
                        $('.categorylist-suggested-scheme-container').css('display','block');
                        $('.createlist-suggested-scheme-container').css('display','none');
                    @endif
                });
            @endif
        @endif
    </script>
    <script>

        var goal_calculator_index = 0;
        var key_value;
        var key_value_dev;
        var g_debt;
        var g_hybrid;
        var g_equity;
        var ge_message = {
            "amount": "Please enter a value between 100 and 9,99,99,99,999.",
            "inflation": "Please enter a value between 0.1 and 15%",
            "period": "Please enter no more than 2 characters",
            "aror_debt": "Please enter a value between 0.00 - 9.00%.",
            "aror_hybrid": "Please enter a value between 0.00 - 12.00%.",
            "aror_equity": "Please enter a value between 0.00 - 15.00%.",
            "total": "Total 100.",
            "purpose_of_investment": "Please enter a value between 0 to 30.",
            "period_less": "Choose period less than goal Period.",
            "not_hundred": "Total 100.",
        }
        
        function changeCostType(type,index){
            // cost_type
            if(type == 2){
                document.getElementById('inflation_view_'+index).style.display = "flex";
                document.getElementById('cost_type_amount_label_'+index).innerHTML = "Current Cost";
            }else{
                document.getElementById('inflation_view_'+index).style.display = "none";
                document.getElementById('cost_type_amount_label_'+index).innerHTML = "Future Cost";
            }
            // inflation
        }

        function changeLimitedPeriodMonthlySIP(index){
            var debt = document.getElementById("limited_period_monthly_sip_debt_"+index).value;
            var hybrid = document.getElementById("limited_period_monthly_sip_hybrid_"+index).value;
            var equity = document.getElementById("limited_period_monthly_sip_equity_"+index).value;

            if(debt){
                debt = parseFloat(debt);
            }else{
                debt = 0;
            }
            if(hybrid){
                hybrid = parseFloat(hybrid);
            }else{
                hybrid = 0;
            }
            if(equity){
                equity = parseFloat(equity);
            }else{
                equity = 0;
            }

            var total_value = debt+hybrid+equity;
            if(total_value != 100){
                document.getElementById("limited_period_monthly_sip_total_error_"+index).innerHTML = ge_message.total;
                document.getElementById("limited_period_monthly_sip_total_"+index).value = total_value;
            }else{
                document.getElementById("limited_period_monthly_sip_total_error_"+index).innerHTML = "";
                document.getElementById("limited_period_monthly_sip_total_"+index).value = total_value;
            }
        }

        function changeLumpsum(index){
            var debt = document.getElementById("lumpsum_debt_"+index).value;
            var hybrid = document.getElementById("lumpsum_hybrid_"+index).value;
            var equity = document.getElementById("lumpsum_equity_"+index).value;

            if(debt){
                debt = parseFloat(debt);
            }else{
                debt = 0;
            }
            if(hybrid){
                hybrid = parseFloat(hybrid);
            }else{
                hybrid = 0;
            }
            if(equity){
                equity = parseFloat(equity);
            }else{
                equity = 0;
            }

            var total_value = debt+hybrid+equity;
            if(total_value != 100){
                document.getElementById("lumpsum_total_error_"+index).innerHTML = ge_message.total;
                document.getElementById("lumpsum_total_"+index).value = total_value;
            }else{
                document.getElementById("lumpsum_total_error_"+index).innerHTML = "";
                document.getElementById("lumpsum_total_"+index).value = total_value;
            }
        }

        function changeMonthlySIP(index){
            var debt = document.getElementById("monthly_sip_debt_"+index).value;
            var hybrid = document.getElementById("monthly_sip_hybrid_"+index).value;
            var equity = document.getElementById("monthly_sip_equity_"+index).value;

            if(debt){
                debt = parseFloat(debt);
            }else{
                debt = 0;
            }
            if(hybrid){
                hybrid = parseFloat(hybrid);
            }else{
                hybrid = 0;
            }
            if(equity){
                equity = parseFloat(equity);
            }else{
                equity = 0;
            }

            var total_value = debt+hybrid+equity;
            if(total_value != 100){
                document.getElementById("monthly_sip_total_error_"+index).innerHTML = ge_message.total;
                document.getElementById("monthly_sip_total_"+index).value = total_value;
            }else{
                document.getElementById("monthly_sip_total_error_"+index).innerHTML = "";
                document.getElementById("monthly_sip_total_"+index).value = total_value;
            }
        }

        function changeLumpsumMonthlySIPAmount(index){
            var debt = document.getElementById("lumpsum_monthly_sip_amount_debt_"+index).value;
            var hybrid = document.getElementById("lumpsum_monthly_sip_amount_hybrid_"+index).value;
            var equity = document.getElementById("lumpsum_monthly_sip_amount_equity_"+index).value;

            if(debt){
                debt = parseFloat(debt);
            }else{
                debt = 0;
            }
            if(hybrid){
                hybrid = parseFloat(hybrid);
            }else{
                hybrid = 0;
            }
            if(equity){
                equity = parseFloat(equity);
            }else{
                equity = 0;
            }

            var total_value = debt+hybrid+equity;
            if(total_value != 100){
                document.getElementById("lumpsum_monthly_sip_amount_total_error_"+index).innerHTML = ge_message.total;
                document.getElementById("lumpsum_monthly_sip_amount_total_"+index).value = total_value;
            }else{
                document.getElementById("lumpsum_monthly_sip_amount_total_error_"+index).innerHTML = "";
                document.getElementById("lumpsum_monthly_sip_amount_total_"+index).value = total_value;
            }
        }

        function changeLumpsumMonthlySIP(index){
            var debt = document.getElementById("lumpsum_monthly_sip_debt_"+index).value;
            var hybrid = document.getElementById("lumpsum_monthly_sip_hybrid_"+index).value;
            var equity = document.getElementById("lumpsum_monthly_sip_equity_"+index).value;

            if(debt){
                debt = parseFloat(debt);
            }else{
                debt = 0;
            }
            if(hybrid){
                hybrid = parseFloat(hybrid);
            }else{
                hybrid = 0;
            }
            if(equity){
                equity = parseFloat(equity);
            }else{
                equity = 0;
            }

            var total_value = debt+hybrid+equity;
            if(total_value != 100){
                document.getElementById("lumpsum_monthly_sip_total_error_"+index).innerHTML = ge_message.total;
                document.getElementById("lumpsum_monthly_sip_total_"+index).value = total_value;
            }else{
                document.getElementById("lumpsum_monthly_sip_total_error_"+index).innerHTML = "";
                document.getElementById("lumpsum_monthly_sip_total_"+index).value = total_value;
            }
            
        }

        function addGoalCalculator(){
            var form_list = document.getElementById("goal_calculator_form").querySelectorAll('input[message=text]');
            console.log(form_list.length);
            if(form_list.length < 3){
                var goal_calculator_index1 = goal_calculator_index;
                goal_calculator_index = goal_calculator_index+1;
                var iHtml = `<div id="goal_calculator_view_`+goal_calculator_index+`">
                                <div class="form-group row mt2 mb2" id="goal_calculator_remove_view_`+goal_calculator_index1+`">
                                    <div class="col-sm-12 text-center">
                                        <button type="button" class="btn banner-btn my-3 removeGoalCalculatorBtn" onclick="removeGoalCalculator(`+goal_calculator_index1+`);" style="background-color: #dc3545;border-color: #dc3545;color: #fff !important;"> Remove Goal Calculator</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Purpose of Investment </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="purpose_of_investment[`+goal_calculator_index+`]" id="purpose_of_investment_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('purpose_of_investment',`+goal_calculator_index+`,1);" message="text" datatype="`+goal_calculator_index+`">
                                                <em id="purpose_of_investment_error_`+goal_calculator_index+`" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Current Age</label>
                                            <div class="col-sm-7">
                                                <div class="d-flex align-items-center">
                                                    <input type="text" name="current_age[`+goal_calculator_index+`]" id="current_age_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('current_age',`+goal_calculator_index+`,2);">
                                                </div>
                                                <div class="cal-icon">
                                                    Yrs
                                                </div>
                                                <em id="current_age_error_`+goal_calculator_index+`" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12" style="display: flex;">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0" for="cost_type_1_`+goal_calculator_index+`">Future Cost
                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="cost_type[`+goal_calculator_index+`]" id="cost_type_1_`+goal_calculator_index+`" value="1" onchange="changeCostType(1,`+goal_calculator_index+`)"  checked>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0" for="cost_type_2_`+goal_calculator_index+`">Current Cost
                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="cost_type[`+goal_calculator_index+`]" id="cost_type_2_`+goal_calculator_index+`" value="2"  onchange="changeCostType(2,`+goal_calculator_index+`)" >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label" id="cost_type_amount_label_`+goal_calculator_index+`">Future Cost</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="amount[`+goal_calculator_index+`]" id="amount_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('amount',`+goal_calculator_index+`,3);">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="amount_error_`+goal_calculator_index+`" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4" id="inflation_view_`+goal_calculator_index+`" style="display: none;">
                                        <div class="form-group row"> 
                                            <label class="col-sm-5 col-form-label">Inflation</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="inflation[`+goal_calculator_index+`]" id="inflation_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('inflation',`+goal_calculator_index+`,4);">
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                                <em id="inflation_error_`+goal_calculator_index+`" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Period</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="period[`+goal_calculator_index+`]" id="period_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('period',`+goal_calculator_index+`,5);">
                                                <div class="cal-icon">
                                                    Yrs
                                                </div>
                                                <em id="period_error_`+goal_calculator_index+`" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted titleBlueUnderline"><strong>Assumed Rate of Return:</strong></h6>
                                    </div>
                                </div>

                                <div class="form-group row" style="padding-left: 20px;">
                                    <label class="col-sm-1 col-form-label">Debt</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="aror_debt[`+goal_calculator_index+`]" id="aror_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('aror_debt',`+goal_calculator_index+`,6);">
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        <em id="aror_debt_error_`+goal_calculator_index+`" class="error"></em>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Hybrid</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="aror_hybrid[`+goal_calculator_index+`]" id="aror_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('aror_hybrid',`+goal_calculator_index+`,7);">
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        <em id="aror_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                    </div>
                                    <label class="col-sm-1 col-form-label">Equity</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="aror_equity[`+goal_calculator_index+`]" id="aror_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="checkInput('aror_equity',`+goal_calculator_index+`,8);">
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        <em id="aror_equity_error_`+goal_calculator_index+`" class="error"></em>
                                    </div>
                                </div>

                                <div>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h6 class="text-muted titleBlueUnderline"><strong>Select Investment Mode:</strong></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="hidden" name="lumpsum_investment[`+goal_calculator_index+`]" id="lumpsum_investment_`+goal_calculator_index+`" value="1">
                                            <input id="lumpsum_investment_mode_`+goal_calculator_index+`" type="checkbox" name="lumpsum_investment_mode[`+goal_calculator_index+`]" value="1" checked="checked" onchange="changeLumpsumInvestmentMode(`+goal_calculator_index+`);"> Lumpsum
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group row" id="lumpsum_investment_mode_view_`+goal_calculator_index+`"  style="padding-left: 20px;">
                                        <label class="col-sm-1 col-form-label">Debt</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="lumpsum_debt[`+goal_calculator_index+`]" id="lumpsum_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsum(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="lumpsum_debt_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Hybrid</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="lumpsum_hybrid[`+goal_calculator_index+`]" id="lumpsum_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsum(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="lumpsum_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Equity</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="lumpsum_equity[`+goal_calculator_index+`]" id="lumpsum_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsum(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="lumpsum_equity_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Total</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="lumpsum_total[`+goal_calculator_index+`]" id="lumpsum_total_`+goal_calculator_index+`" class="form-control" value="" readonly="readonly" onkeyup="changeLumpsum(`+goal_calculator_index+`);">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="lumpsum_total_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="hidden" name="monthly_sip_investment[`+goal_calculator_index+`]" id="monthly_sip_investment_`+goal_calculator_index+`" value="0">
                                            <input id="monthly_sip_investment_mode_`+goal_calculator_index+`" type="checkbox" name="monthly_sip_investment_mode[`+goal_calculator_index+`]" value="2" onchange="changeMonthlySIPInvestmentMode(`+goal_calculator_index+`);"> Monthly SIP
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group row"style="padding-left: 20px;display: none;" id="monthly_sip_investment_mode_view_`+goal_calculator_index+`">
                                        <label class="col-sm-1 col-form-label">Debt</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="monthly_sip_debt[`+goal_calculator_index+`]" id="monthly_sip_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="monthly_sip_debt_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Hybrid</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="monthly_sip_hybrid[`+goal_calculator_index+`]" id="monthly_sip_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="monthly_sip_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Equity</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="monthly_sip_equity[`+goal_calculator_index+`]" id="monthly_sip_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="monthly_sip_equity_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Total</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="monthly_sip_total[`+goal_calculator_index+`]" id="monthly_sip_total_`+goal_calculator_index+`" class="form-control" value="" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="monthly_sip_total_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="hidden" name="limited_period_monthly_investment[`+goal_calculator_index+`]" id="limited_period_monthly_investment_`+goal_calculator_index+`" value="0">
                                            <input id="limited_period_monthly_investment_mode_`+goal_calculator_index+`" type="checkbox" name="limited_period_monthly_investment_mode[`+goal_calculator_index+`]" value="3" onchange="changeLimitedPeriodMonthlySIPInvestmentMode(`+goal_calculator_index+`);"> Limited Period Monthly SIP
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-group row" style="padding-left: 20px;display: none;" id="limited_period_monthly_investment_mode_period_view_`+goal_calculator_index+`">
                                        <label class="col-sm-2 col-form-label">1. SIP Period</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_period_1[`+goal_calculator_index+`]" id="limited_period_monthly_sip_period_1_`+goal_calculator_index+`" class="form-control" value="">
                                            <div class="cal-icon">
                                                Yr
                                            </div>
                                            <em id="limited_period_monthly_sip_period_1_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-2 col-form-label">2. SIP Period</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_period_2[`+goal_calculator_index+`]" id="limited_period_monthly_sip_period_2_`+goal_calculator_index+`" class="form-control" value="">
                                            <div class="cal-icon">
                                               Yr
                                            </div>
                                            <em id="limited_period_monthly_sip_period_2_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-2 col-form-label">3. SIP Period</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_period_3[`+goal_calculator_index+`]" id="limited_period_monthly_sip_period_3_`+goal_calculator_index+`" class="form-control" value="">
                                            <div class="cal-icon">
                                                Yr
                                            </div>
                                            <em id="limited_period_monthly_sip_period_3_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="padding-left: 20px;display: none;" id="limited_period_monthly_investment_mode_view_`+goal_calculator_index+`">
                                        <label class="col-sm-1 col-form-label">Debt</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_debt[`+goal_calculator_index+`]" id="limited_period_monthly_sip_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLimitedPeriodMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="limited_period_monthly_sip_debt_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Hybrid</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_hybrid[`+goal_calculator_index+`]" id="limited_period_monthly_sip_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLimitedPeriodMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="limited_period_monthly_sip_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Equity</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_equity[`+goal_calculator_index+`]" id="limited_period_monthly_sip_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLimitedPeriodMonthlySIP(`+goal_calculator_index+`);" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="limited_period_monthly_sip_equity_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                        <label class="col-sm-1 col-form-label">Total</label>
                                        <div class="col-sm-2">
                                            <input type="text" name="limited_period_monthly_sip_total[`+goal_calculator_index+`]" id="limited_period_monthly_sip_total_`+goal_calculator_index+`" class="form-control" value="" readonly="readonly">
                                            <div class="cal-icon">
                                                %
                                            </div>
                                            <em id="limited_period_monthly_sip_total_error_`+goal_calculator_index+`" class="error"></em>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="hidden" name="lumpsum_monthly_sip_investment[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_investment_`+goal_calculator_index+`" value="0">
                                            <input id="lumpsum_monthly_sip_investment_mode_`+goal_calculator_index+`" type="checkbox" name="lumpsum_monthly_sip_investment_mode[]" value="4" onchange="changeLumpsumMonthlySIPInvestmentMode(`+goal_calculator_index+`);"> Lumpsum+Monthly SIP
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div style="padding-left: 20px;display: none;" id="lumpsum_monthly_sip_investment_mode_view_`+goal_calculator_index+`">
                                        <div class="form-group row">
                                            <label class="col-sm-12 col-form-label">
                                                <input id="lumpsum_monthly_sip_1_`+goal_calculator_index+`" type="radio" name="lumpsum_monthly_sip[`+goal_calculator_index+`]" value="1" checked="checked" onchange="changeLumpsumAmount(`+goal_calculator_index+`)"> Lumpsum Amount
                                            </label>
                                        </div>

                                        <div style="padding-left: 20px;" id="lumpsum_monthly_sip_lumpsum_view_`+goal_calculator_index+`">

                                            <div class="form-group row">
                                                <label class="col-sm-1 col-form-label">Debt</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_amount_debt[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_amount_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIPAmount(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,1);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_amount_debt_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Hybrid</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_amount_hybrid[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_amount_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIPAmount(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,1);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_amount_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Equity</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_amount_equity[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_amount_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIPAmount(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,1);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_amount_equity_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Total</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_amount_total[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_amount_total_`+goal_calculator_index+`" class="form-control" value="" readonly="readonly" >
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_amount_total_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Amount</label>
                                                <div class="col-sm-4" id="lumpsum_monthly_sip_lumpsum_amount_view_`+goal_calculator_index+`">
                                                    <input type="text" name="lumpsum_monthly_sip_lumpsum_amount[`+goal_calculator_index+`]" class="form-control" id="lumpsum_monthly_sip_lumpsum_amount_`+goal_calculator_index+`" value="" onkeyup="maxFunction(`+goal_calculator_index+`,1); changeAmount(`+goal_calculator_index+`,1);">
                                                    <div class="cal-icon">
                                                        ₹
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_lumpsum_amount_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <div class="col-sm-4" style="padding-top: 9px;" id="lumpsum_monthly_sip_lumpsum_amount_exceed_`+goal_calculator_index+`">
                                                    
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <label class="col-sm-12 col-form-label">
                                                <input id="lumpsum_monthly_sip_2_`+goal_calculator_index+`" type="radio" name="lumpsum_monthly_sip[`+goal_calculator_index+`]" value="2" onchange="changeMonthlySIPAmount(`+goal_calculator_index+`);"> Monthly SIP Amount
                                            </label>
                                        </div>
                                        <div style="padding-left: 20px;" id="lumpsum_monthly_sip_view_`+goal_calculator_index+`">
                                            <div class="form-group row">
                                                <label class="col-sm-1 col-form-label">Debt</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_debt[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_debt_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIP(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,2);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_debt_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Hybrid</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_hybrid[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_hybrid_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIP(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,2);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_hybrid_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Equity</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_equity[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_equity_`+goal_calculator_index+`" class="form-control" value="" onkeyup="changeLumpsumMonthlySIP(`+goal_calculator_index+`); maxFunction(`+goal_calculator_index+`,2);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_equity_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Total</label>
                                                <div class="col-sm-2">
                                                    <input type="text" name="lumpsum_monthly_sip_total[`+goal_calculator_index+`]" id="lumpsum_monthly_sip_total_`+goal_calculator_index+`" class="form-control" value="" readonly="readonly">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_total_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Amount</label>
                                                <div class="col-sm-4" id="lumpsum_monthly_sip_amount_view_`+goal_calculator_index+`">
                                                    <input type="text" name="lumpsum_monthly_sip_amount[`+goal_calculator_index+`]" class="form-control" id="lumpsum_monthly_sip_amount_`+goal_calculator_index+`" value="" onkeyup="maxFunction(`+goal_calculator_index+`,2); changeAmount(`+goal_calculator_index+`,2);" readonly="readonly">
                                                    <div class="cal-icon">
                                                        ₹
                                                    </div>
                                                    <em id="lumpsum_monthly_sip_amount_error_`+goal_calculator_index+`" class="error"></em>
                                                </div>
                                                <div class="col-sm-4" style="padding-top: 9px;" id="lumpsum_monthly_sip_amount_exceed_`+goal_calculator_index+`">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                $("#goal_calculator_view").append(iHtml);
            }else{
                alert("Max 3");
            }
                
        }

        function removeGoalCalculator(index){
            document.getElementById("goal_calculator_view_"+index).remove();
            document.getElementById("goal_calculator_remove_view_"+index).remove();
        }

        function isNumeric(val) {
            return /^-?\d+$/.test(val);
        }

        function changeLumpsumInvestmentMode(index) {
            if( $("#lumpsum_investment_mode_"+index).is(':checked') ){
                document.getElementById('lumpsum_investment_mode_view_'+index).style.display = "flex";
                document.getElementById('lumpsum_investment_'+index).value = 1;
            }else {
                document.getElementById('lumpsum_investment_mode_view_'+index).style.display = "none";
                document.getElementById('lumpsum_investment_'+index).value = 0;
            }
        }

        function changeMonthlySIPInvestmentMode(index) {
            if( $("#monthly_sip_investment_mode_"+index).is(':checked') ){
                document.getElementById('monthly_sip_investment_mode_view_'+index).style.display = "flex";
                document.getElementById('monthly_sip_investment_'+index).value = 1;
            }else {
                document.getElementById('monthly_sip_investment_mode_view_'+index).style.display = "none";
                document.getElementById('monthly_sip_investment_'+index).value = 0;
            }
        }

        function changeLimitedPeriodMonthlySIPInvestmentMode(index) {
            if( $("#limited_period_monthly_investment_mode_"+index).is(':checked') ){
                document.getElementById('limited_period_monthly_investment_mode_period_view_'+index).style.display = "flex";
                document.getElementById('limited_period_monthly_investment_mode_view_'+index).style.display = "flex";
                document.getElementById('limited_period_monthly_investment_'+index).value = 1;
            }else {
                document.getElementById('limited_period_monthly_investment_mode_period_view_'+index).style.display = "none";
                document.getElementById('limited_period_monthly_investment_mode_view_'+index).style.display = "none";
                document.getElementById('limited_period_monthly_investment_'+index).value = 0;
            }
        }

        function changeLumpsumMonthlySIPInvestmentMode(index) {
            if( $("#lumpsum_monthly_sip_investment_mode_"+index).is(':checked') ){
                document.getElementById('lumpsum_monthly_sip_investment_mode_view_'+index).style.display = "block";
                document.getElementById('lumpsum_monthly_sip_investment_'+index).value = 1;
            }else {
                document.getElementById('lumpsum_monthly_sip_investment_mode_view_'+index).style.display = "none";
                document.getElementById('lumpsum_monthly_sip_investment_'+index).value = 0;
            }
        }

        function changeLumpsumAmount(index) {
            console.log($("#lumpsum_monthly_sip_1_"+index).is(':checked'));
            $('#lumpsum_monthly_sip_lumpsum_amount_'+index).prop("readonly", false);
            $('#lumpsum_monthly_sip_amount_'+index).prop("readonly", true);
            // if( $("#lumpsum_monthly_sip_1_"+index).is(':checked') ){
                
            // }else {
            //     $('#lumpsum_monthly_sip_lumpsum_amount_'+index).prop("readonly", false);
            //     $('#lumpsum_monthly_sip_amount_'+index).prop("readonly", true);
            // }
        }

        function changeMonthlySIPAmount(index) {
            console.log($("#lumpsum_monthly_sip_2_"+index).is(':checked'));
            $('#lumpsum_monthly_sip_lumpsum_amount_'+index).prop("readonly", true);
            $('#lumpsum_monthly_sip_amount_'+index).prop("readonly", false);
            // if( $("#lumpsum_monthly_sip_2_"+index).is(':checked') ){
                
            // }else {
            //     $('#lumpsum_monthly_sip_lumpsum_amount_'+index).prop("readonly", true);
            //     $('#lumpsum_monthly_sip_amount_'+index).prop("readonly", false);
            // }
        }

        function checkInput(key_name,index,type){
            key_value = document.getElementById(key_name+"_"+index).value;
            if(type == 1){
                if(key_value.length <= 30){
                    document.getElementById(key_name+"_error_"+index).innerHTML = "";
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0 to 30";
                }                
            }else if(type == 2){
                
            }else if(type == 3){
                if(isNumeric(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    if(key_value_dev >= 100 && key_value_dev <=9999999999){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 100 and 9,99,99,99,999.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 100 and 9,99,99,99,999.";
                }
            }else if(type == 4){
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    console.log(key_value_dev);
                    if(key_value_dev >= 0.1 && key_value_dev <=15){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.1 and 15%.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.1 and 15%.";
                }
            }else if(type == 5){
                if(isNumeric(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    console.log(key_value_dev);
                    if(key_value_dev >= 0 && key_value_dev <=99){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter no more than 2 characters.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter no more than 2 characters.";
                }
            }else if(type == 6){
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    console.log(key_value_dev);
                    if(key_value_dev >= 0 && key_value_dev <=9){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 9.00%.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 9.00%.";
                }
                checkActiveInactive(key_name,index,type);
            }else if(type == 7){
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    console.log(key_value_dev);
                    if(key_value_dev >= 0 && key_value_dev <=12){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 12.00%.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 12.00%.";
                }
                checkActiveInactive(key_name,index,type);
            }else if(type == 8){
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    console.log(key_value_dev);
                    if(key_value_dev >= 0 && key_value_dev <=15){
                        document.getElementById(key_name+"_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 15.00%.";
                    }                    
                }else{
                    document.getElementById(key_name+"_error_"+index).innerHTML = "Please enter a value between 0.00 - 15.00%.";
                }
                checkActiveInactive(key_name,index,type);
            }else {

            }
        }

        function formSubmit(){
            //
            var form_list = document.getElementById("goal_calculator_form").querySelectorAll('input[message=text]');

            var array_data = [];
            form_list.forEach(function(val){
                array_data.push($(val).attr('datatype'));
            });

            var flag = true;

            array_data.forEach(function(index){

                key_value = document.getElementById("purpose_of_investment_"+index).value;
                if(key_value.length <= 30){
                    document.getElementById("purpose_of_investment_error_"+index).innerHTML = "";
                }else{
                    document.getElementById("purpose_of_investment_error_"+index).innerHTML = ge_message.purpose_of_investment;
                    flag = false;
                }

                key_value = document.getElementById("amount_"+index).value;
                if(isNumeric(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    if(key_value_dev >= 100 && key_value_dev <=9999999999){
                        document.getElementById("amount_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("amount_error_"+index).innerHTML = ge_message.amount;
                        flag = false;
                    }                    
                }else{
                    document.getElementById("amount_error_"+index).innerHTML = ge_message.amount;
                    flag = false;
                }
                
                // cost_type_2_0
                if( $("#cost_type_1_"+index).is(':checked') ){
                    document.getElementById("inflation_error_"+index).innerHTML = "";
                }else if ( $("#cost_type_1_"+index).is(':checked') ) {
                    key_value = document.getElementById("inflation_"+index).value;
                    if(Number(key_value) ){
                        key_value_dev = parseFloat(key_value);
                        if(key_value_dev >= 0.1 && key_value_dev <=15){
                            document.getElementById("inflation_error_"+index).innerHTML = "";
                        }else{
                            document.getElementById("inflation_error_"+index).innerHTML = ge_message.inflation;
                            flag = false;
                        }                    
                    }else{
                        document.getElementById("inflation_error_"+index).innerHTML = ge_message.inflation;
                        flag = false;
                    }
                }
                    

                key_value = document.getElementById("period_"+index).value;
                if(isNumeric(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    if(key_value_dev >= 0 && key_value_dev <=99){
                        document.getElementById("period_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("period_error_"+index).innerHTML = ge_message.period;
                        flag = false;
                    }                    
                }else{
                    document.getElementById("period_error_"+index).innerHTML = ge_message.period;
                    flag = false;
                }

                var aror_debt = 0;
                var aror_hybrid = 0;
                var aror_equity = 0;
                key_value = document.getElementById("aror_debt_"+index).value;
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    aror_debt = (key_value_dev)?key_value_dev:0;
                    if(key_value_dev >= 0 && key_value_dev <=9){
                        document.getElementById("aror_debt_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("aror_debt_error_"+index).innerHTML = ge_message.aror_debt;
                        flag = false;
                    }                    
                }else{
                    document.getElementById("aror_debt_error_"+index).innerHTML = "";
                }

                key_value = document.getElementById("aror_hybrid_"+index).value;
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    aror_hybrid = (key_value_dev)?key_value_dev:0;
                    if(key_value_dev >= 0 && key_value_dev <=12){
                        document.getElementById("aror_hybrid_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("aror_hybrid_error_"+index).innerHTML = ge_message.aror_hybrid;
                        flag = false;
                    }                    
                }else{
                    document.getElementById("aror_hybrid_error_"+index).innerHTML = "";
                }

                key_value = document.getElementById("aror_equity_"+index).value;
                if(Number(key_value) ){
                    key_value_dev = parseFloat(key_value);
                    aror_equity = (key_value_dev)?key_value_dev:0;
                    if(key_value_dev >= 0 && key_value_dev <=15){
                        document.getElementById("aror_equity_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("aror_equity_error_"+index).innerHTML = ge_message.aror_hybrid;
                        flag = false;
                    }                    
                }else{
                    document.getElementById("aror_equity_error_"+index).innerHTML = "";
                }



                if( $("#lumpsum_investment_mode_"+index).is(':checked') ){
                    g_debt = document.getElementById("lumpsum_debt_"+index).value;
                    g_hybrid = document.getElementById("lumpsum_hybrid_"+index).value;
                    g_equity = document.getElementById("lumpsum_equity_"+index).value;

                    g_debt = (parseFloat(g_debt))?parseFloat(g_debt):0;
                    g_hybrid = (parseFloat(g_hybrid))?parseFloat(g_hybrid):0;
                    g_equity = (parseFloat(g_equity))?parseFloat(g_equity):0;

                    key_value = g_debt+g_hybrid+g_equity;

                    if(key_value == 100){
                        document.getElementById("lumpsum_total_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("lumpsum_total_error_"+index).innerHTML = ge_message.not_hundred;
                        flag = false;
                    }
                    
                }

                if( $("#monthly_sip_investment_mode_"+index).is(':checked') ){
                    g_debt = document.getElementById("monthly_sip_debt_"+index).value;
                    g_hybrid = document.getElementById("monthly_sip_hybrid_"+index).value;
                    g_equity = document.getElementById("monthly_sip_equity_"+index).value;

                    g_debt = (parseFloat(g_debt))?parseFloat(g_debt):0;
                    g_hybrid = (parseFloat(g_hybrid))?parseFloat(g_hybrid):0;
                    g_equity = (parseFloat(g_equity))?parseFloat(g_equity):0;

                    key_value = g_debt+g_hybrid+g_equity;

                    if(key_value == 100){
                        document.getElementById("monthly_sip_total_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("monthly_sip_total_error_"+index).innerHTML = ge_message.not_hundred;
                        flag = false;
                    }
                }

                if( $("#limited_period_monthly_investment_mode_"+index).is(':checked') ){
                    g_debt = document.getElementById("limited_period_monthly_sip_debt_"+index).value;
                    g_hybrid = document.getElementById("limited_period_monthly_sip_hybrid_"+index).value;
                    g_equity = document.getElementById("limited_period_monthly_sip_equity_"+index).value;
                    
                    g_debt = (parseFloat(g_debt))?parseFloat(g_debt):0;
                    g_hybrid = (parseFloat(g_hybrid))?parseFloat(g_hybrid):0;
                    g_equity = (parseFloat(g_equity))?parseFloat(g_equity):0;

                    key_value = g_debt+g_hybrid+g_equity;

                    if(key_value == 100){
                        document.getElementById("limited_period_monthly_sip_total_"+index).innerHTML = "";
                    }else{
                        document.getElementById("limited_period_monthly_sip_total_"+index).innerHTML = ge_message.not_hundred;
                        flag = false;
                    }
                    var period;
                    key_value = document.getElementById("limited_period_monthly_sip_period_1_"+index).value;
                    if(key_value){
                        period = document.getElementById("period_"+index).value;
                        if(parseFloat(key_value) < parseFloat(period)){
                            document.getElementById("limited_period_monthly_sip_period_1_error_"+index).innerHTML = "";
                        }else{
                            document.getElementById("limited_period_monthly_sip_period_1_error_"+index).innerHTML = ge_message.period_less;
                            flag = false;
                        }
                    }else{
                        document.getElementById("limited_period_monthly_sip_period_1_error_"+index).innerHTML = "";
                    }

                    key_value = document.getElementById("limited_period_monthly_sip_period_2_"+index).value;
                    if(key_value){
                        period = document.getElementById("period_"+index).value;
                        if(parseFloat(key_value) < parseFloat(period)){
                            document.getElementById("limited_period_monthly_sip_period_2_error_"+index).innerHTML = "";
                        }else{
                            document.getElementById("limited_period_monthly_sip_period_2_error_"+index).innerHTML = ge_message.period_less;
                            flag = false;
                        }
                    }else{
                        document.getElementById("limited_period_monthly_sip_period_2_error_"+index).innerHTML = "";
                    }

                    key_value = document.getElementById("limited_period_monthly_sip_period_3_"+index).value;
                    if(key_value){
                        period = document.getElementById("period_"+index).value;
                        if(parseFloat(key_value) < parseFloat(period)){
                            document.getElementById("limited_period_monthly_sip_period_3_error_"+index).innerHTML = "";
                        }else{
                            document.getElementById("limited_period_monthly_sip_period_3_error_"+index).innerHTML = ge_message.period_less;
                            flag = false;
                        }
                    }else{
                        document.getElementById("limited_period_monthly_sip_period_3_error_"+index).innerHTML = "";
                    }

                }

                if( $("#lumpsum_monthly_sip_investment_mode_"+index).is(':checked') ){

                    document.getElementById("lumpsum_monthly_sip_lumpsum_amount_error_"+index).innerHTML = "";
                    document.getElementById("lumpsum_monthly_sip_amount_error_"+index).innerHTML = "";
                    document.getElementById("lumpsum_monthly_sip_amount_total_error_"+index).innerHTML = "";
                    document.getElementById("lumpsum_monthly_sip_total_error_"+index).innerHTML = "";

                    g_debt = document.getElementById("lumpsum_monthly_sip_amount_debt_"+index).value;
                    g_hybrid = document.getElementById("lumpsum_monthly_sip_amount_hybrid_"+index).value;
                    g_equity = document.getElementById("lumpsum_monthly_sip_amount_equity_"+index).value;
                
                    g_debt = (parseFloat(g_debt))?parseFloat(g_debt):0;
                    g_hybrid = (parseFloat(g_hybrid))?parseFloat(g_hybrid):0;
                    g_equity = (parseFloat(g_equity))?parseFloat(g_equity):0;

                    key_value = g_debt+g_hybrid+g_equity;

                    if(key_value == 100){
                        document.getElementById("lumpsum_monthly_sip_amount_total_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("lumpsum_monthly_sip_amount_total_error_"+index).innerHTML = ge_message.not_hundred;
                        flag = false;
                    }

                    g_debt = document.getElementById("lumpsum_monthly_sip_debt_"+index).value;
                    g_hybrid = document.getElementById("lumpsum_monthly_sip_hybrid_"+index).value;
                    g_equity = document.getElementById("lumpsum_monthly_sip_equity_"+index).value;
                
                    g_debt = (parseFloat(g_debt))?parseFloat(g_debt):0;
                    g_hybrid = (parseFloat(g_hybrid))?parseFloat(g_hybrid):0;
                    g_equity = (parseFloat(g_equity))?parseFloat(g_equity):0;

                    key_value = g_debt+g_hybrid+g_equity;

                    if(key_value == 100){
                        document.getElementById("lumpsum_monthly_sip_total_error_"+index).innerHTML = "";
                    }else{
                        document.getElementById("lumpsum_monthly_sip_total_error_"+index).innerHTML = ge_message.not_hundred;
                        flag = false;
                    }


                    if( document.getElementById("lumpsum_monthly_sip_1_"+index).checked){
                        key_value = document.getElementById("lumpsum_monthly_sip_lumpsum_amount_"+index).value;
                        if(key_value){
                            document.getElementById("lumpsum_monthly_sip_lumpsum_amount_error_"+index).innerHTML = "";
                        }else{
                            document.getElementById("lumpsum_monthly_sip_lumpsum_amount_error_"+index).innerHTML = ge_message.amount;
                            flag = false;
                        }
                    }

                    if( document.getElementById("lumpsum_monthly_sip_2_"+index).checked){
                        key_value = document.getElementById("lumpsum_monthly_sip_amount_"+index).value;
                        if(key_value){
                            document.getElementById("lumpsum_monthly_sip_amount_"+index).innerHTML = "";
                        }else{
                            document.getElementById("lumpsum_monthly_sip_amount_error_"+index).innerHTML = ge_message.amount;
                            flag = false;
                        }
                    }
                }



            });
            return flag;
        }

        function maxFunction(index,type){

            if(type == 1){
                var lmsa_debt = document.getElementById("lumpsum_monthly_sip_amount_debt_"+index).value;
                var lmsa_hybrid = document.getElementById("lumpsum_monthly_sip_amount_hybrid_"+index).value;
                var lmsa_equity = document.getElementById("lumpsum_monthly_sip_amount_equity_"+index).value;
                console.log(lmsa_debt,lmsa_hybrid,lmsa_equity);
                var aror_debt = document.getElementById("aror_debt_"+index).value;
                var aror_hybrid = document.getElementById("aror_hybrid_"+index).value;
                var aror_equity = document.getElementById("aror_equity_"+index).value;

                var amount = document.getElementById("amount_"+index).value;
                var inflation = document.getElementById("inflation_"+index).value;
                var period = document.getElementById("period_"+index).value;

                amount = (parseFloat(amount))?parseFloat(amount):0;
                inflation = (parseFloat(inflation))?parseFloat(inflation):0;
                period = (parseFloat(period))?parseFloat(period):0;

                if( document.getElementById("cost_type_2_"+index).checked){
                    amount = amount*(Math.pow((1+inflation/100),period));
                }

                lmsa_debt = (parseFloat(lmsa_debt))?parseFloat(lmsa_debt):0;
                lmsa_hybrid = (parseFloat(lmsa_hybrid))?parseFloat(lmsa_hybrid):0;
                lmsa_equity = (parseFloat(lmsa_equity))?parseFloat(lmsa_equity):0;

                aror_debt = (parseFloat(aror_debt))?parseFloat(aror_debt):0;
                aror_hybrid = (parseFloat(aror_hybrid))?parseFloat(aror_hybrid):0;
                aror_equity = (parseFloat(aror_equity))?parseFloat(aror_equity):0;

                console.log("amount : "+amount);
                if(amount && period){
                    var combo_ror = aror_debt*lmsa_debt/100+aror_hybrid*lmsa_hybrid/100+aror_equity*lmsa_equity/100;

                    var combo_amount = amount/(Math.pow((1+combo_ror/100),period));

                    document.getElementById("lumpsum_monthly_sip_lumpsum_amount_exceed_"+index).innerHTML = "Cannot Exceed Rs. <span>"+Number(combo_amount).toFixed(0)+"</span>";

                
                    console.log("combo_ror : "+combo_ror);
                    console.log("combo_amount : "+combo_amount);
                }else{
                    document.getElementById("lumpsum_monthly_sip_lumpsum_amount_exceed_"+index).innerHTML = "";
                }
            }else{
                var lmsa_debt = document.getElementById("lumpsum_monthly_sip_debt_"+index).value;
                var lmsa_hybrid = document.getElementById("lumpsum_monthly_sip_hybrid_"+index).value;
                var lmsa_equity = document.getElementById("lumpsum_monthly_sip_equity_"+index).value;
                console.log(lmsa_debt,lmsa_hybrid,lmsa_equity);
                var aror_debt = document.getElementById("aror_debt_"+index).value;
                var aror_hybrid = document.getElementById("aror_hybrid_"+index).value;
                var aror_equity = document.getElementById("aror_equity_"+index).value;

                var amount = document.getElementById("amount_"+index).value;
                var inflation = document.getElementById("inflation_"+index).value;
                var period = document.getElementById("period_"+index).value;
                
                if( document.getElementById("cost_type_2_"+index).checked){
                    amount = amount*(Math.pow((1+inflation/100),period));
                }

                amount = (parseFloat(amount))?parseFloat(amount):0;
                inflation = (parseFloat(inflation))?parseFloat(inflation):0;
                period = (parseFloat(period))?parseFloat(period):0;

                lmsa_debt = (parseFloat(lmsa_debt))?parseFloat(lmsa_debt):0;
                lmsa_hybrid = (parseFloat(lmsa_hybrid))?parseFloat(lmsa_hybrid):0;
                lmsa_equity = (parseFloat(lmsa_equity))?parseFloat(lmsa_equity):0;

                aror_debt = (parseFloat(aror_debt))?parseFloat(aror_debt):0;
                aror_hybrid = (parseFloat(aror_hybrid))?parseFloat(aror_hybrid):0;
                aror_equity = (parseFloat(aror_equity))?parseFloat(aror_equity):0;

                console.log("amount : "+amount);
                if(amount && period){
                    var combo_ror = Math.pow((1+(aror_debt*lmsa_debt/100+aror_hybrid*lmsa_hybrid/100+aror_equity*lmsa_equity/100)/100),(1/12))-1;

                    var combo_amount = amount*combo_ror/(Math.pow((1+combo_ror),(period*12)) -1);
                        // (BG52*BG66)/((1+BG66)^(BG51)-1)

                    document.getElementById("lumpsum_monthly_sip_amount_exceed_"+index).innerHTML = "Cannot Exceed Rs. <span>"+Number(combo_amount).toFixed(0)+"</span>";
                    
                
                    console.log("combo_ror : "+combo_ror);
                    console.log("combo_amount : "+combo_amount);
                }else{
                    document.getElementById("lumpsum_monthly_sip_amount_exceed_"+index).innerHTML = "";
                }
            }
        }

        function checkActiveInactive(key_name,index,type){
            var aror_debt = document.getElementById("aror_debt_"+index).value;
            var aror_hybrid = document.getElementById("aror_hybrid_"+index).value;
            var aror_equity = document.getElementById("aror_equity_"+index).value;

            aror_debt = (aror_debt)?parseFloat(aror_debt):0;
            aror_hybrid = (aror_hybrid)?parseFloat(aror_hybrid):0;
            aror_equity = (aror_equity)?parseFloat(aror_equity):0;
            
            if(!aror_debt){
                $('#lumpsum_debt_'+index).prop("readonly", true);
                $('#monthly_sip_debt_'+index).prop("readonly", true);
                $('#limited_period_monthly_sip_debt_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_amount_debt_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_debt_'+index).prop("readonly", true);
            }else{
                $('#lumpsum_debt_'+index).prop("readonly", false);
                $('#monthly_sip_debt_'+index).prop("readonly", false);
                $('#limited_period_monthly_sip_debt_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_amount_debt_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_debt_'+index).prop("readonly", false);
            }
            if(!aror_hybrid){
                $('#lumpsum_hybrid_'+index).prop("readonly", true);
                $('#monthly_sip_hybrid_'+index).prop("readonly", true);
                $('#limited_period_monthly_sip_hybrid_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_amount_hybrid_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_hybrid_'+index).prop("readonly", true);
            }else{
                $('#lumpsum_hybrid_'+index).prop("readonly", false);
                $('#monthly_sip_hybrid_'+index).prop("readonly", false);
                $('#limited_period_monthly_sip_hybrid_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_amount_hybrid_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_hybrid_'+index).prop("readonly", false);
            }
            if(!aror_equity){
                $('#lumpsum_equity_'+index).prop("readonly", true);
                $('#monthly_sip_equity_'+index).prop("readonly", true);
                $('#limited_period_monthly_sip_equity_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_amount_equity_'+index).prop("readonly", true);
                $('#lumpsum_monthly_sip_equity_'+index).prop("readonly", true);
            }else{
                $('#lumpsum_equity_'+index).prop("readonly", false);
                $('#monthly_sip_equity_'+index).prop("readonly", false);
                $('#limited_period_monthly_sip_equity_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_amount_equity_'+index).prop("readonly", false);
                $('#lumpsum_monthly_sip_equity_'+index).prop("readonly", false);
            }
        }

        function changeAmount(index,type){
            if(type == 1){
                var amo = document.getElementById("lumpsum_monthly_sip_lumpsum_amount_"+index).value;
                var max_amount = document.getElementById("lumpsum_monthly_sip_lumpsum_amount_exceed_"+index).getElementsByTagName('span')[0].innerHTML;

                amo = parseFloat(amo);
                max_amount = parseFloat(max_amount);

                if(amo > max_amount){
                    document.getElementById("lumpsum_monthly_sip_lumpsum_amount_error_"+index).innerHTML = "Cannot Exceed Rs. "+max_amount;
                }else{
                    document.getElementById("lumpsum_monthly_sip_lumpsum_amount_error_"+index).innerHTML = "";
                }
            }else{
                var amo = document.getElementById("lumpsum_monthly_sip_amount_"+index).value;
                var max_amount = document.getElementById("lumpsum_monthly_sip_amount_exceed_"+index).getElementsByTagName('span')[0].innerHTML;

                amo = parseFloat(amo);
                max_amount = parseFloat(max_amount);

                if(amo > max_amount){
                    document.getElementById("lumpsum_monthly_sip_amount_error_"+index).innerHTML = "Cannot Exceed Rs. "+max_amount;
                }else{
                    document.getElementById("lumpsum_monthly_sip_amount_error_"+index).innerHTML = "";
                }
            }
        }

        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });
        $("#is_note").click( function(){
            if( $(this).is(':checked') ){
                $('textarea[name="note"]').prop("readonly", false);
            }else {
                $('textarea[name="note"]').prop("readonly", true);
            }
        });


        
        function changeNote(){
            var note = document.getElementById('note').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }


        @if($client==1)
            $('input[name="clientname"]').prop("readonly", false);
        @else
            $('input[name="clientname"]').prop("readonly", true);
        @endif
        
        @if($is_note==1)
            $('textarea[name="note"]').prop("readonly", false);
        @else
            $('textarea[name="note"]').prop("readonly", true);
        @endif

    </script>


    
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
@endsection

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">{{$details->name}}</h3>
                    @include('frontend.calculators.common_bio')
                    
                    <form enctype="multipart/form-data" method="post" class="js-validate-form" action="{{route('frontend.goal_calculator_output')}}" name="goal_calculator_form" id="goal_calculator_form" onsubmit="return formSubmit();">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                @csrf
                                <div id="goal_calculator_view">
                                    @foreach($list as $key => $value)
                                        <div id="goal_calculator_view_{{$key}}">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Purpose of Investment </label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="purpose_of_investment[{{$key}}]" id="purpose_of_investment_{{$key}}" class="form-control" value="{{$value['purpose_of_investment']}}" onkeyup="checkInput('purpose_of_investment','{{$key}}',1);" message="text" datatype="{{$key}}">
                                                            <em id="purpose_of_investment_error_{{$key}}" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Current Age</label>
                                                        <div class="col-sm-7">
                                                            <div class="d-flex align-items-center">
                                                                <input type="text" name="current_age[{{$key}}]" id="current_age_{{$key}}" class="form-control" value="{{$value['current_age']}}" onkeyup="checkInput('current_age','{{$key}}',2);">
                                                            </div>
                                                            <div class="cal-icon">
                                                                Yrs
                                                            </div>
                                                            <em id="current_age_error_{{$key}}" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <div class="col-sm-12" style="display: flex;">
                                                    <div class="form-check form-check-inline">
                                                        <label class="checkLinecontainer mb-0" for="cost_type_1_{{$key}}">Future Cost
                                                            <input class="form-check-input fixed_deposit_chk" type="radio" name="cost_type[{{$key}}]" id="cost_type_1_{{$key}}" value="1" onchange="changeCostType(1,'{{$key}}')"  <?php echo ($value['cost_type'] == 1)?'checked':'' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <label class="checkLinecontainer mb-0" for="cost_type_2_{{$key}}">Current Cost
                                                            <input class="form-check-input fixed_deposit_chk" type="radio" name="cost_type[{{$key}}]" id="cost_type_2_{{$key}}" value="2"  onchange="changeCostType(2,'{{$key}}')" <?php echo ($value['cost_type'] == 2)?'checked':'' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label" id="cost_type_amount_label_{{$key}}"><?php echo ($value['cost_type'] == 2)?'Future':'Current' ?> Cost</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="amount[{{$key}}]" id="amount_{{$key}}" class="form-control" value="{{$value['amount']}}" onkeyup="checkInput('amount','{{$key}}',3);">
                                                            <div class="cal-icon">
                                                                ₹
                                                            </div>
                                                            <em id="amount_error_{{$key}}" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4" id="inflation_view_{{$key}}" style="display: <?php echo ($value['cost_type'] == 2)?'block':'none' ?>;">
                                                    <div class="form-group row"> 
                                                        <label class="col-sm-5 col-form-label">Inflation</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="inflation[{{$key}}]" id="inflation_{{$key}}" class="form-control" value="{{$value['inflation']}}" onkeyup="checkInput('inflation','{{$key}}',4);">
                                                            <div class="cal-icon">
                                                                %
                                                            </div>
                                                            <em id="inflation_error_{{$key}}" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group row">
                                                        <label class="col-sm-5 col-form-label">Period</label>
                                                        <div class="col-sm-7">
                                                            <input type="text" name="period[{{$key}}]" id="period_{{$key}}" class="form-control" value="{{$value['period']}}" onkeyup="checkInput('period','{{$key}}',5);">
                                                            <div class="cal-icon">
                                                                Yrs
                                                            </div>
                                                            <em id="period_error_{{$key}}" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group row">
                                                <div class="col-sm-12">
                                                    <h6 class="text-muted titleBlueUnderline"><strong>Assumed Rate of Return:</strong></h6>
                                                </div>
                                            </div>

                                            <div class="form-group row" style="padding-left: 20px;">
                                                <label class="col-sm-1 col-form-label">Debt</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="aror_debt[{{$key}}]" id="aror_debt_{{$key}}" class="form-control" value="{{($value['aror_debt'])?$value['aror_debt']:''}}" onkeyup="checkInput('aror_debt','{{$key}}',6);">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="aror_debt_error_{{$key}}" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Hybrid</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="aror_hybrid[{{$key}}]" id="aror_hybrid_{{$key}}" class="form-control" value="{{($value['aror_hybrid'])?$value['aror_hybrid']:''}}" onkeyup="checkInput('aror_hybrid','{{$key}}',7);">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="aror_hybrid_error_{{$key}}" class="error"></em>
                                                </div>
                                                <label class="col-sm-1 col-form-label">Equity</label>
                                                <div class="col-sm-3">
                                                    <input type="text" name="aror_equity[{{$key}}]" id="aror_equity_{{$key}}" class="form-control" value="{{($value['aror_equity'])?$value['aror_equity']:''}}" onkeyup="checkInput('aror_equity','{{$key}}',8);">
                                                    <div class="cal-icon">
                                                        %
                                                    </div>
                                                    <em id="aror_equity_error_{{$key}}" class="error"></em>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <h6 class="text-muted titleBlueUnderline"><strong>Select Investment Mode:</strong></h6>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                                        <input type="hidden" name="lumpsum_investment[{{$key}}]" id="lumpsum_investment_{{$key}}" value="1">
                                                        <input id="lumpsum_investment_mode_{{$key}}" type="checkbox" name="lumpsum_investment_mode[]" value="1" <?php echo ($value['lumpsum_investment_mode'])?'checked':'' ?> onchange="changeLumpsumInvestmentMode('{{$key}}');"> Lumpsum
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group row" id="lumpsum_investment_mode_view_{{$key}}"  style="padding-left: 20px;display: <?php echo ($value['lumpsum_investment_mode'])?'flex':'none' ?>;">
                                                    <label class="col-sm-1 col-form-label">Debt</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="lumpsum_debt[{{$key}}]" id="lumpsum_debt_{{$key}}" class="form-control" value="{{($value['lumpsum_debt'])?$value['lumpsum_debt']:''}}" onkeyup="changeLumpsum('{{$key}}');" <?php echo ($value['aror_debt'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="lumpsum_debt_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Hybrid</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="lumpsum_hybrid[{{$key}}]" id="lumpsum_hybrid_{{$key}}" class="form-control" value="{{($value['lumpsum_hybrid'])?$value['lumpsum_hybrid']:''}}" onkeyup="changeLumpsum('{{$key}}');" <?php echo ($value['aror_hybrid'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="lumpsum_hybrid_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Equity</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="lumpsum_equity[{{$key}}]" id="lumpsum_equity_{{$key}}" class="form-control" value="{{($value['lumpsum_equity'])?$value['lumpsum_equity']:''}}" onkeyup="changeLumpsum('{{$key}}');" <?php echo ($value['aror_equity'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="lumpsum_equity_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Total</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="lumpsum_total[{{$key}}]" id="lumpsum_total_{{$key}}" class="form-control" value="{{($value['lumpsum_debt']+$value['lumpsum_hybrid']+$value['lumpsum_equity'])}}" readonly="readonly" onkeyup="changeLumpsum('{{$key}}');">
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="lumpsum_total_error_{{$key}}" class="error"></em>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                                        <input type="hidden" name="monthly_sip_investment[{{$key}}]" id="monthly_sip_investment_{{$key}}" value="0">
                                                        <input id="monthly_sip_investment_mode_{{$key}}" type="checkbox" name="monthly_sip_investment_mode[{{$key}}]" value="2" onchange="changeMonthlySIPInvestmentMode('{{$key}}');" <?php echo ($value['monthly_sip_investment_mode'])?'checked':'' ?>> Monthly SIP
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group row"style="padding-left: 20px;display: <?php echo ($value['monthly_sip_investment_mode'])?'flex':'none' ?>;" id="monthly_sip_investment_mode_view_{{$key}}">
                                                    <label class="col-sm-1 col-form-label">Debt</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="monthly_sip_debt[{{$key}}]" id="monthly_sip_debt_{{$key}}" class="form-control" value="{{($value['monthly_sip_debt'])?$value['monthly_sip_debt']:''}}" onkeyup="changeMonthlySIP('{{$key}}');" <?php echo ($value['aror_debt'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="monthly_sip_debt_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Hybrid</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="monthly_sip_hybrid[{{$key}}]" id="monthly_sip_hybrid_{{$key}}" class="form-control" value="{{($value['monthly_sip_hybrid'])?$value['monthly_sip_hybrid']:''}}" onkeyup="changeMonthlySIP('{{$key}}');" <?php echo ($value['aror_hybrid'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="monthly_sip_hybrid_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Equity</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="monthly_sip_equity[{{$key}}]" id="monthly_sip_equity_{{$key}}" class="form-control" value="{{($value['monthly_sip_equity'])?$value['monthly_sip_equity']:''}}" onkeyup="changeMonthlySIP('{{$key}}');" <?php echo ($value['aror_equity'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="monthly_sip_equity_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Total</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="monthly_sip_total[{{$key}}]" id="monthly_sip_total_{{$key}}" class="form-control" value="{{($value['lumpsum_debt']+$value['lumpsum_hybrid']+$value['lumpsum_equity'])}}" readonly="readonly">
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="monthly_sip_total_error_{{$key}}" class="error"></em>
                                                    </div>
                                                </div>
                                            </div>

                                            <div>
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                                        <input type="hidden" name="limited_period_monthly_investment[{{$key}}]" id="limited_period_monthly_investment_{{$key}}" value="0">
                                                        <input id="limited_period_monthly_investment_mode_{{$key}}" type="checkbox" name="limited_period_monthly_investment_mode[{{$key}}]" value="3" onchange="changeLimitedPeriodMonthlySIPInvestmentMode('{{$key}}');" <?php echo ($value['limited_period_monthly_investment_mode'])?'checked':'' ?>> Limited Period Monthly SIP
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="form-group row" style="padding-left: 20px;display: <?php echo ($value['limited_period_monthly_investment_mode'])?'flex':'none' ?>;" id="limited_period_monthly_investment_mode_period_view_{{$key}}">
                                                    <label class="col-sm-2 col-form-label">1. SIP Period</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_period_1[{{$key}}]" id="limited_period_monthly_sip_period_1_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_period_1'])?$value['limited_period_monthly_sip_period_1']:''}}">
                                                        <div class="cal-icon">
                                                            Yr
                                                        </div>
                                                        <em id="limited_period_monthly_sip_period_1_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-2 col-form-label">2. SIP Period</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_period_2[{{$key}}]" id="limited_period_monthly_sip_period_2_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_period_2'])?$value['limited_period_monthly_sip_period_2']:''}}">
                                                        <div class="cal-icon">
                                                           Yr
                                                        </div>
                                                        <em id="limited_period_monthly_sip_period_2_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-2 col-form-label">3. SIP Period</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_period_3[{{$key}}]" id="limited_period_monthly_sip_period_3_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_period_3'])?$value['limited_period_monthly_sip_period_3']:''}}">
                                                        <div class="cal-icon">
                                                            Yr
                                                        </div>
                                                        <em id="limited_period_monthly_sip_period_{{$key}}_error_0" class="error"></em>
                                                    </div>
                                                </div>
                                                <div class="form-group row" style="padding-left: 20px;display: <?php echo ($value['limited_period_monthly_investment_mode'])?'flex':'none' ?>;" id="limited_period_monthly_investment_mode_view_{{$key}}">
                                                    <label class="col-sm-1 col-form-label">Debt</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_debt[{{$key}}]" id="limited_period_monthly_sip_debt_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_debt'])?$value['limited_period_monthly_sip_debt']:''}}" onkeyup="changeLimitedPeriodMonthlySIP('{{$key}}');" <?php echo ($value['aror_debt'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="limited_period_monthly_sip_debt_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Hybrid</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_hybrid[{{$key}}]" id="limited_period_monthly_sip_hybrid_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_hybrid'])?$value['limited_period_monthly_sip_hybrid']:''}}" onkeyup="changeLimitedPeriodMonthlySIP('{{$key}}');" <?php echo ($value['aror_hybrid'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="limited_period_monthly_sip_hybrid_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Equity</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_equity[{{$key}}]" id="limited_period_monthly_sip_equity_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_equity'])?$value['limited_period_monthly_sip_equity']:''}}" onkeyup="changeLimitedPeriodMonthlySIP('{{$key}}');" <?php echo ($value['aror_equity'])?'':'readonly' ?>>
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="limited_period_monthly_sip_equity_error_{{$key}}" class="error"></em>
                                                    </div>
                                                    <label class="col-sm-1 col-form-label">Total</label>
                                                    <div class="col-sm-2">
                                                        <input type="text" name="limited_period_monthly_sip_total[{{$key}}]" id="limited_period_monthly_sip_total_{{$key}}" class="form-control" value="{{($value['limited_period_monthly_sip_debt']+$value['limited_period_monthly_sip_hybrid']+$value['limited_period_monthly_sip_equity'])}}" readonly="readonly">
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="limited_period_monthly_sip_total_error_{{$key}}" class="error"></em>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div>
                                                <div class="row">
                                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                                        <input type="hidden" name="lumpsum_monthly_sip_investment[{{$key}}]" id="lumpsum_monthly_sip_investment_{{$key}}" value="0">
                                                        <input id="lumpsum_monthly_sip_investment_mode_{{$key}}" type="checkbox" name="lumpsum_monthly_sip_investment_mode[]" value="4" onchange="changeLumpsumMonthlySIPInvestmentMode('{{$key}}');" <?php echo ($value['lumpsum_monthly_sip_investment_mode'])?'checked':'' ?>> Lumpsum+Monthly SIP
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div style="padding-left: 20px;display: <?php echo ($value['lumpsum_monthly_sip_investment_mode'])?'block':'none' ?>;" id="lumpsum_monthly_sip_investment_mode_view_{{$key}}">
                                                    <div class="form-group row">
                                                        <label class="col-sm-12 checkLinecontainer mb-0 mt-2"> Lumpsum Amount
                                                            <input class="form-check-input fixed_deposit_chk" id="lumpsum_monthly_sip_1_{{$key}}" type="radio" name="lumpsum_monthly_sip[{{$key}}]" value="1" <?php echo ($value['lumpsum_monthly_sip'] == 1)?'checked':'' ?> onchange="changeLumpsumAmount('{{$key}}')">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>

                                                    <div style="padding-left: 20px;" id="lumpsum_monthly_sip_lumpsum_view_{{$key}}">

                                                        <div class="form-group row">
                                                            <label class="col-sm-1 col-form-label">Debt</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_amount_debt[{{$key}}]" id="lumpsum_monthly_sip_amount_debt_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_amount_debt'])?$value['lumpsum_monthly_sip_amount_debt']:''}}" onkeyup="changeLumpsumMonthlySIPAmount('{{$key}}'); maxFunction('{{$key}}',1);" <?php echo ($value['aror_debt'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_amount_debt_error_0" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Hybrid</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_amount_hybrid[{{$key}}]" id="lumpsum_monthly_sip_amount_hybrid_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_amount_hybrid'])?$value['lumpsum_monthly_sip_amount_hybrid']:''}}" onkeyup="changeLumpsumMonthlySIPAmount('{{$key}}'); maxFunction('{{$key}}',1);" <?php echo ($value['aror_hybrid'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_amount_hybrid_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Equity</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_amount_equity[{{$key}}]" id="lumpsum_monthly_sip_amount_equity_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_amount_equity'])?$value['lumpsum_monthly_sip_amount_equity']:''}}" onkeyup="changeLumpsumMonthlySIPAmount('{{$key}}'); maxFunction('{{$key}}',1);" <?php echo ($value['aror_equity'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_amount_equity_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Total</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_amount_total[{{$key}}]" id="lumpsum_monthly_sip_amount_total_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_amount_debt']+$value['lumpsum_monthly_sip_amount_hybrid']+$value['lumpsum_monthly_sip_amount_equity'])}}" readonly="readonly" >
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_amount_total_error_{{$key}}" class="error"></em>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Amount</label>
                                                            <div class="col-sm-4" id="lumpsum_monthly_sip_lumpsum_amount_view_{{$key}}">
                                                                <input type="text" name="lumpsum_monthly_sip_lumpsum_amount[{{$key}}]" class="form-control" id="lumpsum_monthly_sip_lumpsum_amount_{{$key}}" value="{{($value['lumpsum_monthly_sip_lumpsum_amount'])?$value['lumpsum_monthly_sip_lumpsum_amount']:''}}" onkeyup="maxFunction('{{$key}}',1); changeAmount('{{$key}}',1);">
                                                                <div class="cal-icon">
                                                                    ₹
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_lumpsum_amount_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <div class="col-sm-4" style="padding-top: 9px;" id="lumpsum_monthly_sip_lumpsum_amount_exceed_{{$key}}">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label class="col-sm-12 checkLinecontainer mb-0 mt-2"> Monthly SIP Amount
                                                            <input class="form-check-input fixed_deposit_chk" id="lumpsum_monthly_sip_2_{{$key}}" type="radio" name="lumpsum_monthly_sip[{{$key}}]" value="2" onchange="changeMonthlySIPAmount('{{$key}}');" <?php echo ($value['lumpsum_monthly_sip'] == 2)?'checked':'' ?>>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                    <div style="padding-left: 20px;" id="lumpsum_monthly_sip_view_{{$key}}">
                                                        <div class="form-group row">
                                                            <label class="col-sm-1 col-form-label">Debt</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_debt[{{$key}}]" id="lumpsum_monthly_sip_debt_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_debt'])?$value['lumpsum_monthly_sip_debt']:''}}" onkeyup="changeLumpsumMonthlySIP('{{$key}}'); maxFunction('{{$key}}',2);" <?php echo ($value['aror_debt'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_debt_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Hybrid</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_hybrid[{{$key}}]" id="lumpsum_monthly_sip_hybrid_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_hybrid'])?$value['lumpsum_monthly_sip_hybrid']:''}}" onkeyup="changeLumpsumMonthlySIP('{{$key}}'); maxFunction('{{$key}}',2);" <?php echo ($value['aror_hybrid'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_hybrid_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Equity</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_equity[{{$key}}]" id="lumpsum_monthly_sip_equity_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_equity'])?$value['lumpsum_monthly_sip_equity']:''}}" onkeyup="changeLumpsumMonthlySIP('{{$key}}'); maxFunction('{{$key}}',2);" <?php echo ($value['aror_equity'])?'':'readonly' ?>>
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_equity_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <label class="col-sm-1 col-form-label">Total</label>
                                                            <div class="col-sm-2">
                                                                <input type="text" name="lumpsum_monthly_sip_total[{{$key}}]" id="lumpsum_monthly_sip_total_{{$key}}" class="form-control" value="{{($value['lumpsum_monthly_sip_debt']+$value['lumpsum_monthly_sip_hybrid']+$value['lumpsum_monthly_sip_equity'])}}" readonly="readonly">
                                                                <div class="cal-icon">
                                                                    %
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_total_error_{{$key}}" class="error"></em>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Amount</label>
                                                            <div class="col-sm-4" id="lumpsum_monthly_sip_amount_view_{{$key}}">
                                                                <input type="text" name="lumpsum_monthly_sip_amount[{{$key}}]" class="form-control" id="lumpsum_monthly_sip_amount_{{$key}}" value="{{($value['lumpsum_monthly_sip_amount'])?$value['lumpsum_monthly_sip_amount']:''}}" onkeyup="maxFunction('{{$key}}',2); changeAmount('{{$key}}',2);" readonly="readonly">
                                                                <div class="cal-icon">
                                                                    ₹
                                                                </div>
                                                                <em id="lumpsum_monthly_sip_amount_error_{{$key}}" class="error"></em>
                                                            </div>
                                                            <div class="col-sm-4" style="padding-top: 9px;" id="lumpsum_monthly_sip_amount_exceed_{{$key}}">
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                                
                        <div class="text-center pt-3">
                            <button type="button" class="btn banner-btn mt-3" onclick="addGoalCalculator();">Add New Goal Calculator</button>
                        </div>
                        
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if(isset($client) && $client=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{isset($clientname)?$clientname:''}}" maxlength="30">
                                        <div class="cal-icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        @if ($errors->has('clientname'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('clientname') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if(isset($is_note) && $is_note=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{isset($note) && $note}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(isset($data['report']) && $data['report']=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(isset($data['report']) && $data['report']=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                @include('frontend.calculators.suggested.edit_form')
                                <div class="form-group row">
                                    
                                    <!-- <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div> -->
                                    
                                    <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><!-- <i class="fa fa-angle-left"></i> --> Back</button>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div>
    
                                </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
