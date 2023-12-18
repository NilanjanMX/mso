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
                    loan_amount: {
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
                    rate_of_interest: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    },
                    expected_return_sip: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 18.00]
                    }
                },
                messages:{
                    loan_amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters.",
                    rate_of_interest: "Please enter a value between 0.10 - 18.00%.",
                    expected_return_sip: "Please enter a value between 0.10 - 18.00%."
                }
            });
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
                    <h3 class="mb-3 text-center">Recover Your EMI's through SIPs</h3>
                    <div class="card sip-calculator">
                    <div class="card-header text-center">
                            This calculator helps you to find the amount of SIP required to recover the payment made towards EMI.
                        </div>
                    <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.RecoverYourEMIsThroughSIPs_output')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Loan Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('loan_amount') ? ' is-invalid' : '' }}" name="loan_amount" value="{{old('loan_amount')}}" maxlength="10" >
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('loan_amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('loan_amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Rate of Interest</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('rate_of_interest') ? ' is-invalid' : '' }}" name="rate_of_interest" value="{{old('rate_of_interest')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('rate_of_interest'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('rate_of_interest') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Tenure</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('period') ? ' is-invalid' : '' }}" name="period" value="{{old('period')}}"  >
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
                            
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Expected Return on SIP Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('expected_return_sip') ? ' is-invalid' : '' }}" name="expected_return_sip" value="{{old('expected_return_sip')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('expected_return_sip'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('expected_return_sip') }}</strong>
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
