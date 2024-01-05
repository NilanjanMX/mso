<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>SWP HISTORICAL</title>
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
        <h1 style="color: #000;font-size:15px;margin-bottom:10px !important;text-align:center;">SWP HISTORICAL</h1>
    </div>

    @foreach($result as $key => $value)

        <table class="table text-center mfliketbl">
            <tbody>
                <tr>
                    <td>
                        <strong>Scheme Name</strong>
                    </td>
                    <td>
                        {{$value['schemecode_details']->s_name}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Investment Amount</strong>
                    </td>
                    <td>
                        {{$value['lumpsum_investment_amount']}}
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
                        <strong>SWP Period</strong>
                    </td>
                    <td>
                        {{$value['swp_start_date']}} - {{$value['swp_start_date']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>No. of {{$value['data_frequency']}} Instalments</strong>
                    </td>
                    <td>
                        {{count($value['returnData'])}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Total Withdrawal Amount</strong>
                    </td>
                    <td>
                        
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Market Value as on {{$value['swp_end_date']}}</strong>
                    </td>
                    <td>
                        {{(int) $value['market_value']}}
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>XIRR Return</strong>
                    </td>
                    <td>
                        {{$value['xirr_return']}}
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table text-center mfliketbl" style="margin-top:10px;">
            <tbody>
                <tr>
                    <th><strong>NAV Date</strong></th>
                    <th><strong>NAV</strong></th>
                    <th><strong>Cash Flow</strong></th>
                    <th><strong>Units</strong></th>
                    <th><strong>Balance Units</strong></th>
                    <th><strong>Market Value</strong></th>
                </tr>
                <?php foreach($value['returnData'] as $k1=>$val1){ 
                    $res = (array) $value; ?>
                    <?php if($k1 && ($k1)%20 == 0){ ?>
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
                        <div style="padding: 0 15%;">
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Debt held by Mutual Funds</h1>
                        </div>

                        <table class="table text-center mfliketbl">
                            <tbody>
                                <tr>
                                    <th><strong>NAV Date</strong></th>
                                    <th><strong>NAV</strong></th>
                                    <th><strong>Cash Flow</strong></th>
                                    <th><strong>Units</strong></th>
                                    <th><strong>Balance Units</strong></th>
                                    <th><strong>Market Value</strong></th>
                                </tr>
                    <?php } ?>
                    
                    @if($k1 == 0)
                        @php 
                            $balance_units = $value['lumpsum_investment_amount']/$value['investment_data']->navrs; 
                        @endphp
                        <tr>
                            <td style="text-align:left; height:30px;">{{$value['investment_date']}}</td>
                            <td style="text-align:right;padding-right:10px;">{{number_format((float)($value['investment_data']->navrs), 2, '.', '')}}</td>
                            <td style="text-align:right;padding-right:10px;">{{$value['lumpsum_investment_amount']}}</td>
                            <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 0, '.', '')}}</td>
                            <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 0, '.', '')}}</td>
                            <td style="text-align:right;padding-right:10px;">{{$value['lumpsum_investment_amount']}}</td>
                        </tr>
                    @endif

                    @php 
                        $units = (-(-$value['cash_flow'] / $val1['navrs']));
                        $balance_units = $balance_units - $units;
                    @endphp
                    <tr>
                        <td style="text-align:left; height:30px;">{{$val1['date']}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($val1['navrs']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;">-{{$value['cash_flow']}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($units), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units), 2, '.', '')}}</td>
                        <td style="text-align:right;padding-right:10px;">{{number_format((float)($balance_units * $val1['navrs']), 0, '.', '')}}</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    @endforeach

    @if(isset($watermark) && $watermark == 1)
    <div class="watermark">
        {{env('WATERMARK_TEXT')}}
    </div>
    @endif
                    
    <div style="margin-top:5px;">
        @php
            $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-swp-historical")->first();
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
</main>
</body>
</html>
