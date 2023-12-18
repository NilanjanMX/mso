@extends('layouts.frontend')

@section('js_after')

    <script>
      var invType = 1;
       
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
          console.log("swp amt "+document.getElementById("swpamount").value + " swp type "+document.getElementById("swptype").value);
      },100);
      
      
      var allAmount = 0;
      var tlems = 0;
        
      var csam = window.setInterval(function(){
            var swptp = document.getElementById("swptype").value;
            var form = $("#emi");
            var url = "/premium-calculator/swp_check";
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
                    console.log("retting:"+retVal);
                },
                error: function(dat) {

                }
            });
            console.log("retval:"+retVal);
            console.log("withdrawtype "+document.getElementById("withdrawtype").value);
            console.log("swpamount "+document.getElementById("swpamount").value);
            var defi =parseInt(document.getElementById('defer').value);
            
            if(defi == 0){
              document.getElementById('defval').setAttribute('readonly',true);
            }else{
              document.getElementById('defval').removeAttribute('readonly');
            }
                      
      },1000);
      
      var amountProjectiled = 0;
      
      function ShowAmount(){
            
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

        @if(old('client')!='')
            $('input[name="clientname"]').prop("readonly", false);
        @else
            $('input[name="clientname"]').prop("readonly", true);
        @endif
        @if(old('note')!='')
            $('textarea[name="note"]').prop("readonly", false);
        @else
            $('textarea[name="note"]').prop("readonly", true);
        @endif
    </script>
@endsection

    
    

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" integrity="sha256-+N4/V/SbAFiW1MPBCXnfnP9QSN3+Keu+NlB+0ev/YKQ=" crossorigin="anonymous" />
    <style>
        .sip-calculator label > span {
                vertical-align: middle;
                position: relative;
                display: inline-block;
        }
        .sip-calculator label > span i {
            color: #979393;
        }
        .sip-calculator label > span:hover i {
            color:#131f55;
        }
        .sip-calculator label > span > span {
                position: absolute;
                font-size: 12px;
                white-space: nowrap;
                background: #fff;
                box-shadow: 1px 1px 3px #000;
                padding: 7px;
                border-radius: 4px;
                top: 16px;
                font-weight: normal;
                display: none;
                z-index: 99999999;
        }
        .sip-calculator label > span:hover span {
            display: block;
        }
        .financial_block {
            display: flex;
            flex-direction: row;
            padding:10px;
            border: 1px solid #e2e2e2;
            align-items: center;
        }
        .financial_input { width: calc(100% - 70px); }
        .financial_delete {
            width: 70px;
            text-align:center;
            color: red;
        }
        .btn-primary {
            color: #fff;
            background-color: #16a1db;
            border-color: #16a1db;
            padding: 7px 25px;
            font-weight: 400;
        }
        .savedButton {
            border-radius: 9px !important;
            background: #16a1db !important;
            border: 1px solid #131f55 !important;
            line-height: 17px !important;
        }
        /* biswanath starts  */
        .fourbox {
            padding-left: 25px;
        }
        .fourbox input[type=radio] {
            margin-top: 14px;
        }
        .fourbox input[type=text] {
            padding-right: 0;
        }
        .maxClass {
            display: block;
            text-align: right;
            font-size: 12px;
        }
        /* biswanath starts  */
    </style>
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
                    <form class="js-validate-form" action="{{route('frontend.swp_comprehension_output')}}" method="post">
                        <div class="card sip-calculator singleLineHolder">
                            <input type="hidden" name="formType" value="3" />
                            @csrf
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Custom Calculator Header</label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-2">
                                          <div class="d-flex align-items-center">
                                            <select name="headertype" class="form-control" id="headertype">
                                               
                                               <option value="no" selected>No</option>
                                               <option value="yes">Yes</option>
                                           </select>
                                          </div>
                                    
                                        </div>
                                        <div class="col-sm-10">
                                            
                                            <div class="d-flex align-items-center">
                                                &nbsp;
                                                <input type="text" class="form-control" id="aft" name="details" value="" maxlength="30" readonly>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Current Age</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pr-2 mr-1 maxtwodigit " name="currentage" value="" required="" maxlength="2">
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Investment Mode</label>
                                <div class="col-sm-7">
                                    <div class="form-check">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="investmentmode" onchange="TurnOnSip(1)"  checked value=1>
                                        <label class="form-check-label" for="inlineRadio1">Lumpsum</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="investmentmode" value=2  onchange="TurnOnSip(2)" >
                                        <label class="form-check-label" for="inlineRadio2">Annual Lumpsum</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="investmentmode" value=3  onchange="TurnOnSip(3)" >
                                        <label class="form-check-label" for="inlineRadio2">SIP</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="investmentmode" value=4  onchange="TurnOnSip(4)" >
                                        <label class="form-check-label" for="inlineRadio2">Lumpsum+SIP</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Initial Investment</label>
                                <div class="col-sm-7">
                                    <input type="text" class="form-control pr-2 mr-1" id="initial" name="initial" value="" required="">
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            <div id="sipamt" style="display:none;">
                              <div class="form-group row">
                                  <label class="col-sm-5 col-form-label">SIP Amount</label>
                                  <div class="col-sm-7">
                                      <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="sipamti" name="sipamt" value="" required="">
                                      <div class="cal-icon">
                                          ₹
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div id="invper" style="display:none;">
                               <div class="form-group row" >
                                  <label class="col-sm-5 col-form-label" id="invtypeperiod">Investment Period</label>
                                  <div class="col-sm-7">
                                      <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="invperi" name="invperiod" value="" required="" maxlength="2">
                                      <div class="cal-icon">
                                          Yrs
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div>
                              
                            </div>
                              
                             
                            <input type="hidden" name="def" value=0 id="defer"/>
                            <div class="form-group row">
                                <label class="col-sm-5 col-form-label">Deferment Period &nbsp; <span><i class="fa fa-eye"></i><span>If Deferment is chosen, withdrawal will start from the end of (Payment Period+Deferment Period)</span></span></label>
                                <div class="col-sm-7">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="deferment" onchange="showAc(1)"  checked value="1">
                                                <label class="form-check-label" for="inlineRadio1">No</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input fixed_deposit_chk" type="radio" name="deferment" value="2"  onchange="showAc(2)" >
                                                <label class="form-check-label" for="inlineRadio2">Yes</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="d-flex align-items-center">
                                                <input type="text" name="defermentperiod" id="defval" onChange="accumulatedcalc()" class="form-control" value="" readonly>
                                            </div>
                                            <div class="cal-icon">
                                                Yrs
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display:none;" id="acc">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">ACCUMULATION PHASE:</label>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-5 col-form-label">Select Asset Allocation</label>
                                    <div class="col-sm-7">
                                        <div class="row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Debt %</label> &nbsp;
                                                
                                                <input type="text" name="acdebt" id="acdebt"  class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Hybrid %</label> &nbsp;
                                                <input type="text" name="achybrid" id="achybrid" class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Equity %</label> &nbsp;
                                                <input type="text" name="acequity" id="acequity" class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Total %</label> &nbsp;
                                                <input type="text" name="actotal" id="actotal" class="form-control" value="" readonly>
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
                                                
                                                <input type="text" name="acdebt1" id="acdebt1" class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Hybrid %</label> &nbsp;
                                                <input type="text" name="achybrid1" id="achybrid1" class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Equity %</label> &nbsp;
                                                <input type="text" name="acequity1" id="acequity1" class="form-control" value="" onChange="SetAcTotal()">
                                            </div>
                                            <div class="col-sm-3">
                                                <label class="col-form-label">Average %</label> &nbsp;
                                                <input type="text" name="actotal1" id="actotal1" class="form-control" value="" readonly>
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
                                        <input type="text" id="accval" class="form-control" name="accumulated" value="" required="" readonly>
                                        
                                        
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
                                    <h6 class="text-muted">DISTRIBUTION PHASE:</h6>
                                </div>
                            </div>

                            <div class="form-group row">   
                                <label class="col-sm-4 col-form-label">Select Asset Allocation</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Debt %</label> &nbsp;
                                            
                                            <input type="text" name="debt" id="debt" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Hybrid %</label> &nbsp;
                                            <input type="text" name="hybrid" id="hybrid" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Equity %</label> &nbsp;
                                            <input type="text" name="equity" id="equity" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Total %</label> &nbsp;
                                            <input type="text" name="total" id="total" class="form-control" value="" readonly>
                                            <em id="t2" class="error"></em>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Assumed Rate of Return:</label>
                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Debt %</label> &nbsp;
                                            
                                            <input type="text" name="debt1" id="debt1" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Hybrid %</label> &nbsp;
                                            <input type="text" name="hybrid1" id="hybrid1" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Equity %</label> &nbsp;
                                            <input type="text" name="equity1" id="equity1" class="form-control" value="" onChange="SetTotal()">
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="col-form-label">Average %</label> &nbsp;
                                            <input type="text" name="total1" id="total1" class="form-control" value="" readonly>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><span>SWP Period</span></label>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="swp" id="swp" class="form-control" value="">
                                    </div>
                                    <div class="cal-icon">
                                        Yrs
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Include Annual Increment</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="annualincr" onchange="changeIncr(false)"  checked value="1">
                                        <label class="form-check-label" for="inlineRadio1">No</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="annualincr" value="2"  onchange="changeIncr(true)" >
                                        <label class="form-check-label" for="inlineRadio2">Yes</label>
                                    </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                            </div>
                                
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label"><span>In %</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input id="percentRadio" class="form-check-input fixed_deposit_chk"  onchange="activateMe(1)" type="radio" name="incrtype" class="form-control" value=0 disabled style="margin-top: 6px;"></label>
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="inpercent" id="incrtype1" class="form-control" value="" readonly>
                                    </div>
                                    <div class="cal-icon">
                                        %
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">
                                    <span>In Amount</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                                    <input id="percentAmount" class="form-check-input fixed_deposit_chk"  onchange="activateMe(2)" type="radio" name="incrtype" class="form-control" value=1 disabled style="margin-top: 6px;">
                                    <span><i class="fa fa-eye"></i><span>This option is not available right now !</span></span>
                                </label>
                                
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center">
                                        <input type="text" name="inamount" id="incrtype" class="form-control" value="" readonly>
                                    </div>
                                    <div class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row" style="display:none;">
                                <label class="col-sm-4 col-form-label"><span>SWP Amount</span></label>
                                
                                <div class="col-sm-8">
                                    <div class="d-flex align-items-center">
                                     <input type="text" value="inmonth" id="swptype" name="swptype"/>
                                     <input id="swpamount" type="hidden" name="swpamount" class="form-control" value="">
                                    </div>
                                    <div id="calico" class="cal-icon">
                                        ₹
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row" style = "display:none;">
                                    <label class="col-sm-4 col-form-label">Include Taxation</label>
                                    <div class="col-sm-8">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="taxation" onchange="TurnOnSip('sipInv')"  checked value="1">
                                        <label class="form-check-label" for="inlineRadio1">No</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="taxation" value="1"  onchange="TurnOnSip('emiInv')" >
                                        <label class="form-check-label" for="inlineRadio2">Yes</label>
                                    </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Select Withdrawal Option:</label>
                            </div>
                            <div class="form-group">
                                <div class="d-flex align-middle fourbox">
                                    <input class="form-check-input" type="radio" name="sets" onchange="SetSwp(1)">
                                    <label class="col-form-label">In Amount:</label>
                                    
                                </div>
                                
                            </div>
                            <div id="amountsec" style="display:none;">
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(1)">
                                          <label class="col-form-label">Monthly</label> &nbsp;
                                          <input id="monthly" type="text" name="mamount" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthamt'))" onfocusout = "document.getElementById('mnthamt').innerHTML = ''" readonly>
                                      </div>
                                      <span id="mnthamt" class="maxClass">  </span>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(2)">
                                          <label class="col-form-label">Quaterly</label> &nbsp;
                                          <input id="quaterly" type="text" name="qamount" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthqt'))" onfocusout = "document.getElementById('mnthqt').innerHTML = ''" readonly>
                                      </div>
                                      <span id="mnthqt" class="maxClass"> </span>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(3)">
                                          <label class="col-form-label">Half-Yearly</label> &nbsp;
                                          <input id="half-year" type="text" name="hamount" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthhf'))" onfocusout = "document.getElementById('mnthhf').innerHTML = ''" readonly>
                                      </div>
                                      <span id="mnthhf" class="maxClass"> </span>
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="cats" onchange="SetSwpValue(4)">
                                          <label class="col-form-label">Yearly</label> &nbsp;
                                          <input id="annualy" type="text" name="yamount" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthyr'))" onfocusout = "document.getElementById('mnthyr').innerHTML = ''" readonly>
                                      </div>
                                      <span id="mnthyr" class="maxClass"> </span>
                                  </div>
                              </div>
                            </div>
                            <input type="hidden" name="withdrawtype" id="withdrawtype" value="month">

                            <div class="form-group">
                                <div class="d-flex align-middle fourbox">
                                    <input class="form-check-input" type="radio" name="sets"  onchange="SetSwp(2)">
                                    <label class="col-form-label">In %: (Select the withdrawal mode and total annual withdrawal %)</label>
                                </div>
                            </div>
                            <div id="percentsec" style="display:none;">
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(1)">
                                          <label class="col-form-label">Monthly</label> &nbsp;
                                          <input type="text" name="mpercent" id="monthly1" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthamt1'))" onfocusout = "document.getElementById('mnthamt1').innerHTML = ''" oninput="ShowAmount()" readonly>
                                      </div>
                                      <span id="mnthamt1" class="maxClass"> </span>
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(2)">
                                          <label class="col-form-label">Quaterly</label> &nbsp;
                                          <input type="text" name="qpercent" id="quaterly1" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthqt1'))" onfocusout = "document.getElementById('mnthqt1').innerHTML = ''" oninput="ShowAmount()" readonly>
                                      </div>
                                      <span id="mnthqt1" class="maxClass">  </span>
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(3)">
                                          <label class="col-form-label">Half Yearly</label> &nbsp;
                                          <input type="text" name="hpercent" id="half-year1" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthhf1'))" onfocusout = "document.getElementById('mnthhf1').innerHTML = ''" oninput="ShowAmount()" readonly>
                                      </div>
                                      <span id="mnthhf1" class="maxClass">  </span>
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          <input class="form-check-input" type="radio" name="pats" onchange="SetSwpValue(4)">
                                          <label class="col-form-label">Yearly</label> &nbsp;
                                          <input type="text" name="ypercent" id="annualy1" class="form-control" value="" onfocus="GetMaxVal(document.getElementById('mnthyr1'))" onfocusout = "document.getElementById('mnthyr1').innerHTML = ''" oninput="ShowAmount()" readonly>
                                      </div>
                                      <span id="mnthyr1" class="maxClass">  </span>
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                              </div>
                              
                              <!-- Just amount view starts -->
                              <div class="form-group row">
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="" id="monthlyamt1" class="form-control" value="" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          
                                          <input type="text" name="" id="quaterlyamt1" class="form-control" value="" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="" id="half-yearamt1" class="form-control" value=""  readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                                  <div class="col-sm-3">
                                      <div class="d-flex align-middle fourbox">
                                          
                                          <input type="text" name="" id="annualyamt1" class="form-control" value="" readonly>
                                      </div>
                                      
                                      <!--<input type="text" name="" id="" class="form-control" value="">-->
                                  </div>
                              </div>
                              <!-- Just view end -->
                            
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

                            @include('frontend.calculators.suggested.form')


                            <div class="form-group row">

                                <div class="offset-1 col-sm-10">
                                    <div class=" calcBelowBtn">
                                            <a href="javascript:history.back()" class="btn banner-btn whitebg mx-3">Back</a>
                                            
                                            <button type="button" onclick="location.reload();" class="btn banner-btn whitebg mx-3"> Reset</button>
                                        
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
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
