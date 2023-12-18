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
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Lumpsum Investment Goal Planning Ready Reckoner @if(isset($clientname)) <br>For {{$clientname?$clientname:''}} @endif</h1>
<table>
    <tbody><tr>
        <td style="width: 50%;">
            <strong>Target Amount</strong>
        </td>
        <td style="width: 50%;">
            <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($investment)}}
        </td>
    </tr>
    </tbody>
</table>
    </div>
    <h1 style="background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;">Lumpsum Investment Required</h1>
    @php $cs=1; if($period2!='0'){ 
        $cs++;
    }
    if($period3!='0'){ 
        $cs++;
    }
    if($period4!='0'){ 
        $cs++;
    }
    if($period5!='0'){ 
        $cs++;
    }

    @endphp
    <table >
        <tbody>
        <tr>
            <th rowspan="2"><strong>Rate of Return</strong></th>
            <th colspan="{{$cs}}"><strong>Investment Period (Years)</strong></th>
        </tr>
        <tr>
            <th><strong>{{$period1?$period1:''}}</strong></th>
            @php if($period2!='0'){ @endphp
            <th><strong>{{$period2?$period2:''}}</strong></th>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <th><strong>{{$period3?$period3:''}}</strong></th>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <th><strong>{{$period4?$period4:''}}</strong></th>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <th><strong>{{$period5?$period5:''}}</strong></th>
            @php } @endphp
        </tr>
        <tr>
            <td>
                <strong>{{$interest1?number_format((float)$interest1, 2, '.', ''):0}} %</strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest1/100)), $period5)))}} </strong>
            </td>
            @php } @endphp
        </tr>
        @php if($interest2!='0'){ @endphp
        <tr>
            <td>
                <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest2/100)), $period5)))}} </strong>
            </td>
            @php } @endphp
        </tr>
        @php } @endphp
        @php if($interest3!='0'){ @endphp
        <tr>
            <td>
                <strong>{{$interest3?number_format((float)$interest3, 2, '.', ''):0}} %</strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment/pow((1+($interest3/100)), $period5)))}} </strong>
            </td>
            @php } @endphp
        </tr>
        @php } @endphp
        <tr>
            <td colspan="6" style="text-align: right"><strong>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> in Lacs)</strong></td>
        </tr>

        </tbody>
    </table>
    <p>
        * Mutual fund investments are subject to marker risks, read all scheme related documents carefully.<br>
        * Returns are not guaranteed. The above is for illustration purpose only.
    </p>
    @include('frontend.calculators.common.footer')
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>
