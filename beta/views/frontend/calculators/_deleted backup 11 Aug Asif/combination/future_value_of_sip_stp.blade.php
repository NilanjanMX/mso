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
                sip_amount: {
                    required: true,
                    digits: true,
                    maxlength: 8,
                    range: [100, 99999999],
                },
                sip_interest_rate: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                stp_amount: {
                    required: true,
                    digits: true,
                    maxlength: 10,
                    range: [100, 9999999999],
                },
                debt_interest: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 10.00]
                },
                equity_interest: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00]
                },
                period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
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
                    <h3 class="mb-3 text-center">Future Value Of SIP + STP</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.futureValueOfSipStpOutput')}}" method="post">
                            @csrf

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">SIP Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('sip_amount') ? ' is-invalid' : '' }}" name="sip_amount" value="{{old('sip_amount')}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('sip_amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('sip_interest_rate') ? ' is-invalid' : '' }}" name="sip_interest_rate" value="{{old('sip_interest_rate')}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('sip_interest_rate'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_interest_rate') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">STP Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('stp_amount') ? ' is-invalid' : '' }}" name="stp_amount" value="{{old('stp_amount')}}" maxlength="10"  required>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                    @if ($errors->has('stp_amount'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('stp_amount') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return in Debt Fund</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('debt_interest') ? ' is-invalid' : '' }}" name="debt_interest" value="{{old('debt_interest')}}"   >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('debt_interest'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('debt_interest') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate Of Return in Equity Fund</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('equity_interest') ? ' is-invalid' : '' }}" name="equity_interest" value="{{old('equity_interest')}}" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                    @if ($errors->has('equity_interest'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('equity_interest') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Monthly Transfer Mode</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="monthly_transfer_mode" id="inlineRadio1" value="CA" checked >
                                        <label class="form-check-label" for="inlineRadio1">Capital Appreciation</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{old('investment_period')}}"  required>
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
