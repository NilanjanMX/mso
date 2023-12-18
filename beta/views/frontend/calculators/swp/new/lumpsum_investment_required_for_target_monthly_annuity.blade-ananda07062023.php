@extends('layouts.frontend')

@section('js_after')
    <script>

    $(document).on("keyup",".alert_validation",function(){

            if($("input[name='current_age']").val()!="")
            {
                var current_age=$("input[name='current_age']").val();
            }else{
                var current_age=0;
            }

            if($("input[name='deferment_period']").val()!="")
            {
                var deferment_period=$("input[name='deferment_period']").val();
            }else{
                var deferment_period=0;
            }

            if($("input[name='period']").val()!="")
            {
                var swp_prd=$("input[name='period']").val();
            }else{
                var swp_prd=0;
            }
            


            if(current_age!="")
            {
                if(parseInt(current_age)+parseInt(deferment_period)+parseInt(swp_prd)>100)
                {
                    alert("Current Age + Deferment Period + SWP Period cannot exceed 100.");
                    $(this).val("");
                    $(this).css("border","1px solid red");
                    $(".dis_btn").attr('disabled',true);
                }else{
                    $(this).css("border","1px solid #e2e2e2");  
                    $(".dis_btn").attr('disabled',false); 
                }

            }
    });

    $(document).on("keyup",".eir_val",function(){

        if($("input[name='deferment']:checked").val()=="yes")
        {
            var interest1=$("input[name='distribution_phase_interest_rate_1']").val();
            var interest2=$("input[name='distribution_phase_interest_rate_2']").val();
            if($(this).val()==interest1 || $(this).val()==interest2)
            {
                $("#eir_val_msg").text("Inflation rate and distribution rate can't be same.");
                $(".dis_btn").attr('disabled',true);
            }else{
                $("#eir_val_msg").text('');
                $(".dis_btn").attr('disabled',false);
            }
        }else{
            var interest1=$("input[name='interest1']").val();
            var interest2=$("input[name='interest2']").val();
            if($(this).val()==interest1 || $(this).val()==interest2)
            {
                $("#eir_val_msg").text("Inflation rate and distribution rate can't be same.");
                $(".dis_btn").attr('disabled',true);
            }else{
                $("#eir_val_msg").text('');
                $(".dis_btn").attr('disabled',false);
            }
        }
        
        
    });

    $(document).on("keyup","#for_period_upto",function(){
        var mval=$(this).val();
        if(mval=="")
        {
            $("#from_the_year").val('0');
        }else{
            $("#from_the_year").val(parseInt(mval)+1);
        }
        
     });

        $(document).on("click",".deferment_radio",function(){
            var rval=$(this).val();
            if(rval=='yes')
            {
                $(".deferment_div1").css("display","none");
                $(".deferment_div2").css("display","block");
                $('input[name="deferment_period"]').prop("readonly", false);
            }else{
                $(".deferment_div1").css("display","block");
                $(".deferment_div2").css("display","none");
                $('input[name="deferment_period"]').prop("readonly", true);
            }
        });

        $(document).on("click",".include_inflation",function(){
            var ival=$(this).val();
            if(ival=='yes')
            {
                $('input[name="expected_inflation_rate"]').prop("readonly", false);
            }else{
                $('input[name="expected_inflation_rate"]').prop("readonly", true);
            }
        });

        $(document).on("click",".include_taxation",function(){
            var aival=$(this).val();
            if(aival=='yes')
            {
                $('input[name="assumed_inflation_rate_for_indexation"]').prop("readonly", false);

                $('input[name="applicable_short_term_tax_rate"]').prop("readonly", false);
                $('input[name="for_period_upto"]').prop("readonly", false);
                $('input[name="applicable_long_term_tax_rate"]').prop("readonly", false);

                $('input[name="applicable_short_term_tax_rate"]').val("30");
                $('input[name="for_period_upto"]').val("3");
                $('input[name="applicable_long_term_tax_rate"]').val("20");
                $('input[name="from_the_year"]').val("4");
            }else{
                $('input[name="assumed_inflation_rate_for_indexation"]').prop("readonly", true);

                $('input[name="applicable_short_term_tax_rate"]').prop("readonly", true);
                $('input[name="for_period_upto"]').prop("readonly", true);
                $('input[name="applicable_long_term_tax_rate"]').prop("readonly", true);

                $('input[name="applicable_short_term_tax_rate"]').val("");
                $('input[name="for_period_upto"]').val("");
                $('input[name="applicable_long_term_tax_rate"]').val("");
                $('input[name="from_the_year"]').val("");
            }
        });

       

        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
            }
        });

        $("input[name=balance_required]").blur(function() {
            if(!this.value) {
                $(this).val(0);
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
                current_age: {
                        digits: true,
                        maxlength: 10,
                        range: [1, 99]
                },
                deferment_period: {
                        required: function(element){
                            return $("input[name='deferment']:checked").val()=="yes";
                        },
                        digits: true,
                        maxlength: 10,
                        range: [1, 99]
                },
                initial_investment: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                assumed_inflation_rate_for_indexation: {
                        required: function(element){
                            return $("input[name='include_taxation']:checked").val()=="yes";
                        },
                        number: true,
                        twodecimalplaces: true,
                        range: [0.0, 18.00]
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
                    range: [0.10, 15.00]
                },
                distribution_phase_interest_rate_2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                balance_required: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [0, 9999999999],
                }
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
                    <h3 class="mb-3 text-center">Lumpsum Investment Required For Target Monthly SWP</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.lumpsumInvestmentRequiredForTargetMonthlyAnnuityOutput')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Age (Optional)</label>
                                <div class="col-sm-7">
                                    <input type="text" class="alert_validation form-control maxtwodigit {{ $errors->has('current_age') ? ' is-invalid' : '' }}" name="current_age" value="{{old('current_age')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('current_age'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('current_age') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Monthly SWP Required</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('initial_investment') ? ' is-invalid' : '' }}" name="initial_investment" value="{{old('initial_investment')}}" maxlength="10" >
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
                                <!-- <label class="col-sm-5 col-form-label">Deferment Period</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deferment" id="inlineRadiod1" value="yes" @if(old('deferment')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadiod1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deferment" id="inlineRadiod2" value="no" @if(old('deferment')=='summary')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadiod2">No</label>
                                    </div>
                                </div>
                                xxxx -->

                            </div>

                            <div class="fixed_deposit_box">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Deferment Period </label>
                                            
                                             <div class="form-check form-check-inline">
                                                <input class="form-check-input deferment_radio" type="radio" name="deferment" id="inlineRadiod1" value="yes" @if(old('deferment')=='yes') checked  @endif>
                                                <label class="form-check-label" for="inlineRadiod1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input deferment_radio" type="radio" name="deferment" id="inlineRadiod2" value="no" @if(old('deferment')=='no')  @else checked  @endif >
                                                <label class="form-check-label" for="inlineRadiod2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <input type="text" readonly id="deferment_period" class="alert_validation form-control pr-2 mr-1 number maxtwodigit {{ $errors->has('deferment_period') ? ' is-invalid' : '' }}" name="deferment_period" value="{{old('deferment_period',isset($deferment_period)?$deferment_period:'')}}" >
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                        
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">SWP Period <a href="#" data-toggle="tooltip" class="red-tooltip" data-placement="top" title="Current Age + Deferment Period + SWP Period cannot exceed 100."><i class="fa fa-info-circle" style="font-size:14px; color:#203864;"></i></a></label>
                                <div class="col-sm-7">
                                    <input type="text" class="alert_validation form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{old('period')}}"  >
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="deferment_div1">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">Assumed Rate Of Return:</h6>
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
                            </div>

                            <div class="deferment_div2" style="display:none;">
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <h6 class="text-muted">Assumed Rate Of Return:</h6>
                                    </div>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <h6>Accumulation Phase
                                                </h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <h6>Distribution Phase</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <label class="col-sm-5 col-form-label">Scenario 1</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control number {{ $errors->has('accumulation_phase_interest_rate_1') ? ' is-invalid' : '' }}" name="accumulation_phase_interest_rate_1" required>
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
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control number {{ $errors->has('distribution_phase_interest_rate_1') ? ' is-invalid' : '' }}" name="distribution_phase_interest_rate_1" required>
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
                                <div class="row">
                                    <label class="col-sm-5 col-form-label">Scenario 2 (Optional)</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control number {{ $errors->has('accumulation_phase_interest_rate_2') ? ' is-invalid' : '' }}" name="accumulation_phase_interest_rate_2">
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
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <input type="text" class="form-control number {{ $errors->has('distribution_phase_interest_rate_2') ? ' is-invalid' : '' }}" name="distribution_phase_interest_rate_2">
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
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Balance Required at the end of SWP</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('balance_required') ? ' is-invalid' : '' }}" name="balance_required" value="{{old('balance_required',0)}}" maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('balance_required'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('balance_required') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                             <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Include Inflation</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input include_inflation" type="radio" name="include_inflation" id="inlineRadioii1" value="yes" @if(old('include_inflation')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadioii1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input include_inflation" type="radio" name="include_inflation" id="inlineRadioii2" value="no" @if(old('include_inflation')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadioii2">No</label>
                                    </div>
                                </div>
                            </div>



                             <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Expected Inflation Rate</label>
                                <div class="col-sm-7">
                                    <input readonly type="text" class="eir_val form-control {{ $errors->has('expected_inflation_rate') ? ' is-invalid' : '' }}" name="expected_inflation_rate" value="{{old('expected_inflation_rate')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    <strong id="eir_val_msg" style="color:red;"></strong>
                                    @if ($errors->has('expected_inflation_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_inflation_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Include Taxation</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input include_taxation" type="radio" name="include_taxation" id="inlineRadioit1" value="yes" @if(old('include_taxation')=='yes') checked  @endif>
                                        <label class="form-check-label" for="inlineRadioit1">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input include_taxation" type="radio" name="include_taxation" id="inlineRadioit2" value="no" @if(old('include_taxation')=='no')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadioit2">No</label>
                                    </div>
                                </div>
                            </div>

                             <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Inflation Rate for Indexation</label>
                                <div class="col-sm-7">
                                    <input readonly type="text" class="form-control {{ $errors->has('assumed_inflation_rate_for_indexation') ? ' is-invalid' : '' }}" name="assumed_inflation_rate_for_indexation" value="{{old('assumed_inflation_rate_for_indexation')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('assumed_inflation_rate_for_indexation'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('assumed_inflation_rate_for_indexation') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="fixed_deposit_box">
                                <div class="row">
                                    @php
                                    $applicable_short_term_tax_rate='';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Applicable Short Term Tax Rate</label>
                                            <div class="col-sm-5">
                                                <input readonly type="text" class="form-control pr-2 mr-1 number {{ $errors->has('applicable_short_term_tax_rate') ? ' is-invalid' : '' }}" name="applicable_short_term_tax_rate" value="{{old('applicable_short_term_tax_rate',isset($applicable_short_term_tax_rate)?$applicable_short_term_tax_rate:'')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                    $for_period_upto='';
                                    @endphp
                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">For period upto</label>
                                            <div class="col-sm-5">
                                                <input readonly type="text" id="for_period_upto" class="form-control pr-2 mr-1 number maxtwodigit {{ $errors->has('for_period_upto') ? ' is-invalid' : '' }}" name="for_period_upto" value="{{old('for_period_upto',isset($for_period_upto)?$for_period_upto:'')}}" >
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                        
                                </div>
                            </div>
                            <div class="fixed_deposit_box">
                                <div class="row">
                                    @php
                                    $applicable_long_term_tax_rate='';
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">Applicable Long Term Tax Rate</label>
                                            <div class="col-sm-5">
                                                <input readonly type="text" class="form-control pr-2 mr-1 number {{ $errors->has('applicable_long_term_tax_rate') ? ' is-invalid' : '' }}" name="applicable_long_term_tax_rate" value="{{old('applicable_long_term_tax_rate',isset($applicable_long_term_tax_rate)?$applicable_long_term_tax_rate:'')}}" >
                                                <div class="cal-icon">
                                                    %
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                    $from_the_year='';
                                    @endphp
                                    
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label class="col-sm-7 col-form-label">From the year</label>
                                            <div class="col-sm-5">
                                                <input type="text" readonly id="from_the_year" class="form-control pr-2 mr-1 number maxtwodigit {{ $errors->has('from_the_year') ? ' is-invalid' : '' }}" name="from_the_year" value="{{old('from_the_year',isset($from_the_year)?$from_the_year:'')}}" >
                                                <div class="cal-icon">
                                                    Yr
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                        
                                </div>
                            </div>

                           

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> Add Client Name
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname')}}" maxlength="30">
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
                                <label class="col-sm-5 col-form-label">Get Report</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(old('report')=='summary') checked  @endif>
                                        <label class="form-check-label" for="inlineRadio1">Summary Report</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(old('report')=='summary')  @else checked  @endif >
                                        <label class="form-check-label" for="inlineRadio2">Detailed Report</label>
                                    </div>
                                </div>
                            </div>
                            @include('frontend.calculators.suggested.form')
                            <div class="form-group row">

                                <div class="offset-5 col-sm-7">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                        </div>
                                        <div class="col-md-8">
                                            <button class="dis_btn btn btn-primary btn-round btn-block">Calculate</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
