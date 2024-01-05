@extends('layouts.frontend')

@section('content')


<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap" rel="stylesheet">
<style type="text/css">
    @font-face {
        font-family:'fontello'; 
        font-display:swap;
        src:url("{{asset('fonts/fontello.woff2')}}") format("woff2"),
        url("{{asset('fonts/fontello.woff')}}") format("woff");
        font-weight:normal;
        font-style:normal
    }
    /*table.dataTable thead .sorting {*/
    /*    background-image: url(https://cdn.datatables.net/1.10.20/images/sort_desc.png);*/
    /*}*/


    .nav-link {
        background-color: #25a8e0;
        color: #FFFFFF !important;
    }
    .nav-link:focus, .nav-link:hover {
        color: #FFFFFF;
    }
    .active-nav-link {
        background-color: #75cdf3;
        box-shadow: 0px 0px 6px #000;
    }
    .mf-scanner-filter-box {
        border: 1px solid;
        padding: 5px;
        height: 172px;
        overflow-y: auto;
    }

    .mf-scanner-filter-box ::-webkit-scrollbar {
        display: none;
    }
    .mf-scanner-button-div {
        text-align: right;
        margin-top: 8px;
    }

    .mf-scanner-filter-box-header {
      margin: 0px;
      font-weight: bold;
      display: flex;
    }
    .tab-content {
      border-bottom: 1px solid #ccc;
      margin-bottom: 5px;
    }
    .category-view-span{
      background-color: #fff;
        border: 1px solid #484646;
        padding: 2px 4px;
        margin-right: 4px;
        display: inline-block;
        /*margin-bottom: 4px;*/
        font-size: 11px;
        line-height: 12px
    }
    #mf_scanner_list {
        width: 98%;
        margin: 0 auto;
        border: 1px solid #b5b3b3;
    }
    #mf_scanner_list thead tr {
        background-color: #c3c3c3;
        border: 1px solid #b5b3b3;
    }
    #mf_scanner_list thead tr td + td {
        border-left: 1px solid #b5b3b3;
    }
    #mf_scanner_list thead tr td  {
        padding: 8px 4px;
        text-align: center;
        line-height: 14px;
        font-size: 12px;
        font-weight: 600;
        padding-right: 16px;
    }
    table.dataTable tbody tr:nth-child(even) {
        background-color:#f0f1f6;
    }
    #mf_scanner_list tbody tr + tr {
        border-top: 1px solid #b5b3b3;
    }
    #mf_scanner_list tbody tr td + td {
        border-left: 1px solid #b5b3b3;
    }
    #mf_scanner_list tbody tr td  {
        padding: 3px 4px;
        text-align: right;
        line-height: 14px;
        font-size: 11px;
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
    }
    .downloadButton {
        border-radius: 9px;
        background: #131f55;
        border: 1px solid #131f55;
        line-height: 17px;
    }
    .campairButton {
        border-radius: 9px;
        background: #a2d2f7;
        border: 1px solid #131f55;
        line-height: 17px;
    }
    .savedButton {
        border-radius: 9px;
        background: #16a1db;
        border: 1px solid #131f55;
        line-height: 17px;
    }
    .resetallButton {
        border-radius: 9px;
        background: #e5f4fb;
        border: 1px solid #131f55;
        line-height: 17px;
        color:#131f55;
    }
    .closeButton {
        border-radius: 9px;
        line-height: 17px;
        border:1px solid #131f55;
    }
    .top-tab ul {
        margin: 0;
    }
    .main-sec {
        padding-top:7px;
    }
    .rt-pnl {
        padding-top:0;
    }
    #mf_scanner_list tr td:nth-child(1) {
        width: 20px;
    }
    #myTab .nav-link i {
        float: right;
        font-size: 25px;
        margin-top: -1px;
    }
    #myTab .nav-link.active-nav-link i.fa-angle-right, #myTab .nav-link i.fa-angle-down {
        display: none;
    }
    #myTab .nav-link.active-nav-link i.fa-angle-down, #myTab .nav-link i.fa-angle-right {
        display: block;
    }
    #myTab div.col-md-4, #filters_view div.col-md-2 {
        padding: 0 3px;
    }
    .checkHeading {
        margin: 0px;
        font-weight: bold;
    }
    .checkHeading input {
        float: right;
    }
    .mf-scanner-filter-box-header {
        justify-content: space-between;
    }
    [class^="icon-"]:before, [class*=" icon-"]:before {
        font-family: "fontello";
        font-style: normal;
        font-weight: normal;
        speak: none;
        display: inline-block;
        text-decoration: inherit;
        text-align: center;
        font-variant: normal;
        text-transform: none;
        line-height: 1rem;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    .icon-cancel:before {
        content: '\e800';
        color:#16a1db;
        font-size: 12px;
    }
    .form-check-label {
        font-size: 11px;
        display: block;
        margin: 0;
        line-height: 16px;
        padding-top: 0;
    }
    .pspan {
        display:inline-block;
        
    }
    .pspan span {
    }
    #filters_view .form-check-input, #choose_columns_view .form-check-input {
            margin-top: .1rem;
    }
    .dataHintHold {
        position: relative;
    }
    .dataHintHold span {
        visibility: hidden;
        width: 90px;
        background-color: #20272e;
        color: #fff;
        text-align: center;
        border-radius: 2px;
        padding: 8px;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 80%;
        margin-left: -55px;
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 12px;
        line-height: 15px;
        font-weight: 400;
        box-shadow: 1px 6px 4px #0a0a0a61;
    }
    .dataHintHold span:after {
        content: "";
        position: absolute;
        top: 100%;
        left: 80%;
        margin-left: -6px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
        z-index: 99;
    }
    .dataHintHold:hover span {
        visibility: visible;
        opacity: 1;
    }
    #asset_class_selection_view .form-check-input {
        margin-top: .1rem;
    }
    #mf_scanner_list thead tr th {
        padding: 6px 2px;
        text-align: center;
        line-height: 14px;
        font-size: 11px;
        font-weight: 600;
    }
</style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title"> {{$details->name}}</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.mf_scanner.top_sidebar')
                <div class="col-md-12">
                    <div class="rt-pnl" style="box-shadow: none;">
                        <h2 class="headline1">{{$name}}</h2>
                        <div class="rt-btn-prt">
                            <a href="javascript:history.back()"><i class="fa fa-angle-left"></i> Back</a>

                            @if($permission['is_download'])
                                @if($permission['is_cover'])
                                    <a href="javascript:void(0);" onclick="return openModal('{{$details->url}}','{{$mf_scanner_saved_id}}');">
                                        Download
                                    </a>
                                @else
                                    <a href="{{$details->url}}-download?id={{$mf_scanner_saved_id}}">
                                        Download
                                    </a>
                                @endif
                            @else
                                <button type="button" onclick="openDownloadPermissionModal();">
                                    Download
                                </button>
                            @endif
                        </div>
                        <div class="row mt-3">
                          <table id="mf_scanner_list"> 
                                <thead>
                                    <tr>
                                        <th>
                                            <strong>Scheme </strong>
                                        </th>
                                        <th>
                                            <strong>1 Day </strong>
                                        </th>
                                        <th>
                                            <strong>1 Week </strong>
                                        </th>
                                        <th>
                                            <strong>1 Month </strong>
                                        </th>
                                        <th>
                                            <strong>3 Month </strong>
                                        </th>
                                        <th>
                                            <strong>6 Month </strong>
                                        </th>
                                        <th>
                                            <strong>1 Year </strong>
                                        </th>
                                        <th>
                                            <strong>2 Year </strong>
                                        </th>
                                        <th>
                                            <strong>3 Year </strong>
                                        </th>
                                        <th>
                                            <strong>5 Year </strong>
                                        </th>
                                        <th>
                                            <strong>10 Year </strong>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td style="width:20%;" align="left">
                                           <div style="text-align: left; font-weight:bold;">Category Average</div>
                                        </td>
                                        <td style=" font-weight:bold;{{$oneday}}" align="right">
                                            <?php echo ($avg_data->onedayret)?number_format((float)$avg_data->onedayret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$oneweek}}" align="right">
                                            <?php echo ($avg_data->oneweekret)?number_format((float)$avg_data->oneweekret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$onemonth}}" align="right">
                                            <?php echo ($avg_data->onemonthret)?number_format((float)$avg_data->onemonthret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$threemonth}}" align="right">
                                            <?php echo ($avg_data->threemonthret)?number_format((float)$avg_data->threemonthret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$sixmonth}}" align="right">
                                            <?php echo ($avg_data->sixmonthret)?number_format((float)$avg_data->sixmonthret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$oneyear}}" align="right">
                                            <?php echo ($avg_data->oneyrret)?number_format((float)$avg_data->oneyrret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$twoyear}}" align="right">
                                            <?php echo ($avg_data->twoyearret)?number_format((float)$avg_data->twoyearret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$threeyear}}" align="right">
                                            <?php echo ($avg_data->threeyearret)?number_format((float)$avg_data->threeyearret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$fiveyear}}" align="right">
                                            <?php echo ($avg_data->fiveyearret)?number_format((float)$avg_data->fiveyearret, 2, '.', ''):'-';?>
                                        </td>
                                        <td style=" font-weight:bold;{{$tenyear}}" align="right">
                                            <?php echo ($avg_data->tenyret)?number_format((float)$avg_data->tenyret, 2, '.', ''):'-';?>
                                        </td>
                                    </tr>
                                    <?php if($criteria == 1|| $criteria == 3){ ?> 
                                        <tr>
                                            <td colspan='11' style='text-align:center; background:#16a1db; color:#fff; font-size:13px;padding:7px;'>Best Performing Scheme</td>
                                        </tr>
                                        <?php foreach($best_data as $key=>$value){  ?>
                                            <tr>
                                                <td style="width:15%;" align="left">
                                                   <div style="text-align: left;"> <?php echo ($value->s_name)?$value->s_name:'';?></div>
                                                </td>
                                                <td style="{{$oneday}}" align="right">
                                                    <?php echo ($value->onedayret)?number_format((float)$value->onedayret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$oneweek}}" align="right">
                                                    <?php echo ($value->oneweekret)?number_format((float)$value->oneweekret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$onemonth}}" align="right">
                                                    <?php echo ($value->onemonthret)?number_format((float)$value->onemonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$threemonth}}" align="right">
                                                    <?php echo ($value->threemonthret)?number_format((float)$value->threemonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$sixmonth}}" align="right">
                                                    <?php echo ($value->sixmonthret)?number_format((float)$value->sixmonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$oneyear}}" align="right">
                                                    <?php echo ($value->oneyrret)?number_format((float)$value->oneyrret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$twoyear}}" align="right">
                                                    <?php echo ($value->twoyearret)?number_format((float)$value->twoyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$threeyear}}" align="right">
                                                    <?php echo ($value->threeyearret)?number_format((float)$value->threeyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$fiveyear}}" align="right">
                                                    <?php echo ($value->fiveyearret)?number_format((float)$value->fiveyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$tenyear}}" align="right">
                                                    <?php echo ($value->tenyret)?number_format((float)$value->tenyret, 2, '.', ''):'-';?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                        
                                    <?php if($criteria == 2|| $criteria == 3){ ?> 
                                        <tr>
                                            <td colspan='11' style='text-align:center; background:#16a1db; color:#fff; font-size:13px;padding:7px;'>Worst Performing Scheme</td>
                                        </tr>
                                        <?php foreach($worst_data as $key=>$value){  ?>
                                            <tr>
                                                <td style="" align="left">
                                                   <div style="text-align: left;"> <?php echo ($value->s_name)?$value->s_name:'';?></div>
                                                </td>
                                                <td style="{{$oneday}}" align="right">
                                                    <?php echo ($value->onedayret)?number_format((float)$value->onedayret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$oneweek}}" align="right">
                                                    <?php echo ($value->oneweekret)?number_format((float)$value->oneweekret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$onemonth}}" align="right">
                                                    <?php echo ($value->onemonthret)?number_format((float)$value->onemonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$threemonth}}" align="right">
                                                    <?php echo ($value->threemonthret)?number_format((float)$value->threemonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$sixmonth}}" align="right">
                                                    <?php echo ($value->sixmonthret)?number_format((float)$value->sixmonthret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$oneyear}}" align="right">
                                                    <?php echo ($value->oneyrret)?number_format((float)$value->oneyrret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$twoyear}}" align="right">
                                                    <?php echo ($value->twoyearret)?number_format((float)$value->twoyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$threeyear}}" align="right">
                                                    <?php echo ($value->threeyearret)?number_format((float)$value->threeyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$fiveyear}}" align="right">
                                                    <?php echo ($value->fiveyearret)?number_format((float)$value->fiveyearret, 2, '.', ''):'-';?>
                                                </td>
                                                <td style="{{$tenyear}}" align="right">
                                                    <?php echo ($value->tenyret)?number_format((float)$value->tenyret, 2, '.', ''):'-';?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } ?>
                                </tbody>
                          </table>
                        </div>
                        
                        
                        
                        @if(isset($comments))
                            @if($comments)
                                @php $fsdf = str_replace("\r\n", "<br>",$comments); @endphp
                                <div style="padding: 0 0%;">
                                    <div style="font-size: 16px;font-weight: bold;color: #131f55;padding-top: 15px;  padding-bottom: 14px;">Comment</div>
                                    <div style="border: 1px solid;padding: 10px;border-radius: 6px;">
                                        {!!$fsdf!!}
                                    </div>
                                </div>
                            @endif
                        @endif
                        
                        <div class="row mt-1 pl-2">
                            @php
                                $note_data1 = \App\Models\Mfresearch_note::where('id',5)->first();
                                if(!empty($note_data1)){
                                @endphp
                                {!!$note_data1->description!!}
                            @php } @endphp
                        </div>
                        <div class="row pl-2">
                            Report Date : {{date('d/m/Y')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">
        </div>
    </section>

    @include('frontend.mf_research.modal')
    @include('frontend.mf_research.common.cover_modal_view')

@endsection
