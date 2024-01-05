@extends('layouts.frontend')


@section('js_after')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://code.highcharts.com/stock/highstock.js"></script>
    <script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/stock/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
    

    <script type="text/javascript">
        var glob_names = [];

        const names = ['MSFT', 'AAPL', 'GOOG'];

        function createChart(series) {

            Highcharts.stockChart('containerss', {

                rangeSelector: {
                    selected: 4
                },

                yAxis: {
                    labels: {
                        format: '{#if (gt value 0)}+{/if}{value}%'
                    },
                    plotLines: [{
                        value: 0,
                        width: 2,
                        color: 'silver'
                    }]
                },

                plotOptions: {
                    series: {
                        compare: 'percent',
                        showInNavigator: true
                    }
                },

                tooltip: {
                    pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
                    valueDecimals: 2,
                    split: true
                },

                series
            });

        }
        // (async () => {

        //     const names = ['MSFT', 'AAPL', 'GOOG'];

        //     /**
        //      * Create the chart when all data is loaded
        //      * @return {undefined}
        //      */
        //     function createChart(series) {

        //         Highcharts.stockChart('container', {

        //             rangeSelector: {
        //                 selected: 4
        //             },

        //             yAxis: {
        //                 labels: {
        //                     format: '{#if (gt value 0)}+{/if}{value}%'
        //                 },
        //                 plotLines: [{
        //                     value: 0,
        //                     width: 2,
        //                     color: 'silver'
        //                 }]
        //             },

        //             plotOptions: {
        //                 series: {
        //                     compare: 'percent',
        //                     showInNavigator: true
        //                 }
        //             },

        //             tooltip: {
        //                 pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
        //                 valueDecimals: 2,
        //                 split: true
        //             },

        //             series
        //         });

        //     }

        //     const promises = names.map(name => new Promise(resolve => {
        //         (async () => {
        //             const data = await fetch(
        //                 'https://cdn.jsdelivr.net/gh/highcharts/highcharts@v7.0.0/' +
        //                 'samples/data/' + name.toLowerCase() + '-c.json'
        //             )
        //                 .then(response => response.json());
        //             resolve({ name, data });
        //         })();
        //     }));

        //     const series = await Promise.all(promises);
        //     createChart(series);

        // })();
    </script>
    <script type="text/javascript">

        var globalData = [];

        function drawChart() {
            console.log(globalData);
            var data = google.visualization.arrayToDataTable(globalData);

            var options = {
                title: '',
                curveType: 'function',
                legend: { position: 'bottom',textStyle: {fontSize: 10} },
                hAxis : { 
                    textStyle : {
                        fontSize: 10
                    }

                },
                vAxis : { 
                    textStyle : {
                        fontSize: 10
                    }

                }
            };

            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

            google.visualization.events.addListener(chart, 'ready', function () {
                var imgUri = chart.getImageURI();
                 $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                });
                $.ajax({
                    type: 'POST',
                    url: "{{ route('frontend.investment_analysis_image') }}",
                    data: {img:imgUri},
                    success: function (data) {
                    
                    }
                });
               
            });

            chart.draw(data, options);
        }
    </script>
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

        $('#comment_textarea_div').css('display','none');

        $("#is_graph").click( function(){
            if( $(this).is(':checked') ){
                renderChartData();
                $('#curve_chart').css('display','block');
                document.getElementById("f_graph").value = 1;
            }else {
                $('#curve_chart').css('display','none');
                document.getElementById("f_graph").value = 0;
            }
        });
    </script>

    <script type="text/javascript">
        var global_result = [];
        var global_all_filed = [];
        var global_point_to_point = [];
        var table_html = "";
        var global_selected_row = [];
        var global_view_nav_graph = 0;

        var global_retuen_list = [
            {"id":"1","name":"1 Day","is_checked":1,"key_name":"1dayret"},
            {"id":"2","name":"7 Day","is_checked":0,"key_name":"1weekret"},
            {"id":"3","name":"1 Month","is_checked":1,"key_name":"1monthret"},
            {"id":"4","name":"3 Month","is_checked":1,"key_name":"3monthret"},
            {"id":"5","name":"6 Month","is_checked":0,"key_name":"6monthret"},
            {"id":"6","name":"1 Year","is_checked":1,"key_name":"1yrret"},
            {"id":"7","name":"2 Year","is_checked":0,"key_name":"2yearret"},
            {"id":"8","name":"3 Year","is_checked":1,"key_name":"3yearret"},
            {"id":"9","name":"5 Year","is_checked":1,"key_name":"5yearret"},
            {"id":"10","name":"10 Year","is_checked":0,"key_name":"10yret"},
            {"id":"11","name":"Since <br> Inception","is_checked":0,"key_name":"incret"}
        ];
      
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

        function openPointToPointModal(){
            $("#pointToPointModal").modal('show');
        }

        function savePointToPoint(){
            var all_filed = calculateFunction();
            if(all_filed.length <= 7){
                if(global_point_to_point.length >=3){
                    alert("Max 3 select");
                }else{
                    var insertData = {};
                    insertData.start_date = document.getElementById("start_date").value;
                    insertData.end_date = document.getElementById("end_date").value;
        
                    $("#pointToPointModal").modal('hide');
        
                    global_point_to_point.push(insertData);
        
                    renderPointToPoint();
        
                    renderView();
                    
                }
            }else{
              alert("Max 8 select");
            }
            return false;
        }

        function renderPointToPoint(){
            var return_list = ``;
            global_point_to_point.forEach(function(val){
                return_list = return_list+`<span class="category-view-span">
                        `+val.start_date+`-`+val.end_date+` <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+val.start_date+`-`+val.end_date+`');"></span>
                    </span>`;
            });
            document.getElementById("point_to_point_view").innerHTML = return_list;
        }

        function changeAllCheckbox(id,checked_id){
            var checkbox_flag = $("#"+checked_id).attr('checked');
            var filter_brand_list_id = document.getElementById(id);
              var filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');

              filter_brand_list_id_i.forEach(function(val){
                  // brand.push(val.value);
                  if(checkbox_flag){
                    $(val).attr('checked',true);
                  }else{
                    $(val).removeAttr('checked');
                  }
              });
        }

        function getAllBoxChecked(id){
            var filter_brand_list_id = document.getElementById(id).querySelectorAll('input[type=checkbox]:checked');
            var return_list = [];
            filter_brand_list_id.forEach(function(val){
                return_list.push($(val).val());
            });
            return return_list;
        }

        function getAllBoxCheckedForApi(id){
            var filter_brand_list_id = document.getElementById(id).querySelectorAll('input[type=checkbox]:checked');
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

        function removeFilterView(id){
            console.log(id);
            var insertData = [];
            global_point_to_point.forEach(function(val){
                console.log(val.start_date+"-"+val.end_date);
                if(id == val.start_date+"-"+val.end_date){

                }else{
                    insertData.push(val);
                }
            });
            global_point_to_point = insertData;
            renderPointToPoint();
            renderView();
        }

        function renderView(checked_id){
            var all_filed = calculateFunction();
            if(all_filed.length <= 8){
                var all_data = {};
                all_data._token = "{{ csrf_token() }}";
                all_data.category_id = document.querySelector('input[name="category_id"]:checked').value;
                all_data.plan = getAllBoxCheckedForApi('plan_return');
                all_data.trailing = getAllBoxCheckedForApi('trailing_return');
                all_data.calender = getAllBoxCheckedForApi('calender_return');
                all_data.point_to_point = global_point_to_point;
        
                document.getElementById('randhir_category_id').value = all_data.category_id;
                console.log(all_data);
                callAPi(all_data);   
            }else{
              $("#"+checked_id).prop('checked', false);
              alert("Max 8 select");
            }
        }

        function renderChartData(){
            if(global_view_nav_graph){
                var checked_schemecode_id = document.getElementById("schemecode_id").value;
                var checked_schemecode_ids = checked_schemecode_id.split(',');

                globalData = [];
                var headerData = [];
                var insertData = [];
                headerData.push("Scheme");
                global_all_filed.forEach(function(val){
                    headerData.push(val.name);
                });

                globalData.push(headerData);

                global_result.forEach(function(val){
                    if(checkSchemeCheckbox(val.schemecode,checked_schemecode_ids)){
                        insertData = [val.s_name];
                        global_all_filed.forEach(function(val1){
                            if(val[val1.key_name]){
                                if(val[val1.key_name] != 0){
                                    glo_aum = parseFloat(val[val1.key_name]);
                                    glo_aum = Number((glo_aum).toFixed(2));
                                    if(insertData){
                                        insertData.push(parseFloat(glo_aum.toFixedNoRounding(2)));
                                    }else{
                                        insertData = [parseFloat(glo_aum.toFixedNoRounding(2))];
                                    }
                                    
                                }else{
                                    if(insertData){
                                        insertData.push(0);
                                    }else{
                                        insertData = [0];
                                    }
                                }
                            }else{
                                if(insertData){
                                    insertData.push(0);
                                }else{
                                    insertData = [0];
                                }
                            } 
                        });
                        globalData.push(insertData);
                    }
                });

                // console.log(globalData);
                // google.charts.load('current', {'packages':['corechart']});
                // google.charts.setOnLoadCallback(drawChart);

                $.ajax({
                    url: "{{ url('/mf-category-wise-performance-scheme') }}",
                    method: 'get',
                    data: {"checked_schemecode_ids":checked_schemecode_ids},
                    success: function (result) {
                        console.log(result)
                        createChart(result);
                    }
                });

            }
        }

        function changeRowCheckbox(){

            var filter_brand_list_id = document.getElementById('mf_scanner_list').querySelectorAll('input[type=checkbox]:checked');
            var return_list = [];
            var mf_scanner_compare_value = "";
            filter_brand_list_id.forEach(function(val){
                return_list.push($(val).val());
                if(mf_scanner_compare_value){
                  mf_scanner_compare_value = mf_scanner_compare_value+","+$(val).val();
                }else{
                  mf_scanner_compare_value = $(val).val();
                }
            });
            global_selected_row = return_list;
            document.getElementById("schemecode_id").value = mf_scanner_compare_value;


            if(return_list.length > 1 && return_list.length <= 5){
                if(global_view_nav_graph){
                    document.getElementById('line_chart_div').style.display = "block";
                    renderChartData();
                }
            }else{
                document.getElementById('line_chart_div').style.display = "none";
            }
        }

        function openViewNAVGraph(){
            console.log(global_selected_row);
            if(global_selected_row.length > 1 && global_selected_row.length <= 5){
                global_view_nav_graph = 1;
                document.getElementById('line_chart_div').style.display = "block";
                document.getElementById('f_nav_graph').value = 1;
                renderChartData();
            }else{
                alert("Please Select a minimum 2 and Maximum 5 schemes ");
            }
        }

        var is_first_time = true;
        var glo_aum = 0;
        var glo_aum_date = "";
         
        function checkSchemeCheckbox(schemecode,schemecodes){
            var returnValue = false;
            schemecodes.forEach(function(val){
                if(!returnValue){
                    if(val == schemecode){
                        returnValue = true;
                    }
                }
            });
            return returnValue;
        }

        function renderTableView(){

            var checked_schemecode_id = document.getElementById("schemecode_id").value;
            var checked_schemecode_ids = checked_schemecode_id.split(',');

            table_html = "<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td></td>";
            table_html = table_html+"<td class='total'>Scheme</td>";
            global_all_filed.forEach(function(val){
                table_html = table_html+'<td class="sum">'+val.name+'</td>';
            });
            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";
            var return_avg = {
                "oneday_total":0,
                "oneday_count":0,
            };

            var schemecheckbox = "";

            global_result.forEach(function(val){
                schemecheckbox = "";
                if(checkSchemeCheckbox(val.schemecode,checked_schemecode_ids)){
                    schemecheckbox = "checked";
                }

                table_html = table_html+"<tr>";
                table_html = table_html+"<td><input type='checkbox' name='list_checkbox[]' onchange='changeRowCheckbox();' value='"+val.schemecode+"' "+schemecheckbox+"></td>";
                if(val.s_name){
                    table_html = table_html+"<td style='text-align: left'>"+val.s_name+"</td>";
                }else{
                    table_html = table_html+"<td style='text-align: left'>-</td>";
                }
                global_all_filed.forEach(function(val1){
                    if(val[val1.key_name]){
                        if(val[val1.key_name] != 0){
                            glo_aum = parseFloat(val[val1.key_name]);
                            glo_aum = Number((glo_aum).toFixed(2));
                            table_html = table_html+"<td>"+glo_aum.toFixedNoRounding(2)+"</td>";
                            
                        }else{
                            table_html = table_html+"<td>-</td>";
                        }
                        
                    }else{
                        table_html = table_html+"<td>-</td>";
                    } 
                });
                table_html = table_html+"</tr>";
            });
            table_html = table_html+"</tbody>";
            if(!is_first_time){
                $("#mf_scanner_list").dataTable().fnDestroy();
            }else{
                is_first_time = false;
            }

            document.getElementById('mf_scanner_list').innerHTML = table_html;

            changeRowCheckbox();
            
            $.fn.dataTableExt.oSort["test-desc"] = function (x, y){
                if(x.split('label').length == 3){
                    x = parseInt(x.split('label')[1].replace(/[^0-9]/g, ''));
                    y = parseInt(y.split('label')[1].replace(/[^0-9]/g, ''));
                }
                if ( x > y){
                    return -1;
                }
                return 1;
            };
        
            $.fn.dataTableExt.oSort["test-asc"] = function (x, y){
                if(x.split('label').length == 3){
                    x = parseInt(x.split('label')[1].replace(/[^0-9]/g, ''));
                    y = parseInt(y.split('label')[1].replace(/[^0-9]/g, ''));
                }
                if ( x > y){
                    return 1;
                }
                return -1;
            }
            
            $('#mf_scanner_list').dataTable({
                "bPaginate": false,
                "searching": false,   
                "order": [[ 1, "asc" ]],
                "columnDefs": [
                    { "type": "test", targets: 3 }
                ],
                fnFooterCallback: function(row, data, start, end, display) {
                    var api = this.api();
                    var iFHtml = "<tfoot id='footer_screenr'><tr>" //$(this).append('</tr>');
                    var i = 0;
                    this.api().columns().every(function () {
                        var column = this;
                        var sum = "";
                        if ($(column.header()).hasClass('sum')) { // <--- check ???
                            i = 0;
                            var sum = column
                                .data()
                                .reduce(function(a, b) {
                                    console.log(a,b);
                                    var x = parseFloat(a) || 0;
                                    var y = parseFloat(b) || 0;
                                    if(y){
                                        i = i+1;
                                    }
                                    return x + y;
                                }, 0);
                            if(sum){
                                sum = parseFloat(sum/i).toFixed(2);
                            }else{
                                sum = "-";
                            }
                            iFHtml += '<th style="text-align: right;padding:0px;background:#a9f3ff;">'+sum+'</th>';
                        } else if ($(column.header()).hasClass('total')) {
                            sum = "Category Average";
                            iFHtml += '<th style="text-align: left;padding:0px;background:#a9f3ff;">'+sum+'</th>';
                        } else {
                          sum = "";
                          iFHtml += '<th style="text-align: right;padding:0px;background:#a9f3ff;">'+sum+'</th>';
                        }
                    });
                    iFHtml += "</tr></tfoot>";

                    var footer_screenr = document.getElementById("footer_screenr");
                    if(footer_screenr){
                        $("#footer_screenr").remove();
                    }
                    $(this).append(iFHtml);
                }
            });
        }

        function renderRow(checked_id){
            console.log(checked_id);
            
            global_all_filed = calculateFunction();
        
            if(global_all_filed.length <= 8){
              renderTableView();
            }else{
              $("#"+checked_id).prop('checked', false);
              global_all_filed = calculateFunction();
              // renderTableView();
              alert("Max 8 select");

            }
        }
      
        function calculateFunction(){
            var all_filed = [];
            var cc_return_list = getAllBoxChecked('trailing_return');
            global_retuen_list.forEach(function(val){
              if(cc_return_list.find(o => o == val.id)){
                all_filed.push({"name":val.name,"key_name":val.key_name,"id":val.id,"type":"T"});
              }
            });
        
            cc_return_list = getAllBoxChecked('calender_return');
        
            console.log(cc_return_list);
            cc_return_list.forEach(function(val){
              all_filed.push({"name":val,"key_name":val,"id":val,"type":"C"});
            });
        
            global_point_to_point.forEach(function(val){
              all_filed.push({"name":val.start_date+"-"+val.end_date,"key_name":val.start_date+"-"+val.end_date,"id":val.start_date+"-"+val.end_date,"type":"P"});
            });
            
            return all_filed;
        }

        function callAPi(all_data){
            document.getElementById('mf_scanner_loading').style.display = "block";
            $.ajax({
                url: "{{ url('/mf-category-wise-performance-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    global_result = result;
                    renderRow();
                    document.getElementById('mf_scanner_loading').style.display = "none";
                }
            });
        }

        function checkCompare(){
            if(global_selected_row.length > 1){
                if(global_selected_row.length <= 4){
                    document.getElementById('page_type').value = "COMPARE";
                    document.getElementById('schemecode_id').value = global_selected_row;
                    // console.log(global_selected_row);
                    return true;
                }else{
                    alert("Please select max 4 funds.");
                    return false;
                }
            }else{
                alert("Please select at least 2 funds.");
                return false;
            }
        }

        function checkSave(){
            document.getElementById('page_type').value = "SAVE";
            document.getElementById('schemecode_id').value = global_selected_row;
            var demo_data = "";
            global_all_filed = calculateFunction();
            global_all_filed.forEach(function(val){
                if(demo_data){
                    demo_data = demo_data+","+val.id+"_"+val.type;
                }else{
                    demo_data = val.id+"_"+val.type;
                }
            });
            document.getElementById('all_colum_list').value = demo_data;
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
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
            document.getElementById('schemecode_id').value = global_selected_row;
            var demo_data = "";
            global_all_filed = calculateFunction();
            console.log(global_all_filed);
            global_all_filed.forEach(function(val){
                if(demo_data){
                    demo_data = demo_data+","+val.id+"_"+val.type;
                }else{
                    demo_data = val.id+"_"+val.type;
                }
            });
            document.getElementById('all_colum_list').value = demo_data;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }

        function checkDownloadCSV(){
            document.getElementById('page_type').value = "CSV";
            document.getElementById('schemecode_id').value = global_selected_row;
            var demo_data = "";
            global_all_filed = calculateFunction();
            console.log(global_all_filed);
            global_all_filed.forEach(function(val){
                if(demo_data){
                    demo_data = demo_data+","+val.id+"_"+val.type;
                }else{
                    demo_data = val.id+"_"+val.type;
                }
            });
            document.getElementById('all_colum_list').value = demo_data;
            document.getElementById('f_comments').value = document.getElementById("comments").value;
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }

        window.onload = function afterWebPageLoad() { 
            renderView();

            $('#start_date').datepicker({
                format: 'dd/mm/yyyy',
                uiLibrary: 'bootstrap4',
                autoclose: true,
                endDate: '-1d',
             }).on('change.dp', (e) => {

              });

            $('#end_date').datepicker({
                format: 'dd/mm/yyyy',
                uiLibrary: 'bootstrap4',
                autoclose: true,
                endDate: '-1d',
             }).on('change.dp', (e) => {


              });
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
        width: calc(100% - 2px) !important;
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
        padding: 1px 4px;
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
    #filters_view .form-check-input, #choose_columns_view .form-check-input , .mf-scanner-filter-box .form-check-input {
            margin-top: .1rem;
    }
    #cc_return .form-check {
        margin-left: 5px;
    }
    #calender_return .form-check {
        margin-left: 5px;
    }
    
    #plan_return .form-check {
        margin-left: 5px;
    }
    .dataHintHold {
        position: relative;
    }
    #choose_columns_view .form-check {
        margin-right: 7px;
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
    #mf_scanner_list_wrapper {
        width: 100%;
    }
    #containerss {
      
    }
    #containerss .highcharts-root {
      border-radius: 15px;
      border: 2px solid #003063;
      padding: 4px;  
    }
</style>
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">{{$details->name}}</h2>
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
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mf-scanner-filter-box-header">
                                    <div>
                                        Equity Funds
                                    </div>
                                </div>
                                <div class="mf-scanner-filter-box" id="asset_class_selection_equity">
                                  @if(count($equity_list)>0)
                                        @foreach ($equity_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="equity_fund_{{$result->classcode}}">
                                                <input type="radio" data="{{$result->classname}}" class="form-check-input" id="equity_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView();" @if($result->classname=='Equity - Large Cap Fund') checked @endif>
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
                                </div>
                                <div class="mf-scanner-filter-box" id="asset_class_selection_debt">
                                   @if(count($debt_list)>0)
                                        @foreach ($debt_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="debt_fund_{{$result->classcode}}">
                                                <input type="radio" data="{{$result->classname}}" class="form-check-input" id="debt_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView();">
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
                                </div>
                                <div class="mf-scanner-filter-box" id="asset_class_selection_hybrid">
                                  @if(count($hybrid_list)>0)
                                        @foreach ($hybrid_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="hybrid_fund_{{$result->classcode}}">
                                                <input type="radio" data="{{$result->classname}}" class="form-check-input" id="hybrid_fund_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView();">
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
                                </div>
                                <div class="mf-scanner-filter-box" id="asset_class_selection_other" style="">
                                  @if(count($other_list)>0)
                                        @foreach ($other_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="commodity_other_{{$result->classcode}}">
                                                <input type="radio" data="{{$result->classname}}" class="form-check-input" id="commodity_other_{{$result->classcode}}" name="category_id" value="{{$result->classcode}}" onchange="renderView();">
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
                            <div class="row mt-1" id="choose_columns_view">
                              <div class="col-md-12">
                                <div style="margin: 0px;font-weight: bold;">Plan</div>
                                <div class="mf-scanner-filter-box" id="plan_return" style="display: flex;height: auto;margin-bottom: 15px;">
                                    @if(count($plan_list)>0)
                                        @foreach ($plan_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="plan_{{$result->plan_code}}">
                                                <input type="checkbox" data="{{$result->plan}}" class="form-check-input" id="plan_{{$result->plan_code}}" name="plan[]" value="{{$result->plan_code}}" onchange="renderView();" 
                                                @if($result->plan =='Standard Plan') checked @endif>
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
                            <div class="row mt-1" id="choose_columns_view">
                              <div class="col-md-12">
                                <div style="margin: 0px;font-weight: bold;">Trailing</div>
                                <div class="mf-scanner-filter-box" id="trailing_return" style="display: flex;height: auto;margin-bottom: 15px;">
                                    @if(count($retuen_list)>0)
                                        @foreach ($retuen_list as $key=>$result)
                                            <div class="form-check">
                                              <label class="form-check-label" for="cc_return_{{$result['id']}}">
                                                <input type="checkbox" class="form-check-input" id="cc_return_{{$result['id']}}" name="return[]" value="{{$result['id']}}" onchange="renderRow('cc_return_{{$result['id']}}');" @if($result['is_checked'] ==1) checked @endif>{{$result['name']}}
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
                            <div class="row mt-1" id="choose_columns_view">
                              <div class="col-md-12">
                                <div style="margin: 0px;font-weight: bold;">Calender</div>
                                <div class="mf-scanner-filter-box" id="calender_return" style="display: flex;height: auto;margin-bottom: 15px;">
                                    @for($i=date('Y')-1; $i >= date('Y')-10; $i--)
                                        <div class="form-check">
                                          <label class="form-check-label" for="calender_return_{{$i}}">
                                            <input type="checkbox" class="form-check-input" id="calender_return_{{$i}}" name="calender_return[]" value="{{$i}}" onchange="renderView('calender_return_{{$i}}');">{{$i}}
                                          </label>
                                        </div>
                                    @endfor
                                </div>
                              </div>
                            </div>
                        </div>

                        @if (Auth::check())
                            @if(Auth::user()->email == "test@test.com")
                                
                            @endif
                        @endif
                        
                        <div style="">
                            <div class="row mt-1" id="choose_columns_view">
                              <div class="col-md-12">
                                <div style="margin: 0px;font-weight: bold;">Point to Point 
                                    <span class="icon-calendar asset_child equities1" style="color: #767474;" onclick="openPointToPointModal();">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </span>
                                    <!--<span style="margin-left:20px;" onclick="renderView();">-->
                                    <!--    Reload-->
                                    <!--</span>-->
                                </div>
                                <div class="mf-scanner-filter-box" id="point_to_point_view" style="display: flex;height: auto;margin-bottom: 15px;">
                                    
                                </div>
                              </div>
                            </div>
                        </div>

                        <div class="row mx-0" style="padding-bottom: 10px; font-size: 12px;">
                          
                          <div class="col-md-12 text-center pt-2" style="border-top: 1px solid #ccc; margin-top: 3px;">
                            <form action="{{route('frontend.mf_best_worst')}}" method="get" target="_blank">
                                <input type="hidden" name="category_id" id="randhir_category_id" value="">
                                <button  class="btn btn-success btn-sm downloadButton" target="_blank">
                                    Best - Worst Performing Fund
                                </button>
                            </form>
                          </div>
                        </div>

                        <div class="row mx-0" style="padding-bottom: 10px; font-size: 12px;">
                          
                          <div class="col-md-12 text-center pt-2" style="border-top: 1px solid #ccc; margin-top: 3px;">
                            <form action="{{route('frontend.mf_category_wise_performance_save')}}" method="get" id="save_form_data">
                              <input type="hidden" name="schemecode_id" id="schemecode_id" value="">
                              <input type="hidden" name="shorting_id" id="shorting_id" value="">
                              <input type="hidden" name="all_colum_list" id="all_colum_list" value="">
                              <input type="hidden" name="save_title" id="save_title" value="">
                              <input type="hidden" name="page_type" id="page_type" value="">
                              <input type="hidden" name="title_line1" id="title_line1" value="">
                              <input type="hidden" name="title_line2" id="title_line2" value="">
                              <input type="hidden" name="client_name" id="client_name" value="">
                              <input type="hidden" name="f_comments" id="f_comments" value="">
                              <input type="hidden" name="f_graph" id="f_graph" value="1">
                              <input type="hidden" name="f_nav_graph" id="f_nav_graph" value="0">

                              @if (Auth::check())
                                @if($permission['is_download'])
                                    @if($permission['is_cover'])
                                        <button type="button"  class="btn btn-success btn-sm downloadButton" onclick="return openModal();">
                                            Download
                                        </button>
                                    @else
                                        <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownload();">
                                            Download
                                        </button>
                                    @endif
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
                                @if($permission['is_csv'])
                                    <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownloadCSV();">
                                       Download CSV
                                    </button>
                                @else
                                    <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                        Download CSV
                                    </button>
                                @endif
                                <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openViewNAVGraph();" id="view_nav_graph">
                                    View NAV Graph
                                </button>
                              
                            </form>
                          </div>
                        </div>
                    </div>
                    
                    <div class="row mt-1 mx-0">
                      <div class="col-md-12 text-center" id="mf_scanner_loading" style="display: none;">
                         Loading...
                      </div>
                      <table id="mf_scanner_list">
                        
                      </table>
                    </div>
                    
                    <div class="form-group row mt-2"  style="align-items: flex-start;">
                        <div class="col-sm-12">
                            <label class="sqarecontainer">Add Comments (If any)
                                <input id="is_comments" type="checkbox" name="is_comments" value="1"> 
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="col-sm-12" id="comment_textarea_div" style="display: none;">
                            <textarea class="form-control {{ $errors->has('comments') ? ' is-invalid' : '' }}" name="comments" rows="2" id="comments" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{old('comments')}}</textarea>
                            <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                        </div>
                    </div>
                    <div class="row mt-1 mx-0" id="line_chart_div" style="display: none;">
                        <div class="form-group row mt-2 mx-0" style="align-items: flex-start;">
                            <div class="col-sm-12 d-flex px-0">
                                <label class="sqarecontainer">View Detailed Graph
                                    <input id="is_graph" type="checkbox" name="is_graph" value="1" checked="checked"> 
                                    <span class="checkmark"></span>
                                </label>
                            </div>
                            <div class="col-md-12 text-center" id="containerss" style="width: 100%; height: 500px">
                                Loading...
                            </div>
                        </div>
                    </div>

                    <div class="row mt-1 mx-0">
                        @php
                            $note_data1 = \App\Models\Mfresearch_note::where('id',4)->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                        @php } @endphp
                    </div>
                    <div class="row mx-0">
                        Report Date : {{date('d/m/Y')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--<div class="btm-shape-prt">-->
    <!--    <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">-->
    <!--</div>-->
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

    <div class="modal fade" id="pointToPointModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Point to Point</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" metho="get" onsubmit="return savePointToPoint();">
                <div class="modal-body">
                    <div class="form-group">
                        <label style="margin-bottom: 0px;">Start Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 20px;height: 20px;">
                    </div>
                    <div class="form-group">
                        <label style="margin-bottom: 0px;">End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 20px;height: 20px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">SAVE</button>
                </div>
            </form>
        </div>
      </div>
    </div>

    @include('frontend.mf_research.modal')


    @include('frontend.mf_research.common.cover_modal')
    
    
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
