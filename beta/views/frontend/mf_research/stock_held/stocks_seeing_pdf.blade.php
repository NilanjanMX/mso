<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Stocks Seeing Selling Pressure</title>
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
            font-size: 12px;
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
            top: -134px;
            /*top: -145px;*/
            left: 0px;
            /*right: 94px;*/
            /*right: -25px;*/
            right: -20px;
            height: 50px;
        }
        .pdfHeaderLogo {
            display:inline-block; 
            /*height:150px;*/
            height:115px;
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
            /*height: 50px;*/
            height: 70px;
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
                    <img class="pdfHeaderLogo" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>
    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Stocks Seeing Selling Pressure</h1>
    </div>

    <div style="width: 100%; text-align: right; margin-bottom: 10px;">
        Month : {{date('F, Y', strtotime($invdate))}} 
    </div>

    <table class="table text-center mfliketbl">
        <tbody>
            <tr>
                <th>
                    <strong>Stock Name</strong>
                </th>
                <th>
                    <strong>Sector</strong>
                </th>
                <th>
                    <strong>Classification</strong>
                </th>
                <th>
                    <strong>Net Qty Sold</strong>
                </th>
                <th>
                    <strong>Approx. Sale Value (In <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</strong>
                </th>
            </tr>
            <?php foreach($list as $key=>$value){ 
                $res = (array) $value; ?>
                <?php if($key && ($key)%20 == 0){ ?>
                    </tbody>
                    </table>
                    @if(isset($watermark) && $watermark == 1)
                        <div class="watermark">
                            {{env('WATERMARK_TEXT')}}
                        </div>
                    @endif
                    <footer>
                        <p style="text-align: center;">
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
                                    <img class="pdfHeaderLogo" src="{{$company_logo}}" alt=""></td>
                            </tr>
                            </tbody>
                        </table>
                    </header>
                    <div style="padding: 0 15%;">
                        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Stocks Seeing Selling Pressure</h1>
                    </div>

                    <div style="width: 100%; text-align: right; margin-bottom: 10px;">
                        Month : {{date('F, Y', strtotime($invdate))}} 
                    </div>

                    <table class="table text-center mfliketbl">
                        <tbody>
                            <tr>
                               <th>
                                    <strong>Stock Name</strong>
                                </th>
                                <th>
                                    <strong>Sector</strong>
                                </th>
                                <th>
                                    <strong>Classification</strong>
                                </th>
                                <th>
                                    <strong>Net Qty Sold</strong>
                                </th>
                                <th>
                                    <strong>Approx. Sale Value (In <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> Cr)</strong>
                                </th>
                            </tr>
                <?php } ?>
                <tr>
                    <td style="text-align:left; height:30px;">{{$value->compname}}</td>
                    <td style="text-align:left;">{{$value->Industry}}</td>
                    <td style="text-align:right;">{{$value->mode}}</td>
                    <td style="text-align:right;">{{custome_money_format($value->p_noshares - $value->c_noshares)}}</td>
                    <td style="text-align:right;">{{number_format((float)($value->mktval/100), 2, '.', '')}}</td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    @if(isset($watermark) && $watermark == 1)
    <div class="watermark">
        {{env('WATERMARK_TEXT')}}
    </div>
    @endif
                    
    <div style="margin-top:10px;">
        @php
            $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-stocks-seeing")->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
    </div>
    <div style="margin-top:10px;">
        Report Date : {{date('d/m/Y')}}
    </div>

    <footer>
        <p style="text-align: center;">
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
