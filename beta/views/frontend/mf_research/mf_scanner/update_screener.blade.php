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

      var default_freeze = 0;
      
      var global_url = "{{route('frontend.factsheet')}}?schemecode=";

      var global_compare_list = <?php echo json_encode($global_compare_list);?>;
      
      global_compare_list.sort((a, b) => a.order - b.order);
        
      var global_table_list = [];

          global_compare_list.forEach(function(val){
            if(val.is_checked){
              global_table_list.push(val);
            }
          });
      
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
            var filter_brand_list_id = document.getElementById('asset_class_selection_equity').querySelectorAll('input[type=checkbox]:checked');
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

            renderAllFilterView();
            callAPi(all_data);
            // console.log(all_data);
        }

        function changeRowCheckbox(){
            var filter_brand_list_id = document.getElementById('mf_scanner_list').querySelectorAll('input[class=schemecheckbox]:checked');

            console.log(filter_brand_list_id);
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
            console.log(global_selected_row);
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

        function renderTableView(){
            var checked_schemecode_id = document.getElementById("schemecode_id").value;
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
            var shot_name = "";
            global_table_list.forEach(function(val){
              glob_checked = "";
              if(val.table_checkbox == 1){
                glob_checked = "checked";
              }
              if(val.is_freeze == 1){
                i_flag = i_flag + 1
                if(i_flag == 3){
                    is_freeze = "thirdrow thirdHeading";
                    $('.theDiv').css('margin-left', 470);
                }else if(i_flag == 4){
                    is_freeze = "fourthrow fourthHeading";
                    $('.theDiv').css('margin-left', 610);
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

            //table_html = table_html+'<td></td>';  //added by biswanath

            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";

            table_html = table_html+'<td class="firstrow firstHeading"></td>';
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
                  }else if(val1.key_name == "classname"){
                    if(val.classname){
                          table_html = table_html+"<td class='"+is_freeze+"' style='text-align: left'>"+val.classname+"</td>";
                    }else{
                          table_html = table_html+"<td class='"+is_freeze+"'>"+val.class_name+"</td>";
                    }
                  }else if(!val[val1.key_name] || val[val1.key_name] == "0"){
                    table_html = table_html+"<td class='"+is_freeze+"'>-</td>";
                  }else if(val1.key_name == "total"){
                    if(val[val1.key_name]){
                        //<span style='display:none;'>"+glo_aum+"</span>
                        glo_aum = parseFloat(val[val1.key_name])/100;
                        glo_aum = parseInt(glo_aum);
                        glo_aum_date = new Date(val.total_date);
                        table_html = table_html+"<td class='"+is_freeze+"'><div class='dataHintHold'><randhir>"+glo_aum.toLocaleString('en-IN')+"</randhir><span>"+glo_aum_date.getDate()  + "-" + (glo_aum_date.getMonth()+1) + "-" + glo_aum_date.getFullYear()+"</span></div></td>";
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
                    { "type": "test", targets: 3 }
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
            setTableCheckbox(id,1);
          }else{
            setTableCheckbox(id,0);
          }
        }

        function addTableCheckbox(id){
          console.log(id);
          var compare_list = global_table_list;
          global_compare_list.forEach(function(val){
            if(val.id == id){
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
                alert("In portrait max column 8");
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
                alert("In landscape max column 20");
                return false;
            }
            document.getElementById('all_colum_list').value = JSON.stringify(global_table_list);
            var sortedCol = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][0];
            var sortedDir = $('#mf_scanner_list').dataTable().fnSettings().aaSorting[0][1];
            document.getElementById('shorting_id').value = sortedCol+"_"+sortedDir;
            return true;
        }

        window.onload = function afterWebPageLoad() { 
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

            document.getElementById("default_freeze_value1").innerHTML = default_freeze+1;
            document.getElementById("default_freeze_value2").innerHTML = default_freeze+1;
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
        white-space: nowrap;
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
    #manageModal .modal-header {
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
    #manageModal .modal-header h5 {
        color: #fff;
    }
    #manageModal .modal-header .close {
        position: absolute;
        top: 4px;
        right: 10px;
        color: #fff;
        font-size: 20px;
        font-weight: normal;
        opacity: 1;
    }
    #manageModal .modal-body {
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

    
    #manageModal_view table {
        border-collapse: inherit;
    }
    #manageModal .form-group {
        margin-bottom: 0;
    }
    #manageModal .modal-footer {
        justify-content: center;
        padding: 15px 0;
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
            margin-left: 344px;
            overflow-y: visible;
            padding: 0;
          }
        
          table#mf_scanner_list tr td.firstrow, table#mf_scanner_list tr td.secondrow, 
          table#mf_scanner_list tr td.thirdrow, table#mf_scanner_list tr td.fourthrow, 
          table#mf_scanner_list tr td.fivethrow, table#mf_scanner_list tr td.sixthrow {
            position: absolute; 
            top: auto;
            border-top-width: 1px;
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
            left: 419px;
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
          .thirdHeading, .fourthHeading, .fivethHeading, .sixthHeading {
            min-width: 58px;
            height: 27px;
            background: #c3c3c3;
            border-top: 1px solid #111;
          }
          table#mf_scanner_list tbody tr td.secondrow {
            min-width: 300px;
          }
          table#mf_scanner_list tbody tr td.thirdrow, table#mf_scanner_list tbody tr td.fourthrow, 
          table#mf_scanner_list tbody tr td.fivethrow, table#mf_scanner_list tbody tr td.sixthrow {
            min-width: 70px;
          }
          table#mf_scanner_list thead tr:nth-child(2) td,
          table#mf_scanner_list thead tr:nth-child(2) td.secondrow,
          table#mf_scanner_list thead tr:nth-child(2) td.thirdrow, table#mf_scanner_list thead tr:nth-child(2) td.fourthrow, 
          table#mf_scanner_list thead tr:nth-child(2) td.fivethrow, table#mf_scanner_list thead tr:nth-child(2) td.sixthrow {
            height: 28px;
          }
        table#mf_scanner_list tbody tr td:nth-child(1) {
            padding-top: 2px !important;
            height: 16px;
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
                                      @if(count($equity_list)>0)
                                            @foreach ($equity_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="equity_fund_{{$result->classcode}}">
                                                    <input type="checkbox" data="<?php echo ($result->class_name)?$result->class_name:$result->classname;?>" class="form-check-input" id="equity_fund_{{$result->classcode}}" name="equity_fund[]" value="{{$result->classcode}}" onchange="renderView();" @if(in_array($result->classcode,$ael)) checked @endif>
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
                                            <input type="checkbox" class="form-check-input" id="debt_fund_all" name="debt_fund_all" value="" onchange="changeAllCheckbox('asset_class_selection_debt','debt_fund_all'); renderView();">
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box" id="asset_class_selection_debt">
                                       @if(count($debt_list)>0)
                                            @foreach ($debt_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="debt_fund_{{$result->classcode}}">
                                                    <input type="checkbox" data="<?php echo ($result->class_name)?$result->class_name:$result->classname;?>" class="form-check-input" id="debt_fund_{{$result->classcode}}" name="debt_fund[]" value="{{$result->classcode}}" onchange="renderView();" @if(in_array($result->classcode,$adl)) checked @endif>
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
                                            <input type="checkbox" class="form-check-input" id="hybrid_fund_all" name="hybrid_fund_all" value="" onchange="changeAllCheckbox('asset_class_selection_hybrid','hybrid_fund_all'); renderView();">
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box" id="asset_class_selection_hybrid">
                                      @if(count($hybrid_list)>0)
                                            @foreach ($hybrid_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="hybrid_fund_{{$result->classcode}}">
                                                    <input type="checkbox" data="<?php echo ($result->class_name)?$result->class_name:$result->classname;?>" class="form-check-input" id="hybrid_fund_{{$result->classcode}}" name="hybrid_fund[]" value="{{$result->classcode}}" onchange="renderView();" @if(in_array($result->classcode,$ahl)) checked @endif>
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
                                            <input type="checkbox" class="form-check-input" id="commodity_others_all" name="commodity_others_all" value="" onchange="changeAllCheckbox('asset_class_selection_other','commodity_others_all'); renderView();">
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box" id="asset_class_selection_other" style="">
                                      @if(count($other_list)>0)
                                            @foreach ($other_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="commodity_other_{{$result->classcode}}">
                                                    <input type="checkbox" data="<?php echo ($result->class_name)?$result->class_name:$result->classname;?>" class="form-check-input" id="commodity_other_{{$result->classcode}}" name="other_fund[]" value="{{$result->classcode}}" onchange="renderView();" @if(in_array($result->classcode,$aol)) checked @endif>
                                                    <?php echo ($result->class_name)?$result->class_name:$result->classname;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
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
                                        @if(count($fund_house_list)>0)
                                            @foreach ($fund_house_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="fund_house_{{$result->amc_code}}">
                                                    <input type="checkbox" class="form-check-input" id="fund_house_{{$result->amc_code}}" name="fund_house[]" value="{{$result->amc_code}}" onchange="renderView();" @if(in_array($result->amc_code,$fhl)) checked @endif>{{$result->fund}}
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
                                            Fund Manager
                                        </div>
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="fund_manager_checkbox" name="fund_manager_checkbox" value="" onchange="changeAllCheckbox('filter_fund_manager','fund_manager_checkbox'); renderView();">
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box" id="filter_fund_manager">
                                       @if(count($fund_manager_list)>0)
                                            @foreach ($fund_manager_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="fund_manager_{{$result->id}}">
                                                    <input type="checkbox" class="form-check-input" id="fund_manager_{{$result->id}}" name="fund_manager[]" value="{{$result->id}}" onchange="renderView();"  @if(in_array($result->id,$fml)) checked @endif>{{$result->fundmanager}}
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
                                            Primary Benchmark
                                        </div>
                                        <div>
                                            <input type="checkbox" class="form-check-input" id="primary_benchmark_checkbox" name="primary_benchmark_checkbox" value="" onchange="changeAllCheckbox('filter_primary_benchmark','primary_benchmark_checkbox'); renderView();">
                                        </div>
                                    </div>
                                    <div class="mf-scanner-filter-box" id="filter_primary_benchmark">
                                      @if(count($primary_benchmark_list)>0)
                                            @foreach ($primary_benchmark_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="primary_benchmark_{{$result->IndexCode}}">
                                                    <input type="checkbox" class="form-check-input" id="primary_benchmark_{{$result->IndexCode}}" name="primary_benchmark[]" value="{{$result->IndexCode}}" onchange="renderView();"  @if(in_array($result->IndexCode,$pbl)) checked @endif>{{$result->IndexName}}
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
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
                                                    name="fund_type[]" value="{{$result->type_code}}" onchange="renderView();"  @if(in_array($result->type_code,$ftl)) checked @endif>{{$result->type}}
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
                                                    <input type="checkbox" data="{{$result->option}}" class="form-check-input" id="option_{{$result->opt_code}}" name="option[]" value="{{$result->opt_code}}" onchange="renderView();"   @if(in_array($result->opt_code,$ol)) checked @endif>{{$result->option}}
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
                                                <input type="checkbox" data="0-500" class="form-check-input" id="amu_range_1" name="amu_range[]" value="1" onchange="renderView();"  @if(in_array(1,$amurange)) checked @endif>0-500
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_2">
                                                <input type="checkbox" data="500-750" class="form-check-input" id="amu_range_2" name="amu_range[]" value="2" onchange="renderView();" @if(in_array(2,$amurange)) checked @endif>500-750
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_3">
                                                <input type="checkbox" data="750-2000" class="form-check-input" id="amu_range_3" name="amu_range[]" value="3" onchange="renderView();" @if(in_array(3,$amurange)) checked @endif>750-2000
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_4">
                                                <input type="checkbox" data="2000-5000" class="form-check-input" id="amu_range_4" name="amu_range[]" value="4" onchange="renderView();" @if(in_array(4,$amurange)) checked @endif>2000-5000
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_5">
                                                <input type="checkbox" data="5000-10000" class="form-check-input" id="amu_range_5" name="amu_range[]" value="5" onchange="renderView();" @if(in_array(5,$amurange)) checked @endif>5000-10000
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_6">
                                                <input type="checkbox" data="10000-50000" class="form-check-input" id="amu_range_6" name="amu_range[]" value="6" onchange="renderView();" @if(in_array(6,$amurange)) checked @endif>10000-50000
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label" for="amu_range_7">
                                                <input type="checkbox" data=">50000" class="form-check-input" id="amu_range_7" name="amu_range[]" value="7" onchange="renderView();" @if(in_array(7,$amurange)) checked @endif> >50000
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
                                      @if(count($plan_list)>0)
                                            @foreach ($plan_list as $key=>$result)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="plan_{{$result->plan_code}}">
                                                    <input type="checkbox" data="<?php echo ($result->planname)?$result->planname:$result->plan;?>" class="form-check-input" id="plan_{{$result->plan_code}}" name="plan[]" value="{{$result->plan_code}}" onchange="renderView();" 
                                                    @if(in_array($result->plan_code,$pl)) checked @endif>
                                                    <?php echo ($result->planname)?$result->planname:$result->plan;?>
                                                  </label>
                                                </div>
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
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
                                        @if(count($global_compare_list)>0)
                                            @foreach ($global_compare_list as $key=>$result)
                                              @if($result['type'] == 1)
                                                <div class="form-check">
                                                  <label class="form-check-label" for="cc_basic_detail_{{$result['id']}}">
                                                    <input type="checkbox" class="form-check-input" id="cc_basic_detail_{{$result['id']}}" name="basic_detail[]" value="{{$result['id']}}" onchange="renderRow('cc_basic_detail_','{{$result['id']}}');" @if($result['is_checked'] ==1) checked @endif>{{$result['name']}}
                                                  </label>
                                                </div>
                                              @endif
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div style="margin: 0px;font-weight: bold;">Return (%)</div>
                                    <div class="mf-scanner-filter-box" id="cc_return">
                                        @if(count($global_compare_list)>0)
                                            @foreach ($global_compare_list as $key=>$result)
                                              @if($result['type'] == 2)
                                                  <div class="form-check">
                                                    <label class="form-check-label" for="cc_return_{{$result['id']}}">
                                                      <input type="checkbox" class="form-check-input" id="cc_return_{{$result['id']}}" name="return[]" value="{{$result['id']}}" onchange="renderRow('cc_return_','{{$result['id']}}');" @if($result['is_checked'] ==1) checked @endif>{{$result['name']}}
                                                    </label>
                                                  </div>
                                              @endif
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div style="margin: 0px;font-weight: bold;">Portfolio Attribute</div>
                                    <div class="mf-scanner-filter-box" id="cc_portfolio_att">
                                        @if(count($global_compare_list)>0)
                                            @foreach ($global_compare_list as $key=>$result)
                                              @if($result['type'] == 3)
                                                  <div class="form-check">
                                                    <label class="form-check-label" for="cc_portfolio_att_{{$result['id']}}">
                                                      <input type="checkbox" class="form-check-input" id="cc_portfolio_att_{{$result['id']}}" name="portfolio_attribute[]" value="{{$result['id']}}" onchange="renderRow('cc_portfolio_att_','{{$result['id']}}');" @if($result['is_checked'] ==1) checked @endif>{{$result['name']}}
                                                    </label>
                                                  </div>
                                              @endif
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
                                    </div>
                                  </div>
                                  <div class="col-md-3">
                                    <div style="margin: 0px;font-weight: bold;">MF Ratios</div>
                                    <div class="mf-scanner-filter-box" style="" id="cc_mf_ratio">
                                        @if(count($global_compare_list)>0)
                                            @foreach ($global_compare_list as $key=>$result)
                                                @if($result['type'] == 4)
                                                  <div class="form-check">
                                                    <label class="form-check-label" for="cc_mf_ratio_{{$result['id']}}">
                                                      <input type="checkbox" class="form-check-input" id="cc_mf_ratio_{{$result['id']}}" name="mf_ratios[]" value="{{$result['id']}}" onchange="renderRow('cc_mf_ratio_','{{$result['id']}}');" @if($result['is_checked'] ==1) checked @endif>{{$result['name']}}
                                                    </label>
                                                  </div>
                                                @endif
                                            @endforeach
                                        @else
                                            No Data Found
                                        @endif
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
                          <div class="col-md-8">
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
                                @if($permission['is_save'])
                                    <button class="btn btn-success btn-sm savedButton" onclick="return checkSave();">
                                        Update
                                    </button>
                                @else
                                    <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSavePermissionModal();">
                                        Update
                                    </button>
                                @endif
                               @else
                                    <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                        Download Portrait
                                    </button>
                                    <button  type="button" class="btn btn-success btn-sm downloadButton" onclick="openDownloadLoginModal();">
                                        Download Landscape
                                    </button>
                                    <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openSaveLoginModal();">
                                        Update
                                    </button>
                               @endif
                                    <button  type="button" class="btn btn-success btn-sm savedButton" onclick="openManageModal(1);">
                                        Manage
                                    </button>
                            </form>
                          </div>
                        </div>
                    </div>
                    
                    <div class="row mt-1">
                      <div class="col-md-12 text-center" id="mf_scanner_loading" style="display: none;">
                         Loading...
                      </div>
                      <div class="theDiv">
                        <table id="mf_scanner_list" style="width:100%"> 
                        
                        </table>
                      </div>
                      <div class="pagin"></div>
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
                <h5 class="modal-title" id="exampleModalLongTitle">Manage</h5>
                <p>(First <span id="default_freeze_value1"></span> row will be Freeze, choose your menu and drag in first <span id="default_freeze_value2"></span> line.)</p>
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
    
    @include('frontend.mf_scanner.modal')

@endsection
