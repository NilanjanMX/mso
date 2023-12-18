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

@php
    $child_name = $child_name;
            $age = $current_age;
            $fund_requirement_purpose = $fund_requirement_purpose;
            $age1 = isset($fund_required_age) ? $fund_required_age : 0;
            $amount1 = isset($fund_required_amount) ? $fund_required_amount : 0;

            if(empty($investment_amount))
            { 
                $investment_amount =  0;
            }else{
                $investment_amount =  $investment_amount;
            }
            
            if(empty($return_rate))
            { 
                $return_rate =  0;
            }else{
                $return_rate =  $return_rate;
            }
            $inflation_rate = $inflation_rate;
            //$return_rate = $return_rate;
            $return_rate_1 = $return_rate_1;
            $monthly_return_rate_1 = (1+$return_rate_1/100)**(1/12)-1;
            $period = isset($period) ? $period : 0;

            //Age 1
            $num_of_years1 = $age1 - $age;
            $total_number_of_months1 = $num_of_years1 * 12;

            $fv_fund_required1 = $amount1*(1+$inflation_rate/100)**$num_of_years1;
            $fv_current_investment = $investment_amount*(1+$return_rate/100)**$num_of_years1;

            $balance_required = $fv_fund_required1 - $fv_current_investment;

            $lumpsum_investment_required_1 = $balance_required / ((1+($return_rate_1/100))**($num_of_years1));


            //5 years
            $balance_after_5_years_opt1 = ( $num_of_years1 < 5 ) ? "NA" : $balance_required / ((1+($return_rate_1/100))**( $num_of_years1 - 5 ));

            $required_sip_for_5_years_opt1 = ($balance_after_5_years_opt1 == "NA") ? "NA" : ($balance_after_5_years_opt1*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**60) - 1));


            //10 years
            $balance_after_10_years_opt1 = ( $num_of_years1 < 10 ) ? "NA" : $balance_required / ((1+($return_rate_1/100))**( $num_of_years1 -10 ));

            $required_sip_for_10_years_opt1 = ($balance_after_10_years_opt1 == "NA") ? "NA" : ($balance_after_10_years_opt1*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**120) - 1));


            //Till end
            $required_sip_till_end_opt1 = ($balance_required*$monthly_return_rate_1)/((1+$monthly_return_rate_1)*(((1+$monthly_return_rate_1)**($total_number_of_months1))-1));


        if (isset($return_rate_2)){
                $return_rate_2 = $return_rate_2;
                $monthly_return_rate_2 = (1+$return_rate_2/100)**(1/12)-1;

                $lumpsum_investment_required_2 = $balance_required / ((1+($return_rate_2/100))**($num_of_years1));
                $balance_after_5_years_opt2 = ( $num_of_years1 < 5 ) ? "NA" : $balance_required / ((1+($return_rate_2/100))**( $num_of_years1 - 5));
                $required_sip_for_5_years_opt2 = ($balance_after_5_years_opt2 == "NA") ? "NA" : ($balance_after_5_years_opt2*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**60) - 1));
                $balance_after_10_years_opt2 = ( $num_of_years1 < 10 ) ? "NA" : $balance_required / ((1+($return_rate_2/100))**( $num_of_years1 - 10 ));
                $required_sip_for_10_years_opt2 = ($balance_after_10_years_opt2 == "NA") ? "NA" : ($balance_after_10_years_opt2*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**120) - 1));
                $required_sip_till_end_opt2 = ($balance_required*$monthly_return_rate_2)/((1+$monthly_return_rate_2)*(((1+$monthly_return_rate_2)**($total_number_of_months1))-1));
            }
@endphp

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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Child {{$fund_requirement_purpose}} @if(isset($clientname)) Proposal <br> For {{$clientname?$clientname:''}} @else Planning @endif</h1>
        <table>
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Child Name</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$child_name}}
                </td>
            </tr>
            </tbody>
        </table>

        <table style="margin-top: 20px">
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Child Age</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$current_age?$current_age:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Fund Required at Age</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$fund_required_age?$fund_required_age:0}} Years
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Fund Required</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($fund_required_amount)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Current Investment</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <?php
                                if($investment_amount=='0')
                                {
                                    echo "Nil";
                                }else{ ?>
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> 
                                 {{custome_money_format($investment_amount)}}
                                <?php } ?>
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Rate of Return (CI)</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$return_rate?number_format($return_rate, 2, '.', ''):0}} %
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Expected Inflation Rate</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$inflation_rate?number_format($inflation_rate, 2, '.', ''):0}} %
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Return @if(isset($return_rate_2)) (Scenario 1) @endif</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %
                </td>
            </tr>
            @if(isset($return_rate_2))
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Assumed Return (Scenario 2)</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    {{$return_rate_1?number_format($return_rate_2, 2, '.', ''):0}} %
                </td>
            </tr>
            @endif
            </tbody>
        </table>

        <table style="margin-top: 20px">
            <tbody>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Inflated Cost of Funds Required</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>{{custome_money_format($fv_fund_required1)}}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Expected FV of Current Investment</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style='font-family: DejaVu Sans; sans-serif;'>&#8377;</span>{{ ($investment_amount > 0) ? custome_money_format($fv_current_investment) : "NA"  }}
                </td>
            </tr>
            <tr>
                <td style="text-align: left;Width:50%;">
                    <strong>Balance Fund Required</strong>
                </td>
                <td style="text-align: left;Width:50%;">
                    <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_required)}}
                </td>
            </tr>

            </tbody>
        </table>

    </div>

    @if(!isset($return_rate_2))
        <div style="padding: 0 20%;">
    @endif
            @if($fv_current_investment>$fv_fund_required1)
                <table class="table table-bordered text-center">
                    <tbody>
                    <tr>
                        <td colspan="2"><h3 style="margin-bottom: 0;"><strong>You don't need any further investment for the above Goal!</strong></h3></td>
                    </tr>
                    </tbody>
                </table>
            @else
         <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Available Investment Options:</h1>
            <table class="table table-bordered text-center">
                <tbody>
            @if(isset($return_rate_2))
                <tr>
                    <th>Investment Option</th>
                    <th>
                        Option 1 @ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %
                    </th>
                    <th>
                        Option 2 @ {{$return_rate_2?number_format($return_rate_2, 2, '.', ''):0}} %
                    </th>
                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP Till Age {{$fund_required_age}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($required_sip_till_end_opt1=='NA')?$required_sip_till_end_opt1:custome_money_format($required_sip_till_end_opt1)}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($required_sip_till_end_opt2=='NA')?$required_sip_till_end_opt2:custome_money_format($required_sip_till_end_opt2)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP For 5 Years</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_5_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt1)}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_5_years_opt2 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt2)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP For 10 Years</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_10_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt1)}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_10_years_opt2 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt2)}}</td>
                </tr>
                <tr>
                    <td style="text-align: left">Lumpsum Investment</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($lumpsum_investment_required_1=='NA')?$lumpsum_investment_required_1:custome_money_format($lumpsum_investment_required_1)}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($lumpsum_investment_required_2=='NA')?$lumpsum_investment_required_2:custome_money_format($lumpsum_investment_required_2)}}</td>
                </tr>
            @else
                <tr>
                    <th>Investment Option</th>
                    <th>
                        Amount Required <br>@ {{$return_rate_1?number_format($return_rate_1, 2, '.', ''):0}} %
                    </th>
                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP Till Age {{$fund_required_age}}</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($required_sip_till_end_opt1=='NA')?$required_sip_till_end_opt1:custome_money_format($required_sip_till_end_opt1)}}</td>

                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP For 5 Years</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_5_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_5_years_opt1)}}</td>

                </tr>
                <tr>
                    <td style="text-align: left">Monthly SIP For 10 Years</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{$required_sip_for_10_years_opt1 == "NA" ? "NA" : custome_money_format($required_sip_for_10_years_opt1)}}</td>

                </tr>
                <tr>
                    <td style="text-align: left">Lumpsum Investment</td>
                    <td style="text-align: left"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{($lumpsum_investment_required_1=='NA')?$lumpsum_investment_required_1:custome_money_format($lumpsum_investment_required_1)}}</td>

                </tr>
            @endif
                </tbody>
            </table>
            @endif
        @if(!isset($return_rate_2))
        </div>
    @endif


    @php
    $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Child_Education_/_Marriage_Planning')->first();
    if(!empty($note_data1)){
    @endphp
    {!!$note_data1->description!!}
    @php } @endphp
    
    @include('frontend.calculators.common.footer')
    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>