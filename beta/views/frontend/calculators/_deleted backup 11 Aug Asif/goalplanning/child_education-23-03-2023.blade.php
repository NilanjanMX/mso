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

      /*var number  =  $("input[name=investment_amount]")
        number .on('blur', function() {
            if ($(this).val() == '')
            {
                $(this).val(0);
                $("input[name=return_rate]").val(0);
                return false;
            }
        });*/

        jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
            return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
        }, "You must include two decimal places");

        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[A-Za-z ]+$/i.test(value);
        }, "Letters only please");

        jQuery.validator.addMethod('greaterThan', function(value, element, param) {
            return ( Number(value) > Number(jQuery(param).val()) );
        }, 'Must be greater than start' );

        jQuery.validator.addMethod('lesserThan', function(value, element, param) {
            return ( Number(value) < Number(jQuery(param).val()) );
        }, 'Must be less than end' );

        jQuery.validator.addMethod('disabledIfZero', function(value, element, param) {
            if(Number(value) == 0){
                jQuery(param).prop( "disabled", true );
            } else {
                jQuery(param).prop( "disabled", false );
            }
            return true;
        });

        var validator = $(".js-validate-form").validate({
            errorElement: "em",
            errorContainer: $("#warning, #summary"),
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            rules: {
                child_name: {
                    required: true,
                    lettersonly: true,
                    maxlength: 30,
                },
                current_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [0, 99]
                },
                fund_requirement_purpose: {
                    required: true
                },
                fund_required_age: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    greaterThan: "#current_age",
                    range: [1, 99]
                },
                fund_required_amount: {
                    required: true,
                    number: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                investment_amount: {
                    maxlength: 10,
                    range: [0, 9999999999],
                    lesserThan: "#fund_required_amount",
                    disabledIfZero: "#return_rate",
                },
                inflation_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0, 12.00]
                },
                return_rate: {
                    /*required: function(element) {
                        return (Number($("input[name='investment_amount']").val()) > 0) ? true : false;
                    },*/
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                return_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                return_rate_2: {
                    //required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                }
            },
            messages: {
                fund_required_age: { greaterThan: "Must be greater than Child Age!" },
                investment_amount: { lesserThan: "Must be less than Fund Required!"}
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
                    <h3 class="mb-3 text-center">Child Education / Marriage Planning</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.childEducationOutput')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Child Name</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('child_name') ? ' is-invalid' : '' }}" name="child_name" value="{{old('child_name')}}" maxlength="30" required>

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
                                    <input type="text" class="form-control {{ $errors->has('current_age') ? ' is-invalid' : '' }}" id="current_age" name="current_age" value="{{old('current_age')}}"   required>
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
                                        <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio1" value="Education">
                                        <label class="form-check-label" for="inlineRadio1">Education</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio2" value="Marriage" checked="">
                                        <label class="form-check-label" for="inlineRadio2">Marriage</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="fund_requirement_purpose" id="inlineRadio3" value="Investment" checked="">
                                        <label class="form-check-label" for="inlineRadio3">Investment</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Fund Required Age</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('fund_required_age') ? ' is-invalid' : '' }}" name="fund_required_age" value="{{old('fund_required_age')}}"   required>
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
                                    <input type="number" class="form-control {{ $errors->has('fund_required_amount') ? ' is-invalid' : '' }}" name="fund_required_amount" id="fund_required_amount" value="{{old('fund_required_amount')}}" maxlength="10"  required>
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
                                    <input type="text" class="form-control {{ $errors->has('investment_amount') ? ' is-invalid' : '' }}" name="investment_amount" value="{{old('investment_amount')}}" maxlength="10" >
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
                                    <input type="text" class="form-control number {{ $errors->has('inflation_rate') ? ' is-invalid' : '' }}" name="inflation_rate" value="{{old('inflation_rate')}}"   required>
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
                                    <input type="text" class="form-control number {{ $errors->has('return_rate') ? ' is-invalid' : '' }}" name="return_rate" id="return_rate" value="{{old('return_rate')}}" >
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
                                    <h6 class="text-muted">Assumed Rate of Return (Fresh Investment):</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 1</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('return_rate_1') ? ' is-invalid' : '' }}" name="return_rate_1" value="{{old('return_rate_1')}}"  required>
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
                                    <input type="text" class="form-control number {{ $errors->has('return_rate_2') ? ' is-invalid' : '' }}" name="return_rate_2" value="{{old('return_rate_2')}}" >
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

                            @include('frontend.calculators.suggested.form')
                            <div class="form-group row">

                                <div class="offset-5 col-sm-7">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                        </div>
                                        <div class="col-md-8">
                                            <button class="btn btn-primary btn-round btn-block">Calculate</button>
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
