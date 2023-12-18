<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #001028;
            text-decoration: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            position: relative;
            width: 21cm;
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
            border: 1px solid #b8b8b8;
            padding: 6px 20px;
            font-weight: normal;
            color: #000;
        }

        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
        }

        .table-bordered th, .table-bordered td{
            padding: 10px;
            font-size: 18px;
        }

        h1 {
            font-size: 20px !important;
            color: #131f55 !important;
            margin-bottom: 0 !important;
            margin-top: 15px !important;
        }

        .page-break {
            page-break-after: always;
        }

        @page {
            margin-top: 160px
        }

        header {
            position: fixed;
            top: -130px;
            left: 0px;
            right: 94px;
            height: 50px;
        }

        footer {
            position: fixed;
            bottom: -20px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: 42%;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
        }
    </style>
</head>
<body>
<main style="width: 760px; margin-left: 20px;">
    <SALESPRESENTER_BEFORE/>
    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">&nbsp;</td>
                <td style="text-align:right; border:0;" align="left" valign="middle"><img style="display:inline-block;" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>

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

    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">STP Ready Recokner @if(isset($clientname)) For {{$clientname?$clientname:''}} @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Target Amount</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($target_amount)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;width: 50%;">
                    <strong>Transfer Mode</strong>
                </td>
                <td style="text-align: left;width: 50%;">
                    @if($monthly_transfer_mode=='CA')
                        Capital Appreciation
                    @else
                        @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                            {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                        @else
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($fixed_amount)}}
                        @endif
                    @endif
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Initial Investment Required</h1>
    <table >
        <tbody>
        <tr>
            <th><strong>Rate of Return</strong></th>
            <th colspan="5"><strong>STP Period (Years)</strong></th>
        </tr>
        <tr>
            <td><strong>Debt&nbsp; |&nbsp; Equity</strong></td>
            <th><strong>{{$period1?$period1:''}}</strong></th>
            <th><strong>{{$period2?$period2:''}}</strong></th>
            <th><strong>{{$period3?$period3:''}}</strong></th>
            <th><strong>{{$period4?$period4:''}}</strong></th>
            <th><strong>{{$period5?$period5:''}}</strong></th>
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
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required1)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required4)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required7)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required10)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required13)}} </strong>
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
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required2)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required5)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required8)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required11)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required14)}} </strong>
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
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required3)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required6)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required9)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required12)}} </strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh($initial_investment_required15)}} </strong>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: right"><strong>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> in Lacs)</strong></td>
        </tr>

        </tbody>
    </table>
    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','STP_Goal_Planning_Value_Ready_Recokner')->first();
    if(!empty($note_data1)){
    @endphp
    {!!$note_data1->description!!}
    @php } @endphp
    
    <p>Report Date : {{date('d/m/Y')}}</p>
    @include('frontend.calculators.common.footer')
    @include('frontend.calculators.suggested.pdf-app')
</main>
</body>
</html>
