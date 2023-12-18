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
        
        <main style="width: 806px;">
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">EMI vs SIP Planning @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
                <div class="roundBorderHolder">
                    @if($enter_loan_details == 1)
                        @php 
                            $totalInterest = (($amount * $interest) / 100) * $period;
                            $ins = ($interest/100)/12;
                            $monthlyEmi = ($amount * $ins)/(1-pow((1+$ins),(-($period * 12))));
                            
                            $totalMonths = $period * 12;
                            $totalInterest = ($monthlyEmi * $totalMonths)-$amount;
                            $totalReplayment = $totalInterest + $amount;
                            $monthlyReturnOnSip = 0;
                            $monthlySipRequired = 0;
                            
                            if(!empty($expected_interest)){
                            $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                            $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                            }                        
                        @endphp
                        <table style="margin-bottom:15px !important;">
                            <tbody>
                                <tr>
                                    <td style="text-align: left;Width:50%;">
                                        <strong>Loan Amount</strong>
                                    </td>
                                    <td style="text-align: right; Width:50%;">
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($amount)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;Width:50%;">
                                        <strong>Rate of Interest</strong>
                                    </td>
                                    <td style="text-align: right;Width:50%;">
                                        {{$interest}}  %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;Width:50%;">
                                        <strong>Loan Tenure</strong>
                                    </td>
                                    <td style="text-align: right;Width:50%;">
                                        {{$period}}  Years
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        </br></br>
                        <h3 style="color: #131f55; font-size: 18px; margin:0; padding:15px 0; text-align:center;width: 100%;">Monthly EMI</h3>
                        <div style="width: 100%;">
                            <h5 style="
                                    margin: 0 auto 15px auto;
                                    padding: 12px 10px;
                                    max-width: 237px;
                                    border: 1px solid #ccc;
                                    font-size: 18px;
                                    text-align: center;
                                    background: #a9f3ff;
                                ">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthlyEmi)}}
                            </h5>
                        </div>

                        <table style="margin-bottom:15px !important;">
                            <tbody>
                            <tr>
                                <td style="text-align: left;Width:50%;">Principal Repayment</td>
                                <td style="text-align: right;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($totalReplayment - $totalInterest)}}</td>
                            </tr>
                            
                            <tr>
                                <td style="text-align: left;Width:50%;">Interest Repayment</td>
                                <td style="text-align: right;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($totalInterest)}}</td>
                            </tr>
                            
                            <tr>
                                <td style="text-align: left;Width:50%;">Total Repayment</td>
                                <td style="text-align: right;Width:50%;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($totalReplayment)}}</td>
                            </tr>
                            </tbody>
                        </table>

                        <h5 style="color: #131f55; font-size: 18px; margin:0; padding:15px 0; text-align:center;width: 100%;">
                            Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %
                        </h5>
                        <div style="width: 100%;">
                            <h5 style="margin: 0 auto 15px auto; padding: 12px 10px; max-width: 237px; border: 1px solid #ccc; font-size: 18px; 
                            text-align: center;background: #a9f3ff;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  
                                @if($monthlySipRequired)
                                    {{custome_money_format(round($monthlySipRequired))}}
                                @else  
                                    N/A
                                @endif
                            </h5>
                        </div>

                    @else  
                        @php 
                            $monthlyEmi = $amount;
            
                            $totalMonths = $period * 12;
                            $totalReplayment = $monthlyEmi * $totalMonths;
                            
                            $monthlyReturnOnSip = 0;
                            $monthlySipRequired = 0;
                            if(!empty($expected_interest)){
                                $monthlyReturnOnSip = pow((1+($expected_interest/100)),(1/12))-1;
                                $monthlySipRequired = ($totalReplayment * $monthlyReturnOnSip)/((1+$monthlyReturnOnSip)*(pow((1+$monthlyReturnOnSip),($totalMonths))-1));
                            }
                        @endphp

                        <table style="margin-bottom:15px !important;">
                            <tbody>
                                <tr>
                                    <td style="text-align: left;Width:50%;">
                                        <strong>Monthly EMI</strong>
                                    </td>
                                    <td style="text-align: left;Width:50%;">
                                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($monthlyEmi)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: left;Width:50%;">
                                        <strong>Loan Tenure</strong>
                                    </td>
                                    <td style="text-align: left;Width:50%;">
                                        {{$period}}  Years
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Total Repayment</h1>
                        <div style="">
                            <h5 style="
                                    margin: 0 auto 15px auto;
                                    padding: 12px 10px;
                                    max-width: 237px;
                                    border: 1px solid #ccc;
                                    font-size: 18px;
                                    text-align: center;
                                    background-color: #a9f3ff;
                                ">
                                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>  {{custome_money_format($totalReplayment)}}
                            </h5>
                            
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %
                            </h1>
                        </div>


                        
                        <div> 
                            <h5 style="margin: 0 auto 15px auto; padding: 12px 10px; max-width: 237px; border: 1px solid #ccc; font-size: 18px; 
                            text-align: center; background-color: #a9f3ff;">
                                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span>
                                @if($monthlySipRequired)
                                    {{custome_money_format(round($monthlySipRequired))}}
                                @else  
                                    N/A
                                @endif
                            </h5>
                        </div>

                    @endif
                </div>
            </div>
            <h4 style="width: 100%; font-size: 19px; text-align:center;">If you do an SIP for <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ custome_money_format($monthlySipRequired) }} ,ie., {{ number_format((float)($monthlySipRequired/$monthlyEmi) * 100, 2, '.', '') }} % of the EMI amount, you will recover the full amount of EMI paid by you.</h4>

            @if($is_note)
                <div style="padding: 0 0%;">
                    <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Comment</h1>
                    <div class="roundBorderHolder">
                        <table>
                            <tbody>
                                <tr>
                                    <td>{{$note}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            

            @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
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
        </main>
    </body>
</html>