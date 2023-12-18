@extends('layouts.frontend')

@php

    $data = $form_data;
    // dd($data['asset_name']);
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
        $data['note'] = $form_data['note'];
    }
    
    $data['client'] = old('client');
    if(!$data['client']){
        $data['client'] = $form_data['client'];
    }
    
    $data['client_name'] = old('client_name');
    if(!$data['client_name']){
        $data['client_name'] = $form_data['clientname'];
    }
    
    $data['is_note'] = old('is_note');
    if(!$data['is_note']){
        $data['is_note'] = $form_data['is_note'];
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
                insurance_policy_annual_premium: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 99999999]
                },
                sum_assured: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [10000, 9999999999]
                },
                policy_term: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99]
                },
                equivalent_insurance_term_policy_premium: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [10, 99999999]
                },
                rate_of_return_investments: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                rate_of_return_insurance: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                }
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
                <h3 class="smalllineHeading">Insurance vs. Term Cover With Annual SIP</h3>
                
                @include('frontend.calculators.common_bio')
                <br>
                <form class="js-validate-form" action="{{route('frontend.insuranceTermCover_output')}}" method="post">
                    <div class="card sip-calculator singleLineHolder calculatorFormShape">
                        @csrf

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Insurance Policy Annual Premium</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control {{ $errors->has('insurance_policy_annual_premium') ? ' is-invalid' : '' }}" name="insurance_policy_annual_premium" value="{{old('insurance_policy_annual_premium', isset($data['insurance_policy_annual_premium'])?$data['insurance_policy_annual_premium']:'')}}" maxlength="10"  required>
                                <div class="cal-icon">
                                    ₹
                                </div>
                                @if ($errors->has('insurance_policy_annual_premium'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('insurance_policy_annual_premium') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Sum Assured / Death Benefit</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control {{ $errors->has('sum_assured') ? ' is-invalid' : '' }}" name="sum_assured" value="{{old('sum_assured', isset($data['sum_assured'])?$data['sum_assured']:'')}}" maxlength="10"  required>
                                <div class="cal-icon">
                                    ₹
                                </div>
                                @if ($errors->has('sum_assured'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('sum_assured') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Policy Term</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control maxtwodigit {{ $errors->has('policy_term') ? ' is-invalid' : '' }}" name="policy_term" value="{{old('policy_term', isset($data['policy_term'])?$data['policy_term']:'')}}"  required>
                                <div class="cal-icon">
                                    Yrs
                                </div>
                                @if ($errors->has('policy_term'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('policy_term') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Equivalent Insurance Policy Term Premium</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control {{ $errors->has('equivalent_insurance_term_policy_premium') ? ' is-invalid' : '' }}" name="equivalent_insurance_term_policy_premium" value="{{old('equivalent_insurance_term_policy_premium', isset($data['equivalent_insurance_term_policy_premium'])?$data['equivalent_insurance_term_policy_premium']:'')}}" maxlength="10"  required>
                                <div class="cal-icon">
                                    ₹
                                </div>
                                @if ($errors->has('equivalent_insurance_term_policy_premium'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('equivalent_insurance_term_policy_premium') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Assumed Rate Of Return On Investments</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control number {{ $errors->has('rate_of_return_investments') ? ' is-invalid' : '' }}" name="rate_of_return_investments" value="{{old('rate_of_return_investments', isset($data['rate_of_return_investments'])?$data['rate_of_return_investments']:'')}}"   required>
                                <div class="cal-icon">
                                    %
                                </div>
                                @if ($errors->has('rate_of_return_investments'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rate_of_return_investments') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Assumed Rate Of Return On Insurance</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control number {{ $errors->has('rate_of_return_insurance') ? ' is-invalid' : '' }}" name="rate_of_return_insurance" value="{{old('rate_of_return_insurance', isset($data['rate_of_return_insurance'])?$data['rate_of_return_insurance']:'')}}"   required>
                                <div class="cal-icon">
                                    %
                                </div>
                                @if ($errors->has('rate_of_return_insurance'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('rate_of_return_insurance') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card sip-calculator singleLineHolder">
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
                                        <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><i class="fa fa-angle-left"></i> Back</button>
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
</section>

@endsection
