@extends('layouts.frontend')

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

    @if(old('monthly_transfer_mode')=='FT')
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
                    <h3 class="mb-3 text-center">STP Required For Target Future Value</h3>
                    <div class="card sip-calculator">
                        @include('frontend.calculators.common_bio')
                        <div class="card-body">
                        <form class="js-validate-form" action="{{route('frontend.stpRequiredForTargetFutureValueOutput')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Target Future Value</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('target_amount') ? ' is-invalid' : '' }}" name="target_amount" value="{{old('target_amount')}}" >
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
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" value="{{old('investment_period')}}" >
                                    <div class="cal-icon">
                                        Yr
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
                                    <h6 class="text-muted">Assumed Rate Of Return:</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Debt Fund</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('debt_fund') ? ' is-invalid' : '' }}" name="debt_fund" value="{{old('debt_fund')}}" required >
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
                                    <input type="text" class="form-control number {{ $errors->has('equity_fund') ? ' is-invalid' : '' }}" name="equity_fund" value="{{old('equity_fund')}}" required  >
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
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="monthly_transfer_mode1" name="monthly_transfer_mode" value="CA"  @if(old('monthly_transfer_mode')=='CA')  @else checked  @endif>
                                        <label class="form-check-label" for="monthly_transfer_mode1">
                                            Capital Appreciation
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" id="monthly_transfer_mode2" name="monthly_transfer_mode" value="FT" @if(old('monthly_transfer_mode')=='FT')  checked  @endif >
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
                                <label class="col-sm-5 col-form-label">Monthly Transfer</label>
                                <div class="col-sm-7" id="monthly_transfer_inactive_field">
                                    <input type="text" disabled class="form-control" >
                                    <div class="cal-icon">
                                        %
                                    </div>
                                </div>
                                <div class="col-sm-7" id="monthly_transfer_active_field">
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
                                            <input type="text" class="form-control {{ $errors->has('fixed_percent') ? ' is-invalid' : '' }}" name="fixed_percent" autocomplete="off" min="0.1" max="2.0" value="" @if(old('fixed_transfer_mode')=='FP') required @else readonly @endif>
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
                                                <input class="form-check-input" disabled type="radio" id="fixed_transfer_mode5" name="fixed_transfer_mode" value="FA" @if(old('fixed_transfer_mode')=='FA') checked  @endif >
                                                <label class="form-check-label" for="fixed_transfer_mode5">
                                                    Fixed Amount
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control {{ $errors->has('fixed_amount') ? ' is-invalid' : '' }}" name="fixed_amount" autocomplete="off" value="" min="100" @if(old('fixed_transfer_mode')=='FA') required @else readonly @endif >
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
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> Add Client Name
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"  autocomplete="off" value="{{old('clientname')}}" maxlength="30">
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
