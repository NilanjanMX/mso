@extends('layouts.frontend')

@php

    
    $data['enter_loan_details'] = old('enter_loan_details');
    if(!$data['enter_loan_details']){
        $data['enter_loan_details'] = $form_data['enter_loan_details'];
    }
    
    $data['amount'] = old('amount');
    if(!$data['amount']){
        $data['amount'] = $form_data['amount'];
    }
    
    $data['interest'] = old('interest');
    if(!$data['interest']){
        $data['interest'] = $form_data['interest'];
    }
    
    $data['period'] = old('period');
    if(!$data['period']){
        $data['period'] = $form_data['period'];
    }
    
    $data['expected_interest'] = old('expected_interest');
    if(!$data['expected_interest']){
        $data['expected_interest'] = $form_data['expected_interest'];
    }
    
    
    $data['report'] = old('report');
    if(!$data['report']){
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
    }
    
    $data['note'] = old('note');
    if(!$data['note']){
        $data['note'] = $form_data['note'];
    }
    
    $data['client'] = old('client');
    if(!$data['client']){
        $data['client'] = $form_data['client'];
    }
    
    $data['is_note'] = old('is_note');
    if(!$data['is_note']){
        $data['is_note'] = $form_data['is_note'];
    }
    
    $data['include_performance'] = old('include_performance');
    if(!$data['include_performance']){
        $data['include_performance'] = $suggested_performance;
    }
    
    $data['suggestedlist_type'] = old('suggestedlist_type');
    if(!$data['suggestedlist_type']){
        $data['suggestedlist_type'] = $suggestedlist_type;
    }

@endphp

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

        function changeNote(){
            var note = document.getElementById('note').value;
            
            document.getElementById('note_total_count').innerHTML = note.length;
        }
        
        changeNote();
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    @if($data['client']==1)
        <script>
            $('input[name="clientname"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('input[name="clientname"]').prop("readonly", true);
        </script>
    @endif
    
    @if($data['is_note']==1)
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif

    <script type="text/javascript">
        
        @if(isset($data['suggest']))
           @if($data['suggest'] == "1")
               $(document).ready(function() {
                   setTimeout(function(){
                       $('.include-performance-container').show(500);
                   },500)


                   @if($data['suggestedlist_type'] == "createlist")
                       $('.customlist-suggested-scheme-container').css('display','none');
                       $('.categorylist-suggested-scheme-container').css('display','none');
                       $('.createlist-suggested-scheme-container').css('display','block');
                   @elseif($data['suggestedlist_type'] == "customlist")
                       $('.customlist-suggested-scheme-container').css('display','block');
                       $('.categorylist-suggested-scheme-container').css('display','none');
                       $('.createlist-suggested-scheme-container').css('display','none');
                   @elseif($data['suggestedlist_type'] == "categorylist")
                       $('.customlist-suggested-scheme-container').css('display','none');
                       $('.categorylist-suggested-scheme-container').css('display','block');
                       $('.createlist-suggested-scheme-container').css('display','none');
                   @endif
               });
           @endif
       @endif
   </script>
   <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
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
                <h3 class="smalllineHeading">Recover Your EMI's through SIPs</h3>
                
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
                                            <input class="form-check-input fixed_deposit_chk" id="sipInv" type="radio" name="enter_loan_details" onchange="TurnOnSip('sipInv')"  checked value="1" @if($data['enter_loan_details']=='1') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer" for="emiInv">Enter EMI Details
                                            <input class="form-check-input fixed_deposit_chk" id="emiInv" type="radio" name="enter_loan_details" value="2"  onchange="TurnOnSip('emiInv')" @if($data['enter_loan_details']=='2') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label" id="monthly_emi">Outstanding Loan Amount</label>
                                <div class="col-sm-7">
                                    <input type="text" name="amount" class="form-control" value="{{$data['amount']}}">
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
                                            <input type="text" name="interest" class="form-control" value="{{$data['interest']}}">
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
                                        <input type="text" name="period" class="form-control" value="{{$data['period']}}">
                                    </div>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed return on SIP Investment</label>
                                <div class="col-sm-7">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="expected_interest" class="form-control" value="{{$data['expected_interest']}}">
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
                                        <input id="is_client" type="checkbox" name="client" value="1" @if($form_data['client']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                        <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{$form_data['clientname']}}" maxlength="30">
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
                                                <input id="is_graph" type="checkbox" name="is_graph" value="1" @if($form_data['is_graph']=='1') checked  @endif> 
                                                <span class="checkmark"></span>
                                            </label>
                                    </div>
                                </div> --}}
                                <div class="form-group row">
                                    <div class="col-sm-5">
                                        <label class="sqarecontainer">Add Comments (If any)
                                            <input id="is_note" type="checkbox" name="is_note" value="1" @if($form_data['is_note']=='1') checked  @endif> 
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$form_data['note']}}</textarea>
                                        <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Get Report</label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Summary Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if($data['report']=='summary') checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer">Detailed Report
                                                <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if($data['report']=='summary')  @else checked  @endif >
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                @include('frontend.calculators.suggested.edit_form')
                                <div class="form-group row">
                                    
                                    <!-- <div class="offset-1 col-sm-10">
                                        <div class=" calcBelowBtn">
                                                <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                                <button class="btn banner-btn mx-3">Calculate</button>
                                        </div>
                                    </div> -->
                                    
                                    <div class="offset-1 col-sm-10">
                                        <div class="calcBelowBtn">
                                                <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><i class="fa fa-angle-left"></i> Back</button>
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
