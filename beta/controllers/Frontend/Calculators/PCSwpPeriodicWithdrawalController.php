<?php

namespace App\Http\Controllers\Frontend\Calculators;

use PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Session;
use Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Mail;
use App\Models\SaveCalculators;
use App\Models\CalculatorHeading;
use App\Models\Displayinfo;
use App\Models\Membership;
use App\Models\UserHistory;
use App\Models\History;
use App\Models\HistorySuggestedScheme;
use App\Models\Calculator;
use App\Models\FundPerformanceCreateList;
use App\Models\FundPerformanceCreateCategoryList;
use App\Models\SchemecodeData;
use App\Models\Savelist;
use App\Models\Savelistsoftcopy;
use DB;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class PCSwpPeriodicWithdrawalController extends Controller
{
    
    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }
    
    
    public function periodicCheck(Request $request)
    {
        $calcType = '';
        $sendable = "yoyo";
        
        $input = $request->all();
        
        // dd($input);
        extract($input);
        if($investmentmode == 1 && $def==1 && $swpmode == 1)
        {
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
                
                $sendable = "est,".$maxMoneyBack;
        }
        else if($investmentmode == 1 && $def==1 && $swpmode == 2)
        {
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
            
        }
        
       else if($investmentmode == 1 && $def==2)
       {
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
       }
       else if($investmentmode == 2 && $def==1 && $swpmode == 1)
       {
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
            
       }
       else if($investmentmode == 2 && $def==1 && $swpmode == 2)
       {
            $calcType = 5;
            $sendable = "tst";
            $presentValue = $initial + $initial/($total1/100) * (1-pow((1+$total1/100),(-$invper+1)));
            
            $lastWithdrawPeriod = '';
            if(isset($period1))
            {
            if($period1 <= $invper){
                $value1 = (1+$total1/100)*$initial*((pow((1+$total1/100),$period1)-1)/($total1/100));
                
                $lastWithdrawPeriod = $period1;
                }
            else
                $value1 = (1+$total1/100)*$initial*((pow((1+$total1/100),$period1)-1)/($total1/100))*pow((1+$total1/100),($period1-$invper));
                
                $balance1 = $value1 - $amount1;
                $sendable .= ",".$value1;
                $balance5 = $balance1;
            }
            
            if(isset($period2))
            {
                $monthlyReturn = $total1/100;
            if($period2 <= $invper){
                $value2 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period2-$period1))-1)/($total1/100))+$balance1*pow((1+$total1/100),($period2-$period1));
                $lastWithdrawPeriod = $period2;
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
                $monthlyReturn = $total1/100;
            if($period3 <= $invper){
                $value3 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period3-$period2))-1)/($total1/100))+$balance2*pow((1+$total1/100),($period3-$period2));
                $lastWithdrawPeriod = $period3;
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
            $monthlyReturn = $total1/100;
            if($period4 <= $invper){
                $value4 = (1+$total1/100)*$initial*((pow((1+$total1/100),($period4-$period3))-1)/($total1/100))+$balance3*pow((1+$total1/100),($period4-$period3));
                $lastWithdrawPeriod = $period4;
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
       }
       else if($investmentmode == 2 && $def==2)
       {
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
                
                $sendable = "est,".$maxMoneyBack;
       }
       else if($investmentmode == 3 && $def==1 && $swpmode == 1)
       {
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
            $terminalBal = ($presentValue - $lumpsumSwp) * pow((1+$total1/100),($interval * $withdrawal));
            
            $sendable = "est,".$maxMoneyBack;
            
       }
       
       else if($investmentmode == 3 && $def==1 && $swpmode == 2)
       {
        $calcType = 8;
        $sendable = "tst";
        $Period = $invper * 12;
        $monthlyReturn = pow((1+$total1/100),(1/12))-1;
        $presentValue = $initial + $initial * (1-pow((1+$monthlyReturn),(-$Period+1)));
        if(isset($period1))
            {
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
            
            
            //$endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
       }
       else if($investmentmode == 3 && $def==2)
       {
            $calcType = 9;
            $sipMonths = $invper * 12;
            $monthlyReturn = pow((1+$total1/100),(1/12))-1;
            
            $futureValue = $initial * pow((1+$total1/100),$deferment);
            
            $swpperiod = $installments;
            $return1 = pow((1+$distribution/100),$moneyback) - 1;
            
            $valueAtEndOfInvPer = (1+$monthlyReturn) * $initial * (pow((1+$monthlyReturn),$sipMonths)-1)/($monthlyReturn);
            $futureValue = $valueAtEndOfInvPer * pow((1+$monthlyReturn),($deferment*12));
            $maxMoneyBack = round($futureValue/(1+(1/$return1) * (1-pow((1+$return1),(-$swpperiod+1)))));
            
            $lumpsumswp = ($eachwithdraw+($eachwithdraw/$return1)*(1-pow((1+$return1),(-$swpperiod+1))));
            if(($futureValue - $lumpsumswp)*pow((1+$distribution/100),(($installments-1)*$moneyback)) < 100)
                $terminalBalance = 0;
            else
                $terminalBalance = ($futureValue - $lumpsumswp)*pow((1+$total1/100),(($installments-1)*$moneyback));
                
                $sendable = "est,".$maxMoneyBack;
       }
       else if($investmentmode == 4 && $def == 1 && $swpmode == 1)
       {
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
            
            $sendable = "est,".$maxMoneyBack;
            
       }
       else if($investmentmode == 4 && $def == 1 && $swpmode == 2)
       {
           $calcType = 11; 
           $sendable = "tst";
           $Period = $invper * 12;
           $sipamount = $monthlysipamount;
        $monthlyReturn = pow((1+$total1/100),(1/12))-1;
        $presentValue = $sipamount + $sipamount/$monthlyReturn * (1-pow((1+$monthlyReturn),(-$Period+1)))+$initial;
        if(isset($period1))
            {
            
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
            if($period2 > $invper && $period1 > $invper){
                $value2 = $balance1 * pow((1+$total1/100),($period2-$period1));
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
            
            
            //$endValue = $balance5 * pow((1+$total1/100),($lastwithdraw-$lastWithdrawPeriod));
           
       }
       else if($investmentmode == 4 && $def==2)
       {
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
                $terminalBalance = ($futureValue - $lumpsumswp)*pow((1+$total1/100),(($installments-1)*$moneyback));
                
                $sendable = "est,".$maxMoneyBack;
       }
       
       return $sendable;
    }
    
    public function index(Request $request){

        if($request->action == "back"){
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (Session::has('swp_periodic_withdrawal')) {
                $saveCalculatorsData = Session::get('swp_periodic_withdrawal');

                // dd($saveCalculatorsData);
                // dd(session()->get('calculator_duration'));
                $data = $saveCalculatorsData;
                
                $data['suggest'] = isset($data['suggest'])?$data['suggest']:"";
                
                $data['custom_list_input'] = "";
                $data['category_list_input'] = "";
                $data['suggestedlist_type'] = "";
                $data['scheme_type'] = [];
                $data['scheme_amount'] = [];

                if(isset($saveCalculatorsData['suggest'])){
                    
                    $saveCalculatorsData['suggested_performance'] = session()->get('suggested_performance');

                    $saveCalculatorsData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
                    $saveCalculatorsData['calculator_duration'] = session()->get('calculator_duration');
            
                    $data['form_data']['suggest'] = $saveCalculatorsData['suggest'];
                    if($saveCalculatorsData['suggest'] == 1){
                        $data['suggested_performance'] = $saveCalculatorsData['suggested_performance'];
                        $data['suggestedlist_type'] = $saveCalculatorsData['suggestedlist_type'];
                        $data['calculator_duration'] = $saveCalculatorsData['calculator_duration'];
            
                        $data['suggested_scheme_list'] = [];
            
                        if($data['suggestedlist_type'] == "createlist"){
                            $data['scheme_type'] = $saveCalculatorsData['scheme_type'];
                            $data['scheme_amount'] = $saveCalculatorsData['scheme_amount'];
                            $data['suggested_scheme_list'] = $saveCalculatorsData['suggested_scheme_list'];
                            Session::put('suggested_scheme_list', $saveCalculatorsData['suggested_scheme_list']);
                        }else if($data['suggestedlist_type'] == "customlist"){
                            $data['custom_list_input'] = $saveCalculatorsData['custom_list_input'];
                        }else if($data['suggestedlist_type'] == "categorylist"){
                            $data['category_list_input'] = $saveCalculatorsData['category_list_input'];
                        }            
                        
                        Session::put('suggested_performance', $saveCalculatorsData['suggested_performance']);
                        Session::put('calculator_duration', $saveCalculatorsData['calculator_duration']);
                    }else{
                        $data['suggested_performance'] = "";
                        $data['suggested_scheme_list'] = [];
                        $data['calculator_duration'] = [];
                    }
                }else{
                    $data['suggested_performance'] = "";
                    $data['suggested_scheme_list'] = [];
                    $data['calculator_duration'] = [];
                }

                
                $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_periodic_withdrawal')->first();
                $data['detail'] = DB::table("calculators")->where('url','premium-calculator/swp_periodic_withdrawal')->first();
                return view('frontend.calculators.swp_periodic_withdrawal.edit',$data);
                
            }else{
                return redirect()->route('frontend.swp_periodic_withdrawal');
            }
        }else{
            if (session()->has('suggested_scheme_list')){
                session()->forget('suggested_scheme_list');
            }
            if (session()->has('calculator_form_id')){
                session()->forget('calculator_form_id');
            }
            if (session()->has('swp_periodic_withdrawal')){
                session()->forget('swp_periodic_withdrawal');
            }
            if (session()->has('calc_title')){
                session()->forget('calc_title');
            }
            $ip_address = getIp();
    
            History::create([
                'list_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 47,
                'ip' => $ip_address
            ]);
            
            $data['calculater_heading'] = CalculatorHeading::where('key_name','=','swp_periodic_withdrawal')->first();
            return view('frontend.calculators.swp_periodic_withdrawal.index',$data);
        }
    }
    
    public function output(Request $request){
        $input = $request->all();
        $data = $input;

        $data['client'] = isset($input['client'])?$input['client']:"";
        $data['is_note'] = isset($input['is_note'])?$input['is_note']:"";
        $data['is_graph'] = isset($input['is_graph'])?$input['is_graph']:"";

        if (isset($input['suggest']) && isset($input['suggest'])){
                $data['suggest'] = $input['suggest'];
                $input['duration'] = isset($input['duration'])?$input['duration']:[];

                $data['suggested_performance'] = $input['include_performance'];
                session()->put('suggested_performance',$data['suggested_performance']);
                session()->forget('calculator_duration');
                session()->put('calculator_duration',$input['duration']);
                $data['suggestedlist_type'] = $input['suggestedlist_type'];
                if ($data['suggestedlist_type']=='customlist'){
                    $data['custom_list_input'] = $input['custom_list_input'];
                    $saveListDetails = FundPerformanceCreateList::where('id',$data['custom_list_input'])->first();
                    session()->forget('suggested_scheme_list');
                    $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecodes']);
                    if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                        foreach ($saveListDetails['schemecodes'] as $inp) {
                            $asset_scheme_option = explode("_", $inp);
                            $scheme = $asset_scheme_option[1];
                            $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                            if (session()->has('suggested_scheme_list')) {
                                $suggested_scheme_list = session()->get('suggested_scheme_list');
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            } else {
                                $suggested_scheme_list = array();
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            }
                        }
                    }

                }elseif ($data['suggestedlist_type']=='categorylist'){
                    $data['category_list_input'] = $input['category_list_input'];
                    $saveListDetails = FundPerformanceCreateCategoryList::where('id',$data['category_list_input'])->first();
                    session()->forget('suggested_scheme_list');
                    $saveListDetails['schemecodes'] = json_decode($saveListDetails['schemecode']);
                    if (isset($saveListDetails['schemecodes']) && count($saveListDetails['schemecodes'])) {
                        foreach ($saveListDetails['schemecodes'] as $inp) {
                            $scheme = $inp;
                            $lo_scheme_with_NAV = SchemecodeData::where('schemecode',$scheme)->first();
                            if (session()->has('suggested_scheme_list')) {
                                $suggested_scheme_list = session()->get('suggested_scheme_list');
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            } else {
                                $suggested_scheme_list = array();
                                array_push($suggested_scheme_list,json_decode($lo_scheme_with_NAV['data']));
                                session()->put('suggested_scheme_list',$suggested_scheme_list);
                            }
                        }
                    }
                }
        }
        Session::put('swp_periodic_withdrawal',$data);

        $ip_address = getIp();
        $scheme_count = 0;
        if(session()->has('suggested_scheme_list')){
            $scheme_count = count(session()->get('suggested_scheme_list'));
        }
    
        $history = History::create([
            'view_count' => 1,
            'user_id' => Auth::user()->id,
            'page_type' => "Calculator",
            'page_id' => 47,
            'scheme_count' => $scheme_count,
            'ip' => $ip_address
        ]);

        if(session()->has('suggested_scheme_list')){
            $suggested_scheme_list = session()->get('suggested_scheme_list');
            // dd($suggested_scheme_list);
            foreach ($suggested_scheme_list as $key => $value) {
                $insertData = [];
                $insertData['scheme_id'] = $value->Schemecode;
                $insertData['user_history_id'] = $history['id'];
                // dd($insertData);
                HistorySuggestedScheme::create($insertData);
            }
        }

        $calculators = Calculator::where("url","premium-calculator/swp_periodic_withdrawal")->first();
        if($calculators){
            $package_id = Auth::user()->package_id;
            $permission = DB::table("calculator_permissions")->where("calculator_id",$calculators->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                   "is_view"=>$permission->is_view,
                   "is_download"=>$permission->is_download,
                   "is_cover"=>$permission->is_cover,
                   "is_save"=>$permission->is_save
                ];
            }else{
                 $data['permission'] = [
                     "is_view"=>1,
                     "is_download"=>0,
                     "is_cover"=>0,
                     "is_save"=>0
                 ];
            }
        }else{
            $data['permission'] = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_cover"=>0,
                 "is_save"=>0
            ];
        }
        $data['edit_id'] = session()->get('calculator_form_id');
        // dd($data);
        return view('frontend.calculators.swp_periodic_withdrawal.output',$data);
    }
    
    public function pdf(Request $request){
         if (Session::has('swp_periodic_withdrawal')) {

            $data = Session::get('swp_periodic_withdrawal');
            $data['pdf_title_line1'] = $request->pdf_title_line1;
            $data['pdf_title_line2'] = $request->pdf_title_line2;
            $data['client_name'] = $request->client_name;
            if (Auth::check()){
                $user = Auth::user();
                $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                $data['name_color'] = $displayInfo->name_color;
                $data['company_name_color'] = $displayInfo->company_name_color;
                $data['city_color'] = $displayInfo->city_color;
                $data['address_color_background'] = $displayInfo->address_color_background;
                $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
                $data['amfi_registered'] = $displayInfo->amfi_registered;
                $data['footer_branding_option'] = $displayInfo->footer_branding_option;
                $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                $data['address'] = ($displayInfo->address_check && $displayInfo->address!='')?$displayInfo->address:'';

                if($data['address']){
                    $data['address2'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                    $data['address'] = $data['address']." ".$data['address2'];
                }else{
                    $data['address'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                }

                $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                if (isset($membership) && $membership > 0){
                    $data['watermark'] = 0;
                }else{
                    $data['watermark'] = 1;
                }
            }

            $ip_address = getIp();
            $scheme_count = 0;
            if(session()->has('suggested_scheme_list')){
                $scheme_count = count(session()->get('suggested_scheme_list'));
            }
        
            $history = History::create([
                'download_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 47,
                'scheme_count' => $scheme_count,
                'ip' => $ip_address
            ]);

            if(session()->has('suggested_scheme_list')){
                $suggested_scheme_list = session()->get('suggested_scheme_list');
                // dd($suggested_scheme_list);
                foreach ($suggested_scheme_list as $key => $value) {
                    $insertData = [];
                    $insertData['scheme_id'] = $value->Schemecode;
                    $insertData['user_history_id'] = $history['id'];
                    // dd($insertData);
                    HistorySuggestedScheme::create($insertData);
                }
            }


            $data['detail'] = Calculator::where("url","premium-calculator/swp_periodic_withdrawal")->first();

            if($data['pdf_title_line1']){
                $oMerger = PDFMerger::init();
                $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
                $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
                
                $pdf = PDF::loadView('frontend.calculators.swp_periodic_withdrawal.pdf', $data);
                $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
                
                $oMerger->merge();
                $oMerger->setFileName($data['detail']->name.'.pdf');
                return $oMerger->download();
            }else{

                $pdf = PDF::loadView('frontend.calculators.swp_periodic_withdrawal.pdf',$data);
                return $pdf->download($data['detail']->name.'.pdf');
            }
        }
    }
    
    public function save(Request $request){
        $requestData = $request->all();
        if(Session::has("swp_periodic_withdrawal"))
        {
            $data = Session::get("swp_periodic_withdrawal");

            $data['pie_chart2'] = Session::get('pie_chart2');

            $savedData = $data;

            $savedData['suggested_performance'] = session()->get('suggested_performance');

            $savedData['suggested_scheme_list'] = session()->get('suggested_scheme_list');
            $savedData['calculator_duration'] = session()->get('calculator_duration');
            if (Auth::check()){
                    $user = Auth::user();
                    $displayInfo = Displayinfo::where('user_id',$user->id)->first();
                    $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
                    $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
                    $data['name_color'] = $displayInfo->name_color;
                    $data['company_name_color'] = $displayInfo->company_name_color;
                    $data['city_color'] = $displayInfo->city_color;
                    $data['address_color_background'] = $displayInfo->address_color_background;
                    $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
                    $data['amfi_registered'] = $displayInfo->amfi_registered;
                    $data['footer_branding_option'] = $displayInfo->footer_branding_option;
                    $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
                    $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
                    $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
                    $data['address'] = ($displayInfo->address_check && $displayInfo->address!='')?$displayInfo->address:'';

                    if($data['address']){
                        $data['address2'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                        $data['address'] = $data['address']." ".$data['address2'];
                    }else{
                        $data['address'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                    }

                    $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
                    $membership = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
                    if (isset($membership) && $membership > 0){
                        $data['watermark'] = 0;
                    }else{
                        $data['watermark'] = 1;
                    }
                }
            $view = (string)View::make('frontend.calculators.swp_periodic_withdrawal.pdf',$data);
            
            $edit_id = session()->get('calculator_form_id');

            // dd($edit_id);

            if($edit_id){
                SaveCalculators::where('user_id',Auth::user()->id)->where('id',$edit_id)->update([
                    'title' => $requestData['title'],
                    'data' => serialize($savedData),
                ]);
                Storage::put('calculators/'.$edit_id.'.txt', $view);
            }else{
                $saveCal = SaveCalculators::create([
                    'title' => $requestData['title'],
                    'data' => serialize($savedData),
                    'calculator_id' => 47,
                    'user_id' => Auth::user()->id
                ]);
                Storage::put('calculators/'.$saveCal['id'].'.txt', $view);
            }

            $ip_address = getIp();
            $scheme_count = 0;
            if(session()->has('suggested_scheme_list')){
                $scheme_count = count(session()->get('suggested_scheme_list'));
            }
        
            $history = History::create([
                'save_count' => 1,
                'user_id' => Auth::user()->id,
                'page_type' => "Calculator",
                'page_id' => 47,
                'scheme_count' => $scheme_count,
                'ip' => $ip_address
            ]);

            if(session()->has('suggested_scheme_list')){
                $suggested_scheme_list = session()->get('suggested_scheme_list');
                // dd($suggested_scheme_list);
                foreach ($suggested_scheme_list as $key => $value) {
                    $insertData = [];
                    $insertData['scheme_id'] = $value->Schemecode;
                    $insertData['user_history_id'] = $history['id'];
                    // dd($insertData);
                    HistorySuggestedScheme::create($insertData);
                }
            }
            //return view('frontend.premium_calculator.recover_emis_through_sip_output',$input);
            return response("File saved successfully",200);
        }
    }
    
    public function edit(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();
        // dd($saveCalculators);
        if(!$saveCalculators){
            return redirect()->route('frontend.swp_periodic_withdrawal');
        }

        $data = unserialize($saveCalculators['data']);
        
        $data['suggest'] = isset($data['suggest'])?$data['suggest']:"";
        // dd($data);
        $form_data = $data;

        Session::put('calc_title', $saveCalculators->title);
        Session::put('calculator_form_id', $request->id);
        
        $data['calculater_heading'] = CalculatorHeading::where('key_name','=','goal_calculator')->first();

        $data['custom_list_input'] = "";
        $data['category_list_input'] = "";
        $data['suggestedlist_type'] = "";
        $data['scheme_type'] = [];
        $data['scheme_amount'] = [];

        if(isset($data['suggest'])){
            $data['form_data']['suggest'] = $form_data['suggest'];
            if($data['suggest'] == 1){
                $data['suggested_performance'] = $form_data['suggested_performance'];
                $data['suggestedlist_type'] = $form_data['suggestedlist_type'];
                $data['calculator_duration'] = $form_data['calculator_duration'];
    
                $data['suggested_scheme_list'] = [];
    
                if($data['suggestedlist_type'] == "createlist"){
                    $data['scheme_type'] = $form_data['scheme_type'];
                    $data['scheme_amount'] = $form_data['scheme_amount'];
                    $data['suggested_scheme_list'] = $form_data['suggested_scheme_list'];
                    Session::put('suggested_scheme_list', $data['suggested_scheme_list']);
                }else if($data['suggestedlist_type'] == "customlist"){
                    $data['custom_list_input'] = $form_data['custom_list_input'];
                }else if($data['suggestedlist_type'] == "categorylist"){
                    $data['category_list_input'] = $form_data['category_list_input'];
                }            
                
                Session::put('suggested_performance', $data['suggested_performance']);
                Session::put('calculator_duration', $data['calculator_duration']);
            }else{
                $data['suggested_performance'] = "";
                $data['suggested_scheme_list'] = [];
                $data['calculator_duration'] = [];
            }
        }else{
            $data['suggested_performance'] = "";
            $data['suggested_scheme_list'] = [];
            $data['calculator_duration'] = [];
        }

        // dd($data);

        return view('frontend.calculators.swp_periodic_withdrawal.edit',$data);
    }
    
    public function view(Request $request){

        $saveCalculators = SaveCalculators::where('id','=',$request->id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.swp_periodic_withdrawal');
        }

        $data = unserialize($saveCalculators['data']);

        Session::put('swp_periodic_withdrawal', $data);

        $data['edit_id'] = 0;
        $data['id'] = $request->id;
        $calculators = DB::table("calculators")->where('url','premium-calculator/swp_periodic_withdrawal')->first();
        if($calculators){
            $package_id = Auth::user()->package_id;
            $permission = DB::table("calculator_permissions")->where("calculator_id",$calculators->id)->where("package_id",$package_id)->first();
            if($permission){
                $data['permission'] = [
                   "is_view"=>$permission->is_view,
                   "is_download"=>$permission->is_download,
                   "is_cover"=>$permission->is_cover,
                   "is_save"=>$permission->is_save
                ];
            }else{
                 $data['permission'] = [
                     "is_view"=>1,
                     "is_download"=>0,
                     "is_cover"=>0,
                     "is_save"=>0
                 ];
            }
        }else{
            $data['permission'] = [
                 "is_view"=>1,
                 "is_download"=>0,
                 "is_cover"=>0,
                 "is_save"=>0
            ];
        }

        $data['savelists'] = Savelist::where('user_id',Auth::user()->id)->where('validate_at','>=',date('Y-m-d'))->orderBy('validate_at','desc')->get();
        $data['suggestedlists'] = Savelist::where('user_id',0)->orderBy('id','desc')->get();
        $data['detail'] = $calculators;
        return view('frontend.calculators.swp_periodic_withdrawal.view',$data);
    }
    
    public function merge_download(Request $request){

        $id = $request->save_file_id;

        $saveCalculators = SaveCalculators::where('id','=',$id)->first();

        if(!$saveCalculators){
            return redirect()->route('frontend.swp_periodic_withdrawal');
        }

        $data = unserialize($saveCalculators['data']);
        $data['saved_sp_list_id'] = $request->saved_sp_list_id;
        $data['saved_list_id'] = $request->saved_list_id;
        $data['before_after'] = $request->mergeposition;
        $data['is_cover'] = $request->is_cover;
        $data['pdf_title_line1'] = $request->pdf_title_line1;
        $data['pdf_title_line2'] = $request->pdf_title_line2;
        $data['client_name'] = $request->client_name;
        $data['id'] = $id;
        
        if (Auth::check()){
            $user = Auth::user();
            $displayInfo = Displayinfo::where('user_id',$user->id)->first();
            $data['name'] = ($displayInfo->name_check && $displayInfo->name!='')?$displayInfo->name:'';
            $data['company_name'] = ($displayInfo->company_name_check && $displayInfo->company_name!='')?$displayInfo->company_name:'';
            $data['name_color'] = $displayInfo->name_color;
            $data['company_name_color'] = $displayInfo->company_name_color;
            $data['city_color'] = $displayInfo->city_color;
            $data['address_color_background'] = $displayInfo->address_color_background;
            $data['pdf_cover_image'] = $displayInfo->pdf_cover_image;
            $data['amfi_registered'] = $displayInfo->amfi_registered;
            $data['footer_branding_option'] = $displayInfo->footer_branding_option;
            $data['phone_no'] = ($displayInfo->phone_no_check && $displayInfo->phone_no!='')?$displayInfo->phone_no:'';
            $data['email'] = ($displayInfo->email_check && $displayInfo->email!='')?$displayInfo->email:'';
            $data['website'] = ($displayInfo->website_check && $displayInfo->website!='')?$displayInfo->website:'';
            $data['address'] = ($displayInfo->address_check && $displayInfo->address!='')?$displayInfo->address:'';

            if($data['address']){
                $data['address2'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
                $data['address'] = $data['address']." ".$data['address2'];
            }else{
                $data['address'] = ($displayInfo->address2_check && $displayInfo->address2!='')?$displayInfo->address2:'';
            }

            $data['company_logo'] = ($user['company_logo']!='')?public_path('uploads/logo/'.$user['company_logo']):public_path(env('PDF_COMPANY_LOGO'));
            $data['membership'] = Membership::where('user_id', $user->id)->where('expire_at','>=',date('Y-m-d'))->where('subscription_type','paid')->where('is_active',1)->count();
            if (isset($data['membership']) && $data['membership'] > 0){
                $data['watermark'] = 0;
            }else{
                $data['watermark'] = 1;
            }
        }

        $data['title'] = "Lumsum Investment Required for Target Future Value";

        $data['detail'] = DB::table("calculators")->where('url','premium-calculator/swp_periodic_withdrawal')->first();

        $oMerger = PDFMerger::init();

        if($data['is_cover'] == 1){
            $pdf = PDF::loadView('frontend.pdf_cover_page', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_cover.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_cover.pdf'), 'all');
        }

        if ($data['before_after']=='after'){
            $pdf = PDF::loadView('frontend.calculators.swp_periodic_withdrawal.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }

        if($data['saved_sp_list_id']){
            foreach ($data['saved_sp_list_id'] as $key => $value) {
                $softcopieslists = Savelistsoftcopy::where('savelist_id',$value)->orderBy('position','asc')->get();
    
                if (isset($softcopieslists) && count($softcopieslists)>0){
                    
                    $data1 = $data;
                    $data1['company'] = Auth::user();
                    $data1['name'] = $data['name'];
                    $data1['membership'] = $data['membership'];
                    $data1['displayInfo'] = $displayInfo;
                    $data1['getSoftCopyList'] = $softcopieslists;
                    $data1['type'] = 0;
                    $data1['pdf_title_line1'] = $data['pdf_title_line1'];
                    $data1['pdf_title_line2'] = $data['pdf_title_line2'];
                    $data1['client_name'] = $data['client_name'];
                    
                    // dd($data1);
                    $pdf = PDF::loadView('frontend.salespresenter.mysavelist_output_pdf', $data1);
                    $pdf->save(public_path('calculators/'.$user->id.'_salespresenter.pdf'));
                    $oMerger->addPDF(public_path('calculators/'.$user->id.'_salespresenter.pdf'), 'all');
                }
            }
        }

        if($data['saved_list_id']){
            $softcopieslists = Savelistsoftcopy::where('savelist_id',$data['saved_list_id'])->orderBy('position','asc')->get();

            if (isset($softcopieslists) && count($softcopieslists)>0){
                
                $data1 = $data;
                $data1['company'] = Auth::user();
                $data1['name'] = $data['name'];
                $data1['membership'] = $data['membership'];
                $data1['displayInfo'] = $displayInfo;
                $data1['getSoftCopyList'] = $softcopieslists;
                $data1['type'] = 0;
                $data1['pdf_title_line1'] = $data['pdf_title_line1'];
                $data1['pdf_title_line2'] = $data['pdf_title_line2'];
                $data1['client_name'] = $data['client_name'];
                
                $pdf = PDF::loadView('frontend.salespresenter.mysavelist_output_pdf', $data1);
                $pdf->save(public_path('calculators/'.$user->id.'_salespresenter.pdf'));
                $oMerger->addPDF(public_path('calculators/'.$user->id.'_salespresenter.pdf'), 'all');
            }
        }

        if ($data['before_after']=='before'){
            $pdf = PDF::loadView('frontend.calculators.swp_periodic_withdrawal.pdf', $data);
            $pdf->save(public_path('calculators/'.$user->id.'_calculator.pdf'));
            $oMerger->addPDF(public_path('calculators/'.$user->id.'_calculator.pdf'), 'all');
        }



        $oMerger->merge();
        $oMerger->setFileName($data['detail']->name.".pdf");
        return $oMerger->download();

    }
    
}

?>