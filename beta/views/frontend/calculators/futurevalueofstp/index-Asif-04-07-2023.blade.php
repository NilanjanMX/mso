@extends('layouts.frontend')

@section('js_after')
    <script>

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
                    purchase: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'one')
                            return true;
                            else
                            return false;
                        }
                        
                        
                        //range: [0, (parseInt(document.getElementById('ret').value)-1)],
                    },
                    invest: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'one')
                            return true;
                            else
                            return false;
                        },
                        digits: true,
                        
                        range: [100, 9999999999]
                    },
                    
                    redeem: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'one')
                            return true;
                            else
                            return false;
                        },
                        number: false,
                        
                        
                        
                    },
                    current: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'one')
                            return true;
                            else
                            return false;
                        },
                        number: true,
                        range: [100,9999999999]
                        
                        //range: [0, (parseInt(document.getElementById('anual').value)-1)],
                    },
                    
                    //option 2 data
                    redeem1: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'two')
                            return true;
                            else
                            return false;
                        },
                        number: false
                        
                        
                    },
                    units: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'two')
                            return true;
                            else
                            return false;
                        },
                        number: true,
                        range: [1,9999999999]
                        
                        
                    },
                    nav: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'two')
                            return true;
                            else
                            return false;
                        },
                        number: true,
                        range: [1,999]
                        
                        
                    },
                    purchase1: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'two')
                            return true;
                            else
                            return false;
                        },
                        number: false
                        
                        
                    },
                    currentnav: {
                        required: function()
                        {
                            if(document.getElementById('option').value == 'two')
                            return true;
                            else
                            return false;
                        },
                        number: true
                        
                        
                    },
                    ltcg: {
                        required: true,
                        number: true,
                        maxlength: 2
                        
                        
                        
                    },
                    indexation: {
                        required: true,
                        number: true,
                        range: [0,15]
                        
                        
                        
                    },
                    expected: {
                        required: true,
                        number: true,
                        range: [0,15]
                        
                        
                        
                    },
                    shortterm: {
                        required: true,
                        number: true,
                        range: [0,50]
                        
                        
                        
                    },
                    longterm: {
                        required: true,
                        number: true,
                        range: [0,50]
                        
                        
                        
                    }
                    
                },
                messages: {
                    purchase: "Please enter a date less than Redemption Date",
                    invest: "Please enter a value between Between 100 - 9999999999",
                    redeem: "Please enter a date greater than Purchase Date",
                    current: "Please enter a value between Between 100 - 9999999999",
                    redeem1: "Please enter a date greater than Purchase Date",
                    purchase1: "Please enter a date less than Redemption Date",
                    units: "Please enter a value between Between 1 - 9999999999",
                    nav: "Please enter a value between Between 1 - 999",
                    currentnav: "Please enter a value between Between 1 - 999",
                    ltcg: "Please enter a value between Between 0 - 99",
                    indexation:"Please enter a value between Between 0 - 15.00",
                    expected:"Please enter a value between Between 0 - 15.00",
                    shortterm:"Please enter a value between Between 0 - 50.00",
                    longterm:"Please enter a value between Between 0 - 50.00",
                    
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
                    initial: {
                        required: true,
                        digits: true,
                        maxlength: 10,
                        range: [100,9999999999]
                    },
                    invyear: {
                        required: true,
                        digits: true,
                        maxlength: 2,
                        max:function()
                        {
                            return (parseInt(document.getElementById("aft").value)-1);
                        }
                         
                    },
                    invmonth: {
                        required: false,
                        digits: true,
                        maxlength: 2,
                         max:function()
                         {
                             var total = (parseInt(document.getElementById("aft").value) * parseInt(document.getElementById("inM").value)) - (parseInt(document.getElementById("inY").value) * parseInt(document.getElementById("inM").value));
                             if(total > 11)
                             return 11;
                             else
                             return total;
                         },
                         
                    },
                    expected: {
                        required: true,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 9.00],
                    },
                    indexation: {
                        required: false,
                        number: true,
                        twodecimalplaces: true,
                        range: [0.10, 15.00],
                    },
                    shortterm: {
                        required: true,
                        number: true,
                        
                        range: [0, 50],
                    },
                    longterm: {
                        required: true,
                        number: true,
                        
                        range: [0, 50],
                    },
                    
                    
                    after: {
                        required: true,
                        number: true,
                        
                        range: [0, 99],
                    },
                    
                },
                messages: {
                    initial : "Please enter a value between 100-9999999999",
                    invyear : "Please enter a value less than \n Applicable Long Term Tax Rate After period",
                    invmonth : "Please enter a value that does not exceed Applicable Long Term Tax Rate After period",
                    expected : "Please enter a value between 0-9",
                    indexation : "Please enter a value between 0-15",
                    shortterm : "Please enter a value between 0-50",
                    longterm : "Please enter a value between 0-50",
                    after : "Please enter a value between 0-99",
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
                $('#formId').removeClass('js-validate-form-sub');
                $('#formId').addClass('js-validate-form');
                $('#formType').val(1);
                $("#emi :input").attr("disabled", true);
                $("#sip :input").attr("disabled", false);
                //monthly_emi.innerHTML = 'Outstanding Loan Amount';
                //global_type = 1;
            }else{
                sip.style.display = 'none';
                emi.style.display = 'block';
                $('#formId').removeClass('js-validate-form');
                $('#formId').addClass('js-validate-form-sub');
                $('#formType').val(2);
                $("#sip :input").attr("disabled", true);
                $("#emi :input").attr("disabled", false);
                //monthly_emi.innerHTML = 'Monthly EMI';
                //global_type = 2;
            }
        }
        //$count = 1;
        var count = 1;
        function addField()
        {
            var section = document.getElementById("financial_goal_plan");
            var htmlToInsert = '<div id="'+count+'" class="financial_block" style="margin-top: 7px;"><div class="financial_input"><div class="form-group row"> <label class="col-sm-4 col-form-label">Financial Goal Name</label> <div class="col-sm-8"> <div class="d-flex align-items-center"> <input type="text" name="financial'+count+'" class="form-control" value=""> </div> </div> </div> <div class="form-group row"> <label class="col-sm-4 col-form-label">Amount Required</label> <div class="col-sm-8"> <div class="d-flex align-items-center"> <input type="text" name="requiredAmount'+count+'" class="form-control" value=""> </div> <div class="cal-icon"> ₹ </div> </div> </div> <div class="form-group row"> <label class="col-sm-4 col-form-label">Years Left</label> <div class="col-sm-8"> <div class="d-flex align-items-center"> <input type="text" name="yearsLeft'+count+'" class="form-control" value=""> </div> <div class="cal-icon"> Yrs </div> </div> </div></div><div class="financial_delete"><button type="button" style="color:red;" onclick="removeMe('+count+')"><i class="fa fa-trash" aria-hidden="true"></i></button></div>';
            
            document.getElementById("financial_goal_plan").insertAdjacentHTML('beforeend', htmlToInsert);
            count++;
        }
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
        
        function TurnOnOption(val)
        {
            if(val == 'one')
            {
                document.getElementById('option').value = 'one';
                var one = document.getElementById('pdate');
                var two = document.getElementById('inv');
                var thr = document.getElementById('redeem');
                var market = document.getElementById('market');
                console.log("called");
                one.removeAttribute("readonly",false);
                two.removeAttribute("readonly",false);
                thr.removeAttribute("readonly",false);
                market.removeAttribute("readonly",false);
                
                 one = document.getElementById('pdate1');
                 two = document.getElementById('inv1');
                 thr = document.getElementById('redeem1');
                 market = document.getElementById('market1');
                 var nav = document.getElementById('cnav');
                console.log("called");
                one.setAttribute("readonly",true);
                two.setAttribute("readonly",true);
                thr.setAttribute("readonly",true);
                market.setAttribute("readonly",true);
                nav.setAttribute("readonly",true);
            }
            if(val == 'two')
            {
                document.getElementById('option').value = 'two';
                var one = document.getElementById('pdate1');
                var two = document.getElementById('inv1');
                var thr = document.getElementById('redeem1');
                var market = document.getElementById('market1');
                var nav = document.getElementById('cnav');
                console.log("called");
                one.removeAttribute("readonly",false);
                two.removeAttribute("readonly",false);
                thr.removeAttribute("readonly",false);
                market.removeAttribute("readonly",false);
                nav.removeAttribute("readonly",false);
                
                one = document.getElementById('pdate');
                 two = document.getElementById('inv');
                 thr = document.getElementById('redeem');
                 market = document.getElementById('market');
                
                one.setAttribute("readonly",true);
                two.setAttribute("readonly",true);
                thr.setAttribute("readonly",true);
                market.setAttribute("readonly",true);
            }
        }
        
        var intervalId = window.setInterval(function(){
            
        var date1 = document.getElementById("pdate").value;
        var AllDates = date1.split('/');
        
        
        
        var newDtStr = AllDates[1] + '/' + AllDates[0] + '/' + AllDates[2];
        
        date1 = new Date(newDtStr);
        
            var date2 = document.getElementById("redeem").value;
            var AllDates2 = date2.split('/');
        
        
        var newDtStr2 = AllDates2[1] + '/' + AllDates2[0] + '/' + AllDates2[2];
        
            date2 = new Date(newDtStr2);

        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

        const years = parseInt(diffDays/365);
        const ltcg = parseInt(document.getElementById("lt").value);
        const tax = document.getElementById("tax");
        console.log("years "+date2 + " ltcg "+date1);
        if(years > ltcg)
        {
        tax.value = "Long Term"; 
        }
        else
        {
            tax.value = "Short Term"; 
        }
        }, 1000);

        function checkTotalDiff()
        {
            console.log("Called");
            var date1 = document.getElementById("pdate").value;
        var AllDates = date1.split('/');
        
        
        
        var newDtStr = AllDates[1] + '/' + AllDates[0] + '/' + AllDates[2];
        
        date1 = new Date(newDtStr);
        
            var date2 = document.getElementById("redeem").value;
            var AllDates2 = date2.split('/');
        
        
        var newDtStr2 = AllDates2[1] + '/' + AllDates2[0] + '/' + AllDates2[2];
        
            date2 = new Date(newDtStr2);
            
        const diffTime = Math.abs(date2 - date1);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

        const years = diffDays/365;
        console.log("total years are "+years);
        if(years <0 || years > 3)
        {
            alert("redeem date can not be more than three years of purchase date or less than purchase date");
            document.getElementById("redeem").value = "";
        }
        }

        function checkTotalDiffNew()
        {
            console.log("Called");
            var date1 = document.getElementById("pdate1").value;
        var AllDates = date1.split('/');
        
        
        
        var newDtStr = AllDates[1] + '/' + AllDates[0] + '/' + AllDates[2];
        
        date1 = new Date(newDtStr);
        
            var date2 = document.getElementById("redeem1").value;
            var AllDates2 = date2.split('/');
        
        
        var newDtStr2 = AllDates2[1] + '/' + AllDates2[0] + '/' + AllDates2[2];
        
            date2 = new Date(newDtStr2);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

            const years = diffDays/365;

        if(years <0 || years > 3)
        {
            alert("redeem date can not be more than three years of purchase date or less than purchase date");
            document.getElementById("redeem1").value = "";
        }
        }

        $('#pdate').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true,
            format: 'dd/mm/yyyy'
        });
        
        $('#redeem').datepicker({
            
            clearBtn: true,
            autoclose: true,
            format: 'dd/mm/yyyy'
            
        });
        
        $('#pdate1').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true,
            format: 'dd/mm/yyyy'
        });
        
        $('#redeem1').datepicker({
            uiLibrary: 'bootstrap4',
            clearBtn: true,
            autoclose: true,
            format: 'dd/mm/yyyy'
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
        
        TurnOnSip('sipInv');
        $('.sipInvcheck').prop('checked', true);
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
    @if(old('note')!='')
        <script>
            $('textarea[name="note"]').prop("readonly", false);
        </script>
    @else
        <script>
            $('textarea[name="note"]').prop("readonly", true);
        </script>
    @endif
    
    <link rel="stylesheet" href="{{asset('')}}/f/css/calculator.css">
@endsection

@section('content')
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
                    <h3 class="smalllineHeading">Debt Fund (Hold/Sell) Benefit Calculation</h3>
                    
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.debt_fund_trade_off_output')}}" method="post" id="formId">
                        @csrf
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label"></label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk sipInvcheck" type="radio" name="selectType" onchange="TurnOnSip('sipInv')"  checked value="1">
                                        <label class="form-check-label" for="inlineRadio1">Existing Investment</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="selectType" value="2"  onchange="TurnOnSip('emiInv')" >
                                        <label class="form-check-label" for="inlineRadio2">New Investment</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="sip">
                                <div>        
                                        <label class="form-check-label" for="inlineRadio2">Option 1</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="enter_loan_details" value="1"  onchange="TurnOnOption('one')" checked>
                                    </div>
                                    <input type="hidden" name="formType" value="1" />
                                    <input type="hidden" id="option" name="optionType" value="one" />
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" id="monthly_emi">Purchase Date</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" id="pdate" name="purchase" class="form-control" value="" onChange="checkTotalDiff()" autocomplete="off">
                                                </div>
                                            
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Investment Amount</label>
                                            <div class="col-sm-8">
                                                <div class="d-flex align-items-center">
                                                    <input id="inv" type="text" name="invest" class="form-control" value="">
                                                </div>
                                                <div class="cal-icon">
                                                    ₹
                                                </div>
                                            </div>
                                        </div>
                                    
                                        

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Redemption Date</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                
                                                <input id="redeem" type="text" name="redeem" class="form-control" value="" onChange="checkTotalDiff()" autocomplete="off">
                                                </div>
                                            
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Current Market Value</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="market" type="text" name="current" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- option2 -->
                                    
                                    <div>
                                                
                                                <label class="form-check-label" for="inlineRadio2">Option 2</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="enter_loan_details" value="2"  onchange="TurnOnOption('two')" >
                                            </div>
                                    
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" id="monthly_emi">Purchase Date</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" id="pdate1" name="purchase1" class="form-control" value="" onChange="checkTotalDiffNew()" autocomplete="off" readonly>
                                                </div>
                                            
                                        </div>
                                    </div>
                                    
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">No. of Units</label>
                                            <div class="col-sm-8">
                                                <div class="d-flex align-items-center">
                                                    
                                                    <input id="inv1" type="text" name="units" class="form-control" value="" readonly>
                                                </div>
                                                <div class="cal-icon">
                                                    Unit
                                                </div>
                                            </div>
                                        </div>
                                    


                                    

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Purchase NAV</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="market1" type="text" name="nav" class="form-control" value="" readonly>
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Redemption Date</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="redeem1" type="text" name="redeem1" class="form-control" value="" onChange="checkTotalDiffNew()" autocomplete="off" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Current Nav</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="cnav" type="text" name="currentnav" class="form-control" value="" readonly>
                                            </div>
                                            <div class="cal-icon">
                                                ₹
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End option 2 -->
                                    <br/>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">LTCG Applicable After</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="ltcg" class="form-control" value="3" id="lt" readonly>
                                            </div>
                                            <div class="cal-icon">
                                                Yrs
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Applicable Taxation</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="taxation" class="form-control" value="short term" id="tax" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Assumed Indexation Rate</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="" type="text" name="indexation" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Expected Return For Remaining Period</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="" type="text" name="expected" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Applicable Short Term Tax Rate</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="" type="text" name="shortterm" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Applicable Long Term Tax Rate</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input id="" type="text" name="longterm" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    
                            </div>
                            <div id="emi">
                                <input type="hidden" name="formType" value="2" />
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label" id="monthly_emi">Initial Investment</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="initial" id="" class="form-control" value="">
                                        <div class="cal-icon">
                                            ₹
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Applicable Short Term Tax Rate</label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="shortterm" class="form-control" value="">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Applicable Long Term Tax Rate
                                    </label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                                <div class="col-sm-6">
                                                <div class="d-flex align-items-center">
                                            <input type="text" class="form-control pr-2 mr-1 maxtwodigit " name="longterm" value="" required="" maxlength="2">
                                            
                                            
                                            </div>
                                            <div class="cal-icon" style="width:79px; right:20px;">
                                            %
                                            </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    
                                                    <div class="d-flex align-items-center">
                                                        <lable class="col-form-label">After</lable>&nbsp;
                                                        <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="aft" name="after" value="" maxlength="2">
                                                    </div>
                                                    <div class="cal-icon" style="width:79px; right:20px;">
                                                Yrs
                                            </div>
                                                </div>
                                            </div>
                                        
                                    </div>
                                </div>
                                
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Investment Period</label>
                                        <div class="col-sm-8">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                <div class="d-flex align-items-center">
                                            <input type="text" id="inY" class="form-control pr-2 mr-1 maxtwodigit " name="invyear" value="" required="" maxlength="2">
                                            
                                            
                                            </div>
                                            <div class="cal-icon" style="width:79px; right:20px;">
                                            Yrs
                                            </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="d-flex align-items-center">
                                                        <input type="text" id="inM" class="form-control pr-2 mr-1 maxtwodigit " name="invmonth" value="" maxlength="2">
                                                    </div>
                                                    <div class="cal-icon" style="width:79px; right:20px;">
                                                Months
                                            </div>
                                                </div>
                                            </div>
                                            
                                            
                                            
                                            
                                            </div>
                                            
                                            
                                            
                                        
                                    </div>
                                <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Expected Rate of Return</label>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="expected" class="form-control" value="">
                                            </div>
                                            <div class="cal-icon">
                                                %
                                            </div>
                                        </div>
                                    </div>
                                    


                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label"><span>Assumed Inflation Rate for Indexation</span></label>
                                    <div class="col-sm-8">
                                        <div class="d-flex align-items-center">
                                            <input type="text" name="assumed" class="form-control" value="">
                                        </div>
                                        <div class="cal-icon">
                                            %
                                        </div>
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
    </section>

@endsection
