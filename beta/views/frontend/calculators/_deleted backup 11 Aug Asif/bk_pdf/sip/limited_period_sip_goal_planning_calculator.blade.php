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
                <div class="col-md-8 offset-md-2">
                    <h3 class="mb-3">Limited Period SIP Goal Planning Calculator</h3>
                    <div class="card sip-calculator">
                        <div class="card-header text-center">
                            This calculator finds out how much SIP amount is required for a limited period to achieve target future value after deferment period. (For e.g., target value of Rs.50 lacs after 20 years; SIP period 7 years and then waiting for 13 years.)
                        </div>
                        <div class="card-body">
                        <form action="{{route('frontend.limitedPeriodSIPgoalPlanningCalculatorOutput')}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Target Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" value="{{old('amount')}}" maxlength="10" autocomplete="off">
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
                                <label class="col-sm-5 col-form-label">SIP Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('sip_period') ? ' is-invalid' : '' }}" name="sip_period" value="{{old('sip_period')}}" maxlength="2" autocomplete="off">
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('sip_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('sip_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Deferment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('deferment_period') ? ' is-invalid' : '' }}" name="deferment_period" value="{{old('deferment_period')}}" maxlength="2" autocomplete="off">
                                    <div class="cal-icon">
                                        Yr
                                    </div>
                                    @if ($errors->has('deferment_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('deferment_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h6 class="text-muted">Expected Rate Of Return:</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 1</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('interest1') ? ' is-invalid' : '' }}" name="interest1" value="{{old('interest1')}}" maxlength="2">
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
                                    <input type="text" class="form-control {{ $errors->has('interest2') ? ' is-invalid' : '' }}" name="interest2" value="{{old('interest2')}}" maxlength="2">
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

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> Add Client Name
                                </label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"  autocomplete="off" value="{{old('clientname')}}">
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
                            <div class="form-group row">
                                <div class="offset-5 col-sm-7">
                                    <button class="btn btn-primary btn-round btn-block">Calculate</button>
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
