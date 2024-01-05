@extends('layouts.frontend')
@section('js_after')

    <script>
        var global_result = {};
        var global_scheme_list = [];
        var global_each_transfer_amount = [];
        var is_first_time = true;

        var global_index = 1;

        var swp_type = 1;

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

        function isNumeric(e) {
            const pattern = /^[0-9.]$/;
            return pattern.test(e.key )
        }

        function isOnlyNumber(e) {
            const pattern = /^[0-9]$/;
            return pattern.test(e.key )
        }

        function setDefaultFun(index){
            $('#investment_date_'+index).datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            }).on('changeDate', function(){
                // set the "toDate" start to not be later than "fromDate" ends:

                console.log($(this).val());
                var incept_dates = $(this).val();
                incept_dates = incept_dates.split("/");
                console.log(incept_dates);
                var incept_date = incept_dates[1]+"/"+incept_dates[0]+"/"+incept_dates[2];
                console.log(incept_date);

                $('#stp_start_date_'+index).datepicker('setStartDate', new Date(incept_date));
            }); 
            $('#stp_start_date_'+index).datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            }).on('changeDate', function(){
                var incept_dates = $(this).val();
                incept_dates = incept_dates.split("/");
                console.log(incept_dates);
                var incept_date = incept_dates[1]+"/"+incept_dates[0]+"/"+incept_dates[2];
                console.log(incept_date);

                $('#stp_end_date_'+index).datepicker('setStartDate', new Date(incept_date));
            });

            $('#stp_end_date_'+index).datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            }).on('changeDate', function(){
                console.log($(this).val());

                var incept_dates = $(this).val();
                incept_dates = incept_dates.split("/");
                console.log(incept_dates);
                var incept_date = incept_dates[1]+"/"+incept_dates[0]+"/"+incept_dates[2];
                console.log(incept_date);

                $('#valuation_date_'+index).datepicker('setStartDate', new Date(incept_date));
            });

            $('#valuation_date_'+index).datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });

            $('.schemecode_id').select2({
                placeholder: "Search Scheme",
            });

            $('.amc_name').select2({
                placeholder: "Search AMC",
            });
        }

        function renderRow(all_data,global_result,index){

            var iHtml1 = ``;

            var balance_units = 0;
            var units = 0;
            var total_withdrawal1 = 0;
            var net_amount = 0;
            var pre_nav = 0;
            var capital_gain_loss = 0;
            var first_date = "";
            var i = 0;

            balance_units = parseFloat(global_result.lumpsum_investment_amount / global_result.from_end_date_data.navrs).toFixed(2);
            net_amount = global_result.lumpsum_investment_amount;
            pre_nav = parseFloat(global_result.from_end_date_data.navrs);
            first_date = new Date(global_result.from_end_date_data.navdate);

            iHtml1 = iHtml1+"<tr>";
            iHtml1 = iHtml1+"<td>"+global_result.from_end_date_data.date+"</td>";
            iHtml1 = iHtml1+"<td>"+global_result.from_end_date_data.navrs+"</td>";
            iHtml1 = iHtml1+"<td>"+global_result.lumpsum_investment_amount+"</td>";
            iHtml1 = iHtml1+"<td>"+balance_units+"</td>";
            iHtml1 = iHtml1+"<td>"+balance_units+"</td>";
            iHtml1 = iHtml1+"<td>"+net_amount+"</td>";
            iHtml1 = iHtml1+"<td>0</td>;"
            iHtml1 = iHtml1+"<td>0</td>";
            iHtml1 = iHtml1+"<td>"+global_result.lumpsum_investment_amount+"</td>";
            iHtml1 = iHtml1+"</tr>";

            global_result.returnDataFrom.forEach(function(val){
                
                units = parseFloat(-(-global_result.cash_flow / val.navrs));
                balance_units = parseFloat(balance_units - units);
                net_amount = parseFloat(net_amount) - parseFloat(global_result.cash_flow);
                capital_gain_loss = units*(val.navrs - pre_nav);
                // pre_nav = parseFloat(val.navrs);

                const date2 = new Date(val.navdate);
                const diffTime = Math.abs(date2 - first_date);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                iHtml1 = iHtml1+"<tr>";
                iHtml1 = iHtml1+"<td>"+val.date+"</td>";
                iHtml1 = iHtml1+"<td>"+val.navrs+"</td>";
                iHtml1 = iHtml1+"<td>-"+global_result.cash_flow+"</td>";
                iHtml1 = iHtml1+"<td>"+parseFloat(units).toFixed(2)+"</td>";
                iHtml1 = iHtml1+"<td>"+parseFloat(balance_units).toFixed(2)+"</td>";
                iHtml1 = iHtml1+"<td>"+parseFloat(net_amount).toFixed(0)+"</td>";
                iHtml1 = iHtml1+"<td>"+parseFloat(capital_gain_loss).toFixed(2)+"</td>";
                iHtml1 = iHtml1+"<td>"+diffDays+"</td>";
                iHtml1 = iHtml1+"<td>"+parseFloat(balance_units * val.navrs).toFixed(2)+"</td>";
                iHtml1 = iHtml1+"</tr>";

                total_withdrawal1 = parseFloat(total_withdrawal1) + parseFloat(balance_units);
                i++;             
            });

            var iHtml2 = ``;
            i = 0;
            global_result.returnDataTo.forEach(function(val){
                iHtml2 = iHtml2+"<tr>";
                iHtml2 = iHtml2+"<td>"+val.date+"</td>";
                iHtml2 = iHtml2+"<td>"+val.navrs+"</td>";
                iHtml2 = iHtml2+"<td>"+global_result.cash_flow+"</td>";
                iHtml2 = iHtml2+"<td>"+parseFloat(val.units).toFixed(2)+"</td>";
                iHtml2 = iHtml2+"<td>"+parseFloat(val.balance_units).toFixed(2)+"</td>";
                iHtml2 = iHtml2+"<td>"+parseFloat(val.amount_invested).toFixed(2)+"</td>";
                iHtml2 = iHtml2+"<td>"+parseFloat(val.market_value).toFixed(2)+"</td>";
                iHtml2 = iHtml2+"</tr>";
                i = i+1;
            });

            var table_html = ``;
            table_html = `<h6>STP Transferor Scheme: `+global_result.from_scheme_details.s_name+`</h6>`;
            table_html = table_html+`<table class="table text-center stock_table mfliketbl">`;
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Amount</td>";
            table_html = table_html+"<td>"+parseInt(global_result.lumpsum_investment_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Date</td>";
            table_html = table_html+"<td>"+global_result.investment_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>"+global_result.data_frequency+" STP Amount</td>";
            table_html = table_html+"<td>"+parseInt(global_result.stp_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>STP Period</td>";
            table_html = table_html+"<td>"+global_result.stp_start_date+" - "+global_result.stp_end_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of Instalments</td>";
            table_html = table_html+"<td>"+global_result.returnDataTo.length+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Amount Transferred</td>";
            table_html = table_html+"<td>"+parseInt(global_result.returnDataTo.length * global_result.cash_flow).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of Units Remaining</td>";
            table_html = table_html+"<td id='number_of_units_remaining_"+index+"'></td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Market Value as on "+global_result.stp_end_date+"</td>";
            table_html = table_html+"<td id='to_market_value_as_on_"+index+"'>"+parseInt(global_result.from_market_value).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</table>";

            table_html = table_html+`<h6>STP Transferee Scheme: `+global_result.to_scheme_details.s_name+`</h6>`;
            table_html = table_html+`<table class="table text-center stock_table mfliketbl">`;
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>"+global_result.data_frequency+" STP Amount</td>";
            table_html = table_html+"<td>"+parseInt(global_result.stp_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>STP Period</td>";
            table_html = table_html+"<td>"+global_result.stp_start_date+" - "+global_result.stp_end_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of Instalments</td>";
            table_html = table_html+"<td>"+global_result.returnDataFrom.length+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Amount Invested</td>";
            table_html = table_html+"<td>"+parseInt(global_result.lumpsum_investment_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of Units Remaining</td>";
            table_html = table_html+"<td id='number_of_units_remaining_"+index+"'></td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Market Value as on "+global_result.stp_end_date+"</td>";
            table_html = table_html+"<td id='from_market_value_as_on_"+index+"'>"+parseInt(global_result.to_market_value).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</table>";

            var total_withdrawal = parseFloat(global_result.to_market_value)+parseFloat(global_result.from_market_value);

            var date1 = new Date(global_result.investment_date1);
            var date2 = new Date(global_result.valuation_date1);
            var diffTime = Math.abs(date2 - date1);
            var number_of_days = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            console.log(number_of_days);

            var daily_return = Math.pow((parseFloat(total_withdrawal)/parseFloat(global_result.lumpsum_investment_amount)),(1/number_of_days))-1;

            console.log(daily_return);

            var cagr_returns = Math.pow((1+parseFloat(daily_return)),(number_of_days))-1;

            // var cagr_returns = "";

            table_html = table_html+`<h6>STP Total Returns:</h6>`;
            table_html = table_html+`<table class="table text-center stock_table mfliketbl">`;
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Investment Amount</td>";
            table_html = table_html+"<td>"+parseInt(global_result.lumpsum_investment_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Market Value as on "+global_result.stp_end_date+"</td>";
            table_html = table_html+"<td id='market_value_as_on_"+index+"'>"+parseInt(total_withdrawal).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Profit</td>";
            table_html = table_html+"<td>"+parseInt(total_withdrawal-global_result.lumpsum_investment_amount).toLocaleString('en-IN')+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>CAGR Returns</td>";
            table_html = table_html+"<td>"+parseFloat(cagr_returns).toFixed(2)+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</table>";

            var i = 0;
            table_html = table_html+'<h6>Transferor Scheme: '+global_result.from_scheme_details.s_name+'</h6>';
            table_html = table_html+'<table id="stock_table1_'+index+'" class="table text-center stock_table mfliketbl">';
            table_html = table_html+"<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<th>NAV Date</th>";
            table_html = table_html+"<th>NAV</th>";
            table_html = table_html+"<th>Cash Flow</th>";
            table_html = table_html+"<th>Units</th>";
            table_html = table_html+"<th>Balance Units</th>";
            table_html = table_html+"<th>Net Amount</th>";
            table_html = table_html+"<th>Capital Gain/Loss</th>";
            table_html = table_html+"<th>No. of Days (Investment)</th>";
            table_html = table_html+"<th>Market Value</th>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";
            table_html = table_html+"<tbody>";

            table_html = table_html+""+iHtml1;

            table_html = table_html+"</tbody>";
            table_html = table_html+'</table>';

            var i = 0;
            table_html = table_html+'<h6>Transferee Scheme: '+global_result.to_scheme_details.s_name+'</h6>';
            table_html = table_html+'<table id="stock_table2_'+index+'" class="table text-center stock_table mfliketbl">';
            table_html = table_html+"<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<th>NAV Date</th>";
            table_html = table_html+"<th>NAV</th>";
            table_html = table_html+"<th>Cash Flow</th>";
            table_html = table_html+"<th>Units</th>";
            table_html = table_html+"<th>Balance Units</th>";
            table_html = table_html+"<th>Amount Invested</th>";
            table_html = table_html+"<th>Market Value</th>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</thead>";
            table_html = table_html+"<tbody>";

            table_html = table_html+""+iHtml2;

            table_html = table_html+"</tbody>";
            table_html = table_html+'</table>';

            

            $("#result_view").append(table_html);

            
        }

        function callAPI(all_data){
            document.getElementById("loading_view").style.display = "block";
            document.getElementById("add_btn_search").disabled = true;
            console.log(all_data);
            $.ajax({
                url: "{{ url('/mf-stp-historical-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    document.getElementById('result_view').innerHTML = "";
                    var i = 0;
                    result.forEach(function(val){
                        renderRow(all_data,val,i);
                        i = i+1;
                    });

                    document.getElementById("download_button").style.display = "block";
                    
                    document.getElementById("loading_view").style.display = "none";
                    document.getElementById("add_btn_search").disabled = false;
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert("Error");
                    document.getElementById("download_button").style.display = "none";
                    document.getElementById("loading_view").style.display = "none";
                    document.getElementById("add_btn_search").disabled = false;
                }
            });
        }

        function searchData(){

            var data = {};
            data._token = "{{ csrf_token() }}";
            data.amc_name = [];
            data.from_scheme = [];
            data.to_scheme = [];
            data.initial_investment_amount = [];
            data.investment_date = [];
            data.each_transfer_amount = [];
            data.select_frequency = [];
            data.stp_start_date = [];
            data.stp_end_date = [];
            data.valuation_date = [];

            var form_list = document.getElementById("comp_sch").querySelectorAll('select[message=text]');
            var date_array = [];
            var array_data = [];
            form_list.forEach(function(val){
                array_data.push(parseInt($(val).attr('datatype')));
            });
            console.log(array_data);
            var flag = true;

            array_data.forEach(function(index){

                console.log(index);

                document.getElementById("amc_name_"+index+"_error").innerHTML = "";
                document.getElementById("from_scheme_"+index+"_error").innerHTML = "";
                document.getElementById("to_scheme_"+index+"_error").innerHTML = "";
                document.getElementById("initial_investment_amount_"+index+"_error").innerHTML = "";
                document.getElementById("investment_date_"+index+"_error").innerHTML = "";
                document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "";
                document.getElementById("select_frequency_"+index+"_error").innerHTML = "";
                document.getElementById("stp_start_date_"+index+"_error").innerHTML = "";
                document.getElementById("stp_start_date_"+index+"_error").innerHTML = "";
                document.getElementById("valuation_date_"+index+"_error").innerHTML = "";

                data.amc_name[index] = document.getElementById("amc_name_"+index).value;
                data.from_scheme[index] = document.getElementById("from_scheme_"+index).value;
                data.to_scheme[index] = document.getElementById("to_scheme_"+index).value;
                data.initial_investment_amount[index] = document.getElementById("initial_investment_amount_"+index).value;
                data.investment_date[index] = document.getElementById("investment_date_"+index).value;
                data.each_transfer_amount[index] = document.getElementById("each_transfer_amount_"+index).value;
                data.select_frequency[index] = document.getElementById("select_frequency_"+index).value;
                data.stp_start_date[index] = document.getElementById("stp_start_date_"+index).value;
                data.stp_end_date[index] = document.getElementById("stp_end_date_"+index).value;
                data.valuation_date[index] = document.getElementById("valuation_date_"+index).value;

                if(!data.amc_name[index]){
                    document.getElementById("amc_name_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.from_scheme[index]){
                    document.getElementById("from_scheme_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.to_scheme[index]){
                    document.getElementById("to_scheme_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.initial_investment_amount[index]){
                    document.getElementById("initial_investment_amount_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.investment_date[index]){
                    document.getElementById("investment_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.investment_date[index].split("/");
                    data.investment_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }
                if(!data.each_transfer_amount[index]){
                    document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.select_frequency[index]){
                    document.getElementById("select_frequency_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.stp_start_date[index]){
                    document.getElementById("stp_start_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.stp_start_date[index].split("/");
                    data.stp_start_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }
                if(!data.stp_end_date[index]){
                    document.getElementById("stp_end_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.stp_end_date[index].split("/");
                    data.stp_end_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }
                if(!data.valuation_date[index]){
                    document.getElementById("valuation_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.valuation_date[index].split("/");
                    data.valuation_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }

            });

            console.log(data);


            if(flag){
                callAPI(data);
            }else{
                return false;
            }
        }

        function addButton(){
            var form_list = document.getElementById("comp_sch").querySelectorAll('select[message=text]');
            console.log(form_list.length);
            if(form_list.length < 3){
                var iHtml = `<div id="stp_form_`+global_index+`" style="padding: 10px;border: 1px solid;margin-top: 10px;">
                                        <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">AMC Name</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input amc_name" name="amc_name[`+global_index+`]" message="text" datatype="`+global_index+`" id="amc_name_`+global_index+`" onchange="changeAMCName(`+global_index+`); changeValidation(`+global_index+`,'amc_name');">
                                                <option value="">Select AMC Name</option>
                                                @foreach($fund_house_list as $key => $value)
                                                    <option value="{{$value->amc_code}}">{{$value->fund}}</option>
                                                @endforeach
                                            </select>
                                            <em id="amc_name_`+global_index+`_error" class="error"></em>  
                                        </div>
                                    </div>
                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="from_scheme" class="col-sm-3 col-form-label">From Scheme</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input schemecode_id" name="from_scheme[`+global_index+`]" id="from_scheme_`+global_index+`" onchange="changeValidation(`+global_index+`,'from_scheme');">
                                                <option value="">Select From Scheme</option>
                                            </select>
                                            <em id="from_scheme_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label" >Incept Date</label>
                                        <div class="col-sm-9">
                                            <div  class="form-control" id="from_incept_date_`+global_index+`" style="padding-top: 9px;"></div> 
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="initial_investment_amount" class="col-sm-3 col-form-label">Initial Investment</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="initial_investment_amount[`+global_index+`]" id="initial_investment_amount_`+global_index+`" class="form-control" value="500000" onkeypress="return isNumeric(event)" onkeyup="formValidation(`+global_index+`,'initial_investment_amount');">
                                            <em id="initial_investment_amount_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">Investment Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="investment_date[`+global_index+`]" id="investment_date_`+global_index+`" value="">
                                            <em id="investment_date_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="to_scheme" class="col-sm-3 col-form-label">To Scheme</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input schemecode_id" name="to_scheme[`+global_index+`]" id="to_scheme_`+global_index+`" onchange="changeValidation(`+global_index+`,'to_scheme');">
                                                <option value="">Select To Scheme</option>
                                            </select>
                                            <em id="to_scheme_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label" >Incept Date</label>
                                        <div class="col-sm-9">
                                            <div  class="form-control" id="to_incept_date_`+global_index+`" style="padding-top: 9px;"></div> 
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="select_frequency" class="col-sm-3 col-form-label">Select Frequency</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input" name="select_frequency[`+global_index+`]" id="select_frequency_`+global_index+`" onchange="changeValidation(`+global_index+`,'select_frequency');">
                                                <option value="">Select Frequency</option>
                                                @foreach($data_frequency as $dropdown)
                                                    <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                                @endforeach
                                            </select>
                                            <em id="select_frequency_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="each_transfer_amount" class="col-sm-3 col-form-label">Each Transfer Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="each_transfer_amount[`+global_index+`]" id="each_transfer_amount_`+global_index+`" class="form-control" value="" onkeypress="return isNumeric(event)" onkeyup="changeValidation(`+global_index+`,'each_transfer_amount');">
                                            <em id="each_transfer_amount_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="stp_start_date" class="col-sm-3 col-form-label">STP Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="stp_start_date[`+global_index+`]" id="stp_start_date_`+global_index+`" class="form-control" value="">
                                            <em id="stp_start_date_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="stp_end_date" class="col-sm-3 col-form-label">STP End Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="stp_end_date[`+global_index+`]" id="stp_end_date_`+global_index+`" class="form-control" value="">
                                            <em id="stp_end_date_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">Valuation Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="valuation_date[`+global_index+`]" id="valuation_date_`+global_index+`" class="form-control" value="">
                                            <em id="valuation_date_`+global_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>
                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <div class="col-sm-12 text-right">
                                            <a href="javascript:void(0);" style="color: red;" onclick="removeFormData(`+global_index+`);">Remove</a>
                                        </div>
                                    </div>
                                </div>`;
                $("#stp_form").append(iHtml);

                setDefaultFun(global_index);
                global_index = global_index + 1;
            }else{
                alert("Max 3");
            }

        }

        function removeFormData(index){
            document.getElementById("stp_form_"+index).remove();
        }

        function changeAMCName(index){
            var amc_code = document.getElementById("amc_name_"+index).value;
            $.ajax({
                url: "{{ url('/get-scheme-amcwise-list') }}/"+amc_code,
                method: 'get',
                success: function (result) {
                    global_scheme_list = result;
                    var schemeHtml = ``;
                    result.forEach(function(val){
                        schemeHtml += `<option value="`+val.schemecode+`">`+val.s_name+`</option>`;
                    });
                    document.getElementById('from_scheme_'+index).innerHTML = `<option value="">Select From Scheme</option>`+schemeHtml;
                    document.getElementById('to_scheme_'+index).innerHTML = `<option value="">Select To Scheme</option>`+schemeHtml;
                }
            });
        }

        function changeValidation(index,id){

            var lumpsum_investment_amount = document.getElementById("initial_investment_amount_"+index).value;
            var data_frequency = document.getElementById("select_frequency_"+index).value;
            var each_transfer_amount = document.getElementById("each_transfer_amount_"+index).value;

            var lumpsum_investment = "";
            if(lumpsum_investment_amount && data_frequency){
                if(data_frequency == "Weekly"){
                    lumpsum_investment = lumpsum_investment_amount*10/100;

                }else if(data_frequency == "Fortnightly"){
                    lumpsum_investment = lumpsum_investment_amount*15/100;

                }else if(data_frequency == "Monthly"){
                    lumpsum_investment = lumpsum_investment_amount*25/100;

                }else if(data_frequency == "Quarterly"){
                    lumpsum_investment = lumpsum_investment_amount*50/100;

                }else if(data_frequency == "Half Yearly"){
                    lumpsum_investment = lumpsum_investment_amount*50/100;

                }else if(data_frequency == "Yearly"){
                    lumpsum_investment = lumpsum_investment_amount*50/100;

                }

                global_each_transfer_amount[index] = lumpsum_investment;

                if(each_transfer_amount){
                    lumpsum_investment = parseFloat(lumpsum_investment);
                    each_transfer_amount = parseFloat(each_transfer_amount);
                    console.log(each_transfer_amount);
                    console.log(lumpsum_investment);
                    if(each_transfer_amount > lumpsum_investment){
                        document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "Each Transfer Amount cannot exceed Rs. "+lumpsum_investment;
                    }else{
                        document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "";
                    }
                }
                
                // document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "Each Transfer Amount cannot exceed Rs. "+lumpsum_investment;
            }

            if(id == "from_scheme"){
                var schemecode = document.getElementById("from_scheme_"+index).value;
                var schemecode_detail = global_scheme_list.find(o => o.schemecode == schemecode );

                var incept_date = "";

                if(schemecode_detail){
                    var incept_dates = schemecode_detail.incept_date;
                    incept_dates = incept_dates.split(" ");
                    console.log(incept_dates);
                    incept_dates = incept_dates[0];
                    console.log(incept_dates);

                    incept_dates = incept_dates.split("-");

                    incept_date = incept_dates[1]+"/"+incept_dates[2]+"/"+incept_dates[0];

                    $('#investment_date_'+index).datepicker('setStartDate', new Date(incept_date));

                    incept_date = incept_dates[2]+"/"+incept_dates[1]+"/"+incept_dates[0];
                    document.getElementById("from_scheme_"+index+"_error").innerHTML = "";
                }else{
                    document.getElementById("from_scheme_"+index+"_error").innerHTML = "Required";
                }

                document.getElementById("from_incept_date_"+index).innerHTML = incept_date;
            }else if(id == "to_scheme"){
                var schemecode = document.getElementById("to_scheme_"+index).value;
                var schemecode_detail = global_scheme_list.find(o => o.schemecode == schemecode );

                var incept_date = "";

                if(schemecode_detail){
                    var incept_dates = schemecode_detail.incept_date;
                    incept_dates = incept_dates.split(" ");
                    console.log(incept_dates);
                    incept_dates = incept_dates[0];
                    console.log(incept_dates);

                    incept_dates = incept_dates.split("-");

                    incept_date = incept_dates[2]+"/"+incept_dates[1]+"/"+incept_dates[0];
                    document.getElementById("to_scheme_"+index+"_error").innerHTML = "";
                }else{
                    document.getElementById("to_scheme_"+index+"_error").innerHTML = "Required";
                }

                document.getElementById("to_incept_date_"+index).innerHTML = incept_date;
            }else if(id == "amc_name"){
                var schemecode = document.getElementById("amc_name_"+index).value;
                if(schemecode){
                    document.getElementById("amc_name_"+index+"_error").innerHTML = "";
                }else{
                    document.getElementById("amc_name_"+index+"_error").innerHTML = "Required";
                }
            }else if(id == "select_frequency"){
                var schemecode = document.getElementById("select_frequency_"+index).value;
                if(schemecode){
                    document.getElementById("select_frequency_"+index+"_error").innerHTML = "";
                }else{
                    document.getElementById("select_frequency_"+index+"_error").innerHTML = "Required";
                }
            }else if(id == "initial_investment_amount"){
                var lumpsum_investment_amount = document.getElementById("initial_investment_amount_"+index).value;
                lumpsum_investment_amount = parseFloat(lumpsum_investment_amount);
                if(lumpsum_investment_amount >= 1000 && lumpsum_investment_amount <= 1000000000){
                    document.getElementById("initial_investment_amount_"+index+"_error").innerHTML = "";
                }else{
                    document.getElementById("initial_investment_amount_"+index+"_error").innerHTML = "Please enter a value between 1,000 - 1,00,00,00,000";
                }
            }else if(id == "each_transfer_amount"){
                // var each_transfer_amount = document.getElementById("each_transfer_amount_"+index).value;
                // each_transfer_amount = parseFloat(each_transfer_amount);
                // lumpsum_investment = global_each_transfer_amount[index];
                // if(lumpsum_investment){
                //     lumpsum_investment = parseFloat(lumpsum_investment);
                //     if(each_transfer_amount >= lumpsum_investment){
                //         document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "Each Transfer Amount cannot exceed Rs. "+lumpsum_investment;
                //     }else{
                //         document.getElementById("each_transfer_amount_"+index+"_error").innerHTML = "";
                //     }
                // }else{

                // }
            }
        }

        setDefaultFun(0);
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
@endsection
@section('content')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400&display=swap" rel="stylesheet">
    <style>
        em.error {
            position: absolute;
            left: 22px;
            top: 33px;
            bottom: -14px;
            background: #fff;
            font-size: 10px;
            line-height: 1;
            color: #f00;
            letter-spacing: 0.5px;
            padding: 0 !important;
            font-weight: 600;
        }
        .form-control {
            border-radius: 0;
            border: 1px solid #cacaca !important;
            min-height: 40px;
            font-size: 12px;
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
            .stock_table thead th {
                background: #bee5f6;
            }
            .table-bordered thead td, .table-bordered thead th {
                border-bottom-width: 1px;
            }
            .table thead th {
                vertical-align: bottom;
                border-bottom: 1px solid #929292;
                font-weight: 600 !important;
            }
            .table-bordered.stock_table {
                border: 1px solid #929292;
            }
            .table-bordered.stock_table td, .table-bordered.stock_table th {
                border: 1px solid #929292;
                line-height: 15px;
                padding: 6px 5px;
                vertical-align: middle;
                font-family: "Poppins", sans-serif;
            }
            .table-bordered.stock_table td {
                padding: 9px 5px;
            }
            .table-bordered.stock_table tr td:first-child {
                text-align: left;
            }
            .table-bordered.stock_table tbody tr:nth-child(even) {background: #f0f1f6}
            .table-bordered.stock_table tbody tr:nth-child(odd) {background: #FFF}
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
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">STP HISTORICAL</h2>
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
                    <form action="{{route('frontend.mf_stp_historical_action')}}" method="post" id="comp_sch" >
                        @csrf
                        <div style="">
                            <div id="stp_form">
                                <div id="stp_form_0" style="padding: 10px;border: 1px solid;margin-top: 10px;">
                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">AMC Name</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input amc_name" name="amc_name[0]" message="text" datatype="0" id="amc_name_0" onchange="changeAMCName(0); changeValidation(0,'amc_name');">
                                                <option value="">Select AMC Name</option>
                                                @foreach($fund_house_list as $key => $value)
                                                    <option value="{{$value->amc_code}}">{{$value->fund}}</option>
                                                @endforeach
                                            </select>
                                            <em id="amc_name_0_error" class="error"></em>  
                                        </div>
                                    </div>
                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="from_scheme" class="col-sm-3 col-form-label">From Scheme</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input schemecode_id" name="from_scheme[0]" id="from_scheme_0" onchange="changeValidation(0,'from_scheme');">
                                                <option value="">Select From Scheme</option>
                                            </select>
                                            <em id="from_scheme_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label" >Incept Date</label>
                                        <div class="col-sm-9">
                                            <div  class="form-control" id="from_incept_date_0" style="padding-top: 9px;"></div> 
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="initial_investment_amount" class="col-sm-3 col-form-label">Initial Investment</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="initial_investment_amount[0]" id="initial_investment_amount_0" class="form-control" value="500000" onkeypress="return isNumeric(event)" onkeyup="formValidation(0,'initial_investment_amount');">
                                            <em id="initial_investment_amount_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">Investment Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="investment_date[0]" id="investment_date_0" value="01/01/2021">
                                            <em id="investment_date_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="to_scheme" class="col-sm-3 col-form-label">To Scheme</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input schemecode_id" name="to_scheme[0]" id="to_scheme_0" onchange="changeValidation(0,'to_scheme');">
                                                <option value="">Select To Scheme</option>
                                            </select>
                                            <em id="to_scheme_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label" >Incept Date</label>
                                        <div class="col-sm-9">
                                            <div  class="form-control" id="to_incept_date_0" style="padding-top: 9px;"></div> 
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="select_frequency" class="col-sm-3 col-form-label">Select Frequency</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input" name="select_frequency[0]" id="select_frequency_0" onchange="changeValidation(0,'select_frequency');">
                                                <option value="">Select Frequency</option>
                                                @foreach($data_frequency as $dropdown)
                                                    <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                                @endforeach
                                            </select>
                                            <em id="select_frequency_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="each_transfer_amount" class="col-sm-3 col-form-label">Each Transfer Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="each_transfer_amount[0]" id="each_transfer_amount_0" class="form-control" value="12500" onkeypress="return isNumeric(event)" onkeyup="changeValidation(0,'each_transfer_amount');">
                                            <em id="each_transfer_amount_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="stp_start_date" class="col-sm-3 col-form-label">STP Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="stp_start_date[0]" id="stp_start_date_0" class="form-control" value="01/03/2021">
                                            <em id="stp_start_date_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="stp_end_date" class="col-sm-3 col-form-label">STP End Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="stp_end_date[0]" id="stp_end_date_0" class="form-control" value="01/12/2021">
                                            <em id="stp_end_date_0_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 10px;">
                                        <label for="" class="col-sm-3 col-form-label">Valuation Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="valuation_date[0]" id="valuation_date_0" class="form-control" value="01/11/2022">
                                            <em id="valuation_date_0_error" class="error"></em>  
                                        </div> 
                                    </div>
                                </div>
                            </div>

                            <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                                <button type="button" class="btn btn-primary add_button" id="add_btn_add" onclick="addButton();" style="margin-right: 20px;">Add</button>
                                <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="searchData();">Submit</button>

                                @if (Auth::check())
                                    @if($permission['is_download'])
                                        <button type="submit" class="btn btn-primary add_button" id="download_button" style="display: none;margin-left: 20px;">Download</button>
                                    @else
                                        <button type="button" class="btn btn-primary add_button" id="download_button" style="display: none;margin-left: 20px;" onclick="openDownloadPermissionModal();">
                                            Download
                                        </button>
                                    @endif
                                @else
                                    <button type="button" class="btn btn-primary add_button" id="download_button" style="display: none;margin-left: 20px;" onclick="openDownloadLoginModal();">
                                        Download
                                    </button>
                                @endif
                            </div>  
                        </div>
                    </form>
                </div>
            </div>

            <div class="row Search_Here" style="margin-top: 20px;">
                <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" id="loading_view" style="display: none; text-align: center;padding-bottom: 10px;">
                    Loading ..
                </div>
                <div class="col-12 col-sm-10 col-md-8 col-lg-8 col-xl-8" id="result_view">
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

    <script type="text/javascript">

        function checkSave(){
            document.getElementById('page_type').value = "SAVE";
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
            return true;
        }
    </script>

@endsection
