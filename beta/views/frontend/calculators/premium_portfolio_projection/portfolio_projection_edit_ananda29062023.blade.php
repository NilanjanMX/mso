@extends('layouts.frontend')
@php

    $data['amount'] = old('amount');
    
    $data['report'] = old('report');
    if(!$data['report']){
        $data['report'] = $form_data['report'];
    }
    
    $data['suggest'] = old('suggest');
    if(!$data['suggest']){
        $data['suggest'] = $form_data['suggest'];
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
        
        changeNote();

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
                    lumpsum: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    ilumpsum: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    sip: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    isip: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                    },
                    name2: {
                        required: false,
                        number: false,
                        maxlength: 30,
                    }
                },
                messages: {
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    lumpsum: "Please enter a value between 100 and 9,99,99,99,999.",
                    ilumpsum: "Please enter a value between 100 and 9,99,99,99,999.",
                    sip: "Please enter a value between 100 and 9,99,99,99,999.",
                    isip: "Please enter a value between 100 and 9,99,99,99,999.",
                    rate: "Please enter a value between 0.1 and 18%",
                    period: "Please enter no more than 2 characters"
                }
            });
        });
        
        $(function() {
            jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            var validator = $(".js-sub-validate-form").validate({
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
                    lumpsum: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    ilumpsum: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    sip: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    isip: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    rate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 20.00],
                    },
                    period: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                    },
                    name2: {
                        required: false,
                        number: false,
                        maxlength: 30,
                    }
                },
                messages: {
                    amount: "Please enter a value between 100 and 9,99,99,99,999.",
                    lumpsum: "Please enter a value between 100 and 9,99,99,99,999.",
                    ilumpsum: "Please enter a value between 100 and 9,99,99,99,999.",
                    sip: "Please enter a value between 100 and 9,99,99,99,999.",
                    isip: "Please enter a value between 100 and 9,99,99,99,999.",
                    rate: "Please enter a value between 0.1 and 18%",
                    period: "Please enter no more than 2 characters"
                }
            });
        });
        
        // function TurnOnSip(dat)
        // {
        //     var sip = document.getElementById("ilumpsum_div");
        //     var emi = document.getElementById("isip_view");
            
        //     if(dat == "sipInv"){
        //         sip.style.display = 'none';
        //         emi.style.display = 'none';
        //     }else{
        //         sip.style.display = 'flex';
        //         emi.style.display = 'flex';
        //     }
        // }

        function New(){
            var sip = document.getElementById("ilumpsum_div");
            var emi = document.getElementById("isip_view");
            if (document.getElementById('sipInv').checked) {
                sip.style.display = 'none';
                emi.style.display = 'none';
            }else{
                sip.style.display = 'flex';
                emi.style.display = 'flex';
            } 
        }
        document.getElementById('sipInv').addEventListener('change', New);
        document.getElementById('emiInv').addEventListener('change', New);
        New();

        $("#is_client1").click( function(){
            if( $(this).is(':checked') ){
                $('#name1').prop("readonly", false);
            }else {
                $('#name1').prop("readonly", true);
            }
        });
        $("#is_client2").click( function(){
            if( $(this).is(':checked') ){
                $('#name2').prop("readonly", false);
            }else {
                $('#name2').prop("readonly", true);
            }
        });
        
        $('#name1').prop("readonly", true);
        $('#name2').prop("readonly", true);
    </script>
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
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div>
        </div>

    </div>
    <section class="main-sec">
        <div class="container">
            <div class="row">
                @include('frontend.calculators.left_sidebar')
                <div class="col-md-12">
                    <h3 class="smalllineHeading">Portfolio Projection Report</h3>
                    @include('frontend.calculators.common_bio')
                    
                    <form id="sip" enctype="multipart/form-data" method="post" class="js-validate-form" action="{{route('frontend.portfolio_projection_output')}}" name="recover_emis_through_sip_form">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label"></label>
                                    <div class="col-sm-7">
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer" for="sipInv" >Current Investment
                                                <input class="form-check-input fixed_deposit_chk" id="sipInv" type="radio" name="enter_loan_details" value="1" onchange="TurnOnSip('sipInv')"  @if($form_data['enter_loan_details']=='1')  checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <label class="checkLinecontainer" for="emiInv" >Current + Incremental Investment
                                                <input class="form-check-input fixed_deposit_chk" id="emiInv" type="radio" name="enter_loan_details" value="2"  onchange="TurnOnSip('emiInv')" @if($form_data['enter_loan_details']=='2')  checked  @endif>
                                                <span class="checkmark"></span>
                                            </label> 
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Portfolio Value</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="amount" class="form-control" value="{{$form_data['amount']}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row" id="ilumpsum_div">
                                    <label class="col-sm-5 col-form-label">Current Lumpsum Investment Every Year</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="ilumpsum" class="form-control" value="{{$form_data['ilumpsum'] ?? ''}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Lumpsum Investment Every Year</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="lumpsum" class="form-control" value="{{$form_data['lumpsum']}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Monthly SIP</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="sip" class="form-control" value="{{$form_data['sip'] ?? ''}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" id="isip_view">
                                    <label class="col-sm-5 col-form-label">Addition in SIP(New)</label>
                                    <div class="col-sm-7">
                                        <input type="text" name="isip" class="form-control" value="{{$form_data['isip'] ?? ''}}">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed Rate of Return</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="rate" class="form-control" value="{{$form_data['rate'] ?? ''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Period</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="period" class="form-control" value="{{$form_data['period'] ?? ''}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                    </div>
                                </div>

                        </div>
                            
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                    <input id="is_client" type="checkbox" name="client" value="1" @if($form_data['client']=='1') checked  @endif> 
                                    <span class="checkmark"></span>
                                </label>
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('client') ? ' is-invalid' : '' }}" name="clientname"   value="{{$form_data['clientname']}}" maxlength="30">
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
                                <div class="col-sm-5">
                                    <label class="sqarecontainer">Add Comments (If any)
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if(isset($form_data) && $form_data['is_note']=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$form_data['note'] ?? ''}}</textarea>
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
                                    <div class="calcBelowBtn">
                                            <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn btn-primary btn-round btn-block"><i class="fa fa-angle-left"></i> Back</button>
                                            <button class="btn banner-btn mx-3">Calculate</button>
                                    </div>
                                </div> -->
                                
                                <div class="offset-1 col-sm-10">
                                    <div class="calcBelowBtn">
                                            <button type="button" onclick="window.history.go(-1); return false;" class="btn banner-btn whitebg mx-3"><!-- <i class="fa fa-angle-left"></i> --> Back</button>
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
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
