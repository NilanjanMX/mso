@extends('layouts.frontend')

@section('js_after')
  
    @php
        
        $data['suggest'] = old('suggest');
        if(!$data['suggest']){
            $data['suggest'] = $suggest;
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

    <script>
      var invType = {{$investmentmode}};
       
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
                         range:[100,9999999999],
                         digits:true,
                     },
                     invperiod:{
                         required:function()
                         {
                             if(invType > 1)
                             {
                                 return true;
                                 
                             }
                             return false;
                         },
                         range:[1,99],
                         digits:true
                     },
                     swp:{
                         required:true,
                         range:[1,99],
                         digits:true
                     },
                     defermentperiod:{
                         required:function()
                         {
                             var defi =parseInt(document.getElementById('defer').value);
                             if(defi == 0)
                             return false;
                             else
                             return true;
                         },
                         range:[1,90],
                         digits:true
                     },
                     sipamt:{
                         required:function()
                         {
                             if(invType == 4)
                             {
                                 return true;
                             }
                             return false;
                         },
                         digits:true,
                         range:[100,9999999999]
                     },
                     currentage:{
                         required:false,
                         range:[0,100],
                         digits:true
                     },
                     debt:{
                         required:false,
                         range:[0.00,100.00],
                         digits:false
                         
                     },
                     hybrid:{
                         required:false,
                         range:[0.00,100.00],
                         digits:false
                     },
                     equity:{
                         required:false,
                         range:[0.00,100.00],
                         digits:false
                     },
                     debt1:{
                         required:false,
                         range:[0.00,8.00],
                         digits:false
                     },
                     hybrid1:{
                         required:false,
                         range:[0.00,12.00],
                         digits:false
                     },
                     equity1:{
                         required:false,
                         range:[0,15],
                         digits:false
                     },
                     total:{
                         required:true,
                         max:100,
                         min:100,
                         digits:false
                     },
                     total1:{
                         required:true,
                         max:100,
                         min:1,
                         digits:false
                     },
                     acdebt:{
                         required:false,
                         range:[0,100],
                         digits:false
                     },
                     achybrid:{
                         required:false,
                         range:[0,100],
                         digits:false
                     },
                     acequity:{
                         required:false,
                         range:[0,100],
                         digits:false
                     },
                     acdebt1:{
                         required:false,
                         range:[0,8],
                         digits:false
                     },
                     achybrid1:{
                         required:false,
                         range:[0,12],
                         digits:false
                     },
                     acequity1:{
                         required:false,
                         range:[0,15],
                         digits:false
                     },
                     actotal:{
                         required:true,
                         max:100,
                         min:100,
                         digits:false
                     },
                     actotal1:{
                         required:true,
                         range:[0,100],
                         digits:false
                     },
                     swpamount:{
                         required:true,
                         max:function()
                         {
                             
                             return retVal;
                    },
                         min:1,
                         digits:true,
                     },
                     mamount:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:true,
                         max:function()
                         {
                             return retVal;
                         }
                     },
                     qamount:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:true,
                         max:function()
                         {
                             return retVal;
                         }
                     },
                     hamount:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:true,
                         max:function()
                         {
                             return retVal;
                         }
                     },
                     yamount:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:true,
                         max:function()
                         {
                             return retVal;
                         }
                     },
                     mpercent:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:false,
                         max:function()
                         {
                             return tlems;
                         }
                     },
                     qpercent:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:false,
                         max:function()
                         {
                             return tlems;
                         }
                     },
                     hpercent:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:false,
                         max:function()
                         {
                             return tlems;
                         }
                     },
                     ypercent:{
                         
                         required:function()
                         {
                             
                              return false;
                         },
                         digits:false,
                         max:function()
                         {
                             return tlems;
                         }
                     },
              },
              messages: {
                  
                  initial: "Please enter a value between 100-9999999999",
                  currentage:"Please enter a value between 0-99",
                  invperiod:"Please enter a value between 1-99",
                  swp:"Please enter a value between 1-99",
                  defermentperiod:"Please enter a value between 1-90",
                  sipamt:"Please enter a value between 100-9999999999",
                  debt:"Please enter a value between 0-100",
                  equity:"Please enter a value between 0-100",
                  hybrid:"Please enter a value between 0-100",
                  debt1:"Please enter a value between 0-8",
                  equity1:"Please enter a value between 0-15",
                  hybrid1:"Please enter a value between 0-12",
                  acequity:"Please enter a value between 0-100",
                  achybrid:"Please enter a value between 0-100",
                  acdebt1:"Please enter a value between 0-8",
                  acequity1:"Please enter a value between 0-15",
                  achybrid1:"Please enter a value between 0-12",
                  actotal:"Total 100",
                  actotal1:"",
                  total1:"",
                  total:"Total 100"
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
                  
              },
              messages: {
                  initial : "Please enter a value between 100-99999999",
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
      
      $("#monthly").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#quaterly").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#half-year").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#annualy").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#monthly1").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#quaterly1").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#half-year1").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });
      $("#annualy1").on('keydown',function(e){
          if(e.keyCode == 9)
          {
              e.preventDefault();
          }
      });

      var global_type = 1;
      var retVal = 0;
      
      
      function SetSwp(val)
      {
          document.getElementById("amountsec").style.display = 'none';
          document.getElementById("percentsec").style.display = 'none';
          if(val == 1){
              document.getElementById("swptype").value = "inmonth";
              document.getElementById("amountsec").style.display = 'inline-block';
          }else{
              document.getElementById("swptype").value = "inpercent";
              document.getElementById("percentsec").style.display = 'inline-block';
          }
      }
      var selected = "";
      function SetSwpValue(val){
          selected=val;
          
          document.getElementById("monthly").setAttribute('readonly',true);
          document.getElementById("quaterly").setAttribute('readonly',true);
          document.getElementById("half-year").setAttribute('readonly',true);
          document.getElementById("annualy").setAttribute('readonly',true);
          
          document.getElementById("monthly1").setAttribute('readonly',true);
          document.getElementById("quaterly1").setAttribute('readonly',true);
          document.getElementById("half-year1").setAttribute('readonly',true);
          document.getElementById("annualy1").setAttribute('readonly',true);
          
          if(document.getElementById("swptype").value == "inmonth"){
              if(val == 1){
                  setTimeout( function() { document.getElementById("monthly").removeAttribute('readonly'); }, 2000);
              }
              if(val == 2){
                  setTimeout( function() { document.getElementById("quaterly").removeAttribute('readonly'); }, 2000);
              }
              if(val == 3){
                  setTimeout( function() { document.getElementById("half-year").removeAttribute('readonly'); }, 2000);
              }
              if(val == 4){
                  setTimeout( function() { document.getElementById("annualy").removeAttribute('readonly'); }, 2000);
              }
          }
          
          if(document.getElementById("swptype").value == "inpercent"){
              if(val == 1){
                  setTimeout( function() { document.getElementById("monthly1").removeAttribute('readonly'); }, 2000);
              }
              if(val == 2){
                  setTimeout( function() { document.getElementById("quaterly1").removeAttribute('readonly'); }, 2000);
              }
              if(val == 3){
                  setTimeout( function() { document.getElementById("half-year1").removeAttribute('readonly'); }, 2000);
              }
              if(val == 4){
                  setTimeout( function() { document.getElementById("annualy1").removeAttribute('readonly'); }, 2000);
              }
          }
      }
      
      var ssam = window.setInterval(function(){
          if(selected == 1)
          {
              document.getElementById("swpamount").value = parseInt(document.getElementById("monthly").value);
              if(document.getElementById("swptype").value == "inpercent")
              {
                  document.getElementById("swpamount").value = amountProjectiled;
              }
              document.getElementById("withdrawtype").value = "month";
          }
          if(selected == 2)
          {
              document.getElementById("swpamount").value = parseInt(document.getElementById("quaterly").value);
              if(document.getElementById("swptype").value == "inpercent")
              {
                  document.getElementById("swpamount").value = amountProjectiled;
              }
              document.getElementById("withdrawtype").value = "quater";
          }
          if(selected == 3)
          {
              document.getElementById("swpamount").value = parseInt(document.getElementById("half-year").value);
              if(document.getElementById("swptype").value == "inpercent")
              {
                  document.getElementById("swpamount").value = amountProjectiled;
              }
              document.getElementById("withdrawtype").value = "half";
          }
          if(selected == 4)
          {
              document.getElementById("swpamount").value = parseInt(document.getElementById("annualy").value);
              if(document.getElementById("swptype").value == "inpercent")
              {
                  document.getElementById("swpamount").value = amountProjectiled;
              }
              document.getElementById("withdrawtype").value = "year";
          }
          //console.log("swp amt "+document.getElementById("swpamount").value + " swp type "+document.getElementById("swptype").value);
      },100);
      
      
      var allAmount = 0;
      var tlems = 0;
        
      var csam = window.setInterval(function(){
            var swptp = document.getElementById("swptype").value;
            var form = $("#emi");
            var url = "{{url('')}}/premium-calculator/swp_check";
            $.ajax({
                type: "POST",
                url: url,
                crossDomain : true,
                data: form.serialize(),
                success: function(dat) {
                    var responseDat = dat.split('!');
                    if(swptp == "inmonth")
                      retVal = parseInt(responseDat[0]) ;
                    else{
                      retVal = parseFloat(responseDat[1]);
                      tlems = retVal;
                      retVal = retVal.toFixed(4);
                    }
                    
                    allAmount = parseInt(responseDat[0]) ;
                    // console.log("retting:"+retVal);
                },
                error: function(dat) {

                }
            });
            // console.log("retval:"+retVal);
            // console.log("withdrawtype "+document.getElementById("withdrawtype").value);
            // console.log("swpamount "+document.getElementById("swpamount").value);
            var defi =parseInt(document.getElementById('defer').value);
            
            if(defi == 0){
              document.getElementById('defval').setAttribute('readonly',true);
            }else{
              document.getElementById('defval').removeAttribute('readonly');
            }
                      
      },1000);
      
      var amountProjectiled = 0;
      
      function ShowAmount(){
            console.log(selected);
          document.getElementById('monthlyamt1').value = "";
          document.getElementById('quaterlyamt1').value = "";
          document.getElementById('half-yearamt1').value = "";
          document.getElementById('annualyamt1').value = "";
          
          console.log("swp type "+document.getElementById("swptype").value);
          $init = document.getElementById("initial").value;
          if(defermentApplied || invType > 1){
              $init = document.getElementById("accval").value;
          }
          setTimeout( function() {
            if(selected == 1){
                
                allAmount = $init*((document.getElementById('monthly1').value/100) / 12);
                amountProjectiled = allAmount;
                 document.getElementById('monthlyamt1').value = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(allAmount);
            }
            if(selected == 2){
                allAmount = $init*((document.getElementById('quaterly1').value/100) / 4);
                amountProjectiled = allAmount;
                 document.getElementById('quaterlyamt1').value = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(allAmount);
            }
            if(selected == 3){
                allAmount = $init*((document.getElementById('half-year1').value/100) / 2);
                amountProjectiled = allAmount;
                 document.getElementById('half-yearamt1').value = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(allAmount);
            }
            if(selected == 4){
                allAmount = $init*((document.getElementById('annualy1').value/100) / 1);
                amountProjectiled = allAmount;
                 document.getElementById('annualyamt1').value = new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(allAmount);
            }
          },2000);
      }
      
      function GetMaxVal(val)
      {
            document.getElementById('monthly').value = "";
            document.getElementById('quaterly').value = "";
            document.getElementById('half-year').value = "";
            document.getElementById('annualy').value = "";
            document.getElementById('monthly1').value = "";
            document.getElementById('quaterly1').value = "";
            document.getElementById('half-year1').value = "";
            document.getElementById('annualy1').value = "";
            
            document.getElementById('monthlyamt1').value = "";
            document.getElementById('quaterlyamt1').value = "";
            document.getElementById('half-yearamt1').value = "";
            document.getElementById('annualyamt1').value = "";
            if(val.hasAttribute('readonly')){
             return;
            }else{
              if(document.getElementById("swptype").value == "inpercent"){
                  console.log("In percent got");
                  
                  var jj = window.setTimeout(function(){
                      val.innerHTML = "Max percent "+retVal + "%";
                  },2000);
              }else{
                  var kk = window.setTimeout(function(){
                      val.innerHTML = "Max amount "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(retVal);
                  },2000);
              }
            }
      }
      
      function TurnOnSip(dat)
      {
            invType = dat;
            var invperiodtext = document.getElementById('invtypeperiod');
            var invtypetext = document.getElementById('invtypetext');
            var area = document.getElementById('acc');
            var invPeriod = document.getElementById('invper');
            var sipamt = document.getElementById('sipamt');
            if(dat == 2 || dat == 3 || dat == 4)
            {
                area.style.display = 'block';
                invPeriod.style.display = 'block';
                if(dat == 4){
                    sipamt.style.display = 'block';
                }
                if(dat == 3){
                    sipamt.style.display = 'none';
                }
                if(dat == 2){
                    sipamt.style.display = 'none';
                }
            }else if(dat == 1){
                area.style.display = 'none';
                invPeriod.style.display = 'none';
                sipamt.style.display = 'none';
            }else{
                area.style.display = 'none';
                invPeriod.style.display = 'none';
                sipamt.style.display = 'none';
            }
            
            if(dat == 1){
              invtypetext.innerHTML = "Initial Investment";
            }else if(dat == 2){
              invtypetext.innerHTML = "Annual Investment";
              invperiodtext.innerHTML = "Investment Period";
            }else if(dat == 3){
              invtypetext.innerHTML = "SIP Amount";
              invperiodtext.innerHTML = "SIP Period";
            }else if(dat == 4){
              invtypetext.innerHTML = "Lumpsum Investment";
              invperiodtext.innerHTML = "SIP Period";
            }
      }
      
      function SetTotal()
      {
            var total = document.getElementById('total') ;
            var equity = parseFloat(document.getElementById('equity').value);
            var debt =parseFloat(document.getElementById('debt').value);
            var hybrid = parseFloat(document.getElementById('hybrid').value);
            
            var total1 = document.getElementById('total1') ;
            var equity1 = parseFloat(document.getElementById('equity1').value);
            var debt1 =parseFloat(document.getElementById('debt1').value);
            var hybrid1 = parseFloat(document.getElementById('hybrid1').value);
            
            if(isNaN(equity))
              equity = 0;
            if(isNaN(debt))
              debt = 0;
            if(isNaN(hybrid))
              hybrid = 0;
            
            if(isNaN(equity1))
              equity1 = 0;
            if(isNaN(debt1))
              debt1 = 0;
            if(isNaN(hybrid1))
              hybrid1 = 0;
            
            var tot = equity + debt + hybrid;
            
            var tot1 = (equity/100*equity1) + (debt/100*debt1) + (hybrid/100*hybrid1);
            total.value = tot;
            
            if(tot != 100){
                document.getElementById("t2").innerHTML = "Total 100";
            }else{
                document.getElementById("t2").innerHTML = "";
            }
            total1.value = tot1;
      }
      
      function SetAcTotal()
      {
            var total = document.getElementById('actotal') ;
            var equity = parseFloat(document.getElementById('acequity').value);
            var debt =parseFloat(document.getElementById('acdebt').value);
            var hybrid = parseFloat(document.getElementById('achybrid').value);
            
            var total1 = document.getElementById('actotal1') ;
            var equity1 = parseFloat(document.getElementById('acequity1').value);
            var debt1 =parseFloat(document.getElementById('acdebt1').value);
            var hybrid1 = parseFloat(document.getElementById('achybrid1').value);
            
            if(isNaN(equity))
            equity = 0;
            if(isNaN(debt))
            debt = 0;
            if(isNaN(hybrid))
            hybrid = 0;
            
            if(isNaN(equity1))
            equity1 = 0;
            if(isNaN(debt1))
            debt1 = 0;
            if(isNaN(hybrid1))
            hybrid1 = 0;
            
            var tot = equity + debt + hybrid;
            
            var tot1 = (equity/100*equity1) + (debt/100*debt1) + (hybrid/100*hybrid1);
            
            total.value = tot;
            if(tot != 100){
                document.getElementById("t1").innerHTML = "Total 100";
            }else{
                document.getElementById("t1").innerHTML = "";
            }
            total1.value = tot1;
      }

      var count = 1;
      var incrType = 0;
      function enableDisble(val)
      {
         
      }
      function activateMe(val)
      {
          incrType = val;
          var f = document.getElementById("incrtype");
          var s = document.getElementById("incrtype1");
          
          if(val == 2){
            f.removeAttribute('readonly');
            s.setAttribute('readonly',true);
          }else{
              f.setAttribute('readonly',true);
              s.removeAttribute('readonly');
          }
          if(!allowIncr){
            f.setAttribute('readonly',true);
            s.setAttribute('readonly',true);
          }
      }

      
      
      function TurnOnOption(val)
      {
          
      }
        
      var assetAllocationActivation = window.setInterval(function(){
           if(document.getElementById('equity').value == '' || document.getElementById('equity').value == null)
           {
                document.getElementById('equity1').value = "";
               document.getElementById('equity1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('equity1').removeAttribute("readonly");
           }
           
           if(document.getElementById('debt').value == '' || document.getElementById('debt').value == null)
           {
               document.getElementById('debt1').value = "";
               document.getElementById('debt1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('debt1').removeAttribute("readonly");
           }
           
           if(document.getElementById('hybrid').value == '' || document.getElementById('hybrid').value == null)
           {
               document.getElementById('hybrid1').value = "";
               document.getElementById('hybrid1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('hybrid1').removeAttribute("readonly");
           }
           
           if(document.getElementById('achybrid').value == '' || document.getElementById('achybrid').value == null)
           {
               document.getElementById('achybrid1').value = "";
               document.getElementById('achybrid1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('achybrid1').removeAttribute("readonly");
           }
           
           if(document.getElementById('acdebt').value == '' || document.getElementById('acdebt').value == null)
           {
               document.getElementById('acdebt1').value = "";
               document.getElementById('acdebt1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('acdebt1').removeAttribute("readonly");
           }
           
           if(document.getElementById('acequity').value == '' || document.getElementById('acequity').value == null)
           {
                document.getElementById('acequity1').value = "";
               document.getElementById('acequity1').setAttribute("readonly",true);
           }
           else
           {
               document.getElementById('acequity1').removeAttribute("readonly");
           }
           
           
           var customHeader = document.getElementById('headertype').value;
           var aft = document.getElementById('aft');
           if(customHeader == 'no')
           {
               aft.value = '';
               aft.setAttribute('readonly',true);
           }
           else
           {
               aft.removeAttribute('readonly');
           }
      },1000);
        
      var intervalId = window.setInterval(function(){
          
        accumulatedcalc();
      }, 1000);

      function accumulatedcalc()
      {
          
          var def = parseInt(document.getElementById("defval").value);
          var init = parseInt(document.getElementById("initial").value);
          var sipamt = parseInt(document.getElementById("sipamti").value);
          var total = parseFloat(document.getElementById("actotal1").value);
          var invper = parseInt(document.getElementById("invperi").value);
          var acc = document.getElementById("accval");
          var totalVal = 0;
          if(invType == 1){
              totalVal =Math.round(init * Math.pow((1+total/100),def));
          }
          else if(invType == 2)
          {
              var defi =parseInt(document.getElementById('defer').value);
              if(defi == 0){
              totalVal =Math.round(init * (1+total/100) * (Math.pow((1+total/100),invper)-1)/(total/100));
                  console.log(invper);
              }
              if(defi == 1){
              totalVal =Math.round((init * (1+total/100) * (Math.pow((1+total/100),invper)-1)/(total/100))*Math.pow((1+total/100),def));
                  console.log("s");
              }
              
              
          }
          else if(invType == 3 || invType == 4)
          {
              
              var sipReturn =Math.pow((1+total/100),(1/12))-1;
              var sipPeriod = invper * 12;
              
              var defi =parseInt(document.getElementById('defer').value);
              if(defi == 0){
                  if(invType == 3)
                  totalVal = Math.round(init * (1+sipReturn) * (Math.pow((1+sipReturn),sipPeriod)-1)/(sipReturn));
                  else
                  totalVal = Math.round((init * Math.pow((1+total/100),invper))+(sipamt * (1+sipReturn) * (Math.pow((1+sipReturn),sipPeriod)-1)/(sipReturn)));
              }
              else
              {
                  if(invType == 3){
                  totalVal = Math.round((init * (1+sipReturn) * (Math.pow((1+sipReturn),sipPeriod)-1)/(sipReturn)) * Math.pow((1+total/100),def));
                  console.log("f" + " "+totalVal);
                  }
                  else{
                  totalVal = Math.round((init * Math.pow((1+total/100),(invper + def)))+(sipamt * (1+sipReturn) * (Math.pow((1+sipReturn),sipPeriod)-1)/(sipReturn))*Math.pow((1+total/100),def));
                  console.log("s" + " "+totalVal);
                  }
              }
              
          }
          if(isNaN(totalVal))
          totalVal = 0;
          acc.value = totalVal;
      }

      function checkTotalDiffNew()
      {
          
      }


      var defermentApplied = false;
      
      function showAc(val)
      {
          var area = document.getElementById('acc');
          var def = document.getElementById('defer');
          
          if(val == 1)
          {
              defermentApplied = false;
              area.style.display = "none";
              def.value = 0;
          }
          else
          {
              defermentApplied = true;
              area.style.display = "block";
              def.value = 1;
          }
      }
      
      function changeIcon(val)
      {
          var calico = document.getElementById('calico');
          if(val.value == "inmonth")
          {
              calico.innerHTML = "₹";
          }
          else
          {
              calico.innerHTML = "%";
          }
      }
      
      function setInvType()
      {
          invType = 1;
      }
      var allowIncr = false;
      function changeIncr(type)
      {
          allowIncr = type;
          
          var f = document.getElementById("incrtype");
          var s = document.getElementById("incrtype1");
          
          var percentRadio    = document.getElementById("percentRadio");
          var amountRadio     = document.getElementById("percentAmount");
          
          if(f.checked == true)
          {
              f.removeAttribute("readonly");
          }
          if(s.checked == true)
          {
              s.removeAttribute("readonly");
          }
          
          if(type == false)
          {
              f.value = ""; s.value="";
              f.setAttribute("readonly",true);
              s.setAttribute("readonly",true);
              percentRadio.setAttribute('disabled',true);
              amountRadio.setAttribute('disabled',true);
          }
          else
          {
              console.log("seseme");
              percentRadio.removeAttribute('disabled');
             // amountRadio.removeAttribute('disabled');
              percentRadio.checked = false;
              amountRadio.checked = false;
          }
      }
        
        

    </script>
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

        @if(isset($sets))
            @if($sets == 1)
              SetSwpValue({{$cats}});
            @endif
    
            @if($sets == 2)
              SetSwpValue({{$pats}});
            @endif
        @endif
        
        changeNote();
        SetAcTotal();
    </script>
    
    
        @if($client==1)
            <script>
                $('input[name="clientname"]').prop("readonly", false);
            </script>
        @else
            <script>
                $('input[name="clientname"]').prop("readonly", true);
            </script>
        @endif
        
        @if($is_note==1)
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
                    <h3 class="smalllineHeading">{{$detail->name}}</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.swp_comprehension_output')}}" method="post" id="emi">
                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <input type="hidden" name="formType" value="3" />
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Custom Calculator Header</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-3">
                                          <div class="d-flex align-items-center">
                                            <select name="headertype" class="form-control" id="headertype">
                                               
                                               <option value="no" @if($headertype=='no') selected  @endif>No</option>
                                               <option value="yes" @if($headertype=='yes') selected  @endif>Yes</option>
                                           </select>
                                          </div>
                                        </div>
                                        <div class="col-sm-9">
                                            
                                            <div class="d-flex align-items-center">
                                                &nbsp;
                                                <input type="text" class="form-control" id="aft" name="details" value="{{$details}}" maxlength="30" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Age</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pr-2 mr-1 maxtwodigit " name="currentage" value="{{$currentage}}" required="" maxlength="2">
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Mode</label>
                                <div class="col-sm-7">
                                    <div class="form-check pl-0">
                                        <label class="checkLinecontainer" for="TurnOnSip_1">Lumpsum
                                            <input class="form-check-input fixed_deposit_chk" id="TurnOnSip_1" type="radio" name="investmentmode" onchange="TurnOnSip(1)" @if($investmentmode==1) checked  @endif value=1>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check pl-0">
                                        <label class="checkLinecontainer" for="TurnOnSip_2">Annual Lumpsum
                                            <input class="form-check-input fixed_deposit_chk" id="TurnOnSip_2" type="radio" name="investmentmode" value=2 @if($investmentmode==2) checked  @endif onchange="TurnOnSip(2)" >
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check pl-0">
                                        <label class="checkLinecontainer" for="TurnOnSip_3">SIP
                                            <input class="form-check-input fixed_deposit_chk" id="TurnOnSip_3" type="radio" name="investmentmode" value=3 @if($investmentmode==3) checked  @endif onchange="TurnOnSip(3)" >
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check pl-0">
                                        <label class="checkLinecontainer" for="TurnOnSip_4">Lumpsum+SIP
                                            <input class="form-check-input fixed_deposit_chk" id="TurnOnSip_4" type="radio" name="investmentmode" value=4 @if($investmentmode==4) checked  @endif onchange="TurnOnSip(4)" >
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label" id="invtypetext">
                                  @if($investmentmode==1)
                                    Initial
                                  @elseif($investmentmode==2)
                                    Annual
                                  @elseif($investmentmode==3)
                                    SIP
                                  @else
                                    Lumpsum
                                  @endif
                                 Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pr-2 mr-1" id="initial" name="initial" value="{{$initial}}" required="">
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            <div id="sipamt" style="display:<?php echo ($investmentmode == 4)?'block':'none';?>;">
                              <div class="form-group row">
                                  <label class="col-sm-5 col-form-label">SIP Amount</label>
                                  <div class="col-sm-7">
                                      <input type="text" class="form-control pr-2 mr-1 " id="sipamti" name="sipamt" value="{{$sipamt}}" required="">
                                      <div class="cal-icon">
                                          ₹
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div id="invper" style="display:<?php echo ($investmentmode != 1)?'block':'none';?>;">
                               <div class="form-group row" >
                                  <label class="col-sm-5 col-form-label" id="invtypeperiod">

                                  @if($investmentmode==1)
                                    Investment
                                  @elseif($investmentmode==2)
                                    Investment
                                  @elseif($investmentmode==3)
                                    SIP
                                  @else
                                    SIP
                                  @endif
                                   Period</label>
                                  <div class="col-sm-7">
                                      <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="invperi" name="invperiod" value="{{$invperiod}}" required="" maxlength="2">
                                      <div class="cal-icon">
                                          Yrs
                                      </div>
                                  </div>
                              </div>
                            </div>
                             
                            <input type="hidden" name="def" value="{{$def}}" id="defer"/>
                            <div class="form-group row">
                                <div class="col-sm-5 openEye">
                                    <label class="col-form-label">Deferment Period  &nbsp; 
                                        <span><i class="fa fa-eye"></i><span>If Deferment is chosen, withdrawal will start from the end of (Payment Period+Deferment Period)</span></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <div class="form-check form-check-inline">
                                                <label class="checkLinecontainer mb-0 mt-2" for="showAc_1">No
                                                    <input class="form-check-input fixed_deposit_chk" id="showAc_1" type="radio" name="deferment" onchange="showAc(1)" @if($deferment==1) checked  @endif value="1">
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <label class="checkLinecontainer mb-0 mt-2" for="showAc_2">Yes
                                                    <input class="form-check-input fixed_deposit_chk" id="showAc_2" type="radio" name="deferment" value="2" @if($deferment==2) checked  @endif onchange="showAc(2)" >
                                                    <span class="checkmark"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-7">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="defermentperiod" id="defval" onChange="accumulatedcalc()" class="form-control" value="{{$defermentperiod}}" @if($deferment==1) readonly  @endif>
                                            </div>
                                            <div class="cal-icon">
                                                Yrs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display:<?php echo ($investmentmode != 1)?'block':'none';?>;" id="acc">
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h6 class="text-muted titleBlueUnderline">Accumulation Phase:</h6>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Select Asset Allocation</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Debt %</label> &nbsp;
                                                
                                                <input type="text" name="acdebt" id="acdebt"  class="form-control" value="{{$acdebt}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Hybrid %</label> &nbsp;
                                                <input type="text" name="achybrid" id="achybrid" class="form-control" value="{{$achybrid}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Equity %</label> &nbsp;
                                                <input type="text" name="acequity" id="acequity" class="form-control" value="{{$acequity}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Total %</label> &nbsp;
                                                <input type="text" name="actotal" id="actotal" class="form-control" value="{{$acdebt}}" readonly>
                                                <em id="t1" class="error"></em>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Expected Rate of Return:</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Debt %</label> &nbsp;
                                                
                                                <input type="text" name="acdebt1" id="acdebt1" class="form-control" value="{{$acdebt1}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Hybrid %</label> &nbsp;
                                                <input type="text" name="achybrid1" id="achybrid1" class="form-control" value="{{$achybrid1}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Equity %</label> &nbsp;
                                                <input type="text" name="acequity1" id="acequity1" class="form-control" value="{{$acequity1}}" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Average %</label> &nbsp;
                                                <input type="text" name="actotal1" id="actotal1" class="form-control" value="{{$actotal1}}" readonly>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Accumulated Value</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-12">
                                              <div class="d-flex align-items-center">
                                        <input type="text" id="accval" class="form-control" name="accumulated" value="{{$accumulated}}" required="" readonly>
                                        
                                        
                                        </div>
                                        <div class="cal-icon" style="width:50px; right:16px;">
                                           ₹
                                        </div>
                                            </div>
                                            
                                        </div>
                                        </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h6 class="text-muted titleBlueUnderline">Distribution Phase:</h6>
                                </div>
                            </div>

                            <div class="form-group row">   
                                <label class="col-sm-5 col-form-label">Select Asset Allocation</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Debt %</label>
                                            
                                            <input type="text" name="debt" id="debt" class="form-control" value="{{$debt}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Hybrid %</label>
                                            <input type="text" name="hybrid" id="hybrid" class="form-control" value="{{$hybrid}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Equity %</label>
                                            <input type="text" name="equity" id="equity" class="form-control" value="{{$equity}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Total %</label>
                                            <input type="text" name="total" id="total" class="form-control" value="{{$total}}" readonly>
                                            <em id="t2" class="error"></em>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Assumed Rate of Return:</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Debt %</label>
                                            
                                            <input type="text" name="debt1" id="debt1" class="form-control" value="{{$debt1}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Hybrid %</label>
                                            <input type="text" name="hybrid1" id="hybrid1" class="form-control" value="{{$hybrid1}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Equity %</label>
                                            <input type="text" name="equity1" id="equity1" class="form-control" value="{{$equity1}}" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Average %</label>
                                            <input type="text" name="total1" id="total1" class="form-control" value="{{$total1}}" readonly>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label"><span>SWP Period</span></label>
                                <div class="col-sm-7">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="swp" id="swp" class="form-control" value="{{$swp}}">
                                    </div>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Include Annual Increment</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-check form-check-inline">
                                                    <label class="checkLinecontainer mb-0 mt-1" for="changeIncr_false">No
                                                        <input class="form-check-input fixed_deposit_chk" id="changeIncr_false" type="radio" name="annualincr" onchange="changeIncr(false)"  @if($annualincr==1) checked  @endif value="1">
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="checkLinecontainer mb-0 mt-1" for="changeIncr_true">Yes
                                                        <input class="form-check-input fixed_deposit_chk" id="changeIncr_true" type="radio" name="annualincr" value="2" @if($annualincr==2) checked  @endif onchange="changeIncr(true)" >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                            </div>
                                
                            <div class="form-group row">
                                <div class="col-sm-5">
                                    <label class="checkLinecontainer mb-0 mt-2"><span>In %</span>
                                        <input id="percentRadio" class="form-check-input fixed_deposit_chk"  onchange="activateMe(1)" type="radio" name="incrtype" class="form-control" value=0 @if(isset($incrtype) && $incrtype==0) checked @else disabled @endif >
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="inpercent" id="incrtype1" class="form-control" value="{{isset($inpercent)?$inpercent:''}}" @if(isset($incrtype) && $incrtype==0) @else readonly  @endif >
                                    </div>
                                    <div class="cal-icon">
                                        %
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-5 openEye">
                                    <label class="checkLinecontainer mb-0 mt-2">In Amount  &nbsp; 
                                        <input id="percentAmount" class="form-check-input fixed_deposit_chk"  onchange="activateMe(2)" @if(isset($incrtype) && $incrtype==1) checked @else disabled @endif type="radio" name="incrtype" class="form-control" value=1 >
                                        <span><i class="fa fa-eye"></i><span>This option is not available right now !</span></span>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                                <div class="col-sm-7">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="inamount" id="incrtype" class="form-control" value="{{isset($inamount)?$inamount:''}}" @if(isset($incrtype) && $incrtype==1) @else readonly  @endif>
                                    </div>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row" style="display:none;">
                                <label class="col-sm-5 col-form-label"><span>SWP Amount</span></label>
                                
                                <div class="col-sm-7">
                                    <div class="d-flex align-items-center">
                                     <input type="text" value="inmonth" id="swptype" name="swptype" value="{{$swptype}}"/>
                                     <input id="swpamount" type="hidden" name="swpamount" class="form-control" value="{{$swpamount}}">
                                    </div>
                                    <div id="calico" class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style = "display:none;">
                                    <label class="col-sm-5 col-form-label">Include Taxation</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-5">
                                                <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="taxation" onchange="TurnOnSip('sipInv')"  @if($taxation==0) checked  @endif value="0">
                                        <label class="form-check-label" for="inlineRadio1">No</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="taxation" value="1" @if($taxation==1) checked  @endif onchange="TurnOnSip('emiInv')" >
                                        <label class="form-check-label" for="inlineRadio2">Yes</label>
                                    </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Select Withdrawal Option:</label>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="checkLinecontainer mb-0 mt-2">In Amount:
                                        <input class="form-check-input" type="radio" name="sets" value="1" onchange="SetSwp(1)" @if(isset($sets) && $sets==1) checked  @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                
                            </div>
                            <div id="amountsec" style="display:<?php echo (isset($sets) && $sets == 1)?'block':'none';?>;">
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Monthly
                                            <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(1)" @if(isset($cats) && $cats==1) checked  @endif value="1">
                                            <span class="checkmark"></span>
                                          </label>
                                          <input id="monthly" type="text" name="mamount" class="form-control" value="{{$mamount}}" onfocus="GetMaxVal(document.getElementById('mnthamt'))" onfocusout = "document.getElementById('mnthamt').innerHTML = ''"  @if(isset($cats) && $cats!=1) readonly  @endif>
                                          <em id="mnthamt" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Quaterly
                                            <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(2)" @if(isset($cats) && $cats==2) checked  @endif value="2">
                                            <span class="checkmark"></span>
                                          </label>
                                          <input id="quaterly" type="text" name="qamount" class="form-control" value="{{$qamount}}" onfocus="GetMaxVal(document.getElementById('mnthqt'))" onfocusout = "document.getElementById('mnthqt').innerHTML = ''" @if(isset($cats) && $cats!=2) readonly  @endif>
                                          <em id="mnthqt" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2" style="white-space: nowrap;">Half-Yearly
                                            <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(3)" @if(isset($cats) && $cats==3) checked  @endif value="3">
                                            <span class="checkmark"></span>
                                          </label>
                                          <input id="half-year" type="text" name="hamount" class="form-control" value="{{$hamount}}" onfocus="GetMaxVal(document.getElementById('mnthhf'))" onfocusout = "document.getElementById('mnthhf').innerHTML = ''"  @if(isset($cats) && $cats!=3) readonly  @endif>
                                          <em id="mnthhf" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Yearly
                                            <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(4)" @if(isset($cats) && $cats==4) checked  @endif value="4">
                                            <span class="checkmark"></span>
                                          </label>
                                          <input id="annualy" type="text" name="yamount" class="form-control" value="{{$yamount}}" onfocus="GetMaxVal(document.getElementById('mnthyr'))" onfocusout = "document.getElementById('mnthyr').innerHTML = ''"  @if(isset($cats) && $cats!=4) readonly  @endif>
                                          <em id="mnthyr" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <input type="hidden" name="withdrawtype" id="withdrawtype" value="{{$withdrawtype}}">

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label class="checkLinecontainer mb-0 mt-2">In %: (Select the withdrawal mode and total annual withdrawal %)
                                        <input class="form-check-input" type="radio" name="sets" value="2"  onchange="SetSwp(2)" @if(isset($sets) && $sets==2) checked  @endif>
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                            <div id="percentsec" style="display:<?php echo (isset($sets) && $sets == 2)?'block':'none';?>;">
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Monthly
                                            <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(1)" value="1" @if(isset($pats) && $pats==1) checked  @endif>
                                            <span class="checkmark"></span>
                                          </label>
                                          <input type="text" name="mpercent" id="monthly1" class="form-control" value="{{$mpercent}}" onfocus="GetMaxVal(document.getElementById('mnthamt1'))" onfocusout = "document.getElementById('mnthamt1').innerHTML = ''" oninput="ShowAmount()"  @if(isset($pats) && $pats==1) readonly  @endif>
                                          <em id="mnthamt1" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Quaterly
                                            <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(2)" value="2" @if(isset($pats) && $pats==2) checked  @endif>
                                            <span class="checkmark"></span>
                                          </label>
                                          <input type="text" name="qpercent" id="quaterly1" class="form-control" value="{{$qpercent}}" onfocus="GetMaxVal(document.getElementById('mnthqt1'))" onfocusout = "document.getElementById('mnthqt1').innerHTML = ''" oninput="ShowAmount()"  @if(isset($pats) && $pats==2) readonly  @endif>
                                          <em id="mnthqt1" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2" style="white-space: nowrap;">Half-Yearly
                                            <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(3)" value="3" @if(isset($pats) && $pats==3) checked  @endif>
                                            <span class="checkmark"></span>
                                          </label>
                                          <input type="text" name="hpercent" id="half-year1" class="form-control" value="{{$hpercent}}" onfocus="GetMaxVal(document.getElementById('mnthhf1'))" onfocusout = "document.getElementById('mnthhf1').innerHTML = ''" oninput="ShowAmount()"  @if(isset($pats) && $pats==3) readonly  @endif>
                                          <em id="mnthhf1" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-items-center">
                                          <label class="checkLinecontainer mb-0 mr-2">Yearly
                                            <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(4)" value="4" @if(isset($pats) && $pats==4) checked  @endif>
                                            <span class="checkmark"></span>
                                          </label>
                                          <input type="text" name="ypercent" id="annualy1" class="form-control" value="{{$ypercent}}" onfocus="GetMaxVal(document.getElementById('mnthyr1'))" onfocusout = "document.getElementById('mnthyr1').innerHTML = ''" oninput="ShowAmount()"  @if(isset($pats) && $pats==4) readonly  @endif>
                                          <em id="mnthyr1" class="error maxClass" style="color: #343434;font-size: 12px;">  </em>
                                      </div>
                                  </div>
                              </div>
                              
                              <!-- Just amount view starts -->
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="monthlyamt1" id="monthlyamt1" class="form-control" value="{{$monthlyamt1}}" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          
                                          <input type="text" name="quaterlyamt1" id="quaterlyamt1" class="form-control" value="{{$quaterlyamt1}}" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="halfyearamt1" id="half-yearamt1" class="form-control" value="{{$halfyearamt1}}"  readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="annualyamt1" id="annualyamt1" class="form-control" value="{{$annualyamt1}}" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                              </div>
                              <!-- Just view end -->
                            
                            </div>

                        </div>

                        <div class="card sip-calculator singleLineHolder calculatorFormShape">
                            <div class="form-group row">
                                <div class="col-sm-6 d-flex">
                                    <label class="sqarecontainer" style="margin: 8px 0 0 0;">
                                        <input id="is_client" type="checkbox" name="client" value="1" @if($client=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                    <input placeholder="Add Client Name" type="text" class="form-control {{ $errors->has('clientname') ? ' is-invalid' : '' }}" name="clientname"   value="{{$clientname}}" maxlength="30">
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
                                        <input id="is_note" type="checkbox" name="is_note" value="1" @if($is_note=='1') checked  @endif> 
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <div class="col-sm-7">
                                    <textarea class="form-control {{ $errors->has('note') ? ' is-invalid' : '' }}" name="note" rows="2" id="note" style="height: 100px;" maxlength="500" onkeyup="changeNote();">{{$note}}</textarea>
                                    <div class="text-right charcount"><span id="note_total_count">0</span>/500 characters left.</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Get Report</label>
                                <div class="col-sm-7">
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Summary Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio1" value="summary" @if($report=='summary') checked  @endif>
                                            <span class="checkmark"></span>
                                        </label> 
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <label class="checkLinecontainer">Detailed Report
                                            <input class="form-check-input" type="radio" name="report" id="inlineRadio2" value="detailed" @if($report=='summary')  @else checked  @endif >
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
                                        
                                        @if(session()->get('calculator_form_id'))
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                        @else
                                            <a href="{{route('frontend.swp_comprehension')}}" class="btn banner-btn whitebg mx-3"> Reset</a>
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
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />-->
        <!--</div>-->
    </section>

@endsection
