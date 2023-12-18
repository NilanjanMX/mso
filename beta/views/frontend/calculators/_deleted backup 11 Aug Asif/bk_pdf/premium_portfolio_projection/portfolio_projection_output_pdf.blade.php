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
        
        header img{
            height:110px;
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
            padding: 5px 20px;
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
            <div style="padding: 0 15%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center; background-color: #a9f3ff; padding: 10px 0;">Portfolio Projection Report @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>

                @if($enter_loan_details == 1)
                    @php 
                            $clienPortValue = $amount;
                            $lumpsumInvest = $lumpsum;
                            $currentMonthlySip = $sip;
                            $expectedRateOfReturn = $rate;
                            $tenure = $period;
                            $totalMonths = $tenure * 12;
                            $returnAnual = $expectedRateOfReturn / 100;
                            $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                            $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                            $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                            $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                            
                                                   
                        @endphp
                    <table style="margin-bottom:15px !important;">
                        <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($clienPortValue)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Lumpsum Investment Every year</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($currentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="text-align: right;">
                                        {{$expectedRateOfReturn}}  %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Period</strong>
                                    </td>
                                    <td style="text-align: right;">
                                        {{$tenure}}  Years
                                    </td>
                                </tr>
                            </tbody>
                    </table>

                    </br></br>

                    @if(isset($note) && $note!='')
                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Comments</h1>
                    <div class="roundBorderHolder">
                    <table class="table table-bordered text-center">
                    <tbody><tr>
                        <td style="width: 50%;">
                            <strong>{{$note}}</strong>
                        </td>
                    </tr>
                    </tbody></table>
                    </div>
                    @endif
                </br></br>
                    <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Expected Portfolio Value</h1>
                    <table style="margin-bottom:15px !important;">
                        <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($currentPort)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($anualLumpsum)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>SIP</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sipFv)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; background-color: #a9f3ff;">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td style="text-align: right; background-color: #a9f3ff;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                    </td>
                                </tr>
                                
                            </tbody>
                    </table>
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
                        $returnMonthly = pow((1+$returnAnual),(1/12))-1;
                        $currentPort = $clienPortValue * pow((1+$returnAnual),$tenure);
                        $anualLumpsum = (1+$returnAnual) * $lumpsumInvest * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                        $sipFv = (1+$returnMonthly) * $currentMonthlySip * ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                        $IncAnualLumpsum = (1+$returnAnual) * ($lumpsumInvest+$inclumpsumInvest) * ((pow((1+$returnAnual),$tenure) - 1)/$returnAnual);
                        $incSipFv = (1+$returnMonthly) * ($currentMonthlySip + $icurrentMonthlySip)* ((pow((1+$returnMonthly),$totalMonths) - 1)/$returnMonthly);
                    @endphp
                    
                    <table style="margin-bottom:15px !important;">
                        <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio Value</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($clienPortValue)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Lumpsum Investment Every Year</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($lumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Increment in Annual Lumpsum</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($inclumpsumInvest)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Monthly SIP</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($currentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Addition in SIP(New)</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($icurrentMonthlySip)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Assumed Rate of Return</strong>
                                    </td>
                                    <td style="text-align: right;">
                                        {{$expectedRateOfReturn}}  %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Period</strong>
                                    </td>
                                    <td style="text-align: right;">
                                        {{$tenure}}  Years
                                    </td>
                                </tr>
                            </tbody>
                        </table> </br></br>
                    <h1 style="color: #000;font-size:16px;margin-bottom:12px !important;text-align:center;">Expected Portfolio Value</h1>
                    <table style="margin-bottom:10px !important;">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Current Scenario</th>
                                <th>Incremental Scenario</th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Current Portfolio</strong>
                                    </td>
                                    <td style="text-align: right;">
                                         <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($currentPort)}}
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($currentPort)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>Annual Lumpsum</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($anualLumpsum)}}
                                    </td>
                                    <td style="text-align: right;">
                                         <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($IncAnualLumpsum)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;">
                                        <strong>SIP</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($sipFv)}}
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($incSipFv)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left; ">
                                        <strong>TOTAL</strong>
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($sipFv + $currentPort + $anualLumpsum)}}
                                    </td>
                                    <td style="text-align: right;">
                                          <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($incSipFv + $currentPort + $IncAnualLumpsum)}}
                                    </td>
                                </tr>
                                
                            </tbody>
                    </table>
                @endif
                <br>
                <br>
                <div>
                    @php
                        $note_data2 = \App\Models\Calculator_note::where('category','summery')->where('calculator','portfolio_projection')->first();
                        if(!empty($note_data2)){
                        @endphp
                        {!!$note_data2->description!!}
                    @php } @endphp

                    <p>Report Date : {{date('d/m/Y')}}</p>
                </div>
            </div>
            @include('frontend.calculators.common.footer')
            
            @if(isset($report) && $report=='detailed')
                
                @if($enter_loan_details == 1)
                    <div class="page-break"></div>
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
                    <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Annual Investment & Expected Fund Value</h1>
                    <table>
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
                    @for($i=1;$i<=$period;$i++)
                        @php
                            if($i == 1){
                                $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                            }else{
                                $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                            }
                            $annual_investment_total = $annual_investment_total + $annual_investment;

                            $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);

                            $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                            $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;
                            $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                        @endphp
                        <tr>
                                <td>{{$i}} </td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($annual_investment)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($annual_investment_total)}}</td>
                                <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
                        </tr>
                        @if($i%25==0 && $period>25 && $period>$i)
                            </tbody>
                            </table>
                            @include('frontend.calculators.common.footer')
                            <div class="page-break"></div>
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
                            <table>
                                <tbody>
                                <tr>
                                    <th>Year</th>
                                    <th>Annual Investment</th>
                                    <th>Cumulative Investment</th>
                                    <th>Expected Fund Value</th>
                                </tr>
                        @endif
                    @endfor

                        </tbody>
                    </table>
                @else
                        <div class="page-break"></div>
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
                        <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Annual Investment & Expected Fund Value</h1>
                        <table>
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
                        @for($i=1;$i<=$period;$i++)
                            @php
                                if($i == 1){
                                    $annual_investment = $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12;
                                    $ic_annual_investment =  $clienPortValue + $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                }else{
                                    $annual_investment = $lumpsumInvest + $currentMonthlySip * 12;
                                    $ic_annual_investment = $lumpsumInvest + $currentMonthlySip * 12 + $inclumpsumInvest + $icurrentMonthlySip * 12;
                                }
                                $annual_investment_total = $annual_investment_total + $annual_investment;
                                $ic_annual_investment_total = $ic_annual_investment_total + $ic_annual_investment;

                                $rateofreturn = pow((1+$expectedRateOfReturn/100),(1/12))-1;

                                $eoy_value  = $clienPortValue * pow((1+$expectedRateOfReturn/100),$i);

                                $eoy_value1 = (1+$expectedRateOfReturn/100)*$lumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));
                                
                                $eoy_value2 = (1+$rateofreturn)*$currentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);

                                $eoy_value3 = (1+$expectedRateOfReturn/100)*$inclumpsumInvest*((pow((1+$expectedRateOfReturn/100),$i)-1)/($expectedRateOfReturn/100));

                                $eoy_value4 = (1+$rateofreturn)*$icurrentMonthlySip*((pow((1+$rateofreturn),($i*12))-1)/$rateofreturn);
                            @endphp
                            <tr>
                                    <td style="padding-left:2px;padding-right:2px;">{{$i}} </td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($annual_investment)}}</td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($annual_investment_total)}}</td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($eoy_value + $eoy_value1 +$eoy_value2)}}</td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ic_annual_investment)}}</td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($ic_annual_investment_total)}}</td>
                                    <td style="padding-left:2px;padding-right:2px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>{{custome_money_format($eoy_value + $eoy_value1 + $eoy_value2 + $eoy_value3 +$eoy_value4)}}</td>
                            </tr>
                            @if($i%25==0 && $period>25 && $period>$i)
                                </tbody>
                                </table>
                                @include('frontend.calculators.common.footer')
                                <div class="page-break"></div>
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
                                <table>
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
                            @endif
                        @endfor

                        </tbody>
                    </table>
                @endif

                @php
                $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','portfolio_projection')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
                @php } @endphp


                <p>Report Date : {{date('d/m/Y')}}</p>
        
                @include('frontend.calculators.common.footer')
            @endif
                
            @include('frontend.calculators.suggested.pdf')
            
        </main>

        
    </body>
</html>