@extends('layouts.frontend')

@php

    $data = $form_data;
    // dd($data['asset_name']);
    $data['report'] = old('report', isset($data['report'])?$data['report']:'');
    if(!$data['report']){
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest', isset($data['suggest'])?$data['suggest']:'');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['note'] = old('note', isset($data['note'])?$data['note']:'');
    if(!$data['note']){
        $data['note'] = $form_data['note'];
    }
    
    $data['client'] = old('client', isset($data['client'])?$data['client']:'');
    if(!$data['client']){
        $data['client'] = $form_data['client'];
    }
    
    $data['client_name'] = old('client_name', isset($data['client_name'])?$data['client_name']:'');
    if(!$data['client_name']){
        $data['client_name'] = $form_data['clientname'];
    }
    
    $data['is_note'] = old('is_note', isset($data['is_note'])?$data['is_note']:'');
    if(!$data['is_note']){
        $data['is_note'] = $form_data['is_note'];
    }
    
    $data['include_performance'] = old('include_performance', isset($data['include_performance'])?$data['include_performance']:'');
    if(!$data['include_performance']){
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type', isset($data['suggestedlist_type'])?$data['suggestedlist_type']:'');
    if(!$data['suggestedlist_type']){
        $data['suggestedlist_type'] = $suggestedlist_type;
    }

@endphp

@section('js_after')
    <script>

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

        var validator = $(".js-validate-form").validate({
            errorElement: "em",
            errorContainer: $("#warning, #summary"),
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                current_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [0, 60]
                },
                retirement_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    greaterThan: "#current_age",
                    range: [0, 99]
                },
                current_monthly_expense: {
                    required: true,
                    digits: true,
                    maxlength: 9,
                    range: [1, 100000000]
                },
                expected_inflation_rate_till_retirement: {
                    //required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.00, 18.00]
                },
                expected_inflation_rate_post_retirement: {
                    //required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.00, 18.00]
                },
                accumulation_phase_interest_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                accumulation_phase_interest_rate_2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                distribution_phase_interest_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 12.00]
                },
                distribution_phase_interest_rate_2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 12.00]
                },
                current_market_value_of_investment: {
                    digits: true,
                    maxlength: 10,
                    range: [0, 9999999999]
                },
                expected_rate_of_return_current_investment: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                other_amount_receivable_on_retirement: {
                    digits: true,
                    maxlength: 10,
                    range: [0, 9999999999]
                },
                age_at_which_annuity_ends: {
                    required: true,
                    digits: true,
                    maxlength: 3,
                    greaterThan: "#retirement_age",
                    range: [1, 100]
                },
                balance_required_at_end_of_annuity: {
                    number: true,
                    maxlength: 10,
                    range: [0, 1000000000]
                }
            },
            messages: {
                retirement_age: { greaterThan: "Must be greater than Current Age!" },
                age_at_which_annuity_ends: { greaterThan: "Must be greater than Retirement Age!" },
            }
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
        
        changeNote();
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    @if($data['client']==1)
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif
    
    @if($data['is_note']==1)
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif

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

<section class="main-sec styleNew">
    <div class="container">
        <div class="row">
            @include('frontend.calculators.left_sidebar')
            <div class="col-md-12">
                <h3 class="smalllineHeading">Retirement Need Based Calculator</h3>
                
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.retirementPlanning_output')}}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Age</label>
                                <div class="col-sm-7">
                                    <input type="text" id="current_age" class="form-control {{ $errors->has('current_age') ? ' is-invalid' : '' }}" name="current_age" value="{{old('current_age', isset($data['current_age'])?$data['current_age']:'')}}"   required>
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
                                <label class="col-sm-5 col-form-label">Retirement Age</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('retirement_age') ? ' is-invalid' : '' }}" name="retirement_age" id="retirement_age" value="{{old('retirement_age', isset($data['retirement_age'])?$data['retirement_age']:'')}}"   required>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('retirement_age'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('retirement_age') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Monthly Expense</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('current_monthly_expense') ? ' is-invalid' : '' }}" name="current_monthly_expense" value="{{old('current_monthly_expense', isset($data['current_monthly_expense'])?$data['current_monthly_expense']:'')}}"   required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('current_monthly_expense'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('current_monthly_expense') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Expected Inflation Rate Till Retirement </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('expected_inflation_rate_till_retirement') ? ' is-invalid' : '' }}" name="expected_inflation_rate_till_retirement" value="{{old('expected_inflation_rate_till_retirement', isset($data['expected_inflation_rate_till_retirement'])?$data['expected_inflation_rate_till_retirement']:'')}}">
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('expected_inflation_rate_till_retirement'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_inflation_rate_till_retirement') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Expected Inflation Rate Post Retirement </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('expected_inflation_rate_post_retirement') ? ' is-invalid' : '' }}" name="expected_inflation_rate_post_retirement" value="{{old('expected_inflation_rate_post_retirement', isset($data['expected_inflation_rate_post_retirement'])?$data['expected_inflation_rate_post_retirement']:'')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('expected_inflation_rate_post_retirement'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_inflation_rate_post_retirement') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <h6 class="mb-0 pb-0">Scenario 1</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-5 col-form-label">Accumulation Phase</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control number {{ $errors->has('accumulation_phase_interest_rate_1') ? ' is-invalid' : '' }}" name="accumulation_phase_interest_rate_1" value="{{old('accumulation_phase_interest_rate_1', isset($data['accumulation_phase_interest_rate_1'])?$data['accumulation_phase_interest_rate_1']:'')}}" required>
                                                <div class="cal-icon" style="right: 1px;">
                                                    %
                                                </div>
                                                @if ($errors->has('accumulation_phase_interest_rate_1'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('accumulation_phase_interest_rate_1') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                                <label class="col-sm-5 col-form-label">Distribution Phase</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control number {{ $errors->has('distribution_phase_interest_rate_1') ? ' is-invalid' : '' }}" name="distribution_phase_interest_rate_1" value="{{old('distribution_phase_interest_rate_1', isset($data['distribution_phase_interest_rate_1'])?$data['distribution_phase_interest_rate_1']:'')}}" required>
                                                <div class="cal-icon" style="right: 1px;">
                                                    %
                                                </div>
                                                @if ($errors->has('distribution_phase_interest_rate_1'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('distribution_phase_interest_rate_1') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <h6 class="mb-0 pb-0">Scenario 2 (Optional)</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-5 col-form-label">Accumulation Phase</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control number {{ $errors->has('accumulation_phase_interest_rate_2') ? ' is-invalid' : '' }}" name="accumulation_phase_interest_rate_2" value="{{old('accumulation_phase_interest_rate_2', isset($data['accumulation_phase_interest_rate_2'])?$data['accumulation_phase_interest_rate_2']:'')}}" >
                                                <div class="cal-icon" style="right: 1px;">
                                                    %
                                                </div>
                                                @if ($errors->has('accumulation_phase_interest_rate_2'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('accumulation_phase_interest_rate_2') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                                <label class="col-sm-5 col-form-label">Distribution Phase</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control number {{ $errors->has('distribution_phase_interest_rate_2') ? ' is-invalid' : '' }}" name="distribution_phase_interest_rate_2" value="{{old('distribution_phase_interest_rate_2', isset($data['distribution_phase_interest_rate_2'])?$data['distribution_phase_interest_rate_2']:'')}}" >
                                                <div class="cal-icon" style="right: 1px;">
                                                    %
                                                </div>
                                                @if ($errors->has('distribution_phase_interest_rate_2'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('distribution_phase_interest_rate_2') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Market Value of Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('current_market_value_of_investment') ? ' is-invalid' : '' }}" name="current_market_value_of_investment" value="{{old('current_market_value_of_investment', isset($data['current_market_value_of_investment'])?$data['current_market_value_of_investment']:'')}}"   >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('current_market_value_of_investment'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('current_market_value_of_investment') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate of Return (Current Investment)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('expected_rate_of_return_current_investment') ? ' is-invalid' : '' }}" name="expected_rate_of_return_current_investment" value="{{old('expected_rate_of_return_current_investment', isset($data['expected_rate_of_return_current_investment'])?$data['expected_rate_of_return_current_investment']:'')}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('expected_rate_of_return_current_investment'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_rate_of_return_current_investment') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Other Amount Receivable on Retirement</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('other_amount_receivable_on_retirement') ? ' is-invalid' : '' }}" name="other_amount_receivable_on_retirement" value="{{old('other_amount_receivable_on_retirement', isset($data['other_amount_receivable_on_retirement'])?$data['other_amount_receivable_on_retirement']:'')}}"   >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('other_amount_receivable_on_retirement'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('other_amount_receivable_on_retirement') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Age At Which Annuity Ends</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('age_at_which_annuity_ends') ? ' is-invalid' : '' }}" name="age_at_which_annuity_ends" value="{{old('age_at_which_annuity_ends', isset($data['age_at_which_annuity_ends'])?$data['age_at_which_annuity_ends']:'')}}"  required>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('age_at_which_annuity_ends'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('age_at_which_annuity_ends') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Balance Required At End Of Annuity</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('balance_required_at_end_of_annuity') ? ' is-invalid' : '' }}" name="balance_required_at_end_of_annuity" value="{{old('balance_required_at_end_of_annuity', isset($data['balance_required_at_end_of_annuity'])?$data['balance_required_at_end_of_annuity']:'')}}"   >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('balance_required_at_end_of_annuity'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('balance_required_at_end_of_annuity') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if($data['client']=='1') checked  @endif /> 
                                    <span class="checkmark"></span>
                                </label>
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{$data['client_name']}}" maxlength="30">
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
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if($form_data['is_note']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$data['note']}}</textarea>
                                    <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Get Report</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Summary Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if($data['report']=='summary') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Detailed Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if($data['report']=='summary')  @else checked  @endif >
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                </div>
                            </div>
                            @include('frontend.calculators.suggested.edit_form')
                            <div class="form-group row">
                                
                                <!-- <div class="offset-1 col-sm-10">
                                    <div class=" calcBelowBtn">
                                            <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                            <button class="btn banner-btn mx-3">Calculate</button>
                                    </div>
                                </div> -->
                                
                                <div class="offset-1 col-sm-10">
                                    <div class="calcBelowBtn">
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3">Back</button>
                                            @if(session()->get('retirementPlanning_form_id'))
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            @else
                                                <a href="{{route('frontend.retirementPlanning')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </div>
</section>

@endsection
