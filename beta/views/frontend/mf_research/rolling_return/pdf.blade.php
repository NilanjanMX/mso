<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ROLLING RETURN</title>
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
        
        p {
          margin:0px  !important;  
          padding:0px  !important;  
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
        @if($type == 2)
            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Schemewise Rolling Return Performance </h1>
        @else 
            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Categorywise Rolling Return Performance </h1>
        @endif
    </div>
    
    @php 
    
        $total_count = 0; 
        if($period1_id){
            $total_count = $total_count+1;
        }
        if($period2_id){
            $total_count = $total_count+1;
        }
        if($period3_id){
            $total_count = $total_count+1;
        }
    
    @endphp
    
    @if($type == 2)
        <table class="table text-center" style="margin-top:10px;color: #000;">
            <tbody>
                <tr>
                    <td style="width:70%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Plan : {{$plan_name}}</h1></td>
                    <td style="width:30%;text-align:right;"><h1 style="color: #000;font-size:16px !important;">As On {{$month_name}}, {{$year}}</h1></td>
                </tr>
            </tbody>
        </table>
    @else 
        <table class="table text-center" style="margin-top:10px;color: #000;">
            <tbody>
                <tr>
                    <td style="width:50%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Category : {{$category_name}}</h1></td>
                    <td style="width:17%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Plan : {{$plan_name}}</h1></td>
                    <td style="width:33%;text-align:right;"><h1 style="color: #000;font-size:16px !important;">As On {{$month_name}}, {{$year}}</h1></td>
                </tr>
            </tbody>
        </table>
    @endif

    <table class="table text-center mfliketbl">
        <tbody>
            <tr>
                <th rowspan="2" style="width: {{($total_count==1)?'450':'190'}}px;"><strong>Scheme</strong></th>
                @if($period1_id)
                    <th colspan="3"><strong>{{$period1_id}}</strong></th>
                @endif
                @if($period2_id)
                    <th colspan="3"><strong>{{$period2_id}}</strong></th>
                @endif
                @if($period3_id)
                    <th colspan="3"><strong>{{$period3_id}}</strong></th>
                @endif
            </tr>
            <tr>
                @if($period1_id)
                    <th><strong>AVG</strong></th>
                    <th><strong>MAX</strong></th>
                    <th><strong>MIN</strong></th>
                @endif
                @if($period2_id)
                    <th><strong>AVG</strong></th>
                    <th><strong>MAX</strong></th>
                    <th><strong>MIN</strong></th>
                @endif
                @if($period3_id)
                    <th><strong>AVG</strong></th>
                    <th><strong>MAX</strong></th>
                    <th><strong>MIN</strong></th>
                @endif
            </tr>
            <?php foreach($rolling_return as $key=>$value){ 
                $res = (array) $value; ?>
                <?php if($key && ($key)%17 == 0){ ?>
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
                        @if($type == 2)
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Schemewise Rolling Return Performance </h1>
                        @else 
                            <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Categorywise Rolling Return Performance </h1>
                        @endif
                    </div>
                    
                    @if($type == 2)
                        <table class="table text-center" style="margin-top:10px;color: #000;">
                            <tbody>
                                <tr>
                                    <td style="width:70%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Plan : {{$plan_name}}</h1></td>
                                    <td style="width:30%;text-align:right;"><h1 style="color: #000;font-size:16px !important;">As On {{$month_name}}, {{$year}}</h1></td>
                                </tr>
                            </tbody>
                        </table>
                    @else 
                        <table class="table text-center" style="margin-top:10px;color: #000;">
                            <tbody>
                                <tr>
                                    <td style="width:50%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Category : {{$category_name}}</h1></td>
                                    <td style="width:17%;text-align:left;"><h1 style="color: #000;font-size:16px !important;">Plan : {{$plan_name}}</h1></td>
                                    <td style="width:33%;text-align:right;"><h1 style="color: #000;font-size:16px !important;">As On {{$month_name}}, {{$year}}</h1></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif

                    <table class="table text-center mfliketbl">
                        <tbody>
                            <tr>
                                <th rowspan="2" style="width: {{($total_count==1)?'450':'190'}}px;"><strong>Scheme</strong></th>
                                @if($period1_id)
                                    <th colspan="3"><strong>{{$period1_id}}</strong></th>
                                @endif
                                @if($period2_id)
                                    <th colspan="3"><strong>{{$period2_id}}</strong></th>
                                @endif
                                @if($period3_id)
                                    <th colspan="3"><strong>{{$period3_id}}</strong></th>
                                @endif
                            </tr>
                            <tr>
                                @if($period1_id)
                                    <th><strong>AVG</strong></th>
                                    <th><strong>MAX</strong></th>
                                    <th><strong>MIN</strong></th>
                                @endif
                                @if($period2_id)
                                    <th><strong>AVG</strong></th>
                                    <th><strong>MAX</strong></th>
                                    <th><strong>MIN</strong></th>
                                @endif
                                @if($period3_id)
                                    <th><strong>AVG</strong></th>
                                    <th><strong>MAX</strong></th>
                                    <th><strong>MIN</strong></th>
                                @endif
                            </tr>
                <?php } ?>
                
                <tr>
                    <td style="text-align:left; height:30px;">{{$value['s_name']}}</td>
                    @if($period1_id)
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['avg_1']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['max_1']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['min_1']), 2, '.', '')}}</td>
                    @endif
                    @if($period2_id)
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['avg_2']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['max_2']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['min_2']), 2, '.', '')}}</td>
                    @endif
                    @if($period3_id)
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['avg_3']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['max_3']), 2, '.', '')}}</td>
                        <td style="text-align:right;padding:5px;">{{number_format((float)($value['min_3']), 2, '.', '')}}</td>
                    @endif
                </tr>
            <?php } ?>
        </tbody>
    </table>

    @if(isset($watermark) && $watermark == 1)
    <div class="watermark">
        {{env('WATERMARK_TEXT')}}
    </div>
    @endif
                    
    <div style="margin-top:20px;">
        @php
            $note_data1 = \App\Models\Mfresearch_note::where('type','mf-rolling-return')->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
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
