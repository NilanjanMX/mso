@extends('layouts.frontend')
@section('js_after')
    
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
               
            $("#srch-term").click(function(){
                if(Object.keys(map_global).length==0)
                    fundname_search();
            })
        
            $("#srch-term").autocomplete({
                select: function (a, b) 
                {
                  $(this).val(b.item.value);
                    $("#add_btn_search").click();
                }
            });
        });

        function closeCompare(key_name){
            document.getElementById('row_view1_'+key_name).style.display = "none";
            document.getElementById('row_view2_'+key_name).style.display = "none";
            document.getElementById('row_view3_'+key_name).style.display = "none";
            document.getElementById('row_view4_'+key_name).style.display = "none";
            document.getElementById('row_view5_'+key_name).style.display = "none";
            document.getElementById('row_view6_'+key_name).style.display = "none";
            document.getElementById('row_view7_'+key_name).style.display = "none";
        }

        var $disabledResults = $("#compare_schemecode");
        $disabledResults.select2({
            placeholder: "Select Fund",
        });

        function changeCategory(){
            var compare_category = document.getElementById("compare_category").value;
            var compare_assert_type = document.getElementById("compare_assert_type").value;
            var data = {
                compare_category:compare_category,
                compare_assert_type:compare_assert_type
            }
            $.ajax({
                url: "{{ url('/mf-screener-scheme-list') }}",
                method: 'get',
                data: data,
                success: function (result) {
                    console.log(result);
                    options = '<option value=""></option>';
                    $(result.schemeList).each(function (index, value) {
                        options += '<option value="' + value.schemecode + '">' + value.s_name + '</option>';
                    });
                    $('#compare_schemecode').html(options);
                    $('#compare_schemecode').select2({
                        placeholder: "Select Fund",
                    });
                }
            });
        }

        function changeAssetType(){

            var compare_category = document.getElementById("compare_category").value;
            var compare_assert_type = document.getElementById("compare_assert_type").value;
            var data = {
                compare_category:compare_category,
                compare_assert_type:compare_assert_type
            }
            $.ajax({
                url: "{{ url('/mf-screener-scheme-list') }}",
                method: 'get',
                data: data,
                success: function (result) {
                    console.log(result);
                    var options = '<option value="">All</option>';
                    $(result.categoryList).each(function (index, value) {
                        options += '<option value="' + value.classcode + '">' + value.classname + '</option>';
                    });
                    $('#compare_category').html(options);
                    options = '<option value=""></option>';
                    $(result.schemeList).each(function (index, value) {
                        options += '<option value="' + value.schemecode + '">' + value.s_name + '</option>';
                    });
                    $('#compare_schemecode').html(options);
                    $('#compare_schemecode').select2({
                        placeholder: "Select Fund",
                    });
                }
            });
        }

        $('#as_on_date').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true
        })();

        function searchData(){
            var data = {};
            data.compare_schemecode = document.getElementById('compare_schemecode').value;
            data.data_frequency = document.getElementById('data_frequency').value;
            data.as_on_date = document.getElementById('as_on_date').value;
            data.rollong_return_performance = document.getElementById('rollong_return_performance').value;

            data._token = "{{ csrf_token() }}";
            $.ajax({
                url: "{{ url('/mf-rolling-return-list') }}",
                method: 'post',
                data: data,
                success: function (result) {
                    console.log(result)
                    var iHtml = `<tr>
                            <td rowspan="2">Scheme Name</td>
                            <td colspan="3">1 Month</td>
                        </tr><tr>
                            <td>Avg</td>
                            <td>Max</td>
                            <td>Min</td>
                        </tr>`;
                    result.forEach(function(val){
                        iHtml = iHtml + `<tr>
                            <td>`+val.s_name+`</td>
                            <td>`+val.navrs+`</td>
                            <td>`+val.navrs+`</td>
                            <td>`+val.navrs+`</td>
                        </tr>`
                    });
                    console.log(iHtml);
                    document.getElementById('mf_scanner_list').innerHTML = iHtml;
                }
            });

            return false;
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
        padding: 1px 4px;
        text-align: center;
        line-height: 14px;
        font-size: 11px;
        font-family: 'Poppins', sans-serif;
        font-weight: 400;
    }
</style>
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">SCANNER</h2>
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
            <form method="get" id="comp_sch" >
                  <div class="input-group row" style="margin-bottom: 10px;">
                    <label for="compare_assert_type" class="col-sm-3 col-form-label">Asset Class</label>
                    <div class="col-sm-9">
                    <select class="form-control" name="compare_assert_type" id="compare_assert_type" onchange="changeAssetType();">
                        <option value="">All</option>
                        <option value="Equity">Equity</option>
                        <option value="Debt">Debt</option>
                        <option value="Hybrid">Hybrid</option>
                        <option value="Commodity">Commodity</option>
                        <option value="Others">Others</option>
                    </select>     
                    </div> 
                  </div>  
                  <div class="input-group row" style="margin-bottom: 10px;">
                    <label for="compare_category" class="col-sm-3 col-form-label">Category</label>
                    <div class="col-sm-9">
                    <select class="form-control" name="compare_category" id="compare_category" onchange="changeCategory();">
                        <option value="">All</option>
                        @foreach($category_list as $dropdown)
                            <option value="{{$dropdown->classcode}}">{{$dropdown->classname}}</option>
                        @endforeach
                    </select>  
                    </div> 
                  </div>
                  <div class="input-group row" style="margin-bottom: 10px;">
                    <label for="compare_schemecode" class="col-sm-3 col-form-label">Select Scheme</label>
                    <div class="col-sm-9">
                        <select class="form-control ui-autocomplete-input" name="compare_schemecode" id="compare_schemecode">
                            <option value=""></option>
                            <option value="1_M">1 Month</option>
                            <option value="3_M">3 Month</option>
                            <option value="6_M">6 Month</option>
                            <option value="1_Y">1 Year</option>
                            <option value="3_Y">3 Year</option>
                            <option value="5_Y">5 Year</option>
                            <option value="10_Y">10 Year</option>
                        </select>
                    </div> 
                  </div> 
                  <div class="input-group row" style="margin-bottom: 10px;">
                    <label for="data_frequency" class="col-sm-3 col-form-label">Data Frequency</label>
                    <div class="col-sm-9">
                        <select class="form-control ui-autocomplete-input" name="data_frequency" id="data_frequency">
                            <option value=""></option>
                            <option value="1_M">1 Month</option>
                            <option value="3_M">3 Month</option>
                            <option value="6_M">6 Month</option>
                            <option value="1_Y">1 Year</option>
                            <option value="3_Y">3 Year</option>
                            <option value="5_Y">5 Year</option>
                            <option value="10_Y">10 Year</option>
                        </select>
                    </div> 
                  </div>
                  <div class="input-group row" style="margin-bottom: 10px;">
                    <label for="rollong_return_performance" class="col-sm-3 col-form-label">Rolling Return Performance</label>
                    <div class="col-sm-9">
                        <select class="form-control ui-autocomplete-input" name="rollong_return_performance" id="rollong_return_performance">
                            <option value=""></option>
                            <option value="1_M">1 Month</option>
                            <option value="3_M">3 Month</option>
                            <option value="6_M">6 Month</option>
                            <option value="1_Y">1 Year</option>
                            <option value="3_Y">3 Year</option>
                            <option value="5_Y">5 Year</option>
                            <option value="10_Y">10 Year</option>
                        </select>
                    </div> 
                  </div> 
                  <div class="input-group row">
                    <label for="as_on_date" class="col-sm-3 col-form-label">As On Date</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control ui-autocomplete-input" name="as_on_date" id="as_on_date" autocomplete="off">
                    </div> 
                  </div> 
                  <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                    <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="searchData();">Search</button>
                  </div>   
                      
            </form>
          </div>
        </div>

        <div class="row Search_Here" style="margin-top: 20px;">
          <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" >
            <table id="mf_scanner_list">
                
            </table>
          </div>
        </div>
    </div>
    <div class="btm-shape-prt">
        <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">
    </div>
</section>

@endsection
