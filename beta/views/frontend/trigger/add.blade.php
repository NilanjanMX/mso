@extends('layouts.frontend')

@section('js_after')
    
    <script type="text/javascript">

        var scheme_list = <?php echo json_encode($scheme_list);?>;
        var mf_scheme_list = <?php echo json_encode($scheme_list);?>;
        var index_list = <?php echo json_encode($index_list);?>;
        var fund_house_list = <?php echo json_encode($fund_house_list);?>;
        var category_list = <?php echo json_encode($category_list);?>;
        var plan_list = <?php echo json_encode($plan_list);?>;
        var return_list = <?php echo json_encode($return_list);?>;
        var quant_list = <?php echo json_encode($quant_list);?>;

        var glob_trigger_value = 2;

        $.fn.select2.defaults.set('matcher', function(params, data) {
            
            if ($.trim(params.term) === '') {
                return data;
            }

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
        
        $('.select_index_id').select2({
            placeholder: "Select Index",
        });
        
        $('.select_quant').select2({
            placeholder: "Select Quant",
        });
        
        $('.category_id').select2({
            placeholder: "Select Category",
        });

        function isOnlyNumber(e) {
            const pattern = /^[0-9.]$/;
            return pattern.test(e.key )
        }

        $('#investment_date').datepicker({
            format: 'dd/mm/yyyy',
            endDate: '-1d',
            autoclose: true
        }).on('changeDate', function(){
            console.log($(this).val());

        });

        function renderPlan(){
            var iHtml = `<option value="">Select</option>`;
            plan_list.forEach(function(val){
                if(!val.planname){
                    val.planname = val.plan;
                }
                iHtml = iHtml+`<option value="`+val.plan_code+`">`+val.planname+`</option>`;
            });
            document.getElementById("plan_id").innerHTML = iHtml;
        }

        function renderQuant(){
            var iHtml = `<option value="">Select</option>`;
            quant_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.key_name+`">`+val.name+`</option>`;
            });

            document.getElementById("select_quant").innerHTML = iHtml;

            $('.select_quant').select2({
                placeholder: "Select Quant",
            });
        }

        function renderPeriod(){
            var iHtml = `<option value="">Select</option>`;
            return_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.key_name+`">`+val.name+`</option>`;
            });

            document.getElementById("period_id").innerHTML = iHtml;

            $('.period_id').select2({
                placeholder: "Select Period",
            });
        }

        function renderScheme(){
            var iHtml = `<option value="">Select</option>`;
            scheme_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.schemecode+`">`+val.s_name+`</option>`;
            });

            document.getElementById("scheme_id").innerHTML = iHtml;

            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function renderMfScheme(){
            var iHtml = `<option value="">Select</option>`;
            mf_scheme_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.schemecode+`">`+val.s_name+`</option>`;
            });

            console.log(mf_scheme_list)

            document.getElementById("mf_scheme_id").innerHTML = iHtml;

            $('.schemecode_id').select2({
                placeholder: "Select Fund",
            });
        }

        function renderIndex(){
            var iHtml = `<option value="">Select</option>`;
            index_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.index_code+`">`+val.index_name+`</option>`;
            });

            document.getElementById("select_index").innerHTML = iHtml;

            $('.select_index_id').select2({
                placeholder: "Select Index",
            });
        }

        function renderAum(){
            var iHtml = `<option value="">Select</option>`;
            fund_house_list.forEach(function(val){
                iHtml = iHtml+`<option value="`+val.amc_code+`">`+val.fund+`</option>`;
            });

            document.getElementById("select_amc").innerHTML = iHtml;

            $('.select_amc_id').select2({
                placeholder: "Select AUM",
            });
        }

        function renderCategory(){
            var iHtml = `<option value="">Select</option>`;
            var category_name = "";
            category_list.forEach(function(val){
                category_name = val.class_name;
                if(!val.class_name){
                    category_name = val.classname;
                }
                iHtml = iHtml+`<option value="`+val.classcode+`">`+category_name+`</option>`;
            });

            document.getElementById("category_id").innerHTML = iHtml;

            $('.category_id').select2({
                placeholder: "Select Category",
            });
        }

        function checkNavValidation(){

            var return_value = true;

            var trigger_type = document.getElementById("trigger_type").value;
            var amount = document.getElementById("amount").value;
            var base_nav = document.getElementById("base_nav").value;
            var increase_decrease;
            var appreciation = document.getElementById("appreciation").value;
            var current_nav = document.getElementById("current_nav").value;

            var trigger_condition = document.getElementById("trigger_condition").value;

            console.log(amount,base_nav,appreciation,current_nav,trigger_condition,glob_trigger_value);

            var trigger_value;
            if($("#trigger_value_amount").attr('checked')){
                trigger_value = 2;
            }else{
                trigger_value = 1
            }

            console.log(trigger_value);

            if(trigger_value == 1){
                if($("#increase_decrease_i").attr('checked')){
                    increase_decrease = 1;
                }else{
                    increase_decrease = 2;
                }
                base_nav = parseFloat(base_nav);
                appreciation = parseFloat(appreciation);
                current_nav = parseFloat(current_nav);

                var base_nav_val;

                if(increase_decrease == 1){
                    base_nav_val = base_nav + (base_nav * appreciation / 100);
                }else{
                    base_nav_val = base_nav - (base_nav * appreciation / 100);
                }

                console.log(base_nav_val);

                if(increase_decrease == 2){
                    if(base_nav_val > current_nav){
                        return_value = false;
                        document.getElementById("appreciation_em").innerHTML = "Max "+current_nav;
                    }
                }else{
                    if(base_nav_val < current_nav){
                        return_value = false;
                        document.getElementById("appreciation_em").innerHTML = "Min "+current_nav;
                    }
                }

            }else if(trigger_value == 2){
                amount = parseFloat(amount);
                current_nav = parseFloat(current_nav);
                console.log(amount);
                if(trigger_condition == 2){
                    if(amount > current_nav){
                        return_value = false;
                        document.getElementById("amount_em").innerHTML = "Max "+current_nav;
                    }
                }else{
                    if(amount < current_nav){
                        return_value = false;
                        document.getElementById("amount_em").innerHTML = "Min "+current_nav;
                    }
                }
            }

            return return_value;
        }

        function formValidation() {

            document.getElementById("trigger_type_em").innerHTML = "";
            document.getElementById("trigger_name_em").innerHTML = "";
            document.getElementById("scheme_em").innerHTML = "";
            document.getElementById("current_nav_em").innerHTML = "";
            document.getElementById("select_index_em").innerHTML = "";
            document.getElementById("specific_aum_em").innerHTML = "";
            document.getElementById("select_amc_em").innerHTML = "";
            document.getElementById("select_index_em").innerHTML = "";
            document.getElementById("mf_scheme_em").innerHTML = "";
            document.getElementById("plan_id_em").innerHTML = "";
            document.getElementById("category_id_em").innerHTML = "";
            document.getElementById("period_id_em").innerHTML = "";
            document.getElementById("select_quant_em").innerHTML = "";

            document.getElementById("trigger_condition_em").innerHTML = "";
            document.getElementById("amount_em").innerHTML = "";
            document.getElementById("base_nav_em").innerHTML = "";
            document.getElementById("appreciation_em").innerHTML = "";

            var successFlag = true;

            var data = {};
            data.trigger_type = document.getElementById("trigger_type").value;
            data.trigger_name = document.getElementById("trigger_name").value;
            data.scheme = document.getElementById("scheme_id").value;
            data.current_nav = document.getElementById("current_nav").value;
            data.select_index = document.getElementById("select_index").value;
            data.specific_aum = document.getElementById("specific_aum").value;
            data.select_amc = document.getElementById("select_amc").value;
            data.select_index = document.getElementById("select_index").value;
            data.mf_scheme_id = document.getElementById("mf_scheme_id").value;
            data.plan_id = document.getElementById("plan_id").value;
            data.category_id = document.getElementById("category_id").value;
            data.period_id = document.getElementById("period_id").value;
            data.select_quant = document.getElementById("select_quant").value;

            data.trigger_condition = document.getElementById("trigger_condition").value;

            if(!data.trigger_type){
                document.getElementById("trigger_type_em").innerHTML = "Required";
                successFlag = false;
            }
            if(!data.trigger_name){
                document.getElementById("trigger_name_em").innerHTML = "Required";
                successFlag = false;
            }

            if(data.trigger_type == "scheme-performance-advance-trigger"){
                if(!data.trigger_condition){
                    document.getElementById("trigger_condition_em").innerHTML = "Required";
                    successFlag = false;
                }
                data.amount = document.getElementById("amount").value;
                if(!data.amount){
                    document.getElementById("amount_em").innerHTML = "Required";
                    successFlag = false;
                }
            }else{
                if(!data.current_nav){
                    document.getElementById("current_nav_em").innerHTML = "Required";
                    successFlag = false;
                }

                if($("#trigger_value_amount").attr('checked')){
                    if(!data.trigger_condition){
                        document.getElementById("trigger_condition_em").innerHTML = "Required";
                        successFlag = false;
                    }
                    data.amount = document.getElementById("amount").value;
                    if(!data.amount){
                        document.getElementById("amount_em").innerHTML = "Required";
                        successFlag = false;
                    }
                    console.log(1);
                }

                if($("#trigger_value_percentage").attr('checked')){
                    data.base_nav = document.getElementById("base_nav").value;
                    data.appreciation = document.getElementById("appreciation").value;
                    if(!data.base_nav){
                        document.getElementById("base_nav_em").innerHTML = "Required";
                        successFlag = false;
                    }
                    if(!data.appreciation){
                        document.getElementById("appreciation_em").innerHTML = "Required";
                        successFlag = false;
                    }
                }
            }

            console.log(successFlag);
            
            if(successFlag){
                successFlag = checkNavValidation();
            }

            console.log(successFlag);

            console.log(data);

            return successFlag;
        }

        function changeTriggerValue(type){
            
            document.getElementById("trigger_value_amount_div").style.display = "none";
            document.getElementById("trigger_value_percentage_div").style.display = "none";
            
            if(type == 1){
                document.getElementById("trigger_value_amount_div").style.display = "block";
            }else{
                document.getElementById("trigger_value_percentage_div").style.display = "block";
            }

            glob_trigger_value = type;

            changeValue("trigger_value_amount");
        }

        function changeRemarks(){
            var remarks = document.getElementById('remarks').value;
            document.getElementById("remarks_count_div").innerHTML = remarks.length+"/200";
        }

        function changeTriggerType(){
            var trigger_type = document.getElementById('trigger_type').value;

            document.getElementById("specific_aum_div").style.display = "none";
            document.getElementById("select_amc_div").style.display = "none";
            document.getElementById("select_index_div").style.display = "none";
            document.getElementById("scheme_id_div").style.display = "none";
            document.getElementById("plan_id_div").style.display = "none";
            document.getElementById("category_id_div").style.display = "none";
            document.getElementById("period_id_div").style.display = "none";
            document.getElementById("current_nav_div").style.display = "none";
            document.getElementById("select_quant_div").style.display = "none";
            document.getElementById("mf_scheme_id_div").style.display = "none";
            document.getElementById("investment_date_div").style.display = "none";
            document.getElementById("return_type_div").style.display = "none";
            document.getElementById("trigger_value_div").style.display = "none";

            if(trigger_type == "nav-trigger"){
                document.getElementById("amount_span").innerHTML = "NAV";
                document.getElementById("base_nav_span").innerHTML = "NAV";
                document.getElementById("trigger_value_amount_span").innerHTML = "NAV";
                document.getElementById("current_nav_span").innerHTML = "NAV";

                document.getElementById("scheme_id_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                getApiData(1);
            }else if(trigger_type == "index-trigger"){
                document.getElementById("amount_span").innerHTML = "INDEX";
                document.getElementById("base_nav_span").innerHTML = "INDEX";
                document.getElementById("trigger_value_amount_span").innerHTML = "INDEX";
                document.getElementById("current_nav_span").innerHTML = "INDEX";

                document.getElementById("select_index_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                getApiData(2);
            }else if(trigger_type == "aum-trigger"){

                document.getElementById("amount_span").innerHTML = "AUM";
                document.getElementById("base_nav_span").innerHTML = "AUM";
                document.getElementById("trigger_value_amount_span").innerHTML = "AUM";
                document.getElementById("current_nav_span").innerHTML = "AUM";

                document.getElementById("specific_aum_div").style.display = "block";
                document.getElementById("scheme_id_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                getApiData(3);

            }else if(trigger_type == "category-performance-trigger"){
                document.getElementById("amount_span").innerHTML = "Return";
                document.getElementById("base_nav_span").innerHTML = "Return";
                document.getElementById("trigger_value_amount_span").innerHTML = "Return";
                document.getElementById("current_nav_span").innerHTML = "Return";

                document.getElementById("plan_id_div").style.display = "block";
                document.getElementById("category_id_div").style.display = "block";
                document.getElementById("period_id_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                renderPeriod();
                renderPlan();
                getApiData(4);
                
            }else if(trigger_type == "scheme-performance-trigger"){
                document.getElementById("amount_span").innerHTML = "Return";
                document.getElementById("base_nav_span").innerHTML = "Return";
                document.getElementById("trigger_value_amount_span").innerHTML = "Return";
                document.getElementById("current_nav_span").innerHTML = "Return";

                document.getElementById("mf_scheme_id_div").style.display = "block";
                document.getElementById("period_id_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                renderPeriod();
                getApiData(5);
            }else if(trigger_type == "quants-trigger"){
                document.getElementById("amount_span").innerHTML = "Value";
                document.getElementById("base_nav_span").innerHTML = "Value";
                document.getElementById("trigger_value_amount_span").innerHTML = "Value";
                document.getElementById("current_nav_span").innerHTML = "Value";

                document.getElementById("mf_scheme_id_div").style.display = "block";
                document.getElementById("select_quant_div").style.display = "block";
                document.getElementById("current_nav_div").style.display = "block";
                document.getElementById("trigger_value_div").style.display = "block";

                renderQuant();
                getApiData(5);
            }else if(trigger_type == "scheme-performance-advance-trigger"){
                document.getElementById("amount_span").innerHTML = "Return";

                document.getElementById("investment_date_div").style.display = "block";
                document.getElementById("mf_scheme_id_div").style.display = "block";
                document.getElementById("return_type_div").style.display = "block";

                changeTriggerValue(1);
                getApiData(5);
            }else{

            }
            document.getElementById("current_nav").value = "";
            document.getElementById("base_nav").value = "";
        }

        function changeSpecificAMC() {
            var specific_aum = document.getElementById("specific_aum").value;

            document.getElementById("scheme_id_div").style.display = "none";
            // document.getElementById("current_nav_div").style.display = "none";
            document.getElementById("select_amc_div").style.display = "none";

            if(specific_aum == "Scheme AUM"){
                document.getElementById("scheme_id_div").style.display = "block";
                renderScheme();
                document.getElementById("current_nav").value = "";
                document.getElementById("base_nav").value = "";
            }else if(specific_aum == "AMC AUM"){
                document.getElementById("select_amc_div").style.display = "block";
                renderAum();
                document.getElementById("current_nav").value = "";
                document.getElementById("base_nav").value = "";
            }
        }

        function getApiData(type){
            if(type == 1){
                if(scheme_list.length){
                    renderScheme();
                    return false;
                }
            }else if(type == 2){
                if(index_list.length){
                    renderIndex();
                    return false;
                }
            }else if(type == 3){
                if(fund_house_list.length){
                    renderAum();
                    return false;
                }
            }else if(type == 4){
                if(category_list.length){
                    renderCategory();
                    return false;
                }
            }else if(type == 5){
                if(mf_scheme_list.length){
                    renderMfScheme();
                    return false;
                }
            }else{
                return false;
            }

            $.ajax({
                url: "{{ url('/trigger/get-data') }}",
                method: 'get',
                data: {"type":type},
                success: function (result) {
                    if(type == 1){
                        scheme_list = result.scheme_list;
                        renderScheme();
                    }else if(type == 2){
                        index_list = result.index_list;
                        renderIndex();
                    }else if(type == 3){
                        fund_house_list = result.fund_house_list;
                        renderAum();
                    }else if(type == 4){
                        category_list = result.category_list;
                        renderCategory();
                    }else if(type == 5){
                        mf_scheme_list = result.scheme_list;
                        renderMfScheme();
                    }
                }
            });
        }

        function changeValue(index){
            var data = {};
            var trigger_type = document.getElementById("trigger_type").value;
            var trigger_name = document.getElementById("trigger_name").value;
            var trigger_name = document.getElementById("specific_aum").value;
            var select_amc = document.getElementById("select_amc").value;
            var select_index = document.getElementById("select_index").value;
            var scheme = document.getElementById("scheme_id").value;
            var mf_scheme_id = document.getElementById("mf_scheme_id").value;
            var plan_id = document.getElementById("plan_id").value;
            var category_id = document.getElementById("category_id").value;
            var period_id = document.getElementById("period_id").value;
            var select_quant = document.getElementById("select_quant").value;

            console.log(trigger_type);
            console.log(index);

            if(trigger_type == "nav-trigger"){
                if(index == "scheme_id"){
                    var scheme_detail = scheme_list.find( o => o.schemecode == scheme);
                    if(scheme_detail){
                        document.getElementById("current_nav").value = parseFloat(scheme_detail.navrs).toFixed(2);
                        document.getElementById("base_nav").value = parseFloat(scheme_detail.navrs).toFixed(2);
                    }else{
                        document.getElementById("current_nav").value = "";
                        document.getElementById("base_nav").value = "";
                    }
                }

            }else if(trigger_type == "index-trigger"){
                if(index == "select_index"){
                    var scheme_detail = index_list.find( o => o.index_code == select_index);
                    console.log(scheme_detail)

                    if(scheme_detail){
                        document.getElementById("current_nav").value = parseFloat(scheme_detail.VALUE).toFixed(2);
                        document.getElementById("base_nav").value = parseFloat(scheme_detail.VALUE).toFixed(2);
                    }else{
                        document.getElementById("current_nav").value = "";
                        document.getElementById("base_nav").value = "";
                    }
                }
            }else if(trigger_type == "aum-trigger"){
                if(index == "select_amc"){
                    document.getElementById("current_nav_span").innerHTML = "AUM (In Lacs)";
                    var scheme_detail = fund_house_list.find( o => o.amc_code == select_amc);
                    console.log(scheme_detail)
                    if(scheme_detail){
                        document.getElementById("current_nav").value = parseFloat(scheme_detail.totalaum).toFixed(2);
                        document.getElementById("base_nav").value = parseFloat(scheme_detail.totalaum).toFixed(2);
                    }else{
                        document.getElementById("current_nav").value = "";
                        document.getElementById("base_nav").value = "";
                    }
                }
                if(index == "scheme_id"){
                    document.getElementById("current_nav_span").innerHTML = "AUM (In Lacs)";
                    $.ajax({
                        url: "{{ url('/trigger/get-data') }}",
                        method: 'get',
                        data: {"type":12,"scheme_id":scheme},
                        success: function (result) {
                            if(result.current){
                                document.getElementById("current_nav").value = parseFloat(result.current).toFixed(2);
                                document.getElementById("base_nav").value = parseFloat(result.current).toFixed(2);
                            }else{
                                document.getElementById("current_nav").value = "";
                                document.getElementById("base_nav").value = "";
                            }
                        }
                    });
                }
            }else if(trigger_type == "category-performance-trigger"){
                document.getElementById("trigger_value_percentage").disabled = false;
                if(index == "plan_id" || index == "category_id" || index == "period_id"){
                    $.ajax({
                        url: "{{ url('/trigger/get-data') }}",
                        method: 'get',
                        data: {"type":10,"plan_id":plan_id,"category_id":category_id,"period_id":period_id},
                        success: function (result) {
                            var current_nav = parseFloat(result.current);
                            if(current_nav <= 0){
                                document.getElementById("trigger_value_percentage").disabled = true;
                            }
                            if(result.current){
                                document.getElementById("current_nav").value = parseFloat(result.current).toFixed(2);
                                document.getElementById("base_nav").value = parseFloat(result.current).toFixed(2);
                            }else{
                                document.getElementById("current_nav").value = "";
                                document.getElementById("base_nav").value = "";
                            }
                        }
                    });
                }
            }else if(trigger_type == "scheme-performance-trigger"){
                document.getElementById("trigger_value_percentage").disabled = false;
                if(index == "mf_scheme_id" || index == "period_id"){
                    $.ajax({
                        url: "{{ url('/trigger/get-data') }}",
                        method: 'get',
                        data: {"type":11,"scheme_id":mf_scheme_id,"period_id":period_id},
                        success: function (result) {
                            var current_nav = parseFloat(result.current);

                            if(current_nav <= 0){
                                document.getElementById("trigger_value_percentage").disabled = true;
                            }
                            if(result.current){
                                document.getElementById("current_nav").value = parseFloat(result.current).toFixed(2);
                                document.getElementById("base_nav").value = parseFloat(result.current).toFixed(2);
                            }else{
                                document.getElementById("current_nav").value = "";
                                document.getElementById("base_nav").value = "";
                            }
                        }
                    });
                }
            }else if(trigger_type == "quants-trigger"){
                document.getElementById("trigger_value_percentage").disabled = false;
                if(index == "mf_scheme_id" || index == "select_quant"){
                    $.ajax({
                        url: "{{ url('/trigger/get-data') }}",
                        method: 'get',
                        data: {"type":11,"scheme_id":mf_scheme_id,"period_id":select_quant},
                        success: function (result) {
                            var current_nav = parseFloat(result.current);

                            if(current_nav <= 0){
                                document.getElementById("trigger_value_percentage").disabled = true;
                            }
                            if(result.current){
                                document.getElementById("current_nav").value = parseFloat(result.current).toFixed(2);
                                document.getElementById("base_nav").value = parseFloat(result.current).toFixed(2);
                            }else{
                                document.getElementById("current_nav").value = "";
                                document.getElementById("base_nav").value = "";
                            }
                        }
                    });
                }
            }else if(trigger_type == "scheme-performance-advance-trigger"){
                if(index == "mf_scheme_id"){

                    var scheme_detail = mf_scheme_list.find( o => o.schemecode == mf_scheme_id);
                    console.log(scheme_detail)
                    var incept_date = scheme_detail.Incept_date.split(" ");

                    $('#investment_date').datepicker('setStartDate', new Date(incept_date[0]));
                }
            }


            current_nav = document.getElementById("current_nav").value;

            var trigger_condition = document.getElementById("trigger_condition").value;
            var amount = document.getElementById("amount").value;
            var base_nav = document.getElementById("base_nav").value;
            var appreciation = document.getElementById("appreciation").value;

            document.getElementById("amount_em").innerHTML = "";
            document.getElementById("appreciation_em").innerHTML = "";

            var trigger_value;
            if($("#trigger_value_amount").attr('checked')){
                trigger_value = 2;
            }else{
                trigger_value = 1
            }

            console.log("current_nav:"+current_nav)
            console.log("trigger_value:"+trigger_value)
            console.log(amount,base_nav,appreciation);

            if(current_nav){
                if(trigger_value == 1){
                    if($("#increase_decrease_i").attr('checked')){
                        increase_decrease = 1;
                    }else{
                        increase_decrease = 2;
                    }
                    if(base_nav && appreciation){
                        base_nav = parseFloat(base_nav);
                        appreciation = parseFloat(appreciation);
                        current_nav = parseFloat(current_nav);

                        var base_nav_val;

                        if(increase_decrease == 1){
                            base_nav_val = base_nav + (base_nav * appreciation / 100);
                        }else{
                            base_nav_val = base_nav - (base_nav * appreciation / 100);
                        }

                        console.log(base_nav_val);

                        if(increase_decrease == 2){
                            if(base_nav_val > current_nav){
                                document.getElementById("appreciation_em").innerHTML = "Max "+current_nav;
                            }
                        }else{
                            if(base_nav_val < current_nav){
                                document.getElementById("appreciation_em").innerHTML = "Min "+current_nav;
                            }
                        }
                    }
                        
                }else if(trigger_value == 2){
                    if(trigger_condition && amount){
                        amount = parseFloat(amount);
                        current_nav = parseFloat(current_nav);
                        console.log(amount);
                        if(trigger_condition == 2){
                            if(amount > current_nav){
                                document.getElementById("amount_em").innerHTML = "Max "+current_nav;
                            }
                        }else{
                            if(amount < current_nav){
                                document.getElementById("amount_em").innerHTML = "Min "+current_nav;
                            }
                        }
                    }
                }
            }
        }

        function changeReturnType(type){

        }

        getApiData(1);

    </script>

@endsection

@section('content')

<style type="text/css">
    .top-tab {
        margin-bottom: 61px;
    }
    /*.newsletter {*/
    /*    margin-top: 104px;*/
    /*    margin-bottom: -24px;*/
    /*}*/
    .stationery-btn .banner-btn {
        padding: 10px 15px !important;
    }
    

    .vidpos02 {
        left: -20px;
        top: 187px;
        width: 100px;
        }
    .vidpos04 {
        left: -53px;
        top: 580px;
    }
    .vidpos03 {
        right: 0;
        left: -30px;
        top: 1000px;
        width: 130px;
    }
    .vidpos05 {
        right: -65px;
        top: 1089px;
        width: 150px;
    }
    .vidpos06 {
        right: -65px;
        top: 530px;
        width: 150px;
    }
    .visp {
        right: -30px;
        top: 520px;
        width: 660px;
    }
    .conferencesTable .table tr:hover {
        background-color: #468ff61c;
        transition: all 0.5s;
    }
    .select2-container--open .select2-dropdown--below {
        border: 1px solid #EFF0F7 !important;
        border-radius: 16px !important;
        margin-top: -20px;
    }
    .select2-container--open .select2-dropdown--above {
        border: 1px solid #EFF0F7 !important;
        border-radius: 16px !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: none !important;
        background: #EFF0F7;
        border-radius: 16px;
        height: 48px;
        font-size: 16px;
        color: #3F3D56;
        font-weight: 300;
        padding: .375rem 50px .375rem 24px;
    }
    .select2-results__option--selectable {
        padding: .375rem 50px .375rem 24px !important;
        color: #3F3D56;
        font-weight: 300;
    }
</style>
<!--<img class="kuchi visp" style="" src="{{asset('')}}img/videopageart.png" alt="" />-->
<img class="kuchi vidpos02" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos03" src="{{asset('')}}img/element.png" alt="" />-->
<!--<img class="kuchi vidpos04" src="{{asset('')}}img/element.png" alt="" />-->
<img class="kuchi vidpos05" src="{{asset('')}}img/element.png" alt="" />
<!--<img class="kuchi vidpos06" src="{{asset('')}}img/element.png" alt="" />-->

<div class="banner bannerForAll container">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item shoppingCartBannaer">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Create your own Custom MSO Triggers</h2>
                    <p>Serve your clients with precision. Set triggers and get reminders for profit booking, buying, selling , switch, etc., based on various parameters.</p>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/tiggerBanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div>

<section class="main-sec bodyResponsive">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="category-box categoryList">
                        @include('frontend.trigger.common')
                    </div>
                </div>
                <div class="col-lg-2"></div>
                @if($count_flag)
                    <!--<div class="col-md-8 offset-2">-->
                    <div class="col-lg-8">
                        <form action="{{route('frontend.trigger_save')}}" method="post" onsubmit="return formValidation();">
                            @csrf
                            
                            <div class="triggerSetting newTrigger newTriggerFormSetup">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="text" class="radioTitle">Trigger Name</label>
                                            <input type="text" class="form-control" id="trigger_name" placeholder="Trigger Name" name="trigger_name" maxlength="40">
                                            <em id="trigger_name_em"></em>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Trigger Type</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control" id="trigger_type" name="trigger_type" onchange="changeTriggerType()">
                                                @foreach($trigger_list as $key=>$value)
                                                    <option value="{{$value->type}}" @if($value->name == "NAV Trigger") selected="selected" @endif>{{$value->name}}</option>
                                                @endforeach
                                            </select>
                                            <em id="trigger_type_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="specific_aum_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="text" class="radioTitle">AUM Type</label>
                                            <div class="tiggerListDrop">
                                                <select class="form-control" id="specific_aum" name="specific_aum" onchange="changeSpecificAMC()">
                                                    <option value="Scheme AUM" selected="selected">Scheme AUM</option>
                                                    <option value="AMC AUM">AMC AUM</option>
                                                </select>
                                                <em id="specific_aum_em"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="select_amc_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select AMC</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control select_amc_id" id="select_amc" name="select_amc" onchange="changeValue('select_amc');">
                                                <option value="">Select</option>

                                            </select>
                                            <em id="select_amc_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="select_index_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Index</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control select_index_id" id="select_index" name="select_index" onchange="changeValue('select_index');">
                                                <option value="">Select</option>
                                                @foreach($index_list as $key => $value)
                                                    <option value="{{$value->index_code}}">{{$value->index_name}}</option>
                                                @endforeach
                                            </select>
                                            <em id="select_index_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"  id="scheme_id_div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Scheme</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control schemecode_id" id="scheme_id" name="scheme" onchange="changeValue('scheme_id');">
                                                <option value="">Select</option>
                                            </select>
                                            <em id="scheme_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row"  id="mf_scheme_id_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Scheme</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control schemecode_id" id="mf_scheme_id" name="mf_scheme" onchange="changeValue('mf_scheme_id');">
                                                <option value="">Select</option>
                                            </select>
                                            <em id="mf_scheme_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="plan_id_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Plan</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control plan_id" id="plan_id" name="plan_id" onchange="changeValue('plan_id');">
                                                <option value="">Select</option>
                                                @foreach ($plan_list as $key=>$result)
                                                    <option value="{{$result->plan_code}}"><?php echo ($result->planname)?$result->planname:$result->plan;?></option>
                                                @endforeach
                                            </select>
                                            <em id="plan_id_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" id="category_id_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Category</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control category_id" id="category_id" name="category_id" onchange="changeValue('category_id');">
                                                <option value="">Select</option>
                                                
                                            </select>
                                            <em id="category_id_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="period_id_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Period</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control period_id" id="period_id" name="period_id" onchange="changeValue('period_id');">
                                                <option value="">Select</option>
                                                @foreach($return_list as $key=>$result)
                                                    <option value="{{$result['key_name']}}">{{$result['name']}}</option>
                                                @endforeach
                                            </select>
                                            <em id="period_id_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="select_quant_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Quant</label>
                                          <div class="tiggerListDrop">
                                            <select class="form-control select_quant" id="select_quant" name="select_quant" onchange="changeValue('select_quant');">
                                                <option value="">Select</option>
                                                @foreach ($quant_list as $key=>$result)
                                                    <option value="{{$result['key_name']}}">{{$result['name']}}</option>
                                                @endforeach
                                            </select>
                                            <em id="select_quant_em"></em>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="investment_date_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Select Investment Date</label>
                                          <input type="text" class="form-control" id="investment_date" placeholder="" name="investment_date">
                                          <em id="investment_date_em"></em>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="current_nav_div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="text" class="radioTitle">Current <span id="current_nav_span">NAV</span></label>
                                            <input type="text" class="form-control" id="current_nav" placeholder="Value" name="current_nav" readonly>
                                            <em id="current_nav_em"></em>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="trigger_value_div">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="text" class="radioTitle">Set Trigger Value</label>
                                        </div>
                                        <div class="row triggerValueRow">
                                            <div class="col-md-6 triggerValueCol">
                                                <div class="formCheeckAll">
                                                    <div class="formCheeck">
                                                        <div class="form-group">
                                                            <div class="formCheeck triggerValueOption">
                                                                <label class="displaySettionRadio">
                                                                    <input type="radio" name="trigger_value" id="trigger_value_amount" value="2" checked="checked" onchange="changeTriggerValue(1);">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="radioOption mb-0">Target <span id="trigger_value_amount_span">NAV</span></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 triggerValueCol">
                                                <div class="formCheeckAll">
                                                    <div class="formCheeck">
                                                        <div class="form-group">
                                                            <div class="formCheeck triggerValueOption">
                                                                <label class="displaySettionRadio">
                                                                    <input type="radio" name="trigger_value" id="trigger_value_percentage" value="1" onchange="changeTriggerValue(2);">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="radioOption mb-0">Percentage Wise</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <em id="trigger_value_em"></em>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row" id="return_type_div" style="display: none;">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="text" class="radioTitle">Select Return Type</label>
                                        </div>
                                        <div class="row triggerValueRow">
                                            <div class="col-md-6 triggerValueCol">
                                                <div class="formCheeckAll">
                                                    <div class="formCheeck">
                                                        <div class="form-group">
                                                            <div class="formCheeck triggerValueOption">
                                                                <label class="displaySettionRadio">
                                                                    <input type="radio" name="return_type" id="return_type_absolure" value="1" checked="checked" onchange="changeReturnType(1);">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="radioOption mb-0">Absolute</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 triggerValueCol">
                                                <div class="formCheeckAll">
                                                    <div class="formCheeck">
                                                        <div class="form-group">
                                                            <div class="formCheeck triggerValueOption">
                                                                <label class="displaySettionRadio">
                                                                    <input type="radio" name="return_type" id="return_type_cagr" value="2" onchange="changeReturnType(2);">
                                                                    <span class="checkmark"></span>
                                                                </label>
                                                                <label class="radioOption mb-0">CAGR</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <em id="trigger_value_em"></em>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="trigger_value_amount_div">
                                            <div class="form-group">
                                                <label for="text" class="radioTitle">Trigger Condition</label>
                                                <div class="tiggerListDrop">
                                                    <select class="form-control" id="trigger_condition" name="trigger_condition" onchange="changeValue('trigger_condition');">
                                                        <option value="">Select</option>
                                                        <option value="1" selected>Greater Than Equal To</option>
                                                        <option value="2">Less Than Equal To</option>
                                                    </select>
                                                    <em id="trigger_condition_em"></em>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="text" class="radioTitle">Target <span id="amount_span">NAV</span></label>
                                                <input type="text" class="form-control" id="amount" placeholder="Value" name="amount" onkeyup="changeValue('amount');">
                                                <em id="amount_em"></em>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="trigger_value_percentage_div" style="display: none;">
                                            <div class="form-group">
                                              <label for="text" class="radioTitle">Enter Purchase/Base <span id="base_nav_span">NAV</span></label>
                                              <input type="text" class="form-control" id="base_nav" placeholder="Value" name="base_nav" onkeyup="changeValue('base_nav');">
                                                <em id="base_nav_em"></em>
                                            </div>
                                            <div class="form-group">
                                                <label for="text" class="radioTitle">
                                                    <div class="formCheeckAll inDecForm">
                                                        <div class="formCheeck">
                                                            <div class="form-group">
                                                                <div class="formCheeck triggerValueOption">
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="increase_decrease" id="increase_decrease_i" value="1" checked="checked" onchange="changeValue('increase_decrease');">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="radioOption mb-0">Increase by %</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="formCheeck">
                                                            <div class="form-group">
                                                                <div class="formCheeck triggerValueOption">
                                                                    <label class="displaySettionRadio">
                                                                        <input type="radio" name="increase_decrease" id="increase_decrease_d" value="2" onchange="changeValue('increase_decrease');">
                                                                        <span class="checkmark"></span>
                                                                    </label>
                                                                    <label class="radioOption mb-0">Decrease by %</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <em id="increase_decrease_em"></em>
                                                    </div>
                                                </label>
                                                <input type="text" class="form-control" id="appreciation" placeholder="Percentage Value" name="appreciation"  onkeyup="changeValue('appreciation');" onkeypress="return isOnlyNumber(event)">
                                                <em id="appreciation_em"></em>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                          <label for="text" class="radioTitle">Trigger Notes</label>
                                          <textarea class="form-control h-auto" rows="3" id="remarks" name="remarks" placeholder="" maxlength="200" style="margin-bottom: 0px;" onkeyup="changeRemarks();"></textarea>
                                          <div style="text-align: right;margin-bottom: 25px;font-weight: bold;" id="remarks_count_div">0/200</div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div> 
                            
                            <div class="uploadedLogoBtn triggerSettingBtn" style="display: grid;">
                                <button type="submit" class="btn banner-btn">Set Trigger</button>
                                <button type="submit" class="btn banner-btn whitebg mt-4" style="display: none;">Save In Drafts</button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="col-md-12 text-center text-danger">
                        Maximum no of triggers already added . 
                    </div>
                @endif
            </div>
        </div>
    </section>


@endsection
