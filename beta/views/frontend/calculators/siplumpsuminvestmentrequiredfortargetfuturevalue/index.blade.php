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
                                    <input type="text" class="form-control {{ $errors->has('target_amount') ? ' is-invalid' : '' }}" name="target_amount" value="{{old('target_amount')}}" maxlength="10"  required>
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
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{old('investment_period')}}"  required>
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
                                            <input class="form-check-input" type="radio" name="investment_type" id="inlineRadio1" value="lumpsum" @if(old('investment_type')=='SIP') @else checked  @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-1" for="inlineRadio2">SIP Required
                                            <input class="form-check-input" type="radio" name="investment_type" id="inlineRadio2" value="SIP" @if(old('investment_type')=='SIP') checked  @endif >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row calculate-sip">
                                <label class="col-sm-5 col-form-label">Lumpsum Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{old('investment_amount')}}"  required>
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
                                    <input type="text" class="form-control number {{ $errors->has('onetime_interest_rate') ? ' is-invalid' : '' }}" name="onetime_interest_rate" value="{{old('onetime_interest_rate')}}"   >
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
                                    <input type="text" class="form-control number {{ $errors->has('sip_interest_rate') ? ' is-invalid' : '' }}" name="sip_interest_rate" value="{{old('sip_interest_rate')}}"   >
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
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{old('investment_amount')}}"  required>
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
                                    <input type="text" class="form-control number {{ $errors->has('sip_interest_rate') ? ' is-invalid' : '' }}" name="sip_interest_rate" value="{{old('sip_interest_rate')}}"   >
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
                                    <input type="text" class="form-control number {{ $errors->has('onetime_interest_rate') ? ' is-invalid' : '' }}" name="onetime_interest_rate" value="{{old('onetime_interest_rate')}}"   >
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
                            {{-- <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                        <label class="sqarecontainer">View Detailed Graph
                                            <input id="is_graph" type="checkbox" name="is_graph" value="1" @if(isset($form_data) && $form_data['is_graph']=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                </div>
                            </div> --}}
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
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
