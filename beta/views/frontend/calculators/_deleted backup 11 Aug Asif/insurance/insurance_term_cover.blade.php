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
                insurance_policy_annual_premium: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 99999999]
                },
                sum_assured: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [10000, 9999999999]
                },
                policy_term: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99]
                },
                equivalent_insurance_term_policy_premium: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [10, 99999999]
                },
                rate_of_return_investments: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                rate_of_return_insurance: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
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
                    <h3 class="mb-3 text-center">Insurance vs. Term Cover With Annual SIP</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.insuranceTermCoverOutput')}}" method="post">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Insurance Policy Annual Premium</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('insurance_policy_annual_premium') ? ' is-invalid' : '' }}" name="insurance_policy_annual_premium" value="{{old('insurance_policy_annual_premium')}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('insurance_policy_annual_premium'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('insurance_policy_annual_premium') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Sum Assured / Death Benefit</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('sum_assured') ? ' is-invalid' : '' }}" name="sum_assured" value="{{old('sum_assured')}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('sum_assured'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sum_assured') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Policy Term</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('policy_term') ? ' is-invalid' : '' }}" name="policy_term" value="{{old('policy_term')}}"  required>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('policy_term'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('policy_term') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Equivalent Insurance Policy Term Premium</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('equivalent_insurance_term_policy_premium') ? ' is-invalid' : '' }}" name="equivalent_insurance_term_policy_premium" value="{{old('equivalent_insurance_term_policy_premium')}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('equivalent_insurance_term_policy_premium'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('equivalent_insurance_term_policy_premium') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return On Investments</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('rate_of_return_investments') ? ' is-invalid' : '' }}" name="rate_of_return_investments" value="{{old('rate_of_return_investments')}}"   required>
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('rate_of_return_investments'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('rate_of_return_investments') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return On Insurance</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('rate_of_return_insurance') ? ' is-invalid' : '' }}" name="rate_of_return_insurance" value="{{old('rate_of_return_insurance')}}"   required>
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('rate_of_return_insurance'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('rate_of_return_insurance') }}</strong>
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
