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
        $("#is_note").click( function(){
            if( $(this).is(':checked') ){
                $('textarea[name="note"]').css('display','block');
                $('.cmtFieldCount').css('display','block');
            }else {
                $('textarea[name="note"]').css('display','none');
                $('.cmtFieldCount').css('display','none');
            }
        });
        
        function changeNote(){
            var note = document.getElementById('note').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }

        $(function() {
            jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            jQuery.validator.addMethod('lessThan', function (value, element, param) {
                return this.optional(element) || parseInt(value) <= parseInt($(param).val());
            }, 'Invalid value');

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
                        range: [100, 9999999999]
                    },
                    investment_period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99]
                    },
                    payment_period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        range: [1, 99],
                        lessThan: '#investment_period'
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
                },
                messages:{
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    investment_period: "Please enter no more than 2 characters.",
                    payment_period:{
                        required:"Please enter no more than 2 characters",
                        lessThan:"Payment period must be less than or equal to investment period."
                    },
                    interest1: "Please enter a value between 0.10 - 15.00%.",
                    interest2: "Please enter a value between 0.10 - 15.00%."
                }
            });



        });


        function resetForm(){
            console.log(document.getElementById("amount").value)
            document.getElementById("amount").value = "";
            document.getElementById("investment_period").value = "";
            document.getElementById("payment_period").value = "";
            document.getElementById("interest1").value = "";
            document.getElementById("interest2").value = "";
        }
        
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    <style>
        
    </style>
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
            $('textarea[name="note"]').css('display','block');
            $('.cmtFieldCount').css('display','block');
        </script>
    @else
        <script>
            $('textarea[name="note"]').css('display','none');
            $('.cmtFieldCount').css('display','none');
        </script>
    @endif
@endsection

@section('content')

<!-- <div class="banner bannerForAll container" style="padding-bottom: 0;">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Premium Calculators 1</h2>
                    <p>Lörem ipsum rutavdrag bespepp. Danyre gereras, sar rugbyförälder, ären. Multitirade pabel men spökgarn medan nåfus kreddig. Decill eus. Osm kromera, diadunade intrarade. 
                    </p>
                    <a href="" class="createtempbtn" style=" margin-right: 22px; "><button class="btn banner-btn mt-3">Sample Reports</button></a>
                    <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">How to Use</button></a>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-5"><img class="img-fluid" src="{{asset('')}}img/pcalculatorbanner.png" alt="" /></div>
            </div>
        </div>
    </div>
</div> -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">CALCULATORS CUM CLIENT PROPOSALS</h2>
                </div>
            </div>
        </div>
    </div>
    
    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">{{$details->name}}</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.annualLumpsumRequiredForTargetFutureValue_output')}}" method="post">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                        
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Target Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control {{ $errors->has('amount') ? ' is-invalid' : '' }}" name="amount" id="amount" value="{{old('amount')}}" maxlength="10" >
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
                                <label class="col-sm-5 col-form-label">Investment Period</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('investment_period') ? ' is-invalid' : '' }}" name="investment_period" id="investment_period" value="{{old('investment_period')}}"  >
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
                                <label class="col-sm-5 col-form-label">Payment Period &nbsp; 
                                    <span><i class="fa fa-eye"></i></label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control maxtwodigit {{ $errors->has('payment_period') ? ' is-invalid' : '' }}" name="payment_period" id="payment_period" value="{{old('payment_period')}}"  >
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                    @if ($errors->has('payment_period'))
                                        <div class="invalid-feedback" role="alert">
                                            <strong>{{ $errors->first('payment_period') }}</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Scenario 1</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control number {{ $errors->has('interest1') ? ' is-invalid' : '' }}" name="interest1" id="interest1" value="{{old('interest1')}}" >
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
                                    <input type="text" class="form-control number {{ $errors->has('interest2') ? ' is-invalid' : '' }}" name="interest2" id="interest2" value="{{old('interest2')}}" >
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

                        <!-- <div class="text-center pt-3">
                            <a href="" class="createtempbtn"><button class="btn banner-btn mt-3">Add New Goal Calculator</button></a>
                        </div> -->

                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
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

                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                        <label class="sqarecontainer">View Detailed Graph
                                            <input id="is_graph" type="checkbox" name="is_graph" value="1"> 
                                            <span class="checkmark"></span>
                                        </label>
                                </div>
                            </div>

                            
                            <div class="form-group row"  style="align-items: flex-start;">
                                <!-- <label class="col-sm-5 col-form-label">
                                    
                                </label> -->
                                <div class="col-sm-5">
                                    <label class="sqarecontainer">Add Comments (If any)
                                        <input id="is_note" type="checkbox" name="is_note" value="1"> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{old('note')}}</textarea>
                                    <div class="text-right charcount cmtFieldCount"><span id="note_total_count">0</span>/500 characters left.</div>
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
                                            
                                            <button type="button" onclick="resetForm();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                        
                                            <!-- <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button> -->
                                        
                                            <button class="btn banner-btn mx-3">Calculate</button>
                                       
                                    </div>

                                </div>
                            </div>
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
