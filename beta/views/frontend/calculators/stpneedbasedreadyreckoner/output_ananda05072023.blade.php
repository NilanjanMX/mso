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
                        url: "{{ route('frontend.stpGoalPlanningValueReadyRecoknerOutputSave') }}",
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
      //Assumed Initial Investment
      $assumed_initial_investment = 1;
      //Monthly Switch T8*AC15%
      $monthly_switch = $assumed_initial_investment*($fixed_percent/100);
      $initial_investment = 1;


      //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period1*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required1 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period1*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate2/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate2/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required2 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period1*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate3/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate3/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required3 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period2*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required4 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period2*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate2/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate2/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required5 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period2*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate3/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate3/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required6 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period3*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required7 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period3*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate2/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate2/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required8 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period3*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate3/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate3/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required9 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period4*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required10 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period4*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate2/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate2/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required11 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period4*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate3/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate3/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required12 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period5*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate1/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate1/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required13 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period5*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate2/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate2/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required14 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

     //Total Fund Value1 Start
         //Number of Months
         $number_of_months_period1 = $period5*12;
         //Monthly Debt Return (1+Q9%)^(1/12)-1
         $monthly_debt_return_rate1 =  pow((1+$debt_rate3/100),(1/12))-1;
         //Monthly Equity Return (1+Q10%)^(1/12)-1
          $monthly_equity_return_rate1 =  pow((1+$equity_rate3/100),(1/12))-1;
          $future_value_of_debt_fund = 0;
          $future_value_of_equity_fund = 0;
          $debt_transfer_amount = $monthly_switch;
          $debt_switch_amount = $monthly_switch;
          $equity_transfer_amount = $monthly_switch;
          $equity_switch_amount = $monthly_switch;
         for ($j=1;$j<=$number_of_months_period1;$j++){
                  if ($j==1){
                       $debt_bom_value = $assumed_initial_investment;
                  }else{
                      $debt_bom_value = $debt_balance;
                  }
                  // AS80+AS80*AT80
                  $debt_eom_value = $debt_bom_value+$debt_bom_value*$monthly_debt_return_rate1;

                  if ($debt_eom_value>=$debt_transfer_amount){
                      $debt_monthly_transfer = $monthly_switch;
                  }else{
                      $debt_monthly_transfer = 0;
                  }

                  if ($debt_switch_amount>0){
                      $debt_switch_amount = $debt_monthly_transfer;
                  }else{
                      $debt_switch_amount =0;
                  }
                  //Balance AU80-AV80
                  $debt_balance = $debt_eom_value - $debt_switch_amount;

             //Equity Calculation
              if ($j==1){
                   $equity_bom_value = 0;
              }else{
                  $equity_bom_value = $equity_eom_value;
              }
          // BA80+BA80*BC80+BB80
            $equity_eom_value = $equity_bom_value+$equity_bom_value*$monthly_equity_return_rate1+$debt_switch_amount;
          //Total Value AW80+BD80
          $total_value = $debt_balance + $equity_eom_value;
          //IRR ((1+(BF80-BE80)/BE80))^(12/AZ80)-1
          $irr = (pow(((1+($total_value-$assumed_initial_investment)/$assumed_initial_investment)),(12/$j))-1)*100;
          }
         $future_value_of_debt_fund = $debt_balance;
         $future_value_of_equity_fund = $equity_eom_value;
         $initial_investment_required15 = $target_amount/($future_value_of_debt_fund + $future_value_of_equity_fund);
     //Total Fund Value1 End

}else{
  $initial_investment = 1;
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


      //Initial Investment Required Q7/AR34
      $initial_investment_required1 = $target_amount / $total_fund_value1;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price1 = $target_amount * $monthly_debt_return_rate1;
      //Future Value of Debt Fund
      $future_value_of_debt_fund1 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund1 = $monthly_appreciation_price1*((pow((1+$monthly_equity_return_rate1),$number_of_months_period1)-1)/$monthly_equity_return_rate1);
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


      //Initial Investment Required Q7/AR34
      $initial_investment_required2 = $target_amount / $total_fund_value2;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price2 = $target_amount * $monthly_debt_return_rate2;
      //Future Value of Debt Fund
      $future_value_of_debt_fund2 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund2 = $monthly_appreciation_price2*((pow((1+$monthly_equity_return_rate2),$number_of_months_period2)-1)/$monthly_equity_return_rate2);
      //Total Fund Value AR32+AR33
      $total_fund_value2 = $future_value_of_debt_fund2+$future_value_of_equity_fund2;
  //Total Fund Value1 End

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


      //Initial Investment Required Q7/AR34
      $initial_investment_required3 = $target_amount / $total_fund_value3;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price3 = $target_amount * $monthly_debt_return_rate3;
      //Future Value of Debt Fund
      $future_value_of_debt_fund3 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund3 = $monthly_appreciation_price3*((pow((1+$monthly_equity_return_rate3),$number_of_months_period3)-1)/$monthly_equity_return_rate3);
      //Total Fund Value AR32+AR33
      $total_fund_value3 = $future_value_of_debt_fund3+$future_value_of_equity_fund3;
  //Total Fund Value1 End

  //Total Fund Value1 Start
     //Number of Months
     $number_of_months_period4 = $period2*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate4 =  pow((1+$debt_rate1/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate4 =  pow((1+$equity_rate1/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation4 = $initial_investment*$monthly_debt_return_rate4;
      //Future Value of Debt Fund
      $future_value_of_debt_fund4 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund4 = $monthly_appreciation4*((pow((1+$monthly_equity_return_rate4),$number_of_months_period4)-1)/$monthly_equity_return_rate4);
      //Total Fund Value AR32+AR33
      $total_fund_value4 = $future_value_of_debt_fund4+$future_value_of_equity_fund4;


      //Initial Investment Required Q7/AR34
      $initial_investment_required4 = $target_amount / $total_fund_value4;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price4 = $target_amount * $monthly_debt_return_rate4;
      //Future Value of Debt Fund
      $future_value_of_debt_fund4 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund4 = $monthly_appreciation_price4*((pow((1+$monthly_equity_return_rate4),$number_of_months_period4)-1)/$monthly_equity_return_rate4);
      //Total Fund Value AR32+AR33
      $total_fund_value4 = $future_value_of_debt_fund4+$future_value_of_equity_fund4;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period5 = $period2*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate5 =  pow((1+$debt_rate2/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate5 =  pow((1+$equity_rate2/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation5 = $initial_investment*$monthly_debt_return_rate5;
      //Future Value of Debt Fund
      $future_value_of_debt_fund5 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund5 = $monthly_appreciation5*((pow((1+$monthly_equity_return_rate5),$number_of_months_period5)-1)/$monthly_equity_return_rate5);
      //Total Fund Value AR32+AR33
      $total_fund_value5 = $future_value_of_debt_fund5+$future_value_of_equity_fund5;


      //Initial Investment Required Q7/AR34
      $initial_investment_required5 = $target_amount / $total_fund_value5;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price5 = $target_amount * $monthly_debt_return_rate5;
      //Future Value of Debt Fund
      $future_value_of_debt_fund5 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund5 = $monthly_appreciation_price5*((pow((1+$monthly_equity_return_rate5),$number_of_months_period5)-1)/$monthly_equity_return_rate5);
      //Total Fund Value AR32+AR33
      $total_fund_value5 = $future_value_of_debt_fund5+$future_value_of_equity_fund5;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period6 = $period2*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate6 =  pow((1+$debt_rate3/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate6 =  pow((1+$equity_rate3/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation6 = $initial_investment*$monthly_debt_return_rate6;
      //Future Value of Debt Fund
      $future_value_of_debt_fund6 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund6 = $monthly_appreciation6*((pow((1+$monthly_equity_return_rate6),$number_of_months_period6)-1)/$monthly_equity_return_rate6);
      //Total Fund Value AR32+AR33
      $total_fund_value6 = $future_value_of_debt_fund6+$future_value_of_equity_fund6;


      //Initial Investment Required Q7/AR34
      $initial_investment_required6 = $target_amount / $total_fund_value6;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price6 = $target_amount * $monthly_debt_return_rate6;
      //Future Value of Debt Fund
      $future_value_of_debt_fund6 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund6 = $monthly_appreciation_price6*((pow((1+$monthly_equity_return_rate6),$number_of_months_period6)-1)/$monthly_equity_return_rate6);
      //Total Fund Value AR32+AR33
      $total_fund_value6 = $future_value_of_debt_fund6+$future_value_of_equity_fund6;
  //Total Fund Value1 End

 //Total Fund Value1 Start
     //Number of Months
     $number_of_months_period7 = $period3*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate7 =  pow((1+$debt_rate1/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate7 =  pow((1+$equity_rate1/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation7 = $initial_investment*$monthly_debt_return_rate7;
      //Future Value of Debt Fund
      $future_value_of_debt_fund7 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund7 = $monthly_appreciation7*((pow((1+$monthly_equity_return_rate7),$number_of_months_period7)-1)/$monthly_equity_return_rate7);
      //Total Fund Value AR32+AR33
      $total_fund_value7 = $future_value_of_debt_fund7+$future_value_of_equity_fund7;


      //Initial Investment Required Q7/AR34
      $initial_investment_required7 = $target_amount / $total_fund_value7;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price7 = $target_amount * $monthly_debt_return_rate7;
      //Future Value of Debt Fund
      $future_value_of_debt_fund7 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund7 = $monthly_appreciation_price7*((pow((1+$monthly_equity_return_rate7),$number_of_months_period7)-1)/$monthly_equity_return_rate7);
      //Total Fund Value AR32+AR33
      $total_fund_value7 = $future_value_of_debt_fund7+$future_value_of_equity_fund7;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period8 = $period3*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate8 =  pow((1+$debt_rate2/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate8 =  pow((1+$equity_rate2/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation8 = $initial_investment*$monthly_debt_return_rate8;
      //Future Value of Debt Fund
      $future_value_of_debt_fund8 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund8 = $monthly_appreciation8*((pow((1+$monthly_equity_return_rate8),$number_of_months_period8)-1)/$monthly_equity_return_rate8);
      //Total Fund Value AR32+AR33
      $total_fund_value8 = $future_value_of_debt_fund8+$future_value_of_equity_fund8;


      //Initial Investment Required Q7/AR34
      $initial_investment_required8 = $target_amount / $total_fund_value8;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price8 = $target_amount * $monthly_debt_return_rate8;
      //Future Value of Debt Fund
      $future_value_of_debt_fund8 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund8 = $monthly_appreciation_price8*((pow((1+$monthly_equity_return_rate8),$number_of_months_period8)-1)/$monthly_equity_return_rate8);
      //Total Fund Value AR32+AR33
      $total_fund_value8 = $future_value_of_debt_fund8+$future_value_of_equity_fund8;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period9 = $period3*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate9 =  pow((1+$debt_rate3/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate9 =  pow((1+$equity_rate3/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation9 = $initial_investment*$monthly_debt_return_rate9;
      //Future Value of Debt Fund
      $future_value_of_debt_fund9 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund9 = $monthly_appreciation9*((pow((1+$monthly_equity_return_rate9),$number_of_months_period9)-1)/$monthly_equity_return_rate9);
      //Total Fund Value AR32+AR33
      $total_fund_value9 = $future_value_of_debt_fund9+$future_value_of_equity_fund9;


      //Initial Investment Required Q7/AR34
      $initial_investment_required9 = $target_amount / $total_fund_value9;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price9 = $target_amount * $monthly_debt_return_rate9;
      //Future Value of Debt Fund
      $future_value_of_debt_fund9 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund9 = $monthly_appreciation_price9*((pow((1+$monthly_equity_return_rate9),$number_of_months_period9)-1)/$monthly_equity_return_rate9);
      //Total Fund Value AR32+AR33
      $total_fund_value9 = $future_value_of_debt_fund9+$future_value_of_equity_fund9;
  //Total Fund Value1 End

  //Total Fund Value1 Start
     //Number of Months
     $number_of_months_period10 = $period4*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate10 =  pow((1+$debt_rate1/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate10 =  pow((1+$equity_rate1/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation10 = $initial_investment*$monthly_debt_return_rate10;
      //Future Value of Debt Fund
      $future_value_of_debt_fund10 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund10 = $monthly_appreciation10*((pow((1+$monthly_equity_return_rate10),$number_of_months_period10)-1)/$monthly_equity_return_rate10);
      //Total Fund Value AR32+AR33
      $total_fund_value10 = $future_value_of_debt_fund10+$future_value_of_equity_fund10;


      //Initial Investment Required Q7/AR34
      $initial_investment_required10 = $target_amount / $total_fund_value10;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price10 = $target_amount * $monthly_debt_return_rate10;
      //Future Value of Debt Fund
      $future_value_of_debt_fund10 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund10 = $monthly_appreciation_price10*((pow((1+$monthly_equity_return_rate10),$number_of_months_period10)-1)/$monthly_equity_return_rate10);
      //Total Fund Value AR32+AR33
      $total_fund_value10 = $future_value_of_debt_fund10+$future_value_of_equity_fund10;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period11 = $period4*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate11 =  pow((1+$debt_rate2/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate11 =  pow((1+$equity_rate2/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation11 = $initial_investment*$monthly_debt_return_rate11;
      //Future Value of Debt Fund
      $future_value_of_debt_fund11 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund11 = $monthly_appreciation11*((pow((1+$monthly_equity_return_rate11),$number_of_months_period11)-1)/$monthly_equity_return_rate11);
      //Total Fund Value AR32+AR33
      $total_fund_value11 = $future_value_of_debt_fund11+$future_value_of_equity_fund11;

      //Initial Investment Required Q7/AR34
      $initial_investment_required11 = $target_amount / $total_fund_value11;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price11 = $target_amount * $monthly_debt_return_rate11;
      //Future Value of Debt Fund
      $future_value_of_debt_fund11 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund11 = $monthly_appreciation_price11*((pow((1+$monthly_equity_return_rate11),$number_of_months_period11)-1)/$monthly_equity_return_rate11);
      //Total Fund Value AR32+AR33
      $total_fund_value11 = $future_value_of_debt_fund11+$future_value_of_equity_fund11;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period12 = $period4*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate12 =  pow((1+$debt_rate3/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate12 =  pow((1+$equity_rate3/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation12 = $initial_investment*$monthly_debt_return_rate12;
      //Future Value of Debt Fund
      $future_value_of_debt_fund12 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund12 = $monthly_appreciation12*((pow((1+$monthly_equity_return_rate12),$number_of_months_period12)-1)/$monthly_equity_return_rate12);
      //Total Fund Value AR32+AR33
      $total_fund_value12 = $future_value_of_debt_fund12+$future_value_of_equity_fund12;


      //Initial Investment Required Q7/AR34
      $initial_investment_required12 = $target_amount / $total_fund_value12;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price12 = $target_amount * $monthly_debt_return_rate12;
      //Future Value of Debt Fund
      $future_value_of_debt_fund12 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund12 = $monthly_appreciation_price12*((pow((1+$monthly_equity_return_rate12),$number_of_months_period12)-1)/$monthly_equity_return_rate12);
      //Total Fund Value AR32+AR33
      $total_fund_value12 = $future_value_of_debt_fund12+$future_value_of_equity_fund12;
  //Total Fund Value1 End

   //Total Fund Value1 Start
     //Number of Months
     $number_of_months_period13 = $period5*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate13 =  pow((1+$debt_rate1/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate13 =  pow((1+$equity_rate1/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation13 = $initial_investment*$monthly_debt_return_rate13;
      //Future Value of Debt Fund
      $future_value_of_debt_fund13 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund13 = $monthly_appreciation13*((pow((1+$monthly_equity_return_rate13),$number_of_months_period13)-1)/$monthly_equity_return_rate13);
      //Total Fund Value AR32+AR33
      $total_fund_value13 = $future_value_of_debt_fund13+$future_value_of_equity_fund13;


      //Initial Investment Required Q7/AR34
      $initial_investment_required13 = $target_amount / $total_fund_value13;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price13 = $target_amount * $monthly_debt_return_rate13;
      //Future Value of Debt Fund
      $future_value_of_debt_fund13 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund13 = $monthly_appreciation_price13*((pow((1+$monthly_equity_return_rate13),$number_of_months_period13)-1)/$monthly_equity_return_rate13);
      //Total Fund Value AR32+AR33
      $total_fund_value13 = $future_value_of_debt_fund13+$future_value_of_equity_fund13;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period14 = $period5*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate14 =  pow((1+$debt_rate2/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate14 =  pow((1+$equity_rate2/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation14 = $initial_investment*$monthly_debt_return_rate14;
      //Future Value of Debt Fund
      $future_value_of_debt_fund14 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund14 = $monthly_appreciation14*((pow((1+$monthly_equity_return_rate14),$number_of_months_period14)-1)/$monthly_equity_return_rate14);
      //Total Fund Value AR32+AR33
      $total_fund_value14 = $future_value_of_debt_fund14+$future_value_of_equity_fund14;

      //Initial Investment Required Q7/AR34
      $initial_investment_required14 = $target_amount / $total_fund_value14;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price14 = $target_amount * $monthly_debt_return_rate14;
      //Future Value of Debt Fund
      $future_value_of_debt_fund14 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund14 = $monthly_appreciation_price14*((pow((1+$monthly_equity_return_rate14),$number_of_months_period14)-1)/$monthly_equity_return_rate14);
      //Total Fund Value AR32+AR33
      $total_fund_value14 = $future_value_of_debt_fund14+$future_value_of_equity_fund14;
  //Total Fund Value1 End

  //Total Fund Value2 Start
     //Number of Months
     $number_of_months_period15 = $period5*12;
     //Monthly Debt Return (1+Q9%)^(1/12)-1
     $monthly_debt_return_rate15 =  pow((1+$debt_rate3/100),(1/12))-1;
     //Monthly Equity Return (1+Q10%)^(1/12)-1
      $monthly_equity_return_rate15 =  pow((1+$equity_rate3/100),(1/12))-1;
      //Monthly Appreciation Q7*AR29
      $monthly_appreciation15 = $initial_investment*$monthly_debt_return_rate15;
      //Future Value of Debt Fund
      $future_value_of_debt_fund15 = $initial_investment;
      //Future Value of Equity Fund AR31*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund15 = $monthly_appreciation15*((pow((1+$monthly_equity_return_rate15),$number_of_months_period15)-1)/$monthly_equity_return_rate15);
      //Total Fund Value AR32+AR33
      $total_fund_value15 = $future_value_of_debt_fund15+$future_value_of_equity_fund15;


      //Initial Investment Required Q7/AR34
      $initial_investment_required15 = $target_amount / $total_fund_value15;
      //Monthly Appreciation Price Q7*AR29
      $monthly_appreciation_price15 = $target_amount * $monthly_debt_return_rate15;
      //Future Value of Debt Fund
      $future_value_of_debt_fund15 = $target_amount;
       //Future Value of Equity Fund AR36*(((1+AR30)^(AR28)-1)/AR30)
      $future_value_of_equity_fund15 = $monthly_appreciation_price15*((pow((1+$monthly_equity_return_rate15),$number_of_months_period15)-1)/$monthly_equity_return_rate15);
      //Total Fund Value AR32+AR33
      $total_fund_value15 = $future_value_of_debt_fund12+$future_value_of_equity_fund15;
  //Total Fund Value1 End
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
                    <h2 class="page-title">STP Need Based Ready Reckoner</h2>
                </div>
            </div>
        </div>
    </div>
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.stpGoalPlanningValueReadyRecoknerBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    

                    

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                    <div class="outputTableHolder">
                        <h5 class="midheading">STP Need Based Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>
                                        <strong>Target Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($target_amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Transfer Mode</strong>
                                    </td>
                                    <td>
                                        @if(isset($monthly_transfer_mode) && $monthly_transfer_mode=='CA')
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
                        @if(isset($is_note) && $is_note == 1)
                            <h1 class="midheading">Comments</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>{{$note}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        <h5 class="text-center">Initial Investment Required</h5>
                        <div class="roundBorderHolder">
                            <table class="table table-bordered table-striped text-center" style="background: #fff;">
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
                                            <strong>₹ {{price_in_lakh($initial_investment_required1)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required4)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required7)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required10)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required13)}} </strong>
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
                                            <strong>₹ {{price_in_lakh($initial_investment_required2)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required5)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required8)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required11)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required14)}} </strong>
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
                                            <strong>₹ {{price_in_lakh($initial_investment_required3)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required6)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required9)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required12)}} </strong>
                                        </td>
                                        <td>
                                            <strong>₹ {{price_in_lakh($initial_investment_required15)}} </strong>
                                        </td>
                                    </tr>
        
                                    <tr>
                                        <td colspan="6" style="text-align: right"><strong>(₹ in Lacs)</strong></td>
                                    </tr>
        
                                </tbody>
                            </table>
                        </div>
                            <br/>
                            

                            
                            <p class="text-left">* Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                                *Returns are not guaranteed. The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}</p>

                            @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.stpGoalPlanningValueReadyRecoknerBack')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                                <a href="{{route('frontend.stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.stpGoalPlanningValueReadyRecoknerOutputDownloadPDF')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
