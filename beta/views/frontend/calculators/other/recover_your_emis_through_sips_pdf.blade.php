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
            padding: 6px 20px;
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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">EMI Vs. SIP Planning @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif </h1>
    <table>
        <tbody>
        <tr>
            <td style="width: 50%;">
                <strong>Loan Amount</strong>
            </td>
            <td style="width: 50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($loan_amount)}}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <strong>Rate of Interest</strong>
            </td>
            <td style="width: 50%;">
                {{$rate_of_interest?custome_money_format($rate_of_interest):0}} %
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <strong>Tenure</strong>
            </td>
            <td style="width: 50%;">
                {{$period?$period:0}} Years
            </td>
        </tr>

        </tbody></table>

                    @php
                    $tenure=$period*12;
                    $roi=($rate_of_interest/100)/12;
                    $memi=($roi*$loan_amount)/(1-pow(1+$roi,-$tenure));
                    $ip=($memi*$tenure)-$loan_amount;
                    $tr=$loan_amount+$ip;
                    @endphp
                    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;text-align:center;">Monthly EMI</h1>
                    <h1 style="color: #000; border:1px solid black;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 220px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($memi)}}</h1>


        <table>
        <tbody>
        <tr>
            <td style="width: 50%;">
                <strong>Principal Repayment</strong>
            </td>
            <td style="width: 50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($loan_amount)}}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <strong>Interest Payment</strong>
            </td>
            <td style="width: 50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($ip)}}
            </td>
        </tr>
        <tr>
            <td style="width: 50%;">
                <strong>Total Repayment</strong>
            </td>
            <td style="width: 50%;">
                <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($tr)}}
            </td>
        </tr>
        </tbody></table>
    </div>
   

    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;text-align:center;">Monthly SIP Required @ {{number_format($expected_return_sip,2)}}%</h1>
                    @php
                    $mrs=pow((1+$expected_return_sip/100),(1/12))-1;
                    $msr=($tr*$mrs)/((1+$mrs)*(pow(1+$mrs,$tenure)-1));
                    @endphp
                    <h1 style="color: #000;border:1px solid black;font-size:24px;margin:0 auto;margin-bottom:15px !important;text-align:center;background: #a9f3ff;width: 200px; text-align: center;padding-top: 10px;padding-bottom:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($msr)}}</h1>

                    <p style="color: #000;font-size:18px;margin-bottom:20px !important;text-align:center;">If you do an SIP for <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($msr)}} ,ie., {{number_format($msr/$memi*100,2)}}% of the EMI amount, you will recover the full amount of EMI paid by you.</p>

                    * It is assumed that Rate of Interest on Loan Amount is compounded monthly.<br>
                    * It is assumed that EMI payment starts at the end of 1st month from the date of loan.<br>
                    * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
                    * Returns are not guaranteed. The above is for illustration purpose only.<br>
    @include('frontend.calculators.common.footer')

    @include('frontend.calculators.suggested.pdf')

</main>
</body>
</html>
