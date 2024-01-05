@extends('layouts.frontend')
@section('js_after')
    
    <script>
        var total_scanner_compare_ran = <?php echo count($result);?>;
        $(window).scroll(function(){
            if ( $(window).width() > 767) {
                if ($(this).scrollTop() > 260) {
                    $('#comparison_fund').addClass('fixed-top container');
                    $('#rv_comparison #comparison_fund').css({"padding-top": "2rem", "background":"#fff", "z-index":"1"});
                    $('#comparison_basic').css({"padding-top": "3.5rem"});
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

        function closeCompare(key_name,schemecode){
            console.log(key_name+"==="+schemecode);
            document.getElementById('row_view1_'+key_name).style.display = "none";
            document.getElementById('row_view2_'+key_name).style.display = "none";
            document.getElementById('row_view3_'+key_name).style.display = "none";
            document.getElementById('row_view4_'+key_name).style.display = "none";
            document.getElementById('row_view5_'+key_name).style.display = "none";
            document.getElementById('row_view6_'+key_name).style.display = "none";
            document.getElementById('row_view7_'+key_name).style.display = "none";
            var data = {
                schemecode:schemecode
            }
            total_scanner_compare_ran = total_scanner_compare_ran-1;
            $.ajax({
                url: "{{ url('/mf-screener-compare-remove') }}",
                method: 'get',
                data: data,
                success: function (result) {
                    console.log(result);
                    if(parseInt(result) < 2){
                        document.getElementById('download_saved_div').style.display = "none";
                    }
                }
            });
        }
        
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

        $("#compare_schemecode").select2({
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
        
        function checkDownload(){
            var basic_detail_id = document.getElementById("basic_detail_id").querySelectorAll('input[type=checkbox]:checked');
            var basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("basic_detail_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("return_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("return_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("mf_ratios_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("mf_ratios_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("portfolio_attributes_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("portfolio_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("top_sector_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("sector_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("comparison_top_10_holdings").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = "";
            basic_detail_id.forEach(function(val){
                basic_detail_list = $(val).val();
            });
            document.getElementById("holding_checkbox_id").value = basic_detail_list;
            console.log(basic_detail_list);
            // return false;
            document.getElementById("compare_type").value = "DOWNLOAD";
            document.getElementById("compare_title").value = "";
            return true;
        }
        
        function saveModalData(){
            var basic_detail_id = document.getElementById("basic_detail_id").querySelectorAll('input[type=checkbox]:checked');
            var basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("basic_detail_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("return_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("return_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("mf_ratios_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("mf_ratios_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("portfolio_attributes_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("portfolio_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("top_sector_id").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("sector_checkbox_id").value = basic_detail_list;
            
            basic_detail_id = document.getElementById("comparison_top_10_holdings").querySelectorAll('input[type=checkbox]:checked');
            basic_detail_list = [];
            basic_detail_id.forEach(function(val){
                basic_detail_list.push($(val).val());
            });
            document.getElementById("holding_checkbox_id").value = basic_detail_list;
            document.getElementById("compare_title").value = "";
            document.getElementById("compare_type").value = "SAVE";
            
            document.getElementById("download_save_form").submit();
        }
        
        function addScheme(){
            document.getElementById("basic_detail_checkbox_id").value = "";
            document.getElementById("return_checkbox_id").value = "";
            document.getElementById("mf_ratios_checkbox_id").value = "";
            document.getElementById("portfolio_checkbox_id").value = "";
            document.getElementById("sector_checkbox_id").value = "";
            document.getElementById("holding_checkbox_id").value = "";
            document.getElementById("compare_title").value = "";
            document.getElementById("compare_type").value = "ADD";
            if(total_scanner_compare_ran >= 4){
                alert("Please select max 4 funds.");
                return false;
            }else{
                document.getElementById("download_save_form").submit();
            }
        }
        
        function openModal(){
            $("#sentEmailModal").modal('show');
        }
        
        function changeAllCheckbox(id){

            var checkbox_flag = $("#"+id+"_all").attr('checked');
    
            var filter_brand_list_id = document.getElementById(id+"_id");
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

    </script>
@endsection

@section('content')

<style>
    .list-group-item span {
        vertical-align: middle;
        display: inline-block;
        margin-top: 2px;
        margin-right: 4px;
    }
    .downloadButton {
        border-radius: 4px;
        background: #131f55;
        border: 1px solid #131f55;
        line-height: 22px;
    }
    .savedButton {
        border-radius: 4px;
        background: #16a1db;
        border: 1px solid #131f55;
        line-height: 22px;
    }
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
    
    li .forscroll::-webkit-scrollbar{width:4px;height:4px}
    li .forscroll::-scrollbar{width:4px;height:4px}
    li .forscroll::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.3);border-radius:5px}
    li .forscroll::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.5)}
    
    .hor_scroll::-webkit-scrollbar{width:4px;height:4px}
    .hor_scroll::-webkit-scrollbar-track{-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.2);border-radius:5px}
    .hor_scroll::-webkit-scrollbar-thumb{border-radius:5px;-webkit-box-shadow:inset 0 0 2px rgba(0,0,0,0.3)}
    .InvsHead{background:#f1f2f2;max-height:60px;border-bottom:1px solid #ddd;margin-bottom:1rem}
    li{font-size:12px;line-height:18px}
    .Search_Here{margin-bottom:1.5rem}
    #comparison_fund{margin-top:0rem}
    #comparison_fund .list-group{margin-bottom:0}
    #comparison_fund .list-group-item{min-height:90px;line-height:2}
    #comparison_fund .list-group-item h2{margin:10px 0;font-size:14px;letter-spacing:0;line-height:24px;color:#5f6469}
    #comparison_fund .comparison_fund_outer{border:0;background-color:#fff;width:100%;box-shadow:0 0 1px #888888}
    #comparison_fund .comparison_fund_inner_top{min-height:46px;padding:3px 5px}
    #comparison_fund .comparison_fund_inner_top a{color:#fff;font-size:13px;line-height: 15px; display: inline-block;}
    
    /*#comparison_fund .ct1{border:1px solid #647edd;background-color:#647edd}*/
    
    #comparison_fund .ct2{border:1px solid #20a1dc;background-color:#20a1dc}
    #comparison_fund .ct4{border:1px solid #6398de;background-color:#6398de}
    #comparison_fund .ct3{border:1px solid #3b71b9;background-color:#3b71b9}
    #comparison_fund .comparison_fund_inner_bottom{padding:2px 5px;font-size:12px;min-height:43px;border:1px solid #c0c1c1;border-top:0}
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
    #comparison_Chart .comparison_head{
        color: #fff;
        background: #131f55;
        padding: 2px 0 0 6px;
        height: 28px;
        font-size: 14px;
        font-weight: bold;
    }
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
    .comparison_bodyP2 li:nth-child(even){background:#f7f7f7}
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
    #comparison_top_10_holdings .comparison_bodyP2 li .comp_holding, #comparison_portfolio_market .comp_holding {
        white-space:nowrap;overflow-x:auto;
        /*width:170px;*/
        float:left;
        text-align:left;
        display: flex;
        justify-content: space-between;
        
    }
    #comparison_top_10_holdings .comp_bar{border-right:1px solid #a4a9ad;display:inline;padding-left:5px}
    #comparison_top_10_holdings .comparison_bodyP2 li .comp_percentage{
        /*display:inline-block;float:right;margin-right:5px;width:35px;text-align:right;*/
    }
    #comparison_Chart .list-group-item{min-height:33.3em;line-height:2}
    #comparison_Chart .comparison_bodyP2 #compare_chart{border:1px solid #c0c1c1;margin:0 -10px}
    .comparison_bodyP2 .comp_holding{white-space:nowrap;overflow-x:auto;width:220px;text-align:center}
    input.highcharts-range-selector{top:10px !important;position:absolute !important;border:0px !important;width:1px !important;height:1px !important;padding:0px !important;text-align:center !important;font-size:12px !important;margin-top:0px !important;left:705px !important}
    
    #comparison_portfolio_market .comp_bar,
    #comparison_top_10_holdings .comp_bar {
        border-right: 1px solid #c0c1c1;
        height: 22px;
        margin-top: 4px;
    }
    #comparison_portfolio_market .list-group-item,
    #comparison_top_10_holdings .list-group-item {
        padding-top:0;
        padding-bottom:0;
        line-height:30px;
    }
    #comparison_portfolio_market .comp_holding span:first-child,
    #comparison_top_10_holdings .comp_holding span:first-child {
        width: 85%;
        overflow-x: auto;
        display: block;
        line-height: 18px;
        padding-top: 5px;
        padding-bottom: 0;
        margin-bottom: 4px;
    }
    .comp_percentage {
        padding: 0px 5px;
        width: 47px;
        text-align: right;
    }
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
            
            .scmesearchhead {
                display: flex;
                justify-content: center;
                align-items: center;
                background: #a2d2f7;
                height: 42px;
                font-weight: 700;
            }
            .scmedatahead {
                display: flex;
                align-items: center;
                background: #a2d2f7;
                position:relative;
            }
            .scmedatahead:after {
                content: '';
                position: absolute;
                height: 8px;
                width: 21px;
                background:url({{asset('')}}images/bbarow.png);
                left: 47%;
                bottom: -7px;
            }
            .comparison_fund_inner_bottom {
                position:relative;
            }
            .comparison_fund_inner_bottom:after {
                content: '';
                position: absolute;
                height: 8px;
                width: 21px;
                background:url({{asset('')}}images/wbarrow.png);
                left: 47%;
                bottom: -7px;
            }
            .add_button {
                z-index: 1 !important;
            }
            .comparison_outer .mob_mf_com:nth-child(1) .ct1 {
                background: #ff5ca1;
                border: 1px solid #ff5ca1;
            }
            .comparison_outer .mob_mf_com:nth-child(2) .ct1 {
                background: #7646fe;
                border: 1px solid #7646fe;
            }
            .comparison_outer .mob_mf_com:nth-child(3) .ct1 {
                background: #12cf13;
                border: 1px solid #12cf13;
            }
            .comparison_outer .mob_mf_com:nth-child(4) .ct1 {
                background: #bd9618;
                border: 1px solid #bd9618;
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
        left: 41%;
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
</style>

@php
    // echo "<pre>"; print_r($result); exit;
    $iHtml1 = "";
    $iHtml2 = "";
    $iHtml3 = "";
    $iHtml4 = "";
    $iHtml5 = "";
    $iHtml6 = "";
    $iHtml7 = "";
    
    foreach($result as $key=>$value){
    
        $iHtml1 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view1_'.$key.'">
                      <div class="comparison_fund_outer">
                        <div class="comparison_fund_inner_top ct1">
                          <div class="icon-cancel-circled2 close_image" onclick="closeCompare('.$key.','.$value->schemecode.')">
                              <i class="far fa-times-circle"></i>
                              <input type="hidden" id="scheme_code_0">
                          </div>
                          <div class="">
                              <span id="s_name_0"><a class="compare" id="3461" href="#">'.$value->s_name.'</a></span> 
                          </div>                       
                        </div>
                        <div class="comparison_fund_inner_bottom">
                          <span id="classification_0">'.$value->classname.'</span>
                        </div>
                      </div>                  
                    </div>';
        $EXITLOAD = ($value->EXITLOAD && $value->EXITLOAD != "0")?number_format((float)$value->EXITLOAD, 2, '.', ''):"-";
    
        $iHtml2 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;"  id="row_view2_'.$key.'">
                        <ul class="list-group">
                          <li class="list-group-item" id="basic_launch_date_0">'.date('d-m-Y', strtotime($value->Incept_date)).'</li>
                          <li class="list-group-item" id="basic_launch_date_0">'.$value->classname.'</li>
                          <li class="list-group-item" id="basic_launch_date_0">'.$value->fund_mgr1.'</li>
                          <li class="list-group-item" id="basic_launch_date_0"><div class="dataHintHold">'.custome_money_format((int)($value->total/100)).'<span>'.date('d-m-Y', strtotime($value->total_date)).'</span></div></li>
                          <li class="list-group-item"><div class="comp_holding" id="basic_bench_index_0">'.$value->IndexName.'</div></li>
                          <li class="list-group-item" id="basic_expense_ratio_0">'.$value->expratio.'</li>
                          <li class="list-group-item" id="basic_exit_load_0"><div class="dataHintHold">'.$EXITLOAD.'<span>'.$value->REMARKS.'</span></div></li>
                          <li class="list-group-item" id="basic_lock_in_period_0">'.$value->navrs.'</li>
                        </ul>  
                      </div>';
        $onemonthret = ($value->onemonthret && $value->onemonthret != "0")?number_format((float)$value->onemonthret, 2, '.', ''):"-";
        $threemonthret = ($value->threemonthret && $value->threemonthret != "0")?number_format((float)$value->threemonthret, 2, '.', ''):"-";
        $sixmonthret = ($value->sixmonthret && $value->sixmonthret != "0")?number_format((float)$value->sixmonthret, 2, '.', ''):"-";
        $oneyrret = ($value->oneyrret && $value->oneyrret != "0")?number_format((float)$value->oneyrret, 2, '.', ''):"-";
        $twoyearret = ($value->twoyearret && $value->twoyearret != "0")?number_format((float)$value->twoyearret, 2, '.', ''):"-";
        $threeyearret = ($value->threeyearret && $value->threeyearret != "0")?number_format((float)$value->threeyearret, 2, '.', ''):"-";
        $fiveyearret = ($value->fiveyearret && $value->fiveyearret != "0")?number_format((float)$value->fiveyearret, 2, '.', ''):"-";
        $tenyret = ($value->tenyret && $value->tenyret != "0")?number_format((float)$value->tenyret, 2, '.', ''):"-";
        $incret = ($value->incret && $value->incret != "0")?number_format((float)$value->incret, 2, '.', ''):"-";
        
        $iHtml3 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view3_'.$key.'">
                        <ul class="list-group">
                          <li class="list-group-item" id="return_6m_0">'.$onemonthret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$threemonthret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$sixmonthret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$oneyrret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$twoyearret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$threeyearret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$fiveyearret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$tenyret.'</li>
                          <li class="list-group-item" id="return_6m_0">'.$incret.'</li>
                        </ul>  
                      </div>';
        $alpha = ($value->alpha && $value->alpha != "0")?number_format($value->alpha, 2, '.', ''):"-";
        $sharpe = ($value->sharpe && $value->sharpe != "0")?number_format($value->sharpe, 2, '.', ''):"-";
        $sortino = ($value->sortino && $value->sortino != "0")?number_format($value->sortino, 2, '.', ''):"-";
        $beta = ($value->beta && $value->beta != "0")?number_format($value->beta, 2, '.', ''):"-";
        $sd = ($value->sd && $value->sd != "0")?number_format($value->sd, 2, '.', ''):"-";
    
        $iHtml4 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view4_'.$key.'">
                        <ul class="list-group">
                          <li class="list-group-item" id="performance_alpha_0">'.$alpha.'</li>
                          <li class="list-group-item" id="performance_sharpe_0">'.$sharpe.'</li>
                          <li class="list-group-item" id="performance_sortino_0">'.$sortino.'</li>
                          <li class="list-group-item" id="performance_beta_0">'.$beta.'</li>
                          <li class="list-group-item" id="performance_stand_dev_0">'.$sd.'</li>
                        </ul>  
                      </div>';
        
        $PB = ($value->PB && $value->PB != "0")?number_format((float)$value->PB, 2, '.', ''):"-";
        $PE = ($value->PE && $value->PE != "0")?number_format((float)$value->PE, 2, '.', ''):"-";
        $Div_Yield = ($value->Div_Yield && $value->Div_Yield != "0")?number_format((float)$value->Div_Yield, 2, '.', ''):"-";
        if($value->tr_mode ==  "times"){
            $turnover_ratio = ($value->turnover_ratio && $value->turnover_ratio != "0")?(int)($value->turnover_ratio*100):"-";
        }else{
            $turnover_ratio = ($value->turnover_ratio && $value->turnover_ratio != "0")?number_format((float)$value->turnover_ratio, 2, '.', ''):"-";
        }
        $ASECT_CODE = ($value->ASECT_CODE && $value->ASECT_CODE != "0")?$value->ASECT_CODE:"-";
        $MCAP = ($value->MCAP && $value->MCAP != "0")?custome_money_format((int)($value->MCAP/100)):"-";
        $large_cap = ($value->large_cap && $value->large_cap != "0")?number_format((float)$value->large_cap, 2, '.', ''):"-";
        $mid_cap = ($value->mid_cap && $value->mid_cap != "0")?number_format((float)$value->mid_cap, 2, '.', ''):"-";
        $small_cap = ($value->small_cap && $value->small_cap != "0")?number_format((float)$value->small_cap, 2, '.', ''):"-";
        $ytm = ($value->ytm && $value->ytm != "0")?number_format((float)$value->ytm, 2, '.', ''):"-";
        $rating_one = ($value->rating_one && $value->rating_one != "0")?number_format((float)$value->rating_one, 2, '.', ''):"-";
        $rating_two = ($value->rating_two && $value->rating_two != "0")?number_format((float)$value->rating_two, 2, '.', ''):"-";
        $rating_three = ($value->rating_three && $value->rating_three != "0")?number_format((float)$value->rating_three, 2, '.', ''):"-";
        $rating_four = ($value->rating_four && $value->rating_four != "0")?number_format((float)$value->rating_four, 2, '.', ''):"-";
        $rating_five = ($value->rating_five && $value->rating_five != "0")?number_format((float)$value->rating_five, 2, '.', ''):"-";
        
        $iHtml5 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view5_'.$key.'">
                        <ul class="list-group">
                          <li class="list-group-item" id="port_attr_pb_ratio_0">'.$PB.'</li>
                          <li class="list-group-item" id="port_attr_pe_ratio_0">'.$PE.'</li>
                          <li class="list-group-item" id="port_attr_turnover_ratio_0">'.$Div_Yield.'</li>
                          <li class="list-group-item" id="port_attr_turnover_ratio_0">'.$turnover_ratio.'</li>
                          <li class="list-group-item" id="port_attr_avg_maturity_0">'.$ASECT_CODE.'</li>
                          <li class="list-group-item" id="port_attr_avg_maturity_0">'.$MCAP.'</li>
                          <li class="list-group-item" id="port_attr_avg_maturity_0">'.$large_cap.'</li>
                          <li class="list-group-item" id="port_attr_avg_maturity_0">'.$mid_cap.'</li>
                          <li class="list-group-item" id="port_attr_avg_maturity_0">'.$small_cap.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$value->avg_mat_num.' '.$value->avg_mat_days.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$value->mod_dur_num.' '.$value->mod_dur_days.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$ytm.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$rating_one.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$rating_two.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$rating_three.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$rating_four.'</li>
                          <li class="list-group-item" id="port_attr_mod_dur_0">'.$rating_five.'</li>
                        </ul>  
                      </div>';
    
        $iHtml6 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view6_'.$key.'">
                        <ul class="list-group">';
        foreach($value->sector_list as $val){
            $iHtml6 .= '<li class="list-group-item"><div class="comp_holding" id="comp1_hold0"><span class="forscroll">'.$val->SECT_NAME.'</span><span class="comp_bar"></span><span class="comp_percentage" id="comp1_percent0">'.number_format((float)$val->Perc_Hold, 2, '.', '').'</span></div></li>';
        }
        $iHtml6 .= '</ul>  
                </div>';
    
        $iHtml7 .= '<div class="mob_mf_com col-3 col-md-3 col_0" style="display: block;" id="row_view7_'.$key.'">
                    <ul class="list-group">
                      ';
        foreach($value->holding_list as $val){
            $iHtml7 .= '<li class="list-group-item"><div class="comp_holding" id="comp1_hold0"><span class="forscroll">'.$val->compname.'</span><span class="comp_bar"></span><span class="comp_percentage" id="comp1_percent0">'.number_format((float)$val->holdpercentage, 2, '.', '').'</span></li>';
        }
        $iHtml7 .= '</ul>  
                </div>';
    }

@endphp
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">MF SCHEME COMPARISON</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="main-sec">
        <div class="container">
                <div style="border-bottom: 1px solid #ddd;">
                    @include('frontend.mf_scanner.top_sidebar')
                </div>
            <div class="row Search_Here" style="margin-top: 20px;">
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" >
                <form method="get" action="" id="download_save_form">
                  <div class="input-group row">
                    <div class="col-sm-2 text-center" style="padding-right: 0px;">
                        <div class="form-control scmesearchhead">
                            Search Scheme
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <select class="form-control ui-autocomplete-input" name="compare_schemecode" id="compare_schemecode">
                            <option value=""> </option>
                            @foreach($dropdownList as $dropdown)
                                <option value="{{$dropdown->schemecode}}">{{$dropdown->s_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1" style="padding-left: 0px;">
                        <div class="text-center" style="display: inline-block; justify-content: center;">
                              <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="return addScheme();">Add</button>
                          </div>    
                    </div> 
                    <div class="col-sm-3" style="padding-left: 0px;">
                        <input type="hidden" id="compare_type" name="compare_type" value="">
                        <input type="hidden" id="compare_title" name="compare_title" value="">
                        <input type="hidden" id="basic_detail_checkbox_id" name="basic_detail_checkbox" value="">
                        <input type="hidden" id="return_checkbox_id" name="return_checkbox" value="">
                        <input type="hidden" id="mf_ratios_checkbox_id" name="mf_ratios_checkbox" value="">
                        <input type="hidden" id="portfolio_checkbox_id" name="portfolio_checkbox" value="">
                        <input type="hidden" id="sector_checkbox_id" name="sector_checkbox" value="">
                        <input type="hidden" id="holding_checkbox_id" name="holding_checkbox" value="">
                        <span id="download_saved_div">
                            <?php if(count($result) > 1) { ?>
                                <button type="submit" class="btn btn-success downloadButton" style="margin-right: 6px;" onclick="return checkDownload();">
                                    Download
                                </button>
                                <button type="button" class="btn btn-success savedButton" onclick="return openModal();">
                                    Update
                                </button> 
                            <?php } ?>
                        </span>
                    </div> 
                  </div>
                </form>
              </div>
            </div>
            
        <div id="show_page" class="d_none" style="display: block;">
          <div class="area_wrap_mob">
                <div id="comparison_fund" class="container" style="padding-top: 1.5rem; background: rgb(255, 255, 255); z-index: 1;">
                  <div class="row">
                    <div class="mob_mf_com col-2 col-md-2">                 
                        <div class="list-group">
                            <div class="list-group-item scmedatahead text-center">
                                <h2 style="line-height:18px;width: 100%;">
                                    <!--Comparison of selected funds based on various parameters-->
                                    <b>Scheme <br>Comparison</b>
                                </h2>
                            </div>
                        </div>                
                    </div>
                    <div class="col-10 col-md-10">
                        <div class="row comparison_outer">
                            {!! $iHtml1 !!}
                        </div>               
                    </div>
                  </div>
                </div>
            <div class="row" id="comparison_basic" style="padding-top: 1.5rem;">
              <div class="" style="
                    width: 100%;
                    padding-left: 15px;
                    padding-right: 5px;
                ">
                <div class="comparison_head">
                    <span><input type="checkbox" name="basic_detail_all" id="basic_detail_all" value="" onchange="changeAllCheckbox('basic_detail')" <?php echo (count($basic_detail_checkbox) == 8)?'checked':'';?>></span>
                    <span>BASIC DETAILS</span>
                </div>
              </div>
              <div class="col-2 col-md-2 comparison_bodyP1" id="basic_detail_id">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="inception_date" <?php echo (in_array("inception_date", $basic_detail_checkbox))?'checked':'';?>></span>
                        <span>Inception Date</span>
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="fund_type" <?php echo (in_array("fund_type", $basic_detail_checkbox))?'checked':'';?>></span>
                        Fund Type
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="fund_manager" <?php echo (in_array("fund_manager", $basic_detail_checkbox))?'checked':'';?>></span>
                        Fund Manager
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="aum" <?php echo (in_array("aum", $basic_detail_checkbox))?'checked':'';?>></span>
                        AUM (in Rs. Cr)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="benchmark_index" <?php echo (in_array("benchmark_index", $basic_detail_checkbox))?'checked':'';?>></span>
                        Benchmark Index
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="expense_ratio" <?php echo (in_array("expense_ratio", $basic_detail_checkbox))?'checked':'';?>></span>
                        Expense Ratio (%)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox[]" id="basic_detail_checkbox" value="exit_load" <?php echo (in_array("exit_load", $basic_detail_checkbox))?'checked':'';?>></span>
                        Exit Load (%)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="basic_detail_checkbox" id="basic_detail_checkbox" value="latest_nav" <?php echo (in_array("latest_nav", $basic_detail_checkbox))?'checked':'';?>></span>
                        Latest NAV
                    </li>
                </ul>                       
              </div>
              <div class="col-10 col-md-10 comparison_bodyP2">
                <div class="row">
                    {!! $iHtml2 !!} 
                </div>                                     
              </div>
            </div>

            <div class="row" id="comparison_return">
              <div class="col-12 col-md-12 pr-2">
                <div class="comparison_head">
                    <span><input type="checkbox" name="return_all" id="return_all" value="" onchange="changeAllCheckbox('return')" <?php echo (count($return_checkbox) == 9)?'checked':'';?>></span>
                    <span>RETURN (%)</span>
                </div>
              </div>
              <div class="col-2 col-md-2 comparison_bodyP1" id="return_id">
                  <ul class="list-group">
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="1_month"  <?php echo (in_array("1_month", $return_checkbox))?'checked':'';?>></span>
                        1 Month
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="3_month"  <?php echo (in_array("3_month", $return_checkbox))?'checked':'';?>></span>
                        3 Month
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="6_month"  <?php echo (in_array("6_month", $return_checkbox))?'checked':'';?>></span>
                        6 Month
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="1_year"  <?php echo (in_array("1_year", $return_checkbox))?'checked':'';?>></span>
                        1 Year
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="2_year"  <?php echo (in_array("2_year", $return_checkbox))?'checked':'';?>></span>
                        2 Year
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="3_year"  <?php echo (in_array("3_year", $return_checkbox))?'checked':'';?>></span>
                        3 Year
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="5_year"  <?php echo (in_array("5_year", $return_checkbox))?'checked':'';?>></span>
                        5 Year
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="10_year"  <?php echo (in_array("10_year", $return_checkbox))?'checked':'';?>></span>
                        10 Year
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="return_checkbox[]" id="return_checkbox" value="since_inception"  <?php echo (in_array("since_inception", $return_checkbox))?'checked':'';?>></span>
                        Since Inception
                    </li>
                  </ul>                       
              </div>
              <div class="col-10 col-md-10 comparison_bodyP2">
                <div class="row">
                    {!! $iHtml3 !!}  
                </div>                                     
              </div>
            </div>

            <div class="row" id="comparison_performance_measures">
              <div class="col-12 col-md-12 pr-2">
                <div class="comparison_head">
                    <span><input type="checkbox" name="mf_ratios_all" id="mf_ratios_all" value="" onchange="changeAllCheckbox('mf_ratios')"  <?php echo (count($mf_ratios_checkbox) == 5)?'checked':'';?>></span>
                    <span>MF RATIOS</span>
                </div>
              </div>
              <div class="col-2 col-md-2 comparison_bodyP1" id="mf_ratios_id">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span><input type="checkbox" name="mf_ratios_checkbox[]" id="mf_ratios_checkbox" value="alpha_ratio"  <?php echo (in_array("alpha_ratio", $mf_ratios_checkbox))?'checked':'';?>></span>
                        Alpha Ratio
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="mf_ratios_checkbox[]" id="mf_ratios_checkbox" value="sharpe_ratio" <?php echo (in_array("sharpe_ratio", $mf_ratios_checkbox))?'checked':'';?>></span>
                        Sharpe Ratio
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="mf_ratios_checkbox[]" id="mf_ratios_checkbox" value="sortino" <?php echo (in_array("sortino", $mf_ratios_checkbox))?'checked':'';?>></span>
                        Sortino
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="mf_ratios_checkbox[]" id="mf_ratios_checkbox" value="beta" <?php echo (in_array("beta", $mf_ratios_checkbox))?'checked':'';?>></span>
                        Beta
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="mf_ratios_checkbox[]" id="mf_ratios_checkbox" value="standard_deviation" <?php echo (in_array("standard_deviation", $mf_ratios_checkbox))?'checked':'';?>></span>
                        Standard Deviation
                    </li>
                </ul>                       
              </div>
              <div class="col-10 col-md-10 comparison_bodyP2">
                <div class="row">
                    {!! $iHtml4 !!}   
                </div>                                     
              </div>
            </div>

            <div class="row" id="comparison_portfolio_attributes">
              <div class="col-12 col-md-12 pr-2">
                <div class="comparison_head">
                    <span><input type="checkbox" name="portfolio_attributes_all" id="portfolio_attributes_all" value="" onchange="changeAllCheckbox('portfolio_attributes')"  <?php echo (count($portfolio_checkbox) == 17)?'checked':'';?>></span>
                    <span>PORTFOLIO ATTRIBUTES</span>
                </div>
              </div>
              <div class="col-2 col-md-2 comparison_bodyP1" id="portfolio_attributes_id">
                <ul class="list-group">
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="portfolio_pb_ratio" <?php echo (in_array("portfolio_pb_ratio", $portfolio_checkbox))?'checked':'';?>></span>
                        Portfolio P/B Ratio
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="portfolio_pe_ratio" <?php echo (in_array("portfolio_pe_ratio", $portfolio_checkbox))?'checked':'';?>></span>
                        Portfolio P/E Ratio
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="dividend_yield" <?php echo (in_array("dividend_yield", $portfolio_checkbox))?'checked':'';?>></span>
                        Dividend Yield
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="turnover_ratio" <?php echo (in_array("turnover_ratio", $portfolio_checkbox))?'checked':'';?>></span>
                        Turnover Ratio (%)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="no_of_stocks" <?php echo (in_array("no_of_stocks", $portfolio_checkbox))?'checked':'';?>></span>
                        No. of Stocks
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="avg_market_cap" <?php echo (in_array("avg_market_cap", $portfolio_checkbox))?'checked':'';?>></span>
                        Avg Mkt Cap (Rs Cr)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="large_cap" <?php echo (in_array("large_cap", $portfolio_checkbox))?'checked':'';?>></span>
                        Large Cap (%)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="mid_cap" <?php echo (in_array("mid_cap", $portfolio_checkbox))?'checked':'';?>></span>
                        Mid Cap (%)
                    </li>
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="small_cap" <?php echo (in_array("small_cap", $portfolio_checkbox))?'checked':'';?>></span>
                        Small Cap (%)
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="average_maturity" <?php echo (in_array("average_maturity", $portfolio_checkbox))?'checked':'';?>></span>
                        Average Maturity
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="modified_duration" <?php echo (in_array("modified_duration", $portfolio_checkbox))?'checked':'';?>></span>
                        Modified Duration
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="yield_to_maturity" <?php echo (in_array("yield_to_maturity", $portfolio_checkbox))?'checked':'';?>></span>
                        YTM (%)
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="sovereign_rating" <?php echo (in_array("sovereign_rating", $portfolio_checkbox))?'checked':'';?>></span>
                        Sovereign Rating
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="aaa_rated" <?php echo (in_array("aaa_rated", $portfolio_checkbox))?'checked':'';?>></span>
                        AAA Rated
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="aa_rated" <?php echo (in_array("aa_rated", $portfolio_checkbox))?'checked':'';?>></span>
                        AA Rated
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="a_rated" <?php echo (in_array("a_rated", $portfolio_checkbox))?'checked':'';?>></span>
                        A Rated
                    </li> 
                    <li class="list-group-item">
                        <span><input type="checkbox" name="portfolio_checkbox[]" id="portfolio_checkbox" value="unrated" <?php echo (in_array("unrated", $portfolio_checkbox))?'checked':'';?>></span>
                        Unrated
                    </li>             
                </ul>                       
              </div>
              <div class="col-10 col-md-10 comparison_bodyP2">
                <div class="row">
                    {!! $iHtml5 !!}
                </div>                                     
              </div>
            </div>

            <div class="row" id="comparison_portfolio_market" style="display:none;">
                <div class="col-12 col-md-12 pr-2">
                    <div class="comparison_head">
                        <span><input type="checkbox" name="top_sector_all" id="top_sector_all" value="" onchange="changeAllCheckbox('top_sector')" checked></span>
                        <span>TOP 3 Sector (%)</span>
                    </div>
                </div>
                <div class="col-2 col-md-2 comparison_bodyP1" id="top_sector_id">
                    <ul class="list-group">
                        <li class="list-group-item avgMkCap">
                            <span><input type="checkbox" name="sector_checkbox[]" id="sector_checkbox" value="sector_1" checked></span>
                            Sector 1
                        </li>                               
                        <li class="list-group-item avgMkCap">
                            <span><input type="checkbox" name="sector_checkbox[]" id="sector_checkbox" value="sector_2" checked></span>
                            Sector 2
                        </li>                               
                        <li class="list-group-item avgMkCap">
                            <span><input type="checkbox" name="sector_checkbox[]" id="sector_checkbox" value="sector_3" checked></span>
                            Sector 3
                        </li>                               
                    </ul>                       
                </div>
                <div class="col-10 col-md-10 comparison_bodyP2">
                    <div class="row">
                        {!! $iHtml6 !!}
                    </div>                                     
                </div>
            </div>                 
            <div class="row" id="comparison_top_10_holdings">
                <div class="col-12 col-md-12 pr-2"> 
                    <div class="comparison_head">
                        <span><input type="checkbox" name="top_holding_all" id="top_holding_all" value="1"  <?php echo ($holding_checkbox)?'checked':'';?>></span>
                        <span>TOP HOLDINGS (%)</span>
                    </div>
                </div>
                <div class="col-2 col-md-2 comparison_bodyP1" id="top_holding_id">
                    <ul class="list-group">
                        <li class="list-group-item">
                            Holding 1
                        </li>
                        <li class="list-group-item">
                            Holding 2
                        </li>
                          <li class="list-group-item">
                            Holding 3
                        </li>
                        <li class="list-group-item">
                            Holding 4
                        </li>
                        <li class="list-group-item">
                            Holding 5
                        </li>  
                        <li class="list-group-item">
                            Holding 6
                        </li>  
                        <li class="list-group-item">
                            Holding 7
                        </li>  
                        <li class="list-group-item">
                            Holding 8
                        </li>  
                        <li class="list-group-item">
                            Holding 9
                        </li>
                        <li class="list-group-item">
                            Holding 10
                        </li>
                    </ul>                       
                </div>
                <div class="col-10 col-md-10 comparison_bodyP2">
                    <div class="row">
                        {!! $iHtml7 !!}
                    </div>                                     
                </div>
            </div>   
        </div>
    </div>
    
                    
                    <div class="row mt-1">
                        @php
                            $note_data1 = \App\Models\Mfresearch_note::where('id',2)->first();
                            if(!empty($note_data1)){
                            @endphp
                            {!!$note_data1->description!!}
                        @php } @endphp
                    </div>
                    <div class="row">
                        Report Date : {{date('d/m/Y')}}
                    </div>
    </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}images/shape2.png" alt="" />
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
                    <h4>Do you want to update data</h4>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveModalData();">SAVE</button>
            </div>
        </div>
      </div>
    </div>
@endsection