@extends('layouts.frontend')

@section('js_after')
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/colreorder/1.5.6/js/dataTables.colReorder.min.js"></script>

    <script type="text/javascript">
        var global_compare_url = "{{route('frontend.mf_scanner_compare')}}";
        var global_save_url = "{{route('frontend.mf_scanner_save')}}";
        var global_download_url = "{{route('frontend.mf_scanner_download')}}";
        var global_result = [];
        var global_all_filed = [];
        var table_html = "";
        var glob_checked_img = "{{asset('')}}/img/star_icon-checked.png";
        var glob_unchecked_img = "{{asset('')}}/img/star_icon-unchecked.png";

        var edit_saved_filter_id = 0;

        var default_freeze = 0;
      
        var global_url = "{{route('frontend.factsheet_new')}}?schemecode%5B0%5D=";

        var global_compare_list = <?php echo json_encode($global_compare_list);?>;
        var global_saved_filter = <?php echo json_encode($saved_filter);?>;

        var global_equity_list = <?php echo json_encode($equity_list);?>;
        var global_debt_list = <?php echo json_encode($debt_list);?>;
        var global_hybrid_list = <?php echo json_encode($hybrid_list);?>;
        var global_other_list = <?php echo json_encode($other_list);?>;

        var global_fund_house_list = <?php echo json_encode($fund_house_list);?>;
        var global_fund_manager_list = <?php echo json_encode($fund_manager_list);?>;
        var global_primary_benchmark_list = <?php echo json_encode($primary_benchmark_list);?>;
        var global_fund_type_list = <?php echo json_encode($fund_type_list);?>;
        var global_option_list = <?php echo json_encode($option_list);?>;
        var global_plan_list = <?php echo json_encode($plan_list);?>;

        var global_other_list = <?php echo json_encode($other_list);?>;

        var global_selected_value = <?php echo json_encode($global_selected_value);?>;

        var global_table_list = [];
      
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

        var global_selected_row = [];

        function renderReturnData(){
            global_selected_value.response = (global_selected_value.response)?global_selected_value.response:[];
            global_table_list = global_selected_value.response;
        }

        function renderAssertClass(){
            
            global_selected_value.ael_array = (global_selected_value.ael)?global_selected_value.ael.split(','):[];
            
            var iHtml = ``;
            var is_checked = "";
            global_equity_list.forEach(function(val){
                var class_name = val.class_name;
                if(!class_name){
                    class_name = val.classname;
                }
                is_checked = "";
                if(global_selected_value.ael_array.includes(""+val.classcode)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="equity_fund_`+val.classcode+`">
                                    <input type="checkbox" data="`+class_name+`" class="form-check-input" id="equity_fund_`+val.classcode+`" name="equity_fund[]" value="`+val.classcode+`" onchange="renderView();" `+is_checked+`>
                                        `+class_name+`
                                  </label>
                                </div>`;
            });
            document.getElementById("asset_class_selection_equity").innerHTML = iHtml;

            iHtml = ``;
            global_selected_value.adl_array = (global_selected_value.adl)?global_selected_value.adl.split(','):[];
            global_debt_list.forEach(function(val){
                var class_name = val.class_name;
                if(!class_name){
                    class_name = val.classname;
                }
                is_checked = "";
                if(global_selected_value.adl_array.includes(""+val.classcode)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="debt_fund_`+val.classcode+`">
                                    <input type="checkbox" data="`+class_name+`" class="form-check-input" id="debt_fund_`+val.classcode+`" name="debt_fund[]" value="`+val.classcode+`" onchange="renderView();" `+is_checked+`>
                                        `+class_name+`
                                  </label>
                                </div>`;
            });
            document.getElementById("asset_class_selection_debt").innerHTML = iHtml;

            iHtml = ``;
            global_selected_value.ahl_array = (global_selected_value.ahl)?global_selected_value.ahl.split(','):[];
            global_hybrid_list.forEach(function(val){
                var class_name = val.class_name;
                if(!class_name){
                    class_name = val.classname;
                }
                is_checked = "";
                if(global_selected_value.ahl_array.includes(""+val.classcode)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="hybrid_fund_`+val.classcode+`">
                                    <input type="checkbox" data="`+class_name+`" class="form-check-input" id="hybrid_fund_all_`+val.classcode+`" name="hybrid_fund[]" value="`+val.classcode+`" onchange="renderView();" `+is_checked+`>
                                        `+class_name+`
                                  </label>
                                </div>`;
            });
            document.getElementById("asset_class_selection_hybrid").innerHTML = iHtml;

            iHtml = ``;
            global_selected_value.aol_array = (global_selected_value.aol)?global_selected_value.aol.split(','):[];
            global_other_list.forEach(function(val){
                var class_name = val.class_name;
                if(!class_name){
                    class_name = val.classname;
                }
                is_checked = "";
                if(global_selected_value.aol_array.includes(""+val.classcode)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="other_fund_`+val.classcode+`">
                                    <input type="checkbox" data="`+class_name+`" class="form-check-input" id="other_fund_`+val.classcode+`" name="other_fund[]" value="`+val.classcode+`" onchange="renderView();" `+is_checked+`>
                                        `+class_name+`
                                  </label>
                                </div>`;
            });
            document.getElementById("asset_class_selection_other").innerHTML = iHtml;
        }

        function renderFilters(){
            var iHtml = ``;
            var is_checked = "";

            global_selected_value.fhl_array = (global_selected_value.fhl)?global_selected_value.fhl.split(','):[];
            global_fund_house_list.forEach(function(val){
                is_checked = "";
                if(global_selected_value.fhl_array.includes(""+val.amc_code)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="fund_house_`+val.amc_code+`">
                                    <input type="checkbox" data="`+val.fund+`" class="form-check-input" id="fund_house_`+val.amc_code+`" name="fund_house[]" value="`+val.amc_code+`" onchange="renderView();" `+is_checked+`>
                                        `+val.fund+`
                                  </label>
                                </div>`;
            });
            document.getElementById("filter_fund_house").innerHTML = iHtml;
            
            iHtml = ``;
            global_selected_value.fml_array = (global_selected_value.fml)?global_selected_value.fml.split(','):[];
            global_fund_manager_list.forEach(function(val){
                is_checked = "";
                if(global_selected_value.fml_array.includes(""+val.id)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="fund_manager_`+val.id+`">
                                    <input type="checkbox" data="`+val.fundmanager+`" class="form-check-input" id="fund_manager_`+val.id+`" name="fund_manager[]" value="`+val.id+`" onchange="renderView();" `+is_checked+`>
                                        `+val.fundmanager+`
                                  </label>
                                </div>`;
            });
            document.getElementById("filter_fund_manager").innerHTML = iHtml;
            
            iHtml = ``;
            global_selected_value.pbl_array = (global_selected_value.pbl)?global_selected_value.pbl.split(','):[];
            global_primary_benchmark_list.forEach(function(val){
                is_checked = "";
                if(global_selected_value.pbl_array.includes(""+val.IndexCode)){
                    is_checked = "checked";
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="primary_benchmark_`+val.IndexCode+`">
                                    <input type="checkbox" data="`+val.IndexName+`" class="form-check-input" id="primary_benchmark_`+val.IndexCode+`" name="primary_benchmark[]" value="`+val.IndexCode+`" onchange="renderView();" `+is_checked+`>
                                        `+val.IndexName+`
                                  </label>
                                </div>`;
            });
            document.getElementById("filter_primary_benchmark").innerHTML = iHtml;

            global_selected_value.amurange_array = (global_selected_value.amurange)?global_selected_value.amurange.split(','):[];

            is_checked = "";
            if(global_selected_value.amurange_array.includes("1")){
                is_checked = "checked";
            }

            iHtml = `<div class="form-check">
                        <label class="form-check-label" for="amu_range_1">
                            <input type="checkbox" data="0-500" class="form-check-input" id="amu_range_1" name="amu_range[]" value="1" onchange="renderView();" `+is_checked+`>0-500
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("2")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_2">
                            <input type="checkbox" data="500-750" class="form-check-input" id="amu_range_2" name="amu_range[]" value="2" onchange="renderView();" `+is_checked+`>500-750
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("3")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_3">
                            <input type="checkbox" data="750-2000" class="form-check-input" id="amu_range_3" name="amu_range[]" value="3" onchange="renderView();" `+is_checked+`>750-2000
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("4")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_4">
                            <input type="checkbox" data="2000-5000" class="form-check-input" id="amu_range_4" name="amu_range[]" value="4" onchange="renderView();" `+is_checked+`>2000-5000
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("5")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_5">
                            <input type="checkbox" data="5000-10000" class="form-check-input" id="amu_range_5" name="amu_range[]" value="5" onchange="renderView();" `+is_checked+`>5000-10000
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("6")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_6">
                            <input type="checkbox" data="10000-50000" class="form-check-input" id="amu_range_6" name="amu_range[]" value="6" onchange="renderView();" `+is_checked+`>10000-50000
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.amurange_array.includes("7")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="amu_range_7">
                            <input type="checkbox" data=">50000" class="form-check-input" id="amu_range_7" name="amu_range[]" value="7" onchange="renderView();"  `+is_checked+`> >50000
                        </label>
                    </div>`;

            document.getElementById("filter_amu_range").innerHTML = iHtml;

            iHtml = ``;
            global_selected_value.pl_array = (global_selected_value.pl)?global_selected_value.pl.split(','):[];
            var planname="";
            global_plan_list.forEach(function(val){
                is_checked = "";
                if(global_selected_value.pl_array.includes(""+val.plan_code)){
                    is_checked = "checked";
                }
                var planname = val.planname;
                if(!planname){
                    planname = val.plan;
                }
                iHtml = iHtml + `<div class="form-check">
                                  <label class="form-check-label" for="plan_`+val.plan_code+`">
                                    <input type="checkbox" data="`+planname+`" class="form-check-input" id="plan_`+val.plan_code+`" name="plan[]" value="`+val.plan_code+`" onchange="renderView();" `+is_checked+`>
                                        `+planname+`
                                  </label>
                                </div>`;
            });
            document.getElementById("filter_plan").innerHTML = iHtml;

            global_selected_value.rating_array = (global_selected_value.rating)?global_selected_value.rating.split(','):[];

            is_checked = "";
            if(global_selected_value.rating_array.includes("5")){
                is_checked = "checked";
            }

            iHtml = `<div class="form-check">
                        <label class="form-check-label" for="rating_5">
                            <input type="checkbox" data="5" class="form-check-input" id="rating_5" name="rating[]" value="5" onchange="renderView();" `+is_checked+`>
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.rating_array.includes("4")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="rating_4">
                            <input type="checkbox" data="4" class="form-check-input" id="rating_4" name="rating[]" value="4" onchange="renderView();" `+is_checked+`>
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.rating_array.includes("3")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="rating_3">
                            <input type="checkbox" data="3" class="form-check-input" id="rating_3" name="rating[]" value="3" onchange="renderView();" `+is_checked+`>
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.rating_array.includes("2")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="rating_2">
                            <input type="checkbox" data="2" class="form-check-input" id="rating_2" name="rating[]" value="2" onchange="renderView();" `+is_checked+`>
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.rating_array.includes("1")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="rating_1">
                            <input type="checkbox" data="1" class="form-check-input" id="rating_1" name="rating[]" value="1" onchange="renderView();" `+is_checked+`>
                            <img  src="`+glob_checked_img+`" style="width: 13px;margin-top: -7px;">
                        </label>
                    </div>`;

            is_checked = "";
            if(global_selected_value.rating_array.includes("0")){
                is_checked = "checked";
            }
            iHtml = iHtml + `<div class="form-check">
                        <label class="form-check-label" for="rating_0">
                            <input type="checkbox" data="0" class="form-check-input" id="rating_0" name="rating[]" value="0" onchange="renderView();" `+is_checked+`>
                            Unrated
                        </label>
                    </div>`;

            document.getElementById("filter_rating").innerHTML = iHtml;

        }

        function renderChooseColumns(){
            var iHtml1 = ``;
            var iHtml2 = ``;
            var iHtml3 = ``;
            var iHtml4 = ``;
            var is_checked = "";

            global_selected_value.response = (global_selected_value.response)?global_selected_value.response:[];
            global_compare_list.forEach(function(val){
                is_checked = "";
                if(global_selected_value.response.find( o => o.id === val.id)){
                    is_checked = "checked";
                }
                if(val.type == 1){
                    iHtml1 = iHtml1 + `<div class="form-check">
                                        <label class="form-check-label" for="cc_basic_detail_`+val.id+`">
                                            <input type="checkbox" class="form-check-input" id="cc_basic_detail_`+val.id+`" name="basic_detail[]" value="`+val.id+`" onchange="renderRow('cc_basic_detail_','`+val.id+`');" `+is_checked+`>
                                            `+val.name+`
                                        </label>
                                    </div>`;
                }else if(val.type == 2){
                    iHtml2 = iHtml2 + `<div class="form-check">
                                        <label class="form-check-label" for="cc_return_`+val.id+`">
                                            <input type="checkbox" class="form-check-input" id="cc_return_`+val.id+`" name="return[]" value="`+val.id+`" onchange="renderRow('cc_return_','`+val.id+`');"  `+is_checked+`>
                                            `+val.name+`
                                        </label>
                                    </div>`;
                }else if(val.type == 3){
                    iHtml3 = iHtml3 + `<div class="form-check">
                                        <label class="form-check-label" for="cc_portfolio_att_`+val.id+`">
                                            <input type="checkbox" class="form-check-input" id="cc_portfolio_att_`+val.id+`" name="portfolio_attribute[]" value="`+val.id+`" onchange="renderRow('cc_portfolio_att_','`+val.id+`');"  `+is_checked+`>
                                            `+val.name+`
                                        </label>
                                    </div>`;
                }else if(val.type == 4){
                    iHtml4 = iHtml4 + `<div class="form-check">
                                        <label class="form-check-label" for="cc_mf_ratio_`+val.id+`">
                                            <input type="checkbox" class="form-check-input" id="cc_mf_ratio_`+val.id+`" name="mf_ratios[]" value="`+val.id+`" onchange="renderRow('cc_mf_ratio_','`+val.id+`');"  `+is_checked+`>
                                            `+val.name+`
                                        </label>
                                    </div>`;
                }
            });
            document.getElementById("cc_basic_detail").innerHTML = iHtml1;
            document.getElementById("cc_return").innerHTML = iHtml2;
            document.getElementById("cc_portfolio_att").innerHTML = iHtml3;
            document.getElementById("cc_mf_ratio").innerHTML = iHtml4;
        }

      
        function changeTab(key_name) {
            document.getElementById('asset_class_selection').classList.remove('active-nav-link');
            document.getElementById('filters').classList.remove('active-nav-link');
            document.getElementById('choose_columns').classList.remove('active-nav-link');

            document.getElementById('asset_class_selection_view').classList.remove('active');
            document.getElementById('filters_view').classList.remove('active');
            document.getElementById('choose_columns_view').classList.remove('active');

            document.getElementById('asset_class_selection_view').classList.remove('fade');
            document.getElementById('filters_view').classList.remove('fade');
            document.getElementById('choose_columns_view').classList.remove('fade');

            if(key_name == "asset_class_selection"){
                // console.log(1);
                document.getElementById('filters_view').classList.add('fade');
                document.getElementById('choose_columns_view').classList.add('fade');
                document.getElementById('asset_class_selection_view').classList.add('active');
            }else if(key_name == "filters"){
                // console.log(2);
                document.getElementById('asset_class_selection_view').classList.add('fade');
                document.getElementById('choose_columns_view').classList.add('fade');
                document.getElementById('filters_view').classList.add('active');
            }else if(key_name == "choose_columns"){
                // console.log(3);
                document.getElementById('asset_class_selection_view').classList.add('fade');
                document.getElementById('filters_view').classList.add('fade');
                document.getElementById('choose_columns_view').classList.add('active');
            }
            document.getElementById(key_name).classList.add('active-nav-link');

        }

        function closeAllTab(){
            document.getElementById('asset_class_selection').classList.remove('active-nav-link');
            document.getElementById('filters').classList.remove('active-nav-link');
            document.getElementById('choose_columns').classList.remove('active-nav-link');

            document.getElementById('asset_class_selection_view').classList.remove('active');
            document.getElementById('filters_view').classList.remove('active');
            document.getElementById('choose_columns_view').classList.remove('active');

            document.getElementById('asset_class_selection_view').classList.remove('fade');
            document.getElementById('filters_view').classList.remove('fade');
            document.getElementById('choose_columns_view').classList.remove('fade');

            document.getElementById('asset_class_selection_view').classList.add('fade');
            document.getElementById('filters_view').classList.add('fade');
            document.getElementById('choose_columns_view').classList.add('fade');
        }

        function removeAllCheckBox(id){
            var filter_brand_list_id = document.getElementById(id);
            var filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]:checked');

            // var brand = [];choose_columns_view  asset_class_selection_view  filters_view
            filter_brand_list_id_i.forEach(function(val){
                // brand.push(val.value);
                $(val).removeAttr('checked');
            });
            
            if(id == "choose_columns_view"){
                // console.log(filter_brand_list_id_i);
                global_compare_list.forEach(function(val){
                  if(val.is_checked){
                    if(val.type == 1){
                      $("#cc_basic_detail_"+val.id).attr('checked',true);
                    }else if(val.type == 2){
                      $("#cc_return_"+val.id).attr('checked',true);
                    }else if(val.type == 3){
                      $("#cc_portfolio_att_"+val.id).attr('checked',true);
                    }else{
                      $("#cc_mf_ratio_"+val.id).attr('checked',true);
                    }
                  }
                });
            }else if(id == "asset_class_selection_view"){
                $("#equity_fund_31").attr('checked',true);
            }else if(id == "filters_view"){
                $("#plan_6").attr('checked',true);
                filter_brand_list_id = document.getElementById('filter_amu_range');
                filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');
                filter_brand_list_id_i.forEach(function(val){
                    $(val).attr('checked',true);
                });
                filter_brand_list_id = document.getElementById('filter_primary_benchmark');
                filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');
                filter_brand_list_id_i.forEach(function(val){
                    $(val).attr('checked',true);
                });
                filter_brand_list_id = document.getElementById('filter_fund_manager');
                filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');
                filter_brand_list_id_i.forEach(function(val){
                    $(val).attr('checked',true);
                });
                filter_brand_list_id = document.getElementById('filter_fund_house');
                filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');
                filter_brand_list_id_i.forEach(function(val){
                    $(val).attr('checked',true);
                });
            }

            renderView();
        }

        function changeAllCheckbox(id,checked_id){
            var checkbox_flag = $("#"+checked_id).attr('checked');
            var filter_brand_list_id = document.getElementById(id);
              var filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]');
              filter_brand_list_id_i.forEach(function(val){
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
            $("#"+id).removeAttr('checked');
            renderView();
        }

        function renderAllFilterView(){
            var filter_brand_list_id = document.getElementById('asset_class_selection_view').querySelectorAll('input[type=checkbox]:checked');
            var return_list = ``;
            filter_brand_list_id.forEach(function(val){
                // return_list.push($(val).val());
                return_list = return_list+`<span class="category-view-span">
                            `+$(val).attr('data')+`
                            <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+$(val).attr('id')+`');"></span>
                        </span>`;
            });
            document.getElementById('category_view').innerHTML = return_list;
            var filter_brand_list_id = document.getElementById('filter_fund_type').querySelectorAll('input[type=checkbox]:checked');
            return_list = ``;
            filter_brand_list_id.forEach(function(val){
                // return_list.push($(val).val());
                return_list = return_list+`<span class="category-view-span">
                            `+$(val).attr('data')+`
                            <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+$(val).attr('id')+`');"></span>
                        </span>`;
            });
            document.getElementById('fund_type_view').innerHTML = return_list;
            var filter_brand_list_id = document.getElementById('filter_option').querySelectorAll('input[type=checkbox]:checked');
            return_list = ``;
            filter_brand_list_id.forEach(function(val){
                // return_list.push($(val).val());
                return_list = return_list+`<span class="category-view-span">
                            `+$(val).attr('data')+`
                            <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+$(val).attr('id')+`');"></span>
                        </span>`;
            });
            document.getElementById('option_view').innerHTML = return_list;
            var filter_brand_list_id = document.getElementById('filter_plan').querySelectorAll('input[type=checkbox]:checked');
            return_list = ``;
            filter_brand_list_id.forEach(function(val){
                console.log(val);
                // return_list.push($(val).val());
                return_list = return_list+`<span class="category-view-span">
                            `+$(val).attr('data')+`
                            <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+$(val).attr('id')+`');"></span>
                        </span>`;
            });
            document.getElementById('plan_view').innerHTML = return_list;
            var filter_brand_list_id = document.getElementById('filter_amu_range').querySelectorAll('input[type=checkbox]:checked');
            return_list = ``;
            filter_brand_list_id.forEach(function(val){
                // return_list.push($(val).val());
                return_list = return_list+`<span class="category-view-span">
                            `+$(val).attr('data')+`
                            <span class="icon-cancel asset_child equities1" style="font-size:18px;" onclick="removeFilterView('`+$(val).attr('id')+`');"></span>
                        </span>`;
            });
            // console.log(return_list);
            document.getElementById('amu_range_view').innerHTML = return_list;
        }

        function renderView(){
            var all_data = {};
            all_data._token = "{{ csrf_token() }}";
            all_data.ael = getAllBoxCheckedForApi('asset_class_selection_equity');
            all_data.adl = getAllBoxCheckedForApi('asset_class_selection_debt');
            all_data.ahl = getAllBoxCheckedForApi('asset_class_selection_hybrid');
            all_data.aol = getAllBoxCheckedForApi('asset_class_selection_other');

            all_data.fhl = getAllBoxCheckedForApi('filter_fund_house');
            all_data.fml = getAllBoxCheckedForApi('filter_fund_manager');
            all_data.pbl = getAllBoxCheckedForApi('filter_primary_benchmark');
            all_data.ftl = 1;//getAllBoxCheckedForApi('filter_fund_type');
            all_data.ol = 1;//getAllBoxCheckedForApi('filter_option');
            all_data.pl = getAllBoxCheckedForApi('filter_plan');
            all_data.amurange = getAllBoxCheckedForApi('filter_amu_range');
            all_data.rating = getAllBoxCheckedForApi('filter_rating');

            renderAllFilterView();
            callAPi(all_data);
            // console.log(all_data);
        }

        function highligts() {
            var filter_brand_list_id = document.getElementById('mf_scanner_list').querySelectorAll('input[class=schemecheckbox]:checked');

            console.log("kkk", filter_brand_list_id);
            var no_checked_id = document.getElementById('mf_scanner_list').querySelectorAll('input[class=schemecheckbox]:not(:checked)')

            for (let i = 0; i < no_checked_id.length; i++) {
                let tr_main =  no_checked_id[i].parentNode.parentNode;
                tr_main.style.backgroundColor = '';


                let td1 = tr_main.querySelector('td:first-child');
                let td2 = tr_main.querySelector('td:nth-child(2)')
                // td1.style.backgroundColor = '';
                // td2.style.backgroundColor = '';
                let tdElements = tr_main.getElementsByTagName('td');

                for (let j = 0; j < tdElements.length; j++) {
                    tdElements[j].style.backgroundColor = '';
                }
                
            }

            for (let i = 0; i < filter_brand_list_id.length; i++) {
                let tr_main =  filter_brand_list_id[i].parentNode.parentNode;
                tr_main.style.backgroundColor = '#c7edcf';
                let tdElements = tr_main.getElementsByTagName('td');

                for (let j = 0; j < tdElements.length; j++) {
                    tdElements[j].style.backgroundColor = '#c7edcf';
                }

                let td1 = tr_main.querySelector('td:first-child');
                let td2 = tr_main.querySelector('td:nth-child(2)')
                // td2.style.backgroundColor = '#c7edcf';
                // td1.style.backgroundColor = '#c7edcf';
            }
        }

        function changeRowCheckbox(){
            highligts();
            var filter_brand_list_id = document.getElementById('mf_scanner_list').querySelectorAll('input[class=schemecheckbox]:checked');

            console.log("kkk", filter_brand_list_id);
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
            console.log("gggg",global_selected_row, mf_scanner_compare_value);
            document.getElementById("schemecode_id").value = mf_scanner_compare_value;
        }

        var is_first_time = true;
        var glo_aum = 0;
        var glo_aum_date = "";
        var trackingErrorCategory = [82,26,43,80,7,78,79,34];
        var glob_checked = "";
        var is_freeze = "";
       
         
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

        function checkedAllBox() {
            let length = document.getElementsByClassName('schemecheckbox').length;
            $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0] = 1;
            let checkboxVal = [];
            if ($('#all_checkbox').prop('checked')==true){ 
                for (let i = 0; i < length; i++) {
                    document.getElementsByClassName('schemecheckbox')[i].checked = true;
                    checkboxVal.push(document.getElementsByClassName('schemecheckbox')[i].value);
                }
                let checkboxValStr = checkboxVal.toString();
                document.getElementById("schemecode_id").value = checkboxValStr;
                // changeRowCheckbox();
            } else {
                for (let i = 0; i < length; i++) {
                    document.getElementsByClassName('schemecheckbox')[i].checked = false;
                
                }
                document.getElementById("schemecode_id").value = null;
            }
            highligts();
        }
        
        function renderTableView(){
            var checked_schemecode_id = document.getElementById("schemecode_id").value;
            // console.log('aa-'+checked_schemecode_id);
            var checked_schemecode_ids = checked_schemecode_id.split(',');
            console.log("checked_schemecode_ids");
            console.log(checked_schemecode_ids);
            console.log(checked_schemecode_id);

            table_html = "<thead>";
            table_html = table_html+"<tr id='list_checkbox_tr'>";
            table_html = table_html+"<td rowspan='1' class='firstrow firstHeading'></td>"; // "firstrow firstHeading" added by biswanath
            table_html = table_html+"<td rowspan='1' class='secondrow secondHeading'></td>"; // "secondrow secondHeading" added by biswanath
            //table_html = table_html+"<td rowspan='1'><input type='checkbox' id='category_checkbox' name='category_checkbox' value='0' checked></td>";
            var i_flag = 2;
            
            global_table_list.forEach(function(val){
              glob_checked = "";
              if(val.table_checkbox == 1){
                glob_checked = "checked";
              }
              if(val.is_freeze == 1){
                i_flag = i_flag + 1
                if(i_flag == 3){
                    is_freeze = "thirdrow thirdHeading";
                    $('.theDiv').css('margin-left', 530);
                }else if(i_flag == 4){
                    is_freeze = "fourthrow fourthHeading";
                    $('.theDiv').css('margin-left', 731);
                }else if(i_flag == 5){
                    is_freeze = "fivethrow fivethHeading";
                }else if(i_flag == 6){
                    is_freeze = "sixthrow sixthHeading";
                }else{
                    is_freeze = "";
                }         
              }else{
                  is_freeze = "";
              }

              table_html = table_html+'<td class="'+is_freeze+'"><input type="checkbox" id="list_checkbox_'+val.id+'" name="list_checkbox[]" onchange="changeTableCheckbox('+val.id+');" value="'+val.key_name+','+val.name+'" '+glob_checked+'></td>';
            });
            console.log("i_flag");
            console.log(i_flag);
            if(i_flag <= 2) {
                $('.theDiv').css('margin-left', 331);
            }

            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";

            table_html = table_html+'<td class="firstrow firstHeading">All <input type="checkbox" id="all_checkbox" onchange="checkedAllBox()"></td>';
            table_html = table_html+'<td class="secondrow secondHeading"><b>Fund</b></td>';
            //table_html = table_html+'<td>Category</td>';
            var i_flag = 2;
            var shot_name = "";
            global_table_list.forEach(function(val){
                if(val.is_freeze == 1){
                    i_flag = i_flag + 1
                    if(i_flag == 3){
                        is_freeze = "thirdrow thirdHeading";
                    }else if(i_flag == 4){
                        is_freeze = "fourthrow fourthHeading";
                    }else if(i_flag == 5){
                        is_freeze = "fivethrow fivethHeading";
                    }else if(i_flag == 6){
                        is_freeze = "sixthrow sixthHeading";
                    }else{
                        is_freeze = "";
                    }                
                }else{
                    is_freeze = "";
                }
                if(val.key_name == "MCAP"){
                    shot_name = val.name.slice(0, 14)+"..";
                }else if(val.key_name == "highest_sector_all"){
                    shot_name = "Highest Sector";
                }else if(val.key_name == "highest_sector_all_per"){
                    shot_name = "Highest Sector %";
                }else{
                    shot_name = val.name;
                }
                
                table_html = table_html+'<td  class="'+is_freeze+'">'+shot_name+'</td>';
            });

            //table_html = table_html+'<td></td>';  //added by biswanath

            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";
            // console.log(global_table_list);
            var s_name = "";
            var schemecheckbox = "";
            global_result.forEach(function(val){
                schemecheckbox = "";
                if(checkSchemeCheckbox(val.schemecode,checked_schemecode_ids)){
                    schemecheckbox = "checked";
                }
                s_name = val.s_name;
                if(s_name.length > 45){
                    s_name = s_name.substring(0,45)+"...";
                }
                
              table_html = table_html+"<tr>";
              table_html = table_html+"<td class='firstrow'><input type='checkbox' class='schemecheckbox' name='list_checkbox[]' onchange='changeRowCheckbox();' value='"+val.schemecode+"' "+schemecheckbox+"></td>";
              table_html = table_html+"<td class='secondrow' style='text-align: left'><a href='"+global_url+""+val.schemecode+"' target='_blank'>"+s_name+"</a></td>";
               // "secondrow" added by biswanath
              // if(val.short_name){
              //     table_html = table_html+"<td style='text-align: left'>"+val.short_name+"</td>";
              // }else{
              //     table_html = table_html+"<td></td>";
              // }
              var i_flag = 2;
              global_table_list.forEach(function(val1){
                if(val1.is_freeze == 1){
                    i_flag = i_flag + 1
                    if(i_flag == 3){
                        is_freeze = "thirdrow";
                    }else if(i_flag == 4){
                        is_freeze = "fourthrow";
                    }else if(i_flag == 5){
                        is_freeze = "fivethrow";
                    }else if(i_flag == 6){
                        is_freeze = "sixthrow";
                    }else{
                        is_freeze = "";
                    }           
                }else{
                    is_freeze = "";
                }
                  if(val1.key_name == "EXITLOAD"){
                    if(!val[val1.key_name] || val[val1.key_name] == "0"){
                        table_html = table_html+"<td class='"+is_freeze+"'><div class='dataHintHold'>-<span>"+val.REMARKS+"</span></td>";
                    }else{
                        glo_aum = parseFloat(val[val1.key_name]);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td class='"+is_freeze+"'><div class='dataHintHold'>"+glo_aum.toFixedNoRounding(2)+"<span>"+val.REMARKS+"</span></td>";
                    }
                  }else if(val1.key_name == "rating"){
                    if(val['rating'] == 5){
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>5</span><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'></td>";
                    }else if(val['rating'] == 4){
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>4</span><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'></td>";
                    }else if(val['rating'] == 3){
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>3</span><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'></td>";
                    }else if(val['rating'] == 2){
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>2</span><img  src='"+glob_checked_img+"' style='width: 13px;'><img  src='"+glob_checked_img+"' style='width: 13px;'></td>";
                    }else if(val['rating'] == 1){
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>1</span><img  src='"+glob_checked_img+"' style='width: 13px;'></td>";
                    }else {
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>0</span>Unrated</td>";
                    }
                  }else if(val1.key_name == "classname"){
                    if(val.class_name){
                          table_html = table_html+"<td class='"+is_freeze+"' style='text-align: left;'>"+val.class_name+"</td>";
                    }else{
                          table_html = table_html+"<td class='"+is_freeze+"' style='text-align: left;'>"+val.classname+"</td>";
                    }
                  }else if(!val[val1.key_name] || val[val1.key_name] == "0"){
                    table_html = table_html+"<td class='"+is_freeze+"'>-</td>";
                  }else if(val1.key_name == "total"){
                    if(val[val1.key_name]){
                        //<span style='display:none;'>"+glo_aum+"</span>
                        glo_aum = parseFloat(val[val1.key_name])/100;
                        glo_aum = parseInt(glo_aum);
                        glo_aum_date = new Date(val.total_date);
                        //table_html = table_html+"<td class='"+is_freeze+"'><div class='dataHintHold'><randhir>"+glo_aum.toLocaleString('en-IN')+"</randhir><span>"+glo_aum_date.getDate()  + "-" + (glo_aum_date.getMonth()+1) + "-" + glo_aum_date.getFullYear()+"</span></div></td>";
                        table_html = table_html+"<td class='"+is_freeze+"'><div class='dataHintHold'><randhir>"+glo_aum.toLocaleString('en-IN')+"</randhir></div></td>";
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'></td>";
                    }
                  }else if(val1.key_name == "MCAP"){
                    if(val[val1.key_name]){
                        glo_aum = parseFloat(val[val1.key_name])/100;
                        glo_aum = parseInt(glo_aum);
                        table_html = table_html+"<td class='"+is_freeze+"'>"+glo_aum.toLocaleString('en-IN')+"</td>";
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'></td>";
                    }
                  }else if(val1.key_name == "Incept_date"){
                    if(val[val1.key_name]){
                        glo_aum = new Date(val[val1.key_name]);
                        var month = glo_aum.getMonth()+1;
                        month = (month>9)?month:"0"+month;
                        var day = glo_aum.getDate();
                        day = (day>9)?day:"0"+day;
                        table_html = table_html+"<td class='"+is_freeze+"'><span style='display:none;'>"+glo_aum.getFullYear()+"-"+month+"-"+day+"</span>"+day+ "-" +month+ "-" + glo_aum.getFullYear()+"</td>";
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'></td>";
                    }
                  }else if(val1.key_name == "avg_mat_num"){
                    glo_aum = parseFloat(val['avg_mat_years']);
                    glo_aum = Number((glo_aum).toFixed(2));
                    table_html = table_html+"<td class='"+is_freeze+"'>"+glo_aum.toFixedNoRounding(2)+" years</td>";
                  }else if(val1.key_name == "mod_dur_num"){
                    glo_aum = parseFloat(val['mod_dur_years']);
                    glo_aum = Number((glo_aum).toFixed(2));
                    table_html = table_html+"<td class='"+is_freeze+"'>"+glo_aum.toFixedNoRounding(2)+" years</td>";
                  }else if(val1.key_name == "turnover_ratio"){
                    if(val[val1.key_name]){
                        if(val.tr_mode == "times"){
                            table_html = table_html+"<td class='"+is_freeze+"'>"+Math.round(val[val1.key_name]*100)+" </td>";
                        }else{
                            table_html = table_html+"<td class='"+is_freeze+"'>"+Math.round(val[val1.key_name])+"</td>";
                        }
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'></td>";
                    }
                  }else if(val1.key_name == "IndexName"){
                    table_html = table_html+"<td class='"+is_freeze+"'>"+val[val1.key_name]+"</td>";
                  }else if(val1.key_name == "ASECT_CODE"){
                    table_html = table_html+"<td class='"+is_freeze+"'>"+val[val1.key_name]+"</td>";
                  }else if(val1.key_name == "highest_sector_all"){
                    table_html = table_html+"<td class='"+is_freeze+"'><span style='text-transform:capitalize;'>"+val[val1.key_name].toLowerCase()+"</span></td>";
                  }else if(val1.key_name == "trackingError"){
                    if(val[val1.key_name]){
                        var resulttrackingError = trackingErrorCategory.find( (id) => id === val['classcode'] );
                        console.log( val[val1.key_name]);
                        console.log(resulttrackingError);
                        if(resulttrackingError){
                            glo_aum = parseFloat(val[val1.key_name]);
                            glo_aum = Number((glo_aum).toFixed(2));
                            table_html = table_html+"<td class='"+is_freeze+"'>"+glo_aum.toFixedNoRounding(2)+"</td>";
                        }else{
                            table_html = table_html+"<td class='"+is_freeze+"'>-</td>";
                        }
                        
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'>-</td>";
                    }
                    
                  }else{
                    if(val[val1.key_name]){
                        glo_aum = parseFloat(val[val1.key_name]);
                        glo_aum = Number((glo_aum).toFixed(2));
                        table_html = table_html+"<td class='"+is_freeze+"'>"+glo_aum.toFixedNoRounding(2)+"</td>";
                    }else{
                        table_html = table_html+"<td class='"+is_freeze+"'>-</td>";
                    } 
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
            
            
            $.fn.dataTableExt.oSort["test-desc"] = function (x, y){
                console.log(x,y);
                if(x.split('randhir').length == 3){
                    if(x.split('randhir')[1]){
                        x = parseInt(x.split('randhir')[1].replace(/[^0-9]/g, ''));
                    }else{
                        x = 0;
                    }
                    if(y.split('randhir')[1]){
                        y = parseInt(y.split('randhir')[1].replace(/[^0-9]/g, ''));
                    }else{
                        y = 0;
                    }
                }
                if ( x > y){
                    return -1;
                }
                return 1;
            };
        
            $.fn.dataTableExt.oSort["test-asc"] = function (x, y){
                //alert(2);
                if(x.split('randhir').length == 3){
                    if(x.split('randhir')[1]){
                        x = parseInt(x.split('randhir')[1].replace(/[^0-9]/g, ''));
                    }else{
                        x = 0;
                    }
                    if(y.split('randhir')[1]){
                        y = parseInt(y.split('randhir')[1].replace(/[^0-9]/g, ''));
                    }else{
                        y = 0;
                    }
                }
                if ( x > y){
                    return 1;
                }
                return -1;
            }
            
                 // "responsive": true,
                 //  "colReorder": true,
            $('#mf_scanner_list').DataTable({
                "bPaginate": false,
                "searching": false,
                "order": [[ 1, "asc" ]],
                "columnDefs": [
                    { "type": "test", targets: 0 }
                ]
            });
            setTimeout(function() {
                var parin = $(".dataTables_info").html();
                $('.pagin').html(parin);
                $(".dataTables_info").remove();
            }, 3000);
        }

        function setTableCheckbox(id,value){
            console.log(value);
            var compare_list = [];
            global_table_list.forEach(function(val){
                if(val.id == id){

                    console.log(val);
                    val.table_checkbox = value;
                }
                compare_list.push(val);
            });
            global_compare_list = compare_list;
        }

        function changeTableCheckbox(id){
            if($('input:checkbox[id=list_checkbox_'+id+']').is(':checked')){
                var count = 0;
                // console.log(global_table_list);
                global_table_list.forEach(function(val){
                    if(val.table_checkbox){
                        count = count + 1;
                    }
                });
    
                console.log(count);
                if(count > 19){
                    alert("You can not tick more than 20 columns");
                    $("#list_checkbox_"+id).removeAttr('checked');
                }else{
                    setTableCheckbox(id,1);
                }
            }else{
                setTableCheckbox(id,0);
            }
                
        }

        function addTableCheckbox_old(id){
          console.log(id);
          var compare_list = global_table_list;
          global_compare_list.forEach(function(val){
            if(val.id == id){
              compare_list.push(val);
            }
          });
          global_table_list = compare_list;
        }
        
        function addTableCheckbox(id){
          console.log(global_table_list);
          var compare_list = [];

          var data = global_compare_list.find(o => o.id == id);

          console.log(data);

          var is_rating = global_table_list.find(o => o.key_name == "rating");

          console.log(is_rating);

          var cont_flag = true;
          global_table_list.forEach(function(val){
            if(is_rating){
                compare_list.push(val);
                if(val.key_name == "rating"){
                    if(cont_flag){
                        cont_flag = false;
                        compare_list.push(data);
                    }
                }
            }else{
                if(val.is_freeze == 0){
                    if(cont_flag){
                        cont_flag = false;
                        compare_list.push(data);
                    }
                }
                compare_list.push(val);
            }
          });


          global_table_list = compare_list;
        }

        function removeTableCheckbox(id){
            var compare_list = [];
            console.log(compare_list);

            global_table_list.forEach(function(val){
                if(val.id == id){
                    
                }else{
                    compare_list.push(val);
                }
            });
            console.log(compare_list);
            global_table_list = compare_list;
        }

        function renderRow(checked_id,id){
            console.log(checked_id);
            // var cc_basic_detail_list = getAllBoxChecked('cc_basic_detail');
            // var cc_return_list = getAllBoxChecked('cc_return');
            // var cc_portfolio_att_list = getAllBoxChecked('cc_portfolio_att');
            // var cc_mf_ratio_list = getAllBoxChecked('cc_mf_ratio');

            if(checked_id){
              var d_data = checked_id+id;
              console.log("d_data : "+d_data)
              if($('#'+d_data).is(':checked')){
                addTableCheckbox(id);
              }else{
                removeTableCheckbox(id);
              }
            }

            // console.log(cc_basic_detail_list);

            // console.log(cc_return_list);

            /*global_all_filed = [];
            global_table_list.forEach(function(val){
              console.log(val.type);
              if(val.type == 1){
                if(cc_basic_detail_list.find(o => o == val.id)){
                  global_all_filed.push({"name":val.name,"key_name":val.key_name,"id":val.id,"type":"BD","table_checkbox":val.table_checkbox});
                }
              }else if(val.type == 2){
                if(cc_return_list.find(o => o == val.id)){
                  global_all_filed.push({"name":val.name,"key_name":val.key_name,"id":val.id,"type":"R","table_checkbox":val.table_checkbox});
                }
              }else if(val.type == 3){
                if(cc_portfolio_att_list.find(o => o == val.id)){
                  global_all_filed.push({"name":val.name,"key_name":val.key_name,"id":val.id,"type":"PA","table_checkbox":val.table_checkbox});
                }
              }else{
                if(cc_mf_ratio_list.find(o => o == val.id)){
                  global_all_filed.push({"name":val.name,"key_name":val.key_name,"id":val.id,"type":"MFR","table_checkbox":val.table_checkbox});
                }
              }
            });

            console.log(global_all_filed);

            */

            renderTableView();
        }

        function callAPi(all_data){
            document.getElementById('mf_scanner_loading').style.display = "block";
            $.ajax({
                url: "{{ url('/mf-screener-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    global_result = result;
                    renderRow("","");
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
            var count = 0;
            // console.log(global_table_list);
            global_table_list.forEach(function(val){
                if(val.table_checkbox){
                    count = count + 1;
                }
            });

            console.log(count);
            if(count > 8){
                var txt;
                if (confirm("Landscape research reports cannot be merged with other masterstroke reports. Click to proceed or use portrait mode!")) {
                    txt = 1;
                } else {
                    txt = 2;
                }
                if(txt == 1){
                    //  Deciding Save File Type 
                    document.getElementById('download_type').value = "Landscape";
                    document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
                    var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
                    var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
                    document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
                    $('#sentEmailModal').modal('show');
                    setTimeout(function(){
                        document.getElementById('modal_title').focus();
                    },500);
                }else{
                    return false;
                }
                
            }

          

            document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
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
            document.getElementById('page_type').value = "DOWNLOAD";
            document.getElementById('download_type').value = "Portrait";
            document.getElementById('schemecode_id').value = global_selected_row;
            var count = 0;
            console.log(global_table_list);
            global_table_list.forEach(function(val){
                if(val.table_checkbox){
                    count = count + 1;
                }
            });

            if(count > 8){
                alert("You can not tick more than 8 columns");
                return false;
            }
            document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }

        function checkDownloadLandscape(){
            document.getElementById('page_type').value = "DOWNLOAD";
            document.getElementById('download_type').value = "Landscape";
            document.getElementById('schemecode_id').value = global_selected_row;
            var count = 0;
            // console.log(global_table_list);
            global_table_list.forEach(function(val){
                if(val.table_checkbox){
                    count = count + 1;
                }
            });

            console.log(count);
            if(count > 20){
                alert("You can not tick more than 20 columns");
                return false;
            }
            document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }

        function checkDownloadCSV(){
            document.getElementById('page_type').value = "CSV";
            document.getElementById('download_type').value = "Landscape";
            document.getElementById('schemecode_id').value = global_selected_row;
            

            document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }
        
        window.onload = function afterWebPageLoad() { 

            renderAssertClass();
            renderFilters();
            renderChooseColumns();
            renderReturnData();
            renderView();
        }

        function functionUnfreeze(id){
            
            var spans = document.getElementById('sortable1').getElementsByTagName('span');
            var obj = [];

            for(var i = 0, l = spans.length; i < l; i++){
                obj[spans[i].innerHTML] = i;
            }
            
            return_list = [];
            global_table_list.forEach(function(val){
              val.order = obj[val.id];

              return_list.push(val);
            });
            global_table_list = return_list;
            global_table_list.sort((a, b) => a.order - b.order);

            return_list = [];
            global_table_list.forEach(function(val){
                if(val.id == id){
                    val.is_freeze = 0;
                }
                return_list.push(val);
            });
            global_table_list = return_list;
            global_table_list.sort((a, b) => b.is_freeze - a.is_freeze);
            default_freeze = default_freeze -1;
            openManageModal(0);
        }

        function functionFreeze(id){
          if(default_freeze < 2){
            var spans = document.getElementById('sortable1').getElementsByTagName('span');
            var obj = [];

            for(var i = 0, l = spans.length; i < l; i++){
                obj[spans[i].innerHTML] = i;
            }
            return_list = [];
            global_table_list.forEach(function(val){
              val.order = obj[val.id];
              return_list.push(val);
            });
            global_table_list = return_list;
            global_table_list.sort((a, b) => a.order - b.order);

            return_list = [];
            global_table_list.forEach(function(val){
                if(val.id == id){
                    val.is_freeze = 1;
                }
                return_list.push(val);
            });
            global_table_list = return_list;
            global_table_list.sort((a, b) => b.is_freeze - a.is_freeze);
            default_freeze = default_freeze +1;
            openManageModal(0);
          }else{
            alert("Max 3 freeze");
          }
        }

        function openManageModal(flags){
            var return_list = "<table cellspacing='6' style='width:100%'>";
            return_list = return_list+"<tbody id='sortable1'>";
            var value = "";
              var i = 0;
            return_list = return_list+"<tr>";
            return_list = return_list+"<td class='freezedRow' style='background: #e3f4fb;'><span style='display:none;'>0</span>Fund</td>";
            return_list = return_list+"</tr>";
            global_table_list.forEach(function(val){
                value = val.name.replace("<br>", "");
                if(i < default_freeze){
                    return_list = return_list+"<tr>";
                    return_list = return_list+"<td class='freezedRow' style='background: #e3f4fb;'><span style='display:none;'>"+val.id+"</span>"+value+"<a href='#' onclick='functionUnfreeze("+val.id+");' class='mfunfreeze' style='display: block;'>Unfreeze</a></td>";
                    return_list = return_list+"</tr>";
                }else{
                    return_list = return_list+"<tr class='sortable-row'>";
                    return_list = return_list+"<td><span style='display:none;'>"+val.id+"</span>"+value+"<a href='#' class='mfreeze'  onclick='functionFreeze("+val.id+");'  style='display: block;'>Freeze</a></td>";
                    return_list = return_list+"</tr>";
                }
                i=i+1;
            });
            return_list = return_list+"</tbody>";
            return_list = return_list+"</table>";

            document.getElementById("manageModal_view").innerHTML = return_list;

            // var flagone = (default_freeze == 0)?"selected":"";
            // var flagtwo = (default_freeze == 1)?"selected":"";
            // var flagthree = (default_freeze == 2)?"selected":"";

            // return_list = "<label style='margin-bottom: 0px;'>Freeze</label>";
            // return_list = return_list+"<select class='form-control' style='padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;'' id='freeze_option' onchange='changeFreeze();'>";
            // return_list = return_list+"<option "+flagone+" value='0'>1</option>";
            // return_list = return_list+"<option "+flagtwo+" value='1'>2</option>";
            // return_list = return_list+"<option "+flagthree+" value='2'>3</option>";

            // return_list = return_list+"</select>";

            // document.getElementById("freezeModal_view").innerHTML = return_list;

            // document.getElementById("default_freeze_value1").innerHTML = default_freeze+1;
            // document.getElementById("default_freeze_value2").innerHTML = default_freeze+1;
            if(flags){
                $('#manageModal').modal("show");
            }            

            $( "#sortable1, #sortable2" ).sortable({
                connectWith: ".connectedSortable",
                items: 'tr.sortable-row'
            }).disableSelection();
        }

        function changeTableOrder(){

            var spans = document.getElementById('sortable1').getElementsByTagName('span');
            var obj = [];

            for(var i = 0, l = spans.length; i < l; i++){
                obj[spans[i].innerHTML] = i;
            }
            return_list = [];
            global_table_list.forEach(function(val){
              val.order = obj[val.id];

              return_list.push(val);
            });
            global_table_list = return_list;
            global_table_list.sort((a, b) => a.order - b.order);
            $('#manageModal').modal("hide");
            renderTableView();
        }

        function renderSavedFilter(){
            var return_list = "<table cellspacing='6' style='width:100%'>";
            return_list = return_list+"<tbody>";
            global_saved_filter.forEach(function(val){
                return_list = return_list+"<tr>";
                return_list = return_list+"<td class='' style='width: 160px;'>"+val.name+"</td>";
                return_list = return_list+"<td class='' style='text-align: right;'><a href='javascript:void(0);' class='mfreeze'  onclick='editSavedFilter("+val.id+");' style='display: block;'>Edit</a></td>";
                return_list = return_list+"<td class='' style='text-align: right;'><a href='javascript:void(0);' class='mfreeze'  onclick='selectSavedFilter("+val.id+");' style='display: block;'>View</a></td>";
                
                if(val.is_default){
                    return_list = return_list+"<td class='' style='text-align: right;'><a href='javascript:void(0);' class='mfreeze'  onclick='deleteSavedFilter(0);' style='display: block;'><img class='img-fluid' src='https://masterstroke.5gsoftware.net/public/f/images/red_delete_icon.png' alt=''></a></td>";
                    return_list = return_list+"<td class='' style='text-align: right; width: 120px'><a href='javascript:void(0);' class='defaultmark' style='display: block;'>Default</a></td>"; // added by biswanath
                }else{
                    return_list = return_list+"<td class='' style='text-align: right;'><a href='javascript:void(0);' class='mfreeze'  onclick='deleteSavedFilter("+val.id+");' style='display: block;'><img class='img-fluid' src='https://masterstroke.5gsoftware.net/public/f/images/red_delete_icon.png' alt=''></a></td>";
                    return_list = return_list+"<td class='' style='text-align: right; width: 120px'><a href='javascript:void(0);' class='makemark'  onclick='defualtSavedFilter("+val.id+");' style='display: block;'>Make Default</a></td>";
                }
                return_list = return_list+"</tr>";
            });
            return_list = return_list+"</tbody>";
            return_list = return_list+"</table>";

            document.getElementById("savedFilter_view").innerHTML = return_list;
        }

        function openSavedFilter(){
            renderSavedFilter();
            $("#savedFilterModal").modal('show');
        }

        function saveFilter(){
            document.getElementById("filter_title_error").style.display = "none";
            var filter_title = document.getElementById("filter_title").value;

            if(filter_title){
                var all_data = {};
                all_data._token = "{{ csrf_token() }}";
                all_data.schemecode_id = global_selected_row;
                all_data.all_colum_list = JSON.stringify(global_table_list);
                all_data.order_by = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
                all_data.order_type = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];

                all_data.ael = getAllBoxCheckedForApi('asset_class_selection_equity');
                all_data.adl = getAllBoxCheckedForApi('asset_class_selection_debt');
                all_data.ahl = getAllBoxCheckedForApi('asset_class_selection_hybrid');
                all_data.aol = getAllBoxCheckedForApi('asset_class_selection_other');

                all_data.fhl = getAllBoxCheckedForApi('filter_fund_house');
                all_data.fml = getAllBoxCheckedForApi('filter_fund_manager');
                all_data.pbl = getAllBoxCheckedForApi('filter_primary_benchmark');
                all_data.ftl = 1;
                all_data.ol = 1;
                all_data.pl = getAllBoxCheckedForApi('filter_plan');
                all_data.amurange = getAllBoxCheckedForApi('filter_amu_range');
                all_data.rating = getAllBoxCheckedForApi('filter_rating');

                all_data.filter_title = filter_title;

                $.ajax({
                    url: "{{ url('/mf-screener-save-filter') }}",
                    method: 'post',
                    data: all_data,
                    success: function (result) {
                        global_saved_filter = result;
                        renderSavedFilter();
                        $("#savedFilterModal").modal('show');
                        $("#saveFilterModal").modal('hide');
                    }
                });
            }else{
                document.getElementById("filter_title_error").style.display = "block";
            }
                
        }

        function updateFilter(){
            var all_data = {};
            all_data._token = "{{ csrf_token() }}";
            all_data.id = edit_saved_filter_id;
            all_data.schemecode_id = global_selected_row;
            all_data.all_colum_list = JSON.stringify(global_table_list);
            all_data.order_by = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            all_data.order_type = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];

            all_data.ael = getAllBoxCheckedForApi('asset_class_selection_equity');
            all_data.adl = getAllBoxCheckedForApi('asset_class_selection_debt');
            all_data.ahl = getAllBoxCheckedForApi('asset_class_selection_hybrid');
            all_data.aol = getAllBoxCheckedForApi('asset_class_selection_other');

            all_data.fhl = getAllBoxCheckedForApi('filter_fund_house');
            all_data.fml = getAllBoxCheckedForApi('filter_fund_manager');
            all_data.pbl = getAllBoxCheckedForApi('filter_primary_benchmark');
            all_data.ftl = 1;
            all_data.ol = 1;
            all_data.pl = getAllBoxCheckedForApi('filter_plan');
            all_data.amurange = getAllBoxCheckedForApi('filter_amu_range');
            all_data.rating = getAllBoxCheckedForApi('filter_rating');

            all_data.filter_title = document.getElementById("filter_title").value;

            $.ajax({
                url: "{{ url('/mf-screener-save-filter') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    global_saved_filter = result;
                    renderSavedFilter();
                    document.getElementById("save_filter_button").style.display = "block";
                    document.getElementById("update_filter_button").style.display = "none";
                    $("#savedFilterModal").modal('hide');
                    document.getElementById("filter_model_body").innerHTML = "Your data is successfully updated";
                    $("#saveFilterMessageModal").modal('show');
                }
            });
            removeEventListener("beforeunload", beforeUnloadListener, {capture: true});
        }

        function openSavedFilterModal(){
            if(global_saved_filter.length >= 10){
                alert("Max 10");
            }else{
                document.getElementById("filter_title_error").style.display = "none";
                $("#savedFilterModal").modal('hide');
                $("#saveFilterModal").modal('show');
            }
                
        }

        function closeFilterModal(){
            $("#savedFilterModal").modal('show');
            $("#saveFilterModal").modal('hide');
        }

        function selectSavedFilter(saved_filter_id){
            document.getElementById("update_filter_button").style.display = "none";
            console.log(global_saved_filter);

            global_selected_value = "";
            var saved_filter_detail = global_saved_filter.find( o => o.id === saved_filter_id);

            console.log(saved_filter_detail);
            saved_filter_detail = JSON.stringify(saved_filter_detail);
            console.log(saved_filter_detail);
            saved_filter_detail = JSON.parse(saved_filter_detail);
            console.log(saved_filter_detail);

            global_selected_value = saved_filter_detail.data_array;

            renderAssertClass();
            renderFilters();
            renderChooseColumns();
            renderReturnData();
            renderView();

            $("#savedFilterModal").modal('hide');
            removeEventListener("beforeunload", beforeUnloadListener, {capture: true});
        }

        function editSavedFilter(saved_filter_id){
            edit_saved_filter_id = saved_filter_id;
            global_selected_value = "";
            document.getElementById("save_filter_button").style.display = "none";
            document.getElementById("update_filter_button").style.display = "block";
            var saved_filter_detail = global_saved_filter.find( o => o.id === saved_filter_id);

            console.log(saved_filter_detail);
            saved_filter_detail = JSON.stringify(saved_filter_detail);
            console.log(saved_filter_detail);
            saved_filter_detail = JSON.parse(saved_filter_detail);
            console.log(saved_filter_detail);

            global_selected_value = saved_filter_detail.data_array;

            renderAssertClass();
            renderFilters();
            renderChooseColumns();
            renderReturnData();
            renderView();
            addEventListener("beforeunload", beforeUnloadListener, {capture: true});

            $("#savedFilterModal").modal('hide');
        }

        function  deleteSavedFilter(saved_filter_id){
            if(saved_filter_id){
                if(confirm("Are you sure?")){
                    var all_data = {};
                    all_data.id = saved_filter_id;
                    all_data.type = 1;

                    $.ajax({
                        url: "{{ url('/mf-screener-delete-filter') }}",
                        method: 'get',
                        data: all_data,
                        success: function (result) {
                            global_saved_filter = result;
                            renderSavedFilter();
                        }
                    });
                }
            }else{
                alert("Default can't deleted");
            }
                
        }

        function  defualtSavedFilter(saved_filter_id){
            var all_data = {};
            all_data.id = saved_filter_id;
            all_data.type = 2;

            $.ajax({
                url: "{{ url('/mf-screener-delete-filter') }}",
                method: 'get',
                data: all_data,
                success: function (result) {
                    global_saved_filter = result;
                    renderSavedFilter();
                }
            });
            renderSavedFilter();
        }

        const beforeUnloadListener = (event) => {
            event.preventDefault();
            return event.returnValue = "Are you sure you want to exit?";
        };
    </script>
    
    
    <script type="text/javascript">
        var modal_flag = true;
        @if (Auth::check())
            modal_flag = false;
        @endif

        if(modal_flag){
            $("#permissionModal").modal('show');
        }
    </script>

    <script>

    //     $(window).scroll(function(){
    //         var top1 = parseInt($('.dataTableArea').offset().top - $(window).scrollTop());
    //         if( top1 <= 0 ){

    //             $('.dataTableArea').addClass('sticky container');
    //         }
    //         else {

    //             $('.dataTableArea').removeClass('sticky container');

    //         }
    // })

//     window.onscroll = function() {myFunction()};

//     var header = document.getElementById("dataTableArea");
//     var sticky = header.offsetTop;

//     function myFunction() {
//     if (window.pageYOffset > sticky) {
//         header.classList.add("sticky");
//     } else {
//         header.classList.remove("sticky");
//     }
// }

var distance = $('#mf_scanner_list').offset().top,
    $window = $(window);

$window.scroll(function() {
    if ( $window.scrollTop() >= distance ) {
       //alert("top");
       $('.dataTableArea .mt-1').addClass("stickyEl");
    }else{
        $('.dataTableArea .mt-1').removeClass("stickyEl");
    }
});


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

        .sticky-col {
          position: -webkit-sticky;
          position: sticky;
          background-color: white;
        }

        .first-col {
          width: 25px;
          min-width: 25px;
          max-width: 25px;
          left: 0px;
        }


        .second-col {
          width: 200px;
          min-width: 200px;
          max-width: 200px;
          left: 25px;
        }

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
            /* padding: 8px 4px; */
            padding: 0 4px;
            text-align: center;
            line-height: 14px;
            font-size: 12px;
            font-weight: 600;
            padding-right: 20px;
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
            padding: 10px 4px;
            text-align: right;
            line-height: 14px;
            font-size: 11px;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            white-space: nowrap;
        }
        #mf_scanner_list tbody tr:hover, #mf_scanner_list tbody tr:hover td.firstrow, #mf_scanner_list tbody tr:hover td.secondrow{
            background-color:#c7edcf;
        }
        table.dataTable tbody tr:nth-child(even):hover, table.dataTable tbody tr:nth-child(even):hover td.secondrow, table#mf_scanner_list tbody tr.even:hover td:nth-child(2), table#mf_scanner_list tbody tr.even:hover td:nth-child(3), table#mf_scanner_list tbody tr.even:hover td:nth-child(4){
            background-color:#c7edcf !important;
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
        .form-control {
            border-radius: 0;
            border: 1px solid #cacaca !important;
            min-height: 40px;
            font-size: 12px;
        }
        #manageModal .modal-header, #savedFilterModal .modal-header {
            background: #25a8e0;
            text-align: center;
            color: #fff;
            display: block;
            position: relative;
        }
        #manageModal .modal-header p {
            margin: 0;
            padding: 0;
            color: #fff;
        }
        #manageModal .modal-header h5, #savedFilterModal .modal-header h5  {
            color: #fff;
        }
        #manageModal .modal-header .close, #savedFilterModal .modal-header .close {
            position: absolute;
            top: 4px;
            right: 10px;
            color: #fff;
            font-size: 20px;
            font-weight: normal;
            opacity: 1;
        }
        #manageModal .modal-body, #savedFilterModal .modal-body {
            padding: 10px 30px 0px 30px;
        }
        
        .ui-sortable-handle td {
            border: 1px solid #aeaeae;
            padding: 1px 10px;
            margin-bottom: 3px;
            background: #f7f7f7;
            width: 420px !important;
            top: -7px !important;
            left: 7px !important;
            right: 0 !important;
            position: relative;
        }
        .freezedRow {
                background: #e3f4fb;
                border: 1px solid #aeaeae;
                padding: 1px 10px;
                margin-bottom: 3px;
                width: 420px !important;
                top: -7px !important;
                left: 7px !important;
                right: 0 !important;
                position: relative;
            }
        .ui-sortable-handle td a , .freezedRow a {
            position: absolute;
            right: 4px;
            top: 1px;
            background: #131f55;
            padding: 0 20px;
            border-radius: 7px;
            color: #fff;
            display: none;
        }
        a.defaultmark {
            background: #131f55;
            padding: 3px 0px;
            border-radius: 7px;
            color: #fff;
            width: 106px;
            float: right;
            text-align: center;
        }
        a.makemark {
            background: #e2f5fc;
            padding: 3px 0px;
            border-radius: 7px;
            color: #787d80;
            width: 106px;
            float: right;
            text-align: center;
        }
        #savedFilter_view table td {
            padding: 7px 2px;
            font-size: 15px;
        }
        #savedFilter_view table tbody tr  {
            border-bottom: 1px solid #aeaeae;
        } 
        #manageModal_view table {
            border-collapse: inherit;
        }
        #manageModal .form-group {
            margin-bottom: 0;
        }
        #manageModal .modal-footer, #savedFilterModal .modal-footer {
            justify-content: center;
            padding: 15px 0;
        }
        #savedFilterModal .btn {
            border-radius: 32px;
            text-transform: uppercase;
            width: 110px;
            height: 42px;
        }
        #manageModal .btn {
            border-radius: 32px;
            text-transform: uppercase;
            width: 160px;
            height: 42px;
        }
        #manageModal .btn-secondary {
            background: #c4c8cb;
            border:0;
        }
        #list_checkbox_tr td:nth-child(2) {
            min-width: 290px;
        }
        #mf_scanner_list tr td:nth-child(1) {
            min-width: 20px;
            height: 28px;
            background-image: none;
        }
        table#mf_scanner_list {
                border-collapse: separate;
                border-spacing: 0;
                border-top: 1px solid grey;
              }
              .dataTables_wrapper {
                  position: static;
              }
        table#mf_scanner_list td, table#mf_scanner_list th {
                margin: 0;
                /* white-space: nowrap; */
                border-top-width: 0px;
                min-width: 58px;
              }
              .theDiv {
                overflow-x: scroll;
                margin-left: 331px;
                /* overflow-y: visible; */
                /* overflow-y:scroll;
                padding: 0;
                height:400px; */
              }
              
              table#mf_scanner_list tr td.firstrow, table#mf_scanner_list tr td.secondrow, 
              table#mf_scanner_list tr td.thirdrow, table#mf_scanner_list tr td.fourthrow, 
              table#mf_scanner_list tr td.fivethrow, table#mf_scanner_list tr td.sixthrow {
                position: absolute; 
                top: auto;
                /* border-top-width: 1px; */
                margin-top: -1px;
              }
              table#mf_scanner_list tr td.firstrow {
                left: 0;
                border-left: 1px solid #b5b3b3;
              }
              table#mf_scanner_list tr td.secondrow {
                left: 30px;
              }
              table#mf_scanner_list tr td.thirdrow {
                left: 340px;
              }
              table#mf_scanner_list tr td.fourthrow {
                left: 541px;
              }
              table#mf_scanner_list tr td.fivethrow {
                
              }
              table#mf_scanner_list tr td.sixthrow {
                
              }
              
              .firstHeading {
                height: 27px;
                background: #c3c3c3;min-width: 11px;
                border-top: 1px solid #111;
                border-left: 1px solid #b5b3b3;
              }
              .secondHeading {
                width: 290px;
                height: 27px;background: #c3c3c3;
                line-height: 27px !important;
                border-top: 1px solid #111;
              } 
              .sixthHeading {
                width: 141px;
                height: 27px;background: #c3c3c3;
                line-height: 27px !important;
                border-top: 1px solid #111;
              } 
              .thirdHeading, .fourthHeading, .fivethHeading {
                min-width: 180px !important;
                height: 27px;
                background: #c3c3c3;
                border-top: 1px solid #111;
                line-height: 27px !important;
              }
              table#mf_scanner_list tbody tr td.secondrow {
                /* min-width: 300px; */
                min-width: 292px;
                background-color: #fff;
              }
              table#mf_scanner_list tbody tr td.thirdrow, table#mf_scanner_list tbody tr td.fourthrow, 
              table#mf_scanner_list tbody tr td.fivethrow, table#mf_scanner_list tbody tr td.sixthrow {
                min-width: 192px;
              }
              table#mf_scanner_list thead tr:nth-child(2) td,
              table#mf_scanner_list thead tr:nth-child(2) td.secondrow,
              table#mf_scanner_list thead tr:nth-child(2) td.thirdrow, table#mf_scanner_list thead tr:nth-child(2) td.fourthrow, 
              table#mf_scanner_list thead tr:nth-child(2) td.fivethrow, table#mf_scanner_list thead tr:nth-child(2) td.sixthrow {
                height: 28px;
              }
            table#mf_scanner_list tbody tr td:nth-child(1) {
                /* padding-top: 2px !important; */
                height: 14px;
            }
            table#mf_scanner_list thead td b {
                font-weight: 600;
            }
            table#mf_scanner_list tbody tr.even td:nth-child(2), table#mf_scanner_list tbody tr.even td:nth-child(3), table#mf_scanner_list tbody tr.even td:nth-child(4) {
                background-color: #f0f1f6;
            }
            table#mf_scanner_list tbody tr:last-child td.firstrow {
                border-bottom: 1px solid #b5b3b3;
                margin-top: -2px;
                padding-bottom: 0;
            }
            table#mf_scanner_list tbody tr:last-child td.secondrow, table#mf_scanner_list tbody tr:last-child td.thirdrow, table#mf_scanner_list tbody tr:last-child td.fourthrow {
                border-bottom: 1px solid #b5b3b3;
                margin-top:0.02em;
            }



            #manageModal_view {
                max-height: 350px;
                overflow-y: auto;
                overflow-x: hidden;
                padding-top: 6px;
                padding-right: 15px;
            }
            .category-box.categoryList {
                margin-top: 0;
            }
            #mf_scanner_list tbody tr td:last-child .dataHintHold span {
                left: auto;
                right:0;
                width: auto;
            }
            #mf_scanner_list tbody tr td .dataHintHold span {
                left: auto;
                right:0;
                width: auto;
            }
</style>
<style type="text/css">

            .theDiv{
                height:600px;
                margin-left: 0px !important;
            }
            table#mf_scanner_list thead tr td.firstrow,
            table#mf_scanner_list thead tr td.secondrow,
            table#mf_scanner_list thead tr td.thirdrow,
            table#mf_scanner_list thead tr td.fourthrow 
            {
                background-color: #b5b3b3;
            }
            table#mf_scanner_list thead tr td.firstrow,
            table#mf_scanner_list thead tr td.secondrow,
            table#mf_scanner_list thead tr td.thirdrow,
            table#mf_scanner_list thead tr td.fourthrow{
                position: sticky;
                border-right: 1px solid #b5b3b3;
            }
            table#mf_scanner_list tbody tr td.firstrow,
            table#mf_scanner_list tbody tr td.secondrow,
            table#mf_scanner_list tbody tr td.thirdrow,
            table#mf_scanner_list tbody tr td.fourthrow {
                position: sticky !important;
                background-color: white;
                border-right: 1px solid #b5b3b3;
            }
            .dataHintHold{
                position: static !important;
            } 
            .firstrow{
                text-align: center !important;
            }
            .rt-pnl{
                padding: 26px 15px 30px !important;
            }
            table#mf_scanner_list tr td.secondrow{
                left: 40px !important;
            }
            #mf_scanner_list thead{
                position:sticky;
                top:0;
                z-index:1;
                border-top:0;
            }
            .large-table-fake-top-scroll-container-3{
                display:none;
            }
            .newsletter{
                /* position: inherit !important; */
                margin-bottom:0 !important;
            }
            .btm-shape-prt{
                display:none;
            }
            /* footer{
                z-index:-1 !important;
            } */
            .stickyEl
            {
                /* position: fixed;
                top: -4px;
                left: 0;
                right: 0;
                border: 0;
                margin: 0 auto;
                max-width: 1140px; */
            }
            </style>
            <style>

            
             /* .sticky{
                position: fixed;
                top: 0;
                right: 0;
                left: 0;
                z-index: 1030;
                max-width: 1140px;
                background-color:#fff;
                margin: 0 auto;
                height:400px;
                overflow-y:scroll;
                overflow-x:hidden;
            } */
            /*.dataTableArea{
                height:400px;
                overflow-y:scroll ;
                position: relative;
            } */

    </style>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">MF SCREENER</h2>
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
                            <div class="row" id="myTab" role="tablist">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a class="nav-link" id="asset_class_selection" onclick="changeTab('asset_class_selection');">
                                            Asset Class
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </a>
                                    </div>  
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a class="nav-link" id="filters" onclick="changeTab('filters');">
                                            Filters
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </a>
                                    </div>  
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a class="nav-link" id="choose_columns" onclick="changeTab('choose_columns');">
                                            Choose Columns
                                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                                            <i class="fa fa-angle-down" aria-hidden="true"></i>
                                        </a>
                                    </div>  
                                </div>
                            </div>

                            <div class="tab-content" style="padding-bottom: 10px;">
                              <div class="tab-pane fade" id="asset_class_selection_view">
                                  <div class="row">
                                      <div class="col-md-3">
                                        <div class="mf-scanner-filter-box-header">
                                            <div>
                                                Equity Funds
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="equity_fund_all" name="equity_fund_all" value="" onchange="changeAllCheckbox('asset_class_selection_equity','equity_fund_all'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="asset_class_selection_equity">
                                          
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100% - 58px);">
                                                Debt Funds
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="debt_fund_all" name="debt_fund_all" value="" onchange="changeAllCheckbox('asset_class_selection_debt','debt_fund_all'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="asset_class_selection_debt">
                                           
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="mf-scanner-filter-box-header">
                                            <div>
                                                Hybrid Funds
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="hybrid_fund_all" name="hybrid_fund_all" value="" onchange="changeAllCheckbox('asset_class_selection_hybrid','hybrid_fund_all'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="asset_class_selection_hybrid">
                                          
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div class="mf-scanner-filter-box-header">
                                            <div>
                                                Commodity / Others
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="commodity_others_all" name="commodity_others_all" value="" onchange="changeAllCheckbox('asset_class_selection_other','commodity_others_all'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="asset_class_selection_other" style="">
                                          
                                        </div>
                                        <div class="mf-scanner-button-div">
                                            <button type="button" class="btn btn-success btn-sm resetallButton" style="padding:7px 7px"  onclick="removeAllCheckBox('asset_class_selection_view');">Reset All</button>
                                            <button class="btn btn-success btn-sm closeButton" style="padding:7px 7px" type="button" onclick="closeAllTab();">Close</button>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="tab-pane fade" id="filters_view">
                                  <div class="row">
                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                Fund House
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="fund_house_checkbox" name="fund_house_checkbox" value="" onchange="changeAllCheckbox('filter_fund_house','fund_house_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_fund_house">
                                            
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                Fund Manager
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="fund_manager_checkbox" name="fund_manager_checkbox" value="" onchange="changeAllCheckbox('filter_fund_manager','fund_manager_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_fund_manager">
                                           
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                Primary Benchmark
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="primary_benchmark_checkbox" name="primary_benchmark_checkbox" value="" onchange="changeAllCheckbox('filter_primary_benchmark','primary_benchmark_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_primary_benchmark">
                                          
                                        </div>
                                      </div>
                                      <div class="col-md-2" style="display:none;">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                Fund Type
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="fund_type_checkbox" name="fund_type_checkbox" value="" onchange="changeAllCheckbox('filter_fund_type','fund_type_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_fund_type" style="height: 72px;">
                                            @if(count($fund_type_list)>0)
                                                @foreach ($fund_type_list as $key=>$result)
                                                    <div class="form-check">
                                                      <label class="form-check-label" for="fund_type_{{$result->type_code}}">
                                                        <input type="checkbox" data="{{$result->type}}" class="form-check-input" id="fund_type_{{$result->type_code}}" 
                                                        name="fund_type[]" value="{{$result->type_code}}" onchange="renderView();" @if($result->type =='Open ended scheme') checked @endif>{{$result->type}}
                                                      </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                No Data Found
                                            @endif
                                        </div>
                                        <div class="mf-scanner-filter-box-header" style="margin-top: 3px;">
                                            <div style="width:calc(100%);">
                                                Option
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="option_checkbox" name="option_checkbox" value="" onchange="changeAllCheckbox('filter_option','option_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_option" style="height: 72px;">
                                            @if(count($option_list)>0)
                                                @foreach ($option_list as $key=>$result)
                                                    <div class="form-check">
                                                      <label class="form-check-label" for="option_{{$result->opt_code}}">
                                                        <input type="checkbox" data="{{$result->option}}" class="form-check-input" id="option_{{$result->opt_code}}" name="option[]" value="{{$result->opt_code}}" onchange="renderView();"  @if($result->option =='Growth') checked @endif>{{$result->option}}
                                                      </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                No Data Found
                                            @endif
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                AUM Range
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="amu_range_checkbox" name="amu_range_checkbox" value="" onchange="changeAllCheckbox('filter_amu_range','amu_range_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_amu_range">
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_1">
                                                    <input type="checkbox" data="0-500" class="form-check-input" id="amu_range_1" name="amu_range[]" value="1" onchange="renderView();" checked="checked">0-500
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_2">
                                                    <input type="checkbox" data="500-750" class="form-check-input" id="amu_range_2" name="amu_range[]" value="2" onchange="renderView();" checked="checked">500-750
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_3">
                                                    <input type="checkbox" data="750-2000" class="form-check-input" id="amu_range_3" name="amu_range[]" value="3" onchange="renderView();" checked="checked">750-2000
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_4">
                                                    <input type="checkbox" data="2000-5000" class="form-check-input" id="amu_range_4" name="amu_range[]" value="4" onchange="renderView();" checked="checked">2000-5000
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_5">
                                                    <input type="checkbox" data="5000-10000" class="form-check-input" id="amu_range_5" name="amu_range[]" value="5" onchange="renderView();" checked="checked">5000-10000
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_6">
                                                    <input type="checkbox" data="10000-50000" class="form-check-input" id="amu_range_6" name="amu_range[]" value="6" onchange="renderView();" checked="checked">10000-50000
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label" for="amu_range_7">
                                                    <input type="checkbox" data=">50000" class="form-check-input" id="amu_range_7" name="amu_range[]" value="7" onchange="renderView();" checked="checked"> >50000
                                                </label>
                                            </div>
                                        </div>
                                      </div>
                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                Plan
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="plan_checkbox" name="plan_checkbox" value="" onchange="changeAllCheckbox('filter_plan','plan_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" style="" id="filter_plan">
                                          
                                        </div>
                                      </div>

                                      <div class="col-md-2">
                                        <div class="mf-scanner-filter-box-header">
                                            <div style="width:calc(100%);">
                                                MSO Rating
                                            </div>
                                            <div>
                                                <input type="checkbox" class="form-check-input" id="rating_checkbox" name="amu_range_checkbox" value="" onchange="changeAllCheckbox('filter_rating','rating_checkbox'); renderView();">
                                            </div>
                                        </div>
                                        <div class="mf-scanner-filter-box" id="filter_rating">
                                            
                                        </div>
                                        <div class="mf-scanner-button-div">
                                            <button type="button" class="btn btn-success btn-sm resetallButton" style="padding:7px 7px"  onclick="removeAllCheckBox('filters_view');">Reset All</button>
                                            <button class="btn btn-success btn-sm closeButton" style="padding:7px 7px" type="button" onclick="closeAllTab();">Close</button>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="tab-pane fade" id="choose_columns_view">
                                  <div class="row">
                                      <div class="col-md-3">
                                        <div style="margin: 0px;font-weight: bold;">Basic Details</div>
                                        <div class="mf-scanner-filter-box" id="cc_basic_detail">
                                           
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div style="margin: 0px;font-weight: bold;">Return (%)</div>
                                        <div class="mf-scanner-filter-box" id="cc_return">
                                            
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div style="margin: 0px;font-weight: bold;">Portfolio Attribute</div>
                                        <div class="mf-scanner-filter-box" id="cc_portfolio_att">
                                            
                                        </div>
                                      </div>
                                      <div class="col-md-3">
                                        <div style="margin: 0px;font-weight: bold;">MF Ratios</div>
                                        <div class="mf-scanner-filter-box" style="" id="cc_mf_ratio">
                                            
                                        </div>
                                        <div class="mf-scanner-button-div">
                                            <button type="button" class="btn btn-success btn-sm resetallButton" style="padding:7px 7px" onclick="removeAllCheckBox('choose_columns_view');">Reset All</button>
                                            <button class="btn btn-success btn-sm closeButton" style="padding:7px 7px" type="button" onclick="closeAllTab();">Close</button>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                            </div>

                            <div class="row" style="padding-bottom: 10px; font-size: 12px;">
                              <div class="col-md-12">
                                <div>
                                    Category : 
                                    <span id="category_view">

                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    Fund Type : 
                                    <span id="fund_type_view">
                                        
                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    Option : 
                                    <span id="option_view">

                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    AUM Range : 
                                    <span id="amu_range_view">

                                    </span>
                                </div>
                                <div style="margin-top: 2px;">
                                    Plan : 
                                    <span id="plan_view">

                                    </span>
                                </div>
                              </div>
                              <div class="col-md-12 text-center pt-2 ml-3 mr-3" style="border-top: 1px solid #ccc; margin-top: 3px;">
                                <form action="{{route('frontend.mf_scanner_submit')}}" method="get" id="save_form_data">
                                  <input type="hidden" name="schemecode_id" id="schemecode_id" value="">
                                  <input type="hidden" name="shorting_id" id="shorting_id" value="">
                                  <input type="hidden" name="all_colum_list" id="all_colum_list" value="">
                                  <input type="hidden" name="save_title" id="save_title" value="">
                                  <input type="hidden" name="page_type" id="page_type" value="">
                                  <input type="hidden" name="download_type" id="download_type" value="">
                                  @if (Auth::check())
                                    @if($permission['is_download'])
                                        <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownload();">
                                            Download Portrait 
                                        </button>
                                        <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownloadLandscape();">
                                            Download Landscape 
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                            Download Portrait
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                            Download Landscape
                                        </button>
                                    @endif
                                        <button class="btn btn-success btn-sm campairButton" onclick="return checkCompare();">
                                            Compare
                                        </button>
                                    @if($permission['is_save'])
                                        <button class="btn btn-success btn-sm savedButton" onclick="return checkSave();">
                                            Save
                                        </button>
                                    @else
                                        <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSavePermissionModal();">
                                            Save
                                        </button>
                                    @endif
                                        <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openManageModal(1);">
                                            Manage
                                        </button>

                                    @if($permission['is_csv'])
                                        <button  class="btn btn-success btn-sm downloadButton" onclick="return checkDownloadCSV();">
                                           Download CSV
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadPermissionModal();">
                                            Download CSV
                                        </button>
                                    @endif
                                        <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openSavedFilter();">
                                             My List
                                        </button>
                                   @else
                                        <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                            Download Portrait
                                        </button>
                                        <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                            Download Landscape
                                        </button>
                                        <button class="btn btn-success btn-sm campairButton" onclick="return checkCompare();">
                                            Compare
                                        </button>
                                        <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSaveLoginModal();">
                                            Save
                                        </button>
                                        <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openManageModal(1);">
                                            Manage
                                        </button>
                                        <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                            Download CSV
                                        </button>
                                         <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                            My List
                                        </button>
                                   @endif
                                       
                                </form>
                              </div>
                            </div>
                        </div>
                        
                        <div class="dataTableArea" id="dataTableArea">
                            <div class="row mt-1" >
                            <div class="col-md-12 text-center" id="mf_scanner_loading" style="display: none;">
                                Loading...
                            </div>

                            <div class="theDiv" >
                            <div class="large-table-fake-top-scroll-container-3">
                                <div>&nbsp;</div>
                            </div>
                                <table id="mf_scanner_list" style="width:100%"> 
                                
                                </table>
                            </div>
                            <div class="pagin"></div>
                            </div>
                        </div>

                        <div class="row mt-1">
                            @php
                                $note_data1 = \App\Models\Mfresearch_note::where('type',"mf-screener")->first();
                                if(!empty($note_data1)){
                                @endphp
                                {!!$note_data1->description!!}
                            @php } @endphp
                        </div>
                        <div class="row">
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

    <div class="modal fade" id="manageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Manage Your Screener</h5>
                <!--<p>(First <span id="default_freeze_value1"></span> row will be Freezed, choose your menu and drag in first <span id="default_freeze_value2"></span> line.)</p>-->
                <p>You can freeze / reposition columns</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" id="freezeModal_view">

                </div>
                <div class="form-group" id="manageModal_view">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="changeTableOrder();">Change</button>
            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="savedFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">My List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 300px;overflow: auto;">
                <div class="form-group" id="savedFilter_view">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="save_filter_button" onclick="openSavedFilterModal();" style="width: 210px;">Save Current List</button>
                <button type="button" class="btn btn-primary" id="update_filter_button" onclick="updateFilter();" style="display: none;width: 225px;">Update Current List</button>
            </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="saveFilterModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Save Filter Data</h5>
                <button type="button" class="close"  onclick="closeFilterModal();">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 0px;">
                    <label style="margin-bottom: 0px;">Title</label>
                    <input type="text" name="filter_title" id="filter_title" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;" maxlength="43">
                </div>
                <div id="filter_title_error" class="form-group" style="margin-bottom: 0px;display: none;"> 
                    <small style="color: red;">Required</small>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeFilterModal();">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveFilter();">SAVE</button>
            </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="saveFilterMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Alert</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="filter_model_body" style="text-align: center;font-size: 16px;"></p>
            </div>
            <div class="modal-footer text-center" style="justify-content: center;">
                <button type="button" class="btn btn-secondary btnblue" data-dismiss="modal">Ok</button>
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
