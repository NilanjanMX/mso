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
                        url: "{{ route('frontend.stpFutureValueReadyRecoknerSave') }}",
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
    <style>
        
    </style>
@endsection
@section('content')
@php
if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP'){
    //Monthly Switch T8*AC15%
    $monthly_switch = $initial_investment*($fixed_percent/100);

    //Total Fund Value1 Start
    //Number of Months
        $number_of_months_period1 = $period1*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;

        $debt_transfer_amount1 = $monthly_switch;
        $debt_switch_amount1 = $monthly_switch;
        $debt_balance1 = 0;
        $equity_eom_value1 = 0;
        $future_value_of_debt_fund1 = 0;
        $future_value_of_equity_fund1 = 0;
        $total_fund_value1 = 0;
        $irr1 = 0;
        for ($j=1;$j<=$number_of_months_period1;$j++){
                if ($j==1){
                         $debt_bom_value1 = $initial_investment;
                    }else{
                        $debt_bom_value1 = $debt_balance1;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value1 = $debt_bom_value1+$debt_bom_value1*$monthly_debt_return_rate1;
                    if ($debt_eom_value1>=$debt_transfer_amount1){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount1>0){
                        $debt_switch_amount1 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount1 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance1 = $debt_eom_value1 - $debt_switch_amount1;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value1 = 0;
                    }else{
                        $equity_bom_value1 = $equity_eom_value1;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value1 = $equity_bom_value1+$equity_bom_value1*$monthly_equity_return_rate1+$debt_switch_amount1;
                    //Total Value AW80+BD80
                    $total_value1 = $debt_balance1 + $equity_eom_value1;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr1 = (pow(((1+($total_value1-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund1 = $debt_balance1;
        $future_value_of_equity_fund1 = $equity_eom_value1;
        $total_fund_value1 = $future_value_of_debt_fund1 + $future_value_of_equity_fund1;
    //Total Fund Value1 End

    //Total Fund Value2 Start
    //Number of Months
        $number_of_months_period2 = $period1*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;

        $debt_transfer_amount2 = $monthly_switch;
        $debt_switch_amount2 = $monthly_switch;
        $debt_balance2 = 0;
        $equity_eom_value2 = 0;
        $future_value_of_debt_fund2 = 0;
        $future_value_of_equity_fund2 = 0;
        $total_fund_value2 = 0;
        $irr2 = 0;
        for ($j=1;$j<=$number_of_months_period2;$j++){
                if ($j==1){
                         $debt_bom_value2 = $initial_investment;
                    }else{
                        $debt_bom_value2 = $debt_balance2;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value2 = $debt_bom_value2+$debt_bom_value2*$monthly_debt_return_rate2;
                    if ($debt_eom_value2>=$debt_transfer_amount2){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount2>0){
                        $debt_switch_amount2 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount2 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance2 = $debt_eom_value2 - $debt_switch_amount2;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value2 = 0;
                    }else{
                        $equity_bom_value2 = $equity_eom_value2;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value2 = $equity_bom_value2+$equity_bom_value2*$monthly_equity_return_rate2+$debt_switch_amount2;
                    //Total Value AW80+BD80
                    $total_value2 = $debt_balance2 + $equity_eom_value2;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr2 = (pow(((1+($total_value2-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund2 = $debt_balance2;
        $future_value_of_equity_fund2 = $equity_eom_value2;
        $total_fund_value2 = $future_value_of_debt_fund2 + $future_value_of_equity_fund2;
    //Total Fund Value2 End

    //Total Fund Value3 Start
    //Number of Months
        $number_of_months_period3 = $period1*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;

        $debt_transfer_amount3 = $monthly_switch;
        $debt_switch_amount3 = $monthly_switch;
        $debt_balance3 = 0;
        $equity_eom_value3 = 0;
        $future_value_of_debt_fund3 = 0;
        $future_value_of_equity_fund3 = 0;
        $total_fund_value3 = 0;
        $irr3 = 0;
        for ($j=1;$j<=$number_of_months_period3;$j++){
                if ($j==1){
                         $debt_bom_value3 = $initial_investment;
                    }else{
                        $debt_bom_value3 = $debt_balance3;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value3 = $debt_bom_value3+$debt_bom_value3*$monthly_debt_return_rate3;
                    if ($debt_eom_value3>=$debt_transfer_amount3){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount3>0){
                        $debt_switch_amount3 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount3 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance3 = $debt_eom_value3 - $debt_switch_amount3;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value3 = 0;
                    }else{
                        $equity_bom_value3 = $equity_eom_value3;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value3 = $equity_bom_value3+$equity_bom_value3*$monthly_equity_return_rate3+$debt_switch_amount3;
                    //Total Value AW80+BD80
                    $total_value3 = $debt_balance3 + $equity_eom_value3;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr3 = (pow(((1+($total_value3-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund3 = $debt_balance3;
        $future_value_of_equity_fund3 = $equity_eom_value3;
        $total_fund_value3 = $future_value_of_debt_fund3 + $future_value_of_equity_fund3;
    //Total Fund Value3 End


    //Total Fund Value1 Start
    //Number of Months
        $number_of_months_period1 = $period2*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;

        $debt_transfer_amount1 = $monthly_switch;
        $debt_switch_amount1 = $monthly_switch;
        $debt_balance1 = 0;
        $equity_eom_value1 = 0;
        $future_value_of_debt_fund1 = 0;
        $future_value_of_equity_fund1 = 0;
        $total_fund_value4 = 0;
        $irr1 = 0;
        for ($j=1;$j<=$number_of_months_period1;$j++){
                if ($j==1){
                         $debt_bom_value1 = $initial_investment;
                    }else{
                        $debt_bom_value1 = $debt_balance1;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value1 = $debt_bom_value1+$debt_bom_value1*$monthly_debt_return_rate1;
                    if ($debt_eom_value1>=$debt_transfer_amount1){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount1>0){
                        $debt_switch_amount1 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount1 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance1 = $debt_eom_value1 - $debt_switch_amount1;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value1 = 0;
                    }else{
                        $equity_bom_value1 = $equity_eom_value1;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value1 = $equity_bom_value1+$equity_bom_value1*$monthly_equity_return_rate1+$debt_switch_amount1;
                    //Total Value AW80+BD80
                    $total_value1 = $debt_balance1 + $equity_eom_value1;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr1 = (pow(((1+($total_value1-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund1 = $debt_balance1;
        $future_value_of_equity_fund1 = $equity_eom_value1;
        $total_fund_value4 = $future_value_of_debt_fund1 + $future_value_of_equity_fund1;
    //Total Fund Value1 End

    //Total Fund Value2 Start
    //Number of Months
        $number_of_months_period2 = $period2*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;

        $debt_transfer_amount2 = $monthly_switch;
        $debt_switch_amount2 = $monthly_switch;
        $debt_balance2 = 0;
        $equity_eom_value2 = 0;
        $future_value_of_debt_fund2 = 0;
        $future_value_of_equity_fund2 = 0;
        $total_fund_value5 = 0;
        $irr2 = 0;
        for ($j=1;$j<=$number_of_months_period2;$j++){
                if ($j==1){
                         $debt_bom_value2 = $initial_investment;
                    }else{
                        $debt_bom_value2 = $debt_balance2;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value2 = $debt_bom_value2+$debt_bom_value2*$monthly_debt_return_rate2;
                    if ($debt_eom_value2>=$debt_transfer_amount2){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount2>0){
                        $debt_switch_amount2 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount2 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance2 = $debt_eom_value2 - $debt_switch_amount2;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value2 = 0;
                    }else{
                        $equity_bom_value2 = $equity_eom_value2;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value2 = $equity_bom_value2+$equity_bom_value2*$monthly_equity_return_rate2+$debt_switch_amount2;
                    //Total Value AW80+BD80
                    $total_value2 = $debt_balance2 + $equity_eom_value2;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr2 = (pow(((1+($total_value2-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund2 = $debt_balance2;
        $future_value_of_equity_fund2 = $equity_eom_value2;
        $total_fund_value5 = $future_value_of_debt_fund2 + $future_value_of_equity_fund2;
    //Total Fund Value2 End

    //Total Fund Value3 Start
    //Number of Months
        $number_of_months_period3 = $period2*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;

        $debt_transfer_amount3 = $monthly_switch;
        $debt_switch_amount3 = $monthly_switch;
        $debt_balance3 = 0;
        $equity_eom_value3 = 0;
        $future_value_of_debt_fund3 = 0;
        $future_value_of_equity_fund3 = 0;
        $total_fund_value6 = 0;
        $irr3 = 0;
        for ($j=1;$j<=$number_of_months_period3;$j++){
                if ($j==1){
                         $debt_bom_value3 = $initial_investment;
                    }else{
                        $debt_bom_value3 = $debt_balance3;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value3 = $debt_bom_value3+$debt_bom_value3*$monthly_debt_return_rate3;
                    if ($debt_eom_value3>=$debt_transfer_amount3){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount3>0){
                        $debt_switch_amount3 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount3 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance3 = $debt_eom_value3 - $debt_switch_amount3;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value3 = 0;
                    }else{
                        $equity_bom_value3 = $equity_eom_value3;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value3 = $equity_bom_value3+$equity_bom_value3*$monthly_equity_return_rate3+$debt_switch_amount3;
                    //Total Value AW80+BD80
                    $total_value3 = $debt_balance3 + $equity_eom_value3;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr3 = (pow(((1+($total_value3-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund3 = $debt_balance3;
        $future_value_of_equity_fund3 = $equity_eom_value3;
        $total_fund_value6 = $future_value_of_debt_fund3 + $future_value_of_equity_fund3;
    //Total Fund Value3 End

    //Total Fund Value1 Start
    //Number of Months
        $number_of_months_period1 = $period3*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;

        $debt_transfer_amount1 = $monthly_switch;
        $debt_switch_amount1 = $monthly_switch;
        $debt_balance1 = 0;
        $equity_eom_value1 = 0;
        $future_value_of_debt_fund1 = 0;
        $future_value_of_equity_fund1 = 0;
        $total_fund_value7 = 0;
        $irr1 = 0;
        for ($j=1;$j<=$number_of_months_period1;$j++){
                if ($j==1){
                         $debt_bom_value1 = $initial_investment;
                    }else{
                        $debt_bom_value1 = $debt_balance1;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value1 = $debt_bom_value1+$debt_bom_value1*$monthly_debt_return_rate1;
                    if ($debt_eom_value1>=$debt_transfer_amount1){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount1>0){
                        $debt_switch_amount1 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount1 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance1 = $debt_eom_value1 - $debt_switch_amount1;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value1 = 0;
                    }else{
                        $equity_bom_value1 = $equity_eom_value1;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value1 = $equity_bom_value1+$equity_bom_value1*$monthly_equity_return_rate1+$debt_switch_amount1;
                    //Total Value AW80+BD80
                    $total_value1 = $debt_balance1 + $equity_eom_value1;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr1 = (pow(((1+($total_value1-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund1 = $debt_balance1;
        $future_value_of_equity_fund1 = $equity_eom_value1;
        $total_fund_value7 = $future_value_of_debt_fund1 + $future_value_of_equity_fund1;
    //Total Fund Value1 End

    //Total Fund Value2 Start
    //Number of Months
        $number_of_months_period2 = $period3*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;

        $debt_transfer_amount2 = $monthly_switch;
        $debt_switch_amount2 = $monthly_switch;
        $debt_balance2 = 0;
        $equity_eom_value2 = 0;
        $future_value_of_debt_fund2 = 0;
        $future_value_of_equity_fund2 = 0;
        $total_fund_value8 = 0;
        $irr2 = 0;
        for ($j=1;$j<=$number_of_months_period2;$j++){
                if ($j==1){
                         $debt_bom_value2 = $initial_investment;
                    }else{
                        $debt_bom_value2 = $debt_balance2;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value2 = $debt_bom_value2+$debt_bom_value2*$monthly_debt_return_rate2;
                    if ($debt_eom_value2>=$debt_transfer_amount2){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount2>0){
                        $debt_switch_amount2 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount2 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance2 = $debt_eom_value2 - $debt_switch_amount2;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value2 = 0;
                    }else{
                        $equity_bom_value2 = $equity_eom_value2;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value2 = $equity_bom_value2+$equity_bom_value2*$monthly_equity_return_rate2+$debt_switch_amount2;
                    //Total Value AW80+BD80
                    $total_value2 = $debt_balance2 + $equity_eom_value2;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr2 = (pow(((1+($total_value2-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund2 = $debt_balance2;
        $future_value_of_equity_fund2 = $equity_eom_value2;
        $total_fund_value8 = $future_value_of_debt_fund2 + $future_value_of_equity_fund2;
    //Total Fund Value2 End

    //Total Fund Value3 Start
    //Number of Months
        $number_of_months_period3 = $period3*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;

        $debt_transfer_amount3 = $monthly_switch;
        $debt_switch_amount3 = $monthly_switch;
        $debt_balance3 = 0;
        $equity_eom_value3 = 0;
        $future_value_of_debt_fund3 = 0;
        $future_value_of_equity_fund3 = 0;
        $total_fund_value9 = 0;
        $irr3 = 0;
        for ($j=1;$j<=$number_of_months_period3;$j++){
                if ($j==1){
                         $debt_bom_value3 = $initial_investment;
                    }else{
                        $debt_bom_value3 = $debt_balance3;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value3 = $debt_bom_value3+$debt_bom_value3*$monthly_debt_return_rate3;
                    if ($debt_eom_value3>=$debt_transfer_amount3){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount3>0){
                        $debt_switch_amount3 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount3 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance3 = $debt_eom_value3 - $debt_switch_amount3;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value3 = 0;
                    }else{
                        $equity_bom_value3 = $equity_eom_value3;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value3 = $equity_bom_value3+$equity_bom_value3*$monthly_equity_return_rate3+$debt_switch_amount3;
                    //Total Value AW80+BD80
                    $total_value3 = $debt_balance3 + $equity_eom_value3;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr3 = (pow(((1+($total_value3-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund3 = $debt_balance3;
        $future_value_of_equity_fund3 = $equity_eom_value3;
        $total_fund_value9 = $future_value_of_debt_fund3 + $future_value_of_equity_fund3;
    //Total Fund Value3 End

    //Total Fund Value1 Start
    //Number of Months
        $number_of_months_period1 = $period4*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;

        $debt_transfer_amount1 = $monthly_switch;
        $debt_switch_amount1 = $monthly_switch;
        $debt_balance1 = 0;
        $equity_eom_value1 = 0;
        $future_value_of_debt_fund1 = 0;
        $future_value_of_equity_fund1 = 0;
        $total_fund_value10 = 0;
        $irr1 = 0;
        for ($j=1;$j<=$number_of_months_period1;$j++){
                if ($j==1){
                         $debt_bom_value1 = $initial_investment;
                    }else{
                        $debt_bom_value1 = $debt_balance1;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value1 = $debt_bom_value1+$debt_bom_value1*$monthly_debt_return_rate1;
                    if ($debt_eom_value1>=$debt_transfer_amount1){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount1>0){
                        $debt_switch_amount1 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount1 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance1 = $debt_eom_value1 - $debt_switch_amount1;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value1 = 0;
                    }else{
                        $equity_bom_value1 = $equity_eom_value1;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value1 = $equity_bom_value1+$equity_bom_value1*$monthly_equity_return_rate1+$debt_switch_amount1;
                    //Total Value AW80+BD80
                    $total_value1 = $debt_balance1 + $equity_eom_value1;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr1 = (pow(((1+($total_value1-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund1 = $debt_balance1;
        $future_value_of_equity_fund1 = $equity_eom_value1;
        $total_fund_value10 = $future_value_of_debt_fund1 + $future_value_of_equity_fund1;
    //Total Fund Value1 End

    //Total Fund Value2 Start
    //Number of Months
        $number_of_months_period2 = $period4*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;

        $debt_transfer_amount2 = $monthly_switch;
        $debt_switch_amount2 = $monthly_switch;
        $debt_balance2 = 0;
        $equity_eom_value2 = 0;
        $future_value_of_debt_fund2 = 0;
        $future_value_of_equity_fund2 = 0;
        $total_fund_value11 = 0;
        $irr2 = 0;
        for ($j=1;$j<=$number_of_months_period2;$j++){
                if ($j==1){
                         $debt_bom_value2 = $initial_investment;
                    }else{
                        $debt_bom_value2 = $debt_balance2;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value2 = $debt_bom_value2+$debt_bom_value2*$monthly_debt_return_rate2;
                    if ($debt_eom_value2>=$debt_transfer_amount2){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount2>0){
                        $debt_switch_amount2 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount2 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance2 = $debt_eom_value2 - $debt_switch_amount2;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value2 = 0;
                    }else{
                        $equity_bom_value2 = $equity_eom_value2;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value2 = $equity_bom_value2+$equity_bom_value2*$monthly_equity_return_rate2+$debt_switch_amount2;
                    //Total Value AW80+BD80
                    $total_value2 = $debt_balance2 + $equity_eom_value2;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr2 = (pow(((1+($total_value2-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund2 = $debt_balance2;
        $future_value_of_equity_fund2 = $equity_eom_value2;
        $total_fund_value11 = $future_value_of_debt_fund2 + $future_value_of_equity_fund2;
    //Total Fund Value2 End

    //Total Fund Value3 Start
    //Number of Months
        $number_of_months_period3 = $period4*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;

        $debt_transfer_amount3 = $monthly_switch;
        $debt_switch_amount3 = $monthly_switch;
        $debt_balance3 = 0;
        $equity_eom_value3 = 0;
        $future_value_of_debt_fund3 = 0;
        $future_value_of_equity_fund3 = 0;
        $total_fund_value12 = 0;
        $irr3 = 0;
        for ($j=1;$j<=$number_of_months_period3;$j++){
                if ($j==1){
                         $debt_bom_value3 = $initial_investment;
                    }else{
                        $debt_bom_value3 = $debt_balance3;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value3 = $debt_bom_value3+$debt_bom_value3*$monthly_debt_return_rate3;
                    if ($debt_eom_value3>=$debt_transfer_amount3){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount3>0){
                        $debt_switch_amount3 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount3 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance3 = $debt_eom_value3 - $debt_switch_amount3;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value3 = 0;
                    }else{
                        $equity_bom_value3 = $equity_eom_value3;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value3 = $equity_bom_value3+$equity_bom_value3*$monthly_equity_return_rate3+$debt_switch_amount3;
                    //Total Value AW80+BD80
                    $total_value3 = $debt_balance3 + $equity_eom_value3;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr3 = (pow(((1+($total_value3-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund3 = $debt_balance3;
        $future_value_of_equity_fund3 = $equity_eom_value3;
        $total_fund_value12 = $future_value_of_debt_fund3 + $future_value_of_equity_fund3;
    //Total Fund Value3 End

    //Total Fund Value1 Start
    //Number of Months
        $number_of_months_period1 = $period5*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;

        $debt_transfer_amount1 = $monthly_switch;
        $debt_switch_amount1 = $monthly_switch;
        $debt_balance1 = 0;
        $equity_eom_value1 = 0;
        $future_value_of_debt_fund1 = 0;
        $future_value_of_equity_fund1 = 0;
        $total_fund_value13 = 0;
        $irr1 = 0;
        for ($j=1;$j<=$number_of_months_period1;$j++){
                if ($j==1){
                         $debt_bom_value1 = $initial_investment;
                    }else{
                        $debt_bom_value1 = $debt_balance1;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value1 = $debt_bom_value1+$debt_bom_value1*$monthly_debt_return_rate1;
                    if ($debt_eom_value1>=$debt_transfer_amount1){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount1>0){
                        $debt_switch_amount1 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount1 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance1 = $debt_eom_value1 - $debt_switch_amount1;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value1 = 0;
                    }else{
                        $equity_bom_value1 = $equity_eom_value1;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value1 = $equity_bom_value1+$equity_bom_value1*$monthly_equity_return_rate1+$debt_switch_amount1;
                    //Total Value AW80+BD80
                    $total_value1 = $debt_balance1 + $equity_eom_value1;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr1 = (pow(((1+($total_value1-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund1 = $debt_balance1;
        $future_value_of_equity_fund1 = $equity_eom_value1;
        $total_fund_value13 = $future_value_of_debt_fund1 + $future_value_of_equity_fund1;
    //Total Fund Value1 End

    //Total Fund Value2 Start
    //Number of Months
        $number_of_months_period2 = $period5*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;

        $debt_transfer_amount2 = $monthly_switch;
        $debt_switch_amount2 = $monthly_switch;
        $debt_balance2 = 0;
        $equity_eom_value2 = 0;
        $future_value_of_debt_fund2 = 0;
        $future_value_of_equity_fund2 = 0;
        $total_fund_value14 = 0;
        $irr2 = 0;
        for ($j=1;$j<=$number_of_months_period2;$j++){
                if ($j==1){
                         $debt_bom_value2 = $initial_investment;
                    }else{
                        $debt_bom_value2 = $debt_balance2;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value2 = $debt_bom_value2+$debt_bom_value2*$monthly_debt_return_rate2;
                    if ($debt_eom_value2>=$debt_transfer_amount2){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount2>0){
                        $debt_switch_amount2 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount2 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance2 = $debt_eom_value2 - $debt_switch_amount2;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value2 = 0;
                    }else{
                        $equity_bom_value2 = $equity_eom_value2;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value2 = $equity_bom_value2+$equity_bom_value2*$monthly_equity_return_rate2+$debt_switch_amount2;
                    //Total Value AW80+BD80
                    $total_value2 = $debt_balance2 + $equity_eom_value2;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr2 = (pow(((1+($total_value2-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund2 = $debt_balance2;
        $future_value_of_equity_fund2 = $equity_eom_value2;
        $total_fund_value14 = $future_value_of_debt_fund2 + $future_value_of_equity_fund2;
    //Total Fund Value2 End

    //Total Fund Value3 Start
    //Number of Months
        $number_of_months_period3 = $period5*12;
    //Monthly Debt Return (1+Q9%)^(1/12)-1
        $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
    //Monthly Equity Return (1+Q10%)^(1/12)-1
        $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;

        $debt_transfer_amount3 = $monthly_switch;
        $debt_switch_amount3 = $monthly_switch;
        $debt_balance3 = 0;
        $equity_eom_value3 = 0;
        $future_value_of_debt_fund3 = 0;
        $future_value_of_equity_fund3 = 0;
        $total_fund_value15 = 0;
        $irr3 = 0;
        for ($j=1;$j<=$number_of_months_period3;$j++){
                if ($j==1){
                         $debt_bom_value3 = $initial_investment;
                    }else{
                        $debt_bom_value3 = $debt_balance3;
                    }
                // AS80+AS80*AT80
                    $debt_eom_value3 = $debt_bom_value3+$debt_bom_value3*$monthly_debt_return_rate3;
                    if ($debt_eom_value3>=$debt_transfer_amount3){
                        $debt_monthly_transfer = $monthly_switch;
                    }else{
                        $debt_monthly_transfer = 0;
                    }
                    if ($debt_switch_amount3>0){
                        $debt_switch_amount3 = $debt_monthly_transfer;
                    }else{
                        $debt_switch_amount3 =0;
                    }
                    //Balance AU80-AV80
                    $debt_balance3 = $debt_eom_value3 - $debt_switch_amount3;
                   //Equity Calculation
                    if ($j==1){
                         $equity_bom_value3 = 0;
                    }else{
                        $equity_bom_value3 = $equity_eom_value3;
                    }
                    // BA80+BA80*BC80+BB80
                    $equity_eom_value3 = $equity_bom_value3+$equity_bom_value3*$monthly_equity_return_rate3+$debt_switch_amount3;
                    //Total Value AW80+BD80
                    $total_value3 = $debt_balance3 + $equity_eom_value3;
                    //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
                    $irr3 = (pow(((1+($total_value3-$initial_investment)/$initial_investment)),(12/$j))-1);
        }
        $future_value_of_debt_fund3 = $debt_balance3;
        $future_value_of_equity_fund3 = $equity_eom_value3;
        $total_fund_value15 = $future_value_of_debt_fund3 + $future_value_of_equity_fund3;
    //Total Fund Value3 End
}else{
//Total Fund Value1 Start
   //Number of Months
   $number_of_months_period1 = $period1*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation1 = $initial_investment*$monthly_debt_return_rate1;
    //Future Value of Debt Fund
    $future_value_of_debt_fund1 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund1 = $monthly_appreciation1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
    //Total Fund Value AR32+AR33
    $total_fund_value1 = $future_value_of_debt_fund1+$future_value_of_equity_fund1;
//Total Fund Value1 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period2 = $period1*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation2 = $initial_investment*$monthly_debt_return_rate2;
    //Future Value of Debt Fund
    $future_value_of_debt_fund2 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund2 = $monthly_appreciation2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
    //Total Fund Value AR32+AR33
    $total_fund_value2 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
//Total Fund Value2 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period3 = $period1*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation3 = $initial_investment*$monthly_debt_return_rate3;
    //Future Value of Debt Fund
    $future_value_of_debt_fund3 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund3 = $monthly_appreciation3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
    //Total Fund Value AR32+AR33
    $total_fund_value3 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
//Total Fund Value2 End
//====================================================================//
//Total Fund Value1 Start
   //Number of Months
   $number_of_months_period1 = $period2*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation1 = $initial_investment*$monthly_debt_return_rate1;
    //Future Value of Debt Fund
    $future_value_of_debt_fund1 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund1 = $monthly_appreciation1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
    //Total Fund Value AR32+AR33
    $total_fund_value4 = $future_value_of_debt_fund1+$future_value_of_equity_fund1;
//Total Fund Value1 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period2 = $period2*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation2 = $initial_investment*$monthly_debt_return_rate2;
    //Future Value of Debt Fund
    $future_value_of_debt_fund2 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund2 = $monthly_appreciation2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
    //Total Fund Value AR32+AR33
    $total_fund_value5 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
//Total Fund Value2 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period3 = $period2*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation3 = $initial_investment*$monthly_debt_return_rate3;
    //Future Value of Debt Fund
    $future_value_of_debt_fund3 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund3 = $monthly_appreciation3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
    //Total Fund Value AR32+AR33
    $total_fund_value6 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
//Total Fund Value2 End
//====================================================================//
//Total Fund Value1 Start
   //Number of Months
   $number_of_months_period1 = $period3*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation1 = $initial_investment*$monthly_debt_return_rate1;
    //Future Value of Debt Fund
    $future_value_of_debt_fund1 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund1 = $monthly_appreciation1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
    //Total Fund Value AR32+AR33
    $total_fund_value7 = $future_value_of_debt_fund1+$future_value_of_equity_fund1;
//Total Fund Value1 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period2 = $period3*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation2 = $initial_investment*$monthly_debt_return_rate2;
    //Future Value of Debt Fund
    $future_value_of_debt_fund2 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund2 = $monthly_appreciation2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
    //Total Fund Value AR32+AR33
    $total_fund_value8 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
//Total Fund Value2 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period3 = $period3*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation3 = $initial_investment*$monthly_debt_return_rate3;
    //Future Value of Debt Fund
    $future_value_of_debt_fund3 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund3 = $monthly_appreciation3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
    //Total Fund Value AR32+AR33
    $total_fund_value9 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
//Total Fund Value2 End
//====================================================================//
//Total Fund Value1 Start
   //Number of Months
   $number_of_months_period1 = $period4*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation1 = $initial_investment*$monthly_debt_return_rate1;
    //Future Value of Debt Fund
    $future_value_of_debt_fund1 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund1 = $monthly_appreciation1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
    //Total Fund Value AR32+AR33
    $total_fund_value10 = $future_value_of_debt_fund1+$future_value_of_equity_fund1;
//Total Fund Value1 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period2 = $period4*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation2 = $initial_investment*$monthly_debt_return_rate2;
    //Future Value of Debt Fund
    $future_value_of_debt_fund2 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund2 = $monthly_appreciation2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
    //Total Fund Value AR32+AR33
    $total_fund_value11 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
//Total Fund Value2 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period3 = $period4*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation3 = $initial_investment*$monthly_debt_return_rate3;
    //Future Value of Debt Fund
    $future_value_of_debt_fund3 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund3 = $monthly_appreciation3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
    //Total Fund Value AR32+AR33
    $total_fund_value12 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
//Total Fund Value2 End
//====================================================================//
//Total Fund Value1 Start
   //Number of Months
   $number_of_months_period1 = $period5*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation1 = $initial_investment*$monthly_debt_return_rate1;
    //Future Value of Debt Fund
    $future_value_of_debt_fund1 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund1 = $monthly_appreciation1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
    //Total Fund Value AR32+AR33
    $total_fund_value13 = $future_value_of_debt_fund1+$future_value_of_equity_fund1;
//Total Fund Value1 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period2 = $period5*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate2 =  pow((1+$debt_rate2/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate2 =  pow((1+$equity_rate2/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation2 = $initial_investment*$monthly_debt_return_rate2;
    //Future Value of Debt Fund
    $future_value_of_debt_fund2 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund2 = $monthly_appreciation2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
    //Total Fund Value AR32+AR33
    $total_fund_value14 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
//Total Fund Value2 End

//Total Fund Value2 Start
   //Number of Months
   $number_of_months_period3 = $period5*12;
   //Monthly Debt Return (1+Q9%)^(1/12)-1
   $monthly_debt_return_rate3 =  pow((1+$debt_rate3/100),(1/12))-1;
   //Monthly Equity Return (1+Q10%)^(1/12)-1
    $monthly_equity_return_rate3 =  pow((1+$equity_rate3/100),(1/12))-1;
    //Monthly Appreciation Q7*AR29
    $monthly_appreciation3 = $initial_investment*$monthly_debt_return_rate3;
    //Future Value of Debt Fund
    $future_value_of_debt_fund3 = $initial_investment;
    //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
    $future_value_of_equity_fund3 = $monthly_appreciation3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
    //Total Fund Value AR32+AR33
    $total_fund_value15 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
//Total Fund Value2 End
}
@endphp
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
                    <h2 class="page-title">STP Future Value Ready Reckoner</h2>
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
                        <a href="{{route('frontend.stpFutureValueReadyReckonerBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.stpFutureValueReadyRecoknerOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    

                    

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <div class="outputTableHolder">
                        <h1 class="midheading">STP Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered">
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
                                        <strong>Transfer Mode</strong>
                                    </td>
                                    <td>
                                        @if($monthly_transfer_mode=='CA')
                                            Capital Appreciation
                                        @else
                                            @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                                                {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                                            @else
                                                ₹ {{custome_money_format($fixed_amount)}}
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                </tbody></table>
                        </div>
                        
                        <h1 class="midheading">Future Value of Initial Investment</h1>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered text-center">
                                <tbody>
        
                                    <tr>
                                        <td>Rate of Return</td>
                                        <td colspan="5"><strong>STP Period (Years)</strong></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Debt&nbsp; |&nbsp; Equity</strong></td>
                                        <td><strong>{{$period1?$period1:''}}</strong></td>
                                        <td><strong>{{$period2?$period2:''}}</strong></td>
                                        <td><strong>{{$period3?$period3:''}}</strong></td>
                                        <td><strong>{{$period4?$period4:''}}</strong></td>
                                        <td><strong>{{$period5?$period5:''}}</strong></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>
                                                {{$debt_rate1?number_format((float)$debt_rate1, 2, '.', ''):0}}%
                                                &nbsp;&nbsp;
                                                {{$equity_rate1?number_format((float)$equity_rate1, 2, '.', ''):0}}%
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value1)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value4)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value7)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value10)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value13)}} </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>
                                                {{$debt_rate2?number_format((float)$debt_rate2, 2, '.', ''):0}}%
                                                &nbsp;&nbsp;
                                                {{$equity_rate2?number_format((float)$equity_rate2, 2, '.', ''):0}}%
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value2)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value5)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value8)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value11)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value14)}} </strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>
                                                {{$debt_rate3?number_format((float)$debt_rate3, 2, '.', ''):0}}%
                                                &nbsp;&nbsp;
                                                {{$equity_rate3?number_format((float)$equity_rate3, 2, '.', ''):0}}%
                                            </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value3)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value6)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value9)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value12)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($total_fund_value15)}} </strong>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td colspan="6" style="text-align: right;padding-right:30px;"><strong>(₹ in Lacs)</strong></td>
                                    </tr>
        
                                </tbody>
                            </table>
                        </div>
                            {{-- comment or note section here --}}
                            @include('frontend.calculators.common.comment_output')
                            
                            <!--<p class="text-left">* Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>-->
                            <!--    *Returns are not guaranteed. The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}</p>-->
                            <div class="description-text">
                                @php
                                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','STP_Future_Value_Ready_Recokner')->first();
                                if(!empty($note_data1)){
                                @endphp
                                {!!$note_data1->description!!}
                                @php } @endphp
                                Report Date : {{date('d/m/Y')}}
                            </div>
                            

                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.stpFutureValueReadyReckonerBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.stpFutureValueReadyRecoknerOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
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
        var base_url = "{{route('frontend.stpFutureValueReadyRecoknerOutputDownloadPDF')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
