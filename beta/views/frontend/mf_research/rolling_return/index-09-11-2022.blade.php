@extends('layouts.frontend')
@section('js_after')
    
    <script>
        var global_result = [];
        var is_first_time = true;
        var global_scheme_data = [];
        var global_scheme = [];

        var global_retuen_list = [
            {"id":"1","name":"1 Day","is_checked":0,"key_name":"1dayret"},
            {"id":"2","name":"7 Day","is_checked":0,"key_name":"1weekret"},
            {"id":"3","name":"1 Month","is_checked":0,"key_name":"1monthret"},
            {"id":"4","name":"3 Month","is_checked":0,"key_name":"3monthret"},
            {"id":"5","name":"6 Month","is_checked":0,"key_name":"6monthret"},
            {"id":"6","name":"1 Year","is_checked":1,"key_name":"1yearret"},
            {"id":"7","name":"2 Year","is_checked":0,"key_name":"2yearret"},
            {"id":"8","name":"3 Year","is_checked":1,"key_name":"3yearret"},
            {"id":"9","name":"5 Year","is_checked":1,"key_name":"5yearret"},
            {"id":"10","name":"10 Year","is_checked":0,"key_name":"10yearret"}
          ];

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

        $.fn.select2.defaults.set('matcher', function(params, data) {
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }
    
            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }
    
            var words = params.term.toUpperCase().split(" ");
    
            for (var i = 0; i < words.length; i++) {
                if (data.text.toUpperCase().indexOf(words[i]) < 0) {
                    return null;
                }
            }
    
            return data;
        });

        $("#category_id").select2({
            placeholder: "Search Category",
        });
        $("#plan_id").select2({
            placeholder: "Search Plan",
        });
        $("#period1_id").select2({
            placeholder: "Search Period",
        });
        $("#period2_id").select2({
            placeholder: "Search Period",
        });
        $("#period3_id").select2({
            placeholder: "Search Period",
        });
        $("#data_frequency").select2({
            placeholder: "Search Data Frequency",
        });
        $("#month").select2({
            placeholder: "Search Month",
        });
        $("#year").select2({
            placeholder: "Search Year",
        });

        $("#s_plan_id").select2({
            placeholder: "Search Plan",
        });
        $("#s_schemecode_id").select2({
            placeholder: "Search Scheme",
        });
        $("#s_period1_id").select2({
            placeholder: "Search Period",
        });
        $("#s_period2_id").select2({
            placeholder: "Search Period",
        });
        $("#s_period3_id").select2({
            placeholder: "Search Period",
        });
        $("#s_data_frequency").select2({
            placeholder: "Search Data Frequency",
        });
        $("#s_month").select2({
            placeholder: "Search Month",
        });
        $("#s_year").select2({
            placeholder: "Search Year",
        });

        function changeType(type){
            if(type == 1){
                document.getElementById("schemecode_ids").value = "";
                document.getElementById("schemewise_type").style.display = "none";
                document.getElementById("categorywise_type").style.display = "block";
            }else{
                document.getElementById("schemecode_ids").value = "";
                document.getElementById("schemewise_type").style.display = "block";
                document.getElementById("categorywise_type").style.display = "none";
            }
        }

        function changeCategory(){

        }

        function changePlan(){
            
        }

        function changePeriod(type){
            
        }

        function changeCategory(){
            
        }

        function changeCategory(){
            
        }

        function changeScheme(){
            
        }
        
        function getPeriodName(id){
            var returnValue = "";
            if(id == 1){
                returnValue = "1 Month";
            }else if(id == 2){
                returnValue = "3 Month";
            }else if(id == 3){
                returnValue = "6 Month";
            }else if(id == 4){
                returnValue = "1 Year";
            }else if(id == 5){
                returnValue = "3 Year";
            }else if(id == 6){
                returnValue = "5 Year";
            }else if(id == 7){
                returnValue = "10 Year";
            }
            return returnValue;
        }

        function renderRow(all_data){
            var table_html = ``;
            table_html = "<thead>";
            table_html = table_html+"<tr>";
            // table_html = table_html+"<th></th>";
            table_html = table_html+'<th rowspan="2" style="text-align: left;">Scheme</th>';
            if(all_data.period1_id){
                table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period1_id)+'</th>';
            }
            if(all_data.period2_id){
                table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period2_id)+'</th>';
            }
            if(all_data.period3_id){
                table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period3_id)+'</th>';
            }

            table_html = table_html+"</tr>";

            table_html = table_html+"<tr>";
            if(all_data.period1_id){
                table_html = table_html+'<th >AVG</th>';
                table_html = table_html+'<th >MAX</th>';
                table_html = table_html+'<th >MIN</th>';
            }
            if(all_data.period2_id){
                table_html = table_html+'<th >AVG</th>';
                table_html = table_html+'<th >MAX</th>';
                table_html = table_html+'<th >MIN</th>';
            }
            if(all_data.period3_id){
                table_html = table_html+'<th >AVG</th>';
                table_html = table_html+'<th >MAX</th>';
                table_html = table_html+'<th >MIN</th>';
            }
            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";
            var array_index= 0;
            
            global_result.forEach(function(val){
                table_html = table_html+"<tr>";
                table_html = table_html+'<td  style="text-align: left;">'+val.s_name+'</td>';
                if(all_data.period1_id){
                    table_html = table_html+'<td >'+Number((parseFloat(val.avg_1)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.max_1)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.min_1)).toFixed(2))+'</td>';
                }
                if(all_data.period2_id){
                    table_html = table_html+'<td >'+Number((parseFloat(val.avg_2)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.max_2)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.min_2)).toFixed(2))+'</td>';
                }
                if(all_data.period3_id){
                    table_html = table_html+'<td >'+Number((parseFloat(val.avg_3)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.max_3)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(val.min_3)).toFixed(2))+'</td>';
                }
                table_html = table_html+"</tr>";
                array_index = array_index + 1;
            });

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

        function renderSchemeRow(all_data,result){
            var table_html = ``;
            if(is_first_time){
                table_html = "<thead>";
                table_html = table_html+"<tr>";
                // table_html = table_html+"<td></td>";
                table_html = table_html+'<th rowspan="2" style="text-align: left;">Scheme</th>';
                //table_html = table_html+'<th rowspan="2" style="text-align: left;">Category</th>';
                
                if(all_data.period1_id){
                    table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period1_id)+'</th>';
                }
                if(all_data.period2_id){
                    table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period2_id)+'</th>';
                }
                if(all_data.period3_id){
                    table_html = table_html+'<th colspan="3" style="text-align:center;">'+getPeriodName(all_data.period3_id)+'</th>';
                }
    
                table_html = table_html+"</tr>";
                table_html = table_html+"<tr>";
                if(all_data.period1_id){
                    table_html = table_html+'<th >AVG</th>';
                    table_html = table_html+'<th >MAX</th>';
                    table_html = table_html+'<th >MIN</th>';
                }
                if(all_data.period2_id){
                    table_html = table_html+'<th >AVG</th>';
                    table_html = table_html+'<th >MAX</th>';
                    table_html = table_html+'<th >MIN</th>';
                }
                if(all_data.period3_id){
                    table_html = table_html+'<th >AVG</th>';
                    table_html = table_html+'<th >MAX</th>';
                    table_html = table_html+'<th >MIN</th>';
                }
                table_html = table_html+"</tr>";
                table_html = table_html+"</thead>";

                table_html = table_html+"<tbody id='mf_scanner_body'>";
            }
            
            if(result.s_name){
                
                table_html = table_html+"<tr>";
                table_html = table_html+'<td  style="text-align: left;">'+result.s_name+'</td>';
                if(all_data.period1_id){
                    table_html = table_html+'<td >'+Number((parseFloat(result.avg_1)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.max_1)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.min_1)).toFixed(2))+'</td>';
                }
                if(all_data.period2_id){
                    table_html = table_html+'<td >'+Number((parseFloat(result.avg_2)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.max_2)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.min_2)).toFixed(2))+'</td>';
                }
                if(all_data.period3_id){
                    table_html = table_html+'<td >'+Number((parseFloat(result.avg_3)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.max_3)).toFixed(2))+'</td>';
                    table_html = table_html+'<td >'+Number((parseFloat(result.min_3)).toFixed(2))+'</td>';
                }
                table_html = table_html+"</tr>";

                global_scheme_data.push(result);
            }else{
                alert("Data not found");
            }

            if(is_first_time){
                table_html = table_html+"</tbody>";
            }
            
            if(!is_first_time){
                $("#stock_table").dataTable().fnDestroy();
                $("#mf_scanner_body").append(table_html);
            }else{
                is_first_time = false;
                document.getElementById('stock_table').innerHTML = table_html;
            }
            

            $('#stock_table').dataTable({
                "bPaginate": false,
                "searching": false,   
                "order": [[ 1, "asc" ]]
            });
        }

        function callAPI(all_data){
            console.log(all_data);
            $.ajax({
                url: "{{ url('/mf-rolling-return-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    if(all_data.type == 1){
                        global_result = result;
                        renderRow(all_data);
                    }else if(all_data.type == 2){
                        renderSchemeRow(all_data,result[0]);
                    }
                }
            });
        }

        function searchData(){
            var data = {};
            data._token = "{{ csrf_token() }}";
            data.type = 1;
            data.category_id = document.getElementById("category_id").value;
            data.plan_id = document.getElementById("plan_id").value;
            data.period1_id = document.getElementById("period1_id").value;
            data.period2_id = document.getElementById("period2_id").value;
            data.period3_id = document.getElementById("period3_id").value;
            data.data_frequency = document.getElementById("data_frequency").value;
            data.month = document.getElementById("month").value;
            data.year = document.getElementById("year").value;

            document.getElementById("category_id_error").innerHTML = "";
            document.getElementById("plan_id_error").innerHTML = "";
            document.getElementById("period1_id_error").innerHTML = "";
            document.getElementById("data_frequency_error").innerHTML = "";
            document.getElementById("month_error").innerHTML = "";
            document.getElementById("year_error").innerHTML = "";

            var returns = true;

            if(!data.category_id){
                document.getElementById("category_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.plan_id){
                document.getElementById("plan_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.period1_id || !data.period1_id || !data.period1_id){
                document.getElementById("period1_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.data_frequency){
                document.getElementById("data_frequency_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.month){
                document.getElementById("month_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.year){
                document.getElementById("year_error").innerHTML = "Required";
                returns = false;
            }

            if(returns){
                callAPI(data);
            }else{
                return false;
            }
        }

        function resetForm(){
            document.getElementById("s_plan_id").value = "";
            document.getElementById("select2-s_plan_id-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_schemecode_id").value = "";
            document.getElementById("select2-s_schemecode_id-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_period1_id").value = "";
            document.getElementById("select2-s_period1_id-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_period2_id").value = "";
            document.getElementById("select2-s_period2_id-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_period3_id").value = "";
            document.getElementById("select2-s_period3_id-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_sip_amount").value = "";
            document.getElementById("s_data_frequency").value = "";
            document.getElementById("select2-s_data_frequency-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_month").value = "";
            document.getElementById("select2-s_month-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
            document.getElementById("s_year").value = "";
            document.getElementById("select2-s_year-container").innerHTML = '<span class="select2-selection__placeholder">Search Plan</span>';
        }

        function addData(){
            var data = {};
            data._token = "{{ csrf_token() }}";
            data.type = 2;
            data.schemecode_id = document.getElementById("s_schemecode_id").value;
            data.plan_id = document.getElementById("s_plan_id").value;
            data.period1_id = document.getElementById("s_period1_id").value;
            data.period2_id = document.getElementById("s_period2_id").value;
            data.period3_id = document.getElementById("s_period3_id").value;
            data.data_frequency = document.getElementById("s_data_frequency").value;
            data.month = document.getElementById("s_month").value;
            data.year = document.getElementById("s_year").value;
            // console.log(data);
            var returns = true;

            document.getElementById("s_schemecode_id_error").innerHTML = "";
            document.getElementById("s_plan_id_error").innerHTML = "";
            document.getElementById("s_period1_id_error").innerHTML = "";
            document.getElementById("s_data_frequency_error").innerHTML = "";
            document.getElementById("s_month_error").innerHTML = "";
            document.getElementById("s_year_error").innerHTML = "";

            if(!data.schemecode_id){
                document.getElementById("s_schemecode_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.plan_id){
                document.getElementById("s_plan_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.period1_id || !data.period1_id || !data.period1_id){
                document.getElementById("s_period1_id_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.data_frequency){
                document.getElementById("s_data_frequency_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.month){
                document.getElementById("s_month_error").innerHTML = "Required";
                returns = false;
            }
            if(!data.year){
                document.getElementById("s_year_error").innerHTML = "Required";
                returns = false;
            }

            if(returns){
                global_scheme.push(data.schemecode_id);
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
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">ROLLING RETURN</h2>
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
                    <form method="get" action="{{route('frontend.rolling_return_action')}}" id="comp_sch" >
                        <input type="hidden" id="schemecode_ids" name="schemecode_ids" value="">
                        <div class="input-group row" style="margin-bottom: 10px;">
                            <label for="compare_category" class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9 dequitis" style="display: flex; align-items: center;">
                            <input type="radio" id="type" name="type" value="1" checked="" onchange="changeType(1);" @if(isset($type) && $type==1) checked @endif>
                            <label>Categorywise</label>
                            <input type="radio" name="type" value="2"  onchange="changeType(2);" @if(isset($type) && $type==2) checked @endif>
                            <label>Schemewise</label><br>
                            </div> 
                        </div>  
                        <div id="categorywise_type" style="">
                            <div>
                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="category_id" class="col-sm-3 col-form-label">Select Category</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="category_id" id="category_id" onchange="changeCategory();">
                                            <option value="">Select</option>
                                            @foreach($category_list as $dropdown)
                                                <option value="{{$dropdown->classcode}}">{{$dropdown->classname}}</option>
                                            @endforeach
                                        </select>
                                        <em id="category_id_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="plan_id" class="col-sm-3 col-form-label">Select Plan</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="plan_id" id="plan_id" onchange="changePlan();">
                                            <option value="">Select</option>
                                            @foreach($plan_list as $result)
                                                <option value="{{$result->plan_code}}"><?php echo ($result->planname)?$result->planname:$result->plan;?></option>
                                            @endforeach
                                        </select>  
                                        <em id="plan_id_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="data_frequency" class="col-sm-3 col-form-label">Data Frequency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="data_frequency" id="data_frequency">
                                            <option value="">Select Data Frequency</option>
                                            @foreach($data_frequency as $dropdown)
                                                <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                            @endforeach
                                        </select>
                                        <em id="data_frequency_error" class="error"></em>  
                                    </div> 
                                </div>



                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="period_id" class="col-sm-3 col-form-label">Select Period</label>
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="period1_id" id="period1_id" onchange="changePeriod(1);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>  
                                        <em id="period1_id_error" class="error"></em>  
                                    </div> 
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="period2_id" id="period2_id" onchange="changePeriod(2);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>
                                    </div> 
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="period3_id" id="period3_id" onchange="changePeriod(3);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>
                                    </div> 
                                </div>

                                <div class="input-group row">
                                    <label for="as_on_date" class="col-sm-3 col-form-label">Report As On Date</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select class="form-control ui-autocomplete-input" name="month" id="month">
                                                    <option value="">Select Month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <em id="month_error" class="error"></em>  
                                            </div>
                                            <div class="col-sm-6">
                                                <select class="form-control ui-autocomplete-input" name="year" id="year">
                                                    <option value="">Select Year</option>
                                                    @for($i=date('Y'); $i>=1990; $i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <em id="year_error" class="error"></em>  
                                            </div>
                                        </div>
                                    </div> 
                                </div>
                                <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                                    <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="searchData();">Submit</button>
                                    <button type="submit" class="btn btn-primary" id="download_category_button" onclick="downloadButton();" style="margin-left: 20px;">Download</button>
                                </div>  
                            </div> 
                        </div>
                        <div id="schemewise_type" style="display: none;">
                            <div>
                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="plan_id" class="col-sm-3 col-form-label">Select Plan</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="s_plan_id" id="s_plan_id" onchange="changePlan();">
                                            <option value="">Select</option>
                                            @foreach($plan_list as $result)
                                                <option value="{{$result->plan_code}}"><?php echo ($result->planname)?$result->planname:$result->plan;?></option>
                                            @endforeach
                                        </select>  
                                        <em id="s_plan_id_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="schemecode_id" class="col-sm-3 col-form-label">Select Scheme</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="s_schemecode_id" id="s_schemecode_id" onchange="changeScheme();">
                                            <option value=""> </option>
                                            @foreach($dropdownList as $dropdown)
                                                <option value="{{$dropdown->schemecode}}">{{$dropdown->s_name}}</option>
                                            @endforeach
                                        </select>
                                        <em id="s_schemecode_id_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="s_data_frequency" class="col-sm-3 col-form-label">Data Frequency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="s_data_frequency" id="s_data_frequency">
                                            <option value="">Select</option>
                                            @foreach($data_frequency as $dropdown)
                                                <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                            @endforeach
                                        </select>
                                        <em id="s_data_frequency_error" class="error"></em>  
                                    </div>
                                </div>

                                <div class="input-group row" style="margin-bottom: 10px;">
                                    <label for="s_period_id" class="col-sm-3 col-form-label">Select Period</label>
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="s_period1_id" id="s_period1_id" onchange="changePeriod(1);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>  
                                        <em id="s_period1_id_error" class="error"></em>  
                                    </div> 
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="s_period2_id" id="s_period2_id" onchange="changePeriod(2);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>  
                                    </div> 
                                    <div class="col-sm-3">
                                        <select class="form-control ui-autocomplete-input" name="s_period3_id" id="s_period3_id" onchange="changePeriod(3);">
                                            <option value="">Select</option>
                                            <option value="1">1 Month</option>
                                            <option value="2">3 Month</option>
                                            <option value="3">6 Month</option>
                                            <option value="4">1 Year</option>
                                            <option value="5">3 Year</option>
                                            <option value="6">5 Year</option>
                                            <option value="7">10 Year</option>
                                        </select>  
                                    </div> 
                                </div>
                                
                                <div class="input-group row">
                                    <label for="as_on_date" class="col-sm-3 col-form-label">Report As On Date</label>
                                    <div class="col-sm-9">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select class="form-control ui-autocomplete-input" name="s_month" id="s_month">
                                                    <option value="">Select Month</option>
                                                    <option value="1">January</option>
                                                    <option value="2">February</option>
                                                    <option value="3">March</option>
                                                    <option value="4">April</option>
                                                    <option value="5">May</option>
                                                    <option value="6">June</option>
                                                    <option value="7">July</option>
                                                    <option value="8">August</option>
                                                    <option value="9">September</option>
                                                    <option value="10">October</option>
                                                    <option value="11">November</option>
                                                    <option value="12">December</option>
                                                </select>
                                                <em id="s_month_error" class="error"></em>  
                                            </div>
                                            <div class="col-sm-6">
                                                <select class="form-control ui-autocomplete-input" name="s_year" id="s_year">
                                                    <option value="">Select Year</option>
                                                    @for($i=date('Y'); $i>=1990; $i--)
                                                        <option value="{{$i}}">{{$i}}</option>
                                                    @endfor
                                                </select>
                                                <em id="s_year_error" class="error"></em>  
                                            </div>
                                        </div>
                                    </div> 
                                </div>

                                <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                                    <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="addData();">Add</button>
                                    <button type="submit" class="btn btn-primary" id="download_scheme_button" onclick="downloadButton();" style="margin-left: 20px;">Download</button>
                                </div>  
                            </div>
                        </div>
                         
                              
                    </form>
                </div>
            </div>

            <div class="row Search_Here" style="margin-top: 20px;">
                <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" >
                    <table id="stock_table" class="table text-center stock_table mfliketbl">
                    
                    </table>
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
