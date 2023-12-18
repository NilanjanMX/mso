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

        $('input[type=radio][name=include_step_up]').change(function() {
            if (this.value == 'yes') {
                $('input[name=step_up_rate]').removeAttr("disabled");
            }
            else if (this.value == 'no') {
                $('input[name=step_up_rate]').attr("disabled", true);
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
                amount: {
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
                interest1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                interest2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                step_up_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [1, 25]
                },
            },
            messages:{
                amount: "Please enter a value between 100 and 9,99,99,99,999.",
                period: "Please enter no more than 2 characters.",
                interest1: "Please enter a value between 0.10 - 15.00%.",
                interest2: "Please enter a value between 0.10 - 15.00%.",
                step_up_rate: "Please enter a value between 1.00 - 25.00%."
            }
        });
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

    @if(old('include_step_up')=='yes')
        <script>
            //$('input[name="step_up_rate"]').prop("readonly", false);
            $('input[name=step_up_rate]').removeAttr("disabled");
        </script>
    @else
        <script>
            //$('input[name="step_up_rate"]').prop("readonly", true);
            $('input[name=step_up_rate]').attr("disabled", true);
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
                    <h3 class="smalllineHeading">SIP Required For Target Future Value</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.sipRequiredForTargetFutureValue_output')}}" method="post">
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Target Amount</label>
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
                                    <label class="col-sm-5 col-form-label">SIP Period</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{old('period')}}"  >
                                        <div class="cal-icon">
                                            Yrs
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
                                        <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
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
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Include Step-Up Comparison</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-1">Yes
                                                <input type="radio" name="include_step_up" value="yes" @if(old('include_step_up')=='yes') checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-1">No
                                                <input type="radio" name="include_step_up" value="no" @if(old('include_step_up')=='yes')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div> 
                                        
                                        @if ($errors->has('include_step_up'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('include_step_up') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Step - Up % Every Year</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control {{ $errors->has('step_up_rate') ? ' is-invalid' : '' }}" name="step_up_rate" value="{{old('step_up_rate')}}" >
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('step_up_rate'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('step_up_rate') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Include Cost of Delay Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-1">Yes
                                                <input type="radio" name="include_cost_delay_report" value="yes" @if(old('include_cost_delay_report')=='yes') checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer mb-0 mt-1">No
                                                <input type="radio" name="include_cost_delay_report" value="no" @if(old('include_cost_delay_report')=='yes')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
    
                                        @if ($errors->has('include_cost_delay_report'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('include_cost_delay_report') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
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
    
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                            <label class="sqarecontainer">View Detailed Graph
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1"> 
                                                <span class="checkmark"></span>
                                            </label>
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
            <!--<div class="btm-shape-prt">-->
            <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
            <!--</div>-->
        </div></section>

@endsection
