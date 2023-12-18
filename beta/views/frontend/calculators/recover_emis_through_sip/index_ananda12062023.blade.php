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
                $('textarea[name="note"]').prop("readonly", false);
            }else {
                $('textarea[name="note"]').prop("readonly", true);
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
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                    },
                    interest: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    },
                    expected_interest: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    }
                },
                messages: {
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    period: "Please enter no more than 2 characters",
                    interest: "Please enter a value between 0.1 and 18",
                    expected_interest: "Please enter a value between 0.1 and 18"
                }
            });
        });

        var global_type = 1;
        
        function TurnOnSip(dat)
        {
            var sip = document.getElementById("sip");
            var emi = document.getElementById("emi");
            
            if(dat == "sipInv"){
                sip.style.display = 'block';
                monthly_emi.innerHTML = 'Outstanding Loan Amount';
                global_type = 1;
            }else{
                sip.style.display = 'none';
                monthly_emi.innerHTML = 'Monthly EMI';
                global_type = 2;
            }
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
{{-- <div class="banner bannerForAll container" style="padding-bottom: 0;">
    <div id="main-banner" class="owl-carousel owl-theme">
        <div class="item">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="py-4">Premium Calculators</h2>
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
</div> --}}
    <div class="banner">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">PREMIUM CALCULATORS</h2>
            </div>
        </div>
    </div> 

    <section class="main-sec styleNew">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Recover Your EMI's through SIPss</h3>
                    
                        @include('frontend.calculators.common_bio')
                        <br>
                        <form class="js-validate-form" action="{{route('frontend.recover_emis_through_sip_output')}}" method="post">
                            <div class="card sip-calculator singleLineHolder">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label"></label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer" for="sipInv">Enter Loan Details
                                                <input class="form-check-input fixed_deposit_chk" id="sipInv" type="radio" name="enter_loan_details" onchange="TurnOnSip('sipInv')"  checked value="1" @if(old('enter_loan_details')=='1') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer" for="emiInv">Enter EMI Details
                                                <input class="form-check-input fixed_deposit_chk" id="emiInv" type="radio" name="enter_loan_details" value="2"  onchange="TurnOnSip('emiInv')" @if(old('enter_loan_details')=='2') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" id="monthly_emi">Outstanding Loan Amount</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" class="form-control" value="{{old('amount')}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div  id="sip">
                                    <div class="form-group row">
                                        <label class="col-sm-5 col-form-label">Rate of Interest</label>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="interest" class="form-control" value="{{old('interest')}}">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Tenure <span><i class="fa fa-eye"></i><span>Enter the remaining tenure</span></span></label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="period" class="form-control" value="{{old('period')}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yr
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed return on SIP Investment</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="expected_interest" class="form-control" value="{{old('expected_interest')}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
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
    
                                {{-- <div class="form-group row">
                                    <div class="col-sm-6 d-flex">
                                            <label class="sqarecontainer">View Detailed Graph
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1"> 
                                                <span class="checkmark"></span>
                                            </label>
                                    </div>
                                </div> --}}
    
                                
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
