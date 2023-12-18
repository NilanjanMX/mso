@extends('layouts.frontend')

@php

    $data = $form_data;
    $data['child_name'] = old('child_name');
    if(!$data['child_name']){
        $data['child_name'] = $form_data['child_name'];
    }
    
    $data['current_age'] = old('current_age');
    if(!$data['current_age']){
        $data['current_age'] = $form_data['current_age'];
    }
    
    $data['fund_required_age'] = old('fund_required_age');
    if(!$data['fund_required_age']){
        $data['fund_required_age'] = $form_data['fund_required_age'];
    }
    
    $data['fund_required_amount'] = old('fund_required_amount');
    if(!$data['fund_required_amount']){
        $data['fund_required_amount'] = $form_data['fund_required_amount'];
    }
    
    $data['investment_amount'] = old('investment_amount');
    if(!$data['investment_amount']){
        $data['investment_amount'] = $form_data['investment_amount'];
    }
    
    $data['inflation_rate'] = old('inflation_rate');
    if(!$data['inflation_rate']){
        $data['inflation_rate'] = $form_data['inflation_rate'];
    }
    
    $data['return_rate'] = old('return_rate');
    if(!$data['return_rate']){
        $data['return_rate'] = isset($form_data['return_rate'])??$form_data['return_rate'];
    }
    
    $data['return_rate_1'] = old('return_rate_1');
    if(!$data['return_rate_1']){
        $data['return_rate_1'] = $form_data['return_rate_1'];
    }
    
    $data['return_rate_2'] = old('return_rate_2');
    if(!$data['return_rate_2']){
        $data['return_rate_2'] = $form_data['return_rate_2'];
    }
    
    
    $data['report'] = old('report');
    if(!$data['report']){
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['note'] = old('note');
    if(!$data['note']){
        $data['note'] = isset($form_data['note'])?$form_data['note']:'';
    }
    
    $data['client'] = old('client');
    if(!$data['client']){
        $data['client'] = isset($form_data['client'])?$form_data['client']:'';
    }
    
    $data['client_name'] = old('client_name');
    if(!$data['client_name']){
        $data['client_name'] = isset($form_data['client_name'])?$form_data['client_name']:'';
    }
    
    $data['is_note'] = old('is_note');
    if(!$data['is_note']){
        $data['is_note'] = isset($form_data['is_note'])?$form_data['is_note']:'';
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
                    amount: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                    },
                    interest: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    },
                    expected_interest: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    }
                },
                messages: {
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters",
                    interest: "Please enter a value between 0.1 and 18",
                    expected_interest: "Please enter a value between 0.1 and 18"
                }
            });
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
            <h2 class="page-title">PREMIUM CALCULATORS</h2>
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
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Child Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('child_name') ? ' is-invalid' : '' }}" name="child_name" value="{{$data['child_name']}}" maxlength="30" required>

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
                                    <input type="text" class="form-control {{ $errors->has('current_age') ? ' is-invalid' : '' }}" id="current_age" name="current_age" value="{{$data['current_age']}}"   required>
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
                                            <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio1" value="Education" @if($data['fund_requirement_purpose'] == 'Education') checked @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-2" for="inlineRadio2">Marriage
                                            <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio2" value="Marriage" @if($data['fund_requirement_purpose'] == 'Marriage') checked @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-2" for="inlineRadio3">Investment
                                            <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio3" value="Investment" @if($data['fund_requirement_purpose'] == 'Investment') checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Fund Required Age</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('fund_required_age') ? ' is-invalid' : '' }}" name="fund_required_age" value="{{$data['fund_required_age']}}"   required>
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
                                    <input type="number" class="form-control {{ $errors->has('fund_required_amount') ? ' is-invalid' : '' }}" name="fund_required_amount" id="fund_required_amount" value="{{$data['fund_required_amount']}}" maxlength="10"  required>
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
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{$data['investment_amount']}}" maxlength="10" >
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
                                    <input type="text" class="form-control number {{ $errors->has('inflation_rate') ? ' is-invalid' : '' }}" name="inflation_rate" value="{{$data['inflation_rate']}}"   required>
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
                                    <input type="text" class="form-control number {{ $errors->has('return_rate') ? ' is-invalid' : '' }}" name="return_rate" id="return_rate" value="{{$data['return_rate']}}" >
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
                                    <h6 class="text-muted titleBlueUnderline">Assumed Rate of Return (Fresh Investment):</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 1</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('return_rate_1') ? ' is-invalid' : '' }}" name="return_rate_1" value="{{$data['return_rate_1']}}"  required>
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
                                    <input type="text" class="form-control number {{ $errors->has('return_rate_2') ? ' is-invalid' : '' }}" name="return_rate_2" value="{{$data['return_rate_2']}}" >
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

                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if($data['client']=='1') checked  @endif> 
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
                                {{-- <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                            <label class="sqarecontainer">View Detailed Graph
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1" @if($form_data['is_graph']=='1') checked  @endif> 
                                                <span class="checkmark"></span>
                                            </label>
                                    </div>
                                </div> --}}
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
                                            @if(session()->get('child_edu_marriage_expenses_form_id'))
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            @else
                                                <a href="{{route('frontend.childEducation')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
        </div></section>

@endsection
