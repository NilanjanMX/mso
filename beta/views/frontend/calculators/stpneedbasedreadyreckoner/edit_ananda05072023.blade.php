@extends('layouts.frontend')

@php
    $data['period1'] = old('period1');
    if(!$data['period1']){
        $data['period1'] = $form_data['period1'];
    }
    
    $data['period2'] = old('period2');
    if(!$data['period2']){
        $data['period2'] = $form_data['period2'];
    }
    
    $data['period3'] = old('period3');
    if(!$data['period3']){
        $data['period3'] = $form_data['period3'];
    }
    
    $data['period4'] = old('period4');
    if(!$data['period4']){
        $data['period4'] = $form_data['period4'];
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
        $('input[type=radio][name=monthly_transfer_mode]').change(function() {
            if (this.value == 'FT') {
                $('#monthly_transfer_inactive_field').hide();
                $('#monthly_transfer_active_field').show();
                var fixed_transfer_mode = $('input[name="fixed_transfer_mode"]:checked').val();
                if(fixed_transfer_mode=='FA'){
                    $('input[name="fixed_amount"]').prop("readonly", false);
                    $('input[name="fixed_percent"]').prop("readonly", true);

                    $('input[name="fixed_percent"]').attr('required', false);
                    $('input[name="fixed_amount"]').attr('required', true);
                }else{
                    $('input[name="fixed_amount"]').prop("readonly", true);
                    $('input[name="fixed_amount"]').attr('required', false);

                    $('input[name="fixed_percent"]').prop("readonly", false);
                    $('input[name="fixed_percent"]').attr('required', true);
                }
            }
            else if (this.value == 'CA') {
                $('#monthly_transfer_active_field').hide();
                $('#monthly_transfer_inactive_field').show();
                $("#monthly_transfer_inactive_field :input").prop("disabled", false);
            }

        });

        $('input[type=radio][name=fixed_transfer_mode]').change(function() {
            if(this.value == 'FA'){
                $('input[name="fixed_amount"]').prop("readonly", false);
                $('input[name="fixed_percent"]').prop("readonly", true);

                $('input[name="fixed_percent"]').attr('required', false);
                $('input[name="fixed_amount"]').attr('required', true);
            }else{
                $('input[name="fixed_amount"]').prop("readonly", true);
                $('input[name="fixed_percent"]').prop("readonly", false);

                $('input[name="fixed_percent"]').attr('required', true);
                $('input[name="fixed_amount"]').attr('required', false);
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
                debt_rate1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 10.00],
                },
                debt_rate2: {
                    required: false,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 10.00],
                },
                debt_rate3: {
                    required: false,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 10.00],
                },
                equity_rate1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                equity_rate2: {
                    required: function(element){
                        return $("input[name='debt_rate2']").val()!="";
                    },
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                equity_rate3: {
                    required: function(element){
                        return $("input[name='debt_rate3']").val()!="";
                    },
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                period1: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period2: {
                    required: false,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period3: {
                    required: false,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period4: {
                    required: false,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period5: {
                    required: false,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                }
            },
            messages: {
                period1: { required: "Please fill at least one." },
                period2: { required: "All fields are mandatory." },
                period3: { required: "All fields are mandatory." },
                period4: { required: "All fields are mandatory." },
                period5: { required: "All fields are mandatory." },
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
    @if(isset($form_data['monthly_transfer_mode']) && $form_data['monthly_transfer_mode']=='FT')
    <script>
        $('#monthly_transfer_inactive_field').hide();
        $('#monthly_transfer_active_field').show();
    </script>
    @else
    <script>
        $('#monthly_transfer_active_field').hide();
        $('#monthly_transfer_inactive_field').show();
        $("#monthly_transfer_inactive_field :input").prop("disabled", false);
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
                <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">STP Need Based Ready Reckoner</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.stpGoalPlanningValueReadyRecoknerOutput')}}" method="post">
                            <div class="card sip-calculator singleLineHolder">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Target Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control {{ $errors->has('target_amount') ? ' is-invalid' : '' }}" name="target_amount" value="{{$form_data['target_amount'] ?? ''}}" >
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
                                    <div class="col-sm-4">
                                        <h6 class="text-muted">Assumed Rate Of Return:</h6>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="col-sm-4"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Debt</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('debt_rate1') ? ' is-invalid' : '' }}" name="debt_rate1" value="{{$form_data['debt_rate1'] ?? ''}}"  required >
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('debt_rate2') ? ' is-invalid' : '' }}" name="debt_rate2" value="{{$form_data['debt_rate2'] ?? ''}}"  required >
                                            <input type="text" class="form-control number {{ $errors->has('debt_rate3') ? ' is-invalid' : '' }}" name="debt_rate3" value="{{$form_data['debt_rate3'] ?? ''}}"  required >
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('debt_rate1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('debt_rate1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('debt_rate2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('debt_rate2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('debt_rate3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('debt_rate3') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Equity</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('equity_rate1') ? ' is-invalid' : '' }}" name="equity_rate1" value="{{$form_data['equity_rate1'] ?? ''}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('equity_rate2') ? ' is-invalid' : '' }}" name="equity_rate2" value="{{$form_data['equity_rate2'] ?? ''}}"  required>
                                            <input type="text" class="form-control number {{ $errors->has('equity_rate3') ? ' is-invalid' : '' }}" name="equity_rate3" value="{{$form_data['equity_rate3'] ?? ''}}"  required>
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('equity_rate1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('equity_rate1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('equity_rate2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('equity_rate2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('equity_rate3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('equity_rate3') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Investment Period</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period1') ? ' is-invalid' : '' }}" name="period1" value="{{$form_data['period1'] ?? ''}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period2') ? ' is-invalid' : '' }}" name="period2" value="{{$form_data['period2'] ?? ''}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period3') ? ' is-invalid' : '' }}" name="period3" value="{{$form_data['period3'] ?? ''}}"  required>
                                            <input type="text" class="form-control pr-5 mr-1 maxtwodigit {{ $errors->has('period4') ? ' is-invalid' : '' }}" name="period4" value="{{$form_data['period4'] ?? ''}}"  required>
                                            <input type="text" class="form-control maxtwodigit {{ $errors->has('period5') ? ' is-invalid' : '' }}" name="period5" value="{{$form_data['period5'] ?? ''}}"  required>
                                        </div>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                        @if ($errors->has('period1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period3') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period4'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period4') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('period4'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('period4') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monthly Transfer Mode</label>
                                    <div class="col-sm-8">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="monthly_transfer_mode1" name="monthly_transfer_mode" value="CA"  @if(isset($form_data['monthly_transfer_mode']) && $form_data['monthly_transfer_mode']=='CA' || !isset($form_data['monthly_transfer_mode']))  checked  @endif>
                                            <label class="form-check-label" for="monthly_transfer_mode1">
                                                Capital Appreciation
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" id="monthly_transfer_mode2" name="monthly_transfer_mode" value="FT" @if(isset($form_data['monthly_transfer_mode']) && $form_data['monthly_transfer_mode']=='FT')  checked  @endif >
                                            <label class="form-check-label" for="monthly_transfer_mode2">
                                                Fixed Transfer
                                            </label>
                                        </div>
                                        @if ($errors->has('monthly_transfer_mode'))
                                            <div class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('monthly_transfer_mode') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Monthly Transfer</label>
                                    <div class="col-sm-8" id="monthly_transfer_inactive_field">
                                        <input type="text" disabled class="form-control" >
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                    <div class="col-sm-8" id="monthly_transfer_active_field">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="fixed_transfer_mode3" name="fixed_transfer_mode" value="FP" @if(old('fixed_transfer_mode')=='FP')  @else checked  @endif>
                                                    <label class="form-check-label" for="fixed_transfer_mode3">
                                                        Fixed %
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control {{ $errors->has('fixed_percent') ? ' is-invalid' : '' }}" name="fixed_percent"  min="0.1" max="2.0" value="{{ $form_data['fixed_percent'] ?? '' }}" @if(old('fixed_transfer_mode')=='FP') required @else readonly @endif>
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                                @if ($errors->has('fixed_percent'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fixed_percent') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="row mt-1">
                                            <div class="col-sm-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" id="fixed_transfer_mode5" disabled name="fixed_transfer_mode" value="FA" @if(old('fixed_transfer_mode')=='FA') checked  @endif >
                                                    <label class="form-check-label" for="fixed_transfer_mode5">
                                                        Fixed Amount
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control {{ $errors->has('fixed_amount') ? ' is-invalid' : '' }}" name="fixed_amount"  value="" min="100" @if(old('fixed_transfer_mode')=='FA') required @else readonly @endif >
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                                @if ($errors->has('fixed_amount'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('fixed_amount') }}</strong>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                {{-- <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">INCLUDE THE FOLLOWING IN MY REPORT</h6>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="card sip-calculator singleLineHolder">
                                <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if($form_data['client']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{$form_data['clientname']}}" maxlength="30">
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
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$form_data['note']}}</textarea>
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
        </div></section>

@endsection
