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

    $data['monthly_transfer_mode'] = old('monthly_transfer_mode');
    if(!$data['monthly_transfer_mode']){
        $data['monthly_transfer_mode'] = $form_data['monthly_transfer_mode'];
    }

    $data['fixed_transfer_mode'] = old('fixed_transfer_mode');
    if(!$data['fixed_transfer_mode']){
        $data['fixed_transfer_mode'] = isset($form_data['fixed_transfer_mode'])?$form_data['fixed_transfer_mode']:'';
    }


@endphp

@section('js_after')
    <script>
        
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
            }

        });
        
        

        $('input[type=radio][name=fixed_transfer_mode]').change(function() {
            if(this.value == 'FA'){
                $('input[name="fixed_amount"]').prop("readonly", false);
                $('input[name="fixed_percent"]').prop("readonly", true);
                $('input[name="fixed_percent"]').val('');

                $('input[name="fixed_percent"]').attr('required', false);
                $('input[name="fixed_amount"]').attr('required', true);
            }else{
                $('input[name="fixed_amount"]').prop("readonly", true);
                $('input[name="fixed_percent"]').prop("readonly", false);
                $('input[name="fixed_amount"]').val('');

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
                    range: [100, 9999999999]
                },
                investment_period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99]
                },
                debt_fund: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 10.00]
                },
                equity_fund: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                fixed_percent: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 2.00]
                },
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
    
    @if($data['monthly_transfer_mode']=='FT')
        <script>
            $(document).ready(function() {
    
                $('#monthly_transfer_inactive_field').hide();
                $('#monthly_transfer_active_field').show();
            });
        </script>
    @else
        <script>
            $(document).ready(function() {
                $('#monthly_transfer_active_field').hide();
                $('#monthly_transfer_inactive_field').show();
            });
        </script>
    @endif
    
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
                <h3 class="smalllineHeading">STP Required For Target Future Value</h3>
                
                @include('frontend.calculators.common_bio')
                <br>
                <form class="js-validate-form" action="{{route('frontend.stpRequiredForTargetFutureValue_output')}}" method="post">
                    <div class="card sip-calculator singleLineHolder calculatorFormShape">

                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Target Future Value</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control {{ $errors->has('target_amount') ? ' is-invalid' : '' }}" name="target_amount" value="{{old('target_amount', isset($data['target_amount'])?$data['target_amount']:'')}}" >
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
                                <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{old('investment_period', isset($data['investment_period'])?$data['investment_period']:'')}}" >
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
                            <div class="col-sm-12">
                                <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Debt Fund</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control number {{ $errors->has('debt_fund') ? ' is-invalid' : '' }}" name="debt_fund" value="{{old('debt_fund', isset($data['debt_fund'])?$data['debt_fund']:'')}}" required >
                                <div class="cal-icon">
                                    %
                                </div>
                                @if ($errors->has('debt_fund'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('debt_fund') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Equity Fund</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control number {{ $errors->has('equity_fund') ? ' is-invalid' : '' }}" name="equity_fund" value="{{old('equity_fund', isset($data['equity_fund'])?$data['equity_fund']:'')}}" required  >
                                <div class="cal-icon">
                                    %
                                </div>
                                @if ($errors->has('equity_fund'))
                                    <div class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('equity_fund') }}</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Monthly Transfer Mode</label>
                            <div class="col-sm-7">
                                <div class="form-check form-check-inline">
                                    <label class="checkLinecontainer mb-0 mt-2" for="monthly_transfer_mode1">Capital Appreciation
                                        <input class="form-check-input" type="radio" id="monthly_transfer_mode1" name="monthly_transfer_mode" value="CA"  @if(old('monthly_transfer_mode', isset($data['monthly_transfer_mode'])?$data['monthly_transfer_mode']:'')=='CA') checked  @endif>
                                        <span class="checkmark"></span>
                                    </label> 
                                </div>
                                <div class="form-check form-check-inline">
                                    <label class="checkLinecontainer mb-0 mt-2" for="monthly_transfer_mode2">Fixed Transfer
                                        <input class="form-check-input" type="radio" id="monthly_transfer_mode2" name="monthly_transfer_mode" value="FT" @if(old('monthly_transfer_mode', isset($data['monthly_transfer_mode'])?$data['monthly_transfer_mode']:'')=='FT')  checked  @endif >
                                        <span class="checkmark"></span>
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
                            <label class="col-sm-5 col-form-label">Monthly Transfer</label>
                            <div class="col-sm-7" id="monthly_transfer_inactive_field">
                                <input type="text" class="form-control" >
                                <div class="cal-icon">
                                    %
                                </div>
                            </div>
                            <div class="col-sm-7" id="monthly_transfer_active_field">
                                <div class="form-group row">
                                    <div class="col-sm-6">
                                        <div class="form-check pl-0">
                                            <label class="checkLinecontainer mb-0 mt-2" for="fixed_transfer_mode3">Fixed %
                                                <input class="form-check-input" type="radio" id="fixed_transfer_mode3" name="fixed_transfer_mode" value="FP" @if($data['fixed_transfer_mode']=='FP') checked @else   @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control {{ $errors->has('fixed_percent') ? ' is-invalid' : '' }}" name="fixed_percent" autocomplete="off" min="0.1" max="2.0" @if(old('fixed_transfer_mode', isset($data['fixed_transfer_mode'])?$data['fixed_transfer_mode']:'')=='FP') value="{{ old('fixed_percent', isset($data['fixed_percent'])?$data['fixed_percent']:'') }}" @endif @if(old('fixed_transfer_mode', isset($data['fixed_transfer_mode'])?$data['fixed_transfer_mode']:'')=='FP') required @else readonly @endif>
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
                                        <div class="form-check pl-0">
                                            <label class="checkLinecontainer mb-0 mt-2" for="fixed_transfer_mode5">Fixed Amount
                                                <input class="form-check-input"  type="radio" id="fixed_transfer_mode5" name="fixed_transfer_mode" value="FA" @if(old('fixed_transfer_mode', isset($data['fixed_transfer_mode'])?$data['fixed_transfer_mode']:'')=='FA') checked  @endif >
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control {{ $errors->has('fixed_amount') ? ' is-invalid' : '' }}" name="fixed_amount" autocomplete="off" @if(old('fixed_transfer_mode', isset($data['fixed_transfer_mode'])?$data['fixed_transfer_mode']:'')=='FA') value="{{ old('fixed_amount', isset($data['fixed_amount'])?$data['fixed_amount']:'') }}" @endif  min="100" @if(old('fixed_transfer_mode', isset($data['fixed_transfer_mode'])?$data['fixed_transfer_mode']:'')=='FA') required @else readonly @endif >
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
                                        @if(session()->get('stp_required_for_target_future_value_form_id'))
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                        @else
                                            <a href="{{route('frontend.stpRequiredForTargetFutureValue')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
</section>

@endsection
