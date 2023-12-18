<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Result</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        @include('frontend.calculators.common.pdf_style')
    </head>
    <body class="styleApril">
    @php
        //Annual Investment V7-V10
        $annual_investment = $insurance_policy_annual_premium - $equivalent_insurance_term_policy_premium;
        //Monthly SIP Amount AU30/12
        $monthly_sip_amount = $annual_investment/12;
         //Number of Months R9*12
        $number_of_months = $policy_term*12;
          //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return = (1+$rate_of_return_investments/100)**(1/12)-1;
        //Total Fund Value (Investment) (1+AU33)*AU31*(((1+AU33)^(AU32)-1)/AU33)
        $total_fund_value_investment = (1+$rate_of_return)*$monthly_sip_amount*(((1+$rate_of_return)**($number_of_months)-1)/$rate_of_return);
        //Total Fund Value (Insurance) (1+V12%)*(V7)*(((1+V12%)^(V9)-1)/V12%)
        $total_fund_value_insurance = (1+$rate_of_return_insurance/100)*($insurance_policy_annual_premium)*(((1+$rate_of_return_insurance/100)**($policy_term)-1)/($rate_of_return_insurance/100));
        //echo $total_fund_value_insurance; die();

    @endphp     
    @include('frontend.calculators.common.header')
        
        <main style="width: 806px;">
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Insurance vs. Term Cover With Annual SIP @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h1>
                <div class="roundBorderHolder">

                    <h5 class="midheading">Insurance vs. Term Cover With Annual SIP Comparison @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @else  @endif</h5>
                    <div class="roundBorderHolder">

                        <h1 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align: center;">
                            Insurance
                        </h1>
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Annual Premium</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Sum Assured / Death Benefit</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($sum_assured)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Policy Term</strong>
                                </td>
                                <td>
                                    {{$policy_term?$policy_term:0}} Years
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Assumed Rate Of Return</strong>
                                </td>
                                <td>
                                    {{$rate_of_return_insurance?number_format($rate_of_return_insurance, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Expected Maturity Value</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($total_fund_value_insurance)}}
                                </td>
                            </tr>


                            </tbody>
                        </table>
                        <h1 style="color: #131f55;font-size:22px;margin-bottom:20px;text-align: center;">
                            Term Cover + Monthly SIP
                        </h1>
                        <table class="table table-bordered text-center">
                            <tbody>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Sum Assured / Death Benefit</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($sum_assured)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Term Policy Premium</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($equivalent_insurance_term_policy_premium)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Monthly SIP Amount</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($monthly_sip_amount)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Total Annual Outlay</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($insurance_policy_annual_premium)}}
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Time Period</strong>
                                </td>
                                <td>
                                    {{$policy_term?$policy_term:0}} Years
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Assumed Rate Of Return</strong>
                                </td>
                                <td>
                                    {{$rate_of_return_investments?number_format($rate_of_return_investments, 2, '.', ''):0}} %
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 50%">
                                    <strong>Expected Fund Value</strong>
                                </td>
                                <td>
                                    ₹ {{custome_money_format($total_fund_value_investment)}}
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    
                </div>
            </div>
            
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
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Insurance_vs_Term_Cover_With_Annual_SIP')->first();
            @endphp
            @if(!empty($note_data1))
                {!!$note_data1->description!!}
            @endif
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