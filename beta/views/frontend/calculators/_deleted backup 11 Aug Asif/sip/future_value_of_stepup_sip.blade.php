@extends('layouts.frontend')

@section('js_after')
    <script>
        //$('input[name="clientname"]').prop("readonly", true);
        //$('input[name="step_up_rate"]').prop("readonly", true);
        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });

        $('input[type=radio][name=include_step_up]').change(function() {
            if (this.value == 'yes') {
                $('input[name="step_up_rate"]').prop("readonly", false);
            }
            else if (this.value == 'no') {
                $('input[name="step_up_rate"]').prop("readonly", true);
            }
        });

        jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
            return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
        }, "You must include two decimal places");

        jQuery.validator.addMethod("notEqual", function(value, element, param) {
            return ( Number(value) != Number(jQuery(param).val()) );
        }, "It should not be equal to Annual Increment%");

        var validator = $(".js-validate-form").validate({
            errorElement: "em",
            errorContainer: $("#warning, #summary"),
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                amount: {
                    required: true,
                    digits: true,
                    maxlength: 8,
                    range: [100, 99999999]
                },
                period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99]
                },
                annual_increment: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.00, 25.00]
                },
                interest1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    notEqual: "#annual_increment",
                    range: [0.10, 15.00]
                },
                interest2: {
                    required: false,
                    number: true,
                    twodecimalplaces: true,
                    notEqual: "#annual_increment",
                    range: [0.10, 15.00]
                }
            },
            messages:{
                amount: "Please enter a value between 100 and 9,99,99,999.",
                period: "Please enter no more than 2 characters.",
                annual_increment: "Please enter a value between 1.00 - 25.00%.",
                interest1: {
                        required: "Please enter a value between 0.10 - 15.00%.",
                        range: "Please enter a value between 0.10 - 15.00%.",
                        notEqual: "Expected rate of return can't equal annual increment."
                    },
                interest2: {
                    required: "Please enter a value between 0.10 - 15.00%.",
                    range: "Please enter a value between 0.10 - 15.00%.",
                    notEqual: "Expected rate of return can't equal annual increment."
                }
                }
        });
    </script>
    @if(old('client')!='')
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif

    @if(old('include_step_up')=='yes')
        <script>
            $('input[name="step_up_rate"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="step_up_rate"]').prop("readonly", true);
        </script>
    @endif
    
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
@endsection

@section('content')
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Future Value of Step-Up SIP</h3>
                    @include('frontend.calculators.common_bio')
                    
                    <form class="js-validate-form" action="{{route('frontend.futureValueOfStepUpSIPOutput')}}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Monthly SIP Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{old('amount')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Annual Increment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('annual_increment') ? ' is-invalid' : '' }}" id="annual_increment" name="annual_increment" value="{{old('annual_increment')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('annual_increment'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('annual_increment') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">SIP Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{old('period')}}"  >
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h6 class="text-muted">Assumed Rate Of Return:</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 1</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('interest1') ? ' is-invalid' : '' }}" name="interest1" value="{{old('interest1')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('interest1'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('interest1') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 2 (Optional)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('interest2') ? ' is-invalid' : '' }}" name="interest2" value="{{old('interest2')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('interest2'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('interest2') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if(isset($form_data) && $form_data['client']=='1') checked  @endif> 
                                    <span class="checkmark"></span>
                                </label>
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{isset($data['clientname'])?$data['clientname']:''}}" maxlength="30">
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
                                <div class="col-sm-6 d-flex">
                                        <label class="sqarecontainer">View Detailed Graph
                                            <input id="is_graph" type="checkbox" name="is_graph" value="1" @if(isset($form_data) && $form_data['is_graph']=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label class="sqarecontainer">Add Comments (If any)
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if(isset($form_data) && $form_data['is_note']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{isset($form_data) && $form_data['note']}}</textarea>
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
                            @include('frontend.calculators.suggested.form')
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
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
