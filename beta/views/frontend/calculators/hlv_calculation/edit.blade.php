@extends('layouts.frontend')

@php

    $data = $form_data;
    // dd($data['asset_name']);
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
    
    $data['client_name'] = old('client_name');
    if(!$data['client_name']){
        $data['client_name'] = $form_data['clientname'];
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

var calculation_type = $("input[name='sip_or_stp']").val();
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
                    current: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        //range: [0, (parseInt(document.getElementById('ret').value)-1)],
                    },
                    retire: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        min: function() {
                            return $("#cur").val();
                        }
                    },
                    discount: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    anual: {
                        required: true,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    personal: {
                        required: false,
                        number: true,
                        
                        //range: [0, (parseInt(document.getElementById('anual').value)-1)],
                    },
                    expected: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 100.00],
                    }
                },
                messages: {
                    current: "Please enter a value between 0 and 99 and must be lesser than retirement age",
                    retire: "Please enter a value between 0 and 99 and must be greater than current age",
                    discount: "Please enter a value between 0.01 and 15",
                    anual: "Please enter a value between 0 and 9999999999",
                    personal: "Please enter a value between 0 and 9999999999 and must be less than anual income",
                    expected: "Please enter a value between 0 and 100",
                }
            });
        });
        
        $(function() {
            jQuery.validator.addMethod("twodecimalplaces", function(value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            var validator = $(".js-validate-form-sub").validate({
                errorElement: "em",
                errorContainer: $("#warning, #summary"),
                errorPlacement: function(error, element) {
                    error.appendTo(element.parent());
                },
                rules: {
                    current: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    retire: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                         min: function() {
                            return $("#cur2").val();
                        }
                    },
                    discount: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    anual: {
                        required: true,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    spouse: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 99],
                    },
                    expected: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 100.00],
                    },
                    household: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    anualincrement: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 100],
                    },
                    inflation: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    anualretire: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    rateofreturn: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    annuity: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    market: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    lifeinsure: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    anualsavings: {
                        required: false,
                        number: true,
                        
                        range: [0, 9999999999],
                    },
                    assumedrate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    }
                },
                messages: {
                    current: "Please enter a value between 0 and 99 and must be lesser than retirement age",
                    retire: "Please enter a value between 0 and 99 and must be greater than current age",
                    discount: "Please enter a value between 0.01 and 15",
                    anual: "Please enter a value between 0 and 9999999999",
                    spouse: "Please enter a value between 0 and 99",
                    expected: "Please enter a value between 0 and 100",
                    anualsavings: "Please enter a value between 0 and 9999999999",
                    lifeinsure: "Please enter a value between 0 and 9999999999",
                    market: "Please enter a value between 0 and 9999999999",
                    annuity: "Please enter a value between 0 and 9999999999",
                    anualretire: "Please enter a value between 0 and 9999999999",
                    household: "Please enter a value between 0 and 9999999999",
                    
                    assumedrate: "Please enter a value between 0.01 and 15",
                    rateofreturn: "Please enter a value between 0.01 and 15",
                    inflation: "Please enter a value between 0.01 and 15",
                    anualincrement: "Please enter a value between 0 and 100",
                }
            });
        });

        $(function () {
            jQuery.validator.addMethod("twodecimalplaces", function (value, element) {
                return this.optional(element) || /^\d{0,4}(\.\d{0,2})?$/i.test(value);
            }, "You must include two decimal places");

            var validator = $("#formId").validate({
                errorElement: "em",
                errorContainer: $("#warning, #summary"),
                errorPlacement: function (error, element) {
                    error.appendTo(element.parent());
                },
                rules: {
                    current: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                    },
                    retire: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        min: function () {
                            return ($("#formType").val() == 1) ? $("#cur").val() : $("#cur2").val();
                        }
                    },
                    discount: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    anual: {
                        required: true,
                        number: true,
                        range: [0, 9999999999],
                    },
                    personal: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    expected: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 100.00],
                    },
                    spouse: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 99],
                    },
                    household: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    anualincrement: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0, 100],
                    },
                    inflation: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    anualretire: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    rateofreturn: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    annuity: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    market: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    lifeinsure: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    anualsavings: {
                        required: false,
                        number: true,
                        range: [0, 9999999999],
                    },
                    assumedrate: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    }
                },
                messages: {
                    current: "Please enter a value between 0 and 99 and must be lesser than retirement age",
                    retire: "Please enter a value between 0 and 99 and must be greater than current age",
                    discount: "Please enter a value between 0.01 and 15",
                    anual: "Please enter a value between 0 and 9999999999",
                    personal: "Please enter a value between 0 and 9999999999 and must be less than anual income",
                    expected: "Please enter a value between 0 and 100",
                    spouse: "Please enter a value between 0 and 99",
                    anualsavings: "Please enter a value between 0 and 9999999999",
                    lifeinsure: "Please enter a value between 0 and 9999999999",
                    market: "Please enter a value between 0 and 9999999999",
                    annuity: "Please enter a value between 0 and 9999999999",
                    anualretire: "Please enter a value between 0 and 9999999999",
                    household: "Please enter a value between 0 and 9999999999",
                    
                    assumedrate: "Please enter a value between 0.01 and 15",
                    rateofreturn: "Please enter a value between 0.01 and 15",
                    inflation: "Please enter a value between 0.01 and 15",
                    anualincrement: "Please enter a value between 0 and 100",
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
                emi.style.display = 'none';
                $('#emi').hide();
                // $('#formId').removeClass('js-validate-form-sub');
                // $('#formId').addClass('js-validate-form');
                $('#formType').val(1);
                $("#emi :input").attr("disabled", true);
                $("#sip :input").attr("disabled", false);
                //monthly_emi.innerHTML = 'Outstanding Loan Amount';
                //global_type = 1;
            }else{
                sip.style.display = 'none';
                emi.style.display = 'block';
                $('#sip').hide();
                // $('#formId').removeClass('js-validate-form');
                // $('#formId').addClass('js-validate-form-sub');
                $('#formType').val(2);
                $("#sip :input").attr("disabled", true);
                $("#emi :input").attr("disabled", false);
                //monthly_emi.innerHTML = 'Monthly EMI';
                //global_type = 2;
            }
        }
        @if($data['formType'] == 1)
            TurnOnSip('sipInv')
        @else
            TurnOnSip('emiInv')
        @endif
        
        
        
        function removeMe(element)
        {
            var ele = document.getElementById(element);
            ele.remove();
            var i = 0;
            
            var collection = document.getElementById("financial_goal_plan").children;
            
            for(let i=0;i<collection.length;i++)
            {
                collection[i].setAttribute('id',i);
                collection[i].getElementsByTagName('input')[0].setAttribute('name','financial'+i);
                collection[i].getElementsByTagName('input')[1].setAttribute('name','requiredAmount'+i);
                collection[i].getElementsByTagName('input')[2].setAttribute('name','yearsLeft'+i);
                collection[i].getElementsByTagName('button')[0].setAttribute('onclick','removeMe('+i+')');
            }
        }

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
        
        changeNote();
    </script>
    <script>
        var count = 1;
        function addField()
        {
            var section = document.getElementById("financial_goal_plan");
            var htmlToInsert = '<div id="'+count+'" class="row financial_block" style="margin-top: 7px;align-items: center;"><div class="col-sm-11 financial_input"><div class="form-group row"> <label class="col-sm-5 col-form-label">Financial Goal Name</label> <div class="col-sm-7"> <div class="d-flex align-items-center"> <input type="text" name="financial'+count+'" class="form-control" value=""> </div> </div> </div> <div class="form-group row"> <label class="col-sm-5 col-form-label">Amount Required</label> <div class="col-sm-7"> <div class="d-flex align-items-center"> <input type="text" name="requiredAmount'+count+'" class="form-control" value=""> </div> <div class="cal-icon"> ₹ </div> </div> </div> <div class="form-group row"> <label class="col-sm-5 col-form-label">Years Left</label> <div class="col-sm-7"> <div class="d-flex align-items-center"> <input type="text" name="yearsLeft'+count+'" class="form-control" value=""> </div> <div class="cal-icon"> Yrs </div> </div> </div></div><div class="col-sm-1 financial_delete" style="padding-bottom: 15px;"><button type="button" style="color:red;" onclick="removeMe('+count+')"><i class="fa fa-trash" aria-hidden="true"></i></button></div>';
            
            document.getElementById("financial_goal_plan").insertAdjacentHTML('beforeend', htmlToInsert);
            count++;
        }
    </script>
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
    @if(isset($data['client']) && $data['client']==1)
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
<div class="banner">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h2 class="page-title">PREMIUM CALCULATORS</h2>
            </div>
        </div>
    </div>
</div> 

<section class="main-sec styleNew">
    <div class="container">
        <div class="row">
            @include('frontend.calculators.left_sidebar')
            <div class="col-md-12">
                <h3 class="smalllineHeading">Human Life Value Calculator</h3>
                
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form enctype="multipart/form-data" method="post" class="" action="{{route('frontend.hlv_calculation_output')}}" name="recover_emis_through_sip_form" id="formId">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer" for="sipInv">Income Replacement Method
                                            <input class="form-check-input fixed_deposit_chk" type="radio" id="sipInv" name="enter_loan_details" onchange="TurnOnSip('sipInv')"   value="1" @if($data['formType'] == 1) checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer" for="emiInv">Need Analysis Method
                                            <input class="form-check-input fixed_deposit_chk" type="radio" id="emiInv" name="enter_loan_details" value="2"  onchange="TurnOnSip('emiInv')" @if($data['formType'] == 0) checked @endif>
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div  id="sip">
                                <input type="hidden" name="formType" value="1" id="formType" />
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label" id="monthly_emi">Current Age</label>
                                    <div class="col-sm-7">
                                        <input type="text" id="cur" name="current" class="form-control" value="{{isset($data['current'])?$data['current']:''}}">
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                        <label class="col-sm-5 col-form-label">Retirement Age</label>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center">
                                                <input id="ret" type="text" name="retire" class="form-control" value="{{isset($data['retire'])?$data['retire']:''}}">
                                            </div>
                                            <div class="cal-icon">
                                                Yrs
                                            </div>
                                        </div>
                                    </div>


                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Discounting Rate</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="discount" class="form-control" value="{{isset($data['discount'])?$data['discount']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Annual Income</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input id="anual" type="text" name="anual" class="form-control" value="{{isset($data['anual'])?$data['anual']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Annual Personal Expenses</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="personal" class="form-control" value="{{isset($data['personal'])?$data['personal']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row openEye">
                                    <label class="col-sm-5 col-form-label">Expected Annual Increment  &nbsp;
                                    <span><i class="fa fa-eye"></i><span>The Annual Increment applies to both Current Annual Income as well as Annual Personal Expenses</span></span>
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="expected" class="form-control" value="{{isset($data['expected'])?$data['expected']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div id="emi" style="display:none;">
                                <input type="hidden" name="formType" value="0" id="formType" />
                                <div class="form-group row">
                                <label class="col-sm-5 col-form-label" id="monthly_emi">Current Age</label>
                                <div class="col-sm-7">
                                    <input type="text" name="current" id="cur2" class="form-control" value="{{isset($data['current'])?$data['current']:''}}">
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                                </div>
                            
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Retirement Age</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="retire" class="form-control" value="{{isset($data['retire'])?$data['retire']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Spouse Age</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="spouse" class="form-control" value="{{isset($data['spouse'])?$data['spouse']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            Yrs
                                        </div>
                                    </div>
                                </div>
                                


                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Discounting Rate <span><span>Discounting Rate</span></span></label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="discount" class="form-control" value="{{isset($data['discount'])?$data['discount']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Annual Income</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="anual" class="form-control" value="{{isset($data['anual'])?$data['anual']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Expected Annual Increment
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="expected" class="form-control" value="{{isset($data['expected'])?$data['expected']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                                
                                <div id='financial_goal_plan' style="border: 1px solid rgb(216, 214, 214); padding: 15px 15px 0px 15px;">
                                    <div class="row financial_block" id='0' style="align-items: center;">
                                        <div class="col-sm-11 financial_input">
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Financial Goal Name</label>
                                                <div class="col-sm-7">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" name="financial0" class="form-control" value="{{isset($data['financial0'])?$data['financial0']:''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Amount Required</label>
                                                <div class="col-sm-7">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" name="requiredAmount0" class="form-control" value="{{isset($data['requiredAmount0'])?$data['requiredAmount0']:''}}">
                                                    </div>
                                                    <div class="cal-icon">
                                                        ₹
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-5 col-form-label">Years Left</label>
                                                <div class="col-sm-7">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" name="yearsLeft0" class="form-control" value="{{isset($data['yearsLeft0'])?$data['yearsLeft0']:''}}">
                                                    </div>
                                                    <div class="cal-icon">
                                                        Yrs
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 financial_delete" style="padding-bottom: 15px;">
                                            <button style='color:red;' onclick='removeMe(0)'>
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                            </button>
                                            
                                        </div>
                                    </div>
                                    
                                </div>
                                
                                <div class="form-group row">
                                    <div class="offset-10 col-sm-6">
                                        <div class="col-md-4">
                                            <div class="calcBelowBtn mt-3">
                                                <button type="button" class="btn banner-btn" onclick="addField()">Add More</button>
                                            </div>
                                            </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Annual Household Expenses</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="household" class="form-control" value="{{isset($data['household'])?$data['household']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Expected Annual Increment</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="anualincrement" class="form-control" value="{{isset($data['anualincrement'])?$data['anualincrement']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Annual Retirement Expenses For Spouse</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="anualretire" class="form-control" value="{{isset($data['anualretire'])?$data['anualretire']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed Inflation Rate (Retirement Period)</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="inflation" class="form-control" value="{{isset($data['inflation'])?$data['inflation']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed Rate of Return (Distribution Period)</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="rateofreturn" class="form-control" value="{{isset($data['rateofreturn'])?$data['rateofreturn']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Annuity Required Till Age</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="annuity" class="form-control" value="{{isset($data['annuity'])?$data['annuity']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Market Value of Investments</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="market" class="form-control" value="{{isset($data['market'])?$data['market']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Current Life Insurance Cover + Accrued Bonus</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="lifeinsure" class="form-control" value="{{isset($data['lifeinsure'])?$data['lifeinsure']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row openEye">
                                    <label class="col-sm-5 col-form-label">Current Annual Savings &nbsp;
                                    <span><i class="fa fa-eye"></i><span>The User need to enter the Current Annual Savings manually after taking into account other discretionary expenses</span></span>
                                    </label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="anualsavings" class="form-control" value="{{isset($data['anualsavings'])?$data['anualsavings']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Assumed Rate of Return on Investment</label>
                                    <div class="col-sm-7">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="assumedrate" class="form-control" value="{{isset($data['assumedrate'])?$data['assumedrate']:''}}">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                
                                <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if(old('client', isset($data['client'])?$data['client']:'')=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{old('clientname', isset($data['clientname'])?$data['clientname']:'')}}" maxlength="30">
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
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if(old('is_note', isset($data['is_note'])?$data['is_note']:'')=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{old('note', isset($data['note'])?$data['note']:'')}}</textarea>
                                    <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Get Report</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Summary Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if(old('report', isset($data['report'])?$data['report']:'')=='summary') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Detailed Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if(old('report', isset($data['report'])?$data['report']:'')=='summary')  @else checked  @endif >
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                </div>
                            </div>

                            @include('frontend.calculators.suggested.edit_form')


                            <div class="form-group row">

                                <div class="offset-1 col-sm-10">
                                    <div class=" calcBelowBtn">
                                            <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                            

                                            @if(session()->get('hlv_calculation_form_id'))
                                                <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                            @else
                                                <a href="{{route('frontend.hlv_calculation')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
                                            @endif
                                            
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">-->
        <!--</div>-->
    </div>
</section>

@endsection