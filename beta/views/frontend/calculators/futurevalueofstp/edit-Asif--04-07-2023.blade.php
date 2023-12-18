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
    $('input[type=radio][name=monthly_transfer_mode]').change(function() {
        if (this.value == 'FT') {
            $('#monthly_transfer_inactive_field').hide();
            $('#monthly_transfer_active_field').show();
           var fixed_transfer_mode = $('input[name="fixed_transfer_mode"]:checked').val();
           if(fixed_transfer_mode=='FA'){
                $('input[name="fixed_amount"]').prop("readonly", false);
                //$('input[name="fixed_percent"]').prop("readonly", true);
                $('input[name=fixed_percent]').attr("disabled", true);

                //$('input[name="fixed_percent"]').attr('required', false);
                //$('input[name="fixed_amount"]').attr('required', true);
            }else{
               $('input[name=fixed_amount]').attr("disabled", true);
               //$('input[name="fixed_amount"]').prop("readonly", true);
               //$('input[name="fixed_amount"]').attr('required', false);

               $('input[name="fixed_percent"]').removeAttr("disabled");
               //$('input[name="fixed_percent"]').prop("readonly", false);
               //$('input[name="fixed_percent"]').attr('required', true);
           }
        }
        else if (this.value == 'CA') {
            $('#monthly_transfer_active_field').hide();
            $('#monthly_transfer_inactive_field').show();
        }

    });

    $('input[type=radio][name=fixed_transfer_mode]').change(function() {
        if(this.value == 'FA'){
            $('input[name=fixed_amount]').removeAttr("disabled");
            //$('input[name="fixed_amount"]').prop("readonly", false);
            //$('input[name="fixed_percent"]').prop("readonly", true);
            $('input[name="fixed_percent"]').attr("disabled", true);

            //$('input[name="fixed_percent"]').attr('required', false);
            //$('input[name="fixed_amount"]').attr('required', true);
        }else{
            $('input[name=fixed_amount]').attr("disabled", true);
            //$('input[name="fixed_amount"]').prop("readonly", true);
            //$('input[name="fixed_percent"]').prop("readonly", false);
            $('input[name="fixed_percent"]').removeAttr("disabled");

            //$('input[name="fixed_percent"]').attr('required', true);
            //$('input[name="fixed_amount"]').attr('required', false);
        }
    });
    
    function changeNote(){
        var note = document.getElementById('note').value;
        
        document.getElementById('note_total_count').innerHTML = note.length;
    }
    changeNote();

    $(function () {

jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
}, "You must include two decimal places");

jQuery.validator.addMethod("notGtTwoPercent", function(value, element, param) {
return ( Number(value) <= Number(jQuery(param).val()*2/100) );
}, "Please enter a value between Rs.100 - 2.00% of Initial Investment.");

var validator = $(".js-validate-form").validate({
errorElement: "em",
errorContainer: $("#warning, #summary"),
errorPlacement: function(error, element) {
    error.appendTo(element.parent());
},
rules: {
    initial_investment: {
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
        range: [0.10, 5.00]
    },
    fixed_amount: {
        number: true,
        notGtTwoPercent: "#initial_investment"
    },
},
messages: {
    initial_investment: "Please enter a value between 100 and 9,99,99,99,999.",
    investment_period: "Please enter no more than 2 characters.",
    debt_fund: "Please enter a value between 0.10 - 10.00%.",
    equity_fund: "Please enter a value between 0.10 - 15.00%.",
    fixed_percent: "Please enter a value between 0.10 - 5.00%.",
    fixed_amount: "Please enter a value between Rs.100 - 2.00% of Initial Investment."
}
});
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
    @if($form_data['monthly_transfer_mode']=='FT')
    <script>
        $('#monthly_transfer_inactive_field').hide();
        $('#monthly_transfer_active_field').show();
    </script>
    @else
    <script>
        $('#monthly_transfer_active_field').hide();
        $('#monthly_transfer_inactive_field').show();
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
                    <h3 class="smalllineHeading">Future Value of STP</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.futureValueOfSTPOutput')}}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Initial Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('initial_investment') ? ' is-invalid' : '' }}" name="initial_investment" id="initial_investment" value="{{$form_data['initial_investment']}}" min="100" max="9999999999" required maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('initial_investment'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('initial_investment') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{$form_data['investment_period']}}" min="1" max="99" required  >
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
                                    <input type="text" class="form-control number {{ $errors->has('debt_fund') ? ' is-invalid' : '' }}" name="debt_fund" value="{{$form_data['debt_fund']}}" required >
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
                                    <input type="text" class="form-control number {{ $errors->has('equity_fund') ? ' is-invalid' : '' }}" name="equity_fund" value="{{$form_data['equity_fund']}}" required  >
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
                                        <label class="checkLinecontainer mb-0 mt-1" for="monthly_transfer_mode1">Capital Appreciation
                                            <input class="form-check-input" type="radio" id="monthly_transfer_mode1" name="monthly_transfer_mode" value="CA"  @if($form_data['monthly_transfer_mode']=='CA')  @else checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer mb-0 mt-1" for="monthly_transfer_mode2">Fixed Transfer
                                            <input class="form-check-input" type="radio" id="monthly_transfer_mode2" name="monthly_transfer_mode" value="FT" @if($form_data['monthly_transfer_mode']=='FT')  checked  @endif >
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
                                    <input type="text" disabled class="form-control" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                </div>
                                <div class="col-sm-7" id="monthly_transfer_active_field">
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <div class="form-check form-check-inline">
                                                <label class="checkLinecontainer mb-0 mt-2" for="fixed_transfer_mode3">Fixed %
                                                    <input class="form-check-input" type="radio" id="fixed_transfer_mode3" name="fixed_transfer_mode" value="FP" @if($form_data['fixed_transfer_mode']=='FP')  @else checked  @endif>
                                                    <span class="checkmark"></span>
                                                </label> 
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control {{ $errors->has('fixed_percent') ? ' is-invalid' : '' }}" name="fixed_percent"   value="{{ $form_data['fixed_percent'] ?? ''}}" @if(old('fixed_transfer_mode')=='FP') required  @endif >
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
                                            <div class="form-check form-check-inline">
                                                <label class="checkLinecontainer mb-0 mt-2" for="fixed_transfer_mode5">Fixed Amount
                                                    <input class="form-check-input" type="radio" id="fixed_transfer_mode5" name="fixed_transfer_mode" value="FA" @if($form_data['fixed_transfer_mode']=='FA') checked  @endif >
                                                    <span class="checkmark"></span>
                                                </label> 
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control {{ $errors->has('fixed_amount') ? ' is-invalid' : '' }}" name="fixed_amount"  value="{{ $form_data['fixed_amount'] ?? ''}}" min="100" @if(old('fixed_transfer_mode')=='FA') required  @endif >
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
