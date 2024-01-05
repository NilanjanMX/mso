@extends('layouts.frontend')
@section('js_after')
    <script type = "text/JavaScript" src = "https://MomentJS.com/downloads/moment.js"></script>
    
    <script type="text/javascript">
        function XIRR(values, dates, guess) {
            
          var irrResult = function(values, dates, rate) {
            var r = rate + 1;
            var result = values[0];
            for (var i = 1; i < values.length; i++) {
              result += values[i] / Math.pow(r, moment(dates[i]).diff(moment(dates[0]), 'days') / 365);
            }
            return result;
          }

          // Calculates the first derivation
          var irrResultDeriv = function(values, dates, rate) {
            var r = rate + 1;
            var result = 0;
            for (var i = 1; i < values.length; i++) {
              var frac = moment(dates[i]).diff(moment(dates[0]), 'days') / 365;
              result -= frac * values[i] / Math.pow(r, frac + 1);
            }
            return result;
          }

          // Check that values contains at least one positive value and one negative value
          var positive = false;
          var negative = false;
          for (var i = 0; i < values.length; i++) {
            if (values[i] > 0) positive = true;
            if (values[i] < 0) negative = true;
          }
          
          // Return error if values does not contain at least one positive value and one negative value
          if (!positive || !negative) return '#NUM!';

          // Initialize guess and resultRate
          var guess = (typeof guess === 'undefined') ? 0.1 : guess;
          var resultRate = guess;
          
          // Set maximum epsilon for end of iteration
          var epsMax = 1e-10;
          
          // Set maximum number of iterations
          var iterMax = 50;

          // Implement Newton's method
          var newRate, epsRate, resultValue;
          var iteration = 0;
          var contLoop = true;
          do {
            resultValue = irrResult(values, dates, resultRate);
            newRate = resultRate - resultValue / irrResultDeriv(values, dates, resultRate);
            epsRate = Math.abs(newRate - resultRate);
            resultRate = newRate;
            contLoop = (epsRate > epsMax) && (Math.abs(resultValue) > epsMax);
          } while(contLoop && (++iteration < iterMax));

          if(contLoop) return '#NUM!';

          // Return internal rate of return
          return resultRate;
        }
    </script>
    
    <script>
        var global_result = {};
        var global_scheme_list = <?php echo json_encode($dropdownList);?>;
        var is_first_time = true;
        var globla_index = 1;
        var swp_type = 1;
        var error_message = [];
        var globla_xirr_value = [];

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

                $('#swp_start_date_'+index).datepicker('setStartDate', new Date(incept_date));
                // $('#swp_start_date_'+index).datepicker('setStartDate', new Date($(this).val()));
            }); 
            $('#swp_start_date_'+index).datepicker({
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

                $('#swp_end_date_'+index).datepicker('setStartDate', new Date(incept_date));
            });

            $('#swp_end_date_'+index).datepicker({
                format: 'dd/mm/yyyy',
                endDate: '-1d',
                autoclose: true
            });

            $('.schemecode_id').select2({
                placeholder: "Search Scheme",
            });
        }

        function renderRow(all_data,global_result,index){
            
            let money = [];

            global_result.values.forEach(function(val){
                money.push(val);
            });

            let dates = [];

            global_result.dates.forEach(function(val){
                dates.push(moment(val, 'YYYY/MM/DD'));
            });

            let j = XIRR(money,dates) * 100;
            j = parseFloat(j).toFixed(2);
            globla_xirr_value.push(global_result.schemecode_details.schemecode+"_"+j);

            var table_html = `<table class="table text-center stock_table mfliketbl">`;
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Scheme Name</td>";
            table_html = table_html+"<td>"+global_result.schemecode_details.s_name+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Amount</td>";
            table_html = table_html+"<td>"+global_result.lumpsum_investment_amount+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Investment Date</td>";
            table_html = table_html+"<td>"+global_result.investment_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>SWP Period</td>";
            table_html = table_html+"<td>"+global_result.swp_start_date+" - "+global_result.swp_end_date+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>SWP Frequency</td>";
            table_html = table_html+"<td>"+global_result.swp_frequency+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Each SWP Amount</td>";
            table_html = table_html+"<td>"+global_result.cash_flow+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>No. of "+global_result.data_frequency+" Instalments</td>";
            table_html = table_html+"<td>"+global_result.returnData.length+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Total Withdrawal Amount</td>";
            table_html = table_html+"<td id='total_withdrawal_"+index+"'>"+parseFloat(global_result.total_withdrawal).toFixed(2)+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>Market Value as on "+global_result.swp_end_date+"</td>";
            table_html = table_html+"<td>"+parseFloat(global_result.market_value).toFixed(2)+"</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<td>XIRR Return</td>";
            table_html = table_html+"<td>"+j+" %</td>";
            table_html = table_html+"</tr>";
            table_html = table_html+"</table>";

            table_html = table_html+'<table id="stock_table_'+index+'" class="table text-center stock_table mfliketbl">';
            table_html = table_html+"<thead>";
            table_html = table_html+"<tr>";
            table_html = table_html+"<th>NAV Date</th>";
            table_html = table_html+"<th>NAV</th>";
            table_html = table_html+"<th>Cash Flow</th>";
            table_html = table_html+"<th>Units</th>";
            table_html = table_html+"<th>Balance Units</th>";
            table_html = table_html+"<th>Market Value</th>";
            table_html = table_html+"</tr>";

            table_html = table_html+"</thead>";

            table_html = table_html+"<tbody>";
            var balance_units = 0;
            var units = 0;
            var total_withdrawal = 0;

            balance_units = parseFloat(global_result.lumpsum_investment_amount / global_result.investment_data.navrs).toFixed(2);
            table_html = table_html+"<tr>";
            table_html = table_html+"<td><span style='display:none;'>"+global_result.investment_date1+"</span>"+global_result.investment_date+"</td>";
            table_html = table_html+"<td>"+global_result.investment_data.navrs+"</td>";
            table_html = table_html+"<td>"+global_result.lumpsum_investment_amount+"</td>";
            table_html = table_html+"<td>"+balance_units+"</td>";
            table_html = table_html+"<td>"+balance_units+"</td>";
            table_html = table_html+"<td>"+global_result.lumpsum_investment_amount+"</td>";
            table_html = table_html+"</tr>";

            global_result.returnData.forEach(function(val){
                units = parseFloat(-(-global_result.cash_flow / val.navrs));
                balance_units = parseFloat(balance_units - units);
                table_html = table_html+"<tr>";
                table_html = table_html+"<td><span style='display:none;'>"+val.date1+"</span>"+val.date+"</td>";
                table_html = table_html+"<td>"+val.navrs+"</td>";
                table_html = table_html+"<td>-"+global_result.cash_flow+"</td>";
                table_html = table_html+"<td>"+parseFloat(units).toFixed(2)+"</td>";
                table_html = table_html+"<td>"+parseFloat(balance_units).toFixed(2)+"</td>";
                table_html = table_html+"<td>"+parseInt(balance_units * val.navrs)+"</td>";
                table_html = table_html+"</tr>";

                total_withdrawal = parseFloat(total_withdrawal) + parseFloat(balance_units);      
            })

            table_html = table_html+"</tbody>";
            table_html = table_html+'</table>';

            $("#result_view").append(table_html);
            
            // $('#stock_table_'+index).dataTable({
            //     "bPaginate": false,
            //     "searching": false,   
            //     "order": [[ 0, "asc" ]]
            // });
            
        }

        function callAPI(all_data){
            document.getElementById("add_btn_search").disabled = true;
            document.getElementById("loading_view").style.display = "block";
            console.log(all_data);
            $.ajax({
                url: "{{ url('/mf-swp-historical-list') }}",
                method: 'post',
                data: all_data,
                success: function (result) {
                    document.getElementById('result_view').innerHTML = "";
                    var i = 0;
                    globla_xirr_value = [];
                    result.forEach(function(val){
                        renderRow(all_data,val,i);
                        i = i+1;
                    });

                    document.getElementById("download_button").style.display = "block";
                    document.getElementById("loading_view").style.display = "none";

                    document.getElementById("add_btn_search").disabled = false;

                    var xirr_return = globla_xirr_value;
                    xirr_return = JSON.stringify(xirr_return);
                    document.getElementById("xirr_return").value = xirr_return;
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
            data.type = 1;
            data.swp_type = [];
            data.lumpsum_investment_amount = [];
            data.investment_date = [];
            data.data_frequency = [];
            data.swp_amount = [];
            data.swp_start_date = [];
            data.swp_end_date = [];
            data.schemecode_id = [];

            var form_list = document.getElementById("comp_sch").querySelectorAll('input[message=text]');
            var date_array = [];
            var array_data = [];
            form_list.forEach(function(val){
                array_data.push(parseInt($(val).attr('datatype')));
            });
            console.log(array_data);
            var flag = true;

            array_data.forEach(function(index){

                console.log(index);

                document.getElementById("lumpsum_investment_amount_"+index+"_error").innerHTML = "";
                document.getElementById("investment_date_"+index+"_error").innerHTML = "";
                document.getElementById("data_frequency_"+index+"_error").innerHTML = "";
                document.getElementById("swp_amount_"+index+"_error").innerHTML = "";
                document.getElementById("swp_start_date_"+index+"_error").innerHTML = "";
                document.getElementById("swp_end_date_"+index+"_error").innerHTML = "";
                document.getElementById("schemecode_id_"+index+"_error").innerHTML = "";

                data.lumpsum_investment_amount[index] = document.getElementById("lumpsum_investment_amount_"+index).value;
                data.investment_date[index] = document.getElementById("investment_date_"+index).value;
                data.data_frequency[index] = document.getElementById("data_frequency_"+index).value;
                data.swp_amount[index] = document.getElementById("swp_amount_"+index).value;
                data.swp_start_date[index] = document.getElementById("swp_start_date_"+index).value;
                data.swp_end_date[index] = document.getElementById("swp_end_date_"+index).value;
                data.schemecode_id[index] = document.getElementById("schemecode_id_"+index).value;
                data.swp_type[index] = document.getElementById("swp_type_"+index).value;

                if(!data.lumpsum_investment_amount[index]){
                    document.getElementById("lumpsum_investment_amount_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.investment_date[index]){
                    document.getElementById("investment_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.investment_date[index].split("/");
                    data.investment_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }

                if(!data.data_frequency[index]){
                    document.getElementById("data_frequency_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }
                if(!data.swp_amount[index]){
                    document.getElementById("swp_amount_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    if(error_message[index]){
                        document.getElementById("swp_amount_"+index+"_error").innerHTML = error_message[index];
                        flag = false;
                    }
                }
                if(!data.swp_start_date[index]){
                    document.getElementById("swp_start_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.swp_start_date[index].split("/");
                    data.swp_start_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }
                if(!data.swp_end_date[index]){
                    document.getElementById("swp_end_date_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }else{
                    date_array = data.swp_end_date[index].split("/");
                    data.swp_end_date[index] = date_array[1]+"/"+date_array[0]+"/"+date_array[2];
                }
                if(!data.schemecode_id[index]){
                    document.getElementById("schemecode_id_"+index+"_error").innerHTML = "Required";
                    flag = false;
                }

            });

            console.log(data);


            if(flag){
                callAPI(data);
            }else{
                return false;
            }
        }

        function changeSWP(type,index){
            var swp_label = "";
            if(type == 1){
                swp_label = "SWP Amount";
                swp_type = 1;
            }else{
                swp_label = "SWP %";
                swp_type = 2;
            }
            error_message[index] = "";

            document.getElementById("swp_label_"+index).innerHTML = swp_label;
            document.getElementById("swp_amount_"+index+"_error").innerHTML = error_message[index];
            document.getElementById("swp_type_"+index).value = swp_type;
            document.getElementById("swp_amount_"+index).value = "";
        }

        function addButton(){
            var form_list = document.getElementById("comp_sch").querySelectorAll('input[message=text]');
            console.log(form_list.length);
            if(form_list.length < 3){

                var iHtml = `<div id="swp_live_form_list_`+globla_index+`" style="padding: 10px;border: 1px solid;margin-top: 10px;">
                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">Select Scheme</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input schemecode_id" name="schemecode_id[`+globla_index+`]" id="schemecode_id_`+globla_index+`" onchange="changeScheme(`+globla_index+`)">
                                                <option value=""> </option>
                                                @foreach($dropdownList as $dropdown)
                                                    <option value="{{$dropdown->schemecode}}" >{{$dropdown->s_name}}</option>
                                                @endforeach
                                            </select>
                                            <em id="schemecode_id_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div> 

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">Incept Date</label>
                                        <div class="col-sm-9">
                                            <div  class="form-control" id="incept_date_`+globla_index+`" style="padding-top: 9px;"></div> 
                                        </div> 
                                    </div>
                                    
                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">Lumpsum Investment Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="lumpsum_investment_amount[`+globla_index+`]" id="lumpsum_investment_amount_`+globla_index+`" class="form-control" value=""  message="text" datatype="`+globla_index+`"   onkeyup="formValidation(1,`+globla_index+`);">
                                            <em id="lumpsum_investment_amount_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">Investment Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="investment_date[`+globla_index+`]" id="investment_date_`+globla_index+`" value="" onkeyup="formValidation(2,`+globla_index+`);">
                                            <em id="investment_date_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="data_frequency" class="col-sm-3 col-form-label">SWP Frequency</label>
                                        <div class="col-sm-9">
                                            <select class="form-control ui-autocomplete-input" name="data_frequency[`+globla_index+`]" id="data_frequency_`+globla_index+`" onchange="formValidation(3,`+globla_index+`);">
                                                <option value="">Select Data Frequency</option>
                                                @foreach($data_frequency as $dropdown)
                                                    <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                                @endforeach
                                            </select>
                                            <em id="data_frequency_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <input type="hidden" name="swp_type[`+globla_index+`]" id="swp_type_`+globla_index+`"  value="1">
                                        <label for="" class="col-sm-3 col-form-label">SWP</label>
                                        <div class="col-sm-9 dequitis" style="display: flex; align-items: center;">
                                            <input type="radio" id="amount_type_amt_`+globla_index+`" name="amount_type[`+globla_index+`]" value="1" checked="" onchange="changeSWP(1,`+globla_index+`);" >
                                            <label>Amount</label>
                                            <input type="radio" id="amount_type_per_`+globla_index+`" name="amount_type[`+globla_index+`]" value="2"  onchange="changeSWP(2,`+globla_index+`);">
                                            <label>%</label><br>
                                        </div> 
                                    </div> 

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label" id="swp_label_`+globla_index+`">SWP Amount</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="swp_amount[`+globla_index+`]" id="swp_amount_`+globla_index+`" class="form-control" value=""  onkeyup="formValidation(4,`+globla_index+`);">
                                            <em id="swp_amount_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">SWP Start Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="swp_start_date[`+globla_index+`]" id="swp_start_date_`+globla_index+`" class="form-control" value="">
                                            <em id="swp_start_date_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>

                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <label for="" class="col-sm-3 col-form-label">SWP End Date</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="swp_end_date[`+globla_index+`]" id="swp_end_date_`+globla_index+`" class="form-control" value="">
                                            <em id="swp_end_date_`+globla_index+`_error" class="error"></em>  
                                        </div> 
                                    </div>
                                    <div class="input-group row" style="margin-bottom: 20px;">
                                        <div class="col-sm-12 text-right">
                                            <a href="javascript:void(0);" style="color: red;" onclick="removeFormData(`+globla_index+`);">Remove</a>
                                        </div>
                                    </div>
                                </div>`;

                $("#swp_live_form").append(iHtml);

                setDefaultFun(globla_index);

                globla_index = globla_index + 1;
            }else{
                alert("Max 3");
            }
        }

        function changeScheme (index){
            var schemecode = document.getElementById("schemecode_id_"+index).value;
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
            }

            document.getElementById("incept_date_"+index).innerHTML = incept_date;
            console.log(schemecode);
            console.log(schemecode_detail);
            console.log(global_scheme_list);
        }

        function formValidation(input_type,index){
            var lumpsum_investment_amount = document.getElementById("lumpsum_investment_amount_"+index).value;
            var data_frequency = document.getElementById("data_frequency_"+index).value;
            var swp_amount = document.getElementById("swp_amount_"+index).value;
            var swp_type = document.getElementById("swp_type_"+index).value;
            document.getElementById("lumpsum_investment_amount_"+index+"_error").innerHTML = "";
            error_message[index] = "";
            var lumpsum_investment = 0;
            if(lumpsum_investment_amount && data_frequency && swp_amount){

                lumpsum_investment_amount = parseFloat(lumpsum_investment_amount);
                swp_amount = parseFloat(swp_amount);

                if(swp_type == 1){
                    if(data_frequency == "Weekly"){
                        lumpsum_investment = lumpsum_investment_amount*1/100;

                    }else if(data_frequency == "Fortnightly"){
                        lumpsum_investment = lumpsum_investment_amount*2/100;

                    }else if(data_frequency == "Monthly"){
                        lumpsum_investment = lumpsum_investment_amount*3/100;

                    }else if(data_frequency == "Quarterly"){
                        lumpsum_investment = lumpsum_investment_amount*6/100;

                    }else if(data_frequency == "Half Yearly"){
                        lumpsum_investment = lumpsum_investment_amount*12/100;

                    }else if(data_frequency == "Yearly"){
                        lumpsum_investment = lumpsum_investment_amount*24/100;

                    }

                    if(lumpsum_investment < swp_amount){
                        error_message[index] = "SWP Amount cannot exceed Rs. "+lumpsum_investment;
                    }
                }else{
                    if(data_frequency == "Weekly"){
                        lumpsum_investment = 1;
                    }else if(data_frequency == "Fortnightly"){
                        lumpsum_investment = 2;
                    }else if(data_frequency == "Monthly"){
                        lumpsum_investment = 3;
                    }else if(data_frequency == "Quarterly"){
                        lumpsum_investment = 6;
                    }else if(data_frequency == "Half Yearly"){
                        lumpsum_investment = 12;
                    }else if(data_frequency == "Yearly"){
                        lumpsum_investment = 24;
                    }

                    if(lumpsum_investment < swp_amount){
                        error_message[index] = "SWP Amount cannot exceed "+lumpsum_investment+" % of Investment Amount";
                    }
                }
            }
            document.getElementById("swp_amount_"+index+"_error").innerHTML = error_message[index];

            if(lumpsum_investment_amount){
                lumpsum_investment_amount = parseFloat(lumpsum_investment_amount);
                if(lumpsum_investment_amount >= 10000 && lumpsum_investment_amount <= 1000000000){

                }else{
                    document.getElementById("lumpsum_investment_amount_"+index+"_error").innerHTML = "Please enter a value between 10,000 - 1,00,00,00,000";
                }
            }

        }

        function removeFormData(index){
            document.getElementById("swp_live_form_list_"+index).remove();
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
                    <h2 class="page-title" style="margin-top: 16px; padding-bottom: 3px;">SWP HISTORICAL</h2>
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
                    <form action="{{route('frontend.mf_swp_historical_action')}}" method="get" id="comp_sch" >
                        @csrf
                        <input type="hidden" id="xirr_return" name="xirr_return" value="">
                        <div id="swp_live_form" style="">
                            <div id="swp_live_form_list_0" style="padding: 10px;border: 1px solid;margin-top: 10px;">
                                
                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label">Select Scheme</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input schemecode_id" name="schemecode_id[0]" id="schemecode_id_0" onchange="changeScheme(0)">
                                            <option value=""> </option>
                                            @foreach($dropdownList as $dropdown)
                                                <option value="{{$dropdown->schemecode}}" >{{$dropdown->s_name}}</option>
                                            @endforeach
                                        </select>
                                        <em id="schemecode_id_0_error" class="error"></em>  
                                    </div> 
                                </div> 

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label" >Incept Date</label>
                                    <div class="col-sm-9">
                                        <div  class="form-control" id="incept_date_0" style="padding-top: 9px;"></div> 
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label">Lumpsum Investment Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="lumpsum_investment_amount[0]" id="lumpsum_investment_amount_0" class="form-control" value=""  message="text" datatype="0"  onkeyup="formValidation(1,0);">
                                        <em id="lumpsum_investment_amount_0_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label">Investment Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="investment_date[0]" id="investment_date_0" value=""  onkeyup="formValidation(2,0);">
                                        <em id="investment_date_0_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="data_frequency" class="col-sm-3 col-form-label">SWP Frequency</label>
                                    <div class="col-sm-9">
                                        <select class="form-control ui-autocomplete-input" name="data_frequency[0]" id="data_frequency_0" onchange="formValidation(3,0);">
                                            <option value="">Select Data Frequency</option>
                                            @foreach($data_frequency as $dropdown)
                                                <option value="{{$dropdown['id']}}">{{$dropdown['name']}}</option>
                                            @endforeach
                                        </select>
                                        <em id="data_frequency_0_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <input type="hidden" name="swp_type[0]" id="swp_type_0" value="1">
                                    <label for="" class="col-sm-3 col-form-label">SWP</label>
                                    <div class="col-sm-9 dequitis" style="display: flex; align-items: center;">
                                        <input type="radio" id="amount_type_amt_0" name="amount_type[0]" value="1" checked="" onchange="changeSWP(1,0);" >
                                        <label>Amount</label>
                                        <input type="radio" id="amount_type_per_0" name="amount_type[0]" value="2"  onchange="changeSWP(2,0);">
                                        <label>%</label><br>
                                    </div> 
                                </div> 

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label" id="swp_label_0">SWP Amount</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_amount[0]" id="swp_amount_0" class="form-control" value="" onkeyup="formValidation(4,0);">
                                        <em id="swp_amount_0_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="margin-bottom: 20px;">
                                    <label for="" class="col-sm-3 col-form-label">SWP Start Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_start_date[0]" id="swp_start_date_0" class="form-control" value="">
                                        <em id="swp_start_date_0_error" class="error"></em>  
                                    </div> 
                                </div>

                                <div class="input-group row" style="">
                                    <label for="" class="col-sm-3 col-form-label">SWP End Date</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="swp_end_date[0]" id="swp_end_date_0" class="form-control" value="">
                                        <em id="swp_end_date_0_error" class="error"></em>  
                                    </div> 
                                </div>
                            </div> 
                        </div>

                        <div class="input-group-append text-center" style="display: flex; justify-content: center; padding-top: 16px;">
                            <button type="button" class="btn btn-primary add_button" id="add_btn_add" onclick="addButton();" style="margin-right: 20px;">Add</button>
                            <button type="button" class="btn btn-primary add_button" id="add_btn_search" onclick="searchData();">
                                Submit
                            </button>
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
