@extends('layouts.frontend')

@section('js_after')
    <script>
        $('input[name="clientname"]').prop("readonly", true);
        $("#is_client").click( function(){
            if( $(this).is(':checked') ){
                $('input[name="clientname"]').prop("readonly", false);
            }else {
                $('input[name="clientname"]').prop("readonly", true);
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
                interest3: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                period1: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period2: {
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period3: {
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period4: {
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                period5: {
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                }
            },
            messages: {
                amount: "Please enter a value between 100 and 9,99,99,99,999.",
                interest1: "Please enter a value between 0.10 - 15.00%.",
                interest2: "Please enter a value between 0.10 - 15.00%.",
                interest3: "Please enter a value between 0.10 - 15.00%.",
                period1: "Please enter no more than 2 characters.",
                period2: "Please enter no more than 2 characters.",
                period3: "Please enter no more than 2 characters.",
                period4: "Please enter no more than 2 characters.",
                period5: "Please enter no more than 2 characters.",
            }
        });
    </script>
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
                    <h3 class="mb-3 text-center">SIP Goal Planning Ready Reckoner</h3>
                    <div class="card sip-calculator sip-calculator__modify">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                            <form class="js-validate-form" action="{{route('frontend.sipGoalPlanningReadyRecoknerOutput')}}" method="post">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Target Amount</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{old('amount')}}"  required>
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
                                    <label class="col-sm-4 col-form-label">Assumed Rate Of Return:</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('interest1') ? ' is-invalid' : '' }}" name="interest1" value="{{old('interest1')}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 number {{ $errors->has('interest2') ? ' is-invalid' : '' }}" name="interest2" value="{{old('interest2')}}">
                                            <input type="text" class="form-control number {{ $errors->has('interest3') ? ' is-invalid' : '' }}" name="interest3" value="{{old('interest3')}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                        @if ($errors->has('interest1'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest1') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('interest2'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest2') }}</strong>
                                            </div>
                                        @endif
                                        @if ($errors->has('interest3'))
                                            <div class="invalid-feedback" style="display: inline-block" role="alert">
                                                <strong>{{ $errors->first('interest3') }}</strong>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Investment Period:</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period1') ? ' is-invalid' : '' }}" name="period1" value="{{old('period1')}}"  required>
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period2') ? ' is-invalid' : '' }}" name="period2" value="{{old('period2')}}">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit {{ $errors->has('period3') ? ' is-invalid' : '' }}" name="period3" value="{{old('period3')}}">
                                            <input type="text" class="form-control pr-5 mr-1 maxtwodigit {{ $errors->has('period4') ? ' is-invalid' : '' }}" name="period4" value="{{old('period4')}}">
                                            <input type="text" class="form-control maxtwodigit {{ $errors->has('period5') ? ' is-invalid' : '' }}" name="period5" value="{{old('period5')}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yr
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
                                    <div class="col-sm-12">
                                        <h6 class="text-muted">INCLUDE THE FOLLOWING IN MY REPORT</h6>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">
                                        <input id="is_client" type="checkbox" name="client" value="1"> Add Client Name
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
            <div class="btm-shape-prt">
                <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
            </div>
        </div></section>

@endsection
