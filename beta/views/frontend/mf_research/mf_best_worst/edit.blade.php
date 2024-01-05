@extends('layouts.frontend')

@section('js_after')
    <script type="text/javascript">
        var modal_flag = true;
        @if (Auth::check())
            modal_flag = false;
        @endif

        if(modal_flag){
            $("#permissionModal").modal('show');
        }


        $("#is_comments").click( function(){
            if( $(this).is(':checked') ){
                $('#comment_textarea_div').css('display','block');
            }else {
                $('#comment_textarea_div').css('display','none');
                $('textarea[name="comments"]').val("");
            }
        });
        
        function changeNote(){
            var note = document.getElementById('comments').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }


        @if($comments)
            $('#comment_textarea_div').css('display','block');
        @else
            $('#comment_textarea_div').css('display','none');
        @endif
        
        changeNote();
    </script>

    <script type="text/javascript">
        var global_result = {};
        var global_best_result = [];
        var global_worst_result = [];
        var is_first_time = true;

        Number.prototype.toFixedNoRounding = function(n) {
            const reg = new RegExp("^-?\\d+(?:\\.\\d{0," + n + "})?", "g")
            const a = this.toString().match(reg)[0];
            const dot = a.indexOf(".");
            if (dot === -1) { // integer, insert decimal dot and pad up zeros
                return a + "." + "0".repeat(n);
            }
            const b = n - (a.length - dot) + 1;
            return b > 0 ? (a + "0".repeat(b)) : a;
        }

        function getPlanData(){
            var filter_brand_list_id = document.getElementById('plan_return').querySelectorAll('input[type=checkbox]:checked');
            var return_list = "";
            filter_brand_list_id.forEach(function(val){
                if(return_list){
                    return_list = return_list+","+$(val).val();
                }else{
                    return_list = $(val).val();
                }
            });
            return return_list;
        }

        function getCurrentQueryPeriod(){
            var query_period = document.getElementById("query_period").value;

            var background_color = {
                "oneday":"",
                "oneweek":"",
                "onemonth":"",
                "threemonth":"",
                "sixmonth":"",
                "oneyear":"",
                "twoyear":"",
                "threeyear":"",
                "fiveyear":"",
                "tenyear":""
            }

            if(query_period == "1dayret"){
                background_color.oneday = "style='background:#a2d2f7';";
            }else if(query_period == "1weekret"){
                background_color.oneweek = "style='background:#a2d2f7';";
            }else if(query_period == "1monthret"){
                background_color.onemonth = "style='background:#a2d2f7';";
            }else if(query_period == "3monthret"){
                background_color.threemonth = "style='background:#a2d2f7';";
            }else if(query_period == "6monthret"){
                background_color.sixmonth = "style='background:#a2d2f7';";
            }else if(query_period == "1yrret"){
                background_color.oneyear = "style='background:#a2d2f7';";
            }else if(query_period == "2yearret"){
                background_color.twoyear = "style='background:#a2d2f7';";
            }else if(query_period == "3yearret"){
                background_color.threeyear = "style='background:#a2d2f7';";
            }else if(query_period == "5yearret"){
                background_color.fiveyear = "style='background:#a2d2f7';";
            }else if(query_period == "10yret" || query_period == "10yearret" ){
                background_color.tenyear = "style='background:#a2d2f7';";
            }

            return background_color;
        }

        function renderTableView(){

            var colorBackground = getCurrentQueryPeriod();

            table_html = "<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Scheme</td>";
            table_html = table_html+"<td "+colorBackground.oneday+">1 Day</td>";
            table_html = table_html+"<td "+colorBackground.oneweek+">1 Week</td>";
            table_html = table_html+"<td "+colorBackground.onemonth+">1 Month</td>";
            table_html = table_html+"<td "+colorBackground.threemonth+">3 Month</td>";
            table_html = table_html+"<td "+colorBackground.sixmonth+">6 Month</td>";
            table_html = table_html+"<td "+colorBackground.oneyear+">1 Year</td>";
            table_html = table_html+"<td "+colorBackground.twoyear+">2 Year</td>";
            table_html = table_html+"<td "+colorBackground.threeyear+">3 Year</td>";
            table_html = table_html+"<td "+colorBackground.fiveyear+">5 Year</td>";
            table_html = table_html+"<td "+colorBackground.tenyear+">10 Year</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";

            table_html = table_html+"<tr>";

            table_html = table_html+"<td><b>Category Average</b></td>";

            if(global_result['1dayret'] && global_result['1dayret'] != 0){
                glo_aum = parseFloat(global_result['1dayret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.oneday+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                  table_html = table_html+"<td "+colorBackground.oneday+">-</td>";
            }
            if(global_result['1weekret'] && global_result['1weekret'] != 0){
                glo_aum = parseFloat(global_result['1weekret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.oneweek+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.oneweek+">-</td>";
            }
            if(global_result['1monthret'] && global_result['1monthret'] != 0){
                glo_aum = parseFloat(global_result['1monthret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.onemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.onemonth+">-</td>";
            }
            if(global_result['3monthret'] && global_result['3monthret'] != 0){
                glo_aum = parseFloat(global_result['3monthret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.threemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.threemonth+">-</td>";
            }
            if(global_result['6monthret'] && global_result['6monthret'] != 0){
                glo_aum = parseFloat(global_result['6monthret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.sixmonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.sixmonth+">-</td>";
            }
            if(global_result['1yrret'] && global_result['1yrret'] != 0){
                glo_aum = parseFloat(global_result['1yrret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.oneyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.oneyear+">-</td>";
            }
            if(global_result['2yearret'] && global_result['2yearret'] != 0){
                glo_aum = parseFloat(global_result['2yearret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.twoyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.twoyear+">-</td>";
            }
            if(global_result['3yearret'] && global_result['3yearret'] != 0){
                glo_aum = parseFloat(global_result['3yearret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.threeyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.threeyear+">-</td>";
            }
            if(global_result['5yearret'] && global_result['5yearret'] != 0){
                glo_aum = parseFloat(global_result['5yearret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.fiveyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.fiveyear+">-</td>";
            }
            if(global_result['10yearret'] && global_result['10yearret'] != 0){
                glo_aum = parseFloat(global_result['10yearret']);
                glo_aum = Number((glo_aum).toFixed(2));
                table_html = table_html+"<td "+colorBackground.tenyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
            }else{
                table_html = table_html+"<td "+colorBackground.tenyear+">-</td>";
            }
            table_html = table_html+"</tr>";

            var criteria = document.querySelector('input[name="criteria"]:checked').value;

            if(criteria == 1 || criteria == 3){
                table_html = table_html+"<tr>";
                table_html = table_html+"<td colspan='11' style='text-align:center; background:#16a1db; color:#fff; font-size:13px;padding:7px;'>Best Performing Scheme</td>";
                table_html = table_html+"</tr>";

                global_best_result.forEach(function(val){
                    table_html = table_html+"<tr>";
                    if(val.s_name){
                        table_html = table_html+"<td style='text-align:left;'>"+val.s_name+"</td>";
                    }else{
                        table_html = table_html+"<td class='text-align:left;'></td>";
                    }
                    if(val['1dayret'] && val['1dayret'] != 0){
                        glo_aum = parseFloat(val['1dayret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneday+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                          table_html = table_html+"<td "+colorBackground.oneday+">-</td>";
                    }
                    if(val['1weekret'] && val['1weekret'] != 0){
                        glo_aum = parseFloat(val['1weekret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneweek+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.oneweek+">-</td>";
                    }
                    if(val['1monthret'] && val['1monthret'] != 0){
                        glo_aum = parseFloat(val['1monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.onemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.onemonth+">-</td>";
                    }
                    if(val['3monthret'] && val['3monthret'] != 0){
                        glo_aum = parseFloat(val['3monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.threemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.threemonth+">-</td>";
                    }
                    if(val['6monthret'] && val['6monthret'] != 0){
                        glo_aum = parseFloat(val['6monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.sixmonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.sixmonth+">-</td>";
                    }
                    if(val['1yrret'] && val['1yrret'] != 0){
                        glo_aum = parseFloat(val['1yrret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.oneyear+">-</td>";
                    }
                    if(val['2yearret'] && val['2yearret'] != 0){
                        glo_aum = parseFloat(val['2yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.twoyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.twoyear+">-</td>";
                    }
                    if(val['3yearret'] && val['3yearret'] != 0){
                        glo_aum = parseFloat(val['3yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.threeyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.threeyear+">-</td>";
                    }
                    if(val['5yearret'] && val['5yearret'] != 0){
                        glo_aum = parseFloat(val['5yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.fiveyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.fiveyear+">-</td>";
                    }
                    if(val['10yret'] && val['10yret'] != 0){
                        glo_aum = parseFloat(val['10yret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.tenyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.tenyear+">-</td>";
                    }
                    table_html = table_html+"</tr>";
                });
            }

            if(criteria == 2 || criteria == 3){
                table_html = table_html+"<tr>";
                table_html = table_html+"<td colspan='11' style='text-align:center; background:#16a1db; color:#fff; font-size:13px;padding:7px;'>Worst Performing Scheme</td>";
                table_html = table_html+"</tr>";

                global_worst_result.forEach(function(val){
                    table_html = table_html+"<tr>";
                    if(val.s_name){
                        table_html = table_html+"<td style='text-align:left;'>"+val.s_name+"</td>";
                    }else{
                        table_html = table_html+"<td style='text-align:left;'></td>";
                    }
                    if(val['1dayret'] && val['1dayret'] != 0){
                        glo_aum = parseFloat(val['1dayret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneday+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                          table_html = table_html+"<td "+colorBackground.oneday+">-</td>";
                    }
                    if(val['1weekret'] && val['1weekret'] != 0){
                        glo_aum = parseFloat(val['1weekret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneweek+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.oneweek+">-</td>";
                    }
                    if(val['1monthret'] && val['1monthret'] != 0){
                        glo_aum = parseFloat(val['1monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.onemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.onemonth+">-</td>";
                    }
                    if(val['3monthret'] && val['3monthret'] != 0){
                        glo_aum = parseFloat(val['3monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.threemonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.threemonth+">-</td>";
                    }
                    if(val['6monthret'] && val['6monthret'] != 0){
                        glo_aum = parseFloat(val['6monthret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.sixmonth+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.sixmonth+">-</td>";
                    }
                    if(val['1yrret'] && val['1yrret'] != 0){
                        glo_aum = parseFloat(val['1yrret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.oneyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.oneyear+">-</td>";
                    }
                    if(val['2yearret'] && val['2yearret'] != 0){
                        glo_aum = parseFloat(val['2yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.twoyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.twoyear+">-</td>";
                    }
                    if(val['3yearret'] && val['3yearret'] != 0){
                        glo_aum = parseFloat(val['3yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.threeyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.threeyear+">-</td>";
                    }
                    if(val['5yearret'] && val['5yearret'] != 0){
                        glo_aum = parseFloat(val['5yearret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.fiveyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.fiveyear+">-</td>";
                    }
                    if(val['10yret'] && val['10yret'] != 0){
                        glo_aum = parseFloat(val['10yret']);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td "+colorBackground.tenyear+">"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td "+colorBackground.tenyear+">-</td>";
                    }
                    table_html = table_html+"</tr>";
                });
            }

            table_html = table_html+"</tbody>";

            document.getElementById('mf_scanner_list').innerHTML = table_html;
            
        }

        function checkSave(){
            document.getElementById('page_type').value = "SAVE";
            document.getElementById('f_query_period').value = document.getElementById("query_period").value;
            document.getElementById('f_no_of_funds').value = document.getElementById("no_of_funds").value;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
            document.getElementById('f_category_id').value = document.querySelector('input[name="category_id"]:checked').value;
            document.getElementById('f_criteria').value = document.querySelector('input[name="criteria"]:checked').value;
            document.getElementById('f_plan').value = getPlanData();
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
            document.getElementById('f_query_period').value = document.getElementById("query_period").value;
            document.getElementById('f_no_of_funds').value = document.getElementById("no_of_funds").value;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
            document.getElementById('f_category_id').value = document.querySelector('input[name="category_id"]:checked').value;
            document.getElementById('f_criteria').value = document.querySelector('input[name="criteria"]:checked').value;
            document.getElementById('f_plan').value = getPlanData();
            return true;
        }

        function checkDownloadCSV(){
            document.getElementById('page_type').value = "CSV";
            document.getElementById('f_query_period').value = document.getElementById("query_period").value;
            document.getElementById('f_no_of_funds').value = document.getElementById("no_of_funds").value;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
            document.getElementById('f_category_id').value = document.querySelector('input[name="category_id"]:checked').value;
            document.getElementById('f_criteria').value = document.querySelector('input[name="criteria"]:checked').value;
            document.getElementById('f_plan').value = getPlanData();
            return true;
                return true;
        }

        function renderView(type){
            var all_data = {};
            all_data.query_period = document.getElementById("query_period").value;
            all_data.no_of_funds = document.getElementById("no_of_funds").value;
            all_data.category_id = document.querySelector('input[name="category_id"]:checked').value;
            all_data.criteria = document.querySelector('input[name="criteria"]:checked').value;
            all_data.plan = getPlanData();
            all_data.type = type;
            all_data._token = "{{ csrf_token() }}";
            console.log(all_data);

            document.getElementById('mf_scanner_loading').style.display = "block";
            $.ajax({
                url: "{{ url('/mf-best-worst-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    // result = JSON.parse(result);
                    global_result = result.avg_data;
                    global_best_result = result.best_data;
                    global_worst_result = result.worst_data;

                    var total_count = result.total_count;
                    var no_of_funds = result.no_of_funds;

                    total_count = parseInt(total_count/2);

                    console.log(total_count);
                    var selected_value = no_of_funds;
                    if(all_data.no_of_funds < total_count){
                        selected_value = all_data.no_of_funds;
                    }

                    var html = "";
                    var selected = "";
                    for (var i = 1; i <= total_count; i++) {
                        selected = "";
                        if(selected_value == i){
                            selected = "selected";
                        }
                        html = html+"<option "+selected+">"+i+"</option>";
                    }
                    document.getElementById("no_of_funds").innerHTML = html;
                    renderTableView();
                    document.getElementById('mf_scanner_loading').style.display = "none";
                }
            });
        }

        function renderRow(type){
            renderView(type);
        }

        window.onload = function afterWebPageLoad() { 
            renderView(0);
        }
    </script>
@endsection
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
            padding: 6px 2px;
            text-align: center;
            line-height: 14px;
            font-size: 11px;
            font-weight: 600;
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
        #mf_scanner_list tbody tr td:nth-child(1) {
            text-align: left;
        }
        #mf_scanner_list tbody tr:nth-child(1) td {
            font-weight:bold;
        }
        /*#mf_scanner_list tr td:nth-child(1) {*/
        /*    width: 20px;*/
        /*}*/
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
        #filters_view .form-check-input, #choose_columns_view .form-check-input , .mf-scanner-filter-box .form-check-input {
                margin-top: .1rem;
        }
        #cc_return .form-check {
            margin-left: 5px;
        }
        
        #plan_return .form-check {
            margin-left: 5px;
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

        .form-control {
            border-radius: 0;
            border: 1px solid #cacaca !important;
            min-height: 40px;
            font-size: 12px;
        }
    </style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;"> {{$details->name}}</h2>
                </div>
            </div>
        </div>
        <a href="#" class="btn-chat">Chat With Us</a>
    </div>

    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.mf_scanner.top_sidebar')
                <div class="col-md-12">
                    <div class="rt-pnl" style="box-shadow: none;">
                        <div class="VideoNav" style="border: 0">
                            <!-- <div class="col-md-12 text-center">
                                <h4 class="" style=" padding-bottom: 3px;">Category</h4>
                            </div> -->
                            <div class="row" id="category_div">
                                <div class="col-md-3">
                                    <div class="mf-scanner-filter-box-header">
                                        <div>
                                            Equity Funds
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box">
                                      @if(count($equity_list)>0)
                                            @foreach ($equity_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="equity_fund_{{$result->classcode}}">
                                                    <input type="radio" data="{{$result->classname}}" class="form-check-input" id="equity_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView(1);" @if($result->classcode==$category_id) checked @endif>
                                                        <?php echo ($result->class_name)?$result->class_name:$result->classname;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mf-scanner-filter-box-header">
                                        <div style="width:calc(100% - 58px);">
                                            Debt Funds
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box">
                                       @if(count($debt_list)>0)
                                            @foreach ($debt_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="debt_fund_{{$result->classcode}}">
                                                    <input type="radio" data="{{$result->classname}}" class="form-check-input" id="debt_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView(1);" @if($result->classcode==$category_id) checked @endif>
                                                    <?php echo ($result->class_name)?$result->class_name:$result->classname;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mf-scanner-filter-box-header">
                                        <div>
                                            Hybrid Funds
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box">
                                      @if(count($hybrid_list)>0)
                                            @foreach ($hybrid_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="hybrid_fund_{{$result->classcode}}">
                                                    <input type="radio" data="{{$result->classname}}" class="form-check-input" id="hybrid_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView(1);" @if($result->classcode==$category_id) checked @endif>
                                                    <?php echo ($result->class_name)?$result->class_name:$result->classname;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mf-scanner-filter-box-header">
                                        <div>
                                            Commodity / Others
                                        </div>
                                        <div>
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box">
                                      @if(count($other_list)>0)
                                            @foreach ($other_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="commodity_other_{{$result->classcode}}">
                                                    <input type="radio" data="{{$result->classname}}" class="form-check-input" id="commodity_other_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView(1);" @if($result->classcode==$category_id) checked @endif>
                                                    <?php echo ($result->class_name)?$result->class_name:$result->classname;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="row mt-2" id="choose_columns_view">
                                  <div class="col-md-12">
                                    <div style="margin: 0px;font-weight: bold;">Plan</div>
                                    <div class="mf-scanner-filter-box" id="plan_return" style="display: flex;height: auto;margin-bottom: 15px;">
                                        @if(count($plan_list)>0)
                                            @foreach ($plan_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="plan_{{$result->plan_code}}">
                                                    <input type="checkbox" data="{{$result->plan}}" class="form-check-input" id="plan_{{$result->plan_code}}" name="plan[]" value="{{$result->plan_code}}" onchange="renderView(0);" 
                                                    @if(in_array($result->plan_code, $plan)) checked @endif>
                                                    <?php echo ($result->planname)?$result->planname:$result->plan;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div>
                                <div class="row mt-2">
                                  <div class="col-md-6">
                                    <div style="margin: 0px;font-weight: bold;">No. of Funds</div>
                                    <select class="form-control" id="no_of_funds" name="no_of_funds" onchange="renderRow(0);">

                                        @for($i=1;$i<=$no_of_funds;$i++)
                                            <option @if($no_of_funds == $i) selected @endif>{{$i}}</option>
                                        @endfor
                                    </select>
                                  </div>
                                  <div class="col-md-6">
                                    <label style="margin: 0px;font-weight: bold;">Query Period</label>
                                    <select class="form-control" id="query_period" name="query_period" onchange="renderRow(1);">
                                        <option value="1dayret" @if($query_period == "1dayret") selected @endif>1 Day</option>
                                        <option value="1weekret" @if($query_period == "1weekret") selected @endif>1 Week</option>
                                        <option value="1monthret" @if($query_period == "1monthret") selected @endif>1 Month</option>
                                        <option value="3monthret" @if($query_period == "3monthret") selected @endif>3 Month</option>
                                        <option value="6monthret" @if($query_period == "6monthret") selected @endif>6 Month</option>
                                        <option value="1yrret" @if($query_period == "1yrret") selected @endif>1 Year</option>
                                        <option value="2yearret" @if($query_period == "2yearret") selected @endif>2 Year</option>
                                        <option value="3yearret" @if($query_period == "3yearret") selected @endif>3 Year</option>
                                        <option value="5yearret" @if($query_period == "5yearret") selected @endif>5 Year</option>
                                        <option value="10yearret" @if($query_period == "10yearret") selected @endif>10 Year</option>
                                    </select>
                                  </div>
                                </div>
                            </div>

                            <div>
                                <div class="row mt-2">
                                  <div class="col-md-12">
                                    <div style="margin: 0px;font-weight: bold;">Criteria</div>
                                    <div class="mf-scanner-filter-box" style="display: flex;height: auto;margin-bottom: 15px;">
                                        <div class="form-check" style="margin-right: 7px;">
                                          <label class="form-check-label" for="cc_return">
                                            <input type="radio" class="form-check-input" name="criteria" value="1" onchange="renderRow(0);" @if($criteria == 1) checked @endif>Best
                                          </label>
                                        </div>
                                        <div class="form-check" style="margin-right: 7px;">
                                          <label class="form-check-label" for="cc_return">
                                            <input type="radio" class="form-check-input" name="criteria" value="2" onchange="renderRow(0);" @if($criteria == 2) checked @endif>Worst
                                          </label>
                                        </div>
                                        <div class="form-check">
                                          <label class="form-check-label" for="cc_return">
                                            <input type="radio" class="form-check-input" name="criteria" value="3" onchange="renderRow(0);" @if($criteria == 3) checked @endif>Both
                                          </label>
                                        </div>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="row" style="padding-bottom: 10px; font-size: 12px;">
                              
                              <div class="col-md-12 text-center pt-2 ml-3 mr-3" style="border-top: 1px solid #ccc; margin-top: 3px;">
                                <form action="{{route('frontend.mf_best_worst_save')}}" method="get" id="save_form_data">
                                    <input type="hidden" name="mf_scanner_saved_id" id="mf_scanner_saved_id" value="{{$mf_scanner_saved_id}}">
                                    <input type="hidden" name="f_query_period" id="f_query_period" value="">
                                    <input type="hidden" name="f_no_of_funds" id="f_no_of_funds" value="">
                                    <input type="hidden" name="f_category_id" id="f_category_id" value="">
                                    <input type="hidden" name="f_criteria" id="f_criteria" value="">
                                    <input type="hidden" name="f_plan" id="f_plan" value="">
                                    <input type="hidden" name="save_title" id="save_title" value="">
                                    <input type="hidden" name="page_type" id="page_type" value="">
                                    <input type="hidden" name="f_comments" id="f_comments" value="{{$comments}}">
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
                                                Updated
                                            </button>
                                        @else
                                            <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSavePermissionModal();">
                                                Updated
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

                                    @if($permission['is_csv'])
                                        <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownloadCSV();">
                                           Download CSV
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                            Download CSV
                                        </button>
                                    @endif
                                </form>
                              </div>
                            </div>
                        </div>
                        
                        <div class="row mt-1">
                          <div class="col-md-12 text-center" id="mf_scanner_loading" style="display: none;">
                             Loading...
                          </div>
                          <table id="mf_scanner_list">
                            
                          </table>
                        </div>
                        
                        <div class="form-group row mt-2"  style="align-items: flex-start;">
                            <div class="col-sm-12">
                                <label class="sqarecontainer">Add Comments (If any)
                                    <input id="is_comments" type="checkbox" name="is_comments" value="1" @if($comments) checked  @endif> 
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-sm-12" id="comment_textarea_div">
                                <textarea class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" rows="2" id="comments" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$comments}}</textarea>
                                <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                            </div>
                        </div>
                        <div class="row mt-1">
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
                    <input type="text" name="modal_title" id="modal_title" class="form-control" value="{{$name}}" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;">
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
    
    
    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="success_model_body" style="text-align: center;font-size: 16px;">Please login so that selected parameters are not lost.</p>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
                <a href="{{url('login')}}" class="btn btn-secondary btnblue">Login</a>
                <a href="{{url('membership')}}" class="btn btn-primary btnblue">Become a member</a>
            </div>
        </div>
      </div>
    </div>



@endsection
