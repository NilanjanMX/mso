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
        });

        var $disabledResults = $("#compare_schemecode");
        $disabledResults.select2({
            placeholder: "Search Stock",
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

        function processInfo(inho){
            console.log(inho.length);
            document.getElementById("page_length").value = inho.length;
        }

        
        var stock_table = $('#stock_table').dataTable({
            "bPaginate": true,
            "searching": false, 
            "lengthMenu": [[15,30,50,100,-1], ["Top 15","Top 30","Top 50","Top 100", "All"]],
            drawCallback : function() {
               processInfo(this.api().page.info());
           },
            "order": [[ 0, "asc" ]]
        });

        function clickDownloadButton(){
            var sortedCol = $('#stock_table').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#stock_table').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
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
            
            /*.stock_table thead th {*/
            /*    background: #bee5f6;*/
            /*}*/
            /*.table-bordered thead td, .table-bordered thead th {*/
            /*    border-bottom-width: 1px;*/
            /*}*/
            /*.table thead th {*/
            /*    vertical-align: bottom;*/
            /*    border-bottom: 1px solid #929292;*/
            /*    font-weight: 600 !important;*/
            /*}*/
            /*.table-bordered.stock_table {*/
            /*    border: 1px solid #929292;*/
            /*}*/
            /*.table-bordered.stock_table td, .table-bordered.stock_table th {*/
            /*    border: 1px solid #929292;*/
            /*    line-height: 15px;*/
            /*    padding: 6px 5px;*/
            /*    vertical-align: middle;*/
            /*    font-family: "Poppins", sans-serif;*/
            /*}*/
            /*.table-bordered.stock_table td {*/
            /*    padding: 9px 5px;*/
            /*}*/
            /*.table-bordered.stock_table tr td:first-child {*/
            /*    text-align: left;*/
            /*}*/
            /*.table-bordered.stock_table tbody tr:nth-child(even) {background: #f0f1f6}*/
            /*.table-bordered.stock_table tbody tr:nth-child(odd) {background: #FFF}*/
            
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
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">New Stocks Bought</h2>
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

            <div class="row" style="padding-bottom: 10px; font-size: 12px;">
                
                <div class="col-md-12 text-center pt-2">
                    <form action="{{route('frontend.stocks_held_action')}}" method="get" id="save_form_data">
                      <input type="hidden" name="page_type" id="page_type" value="DOWNLOAD">
                      <input type="hidden" name="page_name" id="page_name" value="ATTRACTING">
                      <input type="hidden" name="shorting_id" id="shorting_id" value="">
                      <input type="hidden" name="page_length" id="page_length" value="">
                      @if (Auth::check())
                        @if(Auth::user()->permission_scanner)
                            <button type="submit" onclick="clickDownloadButton();" class="btn btn-success btn-sm downloadButton">
                                Download
                            </button>
                        @else
                            <button type="button" class="btn btn-success btn-sm downloadButton" onclick="checkError(2);">
                                Download
                            </button>
                        @endif
                       @else
                            <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="checkError(1);">
                                Download
                            </button>
                       @endif
                      
                    </form>
                </div>
            </div>
            
            <div class="row Search_Here" style="margin-top: 10px;">
                
              <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" >
                <div style="float: right; margin-bottom: -30px;">
                    Month : {{date('F, Y', strtotime($invdate))}} 
                </div>
                <div>
                    
                    <table class="table text-center stock_table mfliketbl" id="stock_table">
                        <thead>
                            <tr>
                                <th>Stock Name</th>
                                <th>Sector</th>
                                <th>Classification</th>
                                <th>Net Qty Bought</th>
                                <th>Buy Value (In Rs Cr)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($list)){ ?>
                                <?php foreach ($list as $key => $value) { ?>
                                    <tr>
                                        <td align="left">{{$value->compname}}</td>
                                        <td align="left">{{$value->Industry}}</td>
                                        <td align="left">{{$value->mode}}</td>
                                        <td align="right">{{custome_money_format($value->noshares)}}</td>
                                        <td align="right">{{number_format((float)($value->mktval/100), 2, '.', '')}}</td>
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
              </div>
            </div>
            
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('f/images/shape2.png')}}" alt="">
        </div>
    </section>
    
    <div class="modal fade" id="errorBeforeLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is available only to members.</p>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
                <a href="{{url('login')}}" class="btn btn-secondary btnblue">Login</a>
                <a href="{{url('membership')}}" class="btn btn-primary btnblue">Become a member</a>
            </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="errorAfterLoginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="success_model_body" style="text-align: center;font-size: 16px;">This feature is not available in the current plan.</p>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Cancel</button>
                <a href="{{route('account.upgradePackage')}}" class="btn btn-secondary btnblue">Upgrade plan</a>
            </div>
        </div>
      </div>
    </div>

    <script type="text/javascript">

        function checkError(type){
          if(type == 1){
              $("#errorBeforeLoginModal").modal('show');
          }else{
              $("#errorAfterLoginModal").modal('show');
          }  
        }
    </script>

@endsection
