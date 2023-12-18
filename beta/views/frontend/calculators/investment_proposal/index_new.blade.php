@extends('layouts.frontend')

@section('js_after')
    
    <script>
        var scheme_list = <?php echo json_encode($scheme_list);?>;
        var assets_list = <?php echo json_encode($assets_list);?>;
        var category_list = <?php echo json_encode($category_list);?>;
        var lumpsum_index = 0;
        var sip_index = 0;
        var stp_index = 0;
        var swp_index = 0;
        var non_mf_product_index = 0;
        var insurance_product_index = 0;

        var selected_scheme_id = "";
        var selected_index = "";
        
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

        function changeLumpsum(key_name,index,rule){
            var lumpsum_investor = document.getElementById('lumpsum_investor_'+index).value;
            var lumpsum_schemecode_id = document.getElementById('lumpsum_schemecode_id_'+index).value;
            var lumpsum_amount = document.getElementById('lumpsum_amount_'+index).value;
            var lumpsum_investment_period = document.getElementById('lumpsum_investment_period_'+index).value;
            var lumpsum_assumed_return = document.getElementById('lumpsum_assumed_return_'+index).value;
            console.log(lumpsum_schemecode_id);
            console.log(index);
            console.log(key_name);
            console.log(rule);
            if(key_name == "lumpsum_schemecode_id"){
                if(lumpsum_schemecode_id == 0){
                    selected_scheme_id = "lumpsum_schemecode_id";
                    selected_index = index;
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

            lumpsum_amount = parseInt(lumpsum_amount);
            lumpsum_assumed_return = parseInt(lumpsum_assumed_return);
            lumpsum_investment_period = parseInt(lumpsum_investment_period);

            if(lumpsum_amount && lumpsum_assumed_return && lumpsum_investment_period){
                document.getElementById('lumpsum_expected_future_value_'+index).value = parseFloat(lumpsum_amount * Math.pow((1+lumpsum_assumed_return/100), lumpsum_investment_period)).toFixed(0);
            }else{
                document.getElementById('lumpsum_expected_future_value_'+index).value = "";
            }
        }

        function removeLumpsum(index){
            document.getElementById("lumpsum_tr_"+index).remove();
        }

        function AddMoreLumpsum(){
            lumpsum_index = lumpsum_index+1;
            iHtml = `<tr class="generatetr" id="lumpsum_tr_`+lumpsum_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%; maxlength="100" name="lumpsum_investor[]" id="lumpsum_investor_`+lumpsum_index+`" onkeyup="changeLumpsum('lumpsum_investor',`+lumpsum_index+`,0);"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="schemecode_id_div`+lumpsum_index+`">
                                    <select class="form-control schemecode_id" id="lumpsum_schemecode_id_`+lumpsum_index+`" name="lumpsum_schemecode_id[]" onchange="changeLumpsum('lumpsum_schemecode_id',`+lumpsum_index+`,0);">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="lumpsum_schemecode_id_d_`+lumpsum_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="lumpsum_schemecode_name_`+lumpsum_index+`" maxlength="500" name="lumpsum_schemecode_name[]">
                                </div>
                            </div>
                        </td>
                        <td id="lumpsum_category_`+lumpsum_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="10" name="lumpsum_amount[]" id="lumpsum_amount_`+lumpsum_index+`" onkeyup="changeLumpsum('lumpsum_amount',`+lumpsum_index+`,1);"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="10" name="lumpsum_investment_period[]" id="lumpsum_investment_period_`+lumpsum_index+`" onkeyup="changeLumpsum('lumpsum_investment_period',`+lumpsum_index+`,2);"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="10" name="lumpsum_assumed_return[]" id="lumpsum_assumed_return_`+lumpsum_index+`" onkeyup="changeLumpsum('lumpsum_assumed_return',`+lumpsum_index+`,3);"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_expected_future_value[]" id="lumpsum_expected_future_value_`+lumpsum_index+`" readonly="readonly"></td>
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeLumpsum('`+lumpsum_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#lumpsum_div").append(iHtml);

            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSIP(key_name,index,rule){
            var sip_investor = document.getElementById('sip_investor_'+index).value;
            var sip_schemecode_id = document.getElementById('sip_schemecode_id_'+index).value;
            var sip_amount = document.getElementById('sip_amount_'+index).value;
            var sip_frequency = document.getElementById('sip_frequency_'+index).value;
            var sip_period = document.getElementById('sip_period_'+index).value;
            var sip_investment_period = document.getElementById('sip_investment_period_'+index).value;
            var sip_assumed_return = document.getElementById('sip_assumed_return_'+index).value;

            if(key_name == "sip_schemecode_id"){
                if(sip_schemecode_id == 0){
                    selected_scheme_id = "sip_schemecode_id";
                    selected_index = index;
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

            sip_amount = parseInt(sip_amount);
            sip_frequency = parseInt(sip_frequency);
            sip_period = parseInt(sip_period);
            sip_investment_period = parseInt(sip_investment_period);
            sip_assumed_return = parseInt(sip_assumed_return);

            if(sip_amount && sip_period && sip_investment_period){
                var sip_periods = sip_period * sip_frequency;
                var expected_return = (Math.pow((1+sip_assumed_return/100) , (1/sip_frequency)) - 1);
                var total_investment = sip_amount * sip_periods;
                var investment_period = sip_investment_period * sip_frequency;
                var balance_period = investment_period - sip_periods;
                var future_value = (1+expected_return)*sip_amount*((Math.pow((1+expected_return),(sip_periods))-1))/expected_return;
                var final_value = future_value*Math.pow((1+expected_return),balance_period);
                console.log(balance_period);
                console.log(sip_assumed_return);
                console.log(future_value);
                console.log(expected_return);
                console.log(sip_periods);
                document.getElementById('sip_expected_future_value_'+index).value = parseFloat(final_value).toFixed(0);
                document.getElementById('sip_total_investment_'+index).value = parseFloat(sip_periods*sip_amount).toFixed(0);
            }else{
                document.getElementById('sip_expected_future_value_'+index).value = "";
                document.getElementById('sip_total_investment_'+index).value = "";
            }
        }

        function removeSIP(index){
            document.getElementById("sip_tr_"+index).remove();
        }

        function AddMoreSIP(){
            sip_index = sip_index+1;
            iHtml = `<tr class="generatetr" id="sip_tr_`+sip_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="100" name="sip_investor[]" id="sip_investor_`+sip_index+`" onkeyup="changeSIP('sip_investor',`+sip_index+`,0);"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="sip_schemecode_id_div_`+sip_index+`">
                                    <select class="form-control schemecode_id" id="sip_schemecode_id_`+sip_index+`" name="sip_schemecode_id[]" onchange="changeSIP('sip_schemecode_id',`+sip_index+`,0);">   
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="sip_schemecode_id_d_`+sip_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="sip_schemecode_name_`+sip_index+`" maxlength="500" name="lumpsum_schemecode_name[]">
                                </div>
                            </div>
                        </td>
                        <td id="sip_category_`+sip_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_amount[]" id="sip_amount_`+sip_index+`" onkeyup="changeSIP('sip_amount',`+sip_index+`,0);"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0">
                                    <select class="form-control schemecode_id" id="sip_frequency_`+sip_index+`" name="sip_frequency[]" onchange="changeSIP('sip_frequency',`+sip_index+`,0);">   
                                        <option value="52">Weekly</option>
                                        <option value="26">Fortnightly</option>
                                        <option value="12">Monthly</option>
                                        <option value="4">Quarterly</option>
                                        <option value="2">Half-Yearly</option>
                                        <option value="1">Yearly</option>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_period[]" id="sip_period_`+sip_index+`" onkeyup="changeSIP('sip_period',`+sip_index+`,0);"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_investment_period[]" id="sip_investment_period_`+sip_index+`" onkeyup="changeSIP('sip_investment_period',`+sip_index+`,0);"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_assumed_return[]" id="sip_assumed_return_`+sip_index+`" onkeyup="changeSIP('sip_assumed_return',`+sip_index+`,0);"></td>
                        <td id="">
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_total_investment[]" id="sip_total_investment_`+sip_index+`" readonly="readonly">
                        </td>
                        <td id="">
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_expected_future_value[]" id="sip_expected_future_value_`+sip_index+`" readonly="readonly">
                        </td>
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSIP('`+sip_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#sip_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSTP(key_name,index,rule){
            document.getElementById('stp_amount_error_'+index).innerHTML = "";
            document.getElementById("stp_investment_period_error_"+index).innerHTML = "";

            console.log(key_name);
            var stp_initial_investment_amount = document.getElementById('stp_initial_investment_amount_'+index).value;
            var stp_assumed_return = document.getElementById('stp_assumed_return_'+index).value;
            var stp_equity_assumed_return = document.getElementById('stp_equity_assumed_return_'+index).value;
            var stp_mode = document.getElementById('stp_mode_'+index).value;
            var stp_frequency = document.getElementById('stp_frequency_'+index).value;
            var stp_no_of_frequency = document.getElementById('stp_no_of_frequency_'+index).value;
            var stp_investment_period = document.getElementById('stp_investment_period_'+index).value;
            var stp_amount = document.getElementById('stp_amount_'+index).value;
            var stp_schemecode_id = document.getElementById('stp_schemecode_id_'+index).value;
            var stp_equity_scheme = document.getElementById('stp_equity_scheme_'+index).value;

            if(key_name == "stp_schemecode_id"){
                if(stp_schemecode_id == 0){
                    selected_scheme_id = "stp_schemecode_id";
                    selected_index = index;
                    $("#customModal").modal("show");
                }
            }

            if(key_name == "stp_equity_scheme"){
                if(stp_equity_scheme == 0){
                    selected_scheme_id = "stp_equity_scheme";
                    selected_index = index;
                    $("#customModal").modal("show");
                }
            }

            if(stp_mode == "Appreciation"){
                document.getElementById('stp_amount_'+index).readOnly = true;
            }else{
                document.getElementById('stp_amount_'+index).readOnly = false;
            }

            if(key_name == "stp_mode"){
                document.getElementById('stp_amount_'+index).value = "";
            }

            stp_initial_investment_amount = parseFloat(stp_initial_investment_amount);
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


            // if(key_name == "stp_investment_period"){
            //     console.log(stp_investment_period >= 1 && stp_investment_period <= 99);
            //     if(stp_investment_period >= 1 && stp_investment_period <= 99){
                    
            //     }else{
            //         document.getElementById("stp_investment_period_error_"+index).innerHTML = "Please enter a value between 1 and 99";
            //     }
            // }

            if(key_name == "stp_frequency" || key_name == "stp_no_of_frequency"){
                document.getElementById("stp_investment_period_error_"+index).innerHTML = "Max Investment Period : "+parseFloat(stp_no_of_frequency/stp_frequency).toFixed(2);
            }

            console.log(stp_investment_period , stp_frequency , stp_no_of_frequency)
            console.log(stp_mode)
            if(stp_initial_investment_amount && stp_assumed_return && stp_equity_assumed_return){
                var stp_assumed_returns = stp_assumed_return * stp_frequency;
                var debt_return = (Math.pow((1+stp_assumed_return/100) , (1/stp_frequency)) - 1);
                var equity_return = (Math.pow((1+stp_equity_assumed_return/100) , (1/stp_frequency)) - 1);
                var investment_period = stp_investment_period * stp_frequency;
                var balance_period = investment_period - stp_no_of_frequency;

                if(stp_mode == "Appreciation"){
                    stp_amount = stp_initial_investment_amount * debt_return;
                    var debt_value = stp_initial_investment_amount;
                    var debt_value_investment = debt_value*Math.pow((1+debt_return),balance_period);
                    var equity_value = stp_amount/equity_return*(Math.pow((1+equity_return),stp_no_of_frequency)-1);
                    var equity_value_investment = equity_value*Math.pow((1+equity_return),balance_period);
                    var total_investment = debt_value_investment + equity_value_investment;

                    document.getElementById('stp_amount_'+index).value = parseFloat(stp_amount).toFixed(0);
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
                    var max_stp_amount = (debt_return*stp_initial_investment_amount)/(1-Math.pow((1+debt_return),(-stp_no_of_frequency)));
                    
                    console.log(max_stp_amount)
                    document.getElementById('stp_amount_error_'+index).innerHTML = "Max amount Rs. "+parseFloat(max_stp_amount).toFixed(0);

                }
                document.getElementById('stp_expected_future_value_'+index).value = parseFloat(total_investment).toFixed(0);
            }else{
                document.getElementById('stp_expected_future_value_'+index).value = "";
            }

            // stp_investment_period_error_
            // stp_amount_error_
        }

        function removeSTP(index){
            document.getElementById("stp_tr_"+index).remove();
        }

        function AddMoreSTP(){
            stp_index = stp_index+1;
            iHtml = `<tr class="generatetr" id="stp_tr_`+stp_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_investor[]" id="stp_investor_0" onkeyup="changeSTP('stp_investor',`+stp_index+`,0);" message="stp" datatype="`+stp_index+`" ></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="stp_schemecode_id_div_`+stp_index+`">
                                    <select class="form-control schemecode_id" id="stp_schemecode_id_`+stp_index+`" name="stp_schemecode_id[]" onchange="changeSTP('stp_schemecode_id',`+stp_index+`,0);">   
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="stp_schemecode_id_d_`+stp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="stp_schemecode_name_`+stp_index+`" maxlength="500" name="stp_schemecode_name[]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_initial_investment_amount[]" id="stp_initial_investment_amount_`+stp_index+`" onkeyup="changeSTP('stp_initial_investment_amount',`+stp_index+`,0);">
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_assumed_return[]" id="stp_assumed_return_`+stp_index+`" onkeyup="changeSTP('stp_assumed_return',`+stp_index+`,0);">
                        </td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="stp_equity_scheme_id_div_`+stp_index+`">
                                    <select class="form-control schemecode_id" id="stp_equity_scheme_`+stp_index+`" name="stp_equity_scheme[]" onchange="changeSTP('stp_equity_scheme',`+stp_index+`,0);">   
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="stp_equity_scheme_d_`+stp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="stp_equity_scheme_name_`+stp_index+`" maxlength="500" name="stp_equity_scheme_name[]">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_equity_assumed_return[]" id="stp_equity_assumed_return_`+stp_index+`" onkeyup="changeSTP('stp_equity_assumed_return',`+stp_index+`,0);">
                        </td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0">
                                    <select class="form-control schemecode_id" id="stp_mode_`+stp_index+`" name="stp_mode[]" onchange="changeSTP('stp_mode',`+stp_index+`,0);">   
                                        <option value="Appreciation" selected="selected">Appreciation</option>
                                        <option value="Fixed Amount">Fixed Amount</option>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0">
                                    <select class="form-control schemecode_id" id="stp_frequency_`+stp_index+`" name="stp_frequency[]" onchange="changeSTP('stp_frequency',`+stp_index+`,0);">   
                                        <option value="52">Weekly</option>
                                        <option value="26">Fortnightly</option>
                                        <option value="12" selected="selected">Monthly</option>
                                        <option value="4">Quarterly</option>
                                        <option value="2">Half-Yearly</option>
                                        <option value="1">Yearly</option>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_no_of_frequency[]" id="stp_no_of_frequency_`+stp_index+`" onkeyup="changeSTP('stp_no_of_frequency',`+stp_index+`,0);">
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_investment_period[]" id="stp_investment_period_`+stp_index+`" onkeyup="changeSTP('stp_investment_period',`+stp_index+`,0);">
                            <em style="position:static; text-align: left; background: none; display:block;" id="stp_investment_period_error_`+stp_index+`" class="error"></em>
                        </td>
                        <td>
                            <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_amount[]" id="stp_amount_`+stp_index+`"  onkeyup="changeSTP('stp_amount',`+stp_index+`,0);" readonly="readonly">
                            <em  style="position:static; text-align: left; background: none; display:block;" id="stp_amount_error_`+stp_index+`" class="error"></em>
                        </td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_expected_future_value[]" id="stp_expected_future_value_`+stp_index+`" readonly="readonly"></td>
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSTP('`+stp_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#stp_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function changeSWP(key_name,index,rule){
            
            var swp_schemecode_id = document.getElementById('swp_schemecode_id_'+index).value;

            if(key_name == "swp_schemecode_id"){
                if(swp_schemecode_id == 0){
                    selected_scheme_id = "swp_schemecode_id";
                    selected_index = index;
                    $("#customModal").modal("show");
                }else{
                    var data = scheme_list.find(o => o.schemecode == swp_schemecode_id);
                    console.log(data);
                    if(data){
                        var class_name = data.class_name;
                        if(!data.class_name){
                            class_name = data.classname;
                        }
                        document.getElementById('swp_category_'+index).innerHTML = class_name;
                    }else{
                        document.getElementById('swp_category_'+index).innerHTML = "";
                    }
                }
            }

        }

        function removeSWP(index){
            document.getElementById("swp_tr_"+index).remove();
        }

        function AddMoreSWP(){
            swp_index = swp_index+1;
            iHtml = `<tr class="generatetr" id="swp_tr_`+swp_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="swp_investor[]" id="swp_investor_`+swp_index+`" onkeyup="changeSWP('swp_investor',`+swp_index+`,0);"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="swp_schemecode_id_div_`+swp_index+`">
                                    <select class="form-control schemecode_id" id="swp_schemecode_id_`+swp_index+`" name="swp_schemecode_id[]" onchange="changeSWP('swp_schemecode_id',`+swp_index+`,0);">   
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($scheme_list as $key => $value) { ?>
                                            <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                        <?php }?>
                                    </select>
                                </div>
                                <div class="form-group mb-0" id="swp_schemecode_id_d_`+swp_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="swp_schemecode_name_`+swp_index+`" maxlength="500" name="swp_schemecode_name[]">
                                </div>
                            </div>
                        </td>
                        <td id="swp_category_`+swp_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" message="text" name="swp_amount[]" id="swp_amount_`+swp_index+`" onkeyup="changeSWP('swp_amount',`+swp_index+`,0);"></td>
                        
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSWP('`+swp_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                        </td>
                      </tr>`;
            $("#swp_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }
        //remove by biswanath
       //<td><textarea style="width: 100%;border: none;" maxlength="500" rows="3" name="swp_assumed_return[]" id="swp_assumed_return_`+swp_index+`" onkeyup="changeSWP('swp_assumed_return',`+swp_index+`,0);"></textarea></td>
        function changeNonMfProducts(key_name,index,rule){
            var non_mf_product_id = document.getElementById('non_mf_product_id_'+index).value;

            if(key_name == "non_mf_product_id"){
                if(non_mf_product_id == 0){
                    selected_scheme_id = "non_mf_product_id";
                    selected_index = index;
                    $("#customModal").modal("show");
                }
            }
        }

        function removeNonMfProducts(index){
            document.getElementById("non_mf_product_tr_"+index).remove();
        }

        function AddMoreNonMfProducts(){
            non_mf_product_index = non_mf_product_index+1;
            iHtml = `<tr class="generatetr" id="non_mf_product_tr_`+non_mf_product_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="non_mf_product_inverstor[]" id="non_mf_product_inverstor_`+non_mf_product_index+`"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="non_mf_product_id_div_`+non_mf_product_index+`">
                                    <select class="form-control schemecode_id" id="non_mf_product_id_`+non_mf_product_index+`" name="non_mf_product_id[]" onchange="changeNonMfProducts('non_mf_product_id',`+non_mf_product_index+`,0);">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                                <div class="form-group mb-0" id="non_mf_product_id_d_`+non_mf_product_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="product_name_`+non_mf_product_index+`" name="product_name[]">
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;border: none;" name="non_mf_product_company[]" id="non_mf_product_company_`+non_mf_product_index+`" maxlength="40"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" name="non_mf_product_amount[]" id="non_mf_product_amount_`+non_mf_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" name="non_mf_product_remark[]" id="non_mf_product_remark_`+non_mf_product_index+`" maxlength="100"></td>      
                        <td>
                            <label class="allocationCheck black">
                                <input type="checkbox" checked="checked" name="non_mf_product_attach[0]" id="non_mf_product_attach_`+non_mf_product_index+`">
                                <span class="checkmark"></span>
                            </label>
                        </td>
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeNonMfProducts('`+non_mf_product_index+`');">
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
                    $("#customModal").modal("show");
                }
            }
        }

        function removeInsuranceProduct(index){
            document.getElementById("insurance_product_tr_"+index).remove();
        }

        function addMoreInsuranceProduct(){
            insurance_product_index = insurance_product_index+1;
            iHtml = `<tr class="generatetr" id="insurance_product_tr_`+insurance_product_index+`" style="line-height: 15px;">
                        <td><input type="text" value="" style="width: 100%;border: none;" name="insurance_product_investor[]" id="insurance_product_investor_`+insurance_product_index+`"></td>
                        <td>
                            <div class="allocationSegment mfTableCheck">
                                <div class="form-group mb-0" id="insurance_product_type_div_`+insurance_product_index+`">
                                    <select class="form-control schemecode_id" id="insurance_product_type_id_`+insurance_product_index+`" name="insurance_product_type_id[]" onchange="changeInsuranceProduct('insurance_product_type_id',`+insurance_product_index+`,0);">
                                        <option value="">Select</option>
                                        <option value="0">Custom</option>
                                        <?php foreach ($product_type_list as $key => $value) { ?>
                                            <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                        <?php } ?>
                                    </select>
                                    <b class="schemeSelectNotch"></b>
                                </div>
                                <div class="form-group mb-0" id="insurance_product_type_d_`+insurance_product_index+`" style="display: none;">
                                    <input type="text" value="" style="width: 100%;border: none;" id="product_type_name_`+insurance_product_index+`" maxlength="500" name="product_type_name[]">
                                </div>
                            </div>
                        </td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="40" name="insurance_product_company[]" id="insurance_product_company_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="insurance_product_sum_assured[]" id="insurance_product_sum_assured_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="insurance_product_annual_premium[]" id="insurance_product_annual_premium_`+insurance_product_index+`"></td>
                        <td><input type="text" value="" style="width: 100%;border: none;" maxlength="100" name="insurance_product_remark[]" id="insurance_product_remark_`+insurance_product_index+`"></td>
                        <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeInsuranceProduct('`+insurance_product_index+`');">
                             <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                        </td>
                      </tr>`;
            $("#insurance_product_div").append(iHtml);
            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function onsubmitFunction(){
            var swp_period_month = document.getElementById("swp_period_month").value;
            if(swp_period_month){
                swp_period_month = (parseInt(swp_period_month))?parseInt(swp_period_month):0;
                if(swp_period_month>12){
                    document.getElementById("swp_period_month_error").innerHTML = "Less than equalto 12"
                    return false;   
                }
            }

            var swp_total_investment_amount = document.getElementById("swp_total_investment_amount").value;            
            
            if(swp_total_investment_amount){
                var swp_amount = document.getElementById("swp_div").querySelectorAll('input[message=text]');

                var total_swp_amount = 0;
                swp_amount.forEach(function(val){
                    total_swp_amount = total_swp_amount + parseFloat($(val).val());
                });
                swp_total_investment_amount = parseFloat(swp_total_investment_amount);

                if(swp_total_investment_amount != total_swp_amount){
                    alert("Total of Amount should be equal to Total Investment");
                    return false;
                }
            }


            if($("#stp_checkbox").attr('checked')){
                var stp_div = document.getElementById("stp_div").querySelectorAll('input[message=stp]');
                var stp_data = [];
                stp_div.forEach(function(val){
                    stp_data.push($(val).attr('datatype'));
                });

                stp_data.forEach(function(val){

                });
                console.log(stp_data);
            }
            
            return true;
        }

        function changeMonthValue(){
            var swp_period_month = document.getElementById("swp_period_month").value;
            if(swp_period_month){
                swp_period_month = (parseInt(swp_period_month))?parseInt(swp_period_month):0;
                if(swp_period_month>12){
                    document.getElementById("swp_period_month_error").innerHTML = "Less than equalto 12";
                }else{
                    document.getElementById("swp_period_month_error").innerHTML = "";
                }
            }else{
                document.getElementById("swp_period_month_error").innerHTML = "";
            }
        }
        
        function changeHeaderCheckBox(){
            
        }

        function saveModalData(){
            var modal_title = document.getElementById("modal_title").value;
            if(modal_title){
                if(selected_scheme_id == "lumpsum_schemecode_id"){
                    document.getElementById("lumpsum_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("lumpsum_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("lumpsum_schemecode_name_"+selected_index).value = modal_title;
                }else if(selected_scheme_id == "sip_schemecode_id"){
                    document.getElementById("sip_schemecode_id_div_"+selected_index).style.display = "none";
                    document.getElementById("sip_schemecode_id_d_"+selected_index).style.display = "block";
                    document.getElementById("sip_schemecode_name_"+selected_index).value = modal_title;
                }else if(selected_scheme_id == "stp_equity_scheme"){
                    document.getElementById("stp_equity_scheme_id_div_"+selected_index).style.display = "none";
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

        function changeSwpCalculation(){
            document.getElementById("swp_in_amount_hide").value = "";
            var swp_amount = document.getElementById('swp_total_investment_amount').value;
            var swp_return = document.getElementById('swp_assumed_rate_of_return').value;
            var swp_frequency = document.getElementById('swp_frequency').value;
            var swp_period_year = document.getElementById('swp_period_year').value;
            var swp_period_month = document.getElementById('swp_period_month').value;
            var swp_required_end_value = document.getElementById('swp_required_end_value').value;
            var swp_in_amount = document.getElementById('swp_in_amount').value;
            var swp_in_percent = document.getElementById('swp_in_percent').value;

            var swp_type_amount = document.getElementById('swp_type_amount').value;
            var swp_type_percent = document.getElementById('swp_type_percent').value;

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
            var bi53 = swp_amount-bi52;
            var bi54 = (bi53*bi49)/(1-Math.pow((1+bi49),(-bi48)));
            
            var bi55 = bi54/swp_amount;
            var bi56;
            if(swp_frequency == "Weekly"){
                bi56 = bi55 * 52;
            }else if(swp_frequency == "Fortnightly"){
                bi56 = bi55 * 26;
            }else{
                bi56 = bi55 * 12;
            }

            document.getElementById('swp_total_investment_amount_message').innerHTML = "Monthly&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Max &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>"+parseFloat(bi54).toFixed(2)+"</span></b>";
            var bi561 = bi56*100;
            document.getElementById('swp_total_investment_percent_message').innerHTML = "Annually &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <b>Max &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>"+parseFloat(bi561).toFixed(4)+"</span> %</b>";

            if($("#swp_type_amount").is(':checked')){
                if(!swp_in_amount){
                    return false;
                }
            }else if($("#swp_type_percent").is(':checked')){
                if(!swp_in_percent){
                    return false;
                }
            }

            if($("#swp_type_amount").is(':checked')){
                var bi55 = swp_in_amount*(1-Math.pow((1+bi49),(-bi48)))/bi49;
                var bi56 = swp_amount-bi55;
                var bi57 = bi56*Math.pow((1+bi49),bi48);
                document.getElementById('swp_actual_end_value').value = Number(bi57).toFixed(0);
                console.log(bi57);
            }else if($("#swp_type_percent").is(':checked')){
                var bi57 = swp_in_percent/100/12*swp_amount;
                document.getElementById("swp_in_amount_hide").value = bi57;
                var bi58 = bi57*(1-Math.pow((1+bi49),(-bi48)))/bi49;
                var bi59 = swp_amount-bi58;
                var bi60 = bi59*Math.pow((1+bi49),bi48);
                document.getElementById('swp_actual_end_value').value = Number(bi60).toFixed(0);
            }
        }

        function changeLumpsumCheckbox(){
            if($("#lumpsum_checkbox").attr('checked')){
                document.getElementById("lumpsum_view").style.display = "none";
            }else{
                document.getElementById("lumpsum_view").style.display = "block";
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

        function changeSWPAmount(type){
            console.log(type);
            if(type == 1){
                $('#swp_in_amount').prop("readonly", false);
                $('#swp_in_percent').prop("readonly", true);
            }else if(type == 2){
                $('#swp_in_percent').prop("readonly", false);
                $('#swp_in_amount').prop("readonly", true);
            }else if(type == 3){
                var swp_in_amount = $('#swp_in_amount').val();
                var max_amount = document.getElementById("swp_total_investment_amount_message").getElementsByTagName('span')[0].innerHTML;

                swp_in_amount = parseFloat(swp_in_amount);
                max_amount = parseFloat(max_amount);

                if(swp_in_amount > max_amount){
                    document.getElementById("swp_in_amount_error").innerHTML = "Max";
                }else{
                    document.getElementById("swp_in_amount_error").innerHTML = "";
                }
                console.log(swp_in_amount);
                console.log(max_amount);
            }else if(type == 4){
                var swp_in_amount = $('#swp_in_percent').val();
                var max_amount = document.getElementById("swp_total_investment_percent_message").getElementsByTagName('span')[0].innerHTML;

                swp_in_amount = parseFloat(swp_in_amount);
                max_amount = parseFloat(max_amount);

                if(swp_in_amount > max_amount){
                    document.getElementById("swp_in_percent_error").innerHTML = "Max";
                }else{
                    document.getElementById("swp_in_percent_error").innerHTML = "";
                }
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
    </script>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <style>
        .subMfCalcTable {
                background: #f0f1f6;
                margin-left:-9px;
                margin-right: 10px;
                margin-top: 10px;
                margin-bottom: -9px;
        }
        .subMfCalcTable th {
            background: #f0f1f6;
        }
        .spaceless td {
            padding:0 !important;
        }
        .mfTableCheck {
            margin-left:5px;
        }
        .spaceless input, .spaceless textarea, .select2-container--default .select2-selection--single {
            background: transparent !important;
            border: none !important;
        }
        input:focus, textarea:focus {
          outline-width: 0;
        }
        .allocationCheck.blkcheck {
            display: inline-block; 
            width: auto;
            vertical-align: top;
            margin-top: 4px;
        }
        .checktd {
            height: 31px;
            background: #f4f6fb;
            vertical-align: top;
            text-align: center;
        }
        .allocationCheck.blkcheck .checkmark {
            border-color: #000;
        }
        .allocationCheck.blkcheck .checkmark:after {
            border-color: #000;
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
        
        
        .allocationCheck .checkmark {
            position: absolute;
            top: -15px;
            left: -4px;
            height: 13px;
            width: 13px;
            border: 1px solid #fff;
            border-radius: 2px;
            margin-top: 1px;
        }
        .allocationCheck .checkmarkBlack {
            position: absolute;
            top: -15px;
            left: -4px;
            height: 13px;
            width: 13px;
            border: 1px solid black;
            border-radius: 2px;
            margin-top: 1px;
        }
        .allocationCheck {
            display: inline-block;
            position: relative;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
             width: auto; 
            margin-bottom: 0px;
            margin: 0 auto;
        }
        .headstyle {
            vertical-align: top !important;
            padding-top: 15px !important;
        }
        .allocationSegment {
                min-width: 120px;
            }
        .portfolioTopTable.mfCalcTable thead tr th {
            font-size: 12px;
        }
        .select2-container--open .select2-dropdown--below, .select2-container--open .select2-dropdown--above {
            min-width: 250px !important;
        }
        .schemeSelectNotch {
           display:none; 
        }
        .portfolioTopTable.mfCalcTable.nonMfCalcTable {
            border-spacing: 0;
        }
        .generatetr td {
            border-top:0 !important;
        }
        .invbox {
            padding: 10px 20px;
            border: 1px solid #cacaca;
            margin: 10px 15px;
            margin-bottom: -7px;
        }
        .invbox .form-control {
            border: 1px solid #cacaca;
            min-height: 42px;
        }
        .invbox .form-group {
            margin-bottom: 0px;
        }
        .portfolioTopTable.mfCalcTable.nonMfCalcTable thead {
            bottom: -1px;
        }
        .spaceless textarea {
            text-align: center;
        } 
         .allocationCheck.black .checkmark, .allocationCheck.black .checkmark:after {
            border-color: #5e5c5c;
        }
        
        .portfolioTopTable.mfCalcTable tbody tr:nth-child(even) td,
        .spaceless tr:nth-child(even) td {background-color: #fff !important}
         .portfolioTopTable.mfCalcTable tbody tr:nth-child(odd) td,  
        .spaceless tr:nth-child(odd) td {background-color: #f0f1f6 !important } 
        
        .createlist-suggested-scheme-container .form-control {
            border:0;
            min-height: 38px;
        }
        .createlist-suggested-scheme-container #select2-scheme-container {
            line-height:30px;
        }
        #mycustomelist {
            width: 99%;
        }
        .calcucatorCheck .allocationCheck {
            margin:0;
        }
        .spaceless textarea, .spaceless input {
            border: 1px solid #ccc !important;
            margin: 4px 3%;
            width: 94% !important;
            display: block;
            padding: 7px 0;
        }

        .portfolioTopTable.mfCalcTable.nonMfCalcTable.width01 thead tr th:first-child, 
        .portfolioTopTable.mfCalcTable.nonMfCalcTable.width01 tbody tr td:first-child {
            width: 12%;
        }
        .portfolioTopTable thead tr th, .portfolioTopTable tbody tr td {
            font-size: 12px;
        }
        .portfolioTopTable thead tr {
            background-color: #25a8e0;
        }
        .portfolioTopTable.mfCalcTable.width01 tbody tr td:first-child {
            width: 30%;
        }
    </style>
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
                    <h3 class="mb-3 text-center">Investment Proposal</h2>
                    <div class="rt-pnl">
                        @include('frontend.calculators.common_bio')
                        <!-- <div class="rt-btn-prt">  rt-pnl
                            <a href="{{route('frontend.samplereports')}}" target="_blank">Sample Reports</a>
                            <a href="{{route('frontend.how-to-use-calculator')}}" target="_blank">How to Use</a>
                        </div> -->
                        <div class="">
                            <form action="{{route('frontend.investment_proposal_output')}}" method="post" onsubmit="return onsubmitFunction();">
                                @csrf
                                <div class="row px-3">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                        <div class="form-inline investmentAmountForm calcucatorNameForm">
                                            <label for="text" class="mb-0">Client Name</label>
                                            <input type="text" class="form-control" id="client_name" placeholder="" name="client_name">
                                        </div>
                                    </div>
                                    <div class="col-md-2"></div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="allocationSegmentTitle" style="font-size: 19px; padding-top: 20px; text-align:center;">MF Products</div>
                                        <div class="calcucatorCheck" style="margin-top:0;">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle">Lumpsum</div>
                                                    <input type="checkbox" name="lumpsum_checkbox" id="lumpsum_checkbox" onchange="changeLumpsumCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="nonMfTable" class="table portfolioTopTable nonMfCalcTable width01 mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle" style="width:12%;">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="lumpsum_investor_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investor
                                                    </th>
                                                    <th class="headstyle" style="width:29%;"><br>Scheme</th>
                                                    <th class="headstyle" style="width:20%;">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="lumpsum_category_checkboc" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Category
                                                    </th>
                                                    <th class="headstyle"><br>Amount</th>
                                                    <th class="headstyle" >
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="lumpsum_investment _period_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investment Period
                                                    </th>
                                                    <th class="headstyle" >
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="lumpsum_assumed_return_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Assumed Return
                                                    </th>
                                                    <th class="headstyle" >
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="lumpsum_expected_future_value_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Expected Future&nbsp;Value
                                                    </th>
                                                    <th class="headstyle" style="width: 5%;"><br>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="lumpsum_div" class="spaceless">
                                                  <tr id="lumpsum_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_investor[]" id="lumpsum_investor_0" onkeyup="changeLumpsum('lumpsum_investor',0,0);"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="lumpsum_schemecode_id_div_0">
                                                                <select class="form-control ui-autocomplete-input  schemecode_id" id="lumpsum_schemecode_id_0" name="lumpsum_schemecode_id[]" onchange="changeLumpsum('lumpsum_schemecode_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-0" id="lumpsum_schemecode_id_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="lumpsum_schemecode_name_0" maxlength="500" name="lumpsum_schemecode_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td id="lumpsum_category_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_amount[]" id="lumpsum_amount_0" onkeyup="changeLumpsum('lumpsum_amount',0,1);"> </td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_investment_period[]" id="lumpsum_investment_period_0" onkeyup="changeLumpsum('lumpsum_investment_period',0,2);"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_assumed_return[]" id="lumpsum_assumed_return_0" onkeyup="changeLumpsum('lumpsum_assumed_return',0,3);"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="lumpsum_expected_future_value[]" id="lumpsum_expected_future_value_0" readonly="readonly"></td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeLumpsum('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                    </td>
                                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="AddMoreLumpsum();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="lumpsum_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle">SIP</div>
                                                    <input type="checkbox" name="sip_checkbox" id="sip_checkbox" onchange="changeSIPCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="nonMfTable" class="table portfolioTopTable nonMfCalcTable mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle" style="width:12%;">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_investor_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investor
                                                    </th>
                                                    <th class="headstyle" style="width:29%;"><br>Scheme</th>
                                                    <th class="headstyle" style="width:20%;">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_category_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Category
                                                    </th>
                                                    <th class="headstyle"><br>SIP Amount</th>
                                                    <th class="headstyle"><br>Frequency</th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_sip_period_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        SIP Period
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_investment_period_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investment Period
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_assumed_return_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Assumed Return
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_total_investment_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Total Investment
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="sip_expected_future_value_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Expected Future&nbsp;Value
                                                    </th>
                                                    <th class="headstyle"style="width: 5%;"><br>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="sip_div" class="spaceless">
                                                  <tr id="sip_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="100" name="sip_investor[]" id="sip_investor_0" onkeyup="changeSIP('sip_investor',0,0);"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="lumpsum_schemecode_id_div_0">
                                                                <select class="form-control ui-autocomplete-input  schemecode_id" id="sip_schemecode_id_0" name="sip_schemecode_id[]" onchange="changeSIP('sip_schemecode_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-0" id="sip_schemecode_id_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="sip_schemecode_name_0" maxlength="500" name="sip_schemecode_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td id="sip_category_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_amount[]" id="sip_amount_0" onkeyup="changeSIP('sip_amount',0,0);"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0">
                                                                <select class="form-control schemecode_id" id="sip_frequency_0" name="sip_frequency[]" onchange="changeSIP('sip_frequency',0,0);">   
                                                                    <option value="52">Weekly</option>
                                                                    <option value="26">Fortnightly</option>
                                                                    <option value="12" selected="selected">Monthly</option>
                                                                    <option value="4">Quarterly</option>
                                                                    <option value="2">Half-Yearly</option>
                                                                    <option value="1">Yearly</option>
                                                                </select>
                                                                <b class="schemeSelectNotch"></b>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_period[]" id="sip_period_0" onkeyup="changeSIP('sip_period',0,0);"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_investment_period[]" id="sip_investment_period_0" onkeyup="changeSIP('sip_investment_period',0,0);"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_assumed_return[]" id="sip_assumed_return_0" onkeyup="changeSIP('sip_assumed_return',0,0);"></td>
                                                    <td id="">
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_total_investment[]" id="sip_total_investment_0" readonly="readonly">
                                                    </td>
                                                    <td id="">
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="sip_expected_future_value[]" id="sip_expected_future_value_0" readonly="readonly">
                                                    </td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSIP('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                    </td>
                                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="AddMoreSIP();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="sip_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle">STP</div>
                                                    <input type="checkbox" name="stp_checkbox" id="stp_checkbox" onchange="changeSTPCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="nonMfTable" class="table portfolioTopTable mfCalcTable nonMfCalcTable mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="stp_investor_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investor
                                                    </th>
                                                    <th class="headstyle"><br>From Scheme</th>
                                                    <th class="headstyle">
                                                        Initial<br> Investment <br>Amount
                                                    </th>
                                                    <th class="headstyle"><br>Assumed Return</th>
                                                    <th class="headstyle">
                                                        <br>To Scheme
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>Assumed Return
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>Mode
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>Frequency
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>No. of Frequency
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>Investment Period(Yr)
                                                    </th>
                                                    <th class="headstyle">
                                                        <br>STP Amount
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="stp_expected_future_value_checked" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Expected Future&nbsp;Value
                                                    </th>
                                                    <th class="headstyle"style="width: 5%;"><br>Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="stp_div" class="spaceless">
                                                  <tr id="stp_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_investor[]" id="stp_investor_0" message="stp" datatype="0"  onkeyup="changeSTP('stp_investor',0,0);"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="stp_schemecode_id_div_0">
                                                                <select class="form-control schemecode_id" id="stp_schemecode_id_0" name="stp_schemecode_id[]" onchange="changeSTP('stp_schemecode_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-0" id="stp_schemecode_id_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="stp_schemecode_name_0" maxlength="500" name="stp_schemecode_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_initial_investment_amount[]" id="stp_initial_investment_amount_0" onkeyup="changeSTP('stp_initial_investment_amount',0,0);">
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_assumed_return[]" id="stp_assumed_return_0" onkeyup="changeSTP('stp_assumed_return',0,0);">
                                                    </td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="stp_equity_scheme_div_0">
                                                                <select class="form-control schemecode_id" id="stp_equity_scheme_0" name="stp_equity_scheme[]" onchange="changeSTP('stp_equity_scheme',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-0" id="stp_equity_scheme_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="stp_equity_scheme_name_0" maxlength="500" name="stp_equity_scheme_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_equity_assumed_return[]" id="stp_equity_assumed_return_0" onkeyup="changeSTP('stp_equity_assumed_return',0,0);">
                                                    </td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0">
                                                                <select class="form-control schemecode_id" id="stp_mode_0" name="stp_mode[]" onchange="changeSTP('stp_mode',0,0);">   
                                                                    <option value="Appreciation" selected="selected">Appreciation</option>
                                                                    <option value="Fixed Amount">Fixed Amount</option>
                                                                </select>
                                                                <b class="schemeSelectNotch"></b>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0">
                                                                <select class="form-control schemecode_id" id="stp_frequency_0" name="stp_frequency[]" onchange="changeSTP('stp_frequency',0,0);">   
                                                                    <option value="52">Weekly</option>
                                                                    <option value="26">Fortnightly</option>
                                                                    <option value="12" selected="selected">Monthly</option>
                                                                    <option value="4">Quarterly</option>
                                                                    <option value="2">Half-Yearly</option>
                                                                    <option value="1">Yearly</option>
                                                                </select>
                                                                <b class="schemeSelectNotch"></b>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_no_of_frequency[]" id="stp_no_of_frequency_0" onkeyup="changeSTP('stp_no_of_frequency',0,0);">
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_investment_period[]" id="stp_investment_period_0" onkeyup="changeSTP('stp_investment_period',0,0);">
                                                        <em id="stp_investment_period_error_0" class="error" style="position:static; text-align: left; background: none; display:block;"></em>
                                                    </td>
                                                    <td>
                                                        <input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_amount[]" id="stp_amount_0" readonly="readonly" onkeyup="changeSTP('stp_amount',0,0);">
                                                        <em id="stp_amount_error_0" class="error" style="position:static; text-align: left; background: none; display:block;"></em>
                                                    </td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="stp_expected_future_value[]" id="stp_expected_future_value_0" readonly="readonly"></td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSTP('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                    </td>
                                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="AddMoreSTP();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="stp_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle">SWP</div>
                                                    <input type="checkbox" name="swp_checkbox" id="swp_checkbox" onchange="changeSWPCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" >
                                        <div class="invbox">
                                            <div class="row">
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Total Investment Amount</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="swp_total_investment_amount" class="form-control" id="swp_total_investment_amount" value="" onkeyup ="changeSwpCalculation();">
                                                            <div class="cal-icon ">
                                                                ₹
                                                            </div>
                                                            <em id="swp_total_investment_amount_error" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Assumed Rate of Return</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="swp_assumed_rate_of_return" class="form-control" id="swp_assumed_rate_of_return" value="" onkeyup ="changeSwpCalculation();">
                                                            <div class="cal-icon">
                                                                %
                                                            </div>
                                                            <em id="swp_assumed_rate_of_return_error" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">SWP Frequency</label>
                                                        <div class="col-sm-8">
                                                            <select class="form-control" id="swp_frequency" name="swp_frequency" onchange ="changeSwpCalculation();">   
                                                                <option value="Weekly">Weekly</option>
                                                                <option value="Fortnightly">Fortnightly</option>
                                                                <option value="Monthly" selected="selected">Monthly</option>
                                                            </select>
                                                            <em id="swp_frequency_error" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">SWP Period</label>
                                                        <div class="col-sm-4">
                                                            <input type="number" name="swp_period_year" id="swp_period_year" class="form-control" value="" onkeyup ="changeSwpCalculation();">
                                                            <div class="cal-icon">
                                                               Yrs
                                                            </div>
                                                            <em id="swp_period_year_error" class="error"></em>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input type="number" name="swp_period_month" id="swp_period_month" class="form-control" value="" onkeyup ="changeSwpCalculation(); changeMonthValue();">
                                                            <div class="cal-icon" style="width:70px;">
                                                               Months
                                                            </div>
                                                            <em id="swp_period_month_error" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Required End Value</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="swp_required_end_value" id="swp_required_end_value" class="form-control" value="" onkeyup ="changeSwpCalculation();">
                                                            <div class="cal-icon">
                                                                ₹
                                                            </div>
                                                            <em id="swp_required_end_value_error" class="error"></em>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12"></div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">SWP Amount</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="swp_in_amount" id="swp_in_amount" class="form-control" value="" onkeyup ="changeSwpCalculation(); changeSWPAmount(3);" style="width: calc(100% - 117px); margin-left:117px;">
                                                            <div class="cal-icon" style="width: 117px;left: 16px;border: 1px solid #cacaca;padding-right: 0;top: 0;">
                                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="swp_type_amount" id="swp_type_amount" value="1" onchange="changeSwpCalculation(); changeSWPAmount(1);"  checked>
                                                                <label class="form-check-label" style="margin-bottom:0;" for="inlineRadio1">In Amount</label>
                                                            </div>
                                                            <div class="cal-icon">
                                                                ₹
                                                            </div>
                                                            <em id="swp_in_amount_error" class="error"></em>
                                                            <div id="swp_total_investment_amount_message"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="col-sm-8">
                                                        <input type="hidden" name="swp_in_amount_hide" id="swp_in_amount_hide">
                                                        <input type="text" name="swp_in_percent" id="swp_in_percent" class="form-control" value="" onkeyup ="changeSwpCalculation(); changeSWPAmount(4);" style="width: calc(100% - 117px); margin-left:117px;" readonly="true">
                                                        <div class="cal-icon" style="width: 117px;left: 16px;border: 1px solid #cacaca;padding-right: 0;top: 0;">
                                                            <input class="form-check-input fixed_deposit_chk" type="radio" name="swp_type_amount" id="swp_type_percent" value="2"  onchange="changeSwpCalculation();  changeSWPAmount(2);" >
                                                            <label class="form-check-label" for="inlineRadio2">In %</label>
                                                        </div>
                                                        <div class="cal-icon">
                                                                %
                                                        </div>
                                                        <em id="swp_in_percent_error" class="error"></em>
                                                        <div id="swp_total_investment_percent_message"></div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6" style="margin-top: 10px">
                                                    <div class="form-group row">
                                                        <label class="col-sm-4 col-form-label">Actual End Value</label>
                                                        <div class="col-sm-8">
                                                            <input type="text" name="swp_actual_end_value" id="swp_actual_end_value" class="form-control" value="" readonly="readonly">
                                                            <div class="cal-icon">
                                                                ₹
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="table-responsive" style="margin-top: 20px">
                                            <table id="nonMfTable" class="table portfolioTopTable mfCalcTable nonMfCalcTable width01 mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle" style="width: 15%;">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="swp_investor_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investor
                                                    </th>
                                                    <th class="headstyle" style=""><br>Scheme</th>
                                                    <th class="headstyle"style="">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="swp_category_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Category
                                                    </th>
                                                    <th class="headstyle" style="width: 20%;"><br>Amount</th>
                                                    <th class="headstyle"style="width: 5%;">Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="swp_div" class="spaceless">
                                                  <tr id="swp_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="swp_investor[]" id="swp_investor_0" onkeyup="changeSWP('swp_investor',0,0);"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="swp_schemecode_id_div_0">
                                                                <select class="form-control schemecode_id" id="swp_schemecode_id_0" name="swp_schemecode_id[]" onchange="changeSWP('swp_schemecode_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($scheme_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->schemecode;?>"><?php echo $value->s_name;?></option>
                                                                    <?php }?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group mb-0" id="swp_schemecode_id_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="swp_schemecode_name_0" maxlength="500" name="swp_schemecode_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td id="swp_category_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;"  message="text" maxlength="500" name="swp_amount[]" id="swp_amount_0" onkeyup="changeSWP('swp_amount',0,0);"></td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeSWP('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="AddMoreSWP();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="swp_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck" style="justify-content: center;">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle" style="font-size: 19px; padding-bottom: 12px;">NON MF Products</div>
                                                    <input type="checkbox" name="non_mf_product_checkbox" id="non_mf_product_checkbox" onchange="changeNonMfProductCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="nonMfTable" class="table portfolioTopTable mfCalcTable nonMfCalcTable width01 mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="non_mf_product_investor_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Investor
                                                    </th>
                                                    <th >Product</th>
                                                    <th >Scheme / Company</th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="non_mf_product_amount_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Amount
                                                    </th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="non_mf_product_remark_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Remarks
                                                    </th>
                                                    <th >Attach Scheme&nbsp;Detail</th>
                                                    <th >Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="non_mf_product_div" class="spaceless">
                                                  <tr id="non_mf_product_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="non_mf_product_inverstor[]"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="non_mf_product_id_div_0">
                                                                <select class="form-control schemecode_id" id="non_mf_product_id_0" name="non_mf_product_id[]" onchange="changeNonMfProducts('non_mf_product_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($product_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <b class="schemeSelectNotch"></b>
                                                            </div>
                                                            <div class="form-group mb-0" id="non_mf_product_id_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="product_name_0" maxlength="500" name="product_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="40" name="non_mf_product_company[]" id="non_mf_product_company_0" ></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="non_mf_product_amount[]" id="non_mf_product_amount_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="100" name="non_mf_product_remark[]" id="non_mf_product_remark_0"></td>      
                                                    <td>
                                                        <label class="allocationCheck black">
                                                            <input type="checkbox" checked="checked" name="non_mf_product_attach[0]" id="non_mf_product_attach_0">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeNonMfProducts('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="AddMoreNonMfProducts();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="non_mf_products_view">
                                                
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck" style="justify-content: center;">
                                            <div class="allocationSegment">
                                                <label class="allocationCheck">
                                                    <div class="allocationSegmentTitle" style="font-size: 19px; padding-bottom: 12px;">Insurance Product</div>
                                                    <input type="checkbox" name="insurance_product_checkbox" id="insurance_product_checkbox" onchange="changeInsuranceProductCheckbox();">
                                                    <span class="checkmark" style="left:0; top:0;"></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="insuranceTable" class="table portfolioTopTable mfCalcTable nonMfCalcTable insuranceCalcTable width01 mb-0">
                                                <thead>
                                                  <tr>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="insurance_product_insured_name_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Insured Name
                                                    </th>
                                                    <th >Product</th>
                                                    <th >Scheme / Company</th>
                                                    <th >Sum Assured</th>
                                                    <th >Annual Premium</th>
                                                    <th class="headstyle">
                                                        <label class="allocationCheck">
                                                            <input type="checkbox" checked="checked" name="insurance_product_remark_checkbox" value="1">
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <br>
                                                        Remarks
                                                    </th>
                                                    <th >Action</th>
                                                  </tr>
                                                </thead>
                                                <tbody id="insurance_product_div" class="spaceless">
                                                  <tr id="insurance_product_tr_0" style="line-height: 15px;">
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="insurance_product_investor[]" id="insurance_product_investor"></td>
                                                    <td>
                                                        <div class="allocationSegment mfTableCheck">
                                                            <div class="form-group mb-0" id="insurance_product_type_div_0">
                                                                <select class="form-control schemecode_id" id="insurance_product_type_id_0" name="insurance_product_type_id[]" onchange="changeInsuranceProduct('insurance_product_type_id',0,0);">
                                                                    <option value="">Select</option>
                                                                    <option value="0">Custom</option>
                                                                    <?php foreach ($product_type_list as $key => $value) { ?>
                                                                        <option value="<?php echo $value->id;?>"><?php echo $value->name;?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <b class="schemeSelectNotch"></b>
                                                            </div>
                                                            <div class="form-group mb-0" id="insurance_product_type_d_0" style="display: none;">
                                                                <input type="text" value="" style="width: 100%;border: none;" id="product_type_name_0" maxlength="500" name="product_type_name[]">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="40" name="insurance_product_company[]" id="insurance_product_company_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="insurance_product_sum_assured[]" id="insurance_product_sum_assured_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="500" name="insurance_product_annual_premium[]" id="insurance_product_annual_premium_0"></td>
                                                    <td><input type="text" value="" style="width: 100%;border: none;" maxlength="100" name="insurance_product_remark[]" id="insurance_product_remark_0"></td>
                                                    <td bgcolor="#f0f1f6" id="" style="color:red;text-align: center;" onclick="removeInsuranceProduct('0');">
                                                         <i class="fa fa-trash" aria-hidden="true" style="font-size: 18px;"></i> 
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </div>
                                        <div class="allSchemeBtn calcucatorBtn">
                                            <button type="button" class="btn btn-success btn-sm savedBtn px-3" onclick="addMoreInsuranceProduct();">Add More</button>
                                        </div>
                                        <div style="position: absolute;top: -5px;left: 0;right: 0;bottom: 0;background-color: black;opacity: 0%;" id="insurance_product_view">
                                                
                                        </div>
                                    </div>
                                </div>

                                 <div class="row px-3">
                                    <div class="col-md-12">
                                        <div class="calcucatorCheck">
                                            <div class="">
                                                <label class="allocationCheck black" style="padding-left: 12px;margin-left: 5px;">
                                                    <input type="checkbox" name="is_comment" id="is_comment" value="1">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <label class="form-check-label" for="is_comment" style="vertical-align: text-bottom; font-weight:500;">Add Comment</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12" id="comment_view" style="display: none;">
                                        <div class="table-responsive">
                                            <textarea style="width: 100%;border: none;border: 1px solid #cacaca;" maxlength="500" rows="3" name="comment" id="comment"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row px-3">
                                    <div class="col-md-12">
                                        @include('frontend.calculators.suggested.form')
                                    </div>

                                    
                                    
                                    <div class="col-md-12 text-center allSchemeBtn calsSubmitBtn border-0 pb-3">
                                      <button type="submit" class="btn btn-success btn-sm savedBtn">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/images/shape2.png" alt="" />
        </div>
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveModalData();">SAVE</button>
            </div>
        </div>
      </div>
    </div>

@endsection
