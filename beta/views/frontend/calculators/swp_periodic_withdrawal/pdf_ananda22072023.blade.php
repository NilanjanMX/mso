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
                width: 100%;
                height: 29.7cm;
                margin: 0 auto;
                color: #001028;
                font-size: 14px;
            }

            table {
                width: 100%;
                border-spacing: 0;
                margin-bottom: 30px;
                border: 1px solid #b8b8b8;
                border-top:0;
            }

            table th,
            table td {
                text-align: center;
                /*border: 1px solid #b8b8b8;*/
                padding: 6px 20px;
                font-weight: normal;
                color: #000;
            }

            table {
                margin: 0;
            }
            table tr th + th,
            table tr td + td {
                border-left: 1px solid #b8b8b8;
            }
            table th {
                font-weight: bold;
                background: #a9f3ff;
            }

            .table-bordered th, .table-bordered td{
                padding: 5px;
                font-size: 15px;
                border-top: 1px solid #b8b8b8;
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
                margin-top: 100px
            }

            header {
                position: fixed;
                top: -130px;
                left: 0px;
                right: 0px;
                height: 50px;
            }

            footer {
                /*position: fixed;
                bottom: -10px;
                left: 0px;
                right: 0px;
                height: 50px;*/
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
            table.leftright tr td:first-child {
                text-align:left;
            }
            table.leftright tr td:last-child {
                text-align:right;
            }
        </style>
    </head>
    <body>
        <main>
            <SALESPRESENTER_BEFORE/>
            <header>
                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                    <tbody>
                    <tr>
                        <td style="text-align:left; border:0;">&nbsp;</td>
                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:130px; margin-top: 15px;" src="{{$company_logo}}" alt=""></td>
                    </tr>
                    </tbody>
                </table>
            </header>
            <div style="padding: 0 5%;">
                <h1 style="margin:0 auto; color: #000;font-size:16px;margin-bottom:20px !important;text-align:center; background-color: #a9f3ff; padding: 10px 0; width:90%;">
                    
                    @if(isset($details)) {{$details}} @else Periodic Withdrawal Calculation @endif @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif
                </h1>

                <div>
                    @php
                        $calcType = '';
                        $terminalBalance = 0;
                        $endValue = 0;
                        if($investmentmode == 1 && $def==1 && $swpmode == 1){
                            $calcType = 1;
                            $swpperiod = $withdrawal;
                            $return = pow((1+$total1/100),$interval) - 1;
                            $maxMoneyBack = round(($return * $initial)/(1-pow((1+$return),-$swpperiod)));
                            $lumpsumForSwp1 = ($withdrawamount * (1-pow((1+$return),-$swpperiod)))/$return;
                            
                            if((($initial-$lumpsumForSwp1)*pow((1+$return),$swpperiod)) < 100){
                                $terminalBalance = 0;
                                }
                            else{
                                $terminalBalance = (($initial-$lumpsumForSwp1)*pow((1+$return),$swpperiod));
                                }
                        }else if($investmentmode == 1 && $def==1 && $swpmode == 2){
                           $sendable = "tst";
                            $calcType = 2;
                            $presentValue = $initial;
                            $lastWithdrawPeriod = '';
                            if(isset($period1))
                            {
                            if($period1 > 0){
                                $value1 = $presentValue * pow((1+$total1/100),$period1);
                                $lastWithdrawPeriod = $period1;
                                }
                            else
                                $value1 = $presentValue;
                                
                                $balance1 = $value1 - $amount1;
                                $sendable .= ",".$value1;
                                $balance5 = $balance1;
                            }
                            
                            if(isset($period2))
                            {
                            if($period2 > 0){
                                $value2 = $balance1 * pow((1+$total1/100),($period2-$period1));
                                $lastWithdrawPeriod = $period2;
                                }
                            else
                                $value2 = $balance1;
                                
                                $balance2 = $value2 - $amount2;
                                $sendable .= ",".$value2;
                                $balance5 = $balance2;
                            }
                            
                            if(isset($period3))
                            {
                            if($period3 > 0){
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2));
                                $lastWithdrawPeriod = $period3;
                                }
                            else
                                $value3 = $balance2;
                                
                                $balance3 = $value3 - $amount3;
                                $sendable .= ",".$value3;
                                $balance5 = $balance3;
                            }
                            
                            if(isset($period4))
                            {
                            if($period4 > 0){
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3));
                                $lastWithdrawPeriod = $period4;
                                }
                            else
                                $value4 = $balance3;
                                
                                $balance4 = $value4 - $amount4;
                                $sendable .= ",".$value4;
                                $balance5 = $balance4;
                            }
                            
                            if(isset($period5))
                            {
                            if($period5 > 0){
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4));
                                $lastWithdrawPeriod = $period5;
                                }
                            else
                                $value5 = $balance4;
                                
                                $balance5 = $value5 - $amount5;
                                $balance5 = $balance5;
                                $sendable .= ",".$value5;
                            }
                            
                            
                            $endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
                            
                        }else if($investmentmode == 1 && $def==2){
                           $calcType = 3;
                            $futureValue = $initial * pow((1+$total1/100),$deferment);
                            $swpperiod = $installments;
                            $return1 = pow((1+$distribution/100),$moneyback) - 1;
                            $maxMoneyBack = round($futureValue/(1+(1/$return1) * (1-pow((1+$return1),(-$swpperiod+1)))));
                            $lumpsumswp1 = ($eachwithdraw+($eachwithdraw/$return1)*(1-pow((1+$return1),(-$swpperiod+1))));
                            
                            if(($futureValue - $lumpsumswp1)*pow((1+$distribution/100),(($installments-1)*$moneyback)) < 100)
                                $terminalBalance = 0;
                            else
                                $terminalBalance = ($futureValue - $lumpsumswp1)*pow((1+$distribution/100),(($installments-1)*$moneyback));
                                
                                $sendable = "est,".$maxMoneyBack;
                        }else if($investmentmode == 2 && $def==1 && $swpmode == 1){
                            $calcType = 4;
                            $swpPeriod = $withdrawal;
                            $return1 = pow((1+$total1/100),$interval)-1;
                            $valueAtEndOfInvPer = (1+$total1/100) * $initial * (pow((1+$total1/100),$invper)-1)/($total1/100);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$total1/100),($withdrawal * $interval - $invper));
                            $presentValue = $futureValue / (pow((1+$total1/100),($withdrawal * $interval))) ;
                            
                            $maxMoneyBack = round(($presentValue * $return1)/(1-pow((1+$return1),-$swpPeriod)));
                            
                            $lumpsumSwp = ($withdrawamount * (1-pow((1+$return1),-$swpPeriod)))/$return1;
                            $terminalBal = ($presentValue - $lumpsumSwp) * pow((1+$total1/100),($interval * $withdrawal));
                            
                            $sendable = "est,".$maxMoneyBack;
                            
                        } else if($investmentmode == 2 && $def==1 && $swpmode == 2) {
                            $calcType = 5;
                            $sendable = "tst";
                            $presentValue = $initial + $initial/($total1/100) * (1-pow((1+$total1/100),(-$invper+1)));
                            
                            $lastWithdrawPeriod = '';
                            if(isset($period1))
                            {
                            $lastWithdrawPeriod = $period1;
                            if($period1 <= $invper){
                                $value1 = (1+$total1/100)*$initial*((pow((1+$total1/100),$period1)-1)/($total1/100));
                                
                                
                                }
                            else
                                $value1 = (1+$total1/100)*$initial*((pow((1+$total1/100),$period1)-1)/($total1/100))*pow((1+$total1/100),($period1-$invper));
                                
                                $balance1 = $value1 - $amount1;
                                $sendable .= ",".$value1;
                                $balance5 = $balance1;
                            }
                            
                            if(isset($period2))
                            {
                            $lastWithdrawPeriod = $period2;
                                $monthlyReturn = $total1/100;
                            if($period2 <= $invper){
                                $value2 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period2-$period1))-1)/($total1/100))+$balance1*pow((1+$total1/100),($period2-$period1));
                                
                                }
                            
                            else if($period2 > $invper && $period1 <= $invper)
                            {
                              // $value2 = $balance1 * pow((1+$total1/100),($period2-$period1)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper-$period1))-1)/$monthlyReturn) * pow((1+$total1/100),($period2-$invper));
                            
                                $value2 =   (1+$total1/100)*$initial*  (((pow((1+$total1/100),($invper-$period1))-1)/($total1/100)) * pow((1+$total1/100),($period2-$invper)))+$balance1*pow((1+$total1/100),($period2-$period1));
                            }
                            else if($period2 > $invper && $period1 > $invper)
                            {
                                $value2 = $balance1 * pow((1+$total1/100),($period2-$period1));
                            }
                            else if($invper == $period2)
                            {
                                $value2 = $balance1 * pow((1+$total1/100),($period2-$period1)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period2-$period1)*12))-1)/$monthlyReturn);
                            }
                            else
                                $value2 = $balance1;
                                
                                $balance2 = $value2 - $amount2;
                                $sendable .= ",".$value2;
                                $balance5 = $balance2;
                            }
                            
                            if(isset($period3))
                            {
                            $lastWithdrawPeriod = $period3;
                                $monthlyReturn = $total1/100;
                            if($period3 <= $invper){
                                $value3 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period3-$period2))-1)/($total1/100))+$balance2*pow((1+$total1/100),($period3-$period2));
                                
                                }
                            
                            else if($period3 > $invper && $period2 <= $invper)
                            {
                               //$value3 = $balance2 * pow((1+$total1/100),($period3-$period2)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper-$period2))-1)/$monthlyReturn) * pow((1+$total1/100),($period3-$invper));
                            
                                $value3 = (1+$total1/100)*$initial*  (((pow((1+$total1/100),($invper-$period2))-1)/($total1/100)) * pow((1+$total1/100),($period3-$invper)))+$balance2*pow((1+$total1/100),($period3-$period2));
                            }
                            else if($period3 > $invper && $period2 > $invper)
                            {
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2));
                            }
                            else if($invper == $period3)
                            {
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($period3-$period2))-1)/$monthlyReturn);
                            }
                            else
                                $value3 = $balance2;
                                
                                $balance3 = $value3 - $amount3;
                                $sendable .= ",".$value3;
                                $balance5 = $balance3;
                            }
                            
                            if(isset($period4))
                            {
                            $lastWithdrawPeriod = $period4;
                            $monthlyReturn = $total1/100;
                            if($period4 <= $invper){
                                $value4 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period4-$period3))-1)/($total1/100))+$balance3*pow((1+$total1/100),($period4-$period3));
                                
                                }
                            
                            else if($period4 > $invper && $period3 <= $invper)
                            {
                               //$value4 = $balance3 * pow((1+$total1/100),($period4-$period3)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper-$period3))-1)/$monthlyReturn) * pow((1+$total1/100),($period4-$invper));
                            
                                $value4 = (1+$total1/100)*$initial*  (((pow((1+$total1/100),($invper-$period3))-1)/($total1/100)) * pow((1+$total1/100),($period4-$invper)))+$balance3*pow((1+$total1/100),($period4-$period3));
                            }
                            else if($period4 > $invper && $period3 > $invper)
                            {
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3));
                            }
                            else if($invper == $period4)
                            {
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($period4-$period3))-1)/$monthlyReturn);
                            }
                            else
                                $value4 = $balance3;
                                
                                $balance4 = $value4 - $amount4;
                                $sendable .= ",".$value4;
                                $balance5 = $balance4;
                            }
                            
                            if(isset($period5))
                            {
                            $lastWithdrawPeriod = $period5;
                            $monthlyReturn = $total1/100;
                            if($period5 <= $invper){
                                $value5 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period5-$period4))-1)/($total1/100))+$balance4*pow((1+$total1/100),($period5-$period4));
                                
                                //dd("here 1");
                                }
                            
                            else if($period5 > $invper && $period4 <= $invper)
                            {
                               //$value5 = $balance4 * pow((1+$total1/100),($period5-$period4)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper-$period4))-1)/$monthlyReturn) * pow((1+$total1/100),($period5-$invper));
                               $value5 = (1+$total1/100)*$initial*  (((pow((1+$total1/100),($invper-$period4))-1)/($total1/100)) * pow((1+$total1/100),($period5-$invper)))+$balance4*pow((1+$total1/100),($period5-$period4));
                               //dd("here 2");
                            }
                            else if($period5 > $invper && $period4 > $invper)
                            {
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4));
                                //("here 3");
                            }
                            else if($invper == $period5)
                            {
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($period5-$period4))-1)/$monthlyReturn);
                                //dd("here 4");
                            }
                            else{
                            //dd("here 5");
                                $value5 = $balance4;
                                }
                                
                                $balance5 = $value5 - $amount5;
                                $sendable .= ",".$value5;
                                $balance5 = $balance5;
                            }
                            
                            
                            $endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
                           //else if($period5 < $invper)
                           //$endValue = $balance5 * pow((1+$total1/100),($invper-$period5))+((1+$total1/100)*$initial*((pow((1+$total1/100),($invper-$period5))-1)/($total1/100))*pow((1+$total1/100),($lastwithdraw-$invper)));
                           
                           //dd($endValue);
                        } else if($investmentmode == 2 && $def==2) {
                            $calcType = 6;
                            //$futureValueEOI = (1+$total1/100) * $initial * pow((1+$total1/100),$deferment);
                            
                            $swpperiod = $installments;
                            $return1 = pow((1+$distribution/100),$moneyback) - 1;
                            
                            $valueAtEndOfInvPer = (1+$total1/100) * $initial * (pow((1+$total1/100),$invper)-1)/($total1/100);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$total1/100),$deferment);
                            $maxMoneyBack = round($futureValue/(1+(1/$return1) * (1-pow((1+$return1),(-$swpperiod+1)))));
                            
                            $lumpsumswp = ($eachwithdraw+($eachwithdraw/$return1)*(1-pow((1+$return1),(-$swpperiod+1))));
                            if(($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback)) < 100)
                                $terminalBalance = 0;
                            else
                                $terminalBalance = ($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback));
                                
                                
                                //dd($terminalBalance);
                        } else if($investmentmode == 3 && $def==1 && $swpmode == 1) {
                           $calcType = 7;
                            $sipMonths = $invper * 12;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            $swpPeriod = $withdrawal;
                            $swpperiod = $swpPeriod;
                            $return1 = pow((1+$total1/100),$interval)-1;
                            $valueAtEndOfInvPer = (1+$monthlyReturn) * $initial * (pow((1+$monthlyReturn),$sipMonths)-1)/($monthlyReturn);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$total1/100),($withdrawal * $interval - $invper));
                            $presentValue = $futureValue / (pow((1+$total1/100),($withdrawal * $interval))) ;
                            
                            $maxMoneyBack = round(($presentValue * $return1)/(1-pow((1+$return1),-$swpPeriod)));
                            
                            $lumpsumSwp = ($withdrawamount * (1-pow((1+$return1),-$swpPeriod)))/$return1;
                            $terminalBalance = ($presentValue - $lumpsumSwp) * pow((1+$total1/100),($interval * $withdrawal));
                        } else if($investmentmode == 3 && $def==1 && $swpmode == 2) {
                            $calcType = 8;
                            $sendable = "tst";
                            $Period = $invper * 12;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            $presentValue = $initial + $initial * (1-pow((1+$monthlyReturn),(-$Period+1)));
                            if(isset($period1))
                            {
                            $lastWithdrawPeriod = $period1;
                            if($period1 > $invper){
                                $value1 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper*12))-1)/$monthlyReturn);
                               
                                //$lastWithdrawPeriod = $period1;
                                }
                                else if($period1 <= $invper)
                                {
                                   
                                   $value1 =  (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($period1*12))-1)/$monthlyReturn);
                                }
                            else{
                                
                                $value1 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),($invper*12))-1)/$monthlyReturn);
                            }
                                $sendable .= ",".$value1;
                                $balance1 = $value1 - $amount1;
                                $balance5 = $balance1;
                            }
                            
                            if(isset($period2))
                            {
                            $lastWithdrawPeriod = $period2;
                            if($period2 > $invper && $period1 > $invper){
                                $value2 = $balance1 * pow((1+$monthlyReturn),($period2-$period1));
                               // $lastWithdrawPeriod = $period2;
                                }
                                else if($period2 <= $invper)
                                {
                                    $value2 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period2-$period1)*12))-1)/$monthlyReturn) + $balance1 * pow((1+$monthlyReturn),(($period2-$period1)*12));
                                }
                            else if($period2 < $invper && $period1 < $invper)
                            {
                                $value2 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period2-$period1)*12))-1)/$monthlyReturn);
                            }
                            else if($period2 > $invper && $period1 <= $invper)
                            {
                               //$value2 = $balance1 * pow((1+$total1/100),($period2-$period1)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($invper-$period1)*12))-1)/$monthlyReturn) * pow((1+$total1/100),($period2-$invper));
                               $value2 = (1+$monthlyReturn)*$initial*(((pow((1+$monthlyReturn),(($invper-$period1)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period2-$invper) * 12))) + ($balance1 * pow((1+$monthlyReturn),(($period2-$period1)*12)));
                            }
                            
                            else if($invper == $period2)
                            {
                                $value2 = $balance1 * pow((1+$monthlyReturn),($period2-$period1)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period2-$period1)*12))-1)/$monthlyReturn);
                            }
                            else
                                $value2 = $balance1;
                                $sendable .= ",".$value2;
                                $balance2 = $value2 - $amount2;
                                $balance5 = $balance2;
                            }
                            
                            
                            if(isset($period3))
                            {
                            $lastWithdrawPeriod = $period3;
                            if($period3 > $invper && $period2 > $invper){
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2));
                                //$lastWithdrawPeriod = $period3;
                                }
                                else if($period3 <= $invper)
                                {
                                    $value3 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period3-$period2)*12))-1)/$monthlyReturn) + $balance2 * pow((1+$monthlyReturn),(($period3-$period2)*12));
                                }
                            else if($period3 < $invper && $period2 < $invper)
                            {
                                $value3 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period3-$period2)*12))-1)/$monthlyReturn);
                            }
                            else if($period3 > $invper && $period2 <= $invper)
                            {
                               //$value3 = $balance2 * pow((1+$total1/100),($period3-$period2)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($invper-$period2)*12))-1)/$monthlyReturn) * pow((1+$total1/100),($period3-$invper));
                            
                                $value3 = (1+$monthlyReturn)*$initial*(((pow((1+$monthlyReturn),(($invper-$period2)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period3-$invper) * 12))) + $balance2 * pow((1+$monthlyReturn),(($period3-$period2)*12));
                            }
                            else if($invper == $period2)
                            {
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2));
                            }
                            else if($invper == $period3)
                            {
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period3-$period2)*12))-1)/$monthlyReturn);
                            }
                            else
                                $value3 = $balance2;
                                $sendable .= ",".$value3;
                                $balance3 = $value3 - $amount3;
                                $balance5 = $balance3;
                            }
                            
                            if(isset($period4))
                            {
                            $lastWithdrawPeriod = $period4;
                            if($period4 > $invper && $period3 > $invper){
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3));
                               // $lastWithdrawPeriod = $period3;
                                }
                                else if($period4 <= $invper)
                                {
                                    $value4 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period4-$period3)*12))-1)/$monthlyReturn) + $balance3 * pow((1+$monthlyReturn),(($period4-$period3)*12));
                                }
                            else if($period4 < $invper && $period3 < $invper)
                            {
                                $value4 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period4-$period3)*12))-1)/$monthlyReturn);
                            }
                            else if($period4 > $invper && $period3 <= $invper)
                            {
                                
                               $value4 = (1+$monthlyReturn)*$initial*(((pow((1+$monthlyReturn),(($invper-$period3)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period4-$invper) * 12))) + $balance3 * pow((1+$monthlyReturn),(($period4-$period3)*12));
                            }
                            else if($invper == $period3)
                            {
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3));
                            }
                            else if($invper == $period4)
                            {
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period4-$period3)*12))-1)/$monthlyReturn);
                            }
                            else
                                $value4 = $balance3;
                                $sendable .= ",".$value4;
                                $balance4 = $value4 - $amount4;
                                $balance5 = $balance4;
                            }
                            
                            if(isset($period5))
                            {
                            $lastWithdrawPeriod = $period5;
                            if($period5 > $invper && $period4 > $invper){
                                
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4));
                                //$lastWithdrawPeriod = $period3;
                                }
                                else if($period5 <= $invper)
                                {
                                    
                                    $value5 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period5-$period4)*12))-1)/$monthlyReturn) + $balance4 * pow((1+$monthlyReturn),(($period5-$period4)*12));
                                }
                            else if($period5 < $invper && $period4 < $invper)
                            {
                               
                                $value5 = (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period5-$period4)*12))-1)/$monthlyReturn);
                            }
                            else if($period5 > $invper && $period4 <= $invper)
                            {
                                // echo("400");
                                // exit(0);
                               //$value5 = $balance4 * pow((1+$total1/100),($period5-$period4)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($invper-$period4)*12))-1)/$monthlyReturn) * pow((1+$total1/100),($period5-$invper));
                            
                                $value5 = (1+$monthlyReturn)*$initial*(((pow((1+$monthlyReturn),(($invper-$period4)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period5-$invper) * 12))) + ($balance4 * pow((1+$monthlyReturn),(($period5-$period4)*12)));
                            }
                            else if($invper == $period4)
                            {
                                
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4));
                            }
                            else if($invper == $period5)
                            {
                                
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4)) + (1+$monthlyReturn)*$initial*((pow((1+$monthlyReturn),(($period5-$period4)*12))-1)/$monthlyReturn);
                            }
                            else{
                                
                                $value5 = $balance4;
                            }
                                $sendable .= ",".$value5;
                                $balance5 = $value5 - $amount5;
                                $balance5 = $balance5;
                            }
                            
                            //dd($lastWithdrawPeriod);
                            $endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
                        } else if($investmentmode == 3 && $def==2) {
                            $calcType = 9;
                            $sipMonths = $invper * 12;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            
                            //$futureValue = $initial * pow((1+$total1/100),$deferment);
                            
                            $swpperiod = $installments;
                            $return1 = pow((1+$distribution/100),$moneyback) - 1;
                            
                            $valueAtEndOfInvPer = (1+$monthlyReturn) * $initial * (pow((1+$monthlyReturn),$sipMonths)-1)/($monthlyReturn);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$monthlyReturn),($deferment*12));
                            $maxMoneyBack = round($futureValue/(1+(1/$return1) * (1-pow((1+$return1),(-$swpperiod+1)))));
                            
                            $lumpsumswp = ($eachwithdraw+($eachwithdraw/$return1)*(1-pow((1+$return1),(-$swpperiod+1))));
                            if(($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback)) < 100)
                                $terminalBalance = 0;
                            else
                                $terminalBalance = ($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback));
                                
                                //dd($initial);
                                //dd($maxMoneyBack);
                        } else if($investmentmode == 4 && $def == 1 && $swpmode == 1) {
                            $calcType = 10;
                            $sipMonths = $invper * 12;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            $swpPeriod = $withdrawal;
                            $return1 = pow((1+$total1/100),$interval)-1;
                            $valueAtEndOfInvPer = (1+$monthlyReturn) * $monthlysipamount * (pow((1+$monthlyReturn),$sipMonths)-1)/($monthlyReturn);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$total1/100),($withdrawal * $interval - $invper));
                            $presentValue = $futureValue / (pow((1+$total1/100),($withdrawal * $interval))) ;
                            $totalPresentValue = $initial + $presentValue;
                            $maxMoneyBack = round(($totalPresentValue * $return1)/(1-pow((1+$return1),-$swpPeriod)));
                            
                            $lumpsumSwp = ($withdrawamount * (1-pow((1+$return1),-$swpPeriod)))/$return1;
                            $terminalBalance = ($totalPresentValue - $lumpsumSwp) * pow((1+$total1/100),($interval * $withdrawal));
                            
                        } else if($investmentmode == 4 && $def == 1 && $swpmode == 2) {
                           $calcType = 11; 
                           $sendable = "tst";
                           $Period = $invper * 12;
                           $sipamount = $monthlysipamount;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            $presentValue = $sipamount + $sipamount/$monthlyReturn * (1-pow((1+$monthlyReturn),(-$Period+1)))+$initial;
                            if(isset($period1))
                            {
                             $lastWithdrawPeriod = $period1;
                                if($period1 <= $invper)
                                {
                                   $value1 =  (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),($period1*12))-1)/$monthlyReturn) + ($initial * pow((1+$total1/100),$period1));
                                }
                            else
                                $value1 = (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),($invper*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period1-$invper)*12)) + ($initial * pow((1+$total1/100),$period1));
                                
                                $balance1 = $value1 - $amount1;
                                $sendable .= ",".$value1;
                                $balance5 = $balance1;
                                
                                //return $value1;
                            }
                            
                            if(isset($period2))
                            {
                             $lastWithdrawPeriod = $period2;
                            if($period2 > $invper && $period1 > $invper){
                                $value2 = $balance1 * pow((1+$monthlyReturn),($period2-$period1));
                                $lastWithdrawPeriod = $period2;
                                }
                                else if($period2 <= $invper)
                                {
                                    $value2 = (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),(($period2-$period1)*12))-1)/$monthlyReturn) + $balance1 * pow((1+$monthlyReturn),(($period2-$period1)*12));
                                }
                                else if($period2 > $invper && $period1 <= $invper)
                                {
                                   $value2 = (1+$monthlyReturn)*$monthlysipamount*(((pow((1+$monthlyReturn),(($invper-$period1)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period2-$invper) * 12))) + $balance1 * pow((1+$monthlyReturn),(($period2-$period1)*12));
                                }
                            else
                                $value2 = $balance1;
                                
                                $balance2 = $value2 - $amount2;
                                $sendable .= ",".$value2;
                                $balance5 = $balance2;
                            }
                            
                            
                            if(isset($period3))
                            {
                             $lastWithdrawPeriod = $period3;
                            if($period3 > $invper && $period2 > $invper){
                                $value3 = $balance2 * pow((1+$total1/100),($period3-$period2));
                                $lastWithdrawPeriod = $period3;
                                }
                            else if($period3 <= $invper)
                                {
                                    $value3 = (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),(($period3-$period2)*12))-1)/$monthlyReturn) + $balance2 * pow((1+$monthlyReturn),(($period3-$period2)*12));
                                }
                                else if($period3 > $invper && $period2 <= $invper)
                                {
                                    $value3 = (1+$monthlyReturn)*$monthlysipamount*(((pow((1+$monthlyReturn),(($invper-$period2)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period3-$invper) * 12))) + $balance2 * pow((1+$monthlyReturn),(($period3-$period2)*12));
                                }
                            else
                                $value3 = $balance2;
                                
                                $balance3 = $value3 - $amount3;
                                $sendable .= ",".$value3;
                                $balance5 = $balance3;
                            }
                            
                            if(isset($period4))
                            {
                             $lastWithdrawPeriod = $period4;
                            if($period4 > $invper && $period3 > $invper){
                                $value4 = $balance3 * pow((1+$total1/100),($period4-$period3));
                                $lastWithdrawPeriod = $period4;
                                }
                            else if($period4 <= $invper)
                                {
                                    $value4 = (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),(($period4-$period3)*12))-1)/$monthlyReturn) + $balance3 * pow((1+$monthlyReturn),(($period4-$period3)*12));
                                }
                                else if($period4 > $invper && $period3 <= $invper)
                                {
                                    $value4 = (1+$monthlyReturn)*$monthlysipamount*(((pow((1+$monthlyReturn),(($invper-$period3)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period4-$invper) * 12))) + $balance3 * pow((1+$monthlyReturn),(($period4-$period3)*12));
                                }
                            else
                                $value4 = $balance3;
                                
                                $balance4 = $value4 - $amount4;
                                $sendable .= ",".$value4;
                                $balance5 = $balance4;
                            }
                            
                            if(isset($period5))
                            {
                             $lastWithdrawPeriod = $period5;
                            if($period5 > $invper && $period4 > $invper){
                                $value5 = $balance4 * pow((1+$total1/100),($period5-$period4));
                                $lastWithdrawPeriod = $period5;
                                }
                            else if($period5 <= $invper)
                                {
                                    $value5 = (1+$monthlyReturn)*$monthlysipamount*((pow((1+$monthlyReturn),(($period5-$period4)*12))-1)/$monthlyReturn) + $balance4 * pow((1+$monthlyReturn),(($period5-$period4)*12));
                                }
                                else if($period5 > $invper && $period4 <= $invper)
                                {
                                    $value5 = (1+$monthlyReturn)*$monthlysipamount*(((pow((1+$monthlyReturn),(($invper-$period4)*12))-1)/$monthlyReturn) * pow((1+$monthlyReturn),(($period5-$invper) * 12))) + $balance4 * pow((1+$monthlyReturn),(($period5-$period4)*12));
                                }
                            else
                                $value5 = $balance4;
                                
                                $balance5 = $value5 - $amount5;
                                $sendable .= ",".$value5;
                                $balance5 = $balance5;
                            }
                            
                            
                            $endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
                           //dd($lastWithdrawPeriod);
                           
                           //$total1 = round($total1);
                        } else if($investmentmode == 4 && $def==2) {
                           $calcType = 12;
                            $sipMonths = $invper * 12;
                            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
                            
                            $futureValue = $initial * pow((1+$total1/100),$deferment);
                            
                            $swpperiod = $installments;
                            $return1 = pow((1+$distribution/100),$moneyback) - 1;
                            
                            $valueAtEndOfInvPer = ((1+$monthlyReturn) * $monthlysipamount * (pow((1+$monthlyReturn),$sipMonths)-1)/($monthlyReturn))+$initial*pow((1+$monthlyReturn),$sipMonths);
                            $futureValue = $valueAtEndOfInvPer * pow((1+$monthlyReturn),($deferment*12));
                            $maxMoneyBack = round($futureValue/(1+(1/$return1) * (1-pow((1+$return1),(-$swpperiod+1)))));
                            
                            $lumpsumswp = ($eachwithdraw+($eachwithdraw/$return1)*(1-pow((1+$return1),(-$swpperiod+1))));
                            if(($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback)) < 100)
                                $terminalBalance = 0;
                            else
                                $terminalBalance = ($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback));
                                
                                $sendable = "est,".$maxMoneyBack;
                        }
                       
                        if($endValue < 100)
                            $endValue = 0;
                        if($terminalBalance < 100)
                            $terminalBalance = 0;
                       
                    @endphp

                    @php $tableHtml = ''; @endphp

                    @if($report == "detailed")

                        @php 
                            $age_cal = "";
                            if(isset($currentage)) {
                                $age_cal = "Age";
                            } else {
                                $age_cal = "Year";
                            }

                            if($calcType==1){

                                    $year = 1;
                                    $maxMoneyBackPeriod = $interval;
                                    $openingBalance = $initial;
                                    $rateOfReturn = $total1/100;
                                    //dd($rateOfReturn . " ". $openingBalance." ".$maxMoneyBackPeriod);
                                    while($year <= $withdrawal * $interval)
                                    {
                                        $age = $currentage + $year;
                                        
                                        if($year % $maxMoneyBackPeriod == 0)
                                            $maxMoneyBackPeriodWorking = true;
                                        else
                                            $maxMoneyBackPeriodWorking = false;
                                            
                                            
                                            //
                                        $eoyVal = $openingBalance + $openingBalance * $rateOfReturn;
                                        
                                        if($maxMoneyBackPeriodWorking == true)
                                            $withDrawal = $withdrawamount;
                                        else
                                            $withDrawal = 0;
                                            
                                        $yearEndBal = $eoyVal - $withDrawal;
                                        
                                        if($year == $swpperiod)
                                            $maturityAmount = $yearEndBal;
                                        else
                                            $maturityAmount = 0;
                                        
                                       
                                        $openingBalance = $yearEndBal;
                                        $year++;
                                    }
                            }

                            if($calcType == 2){

                                $year = 1;
                                $swpPeriod = $lastwithdraw;
                                $rateOfReturn = $total1/100;
                                $openingBal = $initial;
                                $age = $currentage;
                                while($year <= $swpPeriod)
                                {
                                    $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                    $withdrawalAmountMain = 0;
                                    $age ++;
                                    if(isset($period1))
                                    {
                                        if($period1 == $year)
                                        $maxMoneyBackPeriod1 = true;
                                        else
                                        $maxMoneyBackPeriod1 = false;
                                        
                                        $withdrawalAmount1 = $amount1;
                                        
                                        if($maxMoneyBackPeriod1 == true)
                                        $withdrawalAmountMain += $amount1;
                                    }
                                    if(isset($period2))
                                    {
                                        if($period2 == $year)
                                        $maxMoneyBackPeriod2 = true;
                                        else
                                        $maxMoneyBackPeriod2 = false;
                                        
                                        $withdrawalAmount2 = $amount2;
                                        
                                        if($maxMoneyBackPeriod2 == true)
                                        $withdrawalAmountMain += $amount2;
                                    }
                                    if(isset($period3))
                                    {
                                        if($period3 == $year)
                                        $maxMoneyBackPeriod3 = true;
                                        else
                                        $maxMoneyBackPeriod3 = false;
                                        
                                        $withdrawalAmount3 = $amount3;
                                        
                                        if($maxMoneyBackPeriod3 == true)
                                        $withdrawalAmountMain += $amount3;
                                    }
                                    if(isset($period4))
                                    {
                                        if($period4 == $year)
                                        $maxMoneyBackPeriod4 = true;
                                        else
                                        $maxMoneyBackPeriod4 = false;
                                        
                                        $withdrawalAmount4 = $amount4;
                                        
                                        if($maxMoneyBackPeriod4 == true)
                                        $withdrawalAmountMain += $amount4;
                                    }
                                    
                                    if(isset($period5))
                                    {
                                        if($period5 == $year)
                                        $maxMoneyBackPeriod5 = true;
                                        else
                                        $maxMoneyBackPeriod5 = false;
                                        
                                        $withdrawalAmount5 = $amount5;
                                        
                                        if($maxMoneyBackPeriod5 == true)
                                        $withdrawalAmountMain += $amount5;
                                    }
                                    
                                    $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                    
                                    $year++;
                                    
                                    $openingBal = $yearEndValue;
                                    $endValue = $yearEndValue;
                                }
                            }

                            if($calcType == 3){

                                $year = 1;
                                $accumulationPeriod = $deferment;
                                $swpPeriod = $moneyback * ($installments - 1) + $deferment;
                                $age = $currentage+1;
                                $fstpayemntAfter = $deferment;
                                $interval = $moneyback;
                                $fgap = $deferment;
                                $openingBal = $initial;
                                $nowAllow = false;
                                $tlec = 0;
                                while($year <= $swpPeriod){
                                    $moneybackPeriodWorking = false;
                                    if($year == $fgap)
                                    {
                                        $moneybackPeriodWorking = true;
                                        $fgap = $interval;
                                        $nowAllow = true;
                                    }
                                    
                                    if(($tlec % $fgap == 0) && $nowAllow){
                                    $moneybackPeriodWorking = true;
                                    }
                                    
                                    $rateOfReturnAcc = $total1/100;
                                    $rateOfReturnDist = $distribution/100;
                                    if($year <= $accumulationPeriod)
                                        $applicableRateOfreturn = $rateOfReturnAcc;
                                    else
                                        $applicableRateOfreturn = $rateOfReturnDist;
                                        
                                        $eoyVal = $openingBal + $openingBal * $applicableRateOfreturn;
                                        
                                        $withdrawalAmount = $eachwithdraw;
                                        
                                        if($moneybackPeriodWorking == false)
                                        $withdrawalAmount = 0;
                                        
                                        $yearEndVal = $eoyVal - $withdrawalAmount;

                                        $year++;
                                        $openingBal = $yearEndVal;
                                        $age++;
                                        if($nowAllow)
                                        $tlec++;
                                    
                                }
                            }

                            if($calcType == 4){

                                $year = 1;
                                $swpPeriod = $withdrawal * $interval;
                                $age = $currentage + 1;
                                $maxMoneyBackPeriod = $interval;
                                $investmentPeriod = $invper;
                                $annualInvestment = $initial;
                                $openingBal = $initial;
                                $rateOfReturn = $total1/100;
                                while($year <= $swpPeriod){
                                    if($year <= $investmentPeriod){
                                        $investmentMade = $annualInvestment;
                                    }else{
                                        $investmentMade = 0;
                                    }
                                        
                                    $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                    
                                    if($year % $interval == 0){
                                        $maxMoneyBackPeriodWorking = true;
                                    }
                                    else{
                                        $maxMoneyBackPeriodWorking = false;
                                    }
                                        
                                    if($maxMoneyBackPeriodWorking == true)
                                        $withdrawMade = $withdrawamount;
                                    else
                                        $withdrawMade = 0;
                                    
                                    $yearEndVal = round($eoyVal - $withdrawMade);
                                    
                                    if($year <= $invper){
                                        $annualLump = $initial;
                                    }else{
                                        $annualLump = 0;
                                    }

                                    $age++;
                                    $year++;
                                    $openingBal = $yearEndVal;
                                    
                                    if($year <= $investmentPeriod)
                                    $openingBal += $investmentMade;
                                        
                                }
                            }

                            if($calcType == 5){

                                $year = 1;
                                $swpPeriod = $lastwithdraw;
                                $rateOfReturn = $total1/100;
                                $openingBal = $initial;
                                $age = $currentage;
                                while($year <= $swpPeriod){
                                    if($year <= $invper)
                                    $annualInv = $initial;
                                    else
                                    $annualInv = 0;
                                    $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                    $withdrawalAmountMain = 0;
                                    $age ++;
                                    if(isset($period1)){
                                        if($period1 == $year)
                                            $maxMoneyBackPeriod1 = true;
                                        else
                                            $maxMoneyBackPeriod1 = false;
                                        
                                        $withdrawalAmount1 = $amount1;
                                        
                                        if($maxMoneyBackPeriod1 == true)
                                        $withdrawalAmountMain += $amount1;
                                    }
                                    if(isset($period2)){
                                        if($period2 == $year)
                                            $maxMoneyBackPeriod2 = true;
                                        else
                                            $maxMoneyBackPeriod2 = false;
                                        
                                        $withdrawalAmount2 = $amount2;
                                        
                                        if($maxMoneyBackPeriod2 == true)
                                        $withdrawalAmountMain += $amount2;
                                    }
                                    if(isset($period3)){
                                        if($period3 == $year)
                                            $maxMoneyBackPeriod3 = true;
                                        else
                                            $maxMoneyBackPeriod3 = false;
                                        
                                        $withdrawalAmount3 = $amount3;
                                        
                                        if($maxMoneyBackPeriod3 == true)
                                        $withdrawalAmountMain += $amount3;
                                    }
                                    if(isset($period4)){
                                        if($period4 == $year)
                                            $maxMoneyBackPeriod4 = true;
                                        else
                                            $maxMoneyBackPeriod4 = false;
                                        
                                        $withdrawalAmount4 = $amount4;
                                        
                                        if($maxMoneyBackPeriod4 == true)
                                        $withdrawalAmountMain += $amount4;
                                    }
                                    
                                    if(isset($period5)){
                                        if($period5 == $year)
                                            $maxMoneyBackPeriod5 = true;
                                        else
                                            $maxMoneyBackPeriod5 = false;
                                        
                                        $withdrawalAmount5 = $amount5;
                                        
                                        if($maxMoneyBackPeriod5 == true)
                                        $withdrawalAmountMain += $amount5;
                                    }
                                    
                                    $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                    
                                    $year++;
                                    $openingBal = $yearEndValue;
                                    $endValue = $yearEndValue;
                                    
                                    if($year <= $invper)
                                    $openingBal += $initial;
                                }
                            }

                            if($calcType == 6){

                                $year = 1;
                                $accumulationPeriod = $deferment;
                                $swpPeriod = $moneyback * ($installments - 1) + $deferment;
                                $age =  1;
                                $fstpayemntAfter = $deferment;
                                $interval = $moneyback;
                                $fgap = $deferment;
                                $openingBal = $initial;
                                $investmentPeriod = $invper;
                                $rateOfReturn = $total1/100;
                                $nowAllow = false;
                                $tlec = 0;
                                while($year <= $invper){
                                    $withdrawMade = 0;
                                    if($year < $accumulationPeriod)
                                        $moneyBackPeriodWorking = false;
                                    else if($year == $accumulationPeriod)
                                        $moneyBackPeriodWorking = true;
                                    else{
                                        if($tlec % $interval == 0)
                                            $moneyBackPeriodWorking = true;
                                        else
                                            $moneyBackPeriodWorking = false;
                                        
                                        $tlec++;
                                    }
                                     
                                    if($year <= $investmentPeriod)
                                        $investmentMade = $initial;
                                    else{
                                        $investmentMade = 0;
                                    }

                                    $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                    //dd($eoyVal);
                                    $withdrawalAmt = $eachwithdraw;
                                    
                                    if($year == $deferment){
                                        $withdrawMade = $withdrawalAmt;
                                        $nowAllow = true;
                                    }
                                   
                                    if($moneyBackPeriodWorking){
                                        $withdrawMade = $withdrawalAmt;
                                    }
                                        
                                    $yearEndBal = $eoyVal;
                                    
                                    if(isset($currentage)){
                                        $agecount = $age + $currentage;
                                    }else{
                                       $agecount = $age;
                                    }
                                    $age++;
                                    $openingBal = $yearEndBal;
                                    $year++;

                                    if($year <= $investmentPeriod)
                                    $openingBal += $investmentMade;
                                }

                                $year = 1;
                                $tlec = 1;
                                $accumulationPeriod = $deferment;
                                while($year <= $swpPeriod){
                                    $withdrawMade = 0;
                                    if($year < $accumulationPeriod)
                                        $moneyBackPeriodWorking = false;
                                    else if($year == $accumulationPeriod)
                                        $moneyBackPeriodWorking = true;
                                    else if($year > $accumulationPeriod){
                                        if($tlec % $moneyback == 0)
                                            $moneyBackPeriodWorking = true;
                                        else
                                            $moneyBackPeriodWorking = false;
                                        
                                        $tlec++;
                                     }
                             
                                    if($year <= $investmentPeriod)
                                        $investmentMade = $initial;
                                    else
                                        $investmentMade = 0;
                                
                                    if($year > $accumulationPeriod){
                                        $rateOfReturn = $distribution/100;
                                        //dd($rateOfReturn);
                                    }else{
                                        $rateOfReturn = $total1/100;
                                        //dd($rateOfReturn);
                                    }
                                    $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                    //dd($eoyVal);
                                    $withdrawalAmt = $eachwithdraw;
                                
                                    if($year == $deferment){
                                        $withdrawMade = $withdrawalAmt;
                                        $nowAllow = true;
                                    }
                                   
                                    if($moneyBackPeriodWorking){
                                        $withdrawMade = $withdrawalAmt;
                                    }
                                    
                                    $yearEndBal = $eoyVal - $withdrawMade;
                                    
                                    if(isset($currentage)){
                                        $agecount = $age + $currentage;
                                    }else{
                                       $agecount = $age;
                                    }
                                    $age++;
                                    $openingBal = $yearEndBal;
                                    $year++;
                                }
                            }

                            if($calcType == 7){

                                $year = 1;
                                $swpPeriod = $withdrawal * $interval;
                                $age = $currentage + 1;
                                $maxMoneyBackPeriod = $interval;
                                $investmentPeriod = $invper;
                                $sipAmount = $initial;
                                $openingBal = 0;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                
                                while($year <= $swpPeriod)
                                {
                                    if($year <= $investmentPeriod)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                    
                                    if($year % $interval == 0)
                                        $maxMoneyBackPeriodWorking = true;
                                    else
                                        $maxMoneyBackPeriodWorking = false;
                                        
                                    if($maxMoneyBackPeriodWorking == true)
                                        $withdrawMade = $withdrawamount;
                                    else
                                        $withdrawMade = 0;
                                    
                                    $yearEndVal = $eoyVal - $withdrawMade;

                                    $age++;
                                    $year++;
                                    $openingBal = $yearEndVal;
                                        
                                }
                            }

                            if($calcType == 8){

                                $year = 1;
                                $swpPeriod = $lastwithdraw;
                                $age = $currentage + 1;
                                $maxMoneyBackPeriod = $interval;
                                $investmentPeriod = $invper;
                                $sipAmount = $initial;
                                $openingBal = 0;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                while($year <= $swpPeriod)
                                {
                                    if($year <= $investmentPeriod)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                    $withdrawalAmountMain = 0;
                                    $age ++;
                                    if(isset($period1))
                                    {
                                        if($period1 == $year)
                                        $maxMoneyBackPeriod1 = true;
                                        else
                                        $maxMoneyBackPeriod1 = false;
                                        
                                        $withdrawalAmount1 = $amount1;
                                        
                                        if($maxMoneyBackPeriod1 == true)
                                        $withdrawalAmountMain += $amount1;
                                    }
                                    if(isset($period2))
                                    {
                                        if($period2 == $year)
                                        $maxMoneyBackPeriod2 = true;
                                        else
                                        $maxMoneyBackPeriod2 = false;
                                        
                                        $withdrawalAmount2 = $amount2;
                                        
                                        if($maxMoneyBackPeriod2 == true)
                                        $withdrawalAmountMain += $amount2;
                                    }
                                    if(isset($period3))
                                    {
                                        if($period3 == $year)
                                        $maxMoneyBackPeriod3 = true;
                                        else
                                        $maxMoneyBackPeriod3 = false;
                                        
                                        $withdrawalAmount3 = $amount3;
                                        
                                        if($maxMoneyBackPeriod3 == true)
                                        $withdrawalAmountMain += $amount3;
                                    }
                                    if(isset($period4))
                                    {
                                        if($period4 == $year)
                                        $maxMoneyBackPeriod4 = true;
                                        else
                                        $maxMoneyBackPeriod4 = false;
                                        
                                        $withdrawalAmount4 = $amount4;
                                        
                                        if($maxMoneyBackPeriod4 == true)
                                        $withdrawalAmountMain += $amount4;
                                    }
                                    
                                    if(isset($period5))
                                    {
                                        if($period5 == $year)
                                        $maxMoneyBackPeriod5 = true;
                                        else
                                        $maxMoneyBackPeriod5 = false;
                                        
                                        $withdrawalAmount5 = $amount5;
                                        
                                        if($maxMoneyBackPeriod5 == true)
                                        $withdrawalAmountMain += $amount5;
                                    }
                                    
                                    $yearEndValue = round($eoyVal - $withdrawalAmountMain);

                                    $year++;
                                    $openingBal = $yearEndValue;
                                    $endValue = $yearEndValue;
                                }
                            }

                            if($calcType == 9){

                                $year = 1;
                                $accumulationPeriod = $deferment;
                                $swpPeriod = $moneyback * ($installments -1)+$deferment;
                                $age = $currentage+1;
                                $fpaymentAfter = $accumulationPeriod;
                                $sipAmount = $initial;
                                $openingBal = 0;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$total1/100),(1/12))-1;
                                $incrMod = 0;
                                while($year <= $invper){
                                    $moneybackPeriodWorking = false;
                                    if($year < $accumulationPeriod)
                                     $moneybackPeriodWorking = false;
                                    if($year == $accumulationPeriod)
                                     $moneybackPeriodWorking = true;
                                    if($year > $accumulationPeriod)
                                    {
                                    $incrMod++;
                                        if($incrMod == $moneyback-1){
                                            $moneybackPeriodWorking = true;
                                            $incrMod = 0;
                                            }
                                        else
                                            $moneybackPeriodWorking = false;
                                    }
                                    
                                    if($year <= $invper)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                    if($moneybackPeriodWorking == true)
                                        $withDraw = $eachwithdraw;
                                    else
                                        $withDraw = 0;
                                        
                                    $yearEndValue = $eoyVal;

                                    $year++;
                                    
                                    $openingBal = $yearEndValue;
                                    $age++;
                                        
                                }

                                $year = 1;
                                $tlec = 1;
                                $accumulationPeriod = $deferment;
                                $year = 1;
                                $tlec = 0;
                                $incrMod = 0;
                                while($year <= $swpPeriod){
                                    $moneybackPeriodWorking = false;
                                    if($year < $accumulationPeriod)
                                        $moneybackPeriodWorking = false;

                                    if($year == $accumulationPeriod)
                                        $moneybackPeriodWorking = true;

                                    if($year > $accumulationPeriod){
                                        $incrMod++;
                                        if($incrMod == $moneyback){
                                            $moneybackPeriodWorking = true;
                                            $incrMod = 0;
                                        }else{
                                            $moneybackPeriodWorking = false;
                                        }
                                    }
                                    
                                    if($year <= $invper)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    if($year > $accumulationPeriod)
                                        $rateOfReturn  = $distribution/100;
                                        
                                    $eoyVal = ($openingBal+$openingBal*$rateOfReturn);
                                    if($moneybackPeriodWorking == true)
                                        $withDraw = $eachwithdraw;
                                    else
                                        $withDraw = 0;
                                        
                                    $yearEndValue = $eoyVal - $withDraw;

                                    $year++;
                                    $openingBal = $yearEndValue;
                                    $age++;
                                            
                                      
                                }
                            }

                            if($calcType == 10){

                                $year = 1;
                                $swpPeriod = $withdrawal * $interval;
                                $age = $currentage + 1;
                                $maxMoneyBackPeriod = $interval;
                                $investmentPeriod = $invper;
                                $sipAmount = $monthlysipamount;
                                $openingBal = $initial;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                
                                while($year <= $swpPeriod){
                                    if($year <= $investmentPeriod)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                    
                                    if($year % $interval == 0)
                                        $maxMoneyBackPeriodWorking = true;
                                    else
                                        $maxMoneyBackPeriodWorking = false;
                                        
                                    if($maxMoneyBackPeriodWorking == true)
                                        $withdrawMade = $withdrawamount;
                                    else
                                        $withdrawMade = 0;
                                    
                                    $yearEndVal = $eoyVal - $withdrawMade;
                                    
                                    $age++;
                                    $year++;
                                    $openingBal = $yearEndVal;
                                        
                                }
                            }

                            if($calcType == 11){

                                $year = 1;
                                $swpPeriod = $lastwithdraw;
                                $age = $currentage;
                                $maxMoneyBackPeriod = $interval;
                                $investmentPeriod = $invper;
                                $sipAmount = $sipamount;
                                $openingBal = $initial;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                while($year <= $swpPeriod){
                                    if($year <= $investmentPeriod)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                    $withdrawalAmountMain = 0;
                                    $age ++;
                                    if(isset($period1)){
                                        if($period1 == $year)
                                        $maxMoneyBackPeriod1 = true;
                                        else
                                        $maxMoneyBackPeriod1 = false;
                                        
                                        $withdrawalAmount1 = $amount1;
                                        
                                        if($maxMoneyBackPeriod1 == true)
                                        $withdrawalAmountMain += $amount1;
                                    }
                                    if(isset($period2)){
                                        if($period2 == $year)
                                        $maxMoneyBackPeriod2 = true;
                                        else
                                        $maxMoneyBackPeriod2 = false;
                                        
                                        $withdrawalAmount2 = $amount2;
                                        
                                        if($maxMoneyBackPeriod2 == true)
                                        $withdrawalAmountMain += $amount2;
                                    }
                                    if(isset($period3)){
                                        if($period3 == $year)
                                        $maxMoneyBackPeriod3 = true;
                                        else
                                        $maxMoneyBackPeriod3 = false;
                                        
                                        $withdrawalAmount3 = $amount3;
                                        
                                        if($maxMoneyBackPeriod3 == true)
                                        $withdrawalAmountMain += $amount3;
                                    }
                                    if(isset($period4)){
                                        if($period4 == $year)
                                        $maxMoneyBackPeriod4 = true;
                                        else
                                        $maxMoneyBackPeriod4 = false;
                                        
                                        $withdrawalAmount4 = $amount4;
                                        
                                        if($maxMoneyBackPeriod4 == true)
                                        $withdrawalAmountMain += $amount4;
                                    }
                                    
                                    if(isset($period5)){
                                        if($period5 == $year)
                                        $maxMoneyBackPeriod5 = true;
                                        else
                                        $maxMoneyBackPeriod5 = false;
                                        
                                        $withdrawalAmount5 = $amount5;
                                        
                                        if($maxMoneyBackPeriod5 == true)
                                        $withdrawalAmountMain += $amount5;
                                    }
                                    
                                    $yearEndValue = round($eoyVal - $withdrawalAmountMain);

                                    $year++;
                                    $openingBal = $yearEndValue;
                                    $endValue = $yearEndValue;
                                }
                            }

                            if($calcType == 12){

                                $year = 1;
                                $accumulationPeriod = $deferment;
                                $swpPeriod = $moneyback * ($installments -1)+$deferment;
                                $age = $currentage+1;
                                $fpaymentAfter = $accumulationPeriod;
                                $sipAmount = $initial;
                                $openingBal = 0;
                                $rateOfReturn = $total1/100;
                                $rateOfReturnMonthly = pow((1+$total1/100),(1/12))-1;
                                $incrMod = 0;
                                while($year <= $invper){
                                    $moneybackPeriodWorking = false;
                                    if($year < $accumulationPeriod)
                                        $moneybackPeriodWorking = false;
                                    if($year == $accumulationPeriod)
                                        $moneybackPeriodWorking = true;
                                    if($year > $accumulationPeriod){
                                        $incrMod++;
                                        if($incrMod == 3){
                                            $moneybackPeriodWorking = true;
                                            $incrMod = 0;
                                        }else{
                                            $moneybackPeriodWorking = false;
                                        }
                                    }
                                        
                                    if($year <= $invper)
                                        $sipDone = $monthlysipamount;
                                    else
                                        $sipDone = 0;
                                            
                                    $fval1 = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),(12 * $year))-1)/$rateOfReturnMonthly);
                                    
                                    $fval2 = $initial * pow((1+$rateOfReturnMonthly),(12 * $year));
                                    $eoyVal = $fval1 + $fval2;
                                    
                                    if($moneybackPeriodWorking == true)
                                        $withDraw = $eachwithdraw;
                                    else
                                        $withDraw = 0;
                                        
                                    $yearEndValue = $eoyVal;
                                        
                                    if($year == 1)
                                        $sipDone = ($initial + $monthlysipamount * 12) / 12;

                                    $year++;
                                    
                                    $openingBal = $yearEndValue;
                                    $age++;
                                        
                                }

                                $year = 1;
                                $tlec = 0;
                                $incrMod = 0;
                                while($year <= $swpPeriod){
                                    $moneybackPeriodWorking = false;
                                    if($year < $accumulationPeriod)
                                        $moneybackPeriodWorking = false;
                                    if($year == $accumulationPeriod)
                                        $moneybackPeriodWorking = true;
                                    if($year > $accumulationPeriod){
                                        $incrMod++;
                                        if($incrMod == $moneyback){
                                            $moneybackPeriodWorking = true;
                                            $incrMod = 0;
                                            }
                                        else
                                            $moneybackPeriodWorking = false;
                                    }
                                        
                                    if($year <= $invper)
                                        $sipDone = $sipAmount;
                                    else
                                        $sipDone = 0;
                                        
                                    if($year > $accumulationPeriod)
                                        $rateOfReturn  = $distribution/100;
                                    else
                                        $rateOfReturn = $total1/100;
                                            
                                    $eoyVal = ($openingBal+$openingBal*$rateOfReturn);
                                    if($moneybackPeriodWorking == true)
                                        $withDraw = $eachwithdraw;
                                    else
                                        $withDraw = 0;
                                            
                                    $yearEndValue = $eoyVal - $withDraw;
                                    
                                    $year++;
                                    $openingBal = $yearEndValue;
                                    $age++;
                                }
                            }
                        @endphp
                    @endif
                                
                    <table class="table table-bordered leftright" style="margin: 0 auto; width:70%">
                        <tbody>
                            @if($currentage > 0)
                                 <tr>
                                    <td>
                                        <strong>Current Age</strong>
                                    </td>
                                    <td>
                                          {{$currentage}} Years
                                    </td>
                                </tr>
                            @endif
                                
                                <tr>
                                    <td>
                                        @if($investmentmode==2)
                                        <strong>Annual Investment</strong>
                                        @elseif($investmentmode==3)
                                        <strong>Monthly SIP Amount</strong>
                                        @elseif($investmentmode==4)
                                        <strong>Lumpsum Investment</strong>
                                        @else
                                        <strong>Initial Investment</strong>
                                        @endif
                                    </td>
                                    <td>
                                         <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($initial)}}
                                    </td>
                                </tr>
                                @if($investmentmode==4)
                                <tr>
                                    <td>
                                        <strong>Monthly SIP Amount</strong>
                                    </td>
                                    <td>
                                          <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($monthlysipamount)}}
                                    </td>
                                </tr>
                                @endif
                                @if($invper > 0)
                                <tr>
                                    <td>
                                        @if($investmentmode==3 || $investmentmode==4)
                                        <strong>SIP Period</strong>
                                        @else
                                        <strong>Payment Period</strong>
                                        @endif
                                    </td>
                                    <td>
                                          {{$invper}} Years
                                    </td>
                                </tr>
                                @endif
                                
                                @if($calcType == 6)
                                <tr>
                                    <td><strong>Deferment Period</strong></td>
                                    <td>{{$deferment}} Years</td>
                                </tr>
                                <tr>
                                    <td><strong>Periodic Withdrawal Interval</strong></td>
                                    <td>{{$moneyback}} Years</td>
                                </tr>
                                <tr>
                                    <td><strong>No of Installments</strong></td>
                                    <td>{{$installments}}</td>
                                </tr>
                                <tr>
                                    <td><strong>Assumed Return (Accumulation Period)</strong></td>
                                    <td>{{sprintf('%0.2f', round($total1,2))}} %</td>
                                </tr>
                                <tr>
                                    <td><strong>Assumed Return (Distribution Period)</strong></td>
                                    <td>{{sprintf('%0.2f', round($distribution,2))}} %</td>
                                </tr>
                                @endif
                                
                                
                                
                                @if($calcType == 9|| $calcType == 12)
                                <tr>
                                    <td>
                                        <strong>Deferment Period</strong>
                                    </td>
                                    <td>
                                          {{$deferment}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Periodic Withdrawal Interval</strong>
                                    </td>
                                    <td>
                                          {{$moneyback}} Years
                                    </td>
                                </tr>
                                @endif
                                
                                @if($calcType == 12)
                                <tr>
                                    <td>
                                        <strong>No. of Instalments</strong>
                                    </td>
                                    <td>
                                          {{$installments}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Return (Accumulation Period)</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f',$total1)}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Return (Distribution Period)</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f',$distribution)}} %
                                    </td>
                                </tr>
                                
                                @endif
                                
                                @if($calcType == 10)
                                <tr>
                                    <td>
                                        <strong>Periodic Withdrawal Interval</strong>
                                    </td>
                                    <td>
                                          {{$interval}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>No. of Instalments</strong>
                                    </td>
                                    <td>
                                          {{$withdrawal}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Rate Of Return</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f', round($total1,2))}} %
                                    </td>
                                </tr>
                                @endif
                                
                                @if($calcType == 1 || ($calcType >= 7 && $calcType<= 9) || $calcType == 11)
                                
                                @if($calcType != 9 && $calcType != 11 && $calcType != 8)
                                <tr>
                                    <td>
                                        @if($calcType >= 7 && $calcType != 9 && $calcType != 11 && $calcType != 8)
                                        <strong>Periodic Withdrawal Interval</strong>
                                        @elseif($calcType != 9 && $calcType < 7)
                                        <strong>Periodic Withdrawal Interval</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($calcType != 9 && $calcType != 11 && $calcType != 8)
                                          {{$interval}} Years
                                          @endif
                                    </td>
                                </tr>
                                @endif
                                @if($calcType != 8 && $calcType != 11)
                                <tr>
                                    <td>
                                        @if($calcType >= 7)
                                        <strong>No. of Installments</strong>
                                        @else
                                        <strong>No. of Withdrawals</strong>
                                        @endif
                                    </td>
                                    <td>
                                        @if($calcType != 11)
                                          {{$swpperiod}}
                                          @endif
                                    </td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <td>
                                        @if($calcType == 8 || $calcType == 11)
                                        <strong>Assumed Rate Of Return</strong>
                                         @elseif($calcType >= 7)
                                            <strong>Assumed Rate of Return</strong>
                                        @else
                                        <strong>Assumed Rate of Return</strong>
                                        @endif
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f', round($total1,2))}} %
                                    </td>
                                </tr>
                                @endif
                                @if($calcType == 2 || $calcType == 5)
                                <tr>
                                    <td>
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f', round($total1,2))}} %
                                    </td>
                                </tr>
                                @endif
                                @if($calcType == 3)
                                <tr>
                                    <td>
                                        <strong>Deferment Period</strong>
                                    </td>
                                    <td>
                                          {{$deferment}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Periodic Withdrawal Interval</strong>
                                    </td>
                                    <td>
                                          {{$moneyback}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>No. of Instalments</strong>
                                    </td>
                                    <td>
                                          {{$installments}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Return (Accumulation Period)</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f', round($total1,2))}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Return (Distribution Period)</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f',$distribution)}} %
                                    </td>
                                </tr>
                                @endif
                                @if($calcType == 4)
                                <tr>
                                    <td>
                                        <strong>Periodic Withdrawal Interval</strong>
                                    </td>
                                    <td>
                                          {{$interval}} Years
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>No. of Instalments</strong>
                                    </td>
                                    <td>
                                          {{$withdrawal}} 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Assumed Return</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f', round($total1,2))}} %
                                    </td>
                                </tr>
                                @endif
                        </tbody>
                    </table>
                    <br/>
                    @if($calcType == 2 || $calcType == 5 || $calcType == 8 || $calcType == 11)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;"> Periodic Withdrawal Details </h5>
                        <table class="table table-bordered">
                            <tbody>
                                <tr><td><strong>Year</strong></td><td><strong>Amount</strong></td></tr>
                                @if(isset($period1))
                                <tr><td>{{$period1}}</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($amount1)}}</td></tr>
                                @endif
                                 @if(isset($period2))
                                <tr><td>{{$period2}}</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($amount2)}}</td></tr>
                                @endif
                                 @if(isset($period3))
                                <tr><td>{{$period3}}</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($amount3)}}</td></tr>
                                @endif
                                 @if(isset($period4))
                                <tr><td>{{$period4}}</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($amount4)}}</td></tr>
                                @endif
                                 @if(isset($period5))
                                <tr><td>{{$period5}}</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($amount5)}}</td></tr>
                                @endif
                            </tbody>
                        </table>
                    @endif
                    <br/>
                    
                    @if($calcType == 1)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Withdrawal Amount Every {{$interval}} @if($interval > 1) Years @else Year @endif</h5>

                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawamount)}}
                        </h5>
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Total Withdrawal Amount</h5>

                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawamount * $swpperiod)}}
                        </h5>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End Value</h5>

                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($terminalBalance)}}
                        </h5>
                    @endif
                    @if($calcType == 2 || $calcType == 5)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End value after {{$lastwithdraw}} @if($lastwithdraw > 1) Years @else Year @endif</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($endValue)}}
                        </h5>
                    @endif
                    @if($calcType == 3 || $calcType == 6 || $calcType == 10 || $calcType == 7 || $calcType == 9)
                        @if($calcType != 7 && $calcType != 10)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Periodic Withdrawal Amount Every {{$moneyback}} @if($moneyback > 1) Years @else Year @endif</h5>
                        @else
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Periodic Withdrawal Amount Every {{$interval}} @if($interval > 1) Years @else Year @endif</h5>
                        @endif
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                @if($calcType == 10 || $calcType == 7)
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawamount)}}
                                @else
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($eachwithdraw)}}
                                @endif
                        </h5>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Total Periodic Withdrawal Amount</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                            @if($calcType == 10 || $calcType == 7)
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawamount * $withdrawal)}}
                                @else
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($eachwithdraw * $installments)}}
                                @endif
                                </h5>
                                
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End Value</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($terminalBalance)}}
                                </h5>
                    @endif
                    @if($calcType == 4)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Periodic Withdrawal Amount Every {{$interval}} @if($interval > 1) Years @else Year @endif</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawamount)}}
                        </h5>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Total Periodic Withdrawal Amount</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($withdrawal * $withdrawamount)}}
                        </h5>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End Value</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($terminalBal)}}
                        </h5>
                        
                    @endif
                    @if($calcType == 11 || $calcType == 8)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End Value After {{$lastwithdraw}} @if($lastwithdraw > 1) Years @else Year @endif</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                @if($endValue <=100)
                                Nil
                                @else
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($endValue)}}
                                @endif
                        </h5>
                    @endif
                        
                    @if($calcType == 12)
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Periodic Withdrawal Amount Every {{$moneyback}} @if($moneyback > 1) Years @else Year @endif</h5>
                         <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($eachwithdraw)}}
                                
                        </h5>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Total Periodic Withdrawal Amount</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                           
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($eachwithdraw * $installments)}}
                                
                                </h5>
                                
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">End Value</h5>
                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                                text-align:center;
                                font-size:25px;
                                font-weight: normal;
                            ">
                                <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> {{custome_money_format($terminalBalance)}}
                        </h5>
                    @endif
                                    
                    <br/>
                    @include('frontend.calculators.common.footer')
                                   
                    @if($report == "detailed")
                        </div>
                        <div class="page-break"></div>
                    
                        <header>       
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;">&nbsp;</td>
                                    <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        @include('frontend.calculators.common.footer') 

                         
                        @if($calcType==1)
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                     <tr class="headerbg">
                                        <td>@if(isset($currentage))Age @else Year @endif</td>
                                        <td>Withdrawal Amount</td>
                                        <td>Fund Value at End of Year</td>
                                    </tr>
                                @php
                                    $year = 1;
                                    $maxMoneyBackPeriod = $interval;
                                    $openingBalance = $initial;
                                    $rateOfReturn = $total1/100;
                                    $rowCount = 0;
                                    //dd($rateOfReturn . " ". $openingBalance." ".$maxMoneyBackPeriod);
                                    while($year <= $withdrawal * $interval)
                                    {
                                        $age = $currentage + $year;
                                        
                                        if($year % $maxMoneyBackPeriod == 0)
                                            $maxMoneyBackPeriodWorking = true;
                                        else
                                            $maxMoneyBackPeriodWorking = false;
                                            
                                            
                                            //
                                        $eoyVal = $openingBalance + $openingBalance * $rateOfReturn;
                                        
                                        if($maxMoneyBackPeriodWorking == true)
                                            $withDrawal = $withdrawamount;
                                        else
                                            $withDrawal = 0;
                                            
                                        $yearEndBal = $eoyVal - $withDrawal;
                                        
                                        if($year == $swpperiod)
                                            $maturityAmount = $yearEndBal;
                                        else
                                            $maturityAmount = 0;
                                                        
                                        $rowCount++;
                                        if($rowCount == 26)
                                        {
                                            @endphp
                                            </table>
                                            </div>
                                            <div class="page-break"></div>
                                                
                                            <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                <tbody>
                                                <tr>
                                                    <td style="text-align:left; border:0;">&nbsp;</td>
                                                    <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                </tr>
                                                </tbody>
                                                </table>
                                            </header>
                                            <div style="padding: 0 5%">
                                                  @include('frontend.calculators.common.footer')  
                                                  <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                     <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                            @php
                                            $rowCount = 0;
                                        }

                                        if($year == 1){
                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withDrawal)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($yearEndBal)."</td></tr>");
                                        }else{
                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withDrawal)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($yearEndBal)."</td></tr>");
                                        }
                                        
                                        $openingBalance = $yearEndBal;
                                        $year++;
                                                        
                                    }
                                @endphp
                                </tbody>
                                </table>
                        @endif
                        @if($calcType == 2)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                     <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                        $year = 1;
                                        $swpPeriod = $lastwithdraw;
                                        $rateOfReturn = $total1/100;
                                        $openingBal = $initial;
                                        $age = $currentage;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                            $withdrawalAmountMain = 0;
                                            $age ++;
                                            if(isset($period1))
                                            {
                                                if($period1 == $year)
                                                $maxMoneyBackPeriod1 = true;
                                                else
                                                $maxMoneyBackPeriod1 = false;
                                                
                                                $withdrawalAmount1 = $amount1;
                                                
                                                if($maxMoneyBackPeriod1 == true)
                                                $withdrawalAmountMain += $amount1;
                                            }
                                            if(isset($period2))
                                            {
                                                if($period2 == $year)
                                                $maxMoneyBackPeriod2 = true;
                                                else
                                                $maxMoneyBackPeriod2 = false;
                                                
                                                $withdrawalAmount2 = $amount2;
                                                
                                                if($maxMoneyBackPeriod2 == true)
                                                $withdrawalAmountMain += $amount2;
                                            }
                                            if(isset($period3))
                                            {
                                                if($period3 == $year)
                                                $maxMoneyBackPeriod3 = true;
                                                else
                                                $maxMoneyBackPeriod3 = false;
                                                
                                                $withdrawalAmount3 = $amount3;
                                                
                                                if($maxMoneyBackPeriod3 == true)
                                                $withdrawalAmountMain += $amount3;
                                            }
                                            if(isset($period4))
                                            {
                                                if($period4 == $year)
                                                $maxMoneyBackPeriod4 = true;
                                                else
                                                $maxMoneyBackPeriod4 = false;
                                                
                                                $withdrawalAmount4 = $amount4;
                                                
                                                if($maxMoneyBackPeriod4 == true)
                                                $withdrawalAmountMain += $amount4;
                                            }
                                            
                                            if(isset($period5))
                                            {
                                                if($period5 == $year)
                                                $maxMoneyBackPeriod5 = true;
                                                else
                                                $maxMoneyBackPeriod5 = false;
                                                
                                                $withdrawalAmount5 = $amount5;
                                                
                                                if($maxMoneyBackPeriod5 == true)
                                                $withdrawalAmountMain += $amount5;
                                            }
                                            
                                            $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {   @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                         <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }

                                            if($year == 1){
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }
                                            
                                            $year++;
                                                        
                                            $openingBal = $yearEndValue;
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        
                        
                        @if($calcType == 3)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                        $year = 1;
                                        $accumulationPeriod = $deferment;
                                        $swpPeriod = $moneyback * ($installments - 1) + $deferment;
                                        $age = $currentage+1;
                                        $fstpayemntAfter = $deferment;
                                        $interval = $moneyback;
                                        $fgap = $deferment;
                                        $openingBal = $initial;
                                        $nowAllow = false;
                                        $tlec = 0;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            $moneybackPeriodWorking = false;
                                            if($year == $fgap)
                                            {
                                                $moneybackPeriodWorking = true;
                                                $fgap = $interval;
                                                $nowAllow = true;
                                            }
                                            
                                            if(($tlec % $fgap == 0) && $nowAllow){
                                            $moneybackPeriodWorking = true;
                                            }
                                            
                                            $rateOfReturnAcc = $total1/100;
                                            $rateOfReturnDist = $distribution/100;
                                            if($year <= $accumulationPeriod)
                                                $applicableRateOfreturn = $rateOfReturnAcc;
                                            else
                                                $applicableRateOfreturn = $rateOfReturnDist;
                                                
                                            $eoyVal = $openingBal + $openingBal * $applicableRateOfreturn;
                                                
                                            $withdrawalAmount = $eachwithdraw;
                                                
                                            if($moneybackPeriodWorking == false)
                                                $withdrawalAmount = 0;
                                                
                                            $yearEndVal = $eoyVal - $withdrawalAmount;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {  @endphp

                                                    </table>
                                                    </div>
                                                    <div class="page-break"></div>
                                                
                                                    <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </header>
                                                    <div style="padding: 0 5%">
                                                        @include('frontend.calculators.common.footer')
                                                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                            <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>

                                                    @php
                                                    $rowCount = 0;
                                            }
                                                
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($withdrawalAmount)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($yearEndVal)."</td></tr>");
                                                $year++;
                                                $openingBal = $yearEndVal;
                                                $age++;
                                                if($nowAllow)
                                                    $tlec++;
                                                    
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        
                        @if($calcType == 4)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    
                                    @php
                                        $year = 1;
                                        $swpPeriod = $withdrawal * $interval;
                                        $age = $currentage + 1;
                                        $maxMoneyBackPeriod = $interval;
                                        $investmentPeriod = $invper;
                                        $annualInvestment = $initial;
                                        $openingBal = $initial;
                                        $rateOfReturn = $total1/100;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $investmentPeriod)
                                                $investmentMade = $annualInvestment;
                                            else
                                                $investmentMade = 0;
                                                
                                            $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                            
                                            if($year % $interval == 0)
                                                $maxMoneyBackPeriodWorking = true;
                                            else
                                                $maxMoneyBackPeriodWorking = false;
                                                
                                            if($maxMoneyBackPeriodWorking == true)
                                                $withdrawMade = $withdrawamount;
                                            else
                                                $withdrawMade = 0;
                                                
                                            $yearEndVal = round($eoyVal - $withdrawMade);
                                            if($year <= $invper)
                                                $annualLump = $initial;
                                            else
                                                $annualLump = 0;
                                            
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {  @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                            
                                                <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                @include('frontend.calculators.common.footer')
                                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                <table class="table table-bordered">
                                                    <tbody>
                                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                                

                                            if($year == 1){
                                                echo ("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($annualLump)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($yearEndVal)."</td></tr>");
                                            }else{
                                                echo ("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($annualLump)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($yearEndVal)."</td></tr>");
                                            }
                                            

                                            $age++;
                                            $year++;
                                            $openingBal = $yearEndVal;
                                            
                                            if($year <= $investmentPeriod)
                                                $openingBal += $investmentMade;
                                                    
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        @if($calcType == 5)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Years @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    
                                    @php
                                        $year = 1;
                                        $swpPeriod = $lastwithdraw;
                                        $rateOfReturn = $total1/100;
                                        $openingBal = $initial;
                                        $age = $currentage;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $invper)
                                            $annualInv = $initial;
                                            else
                                            $annualInv = 0;
                                            $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                            $withdrawalAmountMain = 0;
                                            $age ++;
                                            if(isset($period1))
                                            {
                                                if($period1 == $year)
                                                $maxMoneyBackPeriod1 = true;
                                                else
                                                $maxMoneyBackPeriod1 = false;
                                                
                                                $withdrawalAmount1 = $amount1;
                                                
                                                if($maxMoneyBackPeriod1 == true)
                                                $withdrawalAmountMain += $amount1;
                                            }
                                            if(isset($period2))
                                            {
                                                if($period2 == $year)
                                                $maxMoneyBackPeriod2 = true;
                                                else
                                                $maxMoneyBackPeriod2 = false;
                                                
                                                $withdrawalAmount2 = $amount2;
                                                
                                                if($maxMoneyBackPeriod2 == true)
                                                $withdrawalAmountMain += $amount2;
                                            }
                                            if(isset($period3))
                                            {
                                                if($period3 == $year)
                                                $maxMoneyBackPeriod3 = true;
                                                else
                                                $maxMoneyBackPeriod3 = false;
                                                
                                                $withdrawalAmount3 = $amount3;
                                                
                                                if($maxMoneyBackPeriod3 == true)
                                                $withdrawalAmountMain += $amount3;
                                            }
                                            if(isset($period4))
                                            {
                                                if($period4 == $year)
                                                $maxMoneyBackPeriod4 = true;
                                                else
                                                $maxMoneyBackPeriod4 = false;
                                                
                                                $withdrawalAmount4 = $amount4;
                                                
                                                if($maxMoneyBackPeriod4 == true)
                                                $withdrawalAmountMain += $amount4;
                                            }
                                            
                                            if(isset($period5))
                                            {
                                                if($period5 == $year)
                                                $maxMoneyBackPeriod5 = true;
                                                else
                                                $maxMoneyBackPeriod5 = false;
                                                
                                                $withdrawalAmount5 = $amount5;
                                                
                                                if($maxMoneyBackPeriod5 == true)
                                                $withdrawalAmountMain += $amount5;
                                            }
                                            
                                            $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {
                                                @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr class="headerbg"><td>@if(isset($currentage))Age @else Years @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                            
                                            if($year == 1){
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($annualInv)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($annualInv)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }
                                            
                                            $year++;
                                            $openingBal = $yearEndValue;
                                            
                                            if($year <= $invper)
                                            $openingBal += $initial;
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        @if($calcType == 6)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                    
                                    @php
                                        $year = 1;
                                        $accumulationPeriod = $deferment;
                                        $swpPeriod = $moneyback * ($installments - 1) + $deferment;
                                        $age =  1;
                                        $fstpayemntAfter = $deferment;
                                        $interval = $moneyback;
                                        $fgap = $deferment;
                                        $openingBal = $initial;
                                        $investmentPeriod = $invper;
                                        $rateOfReturn = $total1/100;
                                        $nowAllow = false;
                                        $tlec = 0;
                                        $rowCount = 0;
                                        while($year <= $invper)
                                        {
                                            $withdrawMade = 0;
                                            if($year < $accumulationPeriod)
                                                $moneyBackPeriodWorking = false;
                                            else if($year == $accumulationPeriod)
                                                $moneyBackPeriodWorking = true;
                                            else{
                                                if($distribution % $interval == 0)
                                                $moneyBackPeriodWorking = true;
                                                else
                                                $moneyBackPeriodWorking = false;
                                            }
                                             
                                            if($year <= $investmentPeriod)
                                                $investmentMade = $initial;
                                            else
                                                $investmentMade = 0;
                                                
                                            $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                            //dd($eoyVal);
                                            $withdrawalAmt = $eachwithdraw;
                                                
                                            if($year == $deferment)
                                            {
                                                //$withdrawMade = $withdrawalAmt;
                                                $nowAllow = true;
                                            }
                                               
                                            if(($tlec % $interval == 0) && $nowAllow)
                                            {
                                                //$withdrawMade = $withdrawalAmt;
                                            }
                                                    
                                            $yearEndBal = $eoyVal - $withdrawMade;
                                                    
                                            if(isset($currentage))
                                                $agecount = $age + $currentage;
                                            else
                                               $agecount = $age;

                                            $rowCount++;
                                            if($rowCount == 26)
                                            {  @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                            
                                            echo("<tr><td>".$agecount."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($investmentMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndBal)."</td></tr>");
                                            $age++;
                                            $openingBal = $yearEndBal;
                                            $year++;
                                            
                                            
                                            if($year <= $investmentPeriod)
                                                $openingBal += $investmentMade;
                                            
                                            if($nowAllow)
                                                $tlec++;
                                                            
                                                      
                                        }
                                    @endphp
                                </tbody>
                            </table>
                            </div>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            <div style="padding: 0 5%">
                            
                                @include('frontend.calculators.common.footer')
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                                
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                        
                                        @php
                                            $year = 1;
                                            $tlec = 1;
                                            $rowCount = 0;
                                            $accumulationPeriod = $deferment;
                                            while($year <= $swpPeriod)
                                            {
                                                $withdrawMade = 0;
                                                if($year < $accumulationPeriod)
                                                    $moneyBackPeriodWorking = false;
                                                else if($year == $accumulationPeriod)
                                                    $moneyBackPeriodWorking = true;
                                                else if($year > $accumulationPeriod)
                                                {
                                                    if($tlec % $interval == 0)
                                                    $moneyBackPeriodWorking = true;
                                                    else
                                                    $moneyBackPeriodWorking = false;
                                                    
                                                    $tlec++;
                                                }
                                                         
                                                if($year <= $investmentPeriod)
                                                    $investmentMade = $initial;
                                                else
                                                    $investmentMade = 0;
                                                            
                                                if($year > $accumulationPeriod)
                                                {
                                                    $rateOfReturn = $distribution/100;
                                                    //dd($rateOfReturn);
                                                }
                                                else
                                                {
                                                    $rateOfReturn = $total1/100;
                                                    //dd($rateOfReturn);
                                                }
                                                $eoyVal = $openingBal + $openingBal * $rateOfReturn;
                                                //dd($eoyVal);
                                                $withdrawalAmt = $eachwithdraw;
                                                            
                                                if($year == $deferment)
                                                {
                                                    $withdrawMade = $withdrawalAmt;
                                                    $nowAllow = true;
                                                }
                                                           
                                                if($moneyBackPeriodWorking)
                                                {
                                                    $withdrawMade = $withdrawalAmt;
                                                }
                                                                
                                                $yearEndBal = $eoyVal - $withdrawMade;
                                                
                                                if(isset($currentage))
                                                {
                                                    $agecount = $age + $currentage;
                                                }
                                                else
                                                   $agecount = $age;
                                                $rowCount++;
                                                if($rowCount == 26)
                                                { @endphp

                                                    </table>
                                                    </div>
                                                    <div class="page-break"></div>
                                                    
                                                    <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                    </header>
                                
                                                    <div style="padding: 0 5%">
                                                        @include('frontend.calculators.common.footer')
                                                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                             @php
                                                    $rowCount = 0;
                                                }
                                                
                                                echo("<tr><td>".$agecount."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndBal)."</td></tr>");

                                                $age++;
                                                $openingBal = $yearEndBal;
                                                $year++;
                                                            
                                                            
                                                      
                                            }
                                        @endphp
                                    </tbody>
                                 </table>
                            </div>
                        @endif
                        @if($calcType == 7)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    
                                    @php
                                        $year = 1;
                                        $swpPeriod = $withdrawal * $interval;
                                        $age = $currentage + 1;
                                        $maxMoneyBackPeriod = $interval;
                                        $investmentPeriod = $invper;
                                        $sipAmount = $initial;
                                        $openingBal = 0;
                                        $rateOfReturn = $total1/100;
                                        $rowCount = 0;
                                        $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                                    
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $investmentPeriod)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                
                                            $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                            
                                            if($year % $interval == 0)
                                                $maxMoneyBackPeriodWorking = true;
                                            else
                                                $maxMoneyBackPeriodWorking = false;
                                                
                                            if($maxMoneyBackPeriodWorking == true)
                                                $withdrawMade = $withdrawamount;
                                            else
                                                $withdrawMade = 0;
                                            
                                            if($year <= $invper){
                                                $annualLump = $initial;
                                            }else{
                                                $annualLump = 0;
                                            }
                                                
                                            $yearEndVal = $eoyVal - $withdrawMade;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {  @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                    
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                         <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }

                                            if($year == 1){
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($annualLump)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndVal)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($annualLump)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndVal)."</td></tr>");
                                            }
                                            
                                            $age++;
                                            $year++;
                                            $openingBal = $yearEndVal;
                                                            
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        @if($calcType == 8)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                    @php
                                        $year = 1;
                                        $swpPeriod = $lastwithdraw;
                                        $age = $currentage + 1;
                                        $maxMoneyBackPeriod = $interval;
                                        $investmentPeriod = $invper;
                                        $sipAmount = $initial;
                                        $openingBal = 0;
                                        $rateOfReturn = $total1/100;
                                        $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $investmentPeriod)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                
                                            $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                            $withdrawalAmountMain = 0;
                                            $age ++;
                                            if(isset($period1))
                                            {
                                                if($period1 == $year)
                                                $maxMoneyBackPeriod1 = true;
                                                else
                                                $maxMoneyBackPeriod1 = false;
                                                
                                                $withdrawalAmount1 = $amount1;
                                                
                                                if($maxMoneyBackPeriod1 == true)
                                                $withdrawalAmountMain += $amount1;
                                            }
                                            if(isset($period2))
                                            {
                                                if($period2 == $year)
                                                $maxMoneyBackPeriod2 = true;
                                                else
                                                $maxMoneyBackPeriod2 = false;
                                                
                                                $withdrawalAmount2 = $amount2;
                                                
                                                if($maxMoneyBackPeriod2 == true)
                                                $withdrawalAmountMain += $amount2;
                                            }
                                            if(isset($period3))
                                            {
                                                if($period3 == $year)
                                                $maxMoneyBackPeriod3 = true;
                                                else
                                                $maxMoneyBackPeriod3 = false;
                                                
                                                $withdrawalAmount3 = $amount3;
                                                
                                                if($maxMoneyBackPeriod3 == true)
                                                $withdrawalAmountMain += $amount3;
                                            }
                                            if(isset($period4))
                                            {
                                                if($period4 == $year)
                                                $maxMoneyBackPeriod4 = true;
                                                else
                                                $maxMoneyBackPeriod4 = false;
                                                
                                                $withdrawalAmount4 = $amount4;
                                                
                                                if($maxMoneyBackPeriod4 == true)
                                                $withdrawalAmountMain += $amount4;
                                            }
                                            
                                            if(isset($period5))
                                            {
                                                if($period5 == $year)
                                                $maxMoneyBackPeriod5 = true;
                                                else
                                                $maxMoneyBackPeriod5 = false;
                                                
                                                $withdrawalAmount5 = $amount5;
                                                
                                                if($maxMoneyBackPeriod5 == true)
                                                $withdrawalAmountMain += $amount5;
                                            }
                                            
                                            $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {   @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                    
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                         <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                            
                                            if($year == 1){
                                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(($sipDone*12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".($year + $currentage)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($sipDone*12)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }

                                            $year++;
                                            $openingBal = $yearEndValue;
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        @if($calcType == 9)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                                     
                                    @php
                                        $year = 1;
                                        $accumulationPeriod = $deferment;
                                        $swpPeriod = $moneyback * ($installments -1)+$deferment;
                                        $age = $currentage+1;
                                        $fpaymentAfter = $accumulationPeriod;
                                        $sipAmount = $initial;
                                        $openingBal = 0;
                                        $rateOfReturn = $total1/100;
                                        $rateOfReturnMonthly = pow((1+$total1/100),(1/12))-1;
                                        $incrMod = 0;
                                        $rowCount = 0;
                                        while($year <= $invper)
                                        {
                                            $moneybackPeriodWorking = false;
                                            if($year < $accumulationPeriod)
                                                $moneybackPeriodWorking = false;
                                            if($year == $accumulationPeriod)
                                                $moneybackPeriodWorking = true;

                                            if($year > $accumulationPeriod)
                                            {
                                                $incrMod++;
                                                if($incrMod == $deferment-1){
                                                    $moneybackPeriodWorking = true;
                                                    $incrMod = 0;
                                                }else{
                                                    $moneybackPeriodWorking = false;
                                                }
                                            }
                                                            
                                            if($year <= $invper)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                                
                                            $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                            if($moneybackPeriodWorking == true)
                                                $withDraw = $eachwithdraw;
                                            else
                                                $withDraw = 0;
                                                                
                                            $yearEndValue = $eoyVal;
                                            $rowCount++;
                                            if($rowCount ==26)
                                            {  @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }

                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($sipDone*12)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            $year++;
                                            
                                            $openingBal = $yearEndValue;
                                            $age++;
                                                            
                                        }
                                    @endphp
                                </tbody>
                            </table>
                            <div class="page-break"></div>
                            <header>
                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                    <tbody>
                                    <tr>
                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:130px; margin-top: 15px;" src="{{$company_logo}}" alt=""></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </header>
                            @include('frontend.calculators.common.footer')
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                        $year = 1;
                                        $tlec = 0;
                                        $incrMod = 0;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            $moneybackPeriodWorking = false;
                                            if($year < $accumulationPeriod)
                                                $moneybackPeriodWorking = false;
                                            if($year == $accumulationPeriod)
                                                $moneybackPeriodWorking = true;
                                            if($year > $accumulationPeriod)
                                            {
                                                $incrMod++;
                                                if($incrMod == $moneyback){
                                                    $moneybackPeriodWorking = true;
                                                    $incrMod = 0;
                                                }
                                                else
                                                    $moneybackPeriodWorking = false;
                                            }
                                                            
                                            if($year <= $invper)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                                
                                            if($year > $accumulationPeriod)
                                                $rateOfReturn  = $distribution/100;
                                                                
                                            $eoyVal = ($openingBal+$openingBal*$rateOfReturn);
                                            if($moneybackPeriodWorking == true)
                                                $withDraw = $eachwithdraw;
                                            else
                                                $withDraw = 0;
                                                                
                                            $yearEndValue = $eoyVal - $withDraw;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {
                                                @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withDraw)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            $year++;
                                            $openingBal = $yearEndValue;
                                            $age++;
                                                    
                                              
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif


                        @if($calcType == 10)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    
                                    @php
                                        $year = 1;
                                        $swpPeriod = $withdrawal * $interval;
                                        $age = $currentage + 1;
                                        $maxMoneyBackPeriod = $interval;
                                        $investmentPeriod = $invper;
                                        $sipAmount = $monthlysipamount;
                                        $openingBal = $initial;
                                        $rateOfReturn = $total1/100;
                                        $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                        $rowCount = 0;
                                        
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $investmentPeriod)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                
                                            $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                            
                                            if($year % $interval == 0)
                                                $maxMoneyBackPeriodWorking = true;
                                            else
                                                $maxMoneyBackPeriodWorking = false;
                                                
                                            if($maxMoneyBackPeriodWorking == true)
                                                $withdrawMade = $withdrawamount;
                                            else
                                                $withdrawMade = 0;
                                                
                                            $yearEndVal = $eoyVal - $withdrawMade;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {   @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                    <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                        <tr>
                                                            <td style="text-align:left; border:0;">&nbsp;</td>
                                                            <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                  @include('frontend.calculators.common.footer')
                                                  <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                            @php
                                                            $rowCount = 0;
                                            }

                                            if($year == 1){
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($initial+($sipDone * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndVal)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format(($sipDone * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawMade)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndVal)."</td></tr>");
                                            }
                                            
                                            $age++;
                                            $year++;
                                            $openingBal = $yearEndVal;
                                                        
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif

                        @if($calcType == 11)
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>

                                    @php
                                        $year = 1;
                                        $swpPeriod = $lastwithdraw;
                                        $age = $currentage;
                                        $maxMoneyBackPeriod = $interval;
                                        $investmentPeriod = $invper;
                                        $sipAmount = $sipamount;
                                        $openingBal = $initial;
                                        $rateOfReturn = $total1/100;
                                        $rateOfReturnMonthly = pow((1+$rateOfReturn),(1/12))-1;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            if($year <= $investmentPeriod)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                        
                                            $eoyVal = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),12)-1)/$rateOfReturnMonthly)+($openingBal+$openingBal*$rateOfReturn);
                                            $withdrawalAmountMain = 0;
                                            $age ++;
                                            if(isset($period1))
                                            {
                                                if($period1 == $year)
                                                $maxMoneyBackPeriod1 = true;
                                                else
                                                $maxMoneyBackPeriod1 = false;
                                                
                                                $withdrawalAmount1 = $amount1;
                                                
                                                if($maxMoneyBackPeriod1 == true)
                                                $withdrawalAmountMain += $amount1;
                                            }
                                            if(isset($period2))
                                            {
                                                if($period2 == $year)
                                                $maxMoneyBackPeriod2 = true;
                                                else
                                                $maxMoneyBackPeriod2 = false;
                                                
                                                $withdrawalAmount2 = $amount2;
                                                
                                                if($maxMoneyBackPeriod2 == true)
                                                $withdrawalAmountMain += $amount2;
                                            }
                                            if(isset($period3))
                                            {
                                                if($period3 == $year)
                                                $maxMoneyBackPeriod3 = true;
                                                else
                                                $maxMoneyBackPeriod3 = false;
                                                
                                                $withdrawalAmount3 = $amount3;
                                                
                                                if($maxMoneyBackPeriod3 == true)
                                                $withdrawalAmountMain += $amount3;
                                            }
                                            if(isset($period4))
                                            {
                                                if($period4 == $year)
                                                $maxMoneyBackPeriod4 = true;
                                                else
                                                $maxMoneyBackPeriod4 = false;
                                                
                                                $withdrawalAmount4 = $amount4;
                                                
                                                if($maxMoneyBackPeriod4 == true)
                                                $withdrawalAmountMain += $amount4;
                                            }
                                            
                                            if(isset($period5))
                                            {
                                                if($period5 == $year)
                                                $maxMoneyBackPeriod5 = true;
                                                else
                                                $maxMoneyBackPeriod5 = false;
                                                
                                                $withdrawalAmount5 = $amount5;
                                                
                                                if($maxMoneyBackPeriod5 == true)
                                                $withdrawalAmountMain += $amount5;
                                            }
                                            
                                            $yearEndValue = round($eoyVal - $withdrawalAmountMain);
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {
                                                @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                         <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }

                                            if($year == 1){
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format($initial+($sipDone * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }else{
                                                echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ".custome_money_format(($sipDone * 12))."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withdrawalAmountMain)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            }
                                            
                                            $year++;
                                            $openingBal = $yearEndValue;
                                        }
                                    @endphp
                                </tbody>
                            </table>
                        @endif
                        @if($calcType == 12)
                                                
                                                
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                                     
                                    @php
                                        $year = 1;
                                        $accumulationPeriod = $deferment;
                                        $swpPeriod = $moneyback * ($installments -1)+$deferment;
                                        $age = $currentage+1;
                                        $fpaymentAfter = $accumulationPeriod;
                                        $sipAmount = $initial;
                                        $openingBal = 0;
                                        $rateOfReturn = $total1/100;
                                        $rateOfReturnMonthly = pow((1+$total1/100),(1/12))-1;
                                        $incrMod = 0;
                                        $rowCount = 0;
                                        while($year <= $invper)
                                        {
                                            $moneybackPeriodWorking = false;
                                            if($year < $accumulationPeriod)
                                             $moneybackPeriodWorking = false;
                                            if($year == $accumulationPeriod)
                                             $moneybackPeriodWorking = true;
                                            if($year > $accumulationPeriod)
                                            {
                                            $incrMod++;
                                                if($incrMod == $moneyback){
                                                    $moneybackPeriodWorking = true;
                                                    $incrMod = 0;
                                                    }
                                                else
                                                    $moneybackPeriodWorking = false;
                                            }
                                                
                                            if($year <= $invper)
                                                $sipDone = $monthlysipamount;
                                            else
                                                $sipDone = 0;
                                                    
                                            // dd($sipDone);
                                            $fval1 = $sipDone * (1+$rateOfReturnMonthly) * ((pow((1+$rateOfReturnMonthly),(12 * $year))-1)/$rateOfReturnMonthly);
                                            
                                            $fval2 = $initial * pow((1+$rateOfReturnMonthly),(12 * $year));
                                            $eoyVal = $fval1 + $fval2;
                                            
                                            if($moneybackPeriodWorking == true)
                                                $withDraw = $eachwithdraw;
                                            else
                                                $withDraw = 0;
                                                
                                            $yearEndValue = $eoyVal;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {
                                                @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase <br/> Projected Annual Investment Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                            <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Annual Investment</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                            
                                            if($year == 1)
                                                $sipDone = ($initial + $monthlysipamount * 12) / 12;
                                                            
                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($sipDone * 12)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");
                                            $year++;
                                            
                                            $openingBal = $yearEndValue;
                                            $age++;
                                                            
                                        }
                                    @endphp
                                </tbody>
                            </table>
                            </div>
                            <div class="page-break"></div>
                            
                            <header>       
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;">&nbsp;</td>
                                    <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                            </header>
                            <div style="padding: 0 5%">
                                @include('frontend.calculators.common.footer')
                                <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                                <table class="table table-bordered">
                                    <tbody>
                                     <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                    @php
                                        $year = 1;
                                        $tlec = 0;
                                        $incrMod = 0;
                                        $rowCount = 0;
                                        while($year <= $swpPeriod)
                                        {
                                            $moneybackPeriodWorking = false;
                                            if($year < $accumulationPeriod)
                                                $moneybackPeriodWorking = false;
                                            if($year == $accumulationPeriod)
                                                $moneybackPeriodWorking = true;
                                            if($year > $accumulationPeriod)
                                            {
                                                $incrMod++;
                                                if($incrMod == $moneyback){
                                                    $moneybackPeriodWorking = true;
                                                    $incrMod = 0;
                                                    }
                                                else
                                                    $moneybackPeriodWorking = false;
                                            }
                                                            
                                            if($year <= $invper)
                                                $sipDone = $sipAmount;
                                            else
                                                $sipDone = 0;
                                                                
                                            if($year > $accumulationPeriod)
                                                $rateOfReturn  = $distribution/100;
                                            else
                                                $rateOfReturn = $total1/100;
                                                                
                                            $eoyVal = ($openingBal+$openingBal*$rateOfReturn);
                                            if($moneybackPeriodWorking == true)
                                                $withDraw = $eachwithdraw;
                                            else
                                                $withDraw = 0;
                                                                
                                            $yearEndValue = $eoyVal - $withDraw;
                                            $rowCount++;
                                            if($rowCount == 26)
                                            {
                                                @endphp
                                                </table>
                                                </div>
                                                <div class="page-break"></div>
                                                
                                                <header>       
                                                <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                    <tr>
                                                        <td style="text-align:left; border:0;">&nbsp;</td>
                                                        <td style="text-align:right; border:0;" valign="middle"><img style="display:inline-block; width:110px; height:auto; margin-top: 40px;" src="{{$company_logo}}" alt=""></td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                                </header>
                                                <div style="padding: 0 5%">
                                                    @include('frontend.calculators.common.footer')
                                                    <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase <br/> Projected Periodic Withdrawal & Fund Value</h5>
                                                    <table class="table table-bordered">
                                                        <tbody>
                                                        <tr class="headerbg"><td>@if(isset($currentage))Age @else Year @endif</td><td>Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                                @php
                                                $rowCount = 0;
                                            }
                                                            
                                            echo("<tr><td>".$age."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($withDraw)."</td><td><span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span> ". custome_money_format($yearEndValue)."</td></tr>");

                                            $year++;
                                            $openingBal = $yearEndValue;
                                            $age++;
                                                            
                                                      
                                        }
                                    @endphp
                                </tbody>
                            </table>
                            </div>
                        @endif

                    @endif
                    <div style="">
                        <p>
                            <br/>
                            * The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}<br>
                        </p>
                    </div>
                    <br/>
                    <br/>
                    
                    @include('frontend.calculators.suggested.pdf_design_fix')
                </div>
            </div>
        </main>
    </body>
</html>