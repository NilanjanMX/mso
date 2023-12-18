@extends('layouts.frontend')
@php
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $suggest;
    }
    
    $data['include_performance'] = old('include_performance');
    if(!$data['include_performance']){
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type');
    if(!$data['suggestedlist_type']){
        $data['suggestedlist_type'] = $suggestedlist_type;
    }

@endphp

@section('js_after')
    

    <script type="text/javascript">
        
         @if(isset($data['suggest']))
            @if($data['suggest'] == "1")
                $(document).ready(function() {
                    setTimeout(function(){
                        $('.include-performance-container').show(500);
                    },500)


                    @if($data['suggestedlist_type'] == "createlist")
                        $('.customlist-suggested-scheme-container').css('display','none');
                        $('.categorylist-suggested-scheme-container').css('display','none');
                        $('.createlist-suggested-scheme-container').css('display','block');
                    @elseif($data['suggestedlist_type'] == "customlist")
                        $('.customlist-suggested-scheme-container').css('display','block');
                        $('.categorylist-suggested-scheme-container').css('display','none');
                        $('.createlist-suggested-scheme-container').css('display','none');
                    @elseif($data['suggestedlist_type'] == "categorylist")
                        $('.customlist-suggested-scheme-container').css('display','none');
                        $('.categorylist-suggested-scheme-container').css('display','block');
                        $('.createlist-suggested-scheme-container').css('display','none');
                    @endif
                });
            @endif
        @endif
    </script>

    <script>
        var scheme_data_list = <?php echo json_encode($scheme_data_list);?>;
        var scheme_list = <?php echo json_encode($scheme_list);?>;
        var assets_list = <?php echo json_encode($assets_list);?>;
        var category_list = <?php echo json_encode($category_list);?>;
        var lumpsum_index = 0;
        var main_lumpsum_index = 0;
        var sip_index = 0;
        var main_sip_index = 0;
        var stp_index = 0;
        var main_stp_index = 0;
        var swp_index = 0;
        var main_swp_index = 0;
        var non_mf_product_index = 0;
        var insurance_product_index = 0;

        var lumpsum_error = true;

        var selected_scheme_id = "";
        var selected_index = "";
        var selected_main_index = "";
        
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
        
        $('.schemecode_id').select2({
            placeholder: "Select Fund",
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

        function isNumeric(e) {
            const pattern = /^[0-9.]$/;
            return pattern.test(e.key )
        }

        function validateCalCulation(max,key_name,index){
           var val = document.getElementById(key_name+"_"+index).value;

           if(parseFloat(val) > max){
                document.getElementById(key_name+"_"+index+"_error").innerHTML = "Max value "+max;
           }else{
                document.getElementById(key_name+"_"+index+"_error").innerHTML = "";
           }
        }

        function isOnlyNumber(e) {
            const pattern = /^[0-9]$/;
            return pattern.test(e.key )
        }

        function removeSWP(index){
            document.getElementById("swp_tr_"+index).remove();
        }

        function removeSWPMain(index){
            document.getElementById("swp_main_"+index).remove();
        }

        function AddSWPAssertClass (){
            swp_index = swp_index + 1;
            main_swp_index = main_swp_index + 1;

            var iHtml = `<div id="swp_main_`+main_swp_index+`" class="mt-5">
                            <div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Total Investment Amount</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="swp_total_investment_amount[`+main_swp_index+`]" class="form-control" id="swp_total_investment_amount_`+main_swp_index+`" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon ">
                                                    ₹
                                                </div>
                                                <em id="swp_total_investment_amount_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="swp_assumed_rate_of_return[`+main_swp_index+`]" class="form-control" id="swp_assumed_rate_of_return_`+main_swp_index+`" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`);" onkeypress="return isNumeric(event)">
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                                <em id="swp_assumed_rate_of_return_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">SWP Frequency</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="swp_frequency_`+main_swp_index+`" name="swp_frequency[`+main_swp_index+`]" onchange ="changeSwpCalculation(`+main_swp_index+`);">   
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Fortnightly">Fortnightly</option>
                                                    <option value="Monthly" selected="selected">Monthly</option>
                                                </select>
                                                <em id="swp_frequency_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">SWP Period</label>
                                            <div class="col-sm-3">
                                                <input type="number" name="swp_period_year[`+main_swp_index+`]" id="swp_period_year_`+main_swp_index+`" class="form-control" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                   Yrs
                                                </div>
                                                <em id="swp_period_year_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                            <div class="col-sm-3">
                                                <input type="number" name="swp_period_month[`+main_swp_index+`]" id="swp_period_month_`+main_swp_index+`" class="form-control" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`); changeMonthValue(`+main_swp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon" style="width:70px;">
                                                   Months
                                                </div>
                                                <em id="swp_period_month_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Required End Value</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="swp_required_end_value[`+main_swp_index+`]" id="swp_required_end_value_`+main_swp_index+`" class="form-control" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="swp_required_end_value_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">SWP Amount</label>
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        <div class="form-check form-check-inline">
                                                            <label class="checkLinecontainer mb-0 mt-2" for="swp_type_amount_`+main_swp_index+`">In Amount
                                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="swp_type_amount[`+main_swp_index+`]" id="swp_type_amount_`+main_swp_index+`" value="1" onchange="changeSwpCalculation(`+main_swp_index+`); changeSWPAmount(`+main_swp_index+`,1);"  checked>
                                                                <span class="checkmark"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="swp_in_amount[`+main_swp_index+`]" id="swp_in_amount_`+main_swp_index+`" class="form-control" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`); changeSWPAmount(`+main_swp_index+`,3);" onkeypress="return isNumeric(event)">
                                                        <div class="cal-icon">
                                                            ₹
                                                        </div>
                                                        <em id="swp_in_amount_`+main_swp_index+`_error" class="error"></em>
                                                        <div id="swp_total_investment_amount_message_`+main_swp_index+`"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-check form-check-inline">
                                                            <label class="checkLinecontainer mb-0 mt-2" for="swp_type_percent_`+main_swp_index+`">In %
                                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="swp_type_amount[`+main_swp_index+`]" id="swp_type_percent_`+main_swp_index+`" value="2"  onchange="changeSwpCalculation(`+main_swp_index+`);  changeSWPAmount(`+main_swp_index+`,2);" >
                                                                <span class="checkmark"></span>
                                                            </label> 
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">    
                                                        <input type="hidden" name="swp_in_amount_hide[`+main_swp_index+`]" id="swp_in_amount_hide_`+main_swp_index+`">
                                                        <input type="text" name="swp_in_percent[`+main_swp_index+`]" id="swp_in_percent_`+main_swp_index+`" class="form-control" value="" onkeyup ="changeSwpCalculation(`+main_swp_index+`); changeSWPAmount(`+main_swp_index+`,4);" readonly="true" onkeypress="return isNumeric(event)">
                                                        <div class="cal-icon">
                                                                %
                                                        </div>
                                                        <em id="swp_in_percent_`+main_swp_index+`_error" class="error"></em>
                                                        <div id="swp_total_investment_percent_message_`+main_swp_index+`"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Actual End Value</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="swp_actual_end_value[`+main_swp_index+`]" id="swp_actual_end_value_`+main_swp_index+`" class="form-control" value="" readonly="readonly" message="swp" datatype="`+main_stp_index+`">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="swp_actual_end_value_`+main_swp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive pt-2">
                                <div class="roundTable">
                                    <table id="nonMfTable" class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width: 15%;">
                                                Investor
                                            </th>
                                            <th style="width: 30%;">Scheme</th>
                                            <th style="">
                                                Category
                                            </th>
                                            <th style="width: 20%;">Amount</th>
                                            <th style="width: 5%;">Action</th>
                                          </tr>
                                        </thead>
                                        <tbody id="swp_div_`+main_swp_index+`" class="spaceless">
                                          <tr id="swp_tr_`+swp_index+`">
                                            <td><input type="text" value="" style="width: 100%;" maxlength="500" name="swp_investor[`+main_swp_index+`][`+swp_index+`]" id="swp_investor_`+swp_index+`"></td>
                                            <td>
                                                <div class="">
                                                    <div class="form-group mb-0" id="swp_schemecode_id_div_`+swp_index+`">
                                                        <select class="form-control schemecode_id" id="swp_schemecode_id_`+swp_index+`" name="swp_schemecode_id[`+main_swp_index+`][`+swp_index+`]" onchange="changeSWPScheme(`+main_swp_index+`,`+swp_index+`);" message="schemecode_id">
                                                            <option value="">Select</option>
                                                            <option value="0">Custom</option>
                                                            <?php foreach ($scheme_list as $key => $value) { ?>
                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0" id="swp_schemecode_id_d_`+swp_index+`" style="display: none;">
                                                        <input type="text" value="" style="width: 100%;" id="swp_schemecode_name_`+swp_index+`" maxlength="500" name="swp_schemecode_name[`+main_swp_index+`][`+swp_index+`]">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="swp_category_`+swp_index+`"></div>
                                                <div id="swp_category_id_`+swp_index+`" style="display: none">
                                                    <input type="text" value="" style="width: 100%;" id="swp_category_input_`+swp_index+`" maxlength="500" name="swp_category_input[`+main_swp_index+`][`+swp_index+`]">
                                                </div>
                                            </td>
                                            <td><input type="text" value="" style="width: 100%;"  message="text" maxlength="500" name="swp_amount[`+main_swp_index+`][`+swp_index+`]" id="swp_amount_`+swp_index+`" onkeypress="return isOnlyNumber(event)"></td>
                                            <td id="" style="color:red;text-align: center;" onclick="removeSWP('`+swp_index+`');">
                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                                            </td>
                                          </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right text-danger">
                                <small id="swp_table_error_`+main_swp_index+`"></small>
                            </div>
                            <div class="text-right pt-2 mb-4">
                                <button type="button" class="btn banner-btn" onclick="AddMoreSWP(`+swp_index+`);">Add More</button>
                                <button type="button" class="btn banner-btn removeGoalCalculatorBtn ml-3" onclick="removeSWPMain(`+main_swp_index+`);"> Remove SWP PLAN</button>
                            </div>
                        </div>`;

            
            $("#swp_main").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function AddMoreSWP(index){
            swp_index = swp_index+1;
            iHtml = `<tr id="swp_tr_`+swp_index+`">
                        <td><input type="text" value="" style="width: 100%;" maxlength="500" name="swp_investor[`+index+`][`+swp_index+`]" id="swp_investor_`+swp_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="swp_schemecode_id_div_`+swp_index+`">
                                    <select class="form-control schemecode_id" id="swp_schemecode_id_`+swp_index+`" name="swp_schemecode_id[`+index+`][`+swp_index+`]" onchange="changeSWPScheme(`+index+`,`+swp_index+`);" message="schemecode_id">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="swp_schemecode_id_d_`+swp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="swp_schemecode_name_`+swp_index+`" maxlength="500" name="swp_schemecode_name[`+index+`][`+swp_index+`]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id="swp_category_`+swp_index+`"></div>
                            <div id="swp_category_id_`+swp_index+`" style="display: none">
                                <input type="text" value="" style="width: 100%;" id="swp_category_input_`+swp_index+`" maxlength="500" name="swp_category_input[`+index+`][`+swp_index+`]" onkeypress="return isOnlyNumber(event)">
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;"  message="text" maxlength="500" name="swp_amount[`+index+`][`+swp_index+`]" id="swp_amount_`+swp_index+`"></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeSWP('`+swp_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                        </td>
                      </tr>`;
            $("#swp_div_"+index).append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSWPScheme(main_index,index){
            var lumpsum_schemecode_id = document.getElementById('swp_schemecode_id_'+index).value;

            if(lumpsum_schemecode_id == 0){
                selected_scheme_id = "swp_schemecode_id";
                selected_index = index;
                selected_main_index = main_index;
                document.getElementById("modal_category_id").style.display = "block";
                document.getElementById("modal_title").value = "";
                document.getElementById("modal_category").value = "";
                $("#customModal").modal("show");
            }else{
                var data = scheme_list.find(o => o.schemecode == lumpsum_schemecode_id);
                console.log(data);
                if(data){
                    var class_name = data.class_name;
                    if(!class_name){
                        class_name = data.classname;
                    }
                    console.log(class_name);
                    document.getElementById('swp_category_'+index).innerHTML = class_name;
                }else{
                    document.getElementById('swp_category_'+index).innerHTML = "";
                }
            }
        }

        function changeNonMfProducts(key_name,index,rule){
            var non_mf_product_id = document.getElementById('non_mf_product_id_'+index).value;

            if(key_name == "non_mf_product_id"){
                if(non_mf_product_id == 0){
                    selected_scheme_id = "non_mf_product_id";
                    selected_index = index;

                    document.getElementById("modal_category_id").style.display = "none";
                    document.getElementById("modal_title").value = "";
                    document.getElementById("modal_category").value = "";
                    $("#customModal").modal("show");
                }
            }
        }

        function removeNonMfProducts(index){
            document.getElementById("non_mf_product_tr_"+index).remove();
        }

        function AddMoreNonMfProducts(){
            non_mf_product_index = non_mf_product_index+1;
            iHtml = `<tr class="generatetr" id="non_mf_product_tr_`+non_mf_product_index+`">
                        <td><input type="text" value="" style="width: 100%;" maxlength="500" name="non_mf_product_inverstor[]" id="non_mf_product_inverstor_`+non_mf_product_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="non_mf_product_id_div_`+non_mf_product_index+`">
                                    <select class="form-control schemecode_id" id="non_mf_product_id_`+non_mf_product_index+`" name="non_mf_product_id[]" onchange="changeNonMfProducts('non_mf_product_id',`+non_mf_product_index+`,0);">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="non_mf_product_id_d_`+non_mf_product_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="product_name_`+non_mf_product_index+`" name="product_name[]">
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;" name="non_mf_product_company[]" id="non_mf_product_company_`+non_mf_product_index+`" maxlength="40"></td>
                        <td><input type="text" value="" style="width: 100%;" name="non_mf_product_amount[]" id="non_mf_product_amount_`+non_mf_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;" name="non_mf_product_remark[]" id="non_mf_product_remark_`+non_mf_product_index+`" maxlength="100"></td>      
                        <td>
                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                <input type="checkbox" checked="checked" name="non_mf_product_attach[0]" id="non_mf_product_attach_`+non_mf_product_index+`">
                                <span class="checkmark" style="top: 50%;margin-top: -12px;"></span>
                            </label>
                        </td>
                        <td id="" style="color:red;text-align: center;" onclick="removeNonMfProducts('`+non_mf_product_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#non_mf_product_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeInsuranceProduct(key_name,index,rule){
            var insurance_product_type_id = document.getElementById('insurance_product_type_id_'+index).value;

            if(key_name == "insurance_product_type_id"){
                if(insurance_product_type_id == 0){
                    selected_scheme_id = "insurance_product_type_id";
                    selected_index = index;

                    document.getElementById("modal_category_id").style.display = "none";
                    document.getElementById("modal_title").value = "";
                    document.getElementById("modal_category").value = "";
                    $("#customModal").modal("show");
                }
            }
        }

        function removeInsuranceProduct(index){
            document.getElementById("insurance_product_tr_"+index).remove();
        }

        function addMoreInsuranceProduct(){
            insurance_product_index = insurance_product_index+1;
            iHtml = `<tr class="generatetr" id="insurance_product_tr_`+insurance_product_index+`">
                        <td><input type="text" value="" style="width: 100%;" name="insurance_product_investor[]" id="insurance_product_investor_`+insurance_product_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="insurance_product_type_div_`+insurance_product_index+`">
                                    <select class="form-control schemecode_id" id="insurance_product_type_id_`+insurance_product_index+`" name="insurance_product_type_id[]" onchange="changeInsuranceProduct('insurance_product_type_id',`+insurance_product_index+`,0);">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_type_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="insurance_product_type_d_`+insurance_product_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="product_type_name_`+insurance_product_index+`" maxlength="500" name="product_type_name[]">
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="40" name="insurance_product_company[]" id="insurance_product_company_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="500" name="insurance_product_sum_assured[]" id="insurance_product_sum_assured_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="500" name="insurance_product_annual_premium[]" id="insurance_product_annual_premium_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="100" name="insurance_product_remark[]" id="insurance_product_remark_`+insurance_product_index+`"></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeInsuranceProduct('`+insurance_product_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#insurance_product_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeMonthValue(main_index){
            var swp_period_month = document.getElementById("swp_period_month_"+main_index).value;
            if(swp_period_month){
                swp_period_month = (parseInt(swp_period_month))?parseInt(swp_period_month):0;
                if(swp_period_month>12){
                    document.getElementById("swp_period_month_"+main_index+"_error").innerHTML = "Less than equalto 12";
                }else{
                    document.getElementById("swp_period_month_"+main_index+"_error").innerHTML = "";
                }
            }else{
                document.getElementById("swp_period_month_"+main_index+"_error").innerHTML = "";
            }
        }
        
        function changeHeaderCheckBox(){
            
        }

        function saveModalData(){
            var modal_title = document.getElementById("modal_title").value;
            var modal_category = document.getElementById("modal_category").value;

            console.log(selected_scheme_id);
            if(modal_title){
                if(selected_scheme_id == "lumpsum_schemecode_id"){
                    document.getElementById("lumpsum_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("lumpsum_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("lumpsum_schemecode_name_"+selected_index).value = modal_title;

                    document.getElementById("lumpsum_category_"+selected_index).style.display = "none";
                    document.getElementById("lumpsum_category_id_"+selected_index).style.display = "block";
                    document.getElementById("lumpsum_category_input_"+selected_index).value = modal_category;
                }else if(selected_scheme_id == "sip_schemecode_id"){
                    document.getElementById("sip_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("sip_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("sip_schemecode_name_"+selected_index).value = modal_title;

                    document.getElementById("sip_category_"+selected_index).style.display = "none";
                    document.getElementById("sip_category_id_"+selected_index).style.display = "block";
                    document.getElementById("sip_category_input_"+selected_index).value = modal_category;
                }else if(selected_scheme_id == "stp_equity_scheme"){
                    document.getElementById("stp_equity_scheme_div_"+selected_index).style.display = "none";
                    document.getElementById("stp_equity_scheme_d_"+selected_index).style.display = "block";
                    document.getElementById("stp_equity_scheme_name_"+selected_index).value = modal_title;
                }else if(selected_scheme_id == "stp_schemecode_id"){
                    document.getElementById("stp_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("stp_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("stp_schemecode_name_"+selected_index).value = modal_title;
                }else if(selected_scheme_id == "swp_schemecode_id"){
                    document.getElementById("swp_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("swp_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("swp_schemecode_name_"+selected_index).value = modal_title;

                    document.getElementById("swp_category_"+selected_index).style.display = "none";
                    document.getElementById("swp_category_id_"+selected_index).style.display = "block";
                    document.getElementById("swp_category_input_"+selected_index).value = modal_category;
                }else if(selected_scheme_id == "non_mf_product_id"){
                    document.getElementById("non_mf_product_id_div_"+selected_index).style.display = "none";
                    document.getElementById("non_mf_product_id_d_"+selected_index).style.display = "block";
                    document.getElementById("product_name_"+selected_index).value = modal_title;
                }else if(selected_scheme_id == "insurance_product_type_id"){
                    document.getElementById("insurance_product_type_div_"+selected_index).style.display = "none";
                    document.getElementById("insurance_product_type_d_"+selected_index).style.display = "block";
                    document.getElementById("product_type_name_"+selected_index).value = modal_title;
                }
                $("#customModal").modal("hide");
            }else{
                alert("Scheme required");
            }
        }

        function changeSwpCalculation(index){
            console.log(index);
            document.getElementById("swp_in_amount_hide_"+index).value = "";
            var swp_amount = document.getElementById('swp_total_investment_amount_'+index).value;
            var swp_return = document.getElementById('swp_assumed_rate_of_return_'+index).value;
            var swp_frequency = document.getElementById('swp_frequency_'+index).value;
            var swp_period_year = document.getElementById('swp_period_year_'+index).value;
            var swp_period_month = document.getElementById('swp_period_month_'+index).value;
            var swp_required_end_value = document.getElementById('swp_required_end_value_'+index).value;
            var swp_in_amount = document.getElementById('swp_in_amount_'+index).value;
            var swp_in_percent = document.getElementById('swp_in_percent_'+index).value;

            var swp_type_amount = document.getElementById('swp_type_amount_'+index).value;
            var swp_type_percent = document.getElementById('swp_type_percent_'+index).value;

            document.getElementById("swp_assumed_rate_of_return_"+index+"_error").innerHTML = "";

            if(swp_return){
                swp_return = parseFloat(swp_return);
                if(swp_return > 12){
                    document.getElementById("swp_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 12.";
                }
            }

            if(!swp_amount || !swp_return || !swp_frequency || !swp_period_year || !swp_required_end_value){
                return false;
            }

            

            swp_return = parseFloat(swp_return);
            swp_amount = parseFloat(swp_amount);
            swp_in_amount = parseFloat(swp_in_amount);
            swp_in_percent = parseFloat(swp_in_percent);
            swp_period_month = parseFloat(swp_period_month);

            if(!swp_period_month){
                swp_period_month = 0;
            }

            var bi47 = parseInt(swp_period_year)*12 + swp_period_month;
            var bi48;
            var bi49;

            if(swp_frequency == "Weekly"){
                bi48 = bi47 * 4;
                bi49 = Math.pow((1+swp_return/100),(1/52))-1;

            }else if(swp_frequency == "Fortnightly"){
                bi48 = bi47 * 2;
                bi49 = Math.pow((1+swp_return/100),(1/26))-1;
            }else{
                bi48 = bi47;
                bi49 = Math.pow((1+swp_return/100),(1/12))-1;
            }

            var bi52 = swp_required_end_value/Math.pow((1+bi49),bi48);
            console.log("bi52:"+bi52);
            var bi53 = swp_amount-bi52;
            console.log("bi53:"+bi53);
            var bi54 = (bi53*bi49)/(1-Math.pow((1+bi49),(-bi48)));
            
            console.log("bi54:"+bi54);
            var bi55 = bi54/swp_amount;
            console.log("bi55:"+bi55);
            var bi56;
            if(swp_frequency == "Weekly"){
                bi56 = bi55 * 52;
            }else if(swp_frequency == "Fortnightly"){
                bi56 = bi55 * 26;
            }else{
                bi56 = bi55 * 12;
            }

            document.getElementById('swp_total_investment_amount_message_'+index).innerHTML = "Monthly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Max &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>"+parseFloat(bi54).toFixed(2)+"</span></b>";
            var bi551 = bi55*100;
            var bi61 = bi551*12;
            document.getElementById('swp_total_investment_percent_message_'+index).innerHTML = "Annually &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Max &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>"+parseFloat(bi61).toFixed(4)+"</span> %</b>";

            if($("#swp_type_amount_"+index).is(':checked')){
                if(!swp_in_amount){
                    return false;
                }
            }else if($("#swp_type_percent_"+index).is(':checked')){
                if(!swp_in_percent){
                    return false;
                }
            }

            if($("#swp_type_amount_"+index).is(':checked')){
                var bi55 = swp_in_amount*(1-Math.pow((1+bi49),(-bi48)))/bi49;
                var bi56 = swp_amount-bi55;
                var bi57 = bi56*Math.pow((1+bi49),bi48);
                document.getElementById('swp_actual_end_value_'+index).value = Number(bi57).toFixed(0);
                console.log(bi57);
            }else if($("#swp_type_percent_"+index).is(':checked')){
                var bi57 = swp_in_percent/100/12*swp_amount;
                document.getElementById("swp_in_amount_hide_"+index).value = bi57;
                var bi58 = bi57*(1-Math.pow((1+bi49),(-bi48)))/bi49;
                var bi59 = swp_amount-bi58;
                var bi60 = bi59*Math.pow((1+bi49),bi48);
                document.getElementById('swp_actual_end_value_'+index).value = Number(bi60).toFixed(0);
            }


        }

        function changeSIPCheckbox(){
            if($("#sip_checkbox").attr('checked')){
                document.getElementById("sip_view").style.display = "none";
            }else{
                document.getElementById("sip_view").style.display = "block";
            }
        }

        function changeSTPCheckbox(){
            if($("#stp_checkbox").attr('checked')){
                document.getElementById("stp_view").style.display = "none";
            }else{
                document.getElementById("stp_view").style.display = "block";
            }
        }

        function changeSWPCheckbox(){
            if($("#swp_checkbox").attr('checked')){
                document.getElementById("swp_view").style.display = "none";
            }else{
                document.getElementById("swp_view").style.display = "block";
            }
        }

        function changeNonMfProductCheckbox(){
            if($("#non_mf_product_checkbox").attr('checked')){
                document.getElementById("non_mf_products_view").style.display = "none";
            }else{
                document.getElementById("non_mf_products_view").style.display = "block";
            }
        }

        function changeInsuranceProductCheckbox(){
            if($("#insurance_product_checkbox").attr('checked')){
                document.getElementById("insurance_product_view").style.display = "none";
            }else{
                document.getElementById("insurance_product_view").style.display = "block";
            }
        }

        function changeSWPAmount(main_index,type){
            console.log(type);
            if(type == 1){
                $('#swp_in_amount_'+main_index).prop("readonly", false);
                $('#swp_in_percent_'+main_index).prop("readonly", true);
            }else if(type == 2){
                $('#swp_in_percent_'+main_index).prop("readonly", false);
                $('#swp_in_amount_'+main_index).prop("readonly", true);
            }else if(type == 3){
                var swp_in_amount = $('#swp_in_amount_'+main_index).val();
                var max_amount = document.getElementById("swp_total_investment_amount_message_"+main_index).getElementsByTagName('span')[0].innerHTML;

                swp_in_amount = parseFloat(swp_in_amount);
                max_amount = parseFloat(max_amount);

                if(swp_in_amount > max_amount){
                    document.getElementById("swp_in_amount_"+main_index+"_error").innerHTML = "Max";
                }else{
                    document.getElementById("swp_in_amount_"+main_index+"_error").innerHTML = "";
                }
                console.log(swp_in_amount);
                console.log(max_amount);
            }else if(type == 4){
                var swp_in_amount = $('#swp_in_percent_'+main_index).val();
                var max_amount = document.getElementById("swp_total_investment_percent_message_"+main_index).getElementsByTagName('span')[0].innerHTML;

                swp_in_amount = parseFloat(swp_in_amount);
                max_amount = parseFloat(max_amount);

                if(swp_in_amount > max_amount){
                    document.getElementById("swp_in_percent_"+main_index+"_error").innerHTML = "Max";
                }else{
                    document.getElementById("swp_in_percent_"+main_index+"_error").innerHTML = "";
                }
            }
        }

        function removeSTPMain(index){
            document.getElementById("stp_main_"+index).remove();
        }

        function changeLumpsumScheme(main_index,index){
            var lumpsum_investor = document.getElementById('lumpsum_investor_'+index).value;
            var lumpsum_schemecode_id = document.getElementById('lumpsum_schemecode_id_'+index).value;
            var lumpsum_amount = document.getElementById('lumpsum_amount_'+index).value;

            if(lumpsum_schemecode_id == 0){
                selected_scheme_id = "lumpsum_schemecode_id";
                selected_index = index;
                document.getElementById("modal_category_id").style.display = "block";
                document.getElementById("modal_title").value = "";
                document.getElementById("modal_category").value = "";
                $("#customModal").modal("show");
            }else{
                var data = scheme_list.find(o => o.schemecode == lumpsum_schemecode_id);
                console.log(data);
                if(data){
                    var class_name = data.class_name;
                    if(!class_name){
                        class_name = data.classname;
                    }
                    console.log(class_name);
                    document.getElementById('lumpsum_category_'+index).innerHTML = class_name;
                }else{
                    document.getElementById('lumpsum_category_'+index).innerHTML = "";
                }
            }
        }

        function changeLumpsumCalculation(index){
            var lumpsum_asset_class = document.getElementById('lumpsum_asset_class_'+index).value;
            var lumpsum_investment_amount = document.getElementById('lumpsum_investment_amount_'+index).value;
            var lumpsum_investment_period = document.getElementById('lumpsum_investment_period_'+index).value;
            var lumpsum_assumed_rate_of_return = document.getElementById('lumpsum_assumed_rate_of_return_'+index).value;

            lumpsum_investment_amount = parseFloat(lumpsum_investment_amount);
            lumpsum_investment_period = parseFloat(lumpsum_investment_period);
            lumpsum_assumed_rate_of_return = parseFloat(lumpsum_assumed_rate_of_return);

            console.log(lumpsum_investment_amount+"="+lumpsum_investment_period+"="+lumpsum_assumed_rate_of_return);

            if(lumpsum_investment_amount && lumpsum_investment_period && lumpsum_assumed_rate_of_return){

                var lumpsum_assumed_rate_of_return1 = lumpsum_assumed_rate_of_return / 100;
                lumpsum_assumed_rate_of_return1 = lumpsum_investment_amount * Math.pow((1+lumpsum_assumed_rate_of_return1),(lumpsum_investment_period));
                document.getElementById('lumpsum_actual_end_value_'+index).value = parseFloat(lumpsum_assumed_rate_of_return1).toFixed(0);
            }else{
                document.getElementById('lumpsum_actual_end_value_'+index).value = "";
            }

            document.getElementById("lumpsum_assumed_rate_of_return_"+index+"_error").innerHTML = "";

            if(lumpsum_assumed_rate_of_return){
                console.log("---="+lumpsum_assumed_rate_of_return);
                if(lumpsum_asset_class == "Equity"){
                    if(lumpsum_assumed_rate_of_return >15){
                        document.getElementById("lumpsum_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 15.";
                    }
                }else if(lumpsum_asset_class == "Hybrid"){
                    if(lumpsum_assumed_rate_of_return >12){
                        document.getElementById("lumpsum_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 12.";
                    }
                }else if(lumpsum_asset_class == "Debt"){
                    if(lumpsum_assumed_rate_of_return >8){
                        document.getElementById("lumpsum_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 8.";
                    }
                }else if(lumpsum_asset_class == "Other"){
                    if(lumpsum_assumed_rate_of_return >15){
                        document.getElementById("lumpsum_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 15.";
                    }
                }
            }
        }

        function changeLumpsumCheckbox(){
            if($("#lumpsum_checkbox").attr('checked')){
                document.getElementById("lumpsum_view").style.display = "none";
            }else{
                document.getElementById("lumpsum_view").style.display = "block";
            }
        }

        function AddLumpsumAssertClass(){
            main_lumpsum_index = main_lumpsum_index+1;
            lumpsum_index = lumpsum_index+1;
            var iHtml = `<div id="lumpsum_main_`+main_lumpsum_index+`" class="mt-5">
                            <div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Asset Class</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="lumpsum_asset_class_`+main_lumpsum_index+`" name="lumpsum_asset_class[`+main_lumpsum_index+`]"   onchange ="changeAssetClass(1,`+main_lumpsum_index+`); changeLumpsumCalculation(`+main_lumpsum_index+`);">    
                                                    @foreach($assets_list as $key => $value)
                                                        <option value="{{$value}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                                <em id="swp_asset_class_`+main_lumpsum_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Investment Amount</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="lumpsum_investment_amount[`+main_lumpsum_index+`]" class="form-control" id="lumpsum_investment_amount_`+main_lumpsum_index+`" value="" onkeyup ="changeLumpsumCalculation(`+main_lumpsum_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="lumpsum_investment_amount_`+main_lumpsum_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Investment Period</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="lumpsum_investment_period[`+main_lumpsum_index+`]" class="form-control" id="lumpsum_investment_period_`+main_lumpsum_index+`" value="" onkeyup ="changeLumpsumCalculation(`+main_lumpsum_index+`);" onkeypress="return isNumeric(event)">
                                                <em id="lumpsum_investment_period_`+main_lumpsum_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="lumpsum_assumed_rate_of_return[`+main_lumpsum_index+`]" id="lumpsum_assumed_rate_of_return_`+main_lumpsum_index+`" class="form-control" value="" onkeyup ="changeLumpsumCalculation(`+main_lumpsum_index+`);" onkeypress="return isNumeric(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="lumpsum_assumed_rate_of_return_`+main_lumpsum_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="lumpsum_actual_end_value[`+main_lumpsum_index+`]" id="lumpsum_actual_end_value_`+main_lumpsum_index+`" class="form-control" value="" readonly="readonly" message="lumpsum" datatype="`+main_stp_index+`">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="lumpsum_actual_end_value_`+main_lumpsum_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive pt-2">
                                <div class="roundTable">
                                    <table id="nonMfTable" class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width:15%;">
                                                Investor
                                            </th>
                                            <th style="width:33%;">Scheme</th>
                                            <th>
                                                Category
                                            </th>
                                            <th style="width:15%;">Amount</th>
                                            <th style="width: 5%;">Action</th>
                                          </tr>
                                        </thead>
                                        <tbody id="lumpsum_div_`+main_lumpsum_index+`" class="spaceless">
                                          <tr id="lumpsum_tr_`+lumpsum_index+`">
                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="lumpsum_investor[`+main_lumpsum_index+`][`+lumpsum_index+`]" id="lumpsum_investor_`+lumpsum_index+`"></td>
                                            <td style="width:33%;">
                                                <div class="">
                                                    <div class="form-group mb-0" id="lumpsum_schemecode_id_div_`+lumpsum_index+`">
                                                        <select class="form-control ui-autocomplete-input  schemecode_id" id="lumpsum_schemecode_id_`+lumpsum_index+`" name="lumpsum_schemecode_id[`+main_lumpsum_index+`][`+lumpsum_index+`]" onchange="changeLumpsumScheme(`+main_lumpsum_index+`,`+lumpsum_index+`);" message="schemecode_id">
                                                            <option value="">Select</option>
                                                            <option value="0">Custom</option>
                                                            <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0" id="lumpsum_schemecode_id_d_`+lumpsum_index+`" style="display: none;">
                                                        <input type="text" value="" style="width: 100%;" id="lumpsum_schemecode_name_`+lumpsum_index+`" maxlength="500" name="lumpsum_schemecode_name[`+main_lumpsum_index+`][`+lumpsum_index+`]">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="lumpsum_category_`+lumpsum_index+`"></div>
                                                <div id="lumpsum_category_id_`+lumpsum_index+`" style="display: none">
                                                    <input type="text" value="" style="width: 100%;" id="lumpsum_category_input_`+lumpsum_index+`" maxlength="500" name="lumpsum_category_input[`+main_lumpsum_index+`][`+lumpsum_index+`]">
                                                </div>
                                            </td>
                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="lumpsum_amount[`+main_lumpsum_index+`][`+lumpsum_index+`]" id="lumpsum_amount_`+lumpsum_index+`"  message="lumpsum_amount" onkeypress="return isOnlyNumber(event)"> </td>
                                            <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeLumpsum('`+lumpsum_index+`');">
                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                            </td>
                                          </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right text-danger">
                                <small id="lumpsum_table_error_`+main_lumpsum_index+`"></small>
                            </div>
                            <div class="text-right pt-2 mb-4">
                                <button type="button" class="btn banner-btn" onclick="AddMoreLumpsum(`+main_lumpsum_index+`);">Add More</button>
                                <button type="button" class="btn banner-btn removeGoalCalculatorBtn ml-3" onclick="removeLumpsumMain(`+main_lumpsum_index+`);">Remove Asset Class</button>
                            </div>
                        </div>`;

            $("#lumpsum_main").append(iHtml);

            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function removeLumpsum(index){
            document.getElementById("lumpsum_tr_"+index).remove();
        }

        function removeLumpsumMain(index){
            document.getElementById("lumpsum_main_"+index).remove();
        }

        function AddMoreLumpsum(index){
            console.log(index);
            var lumpsum_asset_class = document.getElementById("lumpsum_asset_class_"+index).value;
            var selectHtml = ``;
            scheme_list.forEach(function(value){
                if(value.asset_type == lumpsum_asset_class){
                    selectHtml = selectHtml+`<option value="`+value.schemecode+`">`+value.s_name+`</option>`;
                }
            });

            lumpsum_index = lumpsum_index+1;
            iHtml = `<tr class="generatetr" id="lumpsum_tr_`+lumpsum_index+`">
                        <td><input type="text" value="" style="width: 100%; maxlength="100" name="lumpsum_investor[`+index+`][`+lumpsum_index+`]" id="lumpsum_investor_`+lumpsum_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="lumpsum_schemecode_id_div_`+lumpsum_index+`">
                                    <select class="form-control schemecode_id" id="lumpsum_schemecode_id_`+lumpsum_index+`" name="lumpsum_schemecode_id[`+index+`][`+lumpsum_index+`]" onchange="changeLumpsumScheme(`+index+`,`+lumpsum_index+`);" message="schemecode_id">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        `+selectHtml+`
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="lumpsum_schemecode_id_d_`+lumpsum_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="lumpsum_schemecode_name_`+lumpsum_index+`" maxlength="500" name="lumpsum_schemecode_name[`+index+`][`+lumpsum_index+`]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id="lumpsum_category_`+lumpsum_index+`"></div>
                            <div id="lumpsum_category_id_`+lumpsum_index+`" style="display: none">
                                <input type="text" value="" style="width: 100%;" id="lumpsum_category_input_`+lumpsum_index+`" maxlength="500" name="lumpsum_category_input[`+index+`][`+lumpsum_index+`]">
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="10" name="lumpsum_amount[`+index+`][`+lumpsum_index+`]" id="lumpsum_amount_`+lumpsum_index+`" message="lumpsum_amount" onkeypress="return isOnlyNumber(event)"></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeLumpsum('`+lumpsum_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#lumpsum_div_"+index).append(iHtml);

            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSIPCalculation(index){
            var sip_asset_class = document.getElementById('sip_asset_class_'+index).value;
            var sip_sip_amount = document.getElementById('sip_sip_amount_'+index).value;
            var sip_frequency = document.getElementById('sip_frequency_'+index).value;
            var sip_assumed_rate_of_return = document.getElementById('sip_assumed_rate_of_return_'+index).value;
            var sip_sip_period = document.getElementById('sip_sip_period_'+index).value;
            var sip_investment_period = document.getElementById('sip_investment_period_'+index).value;

            if(sip_sip_period && sip_investment_period){
                var sip_sip_period1 = parseInt(sip_sip_period);
                var sip_investment_period1 = parseInt(sip_investment_period);
                if(sip_sip_period1 <= sip_investment_period1){
                    document.getElementById('sip_investment_period_'+index+'_error').innerHTML = "";
                }else{
                    document.getElementById('sip_investment_period_'+index+'_error').innerHTML = "Should be equal to or greater than SIP Period";
                }
            }

            if(sip_investment_period && sip_sip_period && sip_assumed_rate_of_return && sip_frequency && sip_sip_amount){

                sip_sip_amount = parseInt(sip_sip_amount);
                sip_frequency = parseInt(sip_frequency);
                sip_assumed_rate_of_return = parseFloat(sip_assumed_rate_of_return);
                sip_sip_period = parseInt(sip_sip_period);
                sip_investment_period = parseInt(sip_investment_period);

                var expected_return = (Math.pow((1+sip_assumed_rate_of_return/100) , (1/sip_frequency)) - 1);
                var sip_periods = sip_sip_period * sip_frequency;
                var balance_period = (sip_investment_period - sip_sip_period) * sip_frequency;

                var total_investment = sip_sip_amount * sip_periods;

                var future_value = (1+expected_return)*sip_sip_amount*((Math.pow((1+expected_return),(sip_periods))-1))/expected_return;

                var final_value = future_value*Math.pow((1+expected_return),balance_period);

                document.getElementById('sip_total_investment_'+index).value = parseFloat(total_investment).toFixed(0);
                document.getElementById('sip_expected_future_value_'+index).value = parseFloat(final_value).toFixed(0);
            }else{
                document.getElementById('sip_total_investment_'+index).value = "";
                document.getElementById('sip_expected_future_value_'+index).value = "";
            }

            document.getElementById("sip_assumed_rate_of_return_"+index+"_error").innerHTML = "";

            if(sip_assumed_rate_of_return){
                if(sip_asset_class == "Equity"){
                    if(sip_assumed_rate_of_return >15){
                        document.getElementById("sip_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 15.";
                    }
                }else if(sip_asset_class == "Hybrid"){
                    if(sip_assumed_rate_of_return >12){
                        document.getElementById("sip_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 12.";
                    }
                }else if(sip_asset_class == "Debt"){
                    if(sip_assumed_rate_of_return >8){
                        document.getElementById("sip_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 8.";
                    }
                }else if(sip_asset_class == "Other"){
                    if(sip_assumed_rate_of_return >15){
                        document.getElementById("sip_assumed_rate_of_return_"+index+"_error").innerHTML = "Please enter a value less than 15.";
                    }
                }
            }
        }

        function changeSIPScheme(main_index,index){
            var sip_schemecode_id = document.getElementById('sip_schemecode_id_'+index).value;

            if(sip_schemecode_id == 0){
                selected_scheme_id = "sip_schemecode_id";
                selected_index = index;
                document.getElementById("modal_category_id").style.display = "block";
                document.getElementById("modal_title").value = "";
                document.getElementById("modal_category").value = "";
                $("#customModal").modal("show");
            }else{
                var data = scheme_list.find(o => o.schemecode == sip_schemecode_id);
                console.log(data);
                if(data){
                    var class_name = data.class_name;
                    if(!class_name){
                        class_name = data.classname;
                    }
                    document.getElementById('sip_category_'+index).innerHTML = class_name;
                }else{
                    document.getElementById('sip_category_'+index).innerHTML = "";
                }
            }
        }

        function removeSIP(index){
            document.getElementById("sip_tr_"+index).remove();
        }

        function AddMoreSIP(index){
            var lumpsum_asset_class = document.getElementById("sip_asset_class_"+index).value;
            var selectHtml = ``;
            scheme_list.forEach(function(value){
                if(value.asset_type == lumpsum_asset_class){
                    selectHtml = selectHtml+`<option value="`+value.schemecode+`">`+value.s_name+`</option>`;
                }
            });
            sip_index = sip_index+1;
            iHtml = `<tr id="sip_tr_`+sip_index+`">
                        <td><input type="text" value="" style="width: 100%;" maxlength="100" name="sip_investor[`+index+`][`+sip_index+`]" id="sip_investor_`+sip_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="sip_schemecode_id_div_`+sip_index+`">
                                    <select class="form-control ui-autocomplete-input  schemecode_id" id="sip_schemecode_id_`+sip_index+`" name="sip_schemecode_id[`+index+`][`+sip_index+`]" onchange="changeSIPScheme(`+index+`,`+sip_index+`);" message="schemecode_id">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        `+selectHtml+`
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="sip_schemecode_id_d_`+sip_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="sip_schemecode_name_`+sip_index+`" maxlength="500" name="sip_schemecode_name[`+index+`][`+sip_index+`]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <div id="sip_category_`+sip_index+`"></div>
                            <div id="sip_category_id_`+sip_index+`" style="display: none">
                                <input type="text" value="" style="width: 100%;" id="sip_category_input_`+sip_index+`" maxlength="500" name="sip_category_input[`+index+`][`+sip_index+`]">
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;" maxlength="500" name="sip_amounts[`+index+`][`+sip_index+`]" id="sip_amounts_`+sip_index+`"  message="sip_amount"></td>
                        <td id="" style="color:red;text-align: center;" onclick="removeSIP('`+sip_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#sip_div_"+index).append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function removeSIPMain(index){
            document.getElementById("lumpsum_main_"+index).remove();
        }

        function AddSIPAssertClass(){
            sip_index = sip_index+1;
            main_sip_index = main_sip_index+1;

            var iHtml = `<div id="sip_main_0" class="mt-5">
                            <div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Asset Class</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="sip_asset_class_`+main_sip_index+`" name="sip_asset_class[`+main_sip_index+`]"  onchange ="changeAssetClass(2,`+main_sip_index+`); changeSIPCalculation(`+main_sip_index+`);">    
                                                    @foreach($assets_list as $key => $value)
                                                        <option value="{{$value}}">{{$value}}</option>
                                                    @endforeach
                                                </select>
                                                <em id="sip_asset_class_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">SIP Amount</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_sip_amount[`+main_sip_index+`]" class="form-control" id="sip_sip_amount_`+main_sip_index+`" value="" onkeyup ="changeSIPCalculation(`+main_sip_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="sip_sip_amount_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Frequency</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="sip_frequency_`+main_sip_index+`" name="sip_frequency[`+main_sip_index+`]" onchange ="changeSIPCalculation(`+main_sip_index+`);">    
                                                    <option value="52">Weekly</option>
                                                    <option value="26">Fortnightly</option>
                                                    <option value="12" selected="selected">Monthly</option>
                                                    <option value="4">Quarterly</option>
                                                    <option value="2">Half-Yearly</option>
                                                    <option value="1">Yearly</option>
                                                </select>
                                                <em id="sip_frequency_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_assumed_rate_of_return[`+main_sip_index+`]" class="form-control" id="sip_assumed_rate_of_return_`+main_sip_index+`" value="" onkeyup ="changeSIPCalculation(`+main_sip_index+`);" onkeypress="return isNumeric(event)">
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                                <em id="sip_assumed_rate_of_return_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">SIP Period</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_sip_period[`+main_sip_index+`]" class="form-control" id="sip_sip_period_`+main_sip_index+`" value="" onkeyup ="changeSIPCalculation(`+main_sip_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                                <em id="sip_sip_period_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Investment Period</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_investment_period[`+main_sip_index+`]" class="form-control" id="sip_investment_period_`+main_sip_index+`" value="" onkeyup ="changeSIPCalculation(`+main_sip_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                                <em id="sip_investment_period_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Total Investment</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_total_investment[`+main_sip_index+`]" id="sip_total_investment_`+main_sip_index+`" class="form-control" value="" readonly="readonly">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="sip_expected_future_value[`+main_sip_index+`]" id="sip_expected_future_value_`+main_sip_index+`" class="form-control" value="" readonly="readonly" message="sip" datatype="`+main_stp_index+`">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="sip_expected_future_value_`+main_sip_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive pt-2">
                                <div class="roundTable">
                                    <table id="nonMfTable" class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width:15%;">
                                                Investor
                                            </th>
                                            <th style="width:33%;">Scheme</th>
                                            <th>
                                                Category
                                            </th>
                                            <th style="width:15%;">SIP Amount</th>
                                            <th style="width: 5%;">Action</th>
                                          </tr>
                                        </thead>
                                        <tbody id="sip_div_`+main_sip_index+`" class="spaceless">
                                          <tr id="sip_tr_`+sip_index+`">
                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="100" name="sip_investor[`+main_sip_index+`][`+sip_index+`]" id="sip_investor_`+sip_index+`"></td>
                                            <td style="width:33%;">
                                                <div class="">
                                                    <div class="form-group mb-0" id="sip_schemecode_id_div_`+sip_index+`">
                                                        <select class="form-control ui-autocomplete-input  schemecode_id" id="sip_schemecode_id_`+sip_index+`" name="sip_schemecode_id[`+main_sip_index+`][`+sip_index+`]" onchange="changeSIPScheme(`+main_sip_index+`,`+sip_index+`);" message="schemecode_id">
                                                            <option value="">Select</option>
                                                            <option value="0">Custom</option>
                                                            <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0" id="sip_schemecode_id_d_`+main_sip_index+`" style="display: none;">
                                                        <input type="text" value="" style="width: 100%;" id="sip_schemecode_name_`+main_sip_index+`" maxlength="500" name="sip_schemecode_name[`+main_sip_index+`][`+sip_index+`]">
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div id="sip_category_`+sip_index+`"></div>
                                                <div id="sip_category_id_`+sip_index+`" style="display: none">
                                                    <input type="text" value="" style="width: 100%;" id="sip_category_input_`+sip_index+`" maxlength="500" name="sip_category_input[`+main_sip_index+`][`+sip_index+`]">
                                                </div>
                                            </td>
                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="sip_amounts[`+main_sip_index+`][`+sip_index+`]" id="sip_amounts_`+sip_index+`" message="sip_amount" onkeypress="return isOnlyNumber(event)"></td>
                                            <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeSIP('`+sip_index+`');">
                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                            </td>
                                          </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right text-danger">
                                <small id="sip_table_error_`+main_sip_index+`"></small>
                            </div>
                            <div class="text-right pt-2 mb-4">
                                <button type="button" class="btn banner-btn" onclick="AddMoreSIP(`+main_sip_index+`);">Add More</button>
                                <button type="button" class="btn banner-btn removeGoalCalculatorBtn ml-3" onclick="removeSIPAssertClass(`+main_sip_index+`);">Remove Asset Class</button>
                            </div>
                        </div>`;

            $("#sip_main").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSTPCalculation(index){
            var stp_initial_investment_amount = document.getElementById('stp_initial_investment_amount_'+index).value;
            var stp_assumed_return = document.getElementById('stp_from_scheme_'+index).value;
            var stp_equity_assumed_return = document.getElementById('stp_to_scheme_'+index).value;
            var stp_mode = document.getElementById('stp_transfer_mode_'+index).value;
            var stp_frequency = document.getElementById('stp_frequency_'+index).value;
            var stp_no_of_frequency = document.getElementById('stp_no_of_frequency_'+index).value;
            var stp_investment_period = document.getElementById('stp_investment_period_'+index).value;
            var stp_amount = document.getElementById('stp_stp_amount_'+index).value;
            $('#stp_stp_amount_'+index).prop("readonly", false);

            stp_initial_igetElementByIdnvestment_amount = parseFloat(stp_initial_investment_amount);
            stp_assumed_return = parseFloat(stp_assumed_return);
            stp_equity_assumed_return = parseFloat(stp_equity_assumed_return);
            stp_frequency = parseFloat(stp_frequency);
            stp_no_of_frequency = parseFloat(stp_no_of_frequency);
            stp_investment_period = parseFloat(stp_investment_period);
            stp_amount = parseFloat(stp_amount);

            stp_initial_investment_amount = (stp_initial_investment_amount)?stp_initial_investment_amount:0;
            stp_assumed_return = (stp_assumed_return)?stp_assumed_return:0;
            stp_equity_assumed_return = (stp_equity_assumed_return)?stp_equity_assumed_return:0;
            stp_frequency = (stp_frequency)?stp_frequency:0;
            stp_no_of_frequency = (stp_no_of_frequency)?stp_no_of_frequency:0;
            stp_investment_period = (stp_investment_period)?stp_investment_period:0;
            stp_amount = (stp_amount)?stp_amount:0;

            // if(key_name == "stp_frequency" || key_name == "stp_no_of_frequency"){
            //     document.getElementById("stp_investment_period_error_"+index).innerHTML = "Max Investment Period : "+parseFloat(stp_no_of_frequency/stp_frequency).toFixed(2);
            // }

            document.getElementById("stp_stp_amount_"+index+"_error").innerHTML = "";

            if(stp_initial_investment_amount && stp_assumed_return && stp_equity_assumed_return){
                var stp_assumed_returns = stp_assumed_return * stp_frequency;
                var debt_return = (Math.pow((1+stp_assumed_return/100) , (1/stp_frequency)) - 1);
                var equity_return = (Math.pow((1+stp_equity_assumed_return/100) , (1/stp_frequency)) - 1);
                var investment_period = stp_investment_period * stp_frequency;
                var balance_period = investment_period - stp_no_of_frequency;
                console.log(stp_mode);
                if(stp_mode == "Appreciation"){
                    stp_amount = stp_initial_investment_amount * debt_return;
                    console.log("stp_amount:"+stp_amount);
                    var debt_value = stp_initial_investment_amount;
                    console.log("debt_value:"+debt_value);
                    var debt_value_investment = debt_value*Math.pow((1+debt_return),balance_period);
                    console.log("debt_value_investment:"+debt_value_investment);
                    var equity_value = stp_amount/equity_return*(Math.pow((1+equity_return),stp_no_of_frequency)-1);
                    console.log("equity_value:"+equity_value);
                    var equity_value_investment = equity_value*Math.pow((1+equity_return),balance_period);
                    console.log("equity_value_investment:"+equity_value_investment);
                    var total_investment = debt_value_investment + equity_value_investment;
                    console.log("total_investment:"+total_investment);
                    document.getElementById('stp_expected_future_value_'+index).value = parseFloat(total_investment).toFixed(0);

                    var max_stp_amount = (debt_return*stp_initial_investment_amount)/(1-Math.pow((1+debt_return),(-stp_no_of_frequency)));
                    document.getElementById('stp_stp_amount_'+index).value = parseFloat(max_stp_amount).toFixed(0);
                    document.getElementById('stp_amount_error_'+index).innerHTML = "";
                    $('#stp_stp_amount_'+index).prop("readonly", true);
                }else{
                    var pv_of_transfer = stp_amount*(1-Math.pow((1+debt_return),(-stp_no_of_frequency)))/debt_return;
                    console.log("pv_of_transfer:"+pv_of_transfer);
                    var debt_value = (stp_initial_investment_amount-pv_of_transfer)*Math.pow((1+debt_return),stp_no_of_frequency);
                    console.log("debt_value:"+debt_value);
                    var debt_value_investment = debt_value*Math.pow((1+debt_return),balance_period);
                    console.log("debt_value_investment:"+debt_value_investment);
                    var equity_value = stp_amount/equity_return*(Math.pow((1+equity_return),stp_no_of_frequency)-1);
                    console.log("equity_value:"+equity_value);
                    var equity_value_investment = equity_value*Math.pow((1+equity_return),balance_period);
                    console.log("equity_value_investment:"+equity_value_investment);
                    var total_investment = debt_value_investment + equity_value_investment;
                    console.log("total_investment:"+total_investment);
                    document.getElementById('stp_expected_future_value_'+index).value = parseFloat(total_investment).toFixed(0);
                    var max_stp_amount = (debt_return*stp_initial_investment_amount)/(1-Math.pow((1+debt_return),(-stp_no_of_frequency)));
                    
                    console.log(max_stp_amount)
                    document.getElementById('stp_amount_error_'+index).innerHTML = "Max amount Rs. <span id='stp_amount_span_"+index+"'>"+parseFloat(max_stp_amount).toFixed(0)+"</span>";

                    console.log(parseFloat(max_stp_amount).toFixed(0));
                    console.log(stp_amount);
                    if(stp_amount){
                        if(parseFloat(max_stp_amount).toFixed(0) < stp_amount){
                            document.getElementById("stp_stp_amount_"+index+"_error").innerHTML = "Input is incorrect";
                        }
                    }

                }
            }else{
                document.getElementById('stp_expected_future_value_'+index).value = "";
            }

            document.getElementById("stp_from_scheme_"+index+"_error").innerHTML = "";
            document.getElementById("stp_to_scheme_"+index+"_error").innerHTML = "";

            if(stp_assumed_return){
                if(stp_assumed_return > 10){
                    document.getElementById("stp_from_scheme_"+index+"_error").innerHTML = "Please enter a value less than 10.";
                }
            }

            if(stp_equity_assumed_return){
                if(stp_equity_assumed_return > 15){
                    document.getElementById("stp_to_scheme_"+index+"_error").innerHTML = "Please enter a value less than 15.";
                }
            }
        }

        function changeSTPScheme(main_index,index){
            var stp_schemecode_id = document.getElementById('stp_schemecode_id_'+index).value;

            if(stp_schemecode_id == 0){
                selected_scheme_id = "stp_schemecode_id";
                selected_index = index;
                document.getElementById("modal_category_id").style.display = "none";
                document.getElementById("modal_title").value = "";
                document.getElementById("modal_category").value = "";
                $("#customModal").modal("show");
            }else{
                
            }
        }

        function changeSTPSchemeEquity(main_index,index){
            var stp_equity_scheme = document.getElementById('stp_equity_scheme_'+index).value;

            if(stp_equity_scheme == 0){
                selected_scheme_id = "stp_equity_scheme";
                selected_index = index;
                document.getElementById("modal_category_id").style.display = "none";
                document.getElementById("modal_title").value = "";
                document.getElementById("modal_category").value = "";
                $("#customModal").modal("show");
            }else{
                
            }
        }

        function AddSTPAssertClass(){
            main_stp_index = main_stp_index + 1;
            stp_index = stp_index+1;

            var iHtml = `<div id="stp_main_`+main_stp_index+`" class="mt-5">
                            <div>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <div class="form-group row">
                                            <label class="col-sm-5 col-form-label">Initial Investment Amount</label>
                                            <div class="col-sm-7">
                                                <input type="text" name="stp_initial_investment_amount[`+main_stp_index+`]" class="form-control" id="stp_initial_investment_amount_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="stp_initial_investment_amount_`+main_stp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-12">
                                        <h6 class="text-muted titleBlueUnderline"><strong>Assumed Rate of Return</strong></h6>
                                    </div>
                                </div>
                                
                                <div style="border: 1px solid rgb(216, 214, 214); padding: 15px 15px 0px 15px;margin-bottom: 1rem;">
                                    <div class="row">
                                            <div class="col-md-6" style="">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">From Scheme</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="stp_from_scheme[`+main_stp_index+`]" class="form-control" id="stp_from_scheme_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isNumeric(event)">
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="stp_from_scheme_`+main_stp_index+`_error" class="error"></em>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6" style="">
                                                <div class="form-group row">
                                                    <label class="col-sm-6 col-form-label">To Scheme</label>
                                                    <div class="col-sm-6">
                                                        <input type="text" name="stp_to_scheme[`+main_stp_index+`]" class="form-control" id="stp_to_scheme_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isNumeric(event)">
                                                        <div class="cal-icon">
                                                            %
                                                        </div>
                                                        <em id="stp_to_scheme_`+main_stp_index+`_error" class="error"></em>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Transfer Mode</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="stp_transfer_mode_`+main_stp_index+`" name="stp_transfer_mode[`+main_stp_index+`]" onchange ="changeSTPCalculation(`+main_stp_index+`);">         
                                                    <option value="Fixed Amount">Fixed Amount</option>
                                                    <option value="Appreciation">Appreciation</option>
                                                </select>
                                                <em id="stp_transfer_mode_`+main_stp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Frequency</label>
                                            <div class="col-sm-6">
                                                <select class="form-control" id="stp_frequency_`+main_stp_index+`" name="stp_frequency[`+main_stp_index+`]" onchange ="changeSTPCalculation(`+main_stp_index+`);">         
                                                    <option value="52">Weekly</option>
                                                    <option value="26">Fortnightly</option>
                                                    <option value="12" selected="selected">Monthly</option>
                                                    <option value="4">Quarterly</option>
                                                    <option value="2">Half-Yearly</option>
                                                    <option value="1">Yearly</option>
                                                </select>
                                                <em id="stp_frequency_`+main_stp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">No. of Frequency</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="stp_no_of_frequency[`+main_stp_index+`]" class="form-control" id="stp_no_of_frequency_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                                <em id="stp_no_of_frequency_`+main_stp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Investment Period</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="stp_investment_period[`+main_stp_index+`]" class="form-control" id="stp_investment_period_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                                <em id="stp_investment_period_`+main_stp_index+`_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">STP Amount</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="stp_stp_amount[`+main_stp_index+`]" class="form-control" id="stp_stp_amount_`+main_stp_index+`" value="" onkeyup ="changeSTPCalculation(`+main_stp_index+`);" onkeypress="return isOnlyNumber(event)">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="stp_stp_amount_`+main_stp_index+`_error" class="error" style="height: 10px;"></em>
                                                <div id="stp_amount_error_`+main_stp_index+`" style="margin-top: 5px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                            <div class="col-sm-6">
                                                <input type="text" name="stp_expected_future_value[`+main_stp_index+`]" id="stp_expected_future_value_`+main_stp_index+`" class="form-control" value="" readonly="readonly" message="stp" datatype="`+main_stp_index+`">
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                <em id="stp_expected_future_value_0_error" class="error"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="table-responsive pt-2">
                                <div class="roundTable">
                                    <table id="nonMfTable" class="table table-bordered">
                                        <thead>
                                          <tr>
                                            <th style="width: 18%; ">
                                                Investor
                                            </th>
                                            <th style="width: 30%; ">From Scheme</th>
                                            <th style="width: 17%; ">Investment </th>
                                            <th style="width: 30%; ">To Scheme </th>
                                            <th style="width: 5%;">Action</th>
                                          </tr>
                                        </thead>
                                        <tbody id="stp_div_`+main_stp_index+`" class="spaceless">
                                          <tr id="stp_tr_`+stp_index+`">
                                            <td style="width: 18%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investor[`+main_stp_index+`][`+stp_index+`]" id="stp_investor_`+stp_index+`"></td>
                                            <td style="width: 30%;">
                                                <div class="">
                                                    <div class="form-group mb-0" id="stp_schemecode_id_div_`+stp_index+`">
                                                        <select class="form-control schemecode_id" id="stp_schemecode_id_`+stp_index+`" name="stp_schemecode_id[`+main_stp_index+`][`+stp_index+`]" message="schemecode_id" onchange="changeSTPScheme(`+main_stp_index+`,`+stp_index+`)">
                                                            <option value="">Select</option>
                                                            <option value="0">Custom</option>
                                                            <?php foreach ($scheme_list as $key => $value) { ?>
                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0" id="stp_schemecode_id_d_`+stp_index+`" style="display: none;">
                                                        <input type="text" value="" style="width: 100%;" id="stp_schemecode_name_`+stp_index+`" maxlength="500" name="stp_schemecode_name[`+main_stp_index+`][`+stp_index+`]">
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width: 17%;">
                                                <input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investment[`+main_stp_index+`][`+stp_index+`]" id="stp_investment_`+stp_index+`" message="stp_investment" onkeypress="return isOnlyNumber(event)">
                                            </td>
                                            <td style="width: 30%;">
                                                <div class="">
                                                    <div class="form-group mb-0" id="stp_equity_scheme_div_`+stp_index+`">
                                                        <select class="form-control schemecode_id" id="stp_equity_scheme_`+stp_index+`" name="stp_equity_scheme[`+main_stp_index+`][`+stp_index+`]" message="schemecode_id" onchange="changeSTPSchemeEquity(`+main_stp_index+`,`+stp_index+`)">
                                                            <option value="">Select</option>
                                                            <option value="0">Custom</option>
                                                            <?php foreach ($scheme_list as $key => $value) { ?>
                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                            <?php }?>
                                                        </select>
                                                    </div>
                                                    <div class="form-group mb-0" id="stp_equity_scheme_d_`+stp_index+`" style="display: none;">
                                                        <input type="text" value="" style="width: 100%;" id="stp_equity_scheme_name_`+stp_index+`" maxlength="500" name="stp_equity_scheme_name[`+main_stp_index+`][`+stp_index+`]">
                                                    </div>
                                                </div>
                                            </td>
                                            <td id="" style="color:red;text-align: center;" onclick="removeSTP('`+stp_index+`');">
                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                            </td>
                                          </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="text-right text-danger">
                                <small id="stp_table_error_`+main_stp_index+`"></small>
                            </div>
                            <div class="text-right pt-2 mb-4">
                                <button type="button" class="btn banner-btn" onclick="AddMoreSTP(`+main_stp_index+`);">Add More</button>
                                <button type="button" class="btn banner-btn removeGoalCalculatorBtn ml-3" onclick="removeSTPMain(`+main_stp_index+`);"> Remove SWP PLAN</button>
                            </div>
                        </div>`;

            $("#stp_main").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function removeSTP(index){
            document.getElementById("stp_tr_"+index).remove();
        }

        function removeSTPMain(index){
            document.getElementById("stp_main_"+index).remove();
        }

        function AddMoreSTP(index){
            stp_index = stp_index+1;
            iHtml = `<tr id="stp_tr_`+stp_index+`">
                        <td style="width: auto;"><input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investor[`+index+`][`+stp_index+`]" id="stp_investor_`+stp_index+`"></td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="stp_schemecode_id_div_`+stp_index+`">
                                    <select class="form-control schemecode_id" id="stp_schemecode_id_`+stp_index+`" name="stp_schemecode_id[`+index+`][`+stp_index+`]" message="schemecode_id" onchange="changeSTPScheme(`+main_stp_index+`,`+stp_index+`)">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="stp_schemecode_id_d_`+stp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="stp_schemecode_name_`+stp_index+`" maxlength="500" name="stp_schemecode_name[`+index+`][`+stp_index+`]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investment[`+index+`][`+stp_index+`]" id="stp_investment_`+stp_index+`" message="stp_investment">
                        </td>
                        <td>
                            <div class="">
                                <div class="form-group mb-0" id="stp_equity_scheme_div_`+stp_index+`">
                                    <select class="form-control schemecode_id" id="stp_equity_scheme_`+stp_index+`" name="stp_equity_scheme[`+index+`][`+stp_index+`]" message="schemecode_id" onchange="changeSTPSchemeEquity(`+main_stp_index+`,`+stp_index+`)">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="stp_equity_scheme_d_`+stp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;" id="stp_equity_scheme_name_`+stp_index+`" maxlength="500" name="stp_equity_scheme_name[`+index+`][`+stp_index+`]">
                                </div>
                            </div>
                        </td>
                        <td id="" style="color:red;text-align: center;" onclick="removeSTP(`+stp_index+`);">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#stp_div_"+index).append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function onsubmitFunction(){
            var success_flag = true;
            var array_data;
            if($("#lumpsum_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_lumpsum").querySelectorAll('input[message=lumpsum]');
                global_lumpsum.forEach(function(val){
                    var lumsum_ind = $(val).attr('datatype');
                    console.log(lumsum_ind);
                    if(document.getElementById("lumpsum_actual_end_value_"+lumsum_ind).value){
                        document.getElementById("lumpsum_actual_end_value_"+lumsum_ind+"_error").innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("lumpsum_actual_end_value_"+lumsum_ind+"_error").innerHTML = "Required";
                    }
                    array_data = document.getElementById("lumpsum_div_"+lumsum_ind).querySelectorAll('input[message=lumpsum_amount]');

                    console.log(array_data);

                    var total_lumpsum_amount = 0;
                    array_data.forEach(function(val){
                        var l_value = ($(val).val())?parseFloat($(val).val()):0;
                        total_lumpsum_amount = total_lumpsum_amount + l_value;
                    });

                    var i_investment_amount = document.getElementById("lumpsum_investment_amount_"+lumsum_ind).value;
                    i_investment_amount = (i_investment_amount)?parseFloat(i_investment_amount):0;
                    if(i_investment_amount == total_lumpsum_amount){
                        document.getElementById("lumpsum_table_error_"+lumsum_ind).innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("lumpsum_table_error_"+lumsum_ind).innerHTML = "Investment Amount not matched";
                    }
                    
                    var lumpsum_assumed_rate_of_return = parseFloat(document.getElementById("lumpsum_assumed_rate_of_return_"+lumsum_ind).value);
                    var lumpsum_asset_class = document.getElementById("lumpsum_asset_class_"+lumsum_ind).value;
                    if(lumpsum_assumed_rate_of_return){
                        if(lumpsum_asset_class == "Equity"){
                            if(lumpsum_assumed_rate_of_return >15){
                                success_flag = false;
                            }
                        }else if(lumpsum_asset_class == "Hybrid"){
                            if(lumpsum_assumed_rate_of_return >12){
                                success_flag = false;
                            }
                        }else if(lumpsum_asset_class == "Debt"){
                            if(lumpsum_assumed_rate_of_return >8){
                                success_flag = false;
                            }
                        }else if(lumpsum_asset_class == "Other"){
                            if(lumpsum_assumed_rate_of_return >15){
                                success_flag = false;
                            }
                        }
                    }

                    console.log(total_lumpsum_amount);
                });
            }
            if($("#sip_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_sip").querySelectorAll('input[message=sip]');
                global_lumpsum.forEach(function(val){
                    var lumsum_ind = $(val).attr('datatype');
                    console.log(lumsum_ind);
                    if(document.getElementById("sip_expected_future_value_"+lumsum_ind).value){
                        document.getElementById("sip_expected_future_value_"+lumsum_ind+"_error").innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("sip_expected_future_value_"+lumsum_ind+"_error").innerHTML = "Required";
                    }
                    array_data = document.getElementById("sip_div_"+lumsum_ind).querySelectorAll('input[message=sip_amount]');

                    console.log(array_data);

                    var total_lumpsum_amount = 0;
                    array_data.forEach(function(val){
                        var l_value = ($(val).val())?parseFloat($(val).val()):0;
                        total_lumpsum_amount = total_lumpsum_amount + l_value;
                    });

                    var i_investment_amount = document.getElementById("sip_sip_amount_"+lumsum_ind).value;
                    i_investment_amount = (i_investment_amount)?parseFloat(i_investment_amount):0;
                    if(i_investment_amount == total_lumpsum_amount){
                        document.getElementById("sip_table_error_"+lumsum_ind).innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("sip_table_error_"+lumsum_ind).innerHTML = "SIP Amount not matched";
                    }

                    var sip_assumed_rate_of_return = parseFloat(document.getElementById("sip_assumed_rate_of_return_"+lumsum_ind).value);
                    var sip_asset_class = document.getElementById("sip_asset_class_"+lumsum_ind).value;
                    if(sip_assumed_rate_of_return){
                        if(sip_asset_class == "Equity"){
                            if(sip_assumed_rate_of_return >15){
                                success_flag = false;
                            }
                        }else if(sip_asset_class == "Hybrid"){
                            if(sip_assumed_rate_of_return >12){
                                success_flag = false;
                            }
                        }else if(sip_asset_class == "Debt"){
                            if(sip_assumed_rate_of_return >8){
                                success_flag = false;
                            }
                        }else if(sip_asset_class == "Other"){
                            if(sip_assumed_rate_of_return >15){
                                success_flag = false;
                            }
                        }
                    }

                    console.log(total_lumpsum_amount);
                });
            }
            if($("#stp_checkbox").is(':checked')){
                var global_stp = document.getElementById("global_stp").querySelectorAll('input[message=stp]');
                global_stp.forEach(function(val){
                    var lumsum_ind = $(val).attr('datatype');
                    console.log(lumsum_ind);
                    if(document.getElementById("stp_expected_future_value_"+lumsum_ind).value){
                        document.getElementById("stp_expected_future_value_"+lumsum_ind+"_error").innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("stp_expected_future_value_"+lumsum_ind+"_error").innerHTML = "Required";
                    }
                    array_data = document.getElementById("stp_div_"+lumsum_ind).querySelectorAll('input[message=stp_investment]');

                    console.log(array_data);

                    var total_lumpsum_amount = 0;
                    array_data.forEach(function(val){
                        var l_value = ($(val).val())?parseFloat($(val).val()):0;
                        total_lumpsum_amount = total_lumpsum_amount + l_value;
                    });

                    var i_investment_amount = document.getElementById("stp_initial_investment_amount_"+lumsum_ind).value;
                    i_investment_amount = (i_investment_amount)?parseFloat(i_investment_amount):0;
                    if(i_investment_amount == total_lumpsum_amount){
                        document.getElementById("stp_table_error_"+lumsum_ind).innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("stp_table_error_"+lumsum_ind).innerHTML = "Investment Amount not matched";
                    }

                    var stp_mode = document.getElementById('stp_transfer_mode_'+lumsum_ind).value;
                    if(stp_mode == "Fixed Amount"){
                        var stp_amount_span = document.getElementById('stp_amount_span_'+lumsum_ind).innerHTML;
                        var stp_stp_amount = document.getElementById('stp_stp_amount_'+lumsum_ind).value;

                        stp_amount_span = parseFloat(stp_amount_span);
                        stp_stp_amount = parseFloat(stp_stp_amount);

                        if(stp_amount_span < stp_stp_amount){
                            success_flag = false;
                            document.getElementById("stp_stp_amount_"+lumsum_ind+"_error").innerHTML = "Input is incorrect";
                        }

                        console.log(stp_amount_span);
                        console.log(stp_stp_amount);
                        console.log(stp_amount_span+"--Ra--"+stp_stp_amount);
                    }

                    var stp_assumed_return = parseFloat(document.getElementById("stp_from_scheme_"+lumsum_ind).value);
                    var stp_equity_assumed_return = parseFloat(document.getElementById("stp_to_scheme_"+lumsum_ind).value);
                    if(stp_assumed_return){
                        if(stp_assumed_return > 10){
                            success_flag = false;
                        }
                    }

                    if(stp_equity_assumed_return){
                        if(stp_equity_assumed_return > 15){
                            success_flag = false;
                        }
                    }

                    console.log(total_lumpsum_amount);
                });
            }
            if($("#swp_checkbox").is(':checked')){
                var global_swp = document.getElementById("global_swp").querySelectorAll('input[message=swp]');
                global_swp.forEach(function(val){
                    var lumsum_ind = $(val).attr('datatype');
                    console.log(lumsum_ind);
                    if(document.getElementById("swp_actual_end_value_"+lumsum_ind).value){
                        document.getElementById("swp_actual_end_value_"+lumsum_ind+"_error").innerHTML = "";
                    }else{
                        success_flag = false;
                        document.getElementById("swp_actual_end_value_"+lumsum_ind+"_error").innerHTML = "Required";
                    }

                    var swp_return = parseFloat(document.getElementById("swp_assumed_rate_of_return_"+lumsum_ind).value);
                    if(swp_return > 12){
                        success_flag = false;
                    }
                });
            }

            if(success_flag){
                return true;
            }else{
                alert("Verify your inputs again");
                window.scrollTo(0, 0);
                return false;
            }
        }

        function changeAssetClass(type,index){
            if(type == 1){
                var global_lumpsum = document.getElementById("lumpsum_div_"+index).querySelectorAll('select');
                var lumpsum_asset_class = document.getElementById("lumpsum_asset_class_"+index).value;
                var iHtml = ``;
                global_lumpsum.forEach(function(val){
                    iHtml = `<option value="">Select</option>
                            <option value="0">Custom</option>`;
                    scheme_list.forEach(function(value){
                        if(value.asset_type == lumpsum_asset_class){
                            iHtml = iHtml+`<option value="`+value.schemecode+`">`+value.s_name+`</option>`;
                        }
                    });
                    var scheme_idd = $(val).attr('id');

                    document.getElementById(scheme_idd).innerHTML = iHtml;
                    scheme_idd = scheme_idd.split('_');
                    document.getElementById("lumpsum_category_"+scheme_idd[scheme_idd.length-1]).innerHTML = "";
                });
            }else if(type == 2){
                var global_lumpsum = document.getElementById("sip_div_"+index).querySelectorAll('select');
                var lumpsum_asset_class = document.getElementById("sip_asset_class_"+index).value;
                var iHtml = ``;
                global_lumpsum.forEach(function(val){
                    iHtml = `<option value="">Select</option>
                            <option value="0">Custom</option>`;
                    scheme_list.forEach(function(value){
                        if(value.asset_type == lumpsum_asset_class){
                            iHtml = iHtml+`<option value="`+value.schemecode+`">`+value.s_name+`</option>`;
                        }
                    });
                    document.getElementById($(val).attr('id')).innerHTML = iHtml;
                });
            }
        }

        $("#is_comment").click( function(){
            if( $(this).is(':checked') ){
                document.getElementById('comment_view').style.display = "block";
            }else {
                document.getElementById('comment').value = "";
                document.getElementById('comment_view').style.display = "none";
            }
        });

        $("#performance_of_selected_mutual_fund").click( function(){
            if( $(this).is(':checked') ){
                document.getElementById('performance_of_selected_mutual_fund_view').style.display = "block";
                renderSchemeList();
            }else {
                document.getElementById('comment').value = "";
                document.getElementById('performance_of_selected_mutual_fund_view').style.display = "none";
            }
        });

        function renderSchemeList(){
            var scheme_array = [];
            var schemecode_id = "";
            var schemecode_detail = [];
            if($("#lumpsum_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_lumpsum").querySelectorAll('select[message=schemecode_id]');
                global_lumpsum.forEach(function(val){
                    schemecode_id = $(val).val();
                    schemecode_detail = scheme_data_list.find(o => o.Schemecode == schemecode_id);
                    if(schemecode_detail){
                        scheme_array.push(schemecode_detail);
                    }
                });
            }

            if($("#sip_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_sip").querySelectorAll('select[message=schemecode_id]');
                global_lumpsum.forEach(function(val){
                    schemecode_id = $(val).val();
                    schemecode_detail = scheme_data_list.find(o => o.Schemecode == schemecode_id);
                    if(schemecode_detail){
                        scheme_array.push(schemecode_detail);
                    }
                });
            }

            if($("#stp_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_stp").querySelectorAll('select[message=schemecode_id]');
                global_lumpsum.forEach(function(val){
                    schemecode_id = $(val).val();
                    schemecode_detail = scheme_data_list.find(o => o.Schemecode == schemecode_id);
                    if(schemecode_detail){
                        scheme_array.push(schemecode_detail);
                    }
                });
            }

            if($("#swp_checkbox").is(':checked')){
                var global_lumpsum = document.getElementById("global_swp").querySelectorAll('select[message=schemecode_id]');
                global_lumpsum.forEach(function(val){
                    schemecode_id = $(val).val();
                    schemecode_detail = scheme_data_list.find(o => o.Schemecode == schemecode_id);
                    if(schemecode_detail){
                        scheme_array.push(schemecode_detail);
                    }
                });
            }
            
            scheme_array.sort((a, b) => {
              const nameA = a.S_NAME.toUpperCase();
              const nameB = b.S_NAME.toUpperCase();
              if (nameA < nameB) {
                return -1;
              }
              if (nameA > nameB) {
                return 1;
              }

              // names must be equal
              return 0;
            });

            scheme_array.sort((a, b) => {
              const nameA = a.CATEGORY.toUpperCase();
              const nameB = b.CATEGORY.toUpperCase();
              if (nameA < nameB) {
                return -1;
              }
              if (nameA > nameB) {
                return 1;
              }
              return 0;
            });

            scheme_array.sort((a, b) => {
              const nameA = a.ASSET_TYPE.toUpperCase();
              const nameB = b.ASSET_TYPE.toUpperCase();
              if (nameA < nameB) {
                return -1;
              }
              if (nameA > nameB) {
                return 1;
              }
              return 0;
            });
            
            var iHtml = ``;

            scheme_array.forEach(function(val){
                val['6MONTHRET'] = (val['6MONTHRET'])?(parseFloat(val['6MONTHRET']).toFixed(2)):"-";
                val['1YEARRET'] = (val['1YEARRET'])?(parseFloat(val['1YEARRET']).toFixed(2)):"-";
                val['3YEARRET'] = (val['3YEARRET'])?(parseFloat(val['3YEARRET']).toFixed(2)):"-";
                val['5YEARRET'] = (val['5YEARRET'])?(parseFloat(val['5YEARRET']).toFixed(2)):"-";
                val['10YEARRET'] = (val['10YEARRET'])?(parseFloat(val['10YEARRET']).toFixed(2)):"-";
                iHtml = iHtml + `<tr>
                        <td align="left" style="width: 35%;">`+val['S_NAME']+`</td>
                        <td align="left" style="width: 30%;">`+val['CATEGORY']+`</td>
                        <td align="right" style="width: 5%;">`+val['6MONTHRET']+`</td>
                        <td align="right" style="width: 5%;">`+val['1YEARRET']+`</td>
                        <td align="right" style="width: 5%;">`+val['3YEARRET']+`</td>
                        <td align="right" style="width: 5%;">`+val['5YEARRET']+`</td>
                        <td align="right" style="width: 5%;">`+val['10YEARRET']+`</td>
                    </tr>`
            });

            document.getElementById("performance_of_selected_mutual_fund_tbody").innerHTML = iHtml;

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
                    <h3 class="smalllineHeading">All-in-one Investment Proposal</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                            
                        <form action="{{route('frontend.investment_proposal_output')}}" method="post" onsubmit="return onsubmitFunction();">
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                @csrf                               
                                
                                <div class="" id="global_lumpsum">
                                    <div  class="form-group row">
                                        <div class="col-sm-12">
                                            <h6 class="text-muted titleBlueUnderline"><strong>Mutual Fund Schemes</strong></h6>
                                        </div>
                                    </div>
                                    
                                    
                                    <div class="row">
                                        <label class="col-sm-12 col-form-label checkcontainer2">
                                            <input type="checkbox" name="lumpsum_checkbox" id="lumpsum_checkbox" onchange="changeLumpsumCheckbox();" <?php echo ($lumpsum_checkbox)?'checked':'';?>> Lumpsum
                                            <span class="checkmark"></span>
                                        </label>
                                        
                                        <div class="col-md-12">
                                            <div id="lumpsum_main">
                                                @if($lumpsum_checkbox)
                                                    @php $lumpsum_count = 0; @endphp
                                                    @foreach($lumpsum_form_list as $k => $ress)
                                                        <div id="lumpsum_main_{{$k}}">
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">Asset Class</label>
                                                                            <div class="col-sm-6">
                                                                                <select class="form-control" id="lumpsum_asset_class_{{$k}}" name="lumpsum_asset_class[{{$k}}]" onchange ="changeAssetClass(1,'{{$k}}'); changeLumpsumCalculation('{{$k}}');">    
                                                                                    @foreach($assets_list as $key => $value)
                                                                                        <option value="{{$value}}" <?php echo ($value == $ress['asset_class'])?'selected':'';?>>{{$value}}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                                <em id="swp_asset_class_{{$k}}_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">Investment Amount</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="lumpsum_investment_amount[{{$k}}]" class="form-control" id="lumpsum_investment_amount_{{$k}}" value="{{$ress['investment_amount']}}" onkeyup ="changeLumpsumCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                                <div class="cal-icon">
                                                                                    ₹
                                                                                </div>
                                                                                <em id="lumpsum_investment_amount_{{$k}}_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="lumpsum_investment_period[{{$k}}]" class="form-control" id="lumpsum_investment_period_{{$k}}" value="{{$ress['investment_period']}}" onkeyup ="changeLumpsumCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                                <em id="lumpsum_investment_period_{{$k}}_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="lumpsum_assumed_rate_of_return[{{$k}}]" id="lumpsum_assumed_rate_of_return_{{$k}}" class="form-control" value="{{$ress['assumed_rate_of_return']}}" onkeyup ="changeLumpsumCalculation('{{$k}}');" onkeypress="return isNumeric(event)">
                                                                                <div class="cal-icon">
                                                                                    %
                                                                                </div>
                                                                                <em id="lumpsum_assumed_rate_of_return_{{$k}}_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="lumpsum_actual_end_value[{{$k}}]" id="lumpsum_actual_end_value_{{$k}}" class="form-control" value="{{$ress['actual_end_value']}}" readonly="readonly" message="lumpsum" datatype="{{$k}}">
                                                                                <div class="cal-icon">
                                                                                    ₹
                                                                                </div>
                                                                                <em id="lumpsum_actual_end_value_{{$k}}_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="table-responsive pt-2">
                                                                    <div class="roundTable">
                                                                        <table id="nonMfTable" class="table table-bordered">
                                                                            <thead>
                                                                              <tr>
                                                                                <th style="width:15%;">
                                                                                    <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                        <input type="checkbox" <?php echo ($lumpsum_checkbox)?'checked':'';?> name="lumpsum_investor_checkbox" value="1"> Investor
                                                                                        <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th style="width:33%;">Scheme</th>
                                                                                <th style="">
                                                                                    <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                        <input type="checkbox" <?php echo ($lumpsum_checkbox)?'checked':'';?> name="lumpsum_category_checkbox" value="1"> Category
                                                                                        <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                                    </label>
                                                                                </th>
                                                                                <th style="width:15%;">Amount</th>
                                                                                <th style="width: 5%;">Action</th>
                                                                              </tr>
                                                                            </thead>
                                                                            <tbody id="lumpsum_div_{{$k}}" class="spaceless">
                                                                                @foreach($ress['table_list'] as $k1 => $val)
                                                                                
                                                                                    <tr id="lumpsum_tr_{{$lumpsum_count}}">
                                                                                        <td style="width:15%;"><input type="text" style="width: 100%;" maxlength="500" name="lumpsum_investor[{{$k}}][{{$lumpsum_count}}]" id="lumpsum_investor_{{$k}}" value="{{$val['investor']}}"></td>
                                                                                        <td style="width:33%;">
                                                                                            <div class="">
                                                                                                <div class="form-group mb-0" id="lumpsum_schemecode_id_div_{{$lumpsum_count}}">
                                                                                                    <select class="form-control ui-autocomplete-input  schemecode_id" id="lumpsum_schemecode_id_{{$lumpsum_count}}" name="lumpsum_schemecode_id[{{$k}}][{{$lumpsum_count}}]" onchange="changeLumpsumScheme('{{$k}}','{{$lumpsum_count}}');" message="schemecode_id">
                                                                                                        <option value="">Select</option>
                                                                                                        <option value="0">Custom</option>
                                                                                                        @if($ress['asset_class'] == "Equity")
                                                                                                            <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                                                                <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                            <?php }?>
                                                                                                        @elseif($ress['asset_class'] == "Hybrid")
                                                                                                            <?php foreach ($hybrid_scheme_list as $key => $value) { ?>
                                                                                                                <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                            <?php }?>
                                                                                                        @elseif($ress['asset_class'] == "Debt")
                                                                                                            <?php foreach ($debt_scheme_list as $key => $value) { ?>
                                                                                                                <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                            <?php }?>
                                                                                                        @else
                                                                                                            <?php foreach ($other_scheme_list as $key => $value) { ?>
                                                                                                                <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                            <?php }?>
                                                                                                        @endif
                                                                                                    </select>
                                                                                                </div>
                                                                                                <div class="form-group mb-0" id="lumpsum_schemecode_id_d_{{$lumpsum_count}}" style="display: none;">
                                                                                                    <input type="text" value="" style="width: 100%;" id="lumpsum_schemecode_name_{{$lumpsum_count}}" maxlength="500" name="lumpsum_schemecode_name[{{$k}}][{{$lumpsum_count}}]">
                                                                                                </div>
                                                                                            </div>
                                                                                        </td>
                                                                                        <td>
                                                                                            <div id="lumpsum_category_{{$lumpsum_count}}">{{$val['category']}}</div>
                                                                                            <div id="lumpsum_category_id_{{$lumpsum_count}}" style="display: none">
                                                                                                <input type="text" value="" style="width: 100%;" id="lumpsum_category_input_{{$k}}" maxlength="500" name="lumpsum_category_input[{{$k}}][{{$lumpsum_count}}]">
                                                                                            </div>
                                                                                        </td>
                                                                                        <td style="width:15%;"><input type="text" value="{{$val['amount']}}" style="width: 100%;" maxlength="500" name="lumpsum_amount[{{$k}}][{{$lumpsum_count}}]" id="lumpsum_amount_{{$lumpsum_count}}" message="lumpsum_amount" onkeypress="return isOnlyNumber(event)"> </td>
                                                                                        <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeLumpsum('{{$lumpsum_count}}');">
                                                                                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                                        </td>
                                                                                    </tr>
                                                                                
                                                                                    @php $lumpsum_count = $lumpsum_count+1; @endphp
                                                                                @endforeach
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                
                                                            </div>
                                                            <div class="text-right text-danger">
                                                                <small id="lumpsum_table_error_0"></small>
                                                            </div>
                                                            
                                                            <div class="text-right pt-2">
                                                                <button type="button" class="btn banner-btn" onclick="AddMoreLumpsum(0);">Add More</button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div id="lumpsum_main_0">
                                                        <div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Asset Class</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="lumpsum_asset_class_0" name="lumpsum_asset_class[0]" onchange ="changeAssetClass(1,0); changeLumpsumCalculation(0);">    
                                                                                @foreach($assets_list as $key => $value)
                                                                                    <option value="{{$value}}">{{$value}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <em id="swp_asset_class_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Investment Amount</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="lumpsum_investment_amount[0]" class="form-control" id="lumpsum_investment_amount_0" value="" onkeyup ="changeLumpsumCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="lumpsum_investment_amount_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="lumpsum_investment_period[0]" class="form-control" id="lumpsum_investment_period_0" value="" onkeyup ="changeLumpsumCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                            <em id="lumpsum_investment_period_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="lumpsum_assumed_rate_of_return[0]" id="lumpsum_assumed_rate_of_return_0" class="form-control" value="" onkeyup ="changeLumpsumCalculation(0);" onkeypress="return isNumeric(event)">
                                                                            <div class="cal-icon">
                                                                                %
                                                                            </div>
                                                                            <em id="lumpsum_assumed_rate_of_return_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="lumpsum_actual_end_value[0]" id="lumpsum_actual_end_value_0" class="form-control" value="" readonly="readonly" message="lumpsum" datatype="0">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="lumpsum_actual_end_value_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="table-responsive pt-2">
                                                            
                                                                <div class="roundTable">
                                                                    <table id="nonMfTable" class="table table-bordered">
                                                                        <thead>
                                                                          <tr>
                                                                            <th style="width:15%;">
                                                                                <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                    <input type="checkbox" checked="checked" name="lumpsum_investor_checkbox" value="1"> Investor
                                                                                    <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                                </label>
                                                                            </th>
                                                                            <th style="width:33%;">Scheme</th>
                                                                            <th style="">
                                                                                <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                    <input type="checkbox" checked="checked" name="lumpsum_category_checkbox" value="1"> Category
                                                                                    <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                                </label>
                                                                            </th>
                                                                            <th style="width:15%;">Amount</th>
                                                                            <th style="width: 5%;">Action</th>
                                                                          </tr>
                                                                        </thead>
                                                                        <tbody id="lumpsum_div_0" class="spaceless">
                                                                          <tr id="lumpsum_tr_0">
                                                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="lumpsum_investor[0][0]" id="lumpsum_investor_0"></td>
                                                                            <td style="width:33%;">
                                                                                <div class="">
                                                                                    <div class="form-group mb-0" id="lumpsum_schemecode_id_div_0">
                                                                                        <select class="form-control ui-autocomplete-input  schemecode_id" id="lumpsum_schemecode_id_0" name="lumpsum_schemecode_id[0][0]" onchange="changeLumpsumScheme(0,0);" message="schemecode_id">
                                                                                            <option value="">Select</option>
                                                                                            <option value="0">Custom</option>
                                                                                            <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                                                <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                                            <?php }?>
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="form-group mb-0" id="lumpsum_schemecode_id_d_0" style="display: none;">
                                                                                        <input type="text" value="" style="width: 100%;" id="lumpsum_schemecode_name_0" maxlength="500" name="lumpsum_schemecode_name[0][0]">
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <div id="lumpsum_category_0"></div>
                                                                                <div id="lumpsum_category_id_0" style="display: none">
                                                                                    <input type="text" value="" style="width: 100%;" id="lumpsum_category_input_0" maxlength="500" name="lumpsum_category_input[0][0]">
                                                                                </div>
                                                                            </td>
                                                                            <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="lumpsum_amount[0][0]" id="lumpsum_amount_0" message="lumpsum_amount" onkeypress="return isOnlyNumber(event)"> </td>
                                                                            <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeLumpsum(0);">
                                                                                 <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                            </td>
                                                                          </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            
                                                        </div>
                                                        <div class="text-right text-danger">
                                                            <small id="lumpsum_table_error_0"></small>
                                                        </div>
                                                        
                                                        <div class="text-right pt-2">
                                                            <button type="button" class="btn banner-btn" onclick="AddMoreLumpsum(0);">Add More</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="text-center pt-2">
                                                <button type="button" class="btn banner-btn" onclick="AddLumpsumAssertClass();">Add Asset Class</button>
                                            </div>
                                            <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;display: <?php echo ($lumpsum_checkbox)?'none':'';?>;" id="lumpsum_view">
                                                    
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-5 mt-3" id="global_sip">
                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                        <input type="checkbox" name="sip_checkbox" id="sip_checkbox" onchange="changeSIPCheckbox();" <?php echo ($sip_checkbox)?'checked':'';?>> SIP
                                        <span class="checkmark"></span>
                                    </label>
                                        
                                    
                                    <div class="col-md-12">
                                        <div id="sip_main">
                                            @if($sip_checkbox)
                                            @php $sip_count = 0; @endphp
                                                @foreach($sip_form_list as $k => $ress)
                                                    <div id="sip_main_{{$k}}">
                                                        <div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Asset Class</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="sip_asset_class_{{$k}}" name="sip_asset_class[{{$k}}]" onchange ="changeAssetClass(2,'{{$k}}');changeSIPCalculation('{{$k}}');">    @foreach($assets_list as $key => $value)
                                                                                    <option value="{{$value}}" <?php echo ($value == $ress['asset_class'])?'selected':'';?>>{{$value}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <em id="sip_asset_class_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">SIP Amount</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_sip_amount[{{$k}}]" class="form-control" id="sip_sip_amount_{{$k}}" value="{{$ress['sip_amount']}}" onkeyup ="changeSIPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="sip_sip_amount_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Frequency</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="sip_frequency_{{$k}}" name="sip_frequency[{{$k}}]" onchange ="changeSIPCalculation('{{$k}}');">         
                                                                                <option value="52" <?php echo (52 == $ress['frequency_value'])?'selected':'';?>>Weekly</option>
                                                                                <option value="26" <?php echo (26 == $ress['frequency_value'])?'selected':'';?>>Fortnightly</option>
                                                                                <option value="12" <?php echo (12 == $ress['frequency_value'])?'selected':'';?>>Monthly</option>
                                                                                <option value="4" <?php echo (4 == $ress['frequency_value'])?'selected':'';?>>Quarterly</option>
                                                                                <option value="2" <?php echo (2 == $ress['frequency_value'])?'selected':'';?>>Half-Yearly</option>
                                                                                <option value="1" <?php echo (1 == $ress['frequency_value'])?'selected':'';?>>Yearly</option>
                                                                            </select>
                                                                            <em id="sip_frequency_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_assumed_rate_of_return[{{$k}}]" class="form-control" id="sip_assumed_rate_of_return_{{$k}}" value="{{$ress['assumed_rate_of_return']}}" onkeyup ="changeSIPCalculation('{{$k}}');" onkeypress="return isNumeric(event)">
                                                                            <div class="cal-icon">
                                                                                %
                                                                            </div>
                                                                            <em id="sip_assumed_rate_of_return_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">SIP Period</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_sip_period[{{$k}}]" class="form-control" id="sip_sip_period_{{$k}}" value="{{$ress['sip_period']}}" onkeyup ="changeSIPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                Yr
                                                                            </div>
                                                                            <em id="sip_sip_period_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_investment_period[{{$k}}]" class="form-control" id="sip_investment_period_{{$k}}" value="{{$ress['investment_period']}}" onkeyup ="changeSIPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                Yr
                                                                            </div>
                                                                            <em id="sip_investment_period_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Total Investment</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_total_investment[{{$k}}]" id="sip_total_investment_{{$k}}" class="form-control" value="{{$ress['total_investment']}}" readonly="readonly">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="sip_expected_future_value[{{$k}}]" id="sip_expected_future_value_{{$k}}" class="form-control" value="{{$ress['expected_future_value']}}" readonly="readonly" message="sip" datatype="{{$k}}">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="sip_expected_future_value_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="table-responsive pt-2">
                                                            <div class="roundTable">
                                                                <table id="nonMfTable" class="table table-bordered">
                                                                    <thead>
                                                                      <tr>
                                                                        <th style="width:15%;">
                                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                <input type="checkbox" name="sip_investor_checkbox" value="1" <?php echo ($sip_investor_checkbox)?'checked':'';?>> Investor
                                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                            </label>
                                                                        </th>
                                                                        <th style="width:33%;">Scheme</th>
                                                                        <th style="">
                                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                <input type="checkbox" name="sip_category_checkbox" value="1" <?php echo ($sip_category_checkbox)?'checked':'';?>> Category
                                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                            </label>
                                                                        </th>
                                                                        <th style="width:15%;">SIP Amount</th>
                                                                        <th style="width: 5%;">Action</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody id="sip_div_{{$k}}" class="spaceless">
                                                                        @foreach($ress['table_list'] as $k1 => $val)
                                                                        
                                                                            <tr id="sip_tr_{{$sip_count}}">
                                                                                <td style="width:15%;"><input type="text" value="{{$val['investor']}}" style="width: 100%;" maxlength="100" name="sip_investor[{{$k}}][{{$sip_count}}]" id="sip_investor_{{$sip_count}}"></td>
                                                                                <td style="width:33%;">
                                                                                    <div class="">
                                                                                        <div class="form-group mb-0" id="sip_schemecode_id_div_0">
                                                                                            <select class="form-control ui-autocomplete-input  schemecode_id" id="sip_schemecode_id_{{$sip_count}}" name="sip_schemecode_id[{{$k}}][{{$sip_count}}]" onchange="changeSIPScheme('{{$k}}','{{$sip_count}}');" message="schemecode_id" message="schemecode_id">
                                                                                                <option value="">Select</option>
                                                                                                <option value="0">Custom</option>
                                                                                                @if($ress['asset_class'] == "Equity")
                                                                                                    <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                                                        <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                    <?php }?>
                                                                                                @elseif($ress['asset_class'] == "Hybrid")
                                                                                                    <?php foreach ($hybrid_scheme_list as $key => $value) { ?>
                                                                                                        <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                    <?php }?>
                                                                                                @elseif($ress['asset_class'] == "Debt")
                                                                                                    <?php foreach ($debt_scheme_list as $key => $value) { ?>
                                                                                                        <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                    <?php }?>
                                                                                                @else
                                                                                                    <?php foreach ($other_scheme_list as $key => $value) { ?>
                                                                                                        <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                    <?php }?>
                                                                                                @endif
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group mb-0" id="sip_schemecode_id_d_{{$sip_count}}" style="display: none;">
                                                                                            <input type="text" value="" style="width: 100%;" id="sip_schemecode_name_{{$sip_count}}" maxlength="500" name="sip_schemecode_name[{{$k}}][{{$sip_count}}]">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div id="sip_category_{{$sip_count}}">{{$val['category']}}</div>
                                                                                    <div id="sip_category_id_{{$sip_count}}" style="display: none">
                                                                                        <input type="text" value="" style="width: 100%;" id="sip_category_input_{{$sip_count}}" maxlength="500" name="sip_category_input[{{$k}}][{{$sip_count}}]">
                                                                                    </div>
                                                                                </td>
                                                                                <td style="width:15%;"><input type="text" value="{{$val['amount']}}" style="width: 100%;" maxlength="500" name="sip_amounts[{{$k}}][{{$sip_count}}]" id="sip_amounts_{{$sip_count}}" message="sip_amount" onkeypress="return isOnlyNumber(event)"></td>
                                                                                <td id="" style="color:red;text-align: center; width:5%;" onclick="removeSIP('{{$sip_count}}');">
                                                                                     <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                                </td>
                                                                            </tr>
                                                                        
                                                                            @php $sip_count = $sip_count+1; @endphp
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="text-right text-danger">
                                                            <small id="sip_table_error_0"></small>
                                                        </div>
                                                        <div class="text-right pt-2">
                                                            <button type="button" class="btn banner-btn" onclick="AddMoreSIP(0);">Add More</button>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else 
                                                <div id="sip_main_0">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Asset Class</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="form-control" id="sip_asset_class_0" name="sip_asset_class[0]" onchange ="changeAssetClass(2,0);changeSIPCalculation(0);">    @foreach($assets_list as $key => $value)
                                                                                <option value="{{$value}}">{{$value}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                        <em id="sip_asset_class_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">SIP Amount</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_sip_amount[0]" class="form-control" id="sip_sip_amount_0" value="" onkeyup ="changeSIPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="sip_sip_amount_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Frequency</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="form-control" id="sip_frequency_0" name="sip_frequency[0]" onchange ="changeSIPCalculation(0);">         
                                                                            <option value="52">Weekly</option>
                                                                            <option value="26">Fortnightly</option>
                                                                            <option value="12" selected="selected">Monthly</option>
                                                                            <option value="4">Quarterly</option>
                                                                            <option value="2">Half-Yearly</option>
                                                                            <option value="1">Yearly</option>
                                                                        </select>
                                                                        <em id="sip_frequency_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_assumed_rate_of_return[0]" class="form-control" id="sip_assumed_rate_of_return_0" value="" onkeyup ="changeSIPCalculation(0);" onkeypress="return isNumeric(event)">
                                                                        <div class="cal-icon">
                                                                            %
                                                                        </div>
                                                                        <em id="sip_assumed_rate_of_return_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">SIP Period</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_sip_period[0]" class="form-control" id="sip_sip_period_0" value="" onkeyup ="changeSIPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            Yr
                                                                        </div>
                                                                        <em id="sip_sip_period_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_investment_period[0]" class="form-control" id="sip_investment_period_0" value="" onkeyup ="changeSIPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            Yr
                                                                        </div>
                                                                        <em id="sip_investment_period_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Total Investment</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_total_investment[0]" id="sip_total_investment_0" class="form-control" value="" readonly="readonly">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="sip_expected_future_value[0]" id="sip_expected_future_value_0" class="form-control" value="" readonly="readonly" message="sip" datatype="0">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="sip_expected_future_value_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive pt-2">
                                                        <div class="roundTable">
                                                            <table id="nonMfTable" class="table table-bordered">
                                                                <thead>
                                                                  <tr>
                                                                    <th style="width:15%;">
                                                                        <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                            <input type="checkbox" checked="checked" name="sip_investor_checkbox" value="1"> Investor
                                                                            <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                        </label>
                                                                    </th>
                                                                    <th style="width:33%;">Scheme</th>
                                                                    <th style="">
                                                                        <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                            <input type="checkbox" checked="checked" name="sip_category_checkbox" value="1"> Category
                                                                            <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                        </label>
                                                                    </th>
                                                                    <th style="width:15%;">SIP Amount</th>
                                                                    <th style="width: 5%;">Action</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody id="sip_div_0" class="spaceless">
                                                                  <tr id="sip_tr_0">
                                                                    <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="100" name="sip_investor[0][0]" id="sip_investor_0"></td>
                                                                    <td style="width:33%;">
                                                                        <div class="">
                                                                            <div class="form-group mb-0" id="sip_schemecode_id_div_0">
                                                                                <select class="form-control ui-autocomplete-input  schemecode_id" id="sip_schemecode_id_0" name="sip_schemecode_id[0][0]" onchange="changeSIPScheme(0,0);" message="schemecode_id" message="schemecode_id">
                                                                                    <option value="">Select</option>
                                                                                    <option value="0">Custom</option>
                                                                                    <?php foreach ($equity_scheme_list as $key => $value) { ?>
                                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                                    <?php }?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-0" id="sip_schemecode_id_d_0" style="display: none;">
                                                                                <input type="text" value="" style="width: 100%;" id="sip_schemecode_name_0" maxlength="500" name="sip_schemecode_name[0][0]">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div id="sip_category_0"></div>
                                                                        <div id="sip_category_id_0" style="display: none">
                                                                            <input type="text" value="" style="width: 100%;" id="sip_category_input_0" maxlength="500" name="sip_category_input[0][0]">
                                                                        </div>
                                                                    </td>
                                                                    <td style="width:15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="sip_amounts[0][0]" id="sip_amounts_0" message="sip_amount" onkeypress="return isOnlyNumber(event)"></td>
                                                                    <td id="" style="color:red;text-align: center; width:5%;" onclick="removeSIP('0');">
                                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="text-right text-danger">
                                                        <small id="sip_table_error_0"></small>
                                                    </div>
                                                    <div class="text-right pt-2">
                                                        <button type="button" class="btn banner-btn" onclick="AddMoreSIP(0);">Add More</button>
                                                    </div>
                                                </div>

                                            @endif
                                        </div>
                                        <div class="text-center pt-2">
                                            <button type="button" class="btn banner-btn" onclick="AddSIPAssertClass();">Add Asset Class</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;display: <?php echo ($sip_checkbox)?'none':'';?>;" id="sip_view">
                                                
                                        </div>
                                    </div>
                                </div>

                                <div class="row pt-5 mt-3" id="global_stp">
                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                        <input type="checkbox" name="stp_checkbox" id="stp_checkbox" onchange="changeSTPCheckbox();" <?php echo ($stp_checkbox)?'checked':'';?>> STP
                                        <span class="checkmark"></span>
                                    </label>
                                    
                                    <div class="col-md-12">
                                        <div id="stp_main">
                                            @if($stp_checkbox)
                                                @php $stp_count = 0; @endphp
                                                @foreach($stp_form_list  as $k => $ress)
                                                    <div id="stp_main_{{$k}}">
                                                        <div>
                                                            <div class="form-group row">
                                                                <div class="col-md-12">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-5 col-form-label">Initial Investment Amount</label>
                                                                        <div class="col-sm-7">
                                                                            <input type="text" name="stp_initial_investment_amount[{{$k}}]" class="form-control" id="stp_initial_investment_amount_{{$k}}" value="{{$ress['initial_investment_amount']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="stp_initial_investment_amount_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="col-sm-12">
                                                                    <h6 class="text-muted titleBlueUnderline"><strong>Assumed Rate of Return</strong></h6>
                                                                </div>
                                                            </div>
                                                            <div style="border: 1px solid rgb(216, 214, 214); padding: 15px 15px 0px 15px;margin-bottom: 1rem;">
                                                                <div class="row">
                                                                        <div class="col-md-6" style="">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm-6 col-form-label">From Scheme</label>
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" name="stp_from_scheme[{{$k}}]" class="form-control" id="stp_from_scheme_{{$k}}" value="{{$ress['from_scheme']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isNumeric(event)">
                                                                                    <div class="cal-icon">
                                                                                        %
                                                                                    </div>
                                                                                    <em id="stp_from_scheme_{{$k}}_error" class="error"></em>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6" style="">
                                                                            <div class="form-group row">
                                                                                <label class="col-sm-6 col-form-label">To Scheme</label>
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" name="stp_to_scheme[{{$k}}]" class="form-control" id="stp_to_scheme_{{$k}}" value="{{$ress['to_scheme']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isNumeric(event)">
                                                                                    <div class="cal-icon">
                                                                                        %
                                                                                    </div>
                                                                                    <em id="stp_to_scheme_{{$k}}_error" class="error"></em>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Transfer Mode</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="stp_transfer_mode_{{$k}}" name="stp_transfer_mode[{{$k}}]" onchange ="changeSTPCalculation('{{$k}}');">         
                                                                                <option value="Fixed Amount" <?php echo ('Fixed Amount' == $ress['transfer_mode'])?'selected':'';?>>Fixed Amount</option>
                                                                                <option value="Appreciation" <?php echo ('Appreciation' == $ress['transfer_mode'])?'selected':'';?>>Appreciation</option>
                                                                            </select>
                                                                            <em id="stp_transfer_mode_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Frequency</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="stp_frequency_{{$k}}" name="stp_frequency[{{$k}}]" onchange ="changeSTPCalculation('{{$k}}');">         
                                                                                <option value="52" <?php echo (52 == $ress['frequency_value'])?'selected':'';?>>Weekly</option>
                                                                                <option value="26" <?php echo (26 == $ress['frequency_value'])?'selected':'';?>>Fortnightly</option>
                                                                                <option value="12" <?php echo (12 == $ress['frequency_value'])?'selected':'';?>>Monthly</option>
                                                                                <option value="4" <?php echo (4 == $ress['frequency_value'])?'selected':'';?>>Quarterly</option>
                                                                                <option value="2" <?php echo (2 == $ress['frequency_value'])?'selected':'';?>>Half-Yearly</option>
                                                                                <option value="1" <?php echo (1 == $ress['frequency_value'])?'selected':'';?>>Yearly</option>
                                                                            </select>
                                                                            <em id="stp_frequency_0_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">No. of Frequency</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="stp_no_of_frequency[{{$k}}]" class="form-control" id="stp_no_of_frequency_{{$k}}" value="{{$ress['no_of_frequency']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            
                                                                            <em id="stp_no_of_frequency_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="stp_investment_period[{{$k}}]" class="form-control" id="stp_investment_period_{{$k}}" value="{{$ress['investment_period']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                Yr
                                                                            </div>
                                                                            <em id="stp_investment_period_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">STP Amount</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="stp_stp_amount[{{$k}}]" class="form-control" id="stp_stp_amount_{{$k}}" value="{{$ress['stp_amount']}}" onkeyup ="changeSTPCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="stp_stp_amount_{{$k}}_error" class="error" style="height: 10px;"></em>

                                                                            <div id="stp_amount_error_{{$k}}" style="margin-top: 5px;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="stp_expected_future_value[{{$k}}]" id="stp_expected_future_value_{{$k}}" class="form-control" value="{{$ress['expected_future_value']}}" readonly="readonly" message="stp" datatype="{{$k}}">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="stp_expected_future_value_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="table-responsive pt-2">
                                                            <div class="roundTable">
                                                                <table id="nonMfTable" class="table table-bordered">
                                                                    <thead>
                                                                      <tr>
                                                                        <th style="width: 18%; ">
                                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                <input type="checkbox" <?php echo ($stp_investor_checkbox)?'checked':'';?> name="stp_investor_checkbox" value="1"> Investor
                                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                            </label>
                                                                        </th>
                                                                        <th style="width: 30%; ">From Scheme</th>
                                                                        <th style="width: 17%; ">Investment </th>
                                                                        <th style="width: 30%; ">To Scheme </th>
                                                                        <th style="width: 5%;">Action</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody id="stp_div_{{$k}}" class="spaceless">
                                                                        @foreach($ress['table_list'] as $k1 => $val)
                                                                            <tr id="stp_tr_{{$stp_count}}">
                                                                                <td style="width: 18%"><input type="text" value="{{$val['investor']}}" style="width: 100%;" maxlength="500" name="stp_investor[{{$k}}][{{$stp_count}}]" id="stp_investor_{{$stp_count}}"></td>
                                                                                <td style="width: 30%">
                                                                                    <div class="">
                                                                                        <div class="form-group mb-0" id="stp_schemecode_id_div_{{$stp_count}}">
                                                                                            <select class="form-control schemecode_id" id="stp_schemecode_id_{{$stp_count}}" name="stp_schemecode_id[{{$k}}][{{$stp_count}}]" message="schemecode_id" onchange="changeSTPScheme('{{$k}}','{{$stp_count}}')">
                                                                                                <option value="">Select</option>
                                                                                                <option value="0">Custom</option>
                                                                                                <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                                    <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                <?php }?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group mb-0" id="stp_schemecode_id_d_{{$stp_count}}" style="display: none;">
                                                                                            <input type="text" value="" style="width: 100%;" id="stp_schemecode_name_{{$stp_count}}" maxlength="500" name="stp_schemecode_name[{{$k}}][{{$stp_count}}]">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td style="width: 17%; ">
                                                                                    <input type="text" value="{{$val['investment']}}" style="width: 100%;" maxlength="500" name="stp_investment[{{$k}}][{{$stp_count}}]" id="stp_investment_{{$stp_count}}" message="stp_investment" onkeypress="return isOnlyNumber(event)">
                                                                                </td>
                                                                                <td style="width: 30%;">
                                                                                    <div class="">
                                                                                        <div class="form-group mb-0" id="stp_equity_scheme_div_{{$stp_count}}">
                                                                                            <select class="form-control schemecode_id" id="stp_equity_scheme_{{$stp_count}}" name="stp_equity_scheme[{{$k}}][{{$stp_count}}]" message="schemecode_id" onchange="changeSTPSchemeEquity('{{$k}}','{{$stp_count}}')">
                                                                                                <option value="">Select</option>
                                                                                                <option value="0">Custom</option>
                                                                                                <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                                    <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                <?php }?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group mb-0" id="stp_equity_scheme_d_{{$stp_count}}" style="display: none;">
                                                                                            <input type="text" value="" style="width: 100%;" id="stp_equity_scheme_name_{{$stp_count}}" maxlength="500" name="stp_equity_scheme_name[{{$k}}][{{$stp_count}}]">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td id="" style="color:red;text-align: center;" onclick="removeSTP('{{$stp_count}}');">
                                                                                     <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                                </td>
                                                                            </tr>
                                                                            @php $stp_count = $stp_count+1; @endphp
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="text-right text-danger">
                                                            <small id="stp_table_error_0"></small>
                                                        </div>
                                                        <div class="text-right pt-2">
                                                            <button type="button" class="btn banner-btn" onclick="AddMoreSTP(0);">Add More</button>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <div id="stp_main_0">
                                                    <div>
                                                        <div class="form-group row">
                                                            <div class="col-md-12">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-5 col-form-label">Initial Investment Amount</label>
                                                                    <div class="col-sm-7">
                                                                        <input type="text" name="stp_initial_investment_amount[0]" class="form-control" id="stp_initial_investment_amount_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="stp_initial_investment_amount_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="col-sm-12">
                                                                <h6 class="text-muted titleBlueUnderline"><strong>Assumed Rate of Return</strong></h6>
                                                            </div>
                                                        </div>
                                                        <div style="border: 1px solid rgb(216, 214, 214); padding: 15px 15px 0px 15px;margin-bottom: 1rem;">
                                                            <div class="row">
                                                                    <div class="col-md-6" style="">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">From Scheme</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="stp_from_scheme[0]" class="form-control" id="stp_from_scheme_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isNumeric(event)">
                                                                                <div class="cal-icon">
                                                                                    %
                                                                                </div>
                                                                                <em id="stp_from_scheme_0_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6" style="">
                                                                        <div class="form-group row">
                                                                            <label class="col-sm-6 col-form-label">To Scheme</label>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="stp_to_scheme[0]" class="form-control" id="stp_to_scheme_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isNumeric(event)">
                                                                                <div class="cal-icon">
                                                                                    %
                                                                                </div>
                                                                                <em id="stp_to_scheme_0_error" class="error"></em>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Transfer Mode</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="form-control" id="stp_transfer_mode_0" name="stp_transfer_mode[0]" onchange ="changeSTPCalculation(0);">         
                                                                            <option value="Fixed Amount">Fixed Amount</option>
                                                                            <option value="Appreciation">Appreciation</option>
                                                                        </select>
                                                                        <em id="stp_transfer_mode_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Frequency</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="form-control" id="stp_frequency_0" name="stp_frequency[0]" onchange ="changeSTPCalculation(0);">         
                                                                            <option value="52">Weekly</option>
                                                                            <option value="26">Fortnightly</option>
                                                                            <option value="12" selected="selected">Monthly</option>
                                                                            <option value="4">Quarterly</option>
                                                                            <option value="2">Half-Yearly</option>
                                                                            <option value="1">Yearly</option>
                                                                        </select>
                                                                        <em id="stp_frequency_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">No. of Frequency</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="stp_no_of_frequency[0]" class="form-control" id="stp_no_of_frequency_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        
                                                                        <em id="stp_no_of_frequency_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Investment Period</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="stp_investment_period[0]" class="form-control" id="stp_investment_period_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            Yr
                                                                        </div>
                                                                        <em id="stp_investment_period_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">STP Amount</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="stp_stp_amount[0]" class="form-control" id="stp_stp_amount_0" value="" onkeyup ="changeSTPCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="stp_stp_amount_0_error" class="error" style="height: 10px;"></em>

                                                                        <div id="stp_amount_error_0" style="margin-top: 5px;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Expected Future Value</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="stp_expected_future_value[0]" id="stp_expected_future_value_0" class="form-control" value="" readonly="readonly" message="stp" datatype="0">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="stp_expected_future_value_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive pt-2">
                                                        <div class="roundTable">
                                                            <table id="nonMfTable" class="table table-bordered">
                                                                <thead>
                                                                  <tr>
                                                                    <th style="width: 18%; ">
                                                                        <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                            <input type="checkbox" checked="checked" name="stp_investor_checkbox" value="1"> Investor
                                                                            <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                        </label>
                                                                    </th>
                                                                    <th style="width: 30%; ">From Scheme</th>
                                                                    <th style="width: 17%; ">Investment </th>
                                                                    <th style="width: 30%; ">To Scheme </th>
                                                                    <th style="width: 5%;">Action</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody id="stp_div_0" class="spaceless">
                                                                  <tr id="stp_tr_0">
                                                                    <td style="width: 18%"><input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investor[0][0]" id="stp_investor_0"></td>
                                                                    <td style="width: 30%">
                                                                        <div class="">
                                                                            <div class="form-group mb-0" id="stp_schemecode_id_div_0">
                                                                                <select class="form-control schemecode_id" id="stp_schemecode_id_0" name="stp_schemecode_id[0][0]" message="schemecode_id" onchange="changeSTPScheme(0,0)">
                                                                                    <option value="">Select</option>
                                                                                    <option value="0">Custom</option>
                                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                                    <?php }?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-0" id="stp_schemecode_id_d_0" style="display: none;">
                                                                                <input type="text" value="" style="width: 100%;" id="stp_schemecode_name_0" maxlength="500" name="stp_schemecode_name[0][0]">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 17%; ">
                                                                        <input type="text" value="" style="width: 100%;" maxlength="500" name="stp_investment[0][0]" id="stp_investment_0" message="stp_investment" onkeypress="return isOnlyNumber(event)">
                                                                    </td>
                                                                    <td style="width: 30%;">
                                                                        <div class="">
                                                                            <div class="form-group mb-0" id="stp_equity_scheme_div_0">
                                                                                <select class="form-control schemecode_id" id="stp_equity_scheme_0" name="stp_equity_scheme[0][0]" message="schemecode_id" onchange="changeSTPSchemeEquity(0,0)">
                                                                                    <option value="">Select</option>
                                                                                    <option value="0">Custom</option>
                                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                                    <?php }?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-0" id="stp_equity_scheme_d_0" style="display: none;">
                                                                                <input type="text" value="" style="width: 100%;" id="stp_equity_scheme_name_0" maxlength="500" name="stp_equity_scheme_name[0][0]">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td id="" style="color:red;text-align: center;" onclick="removeSTP('0');">
                                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="text-right text-danger">
                                                        <small id="stp_table_error_0"></small>
                                                    </div>
                                                    <div class="text-right pt-2">
                                                        <button type="button" class="btn banner-btn" onclick="AddMoreSTP(0);">Add More</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center pt-2">
                                            <button type="button" class="btn banner-btn" onclick="AddSTPAssertClass();">Add STP PLAN</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="stp_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row pt-5 mt-3" id="global_swp">
                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                        <input type="checkbox" name="swp_checkbox" id="swp_checkbox" onchange="changeSWPCheckbox();" <?php echo ($swp_checkbox)?'checked':'';?>> SWP
                                        <span class="checkmark"></span>
                                    </label>
                                    
                                    <div class="col-md-12" >
                                        <div id="swp_main">
                                            @if($swp_checkbox)
                                                @php $swp_count = 0; @endphp
                                                @foreach($swp_form_list as $k => $ress)
                                                    <div id="swp_main_{{$k}}">
                                                        <div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Total Investment Amount</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="swp_total_investment_amount[{{$k}}]" class="form-control" id="swp_total_investment_amount_'{{$k}}'" value="{{$ress['total_investment_amount']}}" onkeyup ="changeSwpCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon ">
                                                                                ₹
                                                                            </div>
                                                                            <em id="swp_total_investment_amount_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="swp_assumed_rate_of_return[{{$k}}]" class="form-control" id="swp_assumed_rate_of_return_{{$k}}" value="{{$ress['assumed_rate_of_return']}}" onkeyup ="changeSwpCalculation('{{$k}}');" onkeypress="return isNumeric(event)">
                                                                            <div class="cal-icon">
                                                                                %
                                                                            </div>
                                                                            <em id="swp_assumed_rate_of_return_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">SWP Frequency</label>
                                                                        <div class="col-sm-6">
                                                                            <select class="form-control" id="swp_frequency_{{$k}}" name="swp_frequency[{{$k}}]" onchange ="changeSwpCalculation('{{$k}}');">   
                                                                                <option value="Weekly" <?php echo ('Weekly' == $ress['frequency'])?'selected':'';?>>Weekly</option>
                                                                                <option value="Fortnightly" <?php echo ('Fortnightly' == $ress['frequency'])?'selected':'';?>>Fortnightly</option>
                                                                                <option value="Monthly" <?php echo ('Weekly' == $ress['frequency'])?'Monthly':'';?>>Monthly</option>
                                                                            </select>
                                                                            <em id="swp_frequency_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">SWP Period</label>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" name="swp_period_year[{{$k}}]" id="swp_period_year_{{$k}}" class="form-control" value="{{$ress['period_year']}}" onkeyup ="changeSwpCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                               Yrs
                                                                            </div>
                                                                            <em id="swp_period_year_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                        <div class="col-sm-3">
                                                                            <input type="number" name="swp_period_month[{{$k}}]" id="swp_period_month_{{$k}}" class="form-control" value="{{$ress['period_month']}}" onkeyup ="changeSwpCalculation('{{$k}}'); changeMonthValue('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon" style="width:70px;">
                                                                               Months
                                                                            </div>
                                                                            <em id="swp_period_month_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Required End Value</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="swp_required_end_value[{{$k}}]" id="swp_required_end_value_{{$k}}" class="form-control" value="{{$ress['required_end_value']}}" onkeyup ="changeSwpCalculation('{{$k}}');" onkeypress="return isOnlyNumber(event)">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="swp_required_end_value_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-12">
                                                                
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-3 col-form-label">SWP Amount</label>
                                                                        <div class="col-sm-6">
                                                                            <div class="form-group row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-check form-check-inline">
                                                                                        <label class="checkLinecontainer mb-0 mt-2" for="swp_type_amount_{{$k}}">In Amount
                                                                                            <input class="form-check-input" type="radio" name="swp_type_amount[{{$k}}]" id="swp_type_amount_{{$k}}" value="1" onchange="changeSwpCalculation('{{$k}}'); changeSWPAmount('{{$k}}',1);"  checked>
                                                                                            <span class="checkmark"></span>
                                                                                        </label> 
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">
                                                                                    <input type="text" name="swp_in_amount[{{$k}}]" id="swp_in_amount_{{$k}}" class="form-control" value="{{$ress['in_amount']}}" onkeyup ="changeSwpCalculation('{{$k}}'); changeSWPAmount('{{$k}}',3);" onkeypress="return isNumeric(event)">
                                                                                    <div class="cal-icon">
                                                                                        ₹
                                                                                    </div>
                                                                                    <em id="swp_in_amount_{{$k}}_error" class="error"></em>
                                                                                   <div id="swp_total_investment_amount_message_{{$k}}"></div>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div class="row">
                                                                                <div class="col-sm-6">
                                                                                    <div class="form-check form-check-inline">
                                                                                        <label class="checkLinecontainer mb-0 mt-2" for="swp_type_percent_{{$k}}">In %
                                                                                            <input class="form-check-input" type="radio" name="swp_type_amount[{{$k}}]" id="swp_type_percent_{{$k}}" value="2"  onchange="changeSwpCalculation('{{$k}}');  changeSWPAmount('{{$k}}',2);" >
                                                                                            <span class="checkmark"></span>
                                                                                        </label> 
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-sm-6">  
                                                                                    <input type="hidden" name="swp_in_amount_hide[{{$k}}]" id="swp_in_amount_hide_{{$k}}">
                                                                                    <input type="text" name="swp_in_percent[{{$k}}]" id="swp_in_percent_{{$k}}" class="form-control" value="{{$ress['in_percent']}}" onkeyup ="changeSwpCalculation('{{$k}}'); changeSWPAmount('{{$k}}',4);" readonly="true" onkeypress="return isNumeric(event)">
                                                                                    <div class="cal-icon">
                                                                                            %
                                                                                    </div>
                                                                                    <em id="swp_in_percent_{{$k}}_error" class="error"></em>
                                                                                    <div id="swp_total_investment_percent_message_{{$k}}"></div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group row">
                                                                        <label class="col-sm-6 col-form-label">Actual End Value</label>
                                                                        <div class="col-sm-6">
                                                                            <input type="text" name="swp_actual_end_value[{{$k}}]" id="swp_actual_end_value_{{$k}}" class="form-control" value="{{$ress['actual_end_value']}}" readonly="readonly" message="swp" datatype="{{$k}}">
                                                                            <div class="cal-icon">
                                                                                ₹
                                                                            </div>
                                                                            <em id="swp_actual_end_value_{{$k}}_error" class="error"></em>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="table-responsive pt-2">
                                                            <div class="roundTable">
                                                                <table id="nonMfTable" class="table table-bordered">
                                                                    <thead>
                                                                      <tr>
                                                                        <th style="width: 15%;">
                                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                <input type="checkbox" checked="checked" name="swp_investor_checkbox" value="1"> Investor
                                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                            </label>
                                                                        </th>
                                                                        <th style="width: 30%;">Scheme</th>
                                                                        <th style="">
                                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                                <input type="checkbox" checked="checked" name="swp_category_checkbox" value="1"> Category
                                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                            </label>
                                                                        </th>
                                                                        <th style="width: 20%;">Amount</th>
                                                                        <th style="width: 5%;">Action</th>
                                                                      </tr>
                                                                    </thead>
                                                                    <tbody id="swp_div_{{$k}}" class="spaceless">
                                                                        @foreach($ress['table_list'] as $k1 => $val)
                                                                            <tr id="swp_tr_{{$swp_count}}">
                                                                                <td style="width: 15%;"><input type="text" value="{{$val['investor']}}" style="width: 100%;" maxlength="500" name="swp_investor[{{$k}}][{{$swp_count}}]" id="swp_investor_{{$swp_count}}"></td>
                                                                                <td style="width: 30%;">
                                                                                    <div class="">
                                                                                        <div class="form-group mb-0" id="swp_schemecode_id_div_{{$swp_count}}">
                                                                                            <select class="form-control schemecode_id" id="swp_schemecode_id_{{$swp_count}}" name="swp_schemecode_id[{{$k}}][{{$swp_count}}]" onchange="changeSWPScheme('{{$k}}','{{$swp_count}}');" message="schemecode_id">
                                                                                                <option value="">Select</option>
                                                                                                <option value="0">Custom</option>
                                                                                                <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                                    <option value="<?php echo $value->schemecode;?>" <?php echo ($value->schemecode == $val['schemecode'])?'selected':'';?>><?php echo $value->s_name;?></option>
                                                                                                <?php }?>
                                                                                            </select>
                                                                                        </div>
                                                                                        <div class="form-group mb-0" id="swp_schemecode_id_d_{{$swp_count}}" style="display: none;">
                                                                                            <input type="text" value="" style="width: 100%;" id="swp_schemecode_name_{{$swp_count}}" maxlength="500" name="swp_schemecode_name[{{$k}}][{{$swp_count}}]">
                                                                                        </div>
                                                                                    </div>
                                                                                </td>
                                                                                <td>
                                                                                    <div id="swp_category_{{$swp_count}}">{{$val['category']}}</div>
                                                                                    <div id="swp_category_id_{{$swp_count}}" style="display: none">
                                                                                        <input type="text" value="" style="width: 100%;" id="swp_category_input_{{$swp_count}}" maxlength="500" name="swp_category_input[{{$k}}][{{$swp_count}}]">
                                                                                    </div>
                                                                                </td>
                                                                                <td style="width: 20%;"><input type="text" value="{{$val['amount']}}" style="width: 100%;"  message="text" maxlength="500" name="swp_amount[{{$k}}][{{$swp_count}}]" id="swp_amount_{{$swp_count}}" message="swp_amount"  onkeypress="return isNumeric(event)"></td>
                                                                                <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeSWP('{{$swp_count}}');">
                                                                                     <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                                                                                </td>
                                                                            </tr>
                                                                            @php $swp_count = $swp_count + 1; @endphp
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <div class="text-right text-danger">
                                                            <small id="swp_table_error_0"></small>
                                                        </div>
                                                        <div class="text-right pt-2">
                                                            <button type="button" class="btn banner-btn" onclick="AddMoreSWP(0);">Add More</button>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            @else
                                                <div id="swp_main_0">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Total Investment Amount</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="swp_total_investment_amount[0]" class="form-control" id="swp_total_investment_amount_0" value="" onkeyup ="changeSwpCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon ">
                                                                            ₹
                                                                        </div>
                                                                        <em id="swp_total_investment_amount_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Assumed Rate of Return</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="swp_assumed_rate_of_return[0]" class="form-control" id="swp_assumed_rate_of_return_0" value="" onkeyup ="changeSwpCalculation(0);" onkeypress="return isNumeric(event)">
                                                                        <div class="cal-icon">
                                                                            %
                                                                        </div>
                                                                        <em id="swp_assumed_rate_of_return_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">SWP Frequency</label>
                                                                    <div class="col-sm-6">
                                                                        <select class="form-control" id="swp_frequency_0" name="swp_frequency[0]" onchange ="changeSwpCalculation(0);">   
                                                                            <option value="Weekly">Weekly</option>
                                                                            <option value="Fortnightly">Fortnightly</option>
                                                                            <option value="Monthly" selected="selected">Monthly</option>
                                                                        </select>
                                                                        <em id="swp_frequency_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">SWP Period</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="number" name="swp_period_year[0]" id="swp_period_year_0" class="form-control" value="" onkeyup ="changeSwpCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                           Yrs
                                                                        </div>
                                                                        <em id="swp_period_year_0_error" class="error"></em>
                                                                    </div>
                                                                    <div class="col-sm-3">
                                                                        <input type="number" name="swp_period_month[0]" id="swp_period_month_0" class="form-control" value="" onkeyup ="changeSwpCalculation(0); changeMonthValue(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon" style="width:70px;">
                                                                           Months
                                                                        </div>
                                                                        <em id="swp_period_month_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Required End Value</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="swp_required_end_value[0]" id="swp_required_end_value_0" class="form-control" value="" onkeyup ="changeSwpCalculation(0);" onkeypress="return isOnlyNumber(event)">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="swp_required_end_value_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                            
                                                                <div class="form-group row">
                                                                    <label class="col-sm-3 col-form-label">SWP Amount</label>
                                                                    <div class="col-sm-6">
                                                                        <div class="form-group row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="checkLinecontainer mb-0 mt-2" for="swp_type_amount_0">In Amount
                                                                                        <input class="form-check-input" type="radio" name="swp_type_amount[0]" id="swp_type_amount_0" value="1" onchange="changeSwpCalculation(0); changeSWPAmount(0,1);"  checked>
                                                                                        <span class="checkmark"></span>
                                                                                    </label> 
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">
                                                                                <input type="text" name="swp_in_amount[0]" id="swp_in_amount_0" class="form-control" value="" onkeyup ="changeSwpCalculation(0); changeSWPAmount(0,3);" onkeypress="return isNumeric(event)">
                                                                                <div class="cal-icon">
                                                                                    ₹
                                                                                </div>
                                                                                <em id="swp_in_amount_0_error" class="error"></em>
                                                                               <div id="swp_total_investment_amount_message_0"></div>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="row">
                                                                            <div class="col-sm-6">
                                                                                <div class="form-check form-check-inline">
                                                                                    <label class="checkLinecontainer mb-0 mt-2" for="swp_type_percent_0">In %
                                                                                        <input class="form-check-input" type="radio" name="swp_type_amount[0]" id="swp_type_percent_0" value="2"  onchange="changeSwpCalculation(0);  changeSWPAmount(0,2);" >
                                                                                        <span class="checkmark"></span>
                                                                                    </label> 
                                                                                </div>
                                                                            </div>
                                                                            <div class="col-sm-6">  
                                                                                <input type="hidden" name="swp_in_amount_hide[0]" id="swp_in_amount_hide_0">
                                                                                <input type="text" name="swp_in_percent[0]" id="swp_in_percent_0" class="form-control" value="" onkeyup ="changeSwpCalculation(0); changeSWPAmount(0,4);" readonly="true" onkeypress="return isNumeric(event)">
                                                                                <div class="cal-icon">
                                                                                        %
                                                                                </div>
                                                                                <em id="swp_in_percent_0_error" class="error"></em>
                                                                                <div id="swp_total_investment_percent_message_0"></div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-6 col-form-label">Actual End Value</label>
                                                                    <div class="col-sm-6">
                                                                        <input type="text" name="swp_actual_end_value[0]" id="swp_actual_end_value_0" class="form-control" value="" readonly="readonly" message="swp" datatype="0">
                                                                        <div class="cal-icon">
                                                                            ₹
                                                                        </div>
                                                                        <em id="swp_actual_end_value_0_error" class="error"></em>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="table-responsive pt-2">
                                                        <div class="roundTable">
                                                            <table id="nonMfTable" class="table table-bordered">
                                                                <thead>
                                                                  <tr>
                                                                    <th style="width: 15%;">
                                                                        <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                            <input type="checkbox" checked="checked" name="swp_investor_checkbox" value="1"> Investor
                                                                            <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                        </label>
                                                                    </th>
                                                                    <th style="width: 30%;">Scheme</th>
                                                                    <th style="">
                                                                        <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                            <input type="checkbox" checked="checked" name="swp_category_checkbox" value="1"> Category
                                                                            <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                                        </label>
                                                                    </th>
                                                                    <th style="width: 20%;">Amount</th>
                                                                    <th style="width: 5%;">Action</th>
                                                                  </tr>
                                                                </thead>
                                                                <tbody id="swp_div_0" class="spaceless">
                                                                  <tr id="swp_tr_0">
                                                                    <td style="width: 15%;"><input type="text" value="" style="width: 100%;" maxlength="500" name="swp_investor[0][0]" id="swp_investor_0"></td>
                                                                    <td style="width: 30%;">
                                                                        <div class="">
                                                                            <div class="form-group mb-0" id="swp_schemecode_id_div_0">
                                                                                <select class="form-control schemecode_id" id="swp_schemecode_id_0" name="swp_schemecode_id[0][0]" onchange="changeSWPScheme(0,0);" message="schemecode_id">
                                                                                    <option value="">Select</option>
                                                                                    <option value="0">Custom</option>
                                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                                    <?php }?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="form-group mb-0" id="swp_schemecode_id_d_0" style="display: none;">
                                                                                <input type="text" value="" style="width: 100%;" id="swp_schemecode_name_0" maxlength="500" name="swp_schemecode_name[0][0]">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <div id="swp_category_0"></div>
                                                                        <div id="swp_category_id_0" style="display: none">
                                                                            <input type="text" value="" style="width: 100%;" id="swp_category_input_0" maxlength="500" name="swp_category_input[0][0]">
                                                                        </div>
                                                                    </td>
                                                                    <td style="width: 20%;"><input type="text" value="" style="width: 100%;"  message="text" maxlength="500" name="swp_amount[0][0]" id="swp_amount_0" message="swp_amount"  onkeypress="return isNumeric(event)"></td>
                                                                    <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeSWP('0');">
                                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="text-right text-danger">
                                                        <small id="swp_table_error_0"></small>
                                                    </div>
                                                    <div class="text-right pt-2">
                                                        <button type="button" class="btn banner-btn" onclick="AddMoreSWP(0);">Add More</button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center pt-2">
                                            <button type="button" class="btn banner-btn" onclick="AddSWPAssertClass();">Add SWP PLAN</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;display: <?php echo ($swp_checkbox)?'none':'';?>;" id="swp_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div  class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted titleBlueUnderline"><strong>Other Investment Schemes</strong></h6>
                                    </div>
                                </div>
                                    
                                <div class="row">
                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                        <input type="checkbox" name="non_mf_product_checkbox" id="non_mf_product_checkbox" onchange="changeNonMfProductCheckbox();" <?php echo ($non_mf_product_checkbox)?'checked':'';?>> Other Investment Schemes
                                        <span class="checkmark"></span>
                                    </label>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive pt-2">
                                            <div class="roundTable">
                                                <table id="nonMfTable" class="table table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th>
                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                <input type="checkbox" <?php echo ($non_mf_product_investor_checkbox)?'checked':'';?> name="non_mf_product_investor_checkbox" value="1"> Investor
                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                            </label>
                                                        </th>
                                                        <th >Product</th>
                                                        <th >Scheme / Company</th>
                                                        <th>
                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                <input type="checkbox" <?php echo ($non_mf_product_amount_checkbox)?'checked':'';?> name="non_mf_product_amount_checkbox" value="1"> Amount
                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                            </label>
                                                        </th>
                                                        <th>
                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                <input type="checkbox" <?php echo ($non_mf_product_remark_checkbox)?'checked':'';?> name="non_mf_product_remark_checkbox" value="1"> Remarks
                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                            </label>
                                                        </th>
                                                        <th style="width: 15%;">Attach Scheme&nbsp;Detail</th>
                                                        <th >Action</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="non_mf_product_div" class="spaceless">
                                                        @foreach($non_mf_product_list as $k => $ress)
                                                            <tr id="non_mf_product_tr_{{$k}}">
                                                                <td><input type="text" value="{{$ress['inverstor']}}" style="width: 100%;" maxlength="500" name="non_mf_product_inverstor[{{$k}}]"></td>
                                                                <td>
                                                                    <div class="">
                                                                        <div class="form-group mb-0" id="non_mf_product_id_div_0">
                                                                            <select class="form-control schemecode_id" id="non_mf_product_id_0" name="non_mf_product_id[]" onchange="changeNonMfProducts('non_mf_product_id','{{$k}}',0);">
                                                                                <option value="">Select</option>
                                                                                <option value="0">Custom</option>
                                                                                <?php foreach ($product_list as $key => $value) { ?>
                                                                                    <option value="<?php echo $value->id;?>" <?php echo ($value->id == $ress['product_id'])?'selected':'';?>><?php echo $value->name;?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <!--<b class="schemeSelectNotch"></b>-->
                                                                        </div>
                                                                        <div class="form-group mb-0" id="non_mf_product_id_d_{{$k}}" style="display: none;">
                                                                            <input type="text" value="" style="width: 100%;" id="product_name_{{$k}}" maxlength="500" name="product_name[]">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" value="{{$ress['company']}}" style="width: 100%;" maxlength="40" name="non_mf_product_company[]" id="non_mf_product_company_{{$k}}" ></td>
                                                                <td><input type="text" value="{{$ress['amount']}}" style="width: 100%;" maxlength="500" name="non_mf_product_amount[]" id="non_mf_product_amount_{{$k}}" onkeypress="return isNumeric(event)"></td>
                                                                <td><input type="text" value="{{$ress['remark']}}" style="width: 100%;" maxlength="100" name="non_mf_product_remark[]" id="non_mf_product_remark_{{$k}}"></td>      
                                                                <td>
                                                                    <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                        <input type="checkbox" checked="checked" name="non_mf_product_attach[{{$k}}]" id="non_mf_product_attach_{{$k}}">
                                                                        <span class="checkmark" style="top: 50%;margin-top: -12px;"></span>
                                                                    </label>
                                                                </td>
                                                                <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeNonMfProducts('{{$k}}');">
                                                                     <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                  </table>
                                            </div>
                                        </div>
                                        <div class="text-right pt-2">
                                            <button type="button" class="btn banner-btn" onclick="AddMoreNonMfProducts(0);">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="non_mf_products_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div  class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted titleBlueUnderline"><strong>Insurance Schemes</strong></h6>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <label class="col-sm-12 col-form-label checkcontainer2">
                                        <input type="checkbox" name="insurance_product_checkbox" id="insurance_product_checkbox" onchange="changeInsuranceProductCheckbox();" <?php echo ($insurance_product_checkbox)?'checked':'';?>> Insurance Schemes
                                        <span class="checkmark"></span>
                                    </label>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive pt-2">
                                            <div class="roundTable">
                                                <table id="insuranceTable" class="table table-bordered">
                                                    <thead>
                                                      <tr>
                                                        <th>
                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                <input type="checkbox" <?php echo ($insurance_product_insured_name_checkbox)?'checked':'';?> name="insurance_product_insured_name_checkbox" value="1"> Insured Name
                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                            </label>
                                                        </th>
                                                        <th >Product</th>
                                                        <th >Scheme / Company</th>
                                                        <th >Sum Assured</th>
                                                        <th >Annual Premium</th>
                                                        <th>
                                                            <label class="checkcontainer mb-0" style="padding-left: 45px;">
                                                                <input type="checkbox" <?php echo ($insurance_product_remark_checkbox)?'checked':'';?> name="insurance_product_remark_checkbox" value="1"> Remarks
                                                                <span class="checkmark" style="left: 0px;margin-left: 12px;"></span>
                                                            </label>
                                                        </th>
                                                        <th >Action</th>
                                                      </tr>
                                                    </thead>
                                                    <tbody id="insurance_product_div" class="spaceless">
                                                        @foreach($insurance_product_list as $k => $ress)
                                                            <tr id="insurance_product_tr_{{$k}}">
                                                                <td><input type="text" value="{{$ress['inverstor']}}" style="width: 100%;" maxlength="500" name="insurance_product_investor[{{$k}}]" id="insurance_product_investor"></td>
                                                                <td>
                                                                    <div class="">
                                                                        <div class="form-group mb-0" id="insurance_product_type_div_{{$k}}">
                                                                            <select class="form-control schemecode_id" id="insurance_product_type_id_{{$k}}" name="insurance_product_type_id[{{$k}}]" onchange="changeInsuranceProduct('insurance_product_type_id','{{$k}}',0);">
                                                                                <option value="">Select</option>
                                                                                <option value="0">Custom</option>
                                                                                <?php foreach ($product_type_list as $key => $value) { ?>
                                                                                    <option value="<?php echo $value->id;?>" <?php echo ($value->id == $ress['product_type_id'])?'selected':'';?>><?php echo $value->name;?></option>
                                                                                <?php } ?>
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group mb-0" id="insurance_product_type_d_{{$k}}" style="display: none;">
                                                                            <input type="text" value="" style="width: 100%;" id="product_type_name_{{$k}}" maxlength="500" name="product_type_name[{{$k}}]">
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td><input type="text" value="{{$ress['company']}}" style="width: 100%;" maxlength="40" name="insurance_product_company[{{$k}}]" id="insurance_product_company_{{$k}}"></td>
                                                                <td><input type="text" value="{{$ress['sum_assured']}}" style="width: 100%;" maxlength="500" name="insurance_product_sum_assured[{{$k}}]" id="insurance_product_sum_assured_{{$k}}"></td>
                                                                <td><input type="text" value="{{$ress['annual_premium']}}" style="width: 100%;" maxlength="500" name="insurance_product_annual_premium[{{$k}}]" id="insurance_product_annual_premium_{{$k}}"></td>
                                                                <td><input type="text" value="{{$ress['remark']}}" style="width: 100%;" maxlength="100" name="insurance_product_remark[{{$k}}]" id="insurance_product_remark_{{$k}}"></td>
                                                                <td id="" style="color:red;text-align: center; width: 5%;" onclick="removeInsuranceProduct('{{$k}}');">
                                                                     <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                  </table>
                                            </div>
                                        </div>
                                        <div class="text-right pt-2">
                                            <button type="button" class="btn banner-btn" onclick="addMoreInsuranceProduct(0);">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="insurance_product_view">
                                                
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if(isset($client) && $client=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{isset($clientname)?$clientname:''}}" maxlength="30">
                                        <div class="cal-icon">
                                            <i class="fa fa-user"></i>
                                        </div>
                                        @if ($errors->has('clientname'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('clientname') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if(isset($is_note) && $is_note=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$note}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(isset($data['report']) && $data['report']=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(isset($data['report']) && $data['report']=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                @include('frontend.calculators.suggested.edit_form')
                                <div class="form-group row">
                                    
                                    <!-- <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div> -->
                                    
                                    <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><!-- <i class="fa fa-angle-left"></i> --> Back</button>
                                                @if(session()->get('calculator_form_id'))
                                                    <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                @else
                                                    <a href="{{route('frontend.investment_proposal')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
                                                @endif
                                                <button class="btn banner-btn mx-3">Calculate</button>
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

    <div class="modal fade" id="customModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Custom Scheme</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label style="margin-bottom: 0px;">Scheme</label>
                    <input type="text" name="modal_title" id="modal_title" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;">
                </div>
                <div class="form-group" id="modal_category_id">
                    <label style="margin-bottom: 0px;">Category</label>
                    <input type="text" name="modal_category" id="modal_category" class="form-control" value="" style="padding-bottom: 0px;padding-top: 0px;min-height: 35px;height: 35px;">
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
