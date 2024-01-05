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
            margin: 0 auto;
            color: #001028;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-spacing: 0;
            margin-bottom: 30px;
            border: 1px solid #b8b8b8;
        }

        table th,
        table td {
            text-align: center;
            padding: 3px 6px;
            font-weight: normal;
            color: #000;
            border-top: 1px solid #b8b8b8;
        }
        table tr td + td, table tr th + th {
            border-left: 1px solid #b8b8b8;
        } 
        table {
            margin: 0;
        }

        table th {
            font-weight: bold;
            background: #a9f3ff;
        }

        .table-bordered th, .table-bordered td{
            padding: 2px 2px;
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
            margin-top: 100px
        }

        header {
            position: fixed;
            top: -110px;
            left: 0px;
            right: 40px;
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
<main style="margin-left: 20px;">
    @php
    $amf='AMFI-Registered Mutual Fund Distributor';
    $is_rating = 0;
    @endphp

    <header>
        <table style="border:0 !important;" cellpadding="0" cellspacing="0">
            <tbody>
            <tr>
                <td style="text-align:left; border:0;" align="left">
                    
                </td>
                <td style="text-align:right; border:0;" align="left" valign="middle">
                    <img style="display:inline-block; height:110px; margin-top: 20px" src="{{$company_logo}}" alt=""></td>
            </tr>
            </tbody>
        </table>
    </header>
    <div style="padding: 0 15%;">
        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Mutual Fund Screener</h1>
    </div>

    <table class="table table-bordered text-center">
        <tbody>
            <tr>
               
                <th style="width:150px;">
                    <strong>Fund</strong>
                </th>
                <?php foreach($response as $value){ ?>
                    <?php if($value['table_checkbox'] == 1){ 
                        if($value['key_name'] == "rating"){
                            $is_rating = 1;
                        }
                    
                    ?>
                        <th <?php if($value['key_name'] == "classname"){ ?> style="width:100px;" <?php } ?>>
                            <strong><?php echo $value['name'];?></strong>
                        </th>
                    <?php } ?>
                <?php } ?>
            </tr>
            <?php foreach($result as $key=>$value){ 
                $res = (array) $value; ?>
                <?php if($key && ($key)%15 == 0){ ?>
                    </tbody>
                    </table>
                    @if(isset($watermark) && $watermark == 1)
                        <div class="watermark">
                            {{env('WATERMARK_TEXT')}}
                        </div>
                    @endif
                    <footer style="height: 70px;">
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
                                    <img style="display:inline-block; height:110px; margin-top: 20px" src="{{$company_logo}}" alt=""></td>
                            </tr>
                            </tbody>
                        </table>
                    </header>
                    <div style="padding: 0 15%;">
                        <h1 style="color: #000;font-size:16px;margin-bottom:20px !important;text-align:center;">Mutual Fund Screener</h1>
                    </div>

                    <table class="table table-bordered text-center">
                        <tbody>
                            <tr>
                               
                                <th style="width:150px;">
                                    <strong>Fund</strong>
                                </th>
                                <?php foreach($response as $val){ ?>
                                    <?php if($val['table_checkbox'] == 1){ ?>
                                        <th <?php if($val['key_name'] == "classname"){ ?> style="width:100px;" <?php } ?>>
                                            <strong><?php echo $val['name'];?> </strong>
                                        </th>
                                    <?php } ?>
                                <?php } ?>
                            </tr>
                <?php } ?>
                <tr>
                    <td style="width:15%;height:32px;" align="left">
                       <div style="text-align: left;"> <?php echo substr($res['s_name'], 0, 35);?></div>
                    </td>
                    <?php foreach($response as $val){ 

                         ?>
                         <?php if($val['table_checkbox'] == 1){ ?>
                            <td align="right" style="text-align:right;">
                                <?php if($val['key_name'] == "classname"){ ?>
                                    <?php if($res['class_name']){ ?>
                                        {{$res['class_name']}}
                                    <?php }else{ ?>
                                        {{$res[$val['key_name']]}}
                                    <?php } ?>
                                <?php } else if($val['key_name'] == "rating"){ ?>
                                    <?php if($res[$val['key_name']] == 5) { ?>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                    <?php } else if($res[$val['key_name']] == 4) { ?>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                    <?php } else if($res[$val['key_name']] == 3) { ?>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                    <?php } else if($res[$val['key_name']] == 2) { ?>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                    <?php } else if($res[$val['key_name']] == 1) { ?>
                                        <img  src="{{public_path('img/star_icon-checked.png')}}" style='width: 8px;'>
                                    <?php } else { ?>
                                        Unrated
                                    <?php } ?>
                                <?php } else if($res[$val['key_name']] == "0" || !$res[$val['key_name']]){ ?>
                                    -
                                <?php } else if($val['key_name'] == "total"){ ?>
                                    {{custome_money_format((int) ($res[$val['key_name']] /100))}}
                                <?php } else if($val['key_name'] == "Incept_date"){ ?>
                                    {{date('d-m-Y', strtotime($res[$val['key_name']]))}}
                                <?php } else if($val['key_name'] == "IndexName"){ ?>
                                    {{$res[$val['key_name']]}}
                                <?php } else if($val['key_name'] == "MCAP"){ ?>
                                    {{custome_money_format((int) $res[$val['key_name']] /100)}}
                                <?php } else if($val['key_name'] == "ASECT_CODE"){ ?>
                                    {{$res[$val['key_name']]}}
                                <?php } else if($val['key_name'] == "highest_sector_all"){ ?>
                                    {{ucfirst(strtolower($res[$val['key_name']]))}}
                                <?php } else if($val['key_name'] == "avg_mat_num"){ ?>
                                    {{$res[$val['key_name']]}} {{$res['avg_mat_days']}}
                                <?php } else if($val['key_name'] == "mod_dur_num"){ ?>
                                    {{$res[$val['key_name']]}} {{$res['mod_dur_days']}}
                                <?php } else if($val['key_name'] == "turnover_ratio"){ ?>
                                    <?php if($res['tr_mode'] == "times"){ ?>
                                        {{custome_money_format((int) ($res['turnover_ratio'] * 100))}}
                                    <?php }else{ ?>
                                        {{$res[$val['key_name']]}}
                                    <?php } ?>
                                <?php } else { ?>
                                    {{number_format((float) $res[$val['key_name']], 2, '.', '')}}
                                <?php } ?>
                            </td>
                        <?php } ?>
                            
                        
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
            if($is_rating){
                $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener-with-rating")->first();
            }else{
                $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener")->first();
            }
            if(!empty($note_data1)){
            @endphp
            {!!$note_data1->description!!}
        @php } @endphp
    </div>  
    <div style="margin-top:10px;">
        Report Date : {{date('d/m/Y')}}
    </div>

    <footer style="height: 70px;">
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
