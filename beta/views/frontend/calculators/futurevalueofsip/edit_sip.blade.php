@extends('layouts.frontend')


@php
    
    $data['amount'] = old('amount');
    if (!$data['amount']) {
        $data['amount'] = $form_data['amount'];
    }
    
    $data['period'] = old('period');
    if (!$data['period']) {
        $data['period'] = $form_data['period'];
    }
    
    $data['interest1'] = old('interest1');
    if (!$data['interest1']) {
        $data['interest1'] = $form_data['interest1'];
    }
    
    $data['interest2'] = old('interest2');
    if (!$data['interest2']) {
        $data['interest2'] = $form_data['interest2'];
    }
    
    $data['report'] = old('report');
    if (!$data['report']) {
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest');
    if (!$data['suggest']) {
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['note'] = old('note');
    if (!$data['note']) {
        $data['note'] = $form_data['note'];
    }
    
    $data['client'] = old('client');
    if (!$data['client']) {
        $data['client'] = $form_data['client'];
    }
    
    $data['is_note'] = old('is_note');
    if (!$data['is_note']) {
        $data['is_note'] = $form_data['is_note'];
    }
    
    $data['is_graph'] = old('is_graph');
    if (!$data['is_graph']) {
        $data['is_graph'] = $form_data['is_graph'];
    }
    
    $data['include_performance'] = old('include_performance');
    if (!$data['include_performance']) {
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type');
    if (!$data['suggestedlist_type']) {
        $data['suggestedlist_type'] = $suggestedlist_type;
    }
    
@endphp


@section('js_after')
    <script>
        $('input[type=radio][name=include_step_up]').change(function() {
            if (this.value == 'yes') {
                //$('input[name="step_up_rate"]').prop("readonly", false);
                $('input[name=step_up_rate]').removeAttr("disabled");
            } else if (this.value == 'no') {
                //$('input[name="step_up_rate"]').prop("readonly", true);
                $('input[name=step_up_rate]').attr("disabled", true);
            }
        });
        $("#is_client").click(function() {
            if ($(this).is(':checked')) {
                $('input[name="clientname"]').prop("readonly", false);
            } else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });

        $("#is_note").click(function() {
            if ($(this).is(':checked')) {
                $('textarea[name="note"]').prop("readonly", false);
            } else {
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
                        range: [100, 9999999999]
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
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
                },
                messages: {
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters.",
                    interest1: "Please enter a value between 0.10 - 15.00%.",
                    interest2: "Please enter a value between 0.10 - 15.00%."
                }
            });
        });

        function activateMe(vl) {
            console.log("stepup " + stepup);
            if (stepup == 0) {
                document.getElementById("step_up_rate").setAttribute("disabled", true);
                document.getElementById("step_up_amount").setAttribute("disabled", true);
                return;
            }
            //console.log(document.getElementsByName("steuup")[1].value);
            if (vl == 2) {
                document.getElementById("step_up_rate").setAttribute("disabled", true);
                document.getElementById("step_up_amount").removeAttribute("disabled");
            } else {
                document.getElementById("step_up_amount").setAttribute("disabled", true);
                document.getElementById("step_up_rate").removeAttribute("disabled");
            }

        }
        var stepup = 0;

        function SetstepUp(val) {
            stepup = val;
            if (val == 0) {
                document.getElementById("step_up_fields").style.display = 'none';
                // document.getElementById("stepupFirst").checked = false;
                // document.getElementById("stepupSecond").checked = false;
                document.getElementById("step_up_rate").setAttribute("disabled", true);
                document.getElementById("step_up_amount").setAttribute("disabled", true);
            } else {
                document.getElementById("step_up_fields").style.display = 'block';
                // document.getElementById("stepupFirst").checked = true;
            }
        }
        @if ($form_data['include_step_up'] == 'yes')
            SetstepUp(1);
        @else
            SetstepUp(0);
        @endif


        function changeNote() {
            var note = document.getElementById('note').value;

            document.getElementById('note_total_count').innerHTML = note.length;
        }

        changeNote();
    </script>
    @if ($data['client'] == 1)
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif

    @if ($data['is_note'] == 1)
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif

    <script type="text/javascript">
        @if (isset($data['suggest']))
            @if ($data['suggest'] == '1')
                $(document).ready(function() {
                    setTimeout(function() {
                        $('.include-performance-container').show(500);
                    }, 500)


                    @if ($data['suggestedlist_type'] == 'createlist')
                        $('.customlist-suggested-scheme-container').css('display', 'none');
                        $('.categorylist-suggested-scheme-container').css('display', 'none');
                        $('.createlist-suggested-scheme-container').css('display', 'block');
                    @elseif ($data['suggestedlist_type'] == 'customlist')
                        $('.customlist-suggested-scheme-container').css('display', 'block');
                        $('.categorylist-suggested-scheme-container').css('display', 'none');
                        $('.createlist-suggested-scheme-container').css('display', 'none');
                    @elseif ($data['suggestedlist_type'] == 'categorylist')
                        $('.customlist-suggested-scheme-container').css('display', 'none');
                        $('.categorylist-suggested-scheme-container').css('display', 'block');
                        $('.createlist-suggested-scheme-container').css('display', 'none');
                    @endif
                });
            @endif
        @endif
    </script>
    <link rel="stylesheet" href="{{ asset('') }}/f/css/calculator.css">

    @if ($form_data['include_step_up'] == 'yes')
        <script>
            //$('input[name="step_up_rate"]').prop("readonly", false);
            $('input[name=step_up_rate]').removeAttr("disabled");
            $('input[name=step_up_amount]').removeAttr("disabled");
        </script>
    @else
        <script>
            //$('input[name="step_up_rate"]').prop("readonly", true);
            $('input[name=step_up_rate]').attr("disabled", true);
            $('input[name=step_up_amount]').attr("disabled", true);
        </script>
    @endif
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

     <!--<div class="banner">-->
     <!--       <div class="container">-->
     <!--           <div class="row">-->
     <!--               <div class="col-md-12 text-center">-->
     <!--                   <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>-->
     <!--               </div>-->
     <!--           </div>-->
     <!--       </div>-->
     <!--   </div>-->

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Future Value of SIP</h3>
                    @include('frontend.calculators.common_bio')
                    <br>

                    <form class="js-validate-form" action="{{ route('frontend.futureValueOfSipOutput') }}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Monthly SIP Amount</label>
                                <div class="col-sm-7">
                                    <input type="text"
                                        class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}"
                                        name="amount" value="{{ $data['amount'] }}" maxlength="10">
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
                                    <input type="text"
                                        class="form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}"
                                        name="period" value="{{ $data['period'] }}">
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
                                    <input type="text"
                                        class="form-control number {{ $errors->has('interest1') ? ' is-invalid' : '' }}"
                                        name="interest1" value="{{ $data['interest1'] }}">
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
                                    <input type="text"
                                        class="form-control number {{ $errors->has('interest2') ? ' is-invalid' : '' }}"
                                        name="interest2" value="{{ $data['interest2'] }}">
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
                                            <input type="radio" name="include_step_up" value="yes" onchange="SetstepUp(1)" @if ($form_data['include_step_up'] == 'yes') checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-1">No
                                            <input type="radio" name="include_step_up" value="no" onchange="SetstepUp(0)" @if ($form_data['include_step_up'] == 'no') checked @endif>
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

                            <div id="step_up_fields" style='display:none;'>
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label class="checkLinecontainer mb-0 mt-2 pl-0"><span>Step - Up % Every Year</span>
                                            <input class="from-control" type="radio" id="stepupFirst" @if (isset($form_data['step_up_rate']) && $form_data['step_up_rate'] != '') checked @endif value=1 name="steuup" onchange="activateMe(1)">
                                            <span class="checkmark" style="left: auto;right: 0px;margin-right: 110px;"></span>
                                        </label>
                                    </div>
                                    
                                    <div class="col-sm-7">

                                        <input type="text" class="form-control" name="step_up_rate" id="step_up_rate"
                                            value="{{ $form_data['step_up_rate'] ?? '' }}">
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
                                    <div class="col-sm-5">
                                        <label class="checkLinecontainer mb-0 mt-2 pl-0"><span>Step - Up Amount Every Year</span>
                                            <input class="from-control" type="radio" id="stepupSecond" value=2 @if (isset($form_data['step_up_amount']) && $form_data['step_up_amount'] != '') checked @endif name="steuup" onchange="activateMe(2)">
                                            <span class="checkmark" style="left: auto;right: 0px;margin-right: 110px;"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">

                                        <input type="text" class="form-control" name="step_up_amount"
                                            id="step_up_amount" value="{{ $form_data['step_up_amount'] ?? '' }}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                        @if ($errors->has('step_up_amount'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('step_up_amount') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1"
                                            @if ($form_data['client'] == '1') checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                    <input placeholder="Add Client Name" type="text"
                                        class="form-control {{ $errors->has('client') ? ' is-invalid' : '' }}"
                                        name="clientname" value="{{ $form_data['clientname'] ?? '' }}" maxlength="30">
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
                                        <input id="is_graph" type="checkbox" name="is_graph" value="1"
                                            @if ($form_data['is_graph'] == '1') checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label class="sqarecontainer">Add Comments (If any)
                                        <input id="is_note" type="checkbox" name="is_note" value="1"
                                            @if (isset($form_data) && $form_data['is_note'] == '1') checked @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2"
                                        id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{ $form_data['note'] }}</textarea>
                                    <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters
                                        left.</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Get Report</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Summary Report
                                            <input class="form-check-input" type="radio" name="report"
                                                id="inlineRadio1" value="summary"
                                                @if ($data['report'] == 'summary') checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Detailed Report
                                            <input class="form-check-input" type="radio" name="report"
                                                id="inlineRadio2" value="detailed"
                                                @if ($data['report'] == 'summary') @else checked @endif>
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
                                        <button type="button" onclick="window.history.go(-1); return false;"
                                            class="btn banner-btn whitebg mx-3">
                                            Back</button>
                                        @if(session()->get('future_value_of_sip_new'))
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                        @else
                                            <a href="{{route('frontend.futureValueOfSipIndex')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
        <!--    <img class="img-fluid" src="{{ asset('') }}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>
@endsection
