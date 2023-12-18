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
        var invType = 1;
        // $(function () {
        //      $('#datetimepicker1').datetimepicker();
        //  });
         
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
                       debt:{
                           required:false,
                           range:[0.00,100.00],
                           digits:false,
                       },
                       debt1:{
                           required:false,
                           range:[0.00,8.00],
                           digits:false,
                       },
                       hybrid:{
                           required:false,
                           range:[0.00,100.00],
                           digits:false,
                       },
                       hybrid1:{
                           required:false,
                           range:[0.00,12.00],
                           digits:false,
                       },
                       equity:{
                           required:false,
                           range:[0.00,100.00],
                           digits:false,
                       },
                       equity1:{
                           required:false,
                           range:[0.00,15.00],
                           digits:false,
                       },
                       total:{
                           required:true,
                           min:100,
                           max:100,
                           digits:false,
                       },
                       total1:{
                           required:true,
                         
                           digits:false,
                       },
                       deferment:{
                           required : function()
                           {
                               return defApplied;
                           },
                           
                           digits:true,
                           range : [1,99]
                          
                       },
                       distribution : {
                           required : function()
                           {
                               return defApplied;
                           },
                           
                           digits:false,
                           range : [0.00,12.00]
                           
                       },
                       moneyback : {
                           required : function()
                           {
                               return defApplied;
                           },
                           
                           digits:true,
                           range : [1,99]
                           
                       },
                       installments : {
                           required : function()
                           {
                               return defApplied;
                           },
                           
                           digits:true,
                           range : [1,99]
                           
                       },
                       eachwithdraw : {
                           required : function()
                           {
                               return defApplied;
                           },
                           
                           digits:true,
                           
                           max : function()
                           {
                               return maxMoney;
                           }
                           
                       },
                       withdrawamount : {
                           required : function()
                           {
                               if(defApplied)
                               return false;
                               else
                               true;
                           },
                           
                           digits:true,
                           
                           max : function()
                           {
                               return maxMoney;
                           }
                       },
                       amount1: {
                           required : false,
                           digits:true,
                           max : function()
                           {
                               return showamt1;
                           },
                       },
                       amount2: {
                           required : false,
                           digits:true,
                           max : function()
                           {
                               return showamt2;
                           },
                       },
                       amount3: {
                           required : false,
                           digits:true,
                           max : function()
                           {
                               return showamt3;
                           },
                       },
                       amount4: {
                           required : false,
                           digits:true,
                           max : function()
                           {
                               return showamt4;
                           },
                       },
                       amount5: {
                           required : false,
                           digits:true,
                           max : function()
                           {
                               return showamt5;
                           },
                       },
                       period1:{
                           required:false,
                           digits:true,
                           max:100,
                       },
                       period2:{
                           required:false,
                           digits:true,
                           min:function()
                           {
                               var v = parseInt(document.getElementById("p1").value);
                               if(isNaN(v))
                               {
                                   v = 0;
                               }
                               return (v+1);
                           },
                           max:100,
                       },
                       period3:{
                           required:false,
                           digits:true,
                           min:function()
                           {
                               var v = parseInt(document.getElementById("p2").value);
                               if(isNaN(v))
                               {
                                   v = 0;
                               }
                               return (v+1);
                           },
                           max:100,
                       },
                       period4:{
                           required:false,
                           digits:true,
                           min:function()
                           {
                               var v = parseInt(document.getElementById("p3").value);
                               if(isNaN(v))
                               {
                                   v = 0;
                               }
                               return (v+1);
                           },
                           max:100,
                       },
                       period5:{
                           required:false,
                           digits:true,
                           min:function()
                           {
                               var v = parseInt(document.getElementById("p4").value);
                               if(isNaN(v))
                               {
                                   v = 0;
                               }
                               return (v+1);
                           },
                           max:100,
                       },
                       withdrawal : {
                           required:false,
                          digits:true,
                          min : function()
                          {
                              var i = parseInt(document.getElementById("invperiod").value);
                              
                                var m = parseInt(document.getElementById("interval").value);
                                
                                 //var invType = document.getElementById("investmentmode").value;
                                 
                                 console.log(invType);
                               
                              if(invType == 1)
                              return 0;
                              
                              console.log("->>> "+i+" "+m);
                                
                                if(isNaN(i))
                                 i = 0;
                                 if(isNaN(m))
                                 m=0;
                                var t = i/m;
                                
                                if(isNaN(t))
                                {
                                    return 0;
                                }
                                else
                                {
                                    var result = (t - Math.floor(t)) !== 0;
                                    
                                    if(result)
                                    {
                                        t++;
                                    }
                                    return parseInt(t);
                                }
                          },
                       },
                       lastwithdraw:{
                          required:function()
                          {
                              return sType;
                          },
                          digits:false,
                          min:function(){
                        
                               
                              if(invType != 1){
                              var t = parseInt(document.getElementById("invperiod").value) ;
                               
                              if(isNaN(t))
                                {
                                    console.log("min-> "+t);
                                    return 0;
                                }
                                else
                                {
                                    console.log("min->-> "+t);
                                    return t;
                                }
                              }
                              else
                              {
                                  var p1 = parseInt(document.getElementById("p1").value);
                                  var p2 = parseInt(document.getElementById("p2").value);
                                  var p3 = parseInt(document.getElementById("p3").value);
                                  var p4 = parseInt(document.getElementById("p4").value);
                                  var p5 = parseInt(document.getElementById("p5").value);
                                   
                                  if(isNaN(p1))
                                  p1 = 0;
                                  if(isNaN(p2))
                                  p2 = 0;
                                  if(isNaN(p3))
                                  p3 = 0;
                                  if(isNaN(p4))
                                  p4 = 0;
                                  if(isNaN(p5))
                                  p5 = 0;
                                   
                                  if(p5 > 0)
                                  return p5;
                                  else if(p4 > 0)
                                  return p4;
                                  else if(p3 > 0)
                                  return p3;
                                  else if(p2 > 0)
                                  return p2;
                                  else
                                  return p1;
                              }
                          },
                          max:100,
                       },
                       
                    interval:{
                    required:function()
                    {
                        return (!sType);
                    },
                    digits:true,
                    max:function()
                    {
                        if(!sType)
                        {
                            console.log("It is 20");
                            return 20;
                        }
                        else
                        {
                            return 10000;
                        }
                    },
                },  
                       
                },
                
                messages: {
                    initial:"Please enter value between ₹100 - ₹9999999999",
                    
                    
                    
                }
            });
        });
        

        var global_type = 1;
        
        function showValidation(val)
        {
            var timeout;
            
                if(isNaN(maxMoney))
                maxMoney = 0;
            if(isNaN(per1))
                per1 = 0;
            if(isNaN(per2))
                per2 = 0;
            if(isNaN(per3))
                per3 = 0;
            if(isNaN(per4))
                per4 = 0;
            if(isNaN(per5))
                per5 = 0;
                
                
            if(val == "each")
            {
                if(document.getElementById("eachwithdrawamm").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("eachwithdrawamm").removeAttribute('readonly');
                        document.getElementById("eachwithdrawtext").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(maxMoney);
                    },3000);
                    return;
                    
                }
                
                document.getElementById("eachwithdrawtext").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(maxMoney);
            }
            if(val == "withdraw")
            {
                if(document.getElementById("withdrawamount").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("withdrawamount").removeAttribute('readonly');
                        document.getElementById("withdrawtext").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(maxMoney);
                    },3000);
                    return;
                    
                }
                    
                document.getElementById("withdrawtext").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(maxMoney);
            }
            if(val == "amount1")
            {
                if(document.getElementById("a1").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("amount1").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per1);
                    },3000);
                    return;
                    
                }
                document.getElementById("amount1").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per1);
            }
            if(val == "amount2")
            {
                if(document.getElementById("a2").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("amount2").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per2);
                    },3000);
                    return;
                    
                }
                document.getElementById("amount2").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per2);
            }
            if(val == "amount3")
            {
                if(document.getElementById("a3").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("amount3").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per3);
                    },3000);
                    return;
                    
                }
                document.getElementById("amount3").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per3);
            }
            if(val == "amount4")
            {
                if(document.getElementById("a4").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("amount4").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per4);
                    },3000);
                    return;
                    
                }
                document.getElementById("amount4").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per4);
            }
            if(val == "amount5")
            {
                if(document.getElementById("a5").hasAttribute('readonly')){
                    timeout = setTimeout(function(){
                        document.getElementById("amount5").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per5);
                    },3000);
                    return;
                    
                }
                document.getElementById("amount5").innerHTML = "Amount cannot exceed "+new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(per5);
            }
            
            
        }
        
        function TurnOnSip(dat)
        {
            invType = dat;
            var withdrawText = document.getElementById("withdrawalinterval");
            var installText = document.getElementById("installments");
            var invper = document.getElementById("invper");
            
            if(dat == 1)
            {
                withdrawText.innerHTML = "Periodic Withdrawal Interval";
                installText.innerHTML = "No. of Withdrawals";
                invper.style.display = "none";
            }
            else if(dat == 2)
            {
                withdrawText.innerHTML = "Periodic Withdrawal Interval";
                installText.innerHTML = "No. of Installments";
                invper.style.display = "block";
            }
            
            
            
        }
        
        var validate = function(e) {
          var t = e.value;
          e.value = (t.indexOf(".") >= 0) ? (t.substr(0, t.indexOf(".")) + t.substr(t.indexOf("."), 3)) : t;
        }
        
        var maxMoney;
        var per1;
        var per2;
        var per3;
        var per4;
        var per5;
        
        var showamt1 = 0;
        var showamt2 = 0;
        var showamt3 = 0;
        var showamt4 = 0;
        var showamt5 = 0;
        
        var csam = window.setInterval(function()
        {
            //maxMoney = 0; per1 = 0; per2 = 0; per3=0; per4=0; per5 = 0;
           // var swptp = document.getElementById("swptype").value;
            var form = $("#emi");
            var url = "{{url('/')}}/premium-calculator/periodicCheck";
            $.ajax({
                type: "POST",
                crossDomain: true,
                url: url,
                data: form.serialize(),
                success: function(dat) {
                      
                    // Ajax call completed successfully
                    //alert(data.responseJSON.response_msg);

                   
                    
                    console.log("retvalAdd:"+dat);
                    var responseDat = dat.split(',');
                    
                    if(responseDat[0] == "est")
                    {
                        maxMoney = parseInt(responseDat[1]);
                    }
                    else
                    {
                        if(responseDat[1] != null && responseDat[1] != "")
                        {
                            per1 =parseInt(responseDat[1]) ;
                            console.log('p1'+per1);
                        }
                        if(responseDat[2] != null && responseDat[2] != "")
                        {
                            per2 = parseInt(responseDat[2]);
                        }
                        if(responseDat[3] != null && responseDat[3] != "")
                        {
                            per3 = parseInt(responseDat[3]);
                        }
                        if(responseDat[4] != null && responseDat[4] != "")
                        {
                            per4 = parseInt(responseDat[4]);
                        }
                        if(responseDat[5] != null && responseDat[5] != "")
                        {
                            per5 = parseInt(responseDat[5]);
                        }
                        
                        if(!isNaN(per1))
                        {
                            showamt1 = per1;
                        }
                        if(!isNaN(per2))
                        {
                            showamt2 = per2;
                        }
                        if(!isNaN(per3))
                        {
                            showamt3 = per3;
                        }
                        if(!isNaN(per4))
                        {
                            showamt4 = per4;
                        }
                        if(!isNaN(per5))
                        {
                            showamt5 = per5;
                        }
                    }
                },
                error: function(dat) {
                      
                    
                    //console.log("tera"+dat);
                    
                }
            });
                        //console.log("retval:"+dat);
                        
                        // var defi =parseInt(document.getElementById('defer').value);
                        
                        // if(defi == 0)
                        // {
                        //     document.getElementById('defval').setAttribute('readonly',true);
                        // }
                        // else
                        // {
                        //     document.getElementById('defval').removeAttribute('readonly');
                        // }
                      var custom = document.getElementById("cus").value;
                      
                      if(custom == 1)
                      {
                          document.getElementById("aft").setAttribute('readonly',true);
                      }
                      else if(custom == 2)
                      {
                          document.getElementById("aft").removeAttribute('readonly');
                      }
                      
                      console.log("stype is "+sType);
        },1000);
        
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
            
            //console.log("total percent "+equity);
            
            
            
            
            if(tot != 100)
                {
                    document.getElementById("t2").innerHTML = "Total 100";
                }
                else
                {
                    document.getElementById("t2").innerHTML = "";
                }
                total.value = tot;
                total1.value = tot1;
                
            
            
        }
        
        function SetAcTotal()
        {
            // var total = document.getElementById('actotal') ;
            // var equity = parseFloat(document.getElementById('acequity').value);
            // var debt =parseFloat(document.getElementById('acdebt').value);
            // var hybrid = parseFloat(document.getElementById('achybrid').value);
            
            // var total1 = document.getElementById('actotal1') ;
            // var equity1 = parseFloat(document.getElementById('acequity1').value);
            // var debt1 =parseFloat(document.getElementById('acdebt1').value);
            // var hybrid1 = parseFloat(document.getElementById('achybrid1').value);
            
            // if(isNaN(equity))
            // equity = 0;
            // if(isNaN(debt))
            // debt = 0;
            // if(isNaN(hybrid))
            // hybrid = 0;
            
            // if(isNaN(equity1))
            // equity1 = 0;
            // if(isNaN(debt1))
            // debt1 = 0;
            // if(isNaN(hybrid1))
            // hybrid1 = 0;
            
            // var tot = equity + debt + hybrid;
            
            // var tot1 = (equity/100*equity1) + (debt/100*debt1) + (hybrid/100*hybrid1);
            
            // //console.log("total percent "+equity);
            
            
            
            
            // if(tot > 100)
            //     total.value = 100;
            // else
            //     total.value = tot;
                
            // if(tot1 > 100)
            //     total1.value = 100;
            // else
            //     total1.value = tot1;
            
            //accumulated();
        }
        //$count = 1;
        var count = 1;
        var defApplied = false;
        
        function enableDisble(val)
        {
           
        }
        function activateMe(val)
        {
            var f = document.getElementById("defermentallot");
            var s = document.getElementById("swpmode");
            
            if(val == 2){
            f.style.display = 'block';
            s.style.display = 'none';
            defApplied = true;
            }
            else
            {
                f.style.display = 'none';
            s.style.display = 'block';
            defApplied = false;
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
           
           
            
        },1000);
        
        var intervalId = window.setInterval(function(){
                    
          //accumulatedcalc();
        }, 1000);

        function accumulatedcalc()
        {
            
            var def = parseInt(document.getElementById("defval").value);
            var init = parseInt(document.getElementById("initial").value);
            var sipamt = parseInt(document.getElementById("sipamti").value);
            var total = parseInt(document.getElementById("actotal1").value);
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


        
        
        function showAc(val)
        {
            var area = document.getElementById('acc');
            var def = document.getElementById('defer');
            
            if(val == 1)
            {
                
                area.style.display = "none";
                def.value = 0;
            }
            else
            {
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
        var sType = false;
        function activateCustom(val)
        {
            
            if(val == true)
            {
                sType = true;
                document.getElementById("custom").style.display = "block";
                document.getElementById("fixed").style.display = "none";
            }
            else
            {
                sType = false;
                document.getElementById("custom").style.display = "none";
                document.getElementById("fixed").style.display = "block";
            }
        }
        
        function invest(val)
        {
            invType = val;
            var addString = '<span><i class="fa fa-eye"></i><span>Withdrawal will start from the end of (Payment Period+Deferment Period)</span></span>';
            if(val == 3)
            {
                document.getElementById("sipamt").style.display = "none";
                document.getElementById("invper").style.display = "block";
                document.getElementById("invlabel").innerHTML = "SIP Period";
                document.getElementById("invtypetext").innerHTML = "Monthly SIP Amount";
                document.getElementById("withdrawalinterval").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("installments").innerHTML = "No of Installments";
                document.getElementById("eachwithdrawamt").innerHTML = "Each Withdrawal Amount";
                document.getElementById("expected").style.display = "block";
                document.getElementById("defermentperiod").innerHTML = "Deferment Period "+addString;
                document.getElementById("moneyback").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("install").innerHTML = "No. of Instalment";
                document.getElementById("each").innerHTML = "Each Withdrawal Amount";
                
            }
            else if(val == 4)
            {
                document.getElementById("sipamt").style.display = "block";
                document.getElementById("invper").style.display = "block";
                document.getElementById("invlabel").innerHTML = "SIP Period";
                document.getElementById("invtypetext").innerHTML = "Lumpsum Investment";
                document.getElementById("withdrawalinterval").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("installments").innerHTML = "No of Installments";
                document.getElementById("eachwithdrawamt").innerHTML = "Each Withdrawal Amount";
                document.getElementById("expected").style.display = "block";
                document.getElementById("defermentperiod").innerHTML = "Deferment Period "+addString;
                document.getElementById("moneyback").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("install").innerHTML = "No. of Instalment";
                document.getElementById("each").innerHTML = "Each Withdrawal Amount";
            }
            else if(val == 2)
            {
                document.getElementById("sipamt").style.display = "none";
                document.getElementById("invper").style.display = "block";
                document.getElementById("invlabel").innerHTML = "Payment Period";
                document.getElementById("invtypetext").innerHTML = "Annual Investment";
                document.getElementById("withdrawalinterval").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("installments").innerHTML = "No of Installments";
                document.getElementById("eachwithdrawamt").innerHTML = "Each Withdrawal Amount";
                document.getElementById("expected").style.display = "block";
                document.getElementById("defermentperiod").innerHTML = "Deferment Period "+addString;
                document.getElementById("moneyback").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("install").innerHTML = "No. of Instalment";
                document.getElementById("each").innerHTML = "Each Withdrawal Amount";
            }
            else
            {
                document.getElementById("sipamt").style.display = "none";
                document.getElementById("invper").style.display = "none";
                document.getElementById("invtypetext").innerHTML = "Initial Investment";
                document.getElementById("withdrawalinterval").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("installments").innerHTML = "No. of Withdrawals";
                document.getElementById("eachwithdrawamt").innerHTML = "Each Withdrawal Amount";
                document.getElementById("expected").style.display = "block";
                document.getElementById("defermentperiod").innerHTML = "Deferment Period";
                document.getElementById("moneyback").innerHTML = "Periodic Withdrawal Interval";
                document.getElementById("install").innerHTML = "No. of Instalment";
                document.getElementById("each").innerHTML = "Each Withdrawal Amount";
            }
        }
        
        function AddAnother(val)
        {
            if(val == 1)
            {
                if(document.getElementById("p1").value != ""){
                    
                    setTimeout(function(){
                        document.getElementById("a1").removeAttribute('readonly');
                    },2000);
                    
                }
                else
                {
                    document.getElementById("a1").setAttribute('readonly',true);
                }
            }
              if(val == 2){  
                if(document.getElementById("p2").value != ""){
                    setTimeout(function(){
                        document.getElementById("a2").removeAttribute('readonly');
                    },2000);
                }
                else
                {
                    document.getElementById("a2").setAttribute('readonly',true);
                }
              }
                
                if(val == 3){
                if(document.getElementById("p3").value != ""){
                    setTimeout(function(){
                        document.getElementById("a3").removeAttribute('readonly');
                    },2000);
                }
                else
                {
                    document.getElementById("a3").setAttribute('readonly',true);
                }
                }
                
                if(val == 4){
                if(document.getElementById("p4").value != ""){
                    setTimeout(function(){
                        document.getElementById("a4").removeAttribute('readonly');
                    },2000);
                }
                else
                {
                    document.getElementById("a4").setAttribute('readonly',true);
                }
                }
                
                if(val == 5){
                if(document.getElementById("p5").value != ""){
                    setTimeout(function(){
                        document.getElementById("a5").removeAttribute('readonly');
                    },2000);
                }
                else
                {
                    document.getElementById("a5").setAttribute('readonly',true);
                }
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

        @if($client==1)
            $('input[name="clientname"]').prop("readonly", false);
        @else
            $('input[name="clientname"]').prop("readonly", true);
        @endif
        
        @if($is_note==1)
            $('textarea[name="note"]').prop("readonly", false);
        @else
            $('textarea[name="note"]').prop("readonly", true);
        @endif

        var dafsdsd = "{{$investmentmode}}";


        invest(dafsdsd);
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
                    <h3 class="mb-3 text-center">SWP Periodic Withdrawal</h3>
                    @include('frontend.calculators.common_bio')
                    <br>
                    <form class="js-validate-form" action="{{route('frontend.swp_periodic_withdrawal_output')}}" method="post" id="emi">
                        @csrf
                        <div class="card sip-calculator singleLineHolder">
                            <input type="hidden" name="formType" value="2" />
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Custom Calculator Header </label>
                              <div class="col-sm-8">
                                <div class="row">
                                  <div class="col-sm-2">
                                    <div class="d-flex align-items-center">
                                      <select name="headertype" id="cus" class="form-control">
                                        <option value="1" @if($headertype=='1') selected  @endif>No</option>
                                        <option value="2" @if($headertype=='2') selected  @endif>Yes</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-sm-10">
                                    <div class="d-flex align-items-center"> &nbsp; <input type="text" class="form-control" id="aft" name="details" value="{{$details}}" maxlength=30>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Current Age (optional) </label>
                              <div class="col-sm-8">
                                <div class="row">
                                  <div class="col-sm-12">
                                    <div class="d-flex align-items-center">
                                      <input type="number" class="form-control pr-2 mr-1 maxtwodigit " name="currentage" value="{{$currentage}}" maxlength="2">
                                    </div>
                                    <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Investment Mode</label>
                              <div class="col-sm-8">
                                <div class="form-check">
                                  <input class="form-check-input fixed_deposit_chk" id="investmentmode1" type="radio" name="investmentmode" onchange="invest(1)" @if($investmentmode==1) checked  @endif value=1>
                                  <label class="form-check-label" for="investmentmode1">Lumpsum</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input fixed_deposit_chk" id="investmentmode2" type="radio" name="investmentmode" value=2 @if($investmentmode==2) checked  @endif onchange="invest(2)">
                                  <label class="form-check-label" for="investmentmode2">Annual Lumpsum</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input fixed_deposit_chk" id="investmentmode3" type="radio" name="investmentmode" value=3 @if($investmentmode==3) checked  @endif onchange="invest(3)">
                                  <label class="form-check-label" for="investmentmode3">SIP</label>
                                </div>
                                <div class="form-check">
                                  <input class="form-check-input fixed_deposit_chk" id="investmentmode4" type="radio" name="investmentmode" value=4 @if($investmentmode==4) checked  @endif onchange="invest(4)">
                                  <label class="form-check-label" for="investmentmode4">Lumpsum+SIP</label>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label" id="invtypetext">Initial Investment</label>
                              <div class="col-sm-8">
                                <div class="row">
                                  <div class="col-sm-12">
                                    <div class="d-flex align-items-center">
                                      <input type="text" id="initial" class="form-control" name="initial" value="{{$initial}}" required="">
                                    </div>
                                    <div class="cal-icon" style="width:79px; right:20px;"> ₹ </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div id="sipamt" style="display:none;">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Monthly SIP Amount </label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" class="form-control" id="sipamti" name="monthlysipamount" value="{{$monthlysipamount}}">
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> ₹ </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <!--Invperiod-->
                            <div id="invper" style="display:none;">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label" id="invlabel">Investment Period</label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" id="invperiod" class="form-control" name="invper" value="{{$invper}}" required="">
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
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
                                <label class="col-sm-4 col-form-label">Select Asset Allocation</label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Debt %</label> &nbsp; <input type="text" name="acdebt" id="acdebt" class="form-control" value="{{$acdebt}}" onChange="SetAcTotal()">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Hybrid %</label> &nbsp; <input type="text" name="achybrid" id="achybrid" class="form-control" value="{{$achybrid}}" onChange="SetAcTotal()">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Equity %</label> &nbsp; <input type="text" name="acequity" id="acequity" class="form-control" value="{{$acequity}}" onChange="SetAcTotal()">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Total %</label> &nbsp; <input type="text" name="actotal" id="actotal" class="form-control" value="{{$actotal}}" readonly>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <!-- Next asset-->
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Assumed Rate of Return:</label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Debt %</label> &nbsp; <input type="text" name="acdebt1" id="acdebt1" class="form-control" value="{{$acdebt1}}" onChange="SetAcTotal()" oninput="validate(this)">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Hybrid %</label> &nbsp; <input type="text" name="achybrid1" id="achybrid1" class="form-control" value="{{$achybrid1}}" onChange="SetAcTotal()" oninput="validate(this)">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Equity %</label> &nbsp; <input type="text" name="acequity1" id="acequity1" class="form-control" value="{{$acequity1}}" onChange="SetAcTotal()" oninput="validate(this)">
                                    </div>
                                    <div class="col-sm-3">
                                      <label class="col-form-label">Average %</label> &nbsp; <input type="text" name="actotal1" id="actotal1" class="form-control" value="{{$actotal1}}" readonly>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Select Asset Allocation</label>
                              <div class="col-sm-8">
                                <div class="row">
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Debt %</label> &nbsp; <input type="text" name="debt" id="debt" class="form-control" value="{{$debt}}" onChange="SetTotal()">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Hybrid %</label> &nbsp; <input type="text" name="hybrid" id="hybrid" class="form-control" value="{{$hybrid}}" onChange="SetTotal()">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Equity %</label> &nbsp; <input type="text" name="equity" id="equity" class="form-control" value="{{$equity}}" onChange="SetTotal()">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Total %</label> &nbsp; <input type="text" name="total" id="total" class="form-control" value="{{$total}}" readonly>
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
                                    <label class="col-form-label">Debt %</label> &nbsp; <input type="text" name="debt1" id="debt1" class="form-control" value="{{$debt1}}" onChange="SetTotal()" oninput="validate(this)">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Hybrid %</label> &nbsp; <input type="text" name="hybrid1" id="hybrid1" class="form-control" value="{{$hybrid1}}" onChange="SetTotal()" oninput="validate(this)">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Equity %</label> &nbsp; <input type="text" name="equity1" id="equity1" class="form-control" value="{{$equity1}}" onChange="SetTotal()" oninput="validate(this)">
                                  </div>
                                  <div class="col-sm-3">
                                    <label class="col-form-label">Average %</label> &nbsp; <input type="text" name="total1" id="total1" class="form-control" value="{{$total1}}" readonly>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="form-group row">
                              <label class="col-sm-4 col-form-label">Deferment Required</label>
                              <div class="col-sm-8">
                                <div class="row">
                                  <div class="col-sm-4">
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input fixed_deposit_chk" type="radio" name="def" onchange="activateMe(1)"  @if($def==1) checked  @endif value=1>
                                      <label class="form-check-label" for="inlineRadio1">No</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                      <input class="form-check-input fixed_deposit_chk" type="radio" name="def" value=2 @if($def==2) checked  @endif onchange="activateMe(2)">
                                      <label class="form-check-label" for="inlineRadio2">Yes</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div id="defermentallot" style="display:none;">
                              <div id="expected" style="display:block;">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label" id="invtypeperiod">Assumed Return During Distribution Period </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                          <input type="text" class="form-control" id="invperi" name="distribution" value="{{$distribution}}" required="">
                                        </div>
                                        <div class="cal-icon" style="width:79px; right:20px;"> % </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label" id="defermentperiod">Deferment Period </label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="swpdefer" name="deferment" value="{{$deferment}}" required="" maxlength="2">
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label" id="moneyback">Periodic Withdrawal Interval </label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="moneyback" name="moneyback" value="{{$moneyback}}" required="" maxlength="2">
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label" id="install">No. of Instalment </label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" class="form-control pr-2 mr-1 maxtwodigit " id="" name="installments" value="{{$installments}}" required="" maxlength="2">
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label" id="each">Each Withdrawal Amount </label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-12">
                                      <div class="d-flex align-items-center">
                                        <input type="text" class="form-control" id="eachwithdrawamm" name="eachwithdraw" onfocus="showValidation('each')" onfocusout="document.getElementById('withdrawtext').innerHTML = ''" value="{{$eachwithdraw}}" required="" readonly>
                                      </div>
                                      <div class="cal-icon" style="width:79px; right:20px;"> ₹ </div>
                                    </div>
                                  </div>
                                  <p id="eachwithdrawtext"></p>
                                </div>
                              </div>
                            </div>
                            <div id="swpmode">
                              <div class="form-group row">
                                <label class="col-sm-4 col-form-label">Select SWP Mode</label>
                                <div class="col-sm-8">
                                  <div class="row">
                                    <div class="col-sm-4">
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk" type="radio" name="swpmode" onchange="activateCustom(false)"  @if($swpmode==1) checked  @endif value=1>
                                        <label class="form-check-label" for="inlineRadio1">Fixed</label>
                                      </div>
                                      <div class="form-check form-check-inline">
                                        <input class="form-check-input fixed_deposit_chk"  @if($swpmode==2) checked  @endif type="radio" name="swpmode" value=2 onchange="activateCustom(true)">
                                        <label class="form-check-label" for="inlineRadio2">Custom</label>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div id="fixed">
                                <div class="form-group row">
                                  <label id="withdrawalinterval" class="col-sm-4 col-form-label">Periodic Withdrawal Interval </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                          <input type="text" class="form-control" id="interval" name="interval" value="{{$interval}}">
                                        </div>
                                        <div class="cal-icon" style="width:79px; right:20px;"> Yrs </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label id="installments" class="col-sm-4 col-form-label">No. of Withdrawals </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                          <input type="text" class="form-control" id="" name="withdrawal" value="{{$withdrawal}}">
                                        </div>
                                        <div class="cal-icon" style="width:79px; right:20px;"> Nos </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label id="eachwithdrawamt" class="col-sm-4 col-form-label">Each Withdrawal Amount </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                          <input type="text" class="form-control" id="withdrawamount" name="withdrawamount" onfocus="showValidation('withdraw')" onfocusout="document.getElementById('withdrawtext').innerHTML = ''" value="{{$withdrawamount}}" required="" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:79px; right:20px;"> ₹ </div>
                                        <p id="withdrawtext"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div id="custom" style="display:none;">
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Withdrawal 1 </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-4">
                                        <div class="d-flex align-items-center"> Period (Yrs) <input type="text" class="form-control" id="p1" name="period1" value="{{$period1}}" onChange="AddAnother(1)" style="width:70%;">
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:20px; top: 4px;">Yrs</div>
                                      </div>
                                      <div class="col-sm-8">
                                        <div class="d-flex align-items-center"> Amount &nbsp; <input type="text" class="form-control" id="a1" name="amount1" style="width:70%;" value="{{$amount1}}" onfocus="showValidation('amount1')" onfocusout="document.getElementById('amount1').innerHTML = ''" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:92px;">₹</div>
                                        <p id="amount1"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Withdrawal 2 </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-4">
                                        <div class="d-flex align-items-center"> Period (Yrs) <input type="text" class="form-control" id="p2" name="period2" value="{{$period2}}" onChange="AddAnother(2)" style="width:70%;">
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:20px; top: 4px;">Yrs</div>
                                      </div>
                                      <div class="col-sm-8">
                                        <div class="d-flex align-items-center"> Amount &nbsp <input type="text" class="form-control" id="a2" name="amount2" value="{{$amount2}}" style="width:70%;" onfocus="showValidation('amount2')" onfocusout="document.getElementById('amount2').innerHTML = ''" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:92px;"> ₹ </div>
                                        <p id="amount2"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Withdrawal 3 </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-4">
                                        <div class="d-flex align-items-center"> Period (Yrs) <input type="text" class="form-control" id="p3" name="period3" value="{{$period3}}" onChange="AddAnother(3)" style="width:70%;">
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:20px; top: 4px;"> Yrs </div>
                                      </div>
                                      <div class="col-sm-8">
                                        <div class="d-flex align-items-center"> Amount &nbsp; <input type="text" class="form-control" id="a3" name="amount3" value="{{$amount3}}" style="width:70%;" onfocus="showValidation('amount3')" onfocusout="document.getElementById('amount3').innerHTML = ''" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:92px;"> ₹ </div>
                                        <p id="amount3"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Withdrawal 4 </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-4">
                                        <div class="d-flex align-items-center"> Period (Yrs) &nbsp; <input type="text" class="form-control" id="p4" name="period4" value="{{$period4}}" onChange="AddAnother(4)" style="width:70%;">
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:20px; top: 4px;"> Yrs </div>
                                      </div>
                                      <div class="col-sm-8">
                                        <div class="d-flex align-items-center"> Amount &nbsp; <input type="text" class="form-control" id="a4" name="amount4" value="{{$amount4}}" style="width:70%;" onfocus="showValidation('amount4')" onfocusout="document.getElementById('amount4').innerHTML = ''" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:92px;"> ₹ </div>
                                        <p id="amount4"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Withdrawal 5 </label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-4">
                                        <div class="d-flex align-items-center"> Period (Yrs)&nbsp; <input type="text" class="form-control" id="p5" name="period5" value="{{$period5}}" onChange="AddAnother(5)" style="width:70%;">
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:20px; top: 4px;"> Yrs </div>
                                      </div>
                                      <div class="col-sm-8">
                                        <div class="d-flex align-items-center"> Amount &nbsp; <input type="text" class="form-control" id="a5" name="amount5" value="{{$amount5}}" style="width:70%;" onfocus="showValidation('amount5')" onfocusout="document.getElementById('amount5').innerHTML = ''" readonly>
                                        </div>
                                        <div class="cal-icon" style="width:47px; right:92px;"> ₹ </div>
                                        <p id="amount5"></p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-sm-4 col-form-label">Report End Period</label>
                                  <div class="col-sm-8">
                                    <div class="row">
                                      <div class="col-sm-12">
                                        <div class="d-flex align-items-center">
                                          <input type="text" id="accval" class="form-control" name="lastwithdraw" value="{{$lastwithdraw}}" required="" style="width: 89%;">
                                        </div>
                                        <div class="cal-icon" style="width: 52px; right: 93px;"> Yrs </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>


                        

                        <div class="card sip-calculator singleLineHolder">
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
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="" />
        </div>
    </section>

@endsection
