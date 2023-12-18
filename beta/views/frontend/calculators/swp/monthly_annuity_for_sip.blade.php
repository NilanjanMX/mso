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
                sip_amount: {
                    required: true,
                    digits: true,
                    maxlength: 8,
                    range: [100, 99999999],
                },
                sip_period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                annuity_period: {
                    required: true,
                    digits: true,
                    maxlength: 2,
                    range: [1, 99],
                },
                accumulation_phase_interest_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                accumulation_phase_interest_rate_2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                distribution_phase_interest_rate_1: {
                    required: true,
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
                },
                distribution_phase_interest_rate_2: {
                    number: true,
                    twodecimalplaces: true,
                    range: [0.10, 15.00],
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
                    <h3 class="mb-3 text-center">Monthly SWP For SIP</h3>
                    <div class="card sip-calculator">
                        <div class="card-header text-center">
                            This calculator gives you how much amount you can withdraw every month (SWP) after a given period of SIP investment. (You can decide how much balance you need at the end of the annuity period. It could also be nil. If your balance requirement is high, figures could become negative.)
                        </div>
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.monthlyAnnuityForSIPOUTPUT')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">SIP Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('sip_amount') ? ' is-invalid' : '' }}" name="sip_amount" value="{{old('sip_amount')}}" maxlength="10" >
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
                                <label class="col-sm-5 col-form-label">SIP Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('sip_period') ? ' is-invalid' : '' }}" name="sip_period" value="{{old('sip_period')}}"  >
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('sip_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Annuity Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('annuity_period') ? ' is-invalid' : '' }}" name="annuity_period" value="{{old('annuity_period')}}"  >
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('annuity_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('annuity_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
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
                            <div class="row">
                                <label class="col-sm-5 col-form-label">Balance Required at the end of Annuity</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-12 form-group">
                                            <div class="form-group">
                                                <input type="text" class="form-control {{ $errors->has('balance_required') ? ' is-invalid' : '' }}" name="balance_required" value="{{old('balance_required',0)}}" required>
                                                <div class="cal-icon" style="right: 1px;">
                                                    ₹
                                                </div>
                                                @if ($errors->has('balance_required'))
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $errors->first('balance_required') }}</strong>
                                                    </div>
                                                @endif
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
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
