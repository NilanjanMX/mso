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
        //Balance Left For Monthly SIP  R7-R10
        $balance_left_for_monthly_sip = $annual_investment-$term_insurance_annual_premium;
        //Monthly SIP Amount AU28/12
        $monthly_sip_amount = $balance_left_for_monthly_sip/12;
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
        //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
        //Total Fund Value (1+AU31)*AU29*(((1+AU31)^(AU30)-1)/AU31)
        $total_fund_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($number_of_months)-1)/$rate_of_return2);
        //echo $total_fund_value; die();
    
    @endphp
    @include('frontend.calculators.common.header')
        
        <main style="width: 806px;">
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Term Insurance + SIP @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
                <table>
                    <tbody>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Current Age</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            {{$current_age?$current_age:0}} Years
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Annual Outlay</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Term Insurance Sum Assured</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($term_insurance_sum_assured)}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Term Insurance Period</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            {{$term_insurance_period?$term_insurance_period:0}} Years
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Term Insurance Annual Premium</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($term_insurance_annual_premium)}}
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left;Width:50%;">
                            <strong>Balance Left For Monthly SIP</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_left_for_monthly_sip)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
        
            </div>
        
            <div style="padding: 0 0%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Monthly SIP Amount</h1>
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
                <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">
                    Expected Fund Value <br>
                    @ {{$rate_of_return}} % At Age {{$term_insurance_period+$current_age}}
                </h1>
                <table class="table table-bordered text-center" >
                    <tbody>
                    <tr>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_fund_value)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
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
            $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Term_Insurance_+_SIP')->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
            @php } @endphp
            
            @if(isset($report) && $report=='detailed')
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
                <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">
                    Yearwise Projected Value
                </h1>
                <table>
                    <tbody>
                    <tr>
                        <th>Age</th>
                        <th>Annual Outlay</th>
                        <th>Life Cover</th>
                        <th>Year End Value <br>@ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                        <!-- <th>Risk Cover + Fund Value<br>(In case of Death)</th> -->
                        <th>Payout in case of Unfortunate Event</th>
                    </tr>
                    @for($i=1;$i<=$term_insurance_period;$i++)
                        @php
                            //Year End Value (1+AV66)*AT66*(((1+AV66)^(AU66*12)-1)/AV66)
                            $year_end_value = (1+$rate_of_return2)*$monthly_sip_amount*(((1+$rate_of_return2)**($i*12)-1)/$rate_of_return2);
                            //Risk Cover N66+V66
                            $risk_cover_fund_value = $term_insurance_sum_assured+$year_end_value;
                            $current_age++;
                        @endphp
                        <tr>
                            <td>{{$current_age}}</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($annual_investment)}}</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($term_insurance_sum_assured)}}</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($year_end_value)}}</td>
                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($risk_cover_fund_value)}}</td>
                        </tr>
                        @if($i%25==0 && $term_insurance_period>25  && $term_insurance_period>$i)
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
                                        <th>Life Cover</th>
                                        <th>Year End Value <br>@ {{$rate_of_return?number_format($rate_of_return, 2, '.', ''):0}} %</th>
                                        <th>Risk Cover + Fund Value<br>(In case of Death)</th>
                                    </tr>
                                    @endif
                    @endfor

                    </tbody>
                </table>
                @php
                $note_data2 = \App\Models\Calculator_note::where('category','cashflow')->where('calculator','Term_Insurance_+_SIP')->first();
                if(!empty($note_data2)){
                @endphp
                {!!$note_data2->description!!}
                @php } @endphp
                    
                <p>Report Date : {{date('d/m/Y')}}</p>
            @endif
        </main>
        @include('frontend.calculators.common.watermark')
        @if($footer_branding_option == "all_pages")
            @include('frontend.calculators.common.footer')
        @endif
            
        @include('frontend.calculators.suggested.pdf')
        </main>
    </body>
</html>