@extends('layouts.frontend')
@php

    
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
    //$('input[name="clientname"]').prop("readonly", true);
    //$('input[name="step_up_rate"]').prop("readonly", true);
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

    var calculation_type = $("input[name='investment_type']").val();
        if(calculation_type == "SIP") {
            $(".calculate-sip").show();
            $(".calculate-sip input").prop("disabled", false);
            $(".calculate-lumpsum").hide();
            $(".calculate-lumpsum input").prop("disabled", true);
        }
        if(calculation_type == "lumpsum") {
            $(".calculate-sip").hide();
            $(".calculate-sip input").prop("disabled", true);
            $(".calculate-lumpsum").show();
            $(".calculate-lumpsum input").prop("disabled", false);
        }

        $("input[name='investment_type']").on('change', function () {
            calculation_type = $(this).val();
            if(calculation_type == "SIP") {
                $(".calculate-sip").show();
                $(".calculate-sip input").prop("disabled", false);
                $(".calculate-lumpsum").hide();
                $(".calculate-lumpsum input").prop("disabled", true);
            }
            if(calculation_type == "lumpsum") {
                $(".calculate-sip").hide();
                $(".calculate-sip input").prop("disabled", true);
                $(".calculate-lumpsum").show();
                $(".calculate-lumpsum input").prop("disabled", false);
            }
        });

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
                target_amount: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                investment_amount: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                sip_interest_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                onetime_interest_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
            }
        });
</script>
    @if(old('client')=='')
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
    @if($form_data['investment_type']=="SIP")
    <script>
        $(".calculate-sip").show();
        $(".calculate-sip input").prop("disabled", false);
        $(".calculate-lumpsum").hide();
        $(".calculate-lumpsum input").prop("disabled", true);
    </script>
    @else
    <script>
        $(".calculate-sip").hide();
        $(".calculate-sip input").prop("disabled", true);
        $(".calculate-lumpsum").show();
        $(".calculate-lumpsum input").prop("disabled", false);
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
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">SIP / Lumpsum Required For Target Future Value</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.sipLumpsumInvestmentTargetFutureValueOutput')}}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Target Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('target_amount') ? ' is-invalid' : '' }}" name="target_amount" value="{{$form_data['target_amount']}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('target_amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('target_amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{$form_data['investment_period']}}"  required>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('investment_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('investment_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Calculate</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-1" for="inlineRadio1">Lumpsum Required
                                            <input class="form-check-input" type="radio" name="investment_type" id="inlineRadio1" value="lumpsum" @if($form_data['investment_type']=='SIP') @else checked  @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-1" for="inlineRadio2">SIP Required
                                            <input class="form-check-input" type="radio" name="investment_type" id="inlineRadio2" value="SIP" @if($form_data['investment_type']=='SIP') checked  @endif >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row calculate-sip">
                                <label class="col-sm-5 col-form-label">Lumpsum Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{$form_data['investment_amount']}}"  required>
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
                            <div class="form-group row calculate-sip">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return (Lumpsum)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('onetime_interest_rate') ? ' is-invalid' : '' }}" name="onetime_interest_rate" value="{{$form_data['onetime_interest_rate']}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('onetime_interest_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('onetime_interest_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row calculate-sip">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return (SIP)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('sip_interest_rate') ? ' is-invalid' : '' }}" name="sip_interest_rate" value="{{$form_data['sip_interest_rate']}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('sip_interest_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_interest_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row calculate-lumpsum">
                                <label class="col-sm-5 col-form-label">SIP Amount Per Month</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{$form_data['investment_amount']}}"  required>
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
                            <div class="form-group row calculate-lumpsum">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return (SIP)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('sip_interest_rate') ? ' is-invalid' : '' }}" name="sip_interest_rate" value="{{$form_data['sip_interest_rate']}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('sip_interest_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_interest_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row calculate-lumpsum">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return (Lumpsum)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('onetime_interest_rate') ? ' is-invalid' : '' }}" name="onetime_interest_rate" value="{{$form_data['onetime_interest_rate']}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('onetime_interest_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('onetime_interest_rate') }}</strong>
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
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{$form_data['clientname'] ?? ''}}" maxlength="30">
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
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if(isset($form_data) && $form_data['is_note']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$form_data['note'] ?? ''}}</textarea>
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
                            @include('frontend.calculators.suggested.edit_form')
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
                                            @if(session()->get('sip_lumpsum_investment_required_target_value_id'))
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            @else
                                                <a href="{{route('frontend.sipLumpsumInvestmentTargetFutureValue')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
