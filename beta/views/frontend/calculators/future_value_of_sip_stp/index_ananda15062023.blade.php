@extends('layouts.frontend')

@section('js_after')
    <script>
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
        
        function changeNote(){
            var note = document.getElementById('note').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }

        
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    
    @if(old('client')!='')
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif
    @if(old('note')!='')
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif

@endsection

@section('content')
    <div class="banner">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Future Value Of SIP + STP</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.futureValueOfSipStp_output')}}" method="post">
                            <div class="card sip-calculator singleLineHolder calculatorFormShape">
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
    
                            </div>
                            <div class="card sip-calculator singleLineHolder">
                                <div class="form-group row">
                                    
                                    <div class="col-sm-6 d-flex">
                                        <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                            <input id="is_client" type="checkbox" name="client" value="1" @if(old('client')=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname')}}" maxlength="30">
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
                                
                                <div class="form-group row"  style="align-items: flex-start;">
                                    <!-- <label class="col-sm-5 col-form-label">
                                        
                                    </label> -->
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if(old('is_note')=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{old('note')}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
    
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(old('report')=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(old('report')=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
    
                                @include('frontend.calculators.suggested.form')
    
    
                                <div class="form-group row">
    
                                    <div class="offset-1 col-sm-10">
                                        <div class=" calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                
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
            <div class="btm-shape-prt">
                <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
            </div>
        </div></section>

@endsection
