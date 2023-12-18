@extends('layouts.frontend')

@section('js_after')
    <script>
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

        jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
            return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
        }, "You must include two decimal places");

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[A-Za-z ]+$/i.test(value);
        }, "Letters only please");

        jQuery.validator.addMethod('greaterThan', function(value, element, param) {
            return ( Number(value) > Number(jQuery(param).val()) );
        }, 'Must be greater than start' );

        jQuery.validator.addMethod('lesserThan', function(value, element, param) {
            return ( Number(value) < Number(jQuery(param).val()) );
        }, 'Must be less than end' );

        jQuery.validator.addMethod('disabledIfZero', function(value, element, param) {
            if(Number(value) == 0){
                jQuery(param).prop( "disabled", true );
            } else {
                jQuery(param).prop( "disabled", false );
            }
            return true;
        });

        var validator = $(".js-validate-form").validate({
            errorElement: "em",
            errorContainer: $("#warning, #summary"),
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                child_name: {
                    required: true,
                    lettersonly: true,
                    maxlength: 30,
                },
                current_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [0, 99]
                },
                fund_requirement_purpose: {
                    required: true
                },
                fund_required_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    greaterThan: "#current_age",
                    range: [1, 99]
                },
                fund_required_amount: {
                    required: true,
                    number: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                investment_amount: {
                    maxlength: 10,
                    range: [0, 9999999999],
                    lesserThan: "#fund_required_amount",
                    disabledIfZero: "#return_rate",
                },
                inflation_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0, 12.00]
                },
                return_rate: {
                    /*required: function(element) {
                        return (Number($("input[name='investment_amount']").val()) > 0) ? true : false;
                    },*/
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                return_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                return_rate_2: {
                    //required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                }
            },
            messages: {
                fund_required_age: { greaterThan: "Must be greater than Child Age!" },
                investment_amount: { lesserThan: "Must be less than Fund Required!"}
            }
        });

        var global_type = 1;
        
        function TurnOnSip(dat)
        {
            var sip = document.getElementById("sip");
            var emi = document.getElementById("emi");
            
            if(dat == "sipInv"){
                sip.style.display = 'block';
                monthly_emi.innerHTML = 'Outstanding Loan Amount';
                global_type = 1;
            }else{
                sip.style.display = 'none';
                monthly_emi.innerHTML = 'Monthly EMI';
                global_type = 2;
            }
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
{{-- <div class="banner bannerForAll container" style="padding-bottom: 0;">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Premium Calculators</h2>
                    <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                    </p>
                    <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                    <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div> --}}
    <div class="banner">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Child Education/Marriage Expenses Calculator</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.childEducation_output')}}" method="post">
                            <div class="card sip-calculator singleLineHolder">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Child Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control {{ $errors->has('child_name') ? ' is-invalid' : '' }}" name="child_name" value="{{old('child_name')}}" maxlength="30" required>
    
                                        @if ($errors->has('child_name'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('child_name') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Child Age</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control {{ $errors->has('current_age') ? ' is-invalid' : '' }}" id="current_age" name="current_age" value="{{old('current_age')}}"   required>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                        @if ($errors->has('current_age'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('current_age') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Fund Requirement Purpose</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-2" for="inlineRadio1">Education
                                                <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio1" value="Education">
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-2" for="inlineRadio2">Marriage
                                                <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio2" value="Marriage" checked="">
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-2" for="inlineRadio3">Investment
                                                <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio3" value="Investment" checked="">
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Fund Required Age</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control {{ $errors->has('fund_required_age') ? ' is-invalid' : '' }}" name="fund_required_age" value="{{old('fund_required_age')}}"   required>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                        @if ($errors->has('fund_required_age'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fund_required_age') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Fund Required Amount</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control {{ $errors->has('fund_required_amount') ? ' is-invalid' : '' }}" name="fund_required_amount" id="fund_required_amount" value="{{old('fund_required_amount')}}" maxlength="10"  required>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                        @if ($errors->has('fund_required_amount'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('fund_required_amount') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Market Value of Investment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{old('investment_amount')}}" maxlength="10" >
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                        @if ($errors->has('investment_amount'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('investment_amount') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Expected inflation rate</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control number {{ $errors->has('inflation_rate') ? ' is-invalid' : '' }}" name="inflation_rate" value="{{old('inflation_rate')}}"   required>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('inflation_rate'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('inflation_rate') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed Rate of Return (Current Investment)</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control number {{ $errors->has('return_rate') ? ' is-invalid' : '' }}" name="return_rate" id="return_rate" value="{{old('return_rate')}}" >
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('return_rate'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('return_rate') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">Assumed Rate of Return (Fresh Investment):</h6>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Scenario 1</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control number {{ $errors->has('return_rate_1') ? ' is-invalid' : '' }}" name="return_rate_1" value="{{old('return_rate_1')}}"  required>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('return_rate_1'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('return_rate_1') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Scenario 2 (Optional)</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control number {{ $errors->has('return_rate_2') ? ' is-invalid' : '' }}" name="return_rate_2" value="{{old('return_rate_2')}}" >
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('return_rate_2'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('return_rate_2') }}</strong>
                                            </div>
                                        @endif
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
    
                                {{-- <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                            <label class="sqarecontainer">View Detailed Graph
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1"> 
                                                <span class="checkmark"></span>
                                            </label>
                                    </div>
                                </div> --}}
    
                                
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
