@extends('layouts.frontend')

@section('js_after')
    <script>

        $('#sale_date').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true,
            format: 'dd-M-yyyy'
        });
        $('#purchase_date').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true,
            startDate: '01-04-2001',
            format: 'dd-M-yyyy'
        });
        
        var count = 1;
        function addField()
        {
            var section = document.getElementById("financial_goal_plan");
            var htmlToInsert = '<div id="'+count+'" class="financial_block" style="margin-top: 7px;"><div class="financial_input"><div class="form-group "><div class="row"><label class="col-sm-4 col-form-label">Financial Year</label><div class="col-sm-2"><select class="form-control" id="financial_year" name="financial_year[]"><option value="">Select a FY</option>@foreach ($financial_years as $financial_year)<option value="{{$financial_year->financial_year}}">{{$financial_year->financial_year}}</option>@endforeach </select></div><label class="col-sm-3 col-form-label">Cost of Improvement</label><div class="col-sm-2"><div class="d-flex align-items-center"><input type="text" name="cost_of_improvement[]" class="form-control" value=""></div><div class="cal-icon">₹</div></div><div class="financial_delete col-sm-1"><button type="button" style="color:red; margin-top: 10px;" onclick="removeMe('+count+')"><i class="fa fa-trash" aria-hidden="true"></i></button></div></div></div></div>';
            
            document.getElementById("financial_goal_plan").insertAdjacentHTML('beforeend', htmlToInsert);
            count++;
        }

        function removeMe(element)
        {
            var ele = document.getElementById(element);
            ele.remove();
            var i = 0;
            
            var collection = document.getElementById("financial_goal_plan").children;
            
            for(let i=0;i<collection.length;i++)
            {
                collection[i].setAttribute('id',i);
                collection[i].getElementsByTagName('input')[0].setAttribute('name','financial'+i);
                collection[i].getElementsByTagName('input')[1].setAttribute('name','requiredAmount'+i);
                collection[i].getElementsByTagName('input')[2].setAttribute('name','yearsLeft'+i);
                collection[i].getElementsByTagName('button')[0].setAttribute('onclick','removeMe('+i+')');
            }
        }

        $(function() {
            jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            var validator = $(".js-validate-form").validate({
                errorElement: "em",
                errorContainer: $("#warning, #summary"),
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                },
                rules: {
                    fair_market_value: {
                        required: true,
                        digits: true,
                        range: [100, 9999999999],
                    },
                    asset_name: {
                        required: false,
                        digits: false,
                        maxlength: 30,
                    },
                    cost_of_improvement: {
                        required: false,
                        digits: true,
                        range: [0, 999999999],
                    },
                    sales_price: {
                        required: true,
                        digits: true,
                        range: [100, 9999999999],
                    },
                    purchase_price: {
                        required: true,
                        digits: true,
                        range: [100, 9999999999],
                    },
                    sales_expenses: {
                        required: false,
                        digits: true,
                        range: [0, 9999999999],
                    },
                    income_tax_slab: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.00, 50.00],
                    },
                    sale_date: { 
                        required: false,
                        greaterThan: "#financial_year" 
                    },
                    purchase_date: {
                        required: false,
                        date: true,
                        maxDate: true
                    },
                    
                },
                messages: {
                    fair_market_value: "Please enter a value between 100 and 9,99,99,99,999.",
                    asset_name: "Max 30 characters",
                    cost_of_improvement: "Please enter a value between 0 and 9,99,99,99,999.",
                    sales_price: "Please enter a value between 100 and 9,99,99,99,999.",
                    purchase_price: "Please enter a value between 100 and 9,99,99,99,999.",
                    sales_expenses: "Please enter a value between 0 and 9,99,99,99,999.",
                    income_tax_slab: "Please enter a value between 0.0 and 50",
                    sale_date: "Must be greater than last financial year",
                    purchase_date: "Must be greater than 01-Apr-2001",
                }
            });
        });

        var global_type = 1;
        
        jQuery.validator.addMethod("greaterThan", 
        function(value, element, params) {
            var last_fy = $("select:last").attr("value");
            if (last_fy == 'undefined' || last_fy === '') { 
                return true; 
            } 
            var values = $("input[name='cost_of_improvement[]']").map(function(){return $(this).val();}).get();
            // var last_fy = $("select:last").attr("value");
            // alert(values);
            if (values == 'undefined' || values == '') { 
                return true; 
            } 
            
            var last_fyold = last_fy;
            var last_fy = last_fy.slice(0, 4);
            var last_fy = '03-31-'+last_fy;

            
            if (value == 0) { 
                return true; 
            } 
            else if (!/Invalid|NaN/.test(new Date(value))) {
                return new Date(value) > new Date(last_fy);
            }
            
            return isNaN(value) && isNaN(last_fy) 
                || (Number(value) > Number(last_fy)); 
        },'Must be greater than last {0}.');

        $.validator.addMethod("maxDate", 
        function(value, element) {
            var curDate = new Date('04-01-2001');
            var inputDate = new Date(value);
            if (value == 0) { 
                return true; 
            } 
            else if (inputDate > curDate)
                return true;
            return false;
        }, "Cannot be less than 01-Apr-2001!");

        function TurnPurchase(dat)
        {
            var before = document.getElementById("before");
            var after = document.getElementById("after");
            
            if(dat == "before"){
                before.style.display = 'block';
                after.style.display = 'none';
                global_type = 1;
            }else{
                before.style.display = 'none';
                after.style.display = 'block';
                global_type = 2;
            }
        }

        $(document).ready(function () {
            $('.sale_date').on('change',function() {
                   
                var query = $(this).val();
                var saledate = new Date(query);

                var asset_type = $('#asset_type').find(":selected").val();
                // alert(asset_type);
                var purchased_before = $('input[name=purchased_before]:checked', '#capital_gains_tax_form').val();
                if(purchased_before == 1){
                    var year = '03-31-2001';
                }else{
                    var year = document.getElementById("purchase_date").value;
                    // var year = '03-31-2001';
                }
                
                var year = new Date(year);
                var age = Math.floor((saledate-year) / (364 * 24 * 60 * 60 * 1000));
                if(asset_type == 'immovable'){
                    if(age < 2){
                        $("#income_tax_slab").prop('disabled', false);
                    }else{
                        $("#income_tax_slab").prop('disabled', true);
                    }
                }else{
                    if(age < 3){
                        $("#income_tax_slab").prop('disabled', false);
                    }else{
                        $("#income_tax_slab").prop('disabled', true);
                    }
                }
                

                var myArray = <?php echo json_encode($check_fy); ?>;
                
                var last_fy = query.substring(query.length-2, query.length);
                var prev = parseInt(last_fy)-1;
                var curr = parseInt(last_fy);
                var next = parseInt(last_fy)+1;

                var last_fy = query.substring(query.length-4, query.length);
                var prevfull = parseInt(last_fy)-1;
                var currfull = parseInt(last_fy);
                var nextfull = parseInt(last_fy)+1;

                var last_fy = '03-31-'+last_fy;
                var last_fy = new Date(last_fy);
                newyear = '';
                // console.log(Number(saledate));
                console.log(Number(last_fy));
                // console.log(next);
                if(Number(saledate) <= Number(last_fy)){
                    if(curr <= 9){
                        curr = '0'+curr;
                    }
                    var newyear = prevfull+'-'+curr;
                }else{
                    if(next <= 9){
                        next = '0'+next;
                    }
                    var newyear = currfull+'-'+next;
                }
                
                // alert(last_fy);
                // var sale_date = new Date(sale_date);

                console.log(newyear);
                if ($.inArray(newyear, myArray) != -1){
                    $("#cost_inflation").prop('disabled', true);
                }else{
                    $("#cost_inflation").prop('disabled', false);
                    $("input[id*=cost_inflation]").rules("add", "required");
                }
                // alert(asset_type);
                // alert(age);
                if(asset_type == 'movable'){
                    if(age < 3){
                        
                        $("#cost_inflation").prop('disabled', true);
                        
                    }
                }
            });
        });

        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });
        $("#is_note").click( function(){
            if( $(this).is(':checked') ){
                $('textarea[name="note"]').prop("readonly", false);
            }else {
                $('textarea[name="note"]').prop("readonly", true);
            }
        });
        
        function changeNote(){
            var note = document.getElementById('note').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }

        
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    
    @if(old('client')!='')
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif
    @if(old('note')!='')
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif

@endsection

@section('content')
    <div class="banner">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">PREMIUM CALCULATORS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Capital Gains Tax Calculation</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.capital_gains_tax_calculator_output')}}" method="post">
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label"></label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input fixed_deposit_chk" type="radio" name="purchased_before" onchange="TurnPurchase('before')"  checked value="1"/>
                                            <label class="form-check-label" for="inlineRadio1">Capital Asset purchased before FY2001-02</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input fixed_deposit_chk" type="radio" name="purchased_before" value="2"  onchange="TurnPurchase('after')" />
                                            <label class="form-check-label" for="inlineRadio2">Capital Asset purchased in or after FY2001-02</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" id="monthly_emi">Select Asset Type</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" id="asset_type" name="asset_type">   
                                            <option value="immovable">Immovable Property</option>
                                            <option value="movable" selected="selected">Movable Property</option>
                                        </select>
                                    </div>
                                    
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" id="monthly_emi">Asset Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="asset_name" class="form-control" value=""/>
                                    </div>
                                </div>
                                
                                <div  id="before">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Fair Market Value as on 01.04.2001</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="fair_market_value" class="form-control" value=""/>
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div  id="after" style="display: none;">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Purchase Price</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="purchase_price" class="form-control" value=""/>
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="form-group ">
                                        <div class="row">
                                            <label class="col-sm-4 col-form-label">Date of Purchase</label>
                                            
                                            <div class="col-sm-8">
                                                <div class="d-flex align-items-center">
                                                    <input type="text" name="purchase_date" id="purchase_date" class="form-control" placeholder="Purchase Date" value="" autocomplete="off" aria-describedby="purchase_date_error" aria-invalid="true"/>
                                                    
                                                </div>
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id='financial_goal_plan' style="border: 1px solid rgb(216, 214, 214); padding: 15px 15px 0px 15px;">
                                <div class="financial_block" id='0'>
                                    <div class="financial_input">
                                        <div class="form-group ">
                                            <div class="row">
                                                <label class="col-sm-4 col-form-label">Financial Year</label>
                                                <div class="col-sm-2">
                                                    <select class="form-control" id="financial_year" name="financial_year[]">
                                                        <option value="">Select a FY</option>
                                                        @foreach ($financial_years as $financial_year)
                                                            <option value="{{$financial_year->financial_year}}">{{$financial_year->financial_year}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <label class="col-sm-3 col-form-label">Cost of Improvement</label>
                                                <div class="col-sm-2">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" name="cost_of_improvement[]" class="form-control" value=""/>
                                                    </div>
                                                    <div class="cal-icon">
                                                        ₹
                                                    </div>
                                                </div>
                                                <div class="financial_delete col-sm-1">
                                                    <button type="button" style="color:red; margin-top: 10px;"onclick='removeMe(0)'>
                                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                </div>
                                <br/>
                                <div class="form-group row">
                                    <div class="offset-10 col-sm-6">
                                        <div class="col-md-4">
                                                <button type="button" class="btn btn-primary savedButton" onclick="addField()">Add More</button>
                                            </div>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Sales Price</label>
                                        
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="sales_price" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Sales Expenses</label>
                                        
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="sales_expenses" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Sale Date</label>
                                        
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="sale_date" id="sale_date" class="form-control sale_date" placeholder="" value="" autocomplete="off" aria-describedby="sale_date_error" aria-invalid="true">
                                                {{-- <em id="sale_date_error" class="error">Sale Date must be within last selected FY</em> --}}
                                                {{-- <input type="text" name="sale_date" class="form-control" value=""> --}}
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Expected Cost Inflation Index</label>
                                        
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="cost_inflation_index" id="cost_inflation" class="form-control" value="" disabled>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="row">
                                        <label class="col-sm-4 col-form-label">Income Tax Slab of the Assessee</label>
                                        
                                        <div class="col-sm-3">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="income_tax_slab" id="income_tax_slab" class="form-control" value="" disabled>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card sip-calculator singleLineHolder">
                                <div class="form-group row">
                                    
                                    <div class="col-sm-6 d-flex">
                                        <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                            <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname')}}" maxlength="30">
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
                                
                                <div class="form-group row"  style="align-items: flex-start;">
                                    <!-- <label class="col-sm-5 col-form-label">
                                        
                                    </label> -->
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if(old('is_note')=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{old('note')}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(old('report')=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(old('report')=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
    
                                @include('frontend.calculators.suggested.form')
    
    
                                <div class="form-group row">
    
                                    <div class="offset-1 col-sm-10">
                                        <div class=" calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                           
                                        </div>
    
                                    </div>
                                </div>
                            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="btm-shape-prt">
                <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
            </div>
        </div></section>

@endsection
