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
        //Number of Months R9*12
        $number_of_months = $term_insurance_period*12;
         //Rate of Return (1+R11%)^(1/12)-1
        $rate_of_return2 = (1+$rate_of_return/100)**(1/12)-1;
         //Monthly SIP Amount (R7*AV28)/((1+AV28)*((1+AV28)^(AV27)-1))
        $monthly_sip_amount = ($goal_amount*$rate_of_return2)/((1+$rate_of_return2)*((1+$rate_of_return2)**($number_of_months)-1));

    @endphp

    @include('frontend.calculators.common.header')
        
        <main style="width: 806px;">
            <div style="padding: 0 15%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Term Insurance + SIP (Goal Based) @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
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
                            <strong>Term Insurance / Goal Amount</strong>
                        </td>
                        <td style="text-align: left;Width:50%;">
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($goal_amount)}}
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
                    </tbody>
                </table>
        
            </div>
        
            <div style="padding: 0 25%;">
                <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;"> Monthly SIP Required <br>
                    To Achieve Goal @ {{number_format($rate_of_return, 2, '.', '')}} %
                </h1>
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td>
                            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <p>
                If you take Term Cover of  <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span></b> {{custome_money_format($goal_amount)}} and do Monthly SIP of <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($monthly_sip_amount)}}</b> you may be
                assured of minimum payout of<br> <b><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($goal_amount)}}</b> either on survival at age <b>{{$term_insurance_period+$current_age}}</b> or unfortunate event of death, subject to fund performance at Assumed rate of return mentioned herewith.
            </p>
            <p style="text-align: left">
               @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Term_Insurance_+_SIP_(Goal_Based)')->first();
                if(!empty($note_data1)){
                @endphp
                {!!$note_data1->description!!}
                @php } @endphp
            </p>
            
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
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Term_Insurance_+_SIP_(Goal_Based)')->first();
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