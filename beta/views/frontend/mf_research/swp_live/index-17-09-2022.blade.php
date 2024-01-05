@extends('layouts.frontend')
@section('js_after')
    
    <script>
        var global_result = {};
        var is_first_time = true;
        
        $('#investment_date').datepicker({
            autoclose: true,
            endDate: '-0d'
        });
        $('#swp_start_date').datepicker({
            autoclose: true,
            endDate: '-0d'
        });
        $('#swp_end_date').datepicker({
            autoclose: true,
            endDate: '-0d'
        });

        $("#schemecode_id").select2({
            placeholder: "Search Scheme",
        });

        function renderRow(all_data){
            var table_html = ``;
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Scheme Name</td>";
            table_html = table_html+"<td>"+global_result.schemecode_details.s_name+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Amount</td>";
            table_html = table_html+"<td>"+all_data.lumpsum_investment_amount+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Date</td>";
            table_html = table_html+"<td>"+all_data.investment_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>SWP Period</td>";
            table_html = table_html+"<td>"+global_result.instalment+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of "+all_data.data_frequency+" Instalments</td>";
            table_html = table_html+"<td>"+global_result.returnData.length+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Withdrawal Amount</td>";
            table_html = table_html+"<td>"+all_data.total_withdrawal+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Market Value as on "+all_data.swp_end_date+"</td>";
            table_html = table_html+"<td>"+global_result.current_value+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>XIRR Return</td>";
            table_html = table_html+"<td>"+global_result.xirr_return+"</td>";
            table_html = table_html+"</tr>";

            document.getElementById('stock_table_header').innerHTML = table_html;

            var i = 0;

            table_html = "<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<th>NAV Date</th>";
            table_html = table_html+"<th>NAV</th>";
            table_html = table_html+"<th>Cash Flow</th>";
            table_html = table_html+"<th>Units</th>";
            table_html = table_html+"<th>Balance Units</th>";
            table_html = table_html+"<th>Market Value</th>";
            table_html = table_html+"</tr>";

            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";
            var balance_units = 0;
            var units = 0;
            global_result.returnData.forEach(function(val){
                if(i == 0){
                    balance_units = parseFloat(global_result.lumpsum_investment_amount / val.navrs).toFixed(2);
                    table_html = table_html+"<tr>";
                    table_html = table_html+"<td>"+val.date+"</td>";
                    table_html = table_html+"<td>"+val.navrs+"</td>";
                    table_html = table_html+"<td>"+global_result.lumpsum_investment_amount+"</td>";
                    table_html = table_html+"<td>"+balance_units+"</td>";
                    table_html = table_html+"<td>"+balance_units+"</td>";
                    table_html = table_html+"<td>"+global_result.lumpsum_investment_amount+"</td>";
                    table_html = table_html+"</tr>";
                }else{
                    units = parseFloat(-(-global_result.cash_flow / val.navrs)).toFixed(2);
                    balance_units = parseFloat(balance_units - units).toFixed(2);
                    table_html = table_html+"<tr>";
                    table_html = table_html+"<td>"+val.date+"</td>";
                    table_html = table_html+"<td>"+val.navrs+"</td>";
                    table_html = table_html+"<td>-"+global_result.cash_flow+"</td>";
                    table_html = table_html+"<td>"+units+"</td>";
                    table_html = table_html+"<td>"+balance_units+"</td>";
                    table_html = table_html+"<td>"+parseFloat(balance_units * val.navrs).toFixed(2)+"</td>";
                    table_html = table_html+"</tr>";
                }
                i++;             
            })

            table_html = table_html+"</tbody>";

            if(!is_first_time){
                $("#stock_table").dataTable().fnDestroy();
            }else{
                is_first_time = false;
            }
            document.getElementById('stock_table').innerHTML = table_html;

            $('#stock_table').dataTable({
                "bPaginate": false,
                "searching": false,   
                "order": [[ 0, "asc" ]]
            });
        }

        function callAPI(all_data){
            console.log(all_data);
            $.ajax({
                url: "{{ url('/mf-swp-live-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    global_result = result;
                    renderRow(all_data);
                }
            });
        }

        function searchData(){
            var data = {};
            data._token = "{{ csrf_token() }}";
            data.type = 1;
            data.lumpsum_investment_amount = document.getElementById("lumpsum_investment_amount").value;
            data.investment_date = document.getElementById("investment_date").value;
            data.data_frequency = document.getElementById("data_frequency").value;
            data.swp_amount = document.getElementById("swp_amount").value;
            data.swp_start_date = document.getElementById("swp_start_date").value;
            data.swp_end_date = document.getElementById("swp_end_date").value;
            data.schemecode_id = document.getElementById("schemecode_id").value;

            document.getElementById("lumpsum_investment_amount_error").innerHTML = "";
            document.getElementById("investment_date_error").innerHTML = "";
            document.getElementById("data_frequency_error").innerHTML = "";
            document.getElementById("swp_amount_error").innerHTML = "";
            document.getElementById("swp_start_date_error").innerHTML = "";
            document.getElementById("swp_end_date_error").innerHTML = "";
            document.getElementById("schemecode_id_error").innerHTML = "";

            var returns = true;

            if(!data.lumpsum_investment_amount){
                document.getElementById("lumpsum_investment_amount_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.investment_date){
                document.getElementById("investment_date_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.data_frequency){
                document.getElementById("data_frequency_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.swp_amount){
                document.getElementById("swp_amount_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.swp_start_date){
                document.getElementById("swp_start_date_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.swp_end_date){
                document.getElementById("swp_end_date_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.schemecode_id){
                document.getElementById("schemecode_id_error").innerHTML = "Required";
                returns = false;
            }

            if(returns){
                callAPI(data);
            }else{
                return false;
            }
        }
    </script>
@endsection
@section('content')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap" rel="stylesheet">
    <style>
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
        li{font-size:12px;line-height:18px}
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
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">SWP Historical</h2>
                </div>
            </div>
        </div>
        <a href="#" class="btn-chat">Chat With Us</a>
    </div>

    <section class="main-sec">
        <div class="container">
            <div style="border-bottom: 1px solid #ddd;">
                @include('frontend.mf_scanner.top_sidebar')
            </div>
            <div class="row Search_Here" style="margin-top: 20px;">
                <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" >
                    <form action="" method="post" id="comp_sch" >
                        @csrf
                        <div id="categorywise_type" style="">
                            <div>
                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">Lumpsum Investment Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="lumpsum_investment_amount" id="lumpsum_investment_amount" class="form-control" value="500000">
                                        <em id="lumpsum_investment_amount_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="plan_id" class="col-sm-3 col-form-label">Investment Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="investment_date" id="investment_date" value="03/11/2021">
                                        <em id="investment_date_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="data_frequency" class="col-sm-3 col-form-label">SWP Frequency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="investment_date" id="data_frequency">
                                            <option value="">Select Data Frequency</option>
                                            @foreach($data_frequency as $dropdown)
                                                <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                            @endforeach
                                        </select>
                                        <em id="data_frequency_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">SWP Amount / %</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_amount" id="swp_amount" class="form-control" value="0.50">
                                        <em id="swp_amount_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">SWP Start Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_start_date" id="swp_start_date" class="form-control" value="03/11/2022">
                                        <em id="swp_start_date_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">SWP End Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_end_date" id="swp_end_date" class="form-control" value="08/29/2022">
                                        <em id="swp_end_date_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">Select Scheme</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="schemecode_id" id="schemecode_id" onchange="">
                                            <option value=""> </option>
                                            @foreach($dropdownList as $dropdown)
                                                <option value="{{$dropdown->schemecode}}" >{{$dropdown->s_name}}</option>
                                            @endforeach
                                        </select>
                                        <em id="schemecode_id_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                                    <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="searchData();">Submit</button>
                                </div>  
                            </div> 
                        </div>
                    </form>
                </div>
            </div>

            <div class="row Search_Here" style="margin-top: 20px;">
                <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" >
                    <div>
                        <table id="stock_table_header" class="table text-center stock_table mfliketbl">
                        
                        </table>
                    </div>
                    <div>
                        <table id="stock_table" class="table text-center stock_table mfliketbl">
                            
                        </table>
                    </div>
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

        function checkSave(){
            document.getElementById('page_type').value = "SAVE";
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
            document.getElementById('page_type').value = "DOWNLOAD";
            return true;
        }
    </script>

@endsection
