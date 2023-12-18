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
                <h1 class="pdfTitie">Recover EMI Through SIP @if(isset($clientname) && $clientname) <br> For {{$clientname?$clientname:''}} @endif</h1>
                
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
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="Width:50%;">
                                            <strong>Loan Amount</strong>
                                        </td>
                                        <td style="text-align: right; Width:50%;">
                                            <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($amount)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="Width:50%;">
                                            <strong>Rate of Interest</strong>
                                        </td>
                                        <td style="text-align: right;Width:50%;">
                                            {{$interest}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="Width:50%;">
                                            <strong>Loan Tenure</strong>
                                        </td>
                                        <td style="text-align: right;Width:50%;">
                                            {{$period}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        
                        <h1 class="pdfTitie">Monthly EMI</h1>
                        <div style="width: 100%;">
                            <h5 class="pdfBlueCell" style="margin-bottom:20px !important;">
                                    <span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($monthlyEmi)}}
                            </h5>
                        </div>
                        
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                <tr>
                                    <td style="Width:50%;">Principal Repayment</td>
                                    <td style="text-align: right;Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($totalReplayment - $totalInterest)}}</td>
                                </tr>
                                
                                <tr>
                                    <td style="Width:50%;">Interest Repayment</td>
                                    <td style="text-align: right;Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($totalInterest)}}</td>
                                </tr>
                                
                                <tr>
                                    <td style="Width:50%;">Total Repayment</td>
                                    <td style="text-align: right;Width:50%;"><span class="pdfRupeeIcon">&#8377;</span> {{custome_money_format($totalReplayment)}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <h1 class="pdfTitie">
                            Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %
                        </h1>
                        <div style="width: 100%;">
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>  
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
                        
                        <div class="roundBorderHolder">
                            <table>
                                <tbody>
                                    <tr>
                                        <td style="Width:50%;">
                                            <strong>Monthly EMI</strong>
                                        </td>
                                        <td style="Width:50%;">
                                            <span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($monthlyEmi)}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="Width:50%;">
                                            <strong>Loan Tenure</strong>
                                        </td>
                                        <td style="Width:50%;">
                                            {{$period}}  Years
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <h1 class="pdfTitie">Total Repayment</h1>
                        <div style="">
                            <h5 class="pdfBlueCell">
                                    <span class="pdfRupeeIcon">&#8377;</span>  {{custome_money_format($totalReplayment)}}
                            </h5>
                            
                            <h1 class="pdfTitie">
                                Monthly SIP Required @ {{number_format((float)$expected_interest, 2, '.', '')}} %
                            </h1>
                        </div>


                        
                        <div> 
                            <h5 class="pdfBlueCell">
                                <span class="pdfRupeeIcon">&#8377;</span>
                                @if($monthlySipRequired)
                                    {{custome_money_format(round($monthlySipRequired))}}
                                @else  
                                    N/A
                                @endif
                            </h5>
                        </div>

                    @endif
            </div>
            <h5 style="width: 100%; text-align:center;margin-top:0px;">If you do an SIP for <span class="pdfRupeeIcon">&#8377;</span> {{ custome_money_format($monthlySipRequired) }} ,ie., {{ number_format((float)($monthlySipRequired/$monthlyEmi) * 100, 2, '.', '') }} % of the EMI amount, you will recover the full amount of EMI paid by you.</h5>

            {{-- comment or note section here --}}
            @include('frontend.calculators.common.comment_pdf')
            

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