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
    /*#mf_scanner_list thead tr {*/
    /*    background-color: #c3c3c3;*/
    /*    border: 1px solid #b5b3b3;*/
    /*}*/
    /*#mf_scanner_list thead tr td + td {*/
    /*    border-left: 1px solid #b5b3b3;*/
    /*}*/
    /*#mf_scanner_list thead tr td  {*/
    /*    padding: 8px 4px;*/
    /*    text-align: center;*/
    /*    line-height: 14px;*/
    /*    font-size: 12px;*/
    /*    font-weight: 600;*/
    /*    padding-right: 16px;*/
    /*}*/
    /*table.dataTable tbody tr:nth-child(even) {*/
    /*    background-color:#f0f1f6;*/
    /*}*/
    /*#mf_scanner_list tbody tr + tr {*/
    /*    border-top: 1px solid #b5b3b3;*/
    /*}*/
    /*#mf_scanner_list tbody tr td + td {*/
    /*    border-left: 1px solid #b5b3b3;*/
    /*}*/
    /*#mf_scanner_list tbody tr td  {*/
    /*    padding: 1px 4px;*/
    /*    text-align: right;*/
    /*    line-height: 14px;*/
    /*    font-size: 11px;*/
    /*    font-family: 'Poppins', sans-serif;*/
    /*    font-weight: 400;*/
    /*}*/
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
</style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">MF SCREENER</h2>
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
                            <a href="{{route('frontend.mf_download_saved_file',['id'=>$mf_scanner_saved_id])}}">Download</a>
                        </div>
                        <div class="row mt-3">
                          <table id="mf_scanner_list" class="table text-center stock_table mfliketbl">
                                <thead>
                                    <tr>
                                        <th style="width:200px;">Sector</th>
                                        <th>No. of Funds</th>
                                        <th>{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                        <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                        <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                        <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <td align="left">&nbsp;{{$detail['sector']}}</td>
                                    <td align="right">&nbsp;{{number_format((float)($detail['number_of_fund']), 2, '.', '')}}</td>
                                    <td align="right">&nbsp;{{number_format((float)($detail['current_month']), 2, '.', '')}}</td>
                                    <td align="right">&nbsp;{{number_format((float)($detail['one_month']), 2, '.', '')}}</td>
                                    <td align="right">&nbsp;{{number_format((float)($detail['two_month']), 2, '.', '')}}</td>
                                    <td align="right">&nbsp;{{number_format((float)($detail['three_month']), 2, '.', '')}}</td>
                                </tbody>
                          </table>
                        </div>
                        <div class="row mt-3">
                          <table id="mf_scanner_list" class="table text-center stock_table mfliketbl">
                                <thead>
                                    <tr>
                                        <th rowspan="2" style="width:300px;">Scheme Name</th>
                                        <th rowspan="2">Fund Manager</th>
                                        <th colspan="3">{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                        <th colspan="3">No. of Shares</th>
                                    </tr>
                                    <tr>
                                        <th>AUM (in Rs Cr)</th>
                                        <th>% of AUM</th>
                                        <th>No. of Shares</th>
                                        <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                        <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                        <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(count($list)){ ?>
                                        <?php foreach ($list as $key => $value) { ?>
                                            <tr>
                                                <td align="left">{{$value->s_name}}</td>
                                                <td align="left">{{$value->fund_mgr1}}</td>
                                                <td align="right">{{custome_money_format($value->aum)}}</td>
                                                <td align="right">{{number_format((float)($value->holdpercentage), 2, '.', '')}}</td>
                                                <!--<td align="right">{{number_format((float)($value->noshares), 2, '.', '')}}</td>-->
                                                <!--<td align="right">{{number_format((float)($value->one_month), 2, '.', '')}}</td>-->
                                                <!--<td align="right">{{number_format((float)($value->two_month), 2, '.', '')}}</td>-->
                                                <!--<td align="right">{{number_format((float)($value->three_month), 2, '.', '')}}</td>-->
                                                
                                                <td style="text-align:right;">{{custome_money_format($value->noshares)}}</td>
                                                <td style="text-align:right;">{{custome_money_format($value->one_month)}}</td>
                                                <td style="text-align:right;">{{custome_money_format($value->two_month)}}</td>
                                                <td style="text-align:right;">{{custome_money_format($value->three_month)}}</td>
                                            </tr>
                                        <?php } ?>
                                    <?php }else{ ?>
                                        <tr>
                                            <td colspan="8" class="text-center"> No Data Found</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                          </table>
                        </div>
                        <div class="row mt-1 pl-2">
                            @php
                                $note_data1 = \App\Models\Mfresearch_note::where('id',1)->first();
                                if(!empty($note_data1)){
                                @endphp
                                {!!$note_data1->description!!}
                            @php } @endphp
                            <br><br>
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

@endsection
