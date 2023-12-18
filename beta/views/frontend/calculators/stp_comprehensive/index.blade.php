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
                      initial:{
                          required:true,
                          digits:true,
                          range:[100,9999999999],
                      },
                      currentage:{
                          
                          required:false,
                          digits:true,
                          range:[1,99],
                      },
                      debt:{
                          required:true,
                          digits:false,
                          range:[0.1,8.00],
                          
                      },
                      equity:{
                          required:true,
                          digits:false,
                          range:[0.1,15.00],
                          
                      },
                      installments:{
                          required:true,
                          digits:true,
                          range:[1,9999],
                      },
                      years:{
                          required:function()
                          {
                              if(tenure ==  1)
                              return true;
                              else
                              return false;
                          },
                          digits:true,
                          min:function()
                          {
                              return yearsVal;
                          },
                      },
                      months:{
                          required:function()
                          {
                              if(tenure ==  1)
                              return false;
                              else
                              return true;
                          },
                          digits:true,
                          min:function()
                          {
                              return monthVal;
                          },
                          
                      },
                      fixedamount:{
                          required:function()
                          {
                              if(showEach == 2 && acEach == 1)
                              {
                                  return true;
                              }
                              else
                              {
                                  return false;
                              }
                          },
                          digits:true,
                          max:function()
                          {
                              
                              
                              return fixedAmtVal;
                          },
                      },
                      fixedpercent:{
                           required:function()
                          {
                              if(showEach == 2 && acEach == 2)
                              {
                                  return true;
                              }
                              else
                              {
                                  return false;
                              }
                          },
                          digits:false,
                          max:function()
                          {
                              
                              
                              return (fixedPercentVal * 100);
                          },
                      }
                       
                },
                messages: {
                    
                    
                    // swpamount:function()
                    // {
                    //     if(document.getElementById("swptype").value == "inmonth")
                    //     {
                    //         return "In month the value can not go beyond 50000";
                    //     }
                    //     else
                    //     {
                    //         return "In annual the value can not go beyond 20";
                    //     }
                    // },
                    
                    
                }
            });
        });
        
        
        function addclient(val)
        {
            if(val.checked)
            document.getElementById('name2').removeAttribute('readonly');
            else{
                document.getElementById('name2').value = "";
                document.getElementById('name2').setAttribute('readonly',true);
            }
        }
        
        var validate = function(e) {
  var t = e.value;
  e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
}
        
        var yearsVal;
        var monthVal;
        var fixedAmtVal;
        var fixedPercentVal;
        var int = window.setInterval(function()
        {
            var dt = parseFloat(document.getElementById("debt").value);
                              var init = parseFloat(document.getElementById("ini").value);
                              var dat = document.getElementById("installments");
                              dat = parseInt(dat.value);
                              if(document.getElementById("headertype").value == "monthly")
                              {
                                  var vl = Math.pow((1+dt/100),(1/12))-1;
                              }
                              else if(document.getElementById("headertype").value == "daily")
                              {
                                  var vl = Math.pow((1+dt/100),(1/365))-1;
                              }
                              else if(document.getElementById("headertype").value == "quater")
                              {
                                  var vl = Math.pow((1+dt/100),(1/4))-1;
                              }
                              else if(document.getElementById("headertype").value == "half-year")
                              {
                                  var vl = Math.pow((1+dt/100),(1/2))-1;
                              }
                              else if(document.getElementById("headertype").value == "weekly")
                              {
                                  var vl = Math.pow((1+dt/100),(1/52))-1;
                              }
                              else if(document.getElementById("headertype").value == "fortnight")
                              {
                                  var vl = Math.pow((1+dt/100),(1/24))-1;
                              }
                              else
                              {
                                  var vl = Math.pow((1+dt/100),(1/1))-1;
                              }
                              
                              fixedAmtVal = parseInt((vl * init)/(1-Math.pow((1+vl),-dat)));
                              fixedPercentVal = fixedAmtVal / init;
                              
                              dat = document.getElementById("installments");
                              dat = parseInt(dat.value);
                              if(document.getElementById("headertype").value == "monthly")
                              {
                                  monthVal = dat;
                                  console.log("working monthly");
                              }
                              else if(document.getElementById("headertype").value == "daily")
                              {
                                  if(dat > 120)
                                monthVal = parseInt(dat/30); 
                                else
                                 monthVal = parseInt(dat/30); 
                                console.log("working daily");
                              }
                              else if(document.getElementById("headertype").value == "quater")
                              {
                                monthVal = parseInt(dat * 3); 
                                console.log("working quater");
                              }
                              else if(document.getElementById("headertype").value == "half-year")
                              {
                                monthVal = parseInt(dat*6); 
                                console.log("working half");
                              }
                              else if(document.getElementById("headertype").value == "annualy")
                              {
                                monthVal = parseInt(dat*12); 
                                console.log("working ann");
                              }
                              else if(document.getElementById("headertype").value == "weekly")
                              {
                                //   if(dat > 120)
                                // monthVal = parseInt(dat/4);
                                // else
                                // monthVal = parseInt(dat*4);
                                
                                 monthVal = parseInt(dat/4);
                                console.log("working week");
                              }
                              else if(document.getElementById("headertype").value == "fortnight")
                              {
                                if(dat > 120)
                                monthVal = parseInt(dat/2);
                                else
                                monthVal = parseInt(dat*2);
                                console.log("working fort");
                              }
                              
                              dat = document.getElementById("installments");
                              dat = parseInt(dat.value);
                              if(document.getElementById("headertype").value == "monthly")
                              {
                                  yearsVal = parseInt((dat/12)+1);
                                  console.log("working monthly1");
                              }
                              else if(document.getElementById("headertype").value == "daily")
                              {
                                yearsVal = parseInt((dat/365)+1); 
                                console.log("working daily1");
                              }
                              else if(document.getElementById("headertype").value == "quater")
                              {
                                yearsVal = parseInt((dat/4)+1); 
                                console.log("working quater1");
                              }
                              else if(document.getElementById("headertype").value == "half-year")
                              {
                                yearsVal = parseInt((dat/2)+1);
                                console.log("working half1");
                              }
                              else if(document.getElementById("headertype").value == "annualy")
                              {
                                yearsVal = parseInt((dat/1)+1); 
                                console.log("working ann1");
                              }
                              else if(document.getElementById("headertype").value == "weekly")
                              {
                                yearsVal = parseInt((dat/52)+1); 
                                console.log("working week1");
                              }
                              else if(document.getElementById("headertype").value == "fortnight")
                              {
                                yearsVal = parseInt((dat/(52/2))+1);
                                console.log("working fort1");
                              }
                              
                              var y = parseInt(document.getElementById("years").value);
                              var m = parseInt(document.getElementById("months").value);
                              
                              if(y> 10 || m > 120 || document.getElementById("headertype").value == "quater" ||document.getElementById("headertype").value == "half-year" || document.getElementById("headertype").value == "annualy")
                              document.getElementById("annreport").checked = true;
                              
                              console.log(yearsVal + " "+monthVal);
        },100);
        
        function getFixed()
        {
            if(isNaN(fixedAmtVal))
            {
                fixedAmtVal = 0;
            }
            document.getElementById("fixedtext").innerHTML = "Amount should be less than ₹"+fixedAmtVal;
        }
        
        function getFixedPercent()
        {
            if(isNaN(fixedPercentVal))
            {
                fixedPercentVal = 0;
            }
            document.getElementById("fixedpertext").innerHTML = "value should be less than "+(fixedPercentVal * 100) +"%";
        }
        function getYrs()
        {
            if(isNaN(yearsVal))
            {
                yearsVal = 0;
            }
            document.getElementById("yrs").innerHTML = "Years can not be less than "+yearsVal;
        }
        function getMnths()
        {
            if(isNaN(monthVal))
            {
                monthVal = 0;
            }
            document.getElementById("mnth").innerHTML = "Months can not be less than "+monthVal;
        }

var tenure = 1;
    function activateMe(val)
    {
        tenure = val;
        var yrs = document.getElementById("years");
        var mnths = document.getElementById("months");
        if (val == 1)
        {
            mnths.value = "";
            yrs.removeAttribute('readonly');
            mnths.setAttribute('readonly',true);
            document.getElementById("annreport").checked = true;
        }
        else
        {
            yrs.value = "";
            mnths.removeAttribute('readonly');
            yrs.setAttribute('readonly',true);
            document.getElementById("monthreport").checked = true;
        }
    }
    
    var selectReport = window.setInterval(function(){
        if(tenure == 1)
        {
            document.getElementById("annreport").checked = true;
        }
        else
        {
            if(document.getElementById("headertype").value != "quater" && document.getElementById("headertype").value != "half-year" && document.getElementById("headertype").value != "annualy")
                document.getElementById("monthreport").checked = true;
            else
                document.getElementById("annreport").checked = true;
        }
        
        if(tenure == 2)
        {
            var mVal = parseInt(document.getElementById("months").value);
            
            if(!isNaN(mVal) && mVal > 0){
            if(document.getElementById("headertype").value == "quater")
            {
                if(mVal % 3 != 0)
                {
                    document.getElementById("montherr").innerHTML = "Month must be divisible by 3 as Quaterly transfer is selected";
                }
                else
                     document.getElementById("montherr").innerHTML = "";
            }
            else if(document.getElementById("headertype").value == "half-year")
            {
                if(mVal % 6 != 0)
                {
                    document.getElementById("montherr").innerHTML = "Month must be divisible by 6 as Half Yearly transfer is selected";
                }
                else
                     document.getElementById("montherr").innerHTML = "";
            }
            else if(document.getElementById("headertype").value == "annualy")
            {
                if(mVal % 12 != 0)
                {
                    document.getElementById("montherr").innerHTML = "Month must be divisible by 12 as Annual transfer is selected";
                }
                else
                     document.getElementById("montherr").innerHTML = "";
            }
            else
            {
                 document.getElementById("montherr").innerHTML = "";
            }
            }
        }
        
    },100);
    
    var showEach = 1;
    var acEach = 1;
    function ShowEach(val)
    {
        showEach = val;
        if(val == 2)
        {
            document.getElementById("each").style.display = "block";
        }
        else
        {
            document.getElementById("each").style.display = "none";
        }
        
        ChangeFrequency(val);
    }
    
    function activateEach(val)
    {
        acEach = val;
         var yrs = document.getElementById("fixed");
        var mnths = document.getElementById("fixedper");
        if (val == 1)
        {
            mnths.value = "";
            yrs.removeAttribute('readonly');
            mnths.setAttribute('readonly',true);
        }
        else
        {
            yrs.value = "";
            mnths.removeAttribute('readonly');
            yrs.setAttribute('readonly',true);
        }
    }
    
    function ChangeFrequency(val)
    {
        var frequency = document.getElementById("headertype");
        frequency.innerHTML = "";
        if(val == 1)
       {
           var option = document.createElement("option");
        option.text = "Monthly";
        option.value = "monthly";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Quarterly";
        option.value = "quater";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Half-Yearly";
        option.value = "half-year";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Yearly";
        option.value = "annualy";
        frequency.add(option);
       }
       
       if(val == 2)
       {
           var option = document.createElement("option");
        option.text = "Daily";
        option.value = "daily";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Weekly";
        option.value = "weekly";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Fortnightly";
        option.value = "fortnight";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Monthly";
        option.value = "monthly";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Quarterly";
        option.value = "quater";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Half-Yearly";
        option.value = "half-year";
        frequency.add(option);
        option = document.createElement("option");
        option.text = "Yearly";
        option.value = "annualy";
        frequency.add(option);
       }
       
    }
</script>

<link rel="stylesheet" href="{{ asset('') }}/f/css/calculator.css">
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
                    <h3 class="smalllineHeading">{{$details->name}}</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form id="sip" enctype="multipart/form-data" method="post" class="js-validate-form" action="{{route('frontend.stp_custom_transfer_output')}}" name="recover_emis_through_sip_form">
                      <div class="card sip-calculator singleLineHolder calculatorFormShape">
                          @csrf
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label" id="initial">Initial Investment</label>
                              <div class="col-sm-7">
                                  <input type="text" id="ini" name="initial" class="form-control" value="">
                                  <div class="cal-icon">
                                      ₹
                                  </div>
                              </div>
                          </div>
                          <div class="form-group row">
                            <div class="col-sm-12">
                                <h6 class="text-muted titleBlueUnderline">Assumed Rate Of Return:</h6>
                            </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label" id="debtfund">Debt Fund</label>
                              <div class="col-sm-7">
                                  <input type="text" id="debt" name="debt" class="form-control" value="" oninput="validate(this)">
                                  <div class="cal-icon">
                                      %
                                  </div>
                              </div>
                          </div>
                          
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label" id="equityfund">Equity Fund</label>
                              <div class="col-sm-7">
                                  <input type="text" id="equity" name="equity" class="form-control" value="" oninput="validate(this)">
                                  <div class="cal-icon">
                                      %
                                  </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label">Transfer Mode</label>
                              <div class="col-sm-7">
                                  <div class="form-check form-check-inline">
                                      <label class="checkLinecontainer mb-0 mt-1" for="ShowEach_1">Capital Appreciation
                                            <input class="form-check-input" type="radio" id="ShowEach_1" name="transfermode" onchange="ShowEach(1)"  checked value=1>
                                            <span class="checkmark"></span>
                                      </label>
                                  </div>
                                  <div class="form-check form-check-inline">
                                      <label class="checkLinecontainer mb-0 mt-1" for="ShowEach_2">Fixed Amount
                                            <input class="form-check-input" type="radio" id="ShowEach_2" name="transfermode" value=2  onchange="ShowEach(2)" >
                                            <span class="checkmark"></span>
                                      </label>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label">Transfer Frequency
                              </label>
                              <div class="col-sm-7">
                                  
                                          
                                            
                                      <select name="transferfrequency" class="form-control" id="headertype">
                                       
                                       <option value="monthly" selected>Monthly</option>
                                       <option value="quater">Quarterly</option>
                                       <option value="half-year">Half-Yearly</option>
                                       <option value="annualy">Yearly</option>
                                   </select>
                                   
                                    
                                  </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-5 col-form-label" id="equityfund">No. of Instalments</label>
                              <div class="col-sm-7">
                                  <input type="text" id="installments" name="installments" class="form-control" value="">
                                  <div class="cal-icon">
                                      
                                  </div>
                              </div>
                          </div>
                          <!-- each transfer -->
                          <div id="each" style="display:none;">
                              <div class="form-group row">
                                      <label class="col-sm-5 col-form-label">Each Transfer Amount:</label>
                                  </div>
                          <div class="form-group row">
                                <div class="col-sm-5">
                                    <label class="checkLinecontainer mb-0 mt-2"><span>Fixed Amount</span>
                                        <input class="form-check-input fixed_deposit_chk"  onchange="activateEach(1)" type="radio" name="eachtransfer" class="form-control" value=1 checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                              <div class="col-sm-7">
                                  <div class="d-flex align-items-center">
                                      <input type="text" name="fixedamount" id="fixed" class="form-control" value="" onfocus="getFixed()" onfocusout="document.getElementById('fixedtext').innerHTML = '' ">
                                  </div>
                                  <div class="cal-icon">
                                      ₹
                                  </div>
                                  <em class="error" style="color: #343434;" id="fixedtext"></em>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-sm-5">
                                    <label class="checkLinecontainer mb-0 mt-2"><span>Fixed %</span>
                                        <input class="form-check-input fixed_deposit_chk"  onchange="activateEach(2)" type="radio" name="eachtransfer" class="form-control" value=2>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                              <div class="col-sm-7">
                                  <div class="d-flex align-items-center">
                                      <input type="text" name="fixedpercent" id="fixedper" onfocus="getFixedPercent()" class="form-control" value="" onfocusout="document.getElementById('fixedpertext').innerHTML = '' " readonly>
                                  </div>
                                  <div class="cal-icon">
                                      %
                                  </div>
                                  <em class="error" style="color: #343434;" id="fixedpertext"></em>
                              </div>
                          </div>
                          </div>
                          <!-- end of transfer -->
                          <div class="form-group row">
                                      <label class="col-sm-5 col-form-label">Total Investment Period:</label>
                                  </div>
                          <div class="form-group row">
                              <div class="col-sm-5">
                                    <label class="checkLinecontainer mb-0 mt-2"><span>Years</span>
                                        <input class="form-check-input fixed_deposit_chk"  onchange="activateMe(1)" type="radio" name="investmentperiod" class="form-control" value=1 checked>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                              <div class="col-sm-7">
                                  <div class="d-flex align-items-center">
                                      <input type="text" name="years" id="years" class="form-control" value="" onfocus="getYrs()" onfocusout="document.getElementById('yrs').innerHTML = '' ">
                                  </div>
                                  <div class="cal-icon">
                                      Yrs
                                  </div>
                                  <em class="error" style="color: #343434;" id="yrs"></em>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-sm-5">
                                    <label class="checkLinecontainer mb-0 mt-2"><span>Months</span>
                                        <input class="form-check-input fixed_deposit_chk"  onchange="activateMe(2)" type="radio" name="investmentperiod" class="form-control" value=2>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                              <div class="col-sm-7">
                                  <div class="d-flex align-items-center">
                                      <input type="text" name="months" id="months" class="form-control" value="" onfocus="getMnths()" onfocusout="document.getElementById('mnth').innerHTML = '' " readonly>
                                      
                                  </div>
                                  <div class="cal-icon">
                                      Mth
                                  </div>
                                  <em id="montherr"></em>
                                  <em class="error" style="color: #343434;" id="mnth"></em>
                              </div>
                          </div>
                        </div>

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
                            
                            <div class="form-group row"  style="align-items: flex-start;">
                                <div class="col-sm-5">
                                    <label class="sqarecontainer">Add Comments (If any)
                                        <input id="is_note" type="checkbox" name="is_note" value="1"> 
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
                            
                            <div class="form-group row">
                                <div class="col-sm-5 openEye">
                                    <label class="col-form-label">Get Report In Format  &nbsp; 
                                      <span><i class="fa fa-eye"></i><span>Monthly Report is available only if the Transfer Frequency is Monthly or less, and Investment Period is less than or equal to 120 months.</span></span>
                                    </label>
                                </div>
                                    
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Monthly Report
                                            <input class="form-check-input" type="radio" name="reportcat" id="monthreport" value="1" @if(old('reportcat')=='1') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Annual Report
                                            <input class="form-check-input" type="radio" name="reportcat" id="annreport" value="2" @if(old('reportcat')=='2')  @else checked  @endif >
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
                                            
                                            <a href="{{route('frontend.stp_custom_transfer')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
                                        
                                            <button class="btn banner-btn mx-3">Calculate</button>
                                       
                                    </div>

                                </div>
                            </div>
                        
                        </div>
                    </form>
                </div>
            </div>
        </div>               
     </section>
    @endsection