<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Result</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    @include('frontend.calculators.common.pdf_style')
</head>
<body class="styleApril">
    
@include('frontend.calculators.common.header')
        
<main class="mainPdf">

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

    <div style="padding: 0 0%;">
        <h1 class="pdfTitie">STP Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody>
                <tr>
                    <td style="width: 50%;">
                        <strong>Initial Investment</strong>
                    </td>
                    <td style="width: 50%;">
                        <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($initial_investment)}}
                    </td>
                </tr>
                <tr>
                    <td style="width: 50%;">
                        <strong>Transfer Mode</strong>
                    </td>
                    <td style="width: 50%;">
                        @if($monthly_transfer_mode=='CA')
                            Capital Appreciation
                        @else
                            @if(isset($fixed_transfer_mode) && $fixed_transfer_mode=='FP')
                                {{$fixed_percent?number_format($fixed_percent, 2, '.', ''):0}} % of Initial Investment
                            @else
                                <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($fixed_amount)}}
                            @endif
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <h1 class="pdfTitie">Future Value of Initial Investment</h1>
    <div class="roundBorderHolder">
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
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value1)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value4)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value7)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value10)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value13)}} </strong>
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
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value2)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value5)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value8)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value11)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value14)}} </strong>
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
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value3)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value6)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value9)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value12)}} </strong>
                </td>
                <td>
                    <strong><span class="pdfRupeeIcon">&#8377;</span> {{price_in_lakh($total_fund_value15)}} </strong>
                </td>
            </tr>
            <tr>
                <td colspan="6" style="text-align: right;padding-right:30px;"><strong>(<span class="pdfRupeeIcon">&#8377;</span> in Lacs)</strong></td>
            </tr>
    
            </tbody>
        </table>
    </div>
    
    {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            
    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','STP_Future_Value_Ready_Recokner')->first();
    if(!empty($note_data1)){
    @endphp
    {!!$note_data1->description!!}
    @php } @endphp
    Report Date : {{date('d/m/Y')}}
</main>
    @include('frontend.calculators.common.watermark')
    @if($footer_branding_option == "all_pages")
        @include('frontend.calculators.common.footer')
    @endif
    @include('frontend.calculators.suggested.pdf')

</body>
</html>
