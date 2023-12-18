@extends('layouts.frontend')

@section('js_after')
    
    <script>
        var scheme_list = <?php echo json_encode($scheme_list);?>;
        var scanner_avg_list = <?php echo json_encode($scanner_avg_list);?>;
        var assets_list = <?php echo json_encode($assets_list);?>;
        var category_list = <?php echo json_encode($category_list);?>;
        var mutual_fund_index = 0;
        var non_mutual_fund_index = 0;
        var insurance_index = 0;

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
        
        $('#schemecode_id0').select2({
            placeholder: "Select Fund",
        });
        $('#category_id0').select2({
            placeholder: "Select Category",
        });
        $('#asset_class0').select2({
            placeholder: "Select Asset Class",
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

        function changeCheckbox(index,asset_class){
            $("#oneday"+index).removeAttr('checked');
            $("#oneweek"+index).removeAttr('checked');
            $("#onemonth"+index).removeAttr('checked');
            $("#threemonth"+index).removeAttr('checked');
            $("#sixmonth"+index).removeAttr('checked');
            $("#oneyear"+index).removeAttr('checked');
            $("#threeyear"+index).removeAttr('checked');
            $("#fiveyear"+index).removeAttr('checked');
            $("#tenyear"+index).removeAttr('checked');

            if(asset_class == "Equity"){
                $("#sixmonth"+index).attr('checked',true);
                $("#oneyear"+index).attr('checked',true);
                $("#threeyear"+index).attr('checked',true);
                $("#fiveyear"+index).attr('checked',true);
                $("#tenyear"+index).attr('checked',true);
            }else if(asset_class == "Hybrid"){
                $("#sixmonth"+index).attr('checked',true);
                $("#oneyear"+index).attr('checked',true);
                $("#threeyear"+index).attr('checked',true);
                $("#fiveyear"+index).attr('checked',true);
                $("#tenyear"+index).attr('checked',true);
            }else if(asset_class == "Debt"){
                $("#onemonth"+index).attr('checked',true);
                $("#threemonth"+index).attr('checked',true);
                $("#sixmonth"+index).attr('checked',true);
                $("#oneyear"+index).attr('checked',true);
                $("#threeyear"+index).attr('checked',true);
            }else if(asset_class == "Other"){
                $("#sixmonth"+index).attr('checked',true);
                $("#oneyear"+index).attr('checked',true);
                $("#threeyear"+index).attr('checked',true);
                $("#fiveyear"+index).attr('checked',true);
                $("#tenyear"+index).attr('checked',true);
            }else if(asset_class == "Custom"){
                $("#sixmonth"+index).attr('checked',true);
                $("#oneyear"+index).attr('checked',true);
                $("#threeyear"+index).attr('checked',true);
                $("#fiveyear"+index).attr('checked',true);
                $("#tenyear"+index).attr('checked',true);
            }
        }

        function removeSchemecode(index){
            console.log(index);
            document.getElementById("mutual_fund_tr1"+index).remove();
            document.getElementById("mutual_fund_tr2"+index).remove();
            document.getElementById("mutual_fund_tr3"+index).remove();
            document.getElementById("mutual_fund_tr4"+index).remove();
            document.getElementById("mutual_fund_tr5"+index).remove();
            document.getElementById("mutual_fund_tr6"+index).remove();
        }

        function changeReturnCheckbox(index,return_id){
            var filter_brand_list_id = document.getElementById("mutual_fund_tr1"+index);
            var filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('input[type=checkbox]:checked');
            console.log(index,return_id);
            if(filter_brand_list_id_i.length <= 5){

            }else{
                alert("Max 5 select");
                $("#"+return_id+index).prop('checked', false);
            }
        }

        function checkAllFillup(){
            var filter_brand_list_id = document.getElementById("mfTable");
            var filter_brand_list_id_i = filter_brand_list_id.querySelectorAll('select[message=MutualFund]');
            console.log(filter_brand_list_id_i)
            var return_val = false;
            filter_brand_list_id_i.forEach(function(val){
                console.log($(val).val());
                if(!$(val).val()){
                    return_val = true;
                }
            });

            return return_val;
        }

        function AddMoreMutualFund(argument) {
            if(checkAllFillup()){
                alert("Please select scheme");
                return false;    
            }
            mutual_fund_index = mutual_fund_index+1;
            var iHtml = `<tr id="mutual_fund_tr1`+mutual_fund_index+`">
                            <th style="width: 5%;height:40px;">
                               
                            </th>
                            <th style="width: 10%;height:40px;">
                                
                            </th>
                            <th style="width: 30%;height:40px;">
                                
                            </th>
                   
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" name="month1[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','onemonth');" id="onemonth`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" name="month3[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','threemonth');" id="threemonth`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked"  name="month6[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','sixmonth');" id="sixmonth`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked" name="year1[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','oneyear');" id="oneyear`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked" name="year3[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','threeyear');" id="threeyear`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked" name="year5[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','fiveyear');" id="fiveyear`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked" name="year10[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','tenyear');" id="tenyear`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                                <label class="checkcontainer">
                                        <input type="checkbox" checked="checked" name="incret[`+mutual_fund_index+`]" onchange="changeReturnCheckbox('`+mutual_fund_index+`','incret');" id="incret`+mutual_fund_index+`">
                                        <span class="checkmark"></span>
                                    </label>
                            </th>
                            <th>
                            </th>
                        </tr>
                        <tr id="mutual_fund_tr2`+mutual_fund_index+`">
                            <th rowspan="2">Asset Class</th>
                            <th rowspan="2">Category</th>
                            <th rowspan="2">Scheme</th>
                            <th colspan="8" class="text-center">Performance</th>
                            <th rowspan="2">Action</th>
                        </tr>
                        <tr id="mutual_fund_tr3`+mutual_fund_index+`">
                            <th class="text-center">1 mth</th>
                            <th class="text-center">3 mth</th>
                            <th class="text-center">6 mth</th>
                            <th class="text-center">1 Year</th>
                            <th class="text-center">3 Year</th>
                            <th class="text-center">5 Year</th>
                            <th class="text-center">10 Year</th>
                            <th class="text-center">inception</th>
                        </tr>
                        <tr id="mutual_fund_tr4`+mutual_fund_index+`">
                            <td>
                                <div class="allocationSegment mfTableCheck">
                                    <div class="form-group mb-0">
                                        <select class="form-control" id="asset_class`+mutual_fund_index+`" name="asset_class[]" onchange="changeAssetClass('`+mutual_fund_index+`');">
                                            <option value="0">Custom</option>
                                            <?php foreach ($assets_list as $key => $value) { ?>
                                                <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0" style="padding:0px;">
                                    <select class="form-control ui-autocomplete-input" id="category_id`+mutual_fund_index+`" name="category_id[]" onchange="changeCategory('`+mutual_fund_index+`');">
                                        <option value="0">Custom</option>
                                        <?php foreach ($category_list as $key => $value) { ?>
                                            <option value="<?php echo $value->classcode;?>"><?php echo $value->classname;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="form-group mb-0" style="padding:0px;">
                                  <select class="form-control ui-autocomplete-input schemecode_id" name="schemecode_id[`+mutual_fund_index+`]" id="schemecode_id`+mutual_fund_index+`" onchange="changeScheme('`+mutual_fund_index+`')" message="MutualFund" datatype="`+mutual_fund_index+`">
                                    <option value="">Select</option>
                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                    <?php }?>
                                  </select>
                                </div>
                            </td>
                            <td class="text-center" id="month1_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="month3_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="month6_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="year1_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="year3_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="year5_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="year10_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="incret_`+mutual_fund_index+`"></td>
                            <td id="" style="color:red;text-align: center;" rowspan="2" onclick="removeSchemecode('`+mutual_fund_index+`');">
                                <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                            </td>
                        </tr>
                        <tr id="mutual_fund_tr5`+mutual_fund_index+`">
                            <td style="height:40px;">
                                <label class="checkcontainer" style="margin-top: -12px;">
                                    <input type="checkbox" checked="checked" name="category_checkbox[`+mutual_fund_index+`]"  id="category_checkbox`+mutual_fund_index+`">
                                    <span class="checkmark"></span>
                                </label>
                            </td>
                            <td class="text-center" id="category_`+mutual_fund_index+`" colspan="2">&nbsp;</td>
                            <td class="text-center" id="category_month1_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_month3_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_month6_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_year1_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_year3_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_year5_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_year10_`+mutual_fund_index+`"></td>
                            <td class="text-center" id="category_incret_`+mutual_fund_index+`"></td>
                        </tr>
                        <tr id="mutual_fund_tr6`+mutual_fund_index+`">
                            <td colspan="13"><textarea style="width: 100%; vertical-align: bottom;" maxlength="500" name="mutual_fund_comment[]" rows="2"> </textarea></td>
                        </tr>`;

            $("#mutual_fund_div").append(iHtml);
            $('#schemecode_id'+mutual_fund_index).select2({
                placeholder: "Select Fund",
            });
            $('#category_id'+mutual_fund_index).select2({
                placeholder: "Select Category",
            });
            $('#asset_class'+mutual_fund_index).select2({
                placeholder: "Select Asset Class",
            });
        }

        function changeAssetClass(index){
            var all_data = {};
            all_data.category_id = document.getElementById("category_id"+index).value;
            all_data.asset_class = document.getElementById("asset_class"+index).value;
            all_data.schemecode_id = document.getElementById("schemecode_id"+index).value;
            all_data.type = 1;
            $.ajax({
                url: "{{ url('/premium-calculator/review_of_existing_investment_data') }}",
                method: 'get',
                data: all_data,
                success: function (result) {
                    global_result = result;
                    global_result = result;
                    var shtml = "<option value=''>Select</option>";
                    global_result.scheme_list.forEach(function(val){
                        shtml = shtml + "<option value='"+val.schemecode+"'>"+val.s_name+"</option>";
                    });

                    var ahtml = "<option value=''>Select</option>";
                    var selected = "";
                    var class_name = "";
                    global_result.category_list.forEach(function(val){
                        class_name = val.class_name;
                        if(!val.class_name){
                            class_name = val.classname;
                        }
                        ahtml = ahtml + "<option value='"+val.classcode+"' "+selected+">"+class_name+"</option>";
                    });


                    document.getElementById('schemecode_id'+index).innerHTML = shtml;
                    document.getElementById('category_id'+index).innerHTML = ahtml;

                    document.getElementById('day1_'+index).innerHTML = "";
                    document.getElementById('day7_'+index).innerHTML = "";
                    document.getElementById('month1_'+index).innerHTML = "";
                    document.getElementById('month3_'+index).innerHTML = "";
                    document.getElementById('month6_'+index).innerHTML = "";
                    document.getElementById('year1_'+index).innerHTML = "";
                    document.getElementById('year3_'+index).innerHTML = "";
                    document.getElementById('year5_'+index).innerHTML = "";
                    document.getElementById('year10_'+index).innerHTML = "";

                    document.getElementById('category_'+index).innerHTML = "";
                    document.getElementById('category_day1_'+index).innerHTML = "";
                    document.getElementById('category_day7_'+index).innerHTML = "";
                    document.getElementById('category_month1_'+index).innerHTML = "";
                    document.getElementById('category_month3_'+index).innerHTML = "";
                    document.getElementById('category_month6_'+index).innerHTML = "";
                    document.getElementById('category_year1_'+index).innerHTML = "";
                    document.getElementById('category_year3_'+index).innerHTML = "";
                    document.getElementById('category_year5_'+index).innerHTML = "";
                    document.getElementById('category_year10_'+index).innerHTML = "";

                    $('#schemecode_id'+index).select2({
                        placeholder: "Select Fund",
                    });

                    changeCheckbox(index,all_data.asset_class);
                }
            });
        }

        function changeCategory(index){
            var all_data = {};
            all_data.category_id = document.getElementById("category_id"+index).value;
            all_data.asset_class = document.getElementById("asset_class"+index).value;
            all_data.schemecode_id = document.getElementById("schemecode_id"+index).value;
            all_data.type = 2;
            $.ajax({
                url: "{{ url('/premium-calculator/review_of_existing_investment_data') }}",
                method: 'get',
                data: all_data,
                success: function (result) {
                    global_result = result;
                    var shtml = "<option value=''>Select</option>";
                    global_result.scheme_list.forEach(function(val){
                        shtml = shtml + "<option value='"+val.schemecode+"'>"+val.s_name+"</option>";
                    });

                    var ahtml = "<option value='0'>Custom</option>";
                    var selected = "";
                    assets_list.forEach(function(val){
                        selected = "";
                        if(val == global_result.category_list.asset_type){
                            selected = "selected";
                        }
                        ahtml = ahtml + "<option value='"+val+"' "+selected+">"+val+"</option>";
                    });
                    document.getElementById('schemecode_id'+index).innerHTML = shtml;
                    document.getElementById('asset_class'+index).innerHTML = ahtml;

                    document.getElementById('day1_'+index).innerHTML = "";
                    document.getElementById('day7_'+index).innerHTML = "";
                    document.getElementById('month1_'+index).innerHTML = "";
                    document.getElementById('month3_'+index).innerHTML = "";
                    document.getElementById('month6_'+index).innerHTML = "";
                    document.getElementById('year1_'+index).innerHTML = "";
                    document.getElementById('year3_'+index).innerHTML = "";
                    document.getElementById('year5_'+index).innerHTML = "";
                    document.getElementById('year10_'+index).innerHTML = "";

                    document.getElementById('category_'+index).innerHTML = "";
                    document.getElementById('category_day1_'+index).innerHTML = "";
                    document.getElementById('category_day7_'+index).innerHTML = "";
                    document.getElementById('category_month1_'+index).innerHTML = "";
                    document.getElementById('category_month3_'+index).innerHTML = "";
                    document.getElementById('category_month6_'+index).innerHTML = "";
                    document.getElementById('category_year1_'+index).innerHTML = "";
                    document.getElementById('category_year3_'+index).innerHTML = "";
                    document.getElementById('category_year5_'+index).innerHTML = "";
                    document.getElementById('category_year10_'+index).innerHTML = "";

                    $('#schemecode_id'+index).select2({
                        placeholder: "Select Fund",
                    });

                    changeCheckbox(index,global_result.category_list.asset_type);
                }
            });
        }

        function setSchemeData(index,data){
            // var glo_aum = parseFloat(data.oneday);
            // glo_aum = Number((glo_aum).toFixed(2));
            // if(data.oneday && data.oneday != 0){
            //     document.getElementById('day1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            // }else{
            //     document.getElementById('day1_'+index).innerHTML = "-"
            // }
            // glo_aum = parseFloat(data.oneweek);
            // glo_aum = Number((glo_aum).toFixed(2));
            // if(data.oneweek && data.oneweek != 0){
            //     document.getElementById('day7_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            // }else{
            //     document.getElementById('day7_'+index).innerHTML = "-"
            // }
            glo_aum = parseFloat(data.onemonth);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.onemonth && data.onemonth != 0){
                document.getElementById('month1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('month1_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.threemonth);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.threemonth && data.threemonth != 0){
                document.getElementById('month3_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('month3_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.sixmonth);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.sixmonth && data.sixmonth != 0){
                document.getElementById('month6_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('month6_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.oneyear);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.oneyear && data.oneyear != 0){
                document.getElementById('year1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('year1_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.threeyear);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.threeyear && data.threeyear != 0){
                document.getElementById('year3_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('year3_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.fiveyear);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.fiveyear && data.fiveyear != 0){
                document.getElementById('year5_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('year5_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.tenyear);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.tenyear  && data.tenyear != 0){
                document.getElementById('year10_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('year10_'+index).innerHTML = "-"
            }
            glo_aum = parseFloat(data.incret);
            glo_aum = Number((glo_aum).toFixed(2));
            if(data.incret  && data.incret != 0){
                document.getElementById('incret_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
            }else{
                document.getElementById('year10_'+index).innerHTML = "-"
            }

            var category_data = scanner_avg_list[data.classcode+"_"+data.plan];
            console.log(category_data);

            if(category_data){

                var class_name = (data.class_name)?data.class_name:data.classname;
                document.getElementById('category_'+index).innerHTML = class_name;
                // glo_aum = parseFloat(category_data.oneday);
                // glo_aum = Number((glo_aum).toFixed(2));
                // if(data.oneday && data.oneday != 0){
                //     document.getElementById('category_day1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                // }else{
                //     document.getElementById('category_day1_'+index).innerHTML = "-"
                // }
                // glo_aum = parseFloat(category_data.oneweek);
                // glo_aum = Number((glo_aum).toFixed(2));
                // if(data.oneweek && data.oneweek != 0){
                //     document.getElementById('category_day7_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                // }else{
                //     document.getElementById('category_day7_'+index).innerHTML = "-"
                // }
                glo_aum = parseFloat(category_data.onemonth);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.onemonth && data.onemonth != 0){
                    document.getElementById('category_month1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_month1_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.threemonth);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.threemonth && data.threemonth != 0){
                    document.getElementById('category_month3_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_month3_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.sixmonth);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.sixmonth && data.sixmonth != 0){
                    document.getElementById('category_month6_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_month6_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.oneyear);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.oneyear && data.oneyear != 0){
                    document.getElementById('category_year1_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_year1_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.threeyear);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.threeyear && data.threeyear != 0){
                    document.getElementById('category_year3_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_year3_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.fiveyear);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.fiveyear && data.fiveyear != 0){
                    document.getElementById('category_year5_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_year5_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.tenyear);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.tenyear  && data.tenyear != 0){
                    document.getElementById('category_year10_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_year10_'+index).innerHTML = "-"
                }
                glo_aum = parseFloat(category_data.incret);
                glo_aum = Number((glo_aum).toFixed(2));
                if(data.incret  && data.incret != 0){
                    document.getElementById('category_incret_'+index).innerHTML = glo_aum.toFixedNoRounding(2);
                }else{
                    document.getElementById('category_incret_'+index).innerHTML = "-"
                }
            }else{
                var class_name = (data.class_name)?data.class_name:data.classname;
                document.getElementById('category_'+index).innerHTML = class_name;
                // document.getElementById('category_day1_'+index).innerHTML = "";
                // document.getElementById('category_day7_'+index).innerHTML = "";
                document.getElementById('category_month1_'+index).innerHTML = "";
                document.getElementById('category_month3_'+index).innerHTML = "";
                document.getElementById('category_month6_'+index).innerHTML = "";
                document.getElementById('category_year1_'+index).innerHTML = "";
                document.getElementById('category_year3_'+index).innerHTML = "";
                document.getElementById('category_year5_'+index).innerHTML = "";
                document.getElementById('category_year10_'+index).innerHTML = "";
                document.getElementById('category_incret_'+index).innerHTML = "";
            }
        }

        function changeScheme(index){
            var schemecode_id = document.getElementById('schemecode_id'+mutual_fund_index).value;
            var data = scheme_list.find(o => o.schemecode == schemecode_id);
            console.log(data);
            if(data){
                setSchemeData(index,data);
                var ahtml = "<option value='0'>Custom</option>";
                var selected = "";
                assets_list.forEach(function(val){
                    selected = "";
                    if(val == data.asset_type){
                        selected = "selected";
                    }
                    ahtml = ahtml + "<option value='"+val+"' "+selected+">"+val+"</option>";
                });
                document.getElementById('asset_class'+index).innerHTML = ahtml;

                ahtml = "<option value='0'>Custom</option>";
                selected = "";
                var class_name = "";
                category_list.forEach(function(val){
                    selected = "";
                    if(val.classcode == data.classcode){
                        selected = "selected";
                    }
                    class_name = val.class_name;
                    if(!val.class_name){
                        class_name = val.classname;
                    }
                    ahtml = ahtml + "<option value='"+val.classcode+"' "+selected+">"+class_name+"</option>";
                });

                document.getElementById('category_id'+index).innerHTML = ahtml;
                changeCheckbox(index,data.asset_type);

            }else{
                document.getElementById('day1_'+index).innerHTML = "";
                document.getElementById('day7_'+index).innerHTML = "";
                document.getElementById('month1_'+index).innerHTML = "";
                document.getElementById('month3_'+index).innerHTML = "";
                document.getElementById('month6_'+index).innerHTML = "";
                document.getElementById('year1_'+mutual_fund_index).innerHTML = "";
                document.getElementById('year3_'+mutual_fund_index).innerHTML = "";
                document.getElementById('year5_'+mutual_fund_index).innerHTML = "";
                document.getElementById('year10_'+mutual_fund_index).innerHTML = "";
            }
        }

        function removeNonMutualFund(index){
            document.getElementById("non_mutual_fund_tr_"+index).remove();
        }

        function AddMoreNonMutualFund(){
            non_mutual_fund_index = non_mutual_fund_index+1;
            iHtml = `<tr id="non_mutual_fund_tr_`+non_mutual_fund_index+`">
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0 mx-1" id="product_id_div`+non_mutual_fund_index+`">
                                    <select class="form-control" id="product_id`+non_mutual_fund_index+`" name="product_id[]" onchange="changeProduct('`+non_mutual_fund_index+`');" message="NonMutualFund" datatype="`+non_mutual_fund_index+`">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="product_name_div`+non_mutual_fund_index+`" style="display: none;">
                                    <textarea style="width: 100%;" id="product_name`+non_mutual_fund_index+`" maxlength="500" rows="3" name="product_name[]"></textarea>
                                </div>
                            </div>
                        </td>
                        <td><textarea style="width: 100%;" maxlength="500" rows="3" name="non_mutual_fund_comment[]"> </textarea></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeNonMutualFund('`+non_mutual_fund_index+`');">
                            <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                        </td>
                    </tr>`;
            $("#non_mutual_fund_div").append(iHtml);
        }

        function removeInsurance(index){
            document.getElementById("insurance_tr_"+index).remove();
        }

        function addMoreInsurance(){
            insurance_index = insurance_index+1;
            iHtml = `<tr id="insurance_tr_`+insurance_index+`">
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0 mx-1" id="product_type_id_div`+insurance_index+`">
                                    <select class="form-control" id="product_type_id`+insurance_index+`" name="product_type_id[]" onchange="changeProductType('`+insurance_index+`');" message="Insurance" datatype="`+insurance_index+`">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_type_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                                <div class="form-group mb-0" id="product_type_name_div`+insurance_index+`" style="display: none;">
                                    <textarea style="width: 100%;" id="product_type_name`+insurance_index+`" maxlength="500" rows="3" name="product_type_name[]"></textarea>
                                </div>
                            </div>
                        </td>
                        <td><textarea style="width: 100%;" maxlength="500" rows="3" name="insurance_user[]"> </textarea></td>
                        <td><textarea style="width: 100%;" maxlength="500" rows="3" name="insurance_comment[]"> </textarea></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeInsurance('`+insurance_index+`');">
                            <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                        </td>
                    </tr>`;
            $("#insurance_view").append(iHtml);
        }

        function onsubmitFunction(){

            var rmutual_fund = $("#mutual_fund").attr('checked');
            var rnon_mutual_fund = $("#non_mutual_fund").attr('checked');
            var rinsurance = $("#insurance").attr('checked');
            if(rmutual_fund || rnon_mutual_fund || rinsurance){

                var formFlag = true;
                if(rmutual_fund){
                    if(checkAllFillup()){
                        alert("Please select scheme");
                        return false;    
                    }
                    var mutual_fund_div = document.getElementById("mutual_fund_div").querySelectorAll('select[message=MutualFund]');
                    mutual_fund_div.forEach(function(val){
                        if(!$(val).val()){
                            formFlag = false;
                        }
                    });
                }

                if(rnon_mutual_fund){
                    var non_mutual_fund_div = document.getElementById("non_mutual_fund_div").querySelectorAll('select[message=NonMutualFund]');
                    non_mutual_fund_div.forEach(function(val){
                        if(!$(val).val()){
                            formFlag = false;
                        }
                    });
                }

                if(rinsurance){
                    var insurance_view = document.getElementById("insurance_view").querySelectorAll('select[message=Insurance]');
                    insurance_view.forEach(function(val){
                        if(!$(val).val()){
                            formFlag = false;
                        }
                    });
                }

                if(!formFlag){
                    alert("Please fillup all fields.");
                }
                return formFlag;
            }else{
                alert("Please select atleast 1")
                return false;
            }
            
        }
        
        function changeHeaderCheckBox(){
            var rmutual_fund = $("#mutual_fund").attr('checked');
            var rnon_mutual_fund = $("#non_mutual_fund").attr('checked');
            var rinsurance = $("#insurance").attr('checked');
            if(rmutual_fund){
                document.getElementById("mutual_fund_over").style.display = "none";
            }else{
                document.getElementById("mutual_fund_over").style.display = "block";
            }
            if(rnon_mutual_fund){
                document.getElementById("non_mutual_fund_over").style.display = "none";
            }else{
                document.getElementById("non_mutual_fund_over").style.display = "block";
            }
            if(rinsurance){
                document.getElementById("insurance_over").style.display = "none";
            }else{
                document.getElementById("insurance_over").style.display = "block";
            }
        }

        function changeProduct(index){
            var product_id = document.getElementById("product_id"+index).value;
            if(product_id == 0){
                document.getElementById("product_id_div"+index).style.display = "none";
                document.getElementById("product_name_div"+index).style.display = "block";
            }else{
                document.getElementById("product_id_div"+index).style.display = "block";
                document.getElementById("product_name_div"+index).style.display = "none";
            }
        }

        function changeProductType(index){
            var product_id = document.getElementById("product_type_id"+index).value;
            if(product_id == 0){
                document.getElementById("product_type_id_div"+index).style.display = "none";
                document.getElementById("product_type_name_div"+index).style.display = "block";
            }else{
                document.getElementById("product_type_id_div"+index).style.display = "block";
                document.getElementById("product_type_name_div"+index).style.display = "none";
            }
        }
    </script>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <style>
        .spaceless td {
            padding:6px !important;
        }
        .spaceless input, .spaceless textarea, .select2-container--default .select2-selection--single {
            border: none !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 18px;
            font-size: 12px;
        }
        .select2-container .select2-selection--single .select2-selection__rendered {
            white-space: break-spaces;
        }
        .select2-container--default .select2-selection--single {
            height: auto !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: auto !important;
            top: 50%;
        }
    </style>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
@endsection
@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">premium calculators</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Review of Investments</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    
                        <!-- <div class="rt-btn-prt">  rt-pnl
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                
                            <form action="{{route('frontend.review_of_existing_investment_output')}}" method="post" onsubmit="return onsubmitFunction();">
                                <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-5 col-form-label">Client Name</label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="client_name" placeholder="" name="client_name">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="checkbox" name="mutual_fund" id="mutual_fund" onchange="changeHeaderCheckBox();"> Mutual Fund
                                            <span class="checkmark"></span>
                                        </label>
                                        
                                        <div class="col-md-12">
                                            <div class="table-responsive pt-2">
                                                <div class="roundTable">
                                                    <table id="mfTable" class="table table-bordered" cellspacing="0">
                                                        <tbody id="mutual_fund_div" class="spaceless">
                                                            <tr id="mutual_fund_tr10">
                                                                <th style="width: 5%;height:40px;">
                                                                   
                                                                </th>
                                                                <th style="width: 10%;height:40px;">
                                                                    
                                                                </th>
                                                                <th style="width: 30%;height:40px;">
                                                                    
                                                                </th>
                                                                <!-- <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" name="day1[0]" onchange="changeReturnCheckbox(0,'oneday');" id="oneday0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" name="day7[0]" onchange="changeReturnCheckbox(0,'oneweek');" id="oneweek0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th> -->
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" name="month1[0]" onchange="changeReturnCheckbox(0,'onemonth');" id="onemonth0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" name="month3[0]" onchange="changeReturnCheckbox(0,'threemonth');" id="threemonth0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" checked="checked" name="month6[0]" onchange="changeReturnCheckbox(0,'sixmonth');" id="sixmonth0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" checked="checked" name="year1[0]" onchange="changeReturnCheckbox(0,'oneyear');" id="oneyear0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" checked="checked" name="year3[0]" onchange="changeReturnCheckbox(0,'threeyear');" id="threeyear0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" checked="checked" name="year5[0]" onchange="changeReturnCheckbox(0,'fiveyear');" id="fiveyear0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" checked="checked" name="year10[0]" onchange="changeReturnCheckbox(0,'tenyear');" id="tenyear0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                    <label class="checkcontainer">
                                                                        <input type="checkbox" name="incret[0]" onchange="changeReturnCheckbox(0,'incret');" id="incret0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </th>
                                                                <th>
                                                                </th>
                                                            </tr>
                                                            <tr id="mutual_fund_tr20">
                                                                <th rowspan="2">Asset Class</th>
                                                                <th rowspan="2">Category</th>
                                                                <th rowspan="2">Scheme</th>
                                                                <th colspan="8" class="text-center">Performance</th>
                                                                <th rowspan="2">Action</th>
                                                            </tr>
                                                            <tr id="mutual_fund_tr30">
                                                                <!-- <th class="text-center">1 day</th>
                                                                <th class="text-center">7 day</th> -->
                                                                <th class="text-center">1 mth</th>
                                                                <th class="text-center">3 mth</th>
                                                                <th class="text-center">6 mth</th>
                                                                <th class="text-center">1 Year</th>
                                                                <th class="text-center">3 Year</th>
                                                                <th class="text-center">5 Year</th>
                                                                <th class="text-center">10 Year</th>
                                                                <th class="text-center">inception</th>
                                                            </tr>
                                                            <tr id="mutual_fund_tr40">
                                                                <td>
                                                                    <div class="allocationSegment mfTableCheck">
                                                                        <!--<label class="allocationCheck">-->
                                                                        <!--    <input type="checkbox" checked="checked" name="assets_checkbox[]">-->
                                                                        <!--    <span class="checkmark"></span>-->
                                                                        <!--</label>-->
                                                                        <div class="form-group mb-0">
                                                                            <select class="form-control" id="asset_class0" name="asset_class[]" onchange="changeAssetClass(0);">
                                                                                <option value="0">Custom</option>
                                                                                <?php foreach ($assets_list as $key => $value) { ?>
                                                                                    <option value="<?php echo $value;?>"><?php echo $value;?></option>
                                                                                <?php }?>
                                                                            </select>
                                                                            <!--<b class="schemeSelectNotch"></b>-->
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0" style="padding:0px;">
                                                                        <select class="form-control ui-autocomplete-input" id="category_id0" name="category_id[]" onchange="changeCategory(0);">
                                                                            <option value="0">Custom</option>
                                                                            <?php foreach ($category_list as $key => $value) { ?>
                                                                                <option value="<?php echo $value->classcode;?>"><?php echo ($value->class_name)?$value->class_name:$value->classname;?></option>
                                                                            <?php }?>
                                                                        </select>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="form-group mb-0" style="padding:0px;">
                                                                      <select class="form-control ui-autocomplete-input schemecode_id" name="schemecode_id[0]" id="schemecode_id0" onchange="changeScheme(0)" message="MutualFund" datatype="0">
                                                                        <option value="">Select</option>
                                                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                        <?php }?>
                                                                      </select>
                                                                    </div>
                                                                </td>
                                                                <!-- <td class="text-center" id="day1_0"></td>
                                                                <td class="text-center" id="day7_0"></td> -->
                                                                <td class="text-center" id="month1_0"></td>
                                                                <td class="text-center" id="month3_0"></td>
                                                                <td class="text-center" id="month6_0"></td>
                                                                <td class="text-center" id="year1_0"></td>
                                                                <td class="text-center" id="year3_0"></td>
                                                                <td class="text-center" id="year5_0"></td>
                                                                <td class="text-center" id="year10_0"></td>
                                                                <td class="text-center" id="incret_0"></td>
                                                                <td rowspan="2" id="" style="color:red;text-align: center;" onclick="removeSchemecode('0');">
                                                                    <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                                                                </td>
                                                            </tr>
        
                                                            <tr id="mutual_fund_tr50">
                                                                <td style="height:40px;">
                                                                    <label class="checkcontainer" style="margin-top: -12px;">
                                                                        <input type="checkbox" checked="checked" name="category_checkbox[0]" id="category_checkbox0">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                </td>
                                                                <td class="text-center" id="category_0" colspan="2">&nbsp;</td>
                                                                <!-- <td class="text-center" id="category_day1_0"></td>
                                                                <td class="text-center" id="category_day7_0"></td> -->
                                                                <td class="text-center" id="category_month1_0"></td>
                                                                <td class="text-center" id="category_month3_0"></td>
                                                                <td class="text-center" id="category_month6_0"></td>
                                                                <td class="text-center" id="category_year1_0"></td>
                                                                <td class="text-center" id="category_year3_0"></td>
                                                                <td class="text-center" id="category_year5_0"></td>
                                                                <td class="text-center" id="category_year10_0"></td>
                                                                <td class="text-center" id="category_incret_0"></td>
                                                            </tr>
                                                            <tr id="mutual_fund_tr60">
                                                                <td colspan="13"><textarea style="width: 100%; vertical-align: bottom;" maxlength="500" name="mutual_fund_comment[]" rows="2"> </textarea></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="mutual_fund_over">
                                                    
                                                </div>
                                            </div>
                                            <div class="text-center pt-2">
                                                <button type="button" class="btn banner-btn" onclick="AddMoreMutualFund();">Add More</button>
                                            </div>
                                            <div class="calcucatorText">*Mutual Fund investments are subject to market risk, read all scheme related document carefully.</div>
                                        </div>
                                    </div>
                                    <div class="row mt-5">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="checkbox" name="non_mutual_fund" id="non_mutual_fund" onchange="changeHeaderCheckBox();"> Non Mutual Fund
                                            <span class="checkmark"></span>
                                        </label>
                                        
                                        <div class="col-md-12">
                                            <div class="table-responsive pt-2">
                                                <div class="roundTable">
                                                    <table id="nonMfTable" class="table table-bordered">
                                                        <thead>
                                                          <tr>
                                                            <th style="width: 20%;">Product</th>
                                                            <th style="width: 75%;">Comments</th>
                                                            <th style="width: 5%;">Action</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody id="non_mutual_fund_div" class="">
                                                          <tr id="non_mutual_fund_tr_0">
                                                            <td>
                                                                <div class="allocationSegment mfTableCheck">
                                                                    <div class="form-group mb-0 mx-1" id="product_id_div0">
                                                                        <select class="form-control" id="product_id0" name="product_id[]" onchange="changeProduct(0);" message="NonMutualFund" datatype="0">
                                                                            <option value="">Select</option>
                                                                            <option value="0">Custom</option>
                                                                            <?php foreach ($product_list as $key => $value) { ?>
                                                                                <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                        <!--<b class="schemeSelectNotch"></b>-->
                                                                    </div>
                                                                    <div class="form-group mb-0" id="product_name_div0" style="display: none;">
                                                                        <textarea style="width: 100%;" id="product_name0" maxlength="500" rows="3" name="product_name[]"></textarea>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><textarea style="width: 100%;" maxlength="500" rows="3" name="non_mutual_fund_comment[]"> </textarea></td>
                                                            <td id="" style="color:red;text-align: center;" onclick="removeNonMutualFund('0');">
                                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                </div>
                                                <div style="position: absolute;top: 0;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="non_mutual_fund_over">
                                                    
                                                </div>
                                            </div>
                                            <div class="text-center pt-2">
                                                <button type="button" class="btn banner-btn" onclick="AddMoreNonMutualFund();">Add More</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-5">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="checkbox" name="insurance" id="insurance" onchange="changeHeaderCheckBox();"> Insurance
                                            <span class="checkmark"></span>
                                        </label>
                                        
                                        <div class="col-md-12">
                                            <div class="table-responsive pt-2">
                                                <div class="roundTable">
                                                    <table id="insuranceTable" class="table table-bordered">
                                                        <thead>
                                                          <tr>
                                                            <th style="width: 20%;">Product Type</th>
                                                            <th style="width: 20%;">Product Name</th>
                                                            <th style="width: 55%;">Comments</th>
                                                            <th style="width: 5%;">Action</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody id="insurance_view" class="">
                                                          <tr id="insurance_tr_0">
                                                            <td>
                                                                <div class="allocationSegment mfTableCheck">
                                                                    <div class="form-group mb-0 mx-1" id="product_type_id_div0">
                                                                        <select class="form-control" id="product_type_id0" name="product_type_id[]" onchange="changeProductType(0);" message="Insurance" datatype="0">
                                                                             <option value="">Select</option> 
                                                                            <option value="0">Custom</option>
                                                                            <?php foreach ($product_type_list as $key => $value) { ?>
                                                                                <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                                            <?php } ?>
                                                                        </select>
                                                                        <!--<b class="schemeSelectNotch"></b>-->
                                                                    </div>
                                                                    <div class="form-group mb-0" id="product_type_name_div0" style="display: none;">
                                                                        <textarea style="width: 100%;" id="product_type_name0" maxlength="500" rows="3" name="product_type_name[]"></textarea>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><textarea style="width: 100%;" maxlength="500" rows="3" name="insurance_user[]"> </textarea></td>
                                                            <td><textarea style="width: 100%;" maxlength="500" rows="3" name="insurance_comment[]"> </textarea></td>
                                                            <td id="" style="color:red;text-align: center;" onclick="removeInsurance('0');">
                                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                            </td>
                                                          </tr>
                                                        </tbody>
                                                      </table>
                                                </div>
                                                <div style="position: absolute;top: 0;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="insurance_over">
                                                    
                                                </div>
                                            </div>
                                            <div class="text-center pt-2">
                                                <button type="button" class="btn banner-btn" onclick="addMoreInsurance();">Add More</button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="form-group row">
                                        <div class="offset-1 col-sm-10">
                                            <div class="calcBelowBtn pt-4">
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><!-- <i class="fa fa-angle-left"></i> --> Back</button>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button type="submit" class="btn banner-btn mx-3">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
