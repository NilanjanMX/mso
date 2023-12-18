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
            margin: 0px;
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
            text-align: center;
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
            margin-top: 100px
        }

        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 120px;
        }

        footer {
            position: fixed;
            bottom: -70px;
            left: -53px; 
            right: -53px;
            height: 144px;
        }
        footer p {
            font-size: 17px;
            color: #003063;
        }
        .watermark{
            font-size: 60px;
            color: rgba(0,0,0,0.10);
            position: absolute;
            top: -400px;
            left: 26%;
            z-index: 1;
            transform: rotate(-25deg);
            font-weight: 700;
        }
        
        .bluebar {
            background-color: #131f55;color:#fff !important;font-size:20px;padding:10px;text-align:center;
        }



        .styleApril table th, .styleApril .table-bordered th {
            font-weight: bold;
            background: #fff;
            font-size: 16px;
        }
        .styleApril .roundBorderHolder {
            border: 1px solid #458ff6;
            overflow: hidden;
            border-radius: 12px;
        }
        .styleApril .withBluebar {
            border-top: none;
            border-radius: 0 0 12px 12px;
        }
        .styleApril .bluebar {
            border-radius: 12px 12px 0 0;
            font-weight: normal;
            font-size: 22px;
            border: 1px solid #458ff6;
            border-bottom: none;
        }
        .styleApril .roundBorderHolder .table {
            width: 101%;
            background-color: transparent;
            margin-left: -1px;
            margin-top: -1px;
            margin-bottom: -1px;
        }
        .styleApril table th,
        .styleApril table td {
            border: none;
            border-top: 1px solid #458ff6;
            border-right: 1px solid #458ff6;
            padding: 6px 5px;
            font-size: 15px;
        }
        .styleApril table table tr:first-child td {
            border-top: 0;
        }
        .styleApril table table tr td:first-child {
            border-left:0;
        }
        .styleApril table table tr td:last-child {
            border-right:0;
        }
        .styleApril table table tr:last-child td {
            border-bottom:0;
        }
        .styleApril .graphView {
            border: 2px solid #003063;
            border-radius:12px;
            overflow: hidden;
            padding: 5px;
        }
        .styleApril .graphHeading {
            color: #204a77;
            font-size: 25px;
            font-weight: normal;
            text-align: center;
            
        }
        .bluelinefooter {
            background:#003063;
        }
        .orangelinefooter {
            background:#dc5440;
            height: 30px;
            width: 50%;
        }
        .nobordernopadding {
            padding: 0px !important;
            border: 0px !important;
        }
        .tableh1heading {
            background-color: #131f45;
            color:#fff !important;
            font-size:20px;
            padding:10px;
            text-align:center;
            margin: 0;
            vertical-align: top;
        }
        .margintop30px {
            margin-top: 30px;
        }
    </style>
</head>
<body class="styleApril">
    <header>
        @include('frontend.calculators.common.header')
    </header>

    <footer>
        <br>
        @include('frontend.calculators.common.footer')
    </footer>
    <main>
  
    <div style="padding: 0 0%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Lumpsum Investment Ready Reckoner @if(isset($clientname)) <br> For {{$clientname?$clientname:''}} @endif</h1>
        <div class="roundBorderHolder">
            <table>
                <tbody><tr>
                    <td style="width: 50%;">
                        <strong>Lumpsum Investment</strong>
                    </td>
                    <td style="width: 50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($investment)}}
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h1 style="color: #000;font-size:16px;margin-bottom:5px !important;margin-top: 30px !important;text-align:center;">Future Value of Lumpsum Investment</h1>
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
    <div class="roundBorderHolder">
        <table class="table table-bordered text-center">
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
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest1/100)), $period5)))}} </strong>
            </td>
            @php } @endphp
        </tr>
        @php if($interest2!='0'){ @endphp
        <tr>
            <td>
                <strong>{{$interest2?number_format((float)$interest2, 2, '.', ''):0}} %</strong>
            </td>
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest2/100)), $period5)))}} </strong>
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
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period1)))}} </strong>
            </td>
            @php if($period2!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period2)))}} </strong>
            </td>
            @php } @endphp
            @php if($period3!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period3)))}} </strong>
            </td>
            @php } @endphp
            @php if($period4!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period4)))}} </strong>
            </td>
            @php } @endphp
            @php if($period5!='0'){ @endphp
            <td>
                <strong><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{price_in_lakh(($investment*pow((1+($interest3/100)), $period5)))}} </strong>
            </td>
            @php } @endphp
        </tr>
        @php } @endphp
        <tr>
            <td colspan="6" style="text-align: right"><strong>(<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> in Lacs)</strong></td>
        </tr>

        </tbody>
    </table>
    </div>
    @php
                $note_data1 = \App\Models\Calculator_note::where('category','summery')->where('calculator','Lumsum_Investment_Required_for_Target_Future_Value')->first();
                if(!empty($note_data1)){
            @endphp
                {!!$note_data1->description!!}
            @php } @endphp
            Report Date : {{date('d/m/Y')}}
            
    @include('frontend.calculators.suggested.pdf')
</main>
</body>
</html>
