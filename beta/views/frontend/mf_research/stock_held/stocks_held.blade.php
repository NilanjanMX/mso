@extends('layouts.frontend')
@section('js_after')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <script>
        $(window).scroll(function(){
            if ( $(window).width() > 767) {
                if ($(this).scrollTop() > 280) {
                    $('#comparison_fund').addClass('fixed-top container');
                    $('#rv_comparison #comparison_fund').css({"padding-top": "2rem", "background":"#fff", "z-index":"1"});
                    $('#comparison_basic').css({"padding-top": "9.5rem"});
                } else {
                    $('#comparison_fund').removeClass('fixed-top container');
                    $('#rv_comparison #comparison_fund').css({"padding-top": "0", "background":"#fff", "z-index":"1"});
                    $('#comparison_basic').css({"padding-top": "0"});
                }
            }
        });
        var array_id=[];
        var color_arr=['#2d94e7','#85c953','#a55fa9','#00C78C'];

        var id ="3461-30046-16718-293"
        var array_id1=[]

        $(document).ready(function(){
            var id1=id.split('-');
            if(id1!=""){
                for(var t=0;t<id1.length;t++){
                    array_id.push(id1[t]);
                }
                $("#show_page").show();
                //compare_value()
            }
        });

        var $disabledResults = $("#compare_schemecode");
        $disabledResults.select2({
            placeholder: "Search Stock",
        });

        var $disabledResults = $("#amc_code");
        $disabledResults.select2({
            placeholder: "Search AMC",
        });

        function changeCategory(){
            var compare_category = $("#gst_zone").attr('checked');
            if(compare_category){
                compare_category = "Domestic";
            }else{
                compare_category = "Overseas";
            }

            $.ajax({
                url: "{{ url('/mf-stocks-held-scheme-list') }}",
                method: 'get',
                data: {"compare_category":compare_category},
                success: function (result) {
                    console.log(result);
                    options = '<option value=""></option>';
                    $(result.schemeList).each(function (index, value) {
                        options += '<option value="' + value.fincode + '">' + value.compname + '</option>';
                    });
                    $('#compare_schemecode').html(options);
                    $('#compare_schemecode').select2({
                        placeholder: "Select Stock",
                    });
                }
            });
        }

        function changeType(){
            var type = $("#type").attr('checked');
            document.getElementById("amc_div").style.display = "none";
            document.getElementById("stock_div").style.display = "none";
            if(type){
                document.getElementById("amc_div").style.display = "block";
            }else{
                document.getElementById("stock_div").style.display = "block";
            }

        }

        function processInfo(inho){
            console.log(inho.length);
            document.getElementById("page_length").value = inho.length;
        }
        
        var orderPos = 7;
        
        <?php if($type == "AMC"){ ?>
            orderPos = 6;
        <?php  } ?>

        var stock_table = $('#stock_table').dataTable({
            "bPaginate": true,
            "searching": true, 
            "lengthMenu": [[15,20,30,-1], ["Top 15","Top 20","Top 30", "All"]],
            drawCallback : function() {
               processInfo(this.api().page.info());
            },
            "columnDefs": [{
                  targets: 0,
                  searchable: true,
                  visible: false
            }],
            "search": {regex: true},
            "pageLength":20,
            "order": [[ orderPos, "desc" ]]
        });
        
        function onChangeAUMFilter(){
            var val = $("#chapter_id").select2("val");

            console.log(val);

            if(val){
                var data1 = "";
                val.forEach(function(value){
                    if(data1){
                        data1 = data1+"|"+value;
                    }else{
                        data1 = value;
                    }
                });
                console.log(stock_table);
                console.log(1);
                $('#stock_table').DataTable()
                    .column(0)
                    .search(data1, true, false )
                    .draw();
            }else{
                $('#stock_table').DataTable()
                    .column(0)
                    .search('', true, false )
                    .draw();
            }
        }

        $( document ).ready(function() {
            var roleDropdown = "<div class='dataTables_filter' style=''>";
            roleDropdown = roleDropdown+"<select style='background: #fff;border-radius: 0;border: 1px solid #dce1e4;box-shadow: none!important;font-size: 13px;padding: 6px 10px!important;' class='js-states' multiple='multiple' name='chapter_id' onchange='onChangeAUMFilter();' id='chapter_id' >";
            roleDropdown = roleDropdown+"<option value=''>Select AMC </option>";
            <?php foreach($fund_house_list as $key=>$val){ ?>
              roleDropdown = roleDropdown+"<option value='<?php echo $val->amc_code; ?>'><?php echo $val->fund; ?></option>";
            <?php } ?>
            $( ".dataTables_length" ).after(roleDropdown);

            $("#chapter_id").select2({
                maximumSelectionLength: 5,
                placeholder:'All AMC'
            });
        });
    </script>
@endsection
@section('content')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        #stock_table_filter {
            display: none;
        }
        .form-control {
            border-radius: 0;
            border: 1px solid #cacaca !important;
            min-height: 40px;
            font-size: 12px;
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
        .input-group-append {
            margin-right: 15px;
        }
        .Search_Here {
            justify-content: center;
        }
        .input-group-text {
            margin-right:5px;
        }
        /*.select2-container {*/
        /*    width: 75% !important;*/
        /*}*/
        ::-webkit-input-placeholder{color:#7e8387}
        #rv_comparison:-ms-input-placeholder{color:#7e8387}
        ::-moz-placeholder{color:#7e8387;opacity:1}
        :-moz-placeholder{color:#7e8387;opacity:1}
        li .comp_holding::-webkit-scrollbar{width:4px;height:4px}
        li .comp_holding::-scrollbar{width:4px;height:4px}
        li .comp_holding::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.3);border-radius:5px}
        li .comp_holding::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.5)}
        .hor_scroll::-webkit-scrollbar{width:4px;height:4px}
        .hor_scroll::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.2);border-radius:5px}
        .hor_scroll::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.3)}
        .InvsHead{background:#f1f2f2;max-height:60px;border-bottom:1px solid #ddd;margin-bottom:1rem}
        .select2-dropdown li{font-size:12px;line-height:18px}
        .Search_Here{margin-bottom:1.5rem}
        #comparison_fund{margin-top:0rem}
        #comparison_fund .list-group{margin-bottom:0}
        #comparison_fund .list-group-item{min-height:122px;line-height:2}
        #comparison_fund .list-group-item h2{margin:10px 0;font-size:14px;letter-spacing:0;line-height:24px;color:#5f6469}
        #comparison_fund .comparison_fund_outer{border:0;background-color:#fff;width:100%;box-shadow:0 0 1px #888888}
        #comparison_fund .comparison_fund_inner_top{min-height:46px;padding:3px 5px}
        #comparison_fund .comparison_fund_inner_top a{color:#fff;font-size:13px;line-height: 15px; display: inline-block;}
        #comparison_fund .ct1{border:1px solid #647edd;background-color:#647edd}
        #comparison_fund .ct2{border:1px solid #20a1dc;background-color:#20a1dc}
        #comparison_fund .ct4{border:1px solid #6398de;background-color:#6398de}
        #comparison_fund .ct3{border:1px solid #3b71b9;background-color:#3b71b9}
        #comparison_fund .comparison_fund_inner_bottom{padding:2px 5px;font-size:12px;min-height:75px;border:1px solid #c0c1c1;border-top:0}
        #comparison_fund .comparison_fund_inner_bottom span{display:inline-block}
        #comparison_fund .comparison_fund_inner_bottom img{width:80px}
        #comparison_fund .comparison_outer .col-md-3{padding-right:5px;padding-left:5px}
        #comparison_fund .comparison_fund_inner_top .icon-cancel-circled2{float:right;margin-top:-14px;margin-right:-14px;font-size:20px;color:#5f6469;cursor:pointer;background:#fff;border-radius:100%; height: 20px; width: 20px;}
        #comparison_basic{margin-top:20px}
        #comparison_basic .comparison_head,
        #comparison_return .comparison_head,
        #comparison_performance_measures .comparison_head,
        #comparison_portfolio_attributes .comparison_head,
        #comparison_portfolio_market .comparison_head,
        #comparison_top_10_holdings .comparison_head,
        #comparison_Chart .comparison_head{color:#fff;background:#2d94e7;padding:8px 0 0 6px;height:35px;font-size:12px}
        #comparison_basic .comparison_head span:before,#comparison_return .comparison_head span:before,
        #comparison_performance_measures .comparison_head span:before,
        #comparison_portfolio_attributes .comparison_head span:before,
        #comparison_portfolio_market .comparison_head span:before,
        #comparison_top_10_holdings .comparison_head span:before,
        #comparison_Chart .comparison_head span:before{background:#2d94e7;border:none;content:'';display:block;height:25px;left:382px;position:absolute;top:-4px;-webkit-transform:rotate(45deg);transform:rotate(45deg);width:25px; display: none;}
        .list-group{margin-bottom:1.5rem}
        .list-group-item{border-radius:0;padding:6px 2px 6px 4px;border:1px solid #c0c1c1;height:32px}
        .comparison_bodyP1 li:nth-child(even){background:#a2d2f7}
        .comparison_bodyP2 li{text-align:center}
        .comparison_bodyP2 li:nth-child(even){background:#f1f2f2}
        #comparison_basic .comparison_bodyP2 .col-md-3,
        #comparison_return .comparison_bodyP2 .col-md-3,
        #comparison_performance_measures .comparison_bodyP2 .col-md-3,
        #comparison_portfolio_attributes .comparison_bodyP2 .col-md-3,
        #comparison_portfolio_market .comparison_bodyP2 .col-md-3,
        #comparison_top_10_holdings .comparison_bodyP2 .col-md-3{padding-right:5px;padding-left:5px}
        #comparison_fund .col-md-2,#comparison_basic .col-md-2,#comparison_return .col-md-2,
        #comparison_performance_measures .col-md-2,#comparison_portfolio_attributes .col-md-2,
        #comparison_portfolio_market .col-md-2,#comparison_top_10_holdings .col-md-2,
        #comparison_Chart .col-md-2{padding-right:5px}#comparison_top_10_holdings .comparison_bodyP1 li .comp_holding,
        #comparison_top_10_holdings .comparison_bodyP2 li .comp_holding{white-space:nowrap;overflow-x:auto;width:170px;display:inline-block;float:left;text-align:left}
        #comparison_top_10_holdings .comp_bar{border-right:1px solid #a4a9ad;display:inline;padding-left:5px}
        #comparison_top_10_holdings .comparison_bodyP2 li .comp_percentage{display:inline-block;float:right;margin-right:5px;width:35px;text-align:right}
        #comparison_Chart .list-group-item{min-height:33.3em;line-height:2}
        #comparison_Chart .comparison_bodyP2 #compare_chart{border:1px solid #c0c1c1;margin:0 -10px}
        .comparison_bodyP2 .comp_holding{white-space:nowrap;overflow-x:auto;width:220px;text-align:center}
        input.highcharts-range-selector{top:10px !important;position:absolute !important;border:0px !important;width:1px !important;height:1px !important;padding:0px !important;text-align:center !important;font-size:12px !important;margin-top:0px !important;left:705px !important}
        @media screen and (min-width: 992px) and (max-width: 1199px){
            #comparison_fund .list-group-item h2{margin:0;padding:5px}
            #comparison_fund .list-group-item{min-height:135px}
            #comparison_fund .comparison_fund_inner_top{padding:5px 3px}
            #comparison_fund .comparison_fund_inner_top a{font-size:11px;word-spacing:-1px}
            #comparison_fund .comparison_fund_inner_bottom{padding:5px 3px}
            #comparison_top_10_holdings .comparison_bodyP1 li .comp_holding,
            #comparison_top_10_holdings .comparison_bodyP2 li .comp_holding{width:135px}
            .list-group-item{padding:6px 1px 6px 2px}
            #comparison_basic .comparison_head,
            #comparison_return .comparison_head,
            #comparison_performance_measures .comparison_head,
            #comparison_portfolio_attributes .comparison_head,
            #comparison_portfolio_market .comparison_head,
            #comparison_top_10_holdings .comparison_head,
            #comparison_Chart .comparison_head{padding:8px 0 0 3px}
            #comparison_basic .comparison_head span:before,
            #comparison_return .comparison_head span:before,
            #comparison_performance_measures .comparison_head span:before,
            #comparison_portfolio_attributes .comparison_head span:before,
            #comparison_portfolio_market .comparison_head span:before,
            #comparison_top_10_holdings .comparison_head span:before,
            #comparison_Chart .comparison_head span:before{left:385px}
            #comparison_fund .comparison_fund_inner_top{min-height:52px}
            .comparison_bodyP2 .comp_holding{min-width:10em;max-width:15em}
            .highcharts-input-group{display:none}}
            .stock_table thead th {
                background: #bee5f6;
            }
            .table-bordered thead td, .table-bordered thead th {
                border-bottom-width: 1px;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: 1px solid #929292;
                font-weight: 600 !important;
            }
            .table-bordered.stock_table {
                border: 1px solid #929292;
            }
            .table-bordered.stock_table td, .table-bordered.stock_table th {
                border: 1px solid #929292;
                line-height: 15px;
                padding: 6px 5px;
                vertical-align: middle;
                font-family: "Poppins", sans-serif;
            }
            .table-bordered.stock_table td {
                padding: 9px 5px;
            }
            .table-bordered.stock_table tr td:first-child {
                text-align: left;
            }
            .table-bordered.stock_table tbody tr:nth-child(even) {background: #f0f1f6}
            .table-bordered.stock_table tbody tr:nth-child(odd) {background: #FFF}
            .fund_active_btn {
                display:block;
                position:relative;
                background: #25a8e0 !important;
                border:1px solid #121f54;
                border-radius:0 !important;
            }
            .fund_active_btn:after {
                display:block;
                position:absolute;
                height: 11px;
                width: 23px;
                content: "";
                background: url({{asset('images/activebottomarrow.png')}});
                left: 47%;
                bottom: -11px;
            }
            .fund_norm_btn {
                background: #fff !important;
                border:1px solid #121f54;
                border-radius:0 !important;
            }
            .dequitis label {
                margin-bottom: 0;
                padding-left:5px;
                margin-right: 7px;
            }
            @media screen and (min-width: 768px) and (max-width: 991px){
                #comparison_fund .comparison_fund_inner_top{padding:5px 3px}
                #comparison_fund .comparison_fund_inner_top a{font-size:9px;word-spacing:0;word-break:break-all}
                #comparison_fund .comparison_fund_inner_bottom{padding:5px 3px;font-size:9px;min-height:95px}
                li{font-size:9px}#comparison_fund .col-md-2,
                #comparison_basic .col-md-2,
                #comparison_return .col-md-2,
                #comparison_performance_measures .col-md-2,
                #comparison_portfolio_attributes .col-md-2,
                #comparison_portfolio_market .col-md-2,
                #comparison_top_10_holdings .col-md-2,
                #comparison_Chart .col-md-2{padding-right:0}
                #comparison_top_10_holdings .comparison_bodyP1 li .comp_holding,
                #comparison_top_10_holdings .comparison_bodyP2 li .comp_holding{width:90px}
                .comparison_bodyP1 .avgMkCap{line-height:12px}
                #comparison_fund .list-group-item h2{font-size:12px;padding:3px}
                #comparison_fund .list-group-item{min-height:147px}
                .list-group-item{padding:2px 0px 2px 1px}
                #comparison_basic .comparison_head,
                #comparison_return .comparison_head,
                #comparison_performance_measures .comparison_head,
                #comparison_portfolio_attributes .comparison_head,
                #comparison_portfolio_market .comparison_head,
                #comparison_top_10_holdings .comparison_head,
                #comparison_Chart .comparison_head{padding:8px 0 0 3px}
                #comparison_basic .comparison_head span:before,
                #comparison_return .comparison_head span:before,
                #comparison_performance_measures .comparison_head span:before,
                #comparison_portfolio_attributes .comparison_head span:before,
                #comparison_portfolio_market .comparison_head span:before,
                #comparison_top_10_holdings .comparison_head span:before,
                #comparison_Chart .comparison_head span:before{left:385px}
                #comparison_fund .list-group-item h1{line-height:21px}
                #comparison_Chart .list-group-item{min-height:44.3em}
                #comparison_fund .comparison_fund_inner_top{min-height:52px}
                .comparison_bodyP2 .comp_holding{min-width:10em;max-width:15em}
                .highcharts-input-group{display:none}}
                @media screen and (max-width: 767px){#rv_comparison{text-align:left;font-size:16px}
                #rv_comparison:before{margin:5em 1em 0}.area_wrap_mob{overflow-x:auto}
                #comparison_fund{width:60em}.mob_mf_com{min-width:10em;max-width:15em}
                #comparison_basic{min-width:62em}#comparison_return{min-width:62em}
                #comparison_performance_measures{min-width:62em}
                #comparison_portfolio_attributes{min-width:62em}
                #comparison_portfolio_market{min-width:62em}
                #comparison_top_10_holdings{min-width:62em}
                #comparison_top_10_holdings .comparison_bodyP2 li .comp_holding{max-width:120px}
                #comparison_Chart{min-width:62em}.comparison_head{text-align:left;font-size:14px}
                .comparison_head span{margin-top:-2em}
                #comparison_basic .comparison_head,
                #comparison_return .comparison_head,
                #comparison_performance_measures .comparison_head,
                #comparison_portfolio_attributes .comparison_head,
                #comparison_portfolio_market .comparison_head,
                #comparison_top_10_holdings .comparison_head,
                #comparison_Chart .comparison_head{width:250px;height:25px;padding-top:5px}
                #comparison_basic .comparison_head span:before,
                #comparison_return .comparison_head span:before,
                #comparison_performance_measures .comparison_head span:before,
                #comparison_portfolio_attributes .comparison_head span:before,
                #comparison_portfolio_market .comparison_head span:before,
                #comparison_top_10_holdings .comparison_head span:before,
                #comparison_Chart .comparison_head span:before{top:-3px;left:14.7rem;height:18px;width:18px}
                .comparison_bodyP2 .comp_holding{min-width:10em;max-width:15em}}
    </style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">Stocks held by Mutual Funds</h2>
                </div>
            </div>
        </div>
        <a href="#" class="btn-chat">Chat With Us</a>
    </div>

    <section class="main-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="rt-pnl" style="box-shadow: none;">
                        <div class="VideoNav" style="border: 0">
                            
                        </div>
                        
                        <div class="row mt-1">
                          <table id="mf_scanner_list">
                            
                          </table>
                        </div>
                    </div>
                </div>
            </div>
            <div style="border-bottom: 1px solid #ddd;">
                @include('frontend.mf_scanner.top_sidebar')
            </div>

            @include('frontend.mf_research.stock_held.common')
            
            <div class="row Search_Here" style="margin-top: 20px;">
              <div class="col-12 col-sm-10 col-md-10 col-lg-10 col-xl-10" >
                <form method="get" action="" id="comp_sch">
                    <div class="input-group row" style="margin-bottom: 10px;">
                        <label for="compare_category" class="col-sm-3 col-form-label">Type</label>
                        <div class="col-sm-9 dequitis" style="display: flex; align-items: center;">
                            <input type="radio"  name="type" value="Stock" checked="" @if(isset($type) && $type=='Stock') checked @endif>
                            <label>Schemewise</label>
                            <input type="radio" id="type" name="type" value="AMC" @if(isset($type) && $type=='AMC') checked @endif>
                            <label>AMCwise</label><br>
                        </div> 
                    </div>
                    <div>
                      <div class="input-group row" style="margin-bottom: 10px;">
                        <label for="compare_category" class="col-sm-3 col-form-label">Category</label>
                        <div class="col-sm-9 dequitis" style="display: flex; align-items: center;">
                            <input type="radio" id="gst_zone" name="gst_zone" value="Domestic" checked="" onchange="changeCategory();" @if(isset($gst_zone) && $gst_zone=='Domestic') checked @endif>
                            <label>Domestic Equities</label>
                            <input type="radio" name="gst_zone" value="Overseas"  onchange="changeCategory();" @if(isset($gst_zone) && $gst_zone=='Overseas') checked @endif>
                            <label>Overseas Equities</label><br>
                        </div> 
                      </div>  
                      <div class="input-group row" style="margin-bottom: 10px;">
                        <label for="compare_schemecode" class="col-sm-3 col-form-label">Search Stock</label>
                        <div class="col-sm-9">
                            <select class="form-control ui-autocomplete-input" name="compare_schemecode" id="compare_schemecode">
                                <option value=""> </option>
                                @foreach($dropdownList as $dropdown)
                                    <option value="{{$dropdown->fincode}}"  @if(isset($compare_schemecode) && $compare_schemecode==$dropdown->fincode) selected @endif>{{$dropdown->compname}}</option>
                                @endforeach
                            </select>
                        </div> 
                      </div> 
                    </div>
                    <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px; padding-bottom:10px;">
                        <button type="submit" class="btn btn-primary campairButton" id="add_btn_search">Search</button>
                    </div>   
                </form>

                <?php if($type == "Stock" || $type == "AMC"){ ?>
                    <div class="row" style="padding-bottom: 10px; font-size: 12px;">
                          
                        <div class="col-md-12 text-center pt-2 ml-3 mr-3" style="border-top: 1px solid #ccc; margin-top: 3px;">
                            <form action="{{route('frontend.stocks_held_action')}}" method="get" id="save_form_data">
                              <input type="hidden" name="page_type" id="page_type" value="">
                              <input type="hidden" name="save_title" id="save_title" value="">
                              <input type="hidden" name="schemecode_id" id="schemecode_id" value="{{$compare_schemecode}}">
                              <input type="hidden" name="gst_zone" id="gst_zone" value="{{$gst_zone}}">
                              <input type="hidden" name="type" id="sh_type" value="{{$type}}">
                              <input type="hidden" name="page_name" id="page_name" value="HELD">
                                <input type="hidden" name="shorting_id" id="shorting_id" value="">
                              <input type="hidden" name="page_length" id="page_length" value="">
                              @if (Auth::check())
                                @if($permission['is_download'])
                                    <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownload();">
                                        Download
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                        Download
                                    </button>
                                @endif
                                @if($permission['is_save'])
                                    <button class="btn btn-success btn-sm savedButton" onclick="return checkSave();">
                                        Save
                                    </button>
                                @else
                                    <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSavePermissionModal();">
                                        Save
                                    </button>
                                @endif
                               @else
                                    <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                        Download
                                    </button>
                                    <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSaveLoginModal();">
                                        Save
                                    </button>
                               @endif
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <?php if($type == "Stock"){ ?>
                    <div>
                        <table class="table text-center stock_table mfliketbl">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="4" align="center">No. of Shares</th>
                                </tr>
                                <tr>
                                    <th>Sector</th>
                                    <th>No. of Funds</th>
                                    <th>{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">{{$detail['sector']}}</td>
                                    <td align="right">{{$detail['number_of_fund']}}</td>
                                    <td align="right">{{($detail['current_month'])?custome_money_format($detail['current_month']):'-'}}</td>
                                    <td align="right">{{$detail['one_month']?custome_money_format($detail['one_month']):'-'}}</td>
                                    <td align="right">{{$detail['two_month']?custome_money_format($detail['two_month']):'-'}}</td>
                                    <td align="right">{{$detail['three_month']?custome_money_format($detail['three_month']):'-'}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table text-center stock_table mfliketbl" id="stock_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">AMC Name</th>
                                    <th rowspan="2">Scheme Name</th>
                                    <th rowspan="2">Fund Manager</th>
                                    <th colspan="4">{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                    <th colspan="3">No. of Shares</th>
                                </tr>
                                <tr>
                                    <th>AUM (in ₹ Cr)</th>
                                    <th>% of AUM</th>
                                    <th>No. of Shares</th>
                                    <th>Market Value (in Rs. Cr)</th>
                                    <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($list)){ ?>
                                    <?php foreach ($list as $key => $value) { ?>
                                        <tr>
                                            <td>{{$value->amc_code}}</td>
                                            <td align="left"><a href="{{route('frontend.factsheet_new')}}?schemecode%5B0%5D={{$value->schemecode}}" target='_blank'>{{$value->s_name}}</a></td>
                                            <td align="left">{{$value->fund_mgr1}}</td>
                                            <td align="right">{{custome_money_format($value->aum/100)}}</td>
                                            <td align="right">{{number_format((float)($value->holdpercentage), 2, '.', '')}}</td>                              
                                            <td align="right">{{custome_money_format($value->noshares)}}</td>                                            
                                            <td style="text-align:right;">{{custome_money_format($value->aum/100*$value->holdpercentage/100)}}</td>
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
                    <div class="mt-1">
                        @php
                            $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-stocks-held")->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                        @php } @endphp
                    </div>
                    <div class="">
                        Report Date : {{date('d/m/Y')}}
                    </div>
                <?php }else if($type == "AMC"){ ?>
                    
                    <div>
                        <table class="table text-center stock_table mfliketbl">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th colspan="4" align="center">No. of Shares</th>
                                </tr>
                                <tr>
                                    <th>Sector</th>
                                    <th>No. of Funds</th>
                                    <th>{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td align="left">{{$detail['sector']}}</td>
                                    <td align="right">{{$detail['number_of_fund']}}</td>
                                    <td align="right">{{custome_money_format($detail['current_month'])}}</td>
                                    <td align="right">{{custome_money_format($detail['one_month'])}}</td>
                                    <td align="right">{{custome_money_format($detail['two_month'])}}</td>
                                    <td align="right">{{custome_money_format($detail['three_month'])}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table text-center stock_table mfliketbl" id="stock_table">
                            <thead>
                                <tr>
                                    <th rowspan="2">AMC Name</th>
                                    <th rowspan="2">AMC Name</th>
                                    <th colspan="4">{{date('M d, Y', strtotime($detail['current_date']))}}</th>
                                    <th colspan="3">No. of Shares</th>
                                </tr>
                                <tr>
                                    <th>AUM (in ₹ Cr)</th>
                                    <th>% of AUM</th>
                                    <th>No. of Shares</th>
                                    <th>Market Value (in Rs. Cr)</th>
                                    <th>{{date('M d, Y', strtotime($detail['one_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['two_month_date']))}}</th>
                                    <th>{{date('M d, Y', strtotime($detail['three_month_date']))}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($list)){ ?>
                                    <?php foreach ($list as $key => $value) { ?>
                                        <tr>
                                            <td>{{$value->amc_code}}</td>
                                            <td align="left">{{$value->fund_name}}</td>
                                            <td align="right">{{custome_money_format($value->aum/100)}}</td>
                                            <td align="right">{{number_format((float)($value->holdpercentage), 2, '.', '')}}</td>                                            
                                            <td align="right">{{custome_money_format($value->noshares)}}</td>
                                            <td style="text-align:right;">{{custome_money_format($value->aum/100*$value->holdpercentage/100)}}</td>
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
                    <div class="mt-1">
                        @php
                            $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-stocks-held")->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                        @php } @endphp
                    </div>
                    <div class="">
                        Report Date : {{date('d/m/Y')}}
                    </div>
                <?php } ?>
              </div>
            </div>
            
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">
        </div>
    </section>


    <div class="modal fade" id="sentEmailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Save Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label style="margin-bottom: 0px;">Title</label>
                    <input type="text" name="modal_title" id="modal_title" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveModalData();">SAVE</button>
            </div>
        </div>
      </div>
    </div>
    
    @include('frontend.mf_scanner.modal')

    <script type="text/javascript">

        function checkError(type){
          if(type == 1){
              $("#errorBeforeLoginModal").modal('show');
          }else{
              $("#errorAfterLoginModal").modal('show');
          }  
        }

        function checkSave(){
            document.getElementById('page_type').value = "SAVE";
            var sortedCol = $('#stock_table').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#stock_table').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            $('#sentEmailModal').modal('show');
            setTimeout(function(){
                document.getElementById('modal_title').focus();
            },500);
            return false;
        }

        function saveModalData(){
            var modal_title = document.getElementById('modal_title').value;
            // console.log(modal_title); 
            // return false;
            document.getElementById('save_title').value = modal_title;
            document.getElementById('save_form_data').submit();
        }

        function checkDownload(){
            var sortedCol = $('#stock_table').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#stock_table').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            document.getElementById('page_type').value = "DOWNLOAD";
            return true;
        }
    </script>

@endsection
