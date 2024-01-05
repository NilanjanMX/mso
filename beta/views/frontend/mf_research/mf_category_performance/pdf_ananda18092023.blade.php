<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>MF SCREENER</title>
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
            height: 29.7cm;
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
            padding:0;
        }

        table {
            width:100%;
            border-spacing: 0;
            margin-bottom: 30px;
        }

        table th,
        table td {
            text-align: center;
            border: 1px solid #b8b8b8;
            padding: 5px 8px;
            font-weight: normal;
            color: #000;
        }

        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
            /*white-space: nowrap;*/
        }

        .table-bordered th, .table-bordered td{
            padding: 4px 6px;
            font-size: 12px;
            /*white-space: nowrap;*/
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
        .table tr th:nth-child(1) {
            width: 150px;
        }
    </style>
</head>
<body>
<main style="width: 810px;">
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
    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">MF Category-wise Performance</h1>
    </div>

    <table class="table table-bordered text-center">
        <tbody>
            <tr>
               
                <th>
                    <strong>Category</strong>
                </th>
                <th>
                    <strong>Plan</strong>
                </th>
                <?php foreach($cbd as $value){ ?>
                    <th>
                        <strong><?php echo $value['name'];?></strong>
                    </th>
                <?php } ?>
            </tr>
            <?php foreach($result as $key=>$value){ 
                $res = (array) $value; ?>
                <?php if($key && ($key)%20 == 0){ ?>
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
                        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Category-wise Performance</h1>
                    </div>

                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                               
                                <th>
                                    <strong>Category</strong>
                                </th>
                                <th>
                                    <strong>Plan</strong>
                                </th>
                                <?php foreach($cbd as $value){ ?>
                                    <th>
                                        <strong><?php echo $value['name'];?></strong>
                                    </th>
                                <?php } ?>
                            </tr>
                <?php } ?>
                <tr>
                    <td style="width:30%;height:32px;" align="left">
                       <div style="text-align: left;"> <?php echo ($res['class_name'])?$res['class_name']:$res['classname'];?></div>
                    </td>
                    <td style="width:10%;" align="right">
                        <?php echo ($res['plan_name'])?$res['plan_name']:$res['planname'];?>
                    </td>
                    <?php foreach($crkey as $val){ ?>
                        <td align="right" style="text-align:right;">
                            <?php if($res[$val] == "0" || !$res[$val]){ ?>
                                -
                            <?php } else { ?>
                                {{number_format($res[$val], 2, '.', '')}}
                            <?php } ?>
                        </td>
                    <?php } ?>
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
            $note_data1 = \App\Models\Mfresearch_note::where('id',3)->first();
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
    </div>
    <div style="margin-top:10px;">
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
