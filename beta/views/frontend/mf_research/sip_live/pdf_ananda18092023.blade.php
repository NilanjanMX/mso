<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>STP HISTORICAL</title>
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
            padding: 4px 3px;
            font-weight: normal;
            color: #000;
            font-size: 12px;
        }

        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
        }

        .table-bordered th, .table-bordered td{
            padding: 4px 3px;
            font-size: 12px;
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
        
        .mfliketbl {
            border: 1px solid #b5b3b3 !important;
            border-bottom: 0;
        }
        .mfliketbl tbody tr td, .mfliketbl tbody tr th {
            vertical-align: middle;
            border-bottom: 1px solid #b5b3b3;
            font-size: 12px;
        }
        .mfliketbl tbody tr td + td, .mfliketbl tbody tr th + th {
            border-left: 1px solid #b5b3b3;
        }
        /*.mfliketbl tbody tr:nth-child(even) {*/
        /*    background-color: #f0f1f6;*/
        /*}*/
    </style>
</head>
<body>
<main style="width: 760px; margin-left: 20px;">
    @php
    $amf='AMFI-Registered Mutual Fund Distributor';
    @endphp

        

    @foreach($result as $key => $value)
        @if($key)
            <div class="page-break"></div>
        @endif
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">
                        
                    </td>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:110px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>
        <div style="padding: 0 5%;">
            <h1 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">STP Historical Performance Report</h1>
        </div>
        <div>
            <h4 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">STP Transferor Scheme : {{$value['from_scheme_details']->s_name}}</h4>
        </div>
        <table class="table text-center mfliketbl">
            <tbody>
                <tr>
                    <td style="width:50%;">
                        <strong>Investment Amount</strong>
                    </td>
                    <td style="width:50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_investment_amount'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Investment Date</strong>
                    </td>
                    <td>
                        {{$value['investment_date']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>{{$value['data_frequency']}} STP Amount</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['stp_amount'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>STP Period</strong>
                    </td>
                    <td>
                        {{$value['stp_start_date']}} - {{$value['stp_end_date1']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>No. of Instalments</strong>
                    </td>
                    <td>
                        {{count($value['returnDataFrom'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Total Amount Transferred</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(count($value['returnDataFrom']) * $value['cash_flow'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>No. of Units Remaining</strong>
                    </td>
                    <td>
                        {{number_format((float)($value['from_unit']), 2, '.', '')}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Market Value as on {{$value['stp_end_date']}}</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['from_market_value'])}}
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            <h4 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">STP Transferee Scheme : {{$value['to_scheme_details']->s_name}}</h4>
        </div>

        <table class="table text-center mfliketbl">
            <tbody>
                <tr>
                    <td style="width:50%;">
                        <strong>{{$value['data_frequency']}} STP Amount</strong>
                    </td>
                    <td style="width:50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['stp_amount'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>STP Period</strong>
                    </td>
                    <td>
                        {{$value['stp_start_date']}} - {{$value['stp_end_date1']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>No. of Instalments</strong>
                    </td>
                    <td>
                        {{count($value['returnDataTo'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Total Amount Invested</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format(count($value['returnDataTo']) * $value['cash_flow'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>No. of Units Remaining</strong>
                    </td>
                    <td>
                        {{number_format((float)($value['to_unit']), 2, '.', '')}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Market Value as on {{$value['stp_end_date']}}</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['to_market_value'])}}
                    </td>
                </tr>
            </tbody>
        </table>

        <div>
            <h4 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">STP Total Returns : </h4>
        </div>

        @php 
            $now = strtotime($value['investment_date1']);
            $your_date = strtotime($value['valuation_date1']);
            $datediff = $your_date - $now;
            $number_of_days = round($datediff / (60 * 60 * 24));

            $total_withdrawal = $value['from_market_value']+$value['to_market_value'];
            $abc = $total_withdrawal/$value['lumpsum_investment_amount'];
            $abd = 1/$number_of_days;
            $daily_return = pow($abc,$abd)-1;
            $cagr_returns = pow((1+$daily_return),(365))-1;
        @endphp

        <table class="table text-center mfliketbl">
            <tbody>
                <tr>
                    <td style="width:50%;">
                        <strong>Total Investment Amount</strong>
                    </td>
                    <td style="width:50%;">
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_investment_amount'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Market Value as on {{$value['stp_end_date']}}</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_withdrawal)}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Total Profit</strong>
                    </td>
                    <td>
                        <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($total_withdrawal -$value['lumpsum_investment_amount'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>CAGR Returns</strong>
                    </td>
                    <td>
                        {{number_format((float)($cagr_returns*100), 2, '.', '')}} %
                    </td>
                </tr>
            </tbody>
        </table>

        @if(isset($watermark) && $watermark == 1)
        <div class="watermark">
            {{env('WATERMARK_TEXT')}}
        </div>
        @endif
                        
        <div style="margin-top:5px;">
            @php
                $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-stp-historical")->first();
                if(!empty($note_data1)){
                @endphp
                {!!$note_data1->description!!}
            @php } @endphp
        </div>
        <div style="margin-top:5px;">
            Report Date : {{date('d/m/Y')}}
        </div>
    
        <footer style="height: 70px;">
            <p style="margin-left:-10%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>
        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">
                        
                    </td>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:110px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>

        <div>
            <h4 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">Transferor Scheme : {{$value['from_scheme_details']->s_name}}</h4>
        </div>

        @php $total_count = 2; @endphp

        <table class="table text-center mfliketbl" style="margin-top:10px;">
            <tbody>
                <tr>
                    <th style='width:11%;'><strong>NAV Date</strong></th>
                    <th style='width:9%;'><strong>NAV</strong></th>
                    <th style='width:11%;'><strong>Cash Flow</strong></th>
                    <th style='width:10%;'><strong>Units</strong></th>
                    <th style='width:11%;'><strong>Balance Units</strong></th>
                    <th style='width:12%;'><strong>Net Amount</strong></th>
                    <th style='width:11%;'><strong>Capital Gain/Loss</strong></th>
                    <th style='width:11%;'><strong>No. of Days (Investment)</strong></th>
                    <th style='width:14%;'><strong>Market Value</strong></th>
                </tr>

                @php 
                    $units = (-(-$value['cash_flow'] / $value['investment_data']->navrs));
                    $pre_nav = (float) $value['investment_data']->navrs;
                    $pre_navdate = $value['investment_data']->navdate;
                    $net_amount = $value['lumpsum_investment_amount'];
                    $balance_units = $value['lumpsum_investment_amount'] / $value['investment_data']->navrs;
                    
                    $now = strtotime($pre_navdate);
                @endphp

                <tr>
                    <td style="text-align:center;height:15px;">{{$value['investment_data']->date}}</td>
                    <td style="text-align:right;padding-right:10px;">{{number_format((float)($value['investment_data']->navrs), 2, '.', '')}}</td>
                    <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_investment_amount'])}}</td>
                    <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 2, '.', '')}}</td>
                    <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 2, '.', '')}}</td>
                    <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($net_amount)}}</td>
                    <td style="text-align:right;padding-right:10px;">0</td>
                    <td style="text-align:right;padding-right:10px;">0</td>
                    <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['lumpsum_investment_amount'])}}</td>
                </tr>
                <?php foreach($value['returnDataFrom'] as $k1=>$val1){ 
                    $res = (array) $value;
                    $total_count =  $total_count + 1; ?>
                    
                    @php 
                        $units = (-(-$value['cash_flow'] / $val1['navrs']));
                        $balance_units = $balance_units - $units;
                        $net_amount = ($net_amount) - ($value['cash_flow']);
                        $capital_gain_loss = $units*($val1['navrs'] - $pre_nav);
                        
                        $your_date = strtotime($val1['navdate']);
                        $datediff = $your_date - $now;
                        $number_of_days = round($datediff / (60 * 60 * 24));
                    @endphp
                    
                    @if($total_count == 35)
                        @php $total_count = 0; @endphp
                        </tbody>
                        </table>
                        @if(isset($watermark) && $watermark == 1)
                        <div class="watermark">
                            {{env('WATERMARK_TEXT')}}
                        </div>
                        @endif
                        <footer style="height: 70px;">
                            <p style="margin-left:-10%;text-align: center;">
                                {!! ($name!='')?$name.'<br>':'' !!}
                                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                                @php if(isset($amfi_registered)){ @endphp
                                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                                @php } @endphp
                                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                                {!! ($website!='')?'Website: '.$website:'' !!}
                            </p>
                        </footer>
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">
                                        
                                    </td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle">
                                        <img style="display:inline-block; height:110px;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        <table class="table text-center mfliketbl" style="margin-top:10px;">
                        <tbody>
                            <tr>
                                <th style='width:11%;'><strong>NAV Date</strong></th>
                                <th style='width:9%;'><strong>NAV</strong></th>
                                <th style='width:11%;'><strong>Cash Flow</strong></th>
                                <th style='width:10%;'><strong>Units</strong></th>
                                <th style='width:11%;'><strong>Balance Units</strong></th>
                                <th style='width:12%;'><strong>Net Amount</strong></th>
                                <th style='width:11%;'><strong>Capital Gain/Loss</strong></th>
                                <th style='width:11%;'><strong>No. of Days (Investment)</strong></th>
                                <th style='width:14%;'><strong>Market Value</strong></th>
                            </tr>
                    @endif
                    <tr>
                        <td style="text-align:center; height:15px;">{{$val1['date']}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($val1['navrs']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> -{{custome_money_format($value['cash_flow'])}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($units), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($net_amount)}}</td>
                        <td style="text-align:right;padding-right:10px;">{{custome_money_format($capital_gain_loss)}}</td>
                        <td style="text-align:right;padding-right:10px;">{{$number_of_days}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($balance_units * $val1['navrs'])}}</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        @if(isset($watermark) && $watermark == 1)
        <div class="watermark">
            {{env('WATERMARK_TEXT')}}
        </div>
        @endif
        
    
        <footer style="height: 70px;">
            <p style="margin-left:-10%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>

        <div class="page-break"></div>
        <header>
            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                <tbody>
                <tr>
                    <td style="text-align:left; border:0;" align="left">
                        
                    </td>
                    <td style="text-align:right; border:0;" align="left" valign="middle">
                        <img style="display:inline-block; height:110px;" src="{{$company_logo}}" alt=""></td>
                </tr>
                </tbody>
            </table>
        </header>

        <div>
            <h4 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">Transferee Scheme : {{$value['to_scheme_details']->s_name}}</h4>
        </div>

         @php $total_count = 2; @endphp

        <table class="table text-center mfliketbl" style="margin-top:10px;">
            <tbody>
                <tr>
                    <th><strong>NAV Date</strong></th>
                    <th><strong>NAV</strong></th>
                    <th><strong>Cash Flow</strong></th>
                    <th><strong>Units</strong></th>
                    <th><strong>Balance Units</strong></th>
                    <th><strong>Amount Invested</strong></th>
                    <th><strong>Market Value</strong></th>
                </tr>
                <?php foreach($value['returnDataTo'] as $k1=>$val1){ 
                    $res = (array) $value; 
                    $total_count =  $total_count + 1; ?>
                    
                    @if($total_count == 35)
                        @php $total_count = 0; @endphp
                        </tbody>
                        </table>
                        @if(isset($watermark) && $watermark == 1)
                        <div class="watermark">
                            {{env('WATERMARK_TEXT')}}
                        </div>
                        @endif
                        <footer style="height: 70px;">
                            <p style="margin-left:-10%;text-align: center;">
                                {!! ($name!='')?$name.'<br>':'' !!}
                                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                                @php if(isset($amfi_registered)){ @endphp
                                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                                @php } @endphp
                                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                                {!! ($website!='')?'Website: '.$website:'' !!}
                            </p>
                        </footer>
                        <div class="page-break"></div>
                        <header>
                            <table style="border:0 !important;" cellpadding="0" cellspacing="0">
                                <tbody>
                                <tr>
                                    <td style="text-align:left; border:0;" align="left">
                                        
                                    </td>
                                    <td style="text-align:right; border:0;" align="left" valign="middle">
                                        <img style="display:inline-block; height:110px;" src="{{$company_logo}}" alt=""></td>
                                </tr>
                                </tbody>
                            </table>
                        </header>
                        <table class="table text-center mfliketbl" style="margin-top:10px;">
                        <tbody>
                            <tr>
                                <th><strong>NAV Date</strong></th>
                                <th><strong>NAV</strong></th>
                                <th><strong>Cash Flow</strong></th>
                                <th><strong>Units</strong></th>
                                <th><strong>Balance Units</strong></th>
                                <th><strong>Amount Invested</strong></th>
                                <th><strong>Market Value</strong></th>
                            </tr>
                    @endif
                    <tr>
                        <td style="text-align:center; height:15px;">{{$val1['date']}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($val1['navrs']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($value['cash_flow'])}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($val1['units']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($val1['balance_units']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($val1['amount_invested'])}}</td>
                        <td style="text-align:right;padding-right:10px;"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{custome_money_format($val1['market_value'])}}</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        @if(isset($watermark) && $watermark == 1)
        <div class="watermark">
            {{env('WATERMARK_TEXT')}}
        </div>
        @endif
    
        <footer style="height: 70px;">
            <p style="margin-left:-10%;text-align: center;">
                {!! ($name!='')?$name.'<br>':'' !!}
                {!! ($company_name!='')?$company_name.'<br>':'' !!}
                @php if(isset($amfi_registered)){ @endphp
                {!! ($amfi_registered!='' || $amfi_registered!='0')?$amf.'<br>':'' !!}
                @php } @endphp
                {!! ($email!='')?'Email: '.$email.', ':'' !!}
                @if($website!=''){!! ($phone_no!='')?'M: '.$phone_no.', ':'' !!}@else {!! ($phone_no!='')?'M: '.$phone_no:'' !!} @endif
                {!! ($website!='')?'Website: '.$website:'' !!}
            </p>
        </footer>

    @endforeach

    
</main>
</body>
</html>
