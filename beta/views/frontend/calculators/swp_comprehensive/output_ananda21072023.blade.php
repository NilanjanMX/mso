@extends('layouts.frontend')
@section('js_after')
<script>
    jQuery(document).ready(function(){
        jQuery('#save_cal_btn').click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            var title = jQuery('#save_title').val();
            if(title.trim()==''){
                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                jQuery('#save_cal_msg').addClass('alert-danger');
                jQuery('#save_cal_msg').html('Please Enter Desired Download File Name');
            }else{
                jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                jQuery('#save_cal_msg').html('');
                jQuery('#save_title').val('');
                jQuery.ajax({
                    url: "{{ route('frontend.swp_comprehension_output_save') }}",
                    method: 'get',
                    data: {
                        title: title
                    },
                    success: function(result){
                        jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                        jQuery('#save_cal_msg').addClass('alert-success');
                        jQuery('#save_cal_msg').html('Data Successfully Saved');
                        setTimeout(function () {
                            $('#saveOutput').modal('toggle');
                            jQuery('#save_cal_msg').removeClass('alert-success alert-danger');
                            jQuery('#save_cal_msg').html('');
                        },500);
                        jQuery('.save_only').hide();
                        jQuery('.view_save_only').show();
                    }});
            }

        });
    });
</script>
@endsection
@section('content')

    <div class="banner styleApril">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div>
        </div>
    </div>

    
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <a href="{{route('frontend.swp_comprehension')}}" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @if($permission['is_save'])
                        @if($edit_id)
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.swp_comprehension_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>


                    <h5 class="mb-3">@if(isset($details)) {{$details}} @else SWP Calculation @endif @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                    

                    
                        @php 
                        //use DB;
                        $calctype = '';
                        if($investmentmode == 1 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                        //dd("first calc");
                        $calctype = 1;
                            
                                $swpAmt = $swpamount;
                            
                                
                                
                                
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                                
                            
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($initial * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            $totalMonths = $mainMonths;
                            
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  2;
                            
                                $swpAmt = $swpamount;
                            
                           
                            $mainMonths = 0;
                            $inter = 1;
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            //dd($balanceAvailable);
                        }
                      else  if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 3;
                            
                                $swpAmt = $swpamount;
                           // dd($swpAmt);
                                
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $initial * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $initial * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                       else if($investmentmode == 1 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                       // dd("fourth calc");
                        $calctype = 4;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($initial * ($swpamount/100))/12;
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                }
                            
                            $annualRateOfReturn = $monthlyRateOfReturn;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $initial - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($initial - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$initial * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("fifth calc");
                        $calctype = 5;
                            
                                $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                           
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                       else if($investmentmode == 1 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 6;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 2 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 7;
                            
                                $swpAmt = $swpamount;
                            
                                
                                
                                
                            
                            $mainMonths = 0;
                            $inter = 1;
                            $addonText = "";
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            
                        }
                       else if($investmentmode == 2 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            //dd("sec calc");
                            $calctype =  8;
                            
                                $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                            //dd($balanceAvailable);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 9;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            //dd($accumulated);
                        }
                        else if($investmentmode == 2 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 10;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 11;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        else if($investmentmode == 2 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                        //dd("sixth calc");
                        $calctype = 12;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                        
                        else if($investmentmode == 3 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 13;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  14;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                        //dd("third calc");
                        $calctype = 15;
                        
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($actotal1 == $inpercent)
                            {
                            //echo("Coming 1");
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                            //echo("Coming 2");
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            //dd($totalMonths." ".$total1);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                            
                        }
                        else  if($investmentmode == 3 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 16;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 17;
                             $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        
                        else if($investmentmode == 3 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 18;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                            //dd($balanceAvailable);
                        }
                       else if($investmentmode == 4 && $def==0 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype = 19;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                            
                            $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "1" && $taxation == 1)
                        {
                            $calctype =  20;
                            $swpAmt = $swpamount;
                            
                           
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                
                                }
                              
                             $lumpsumforAnnuity = $swpAmt * (1-pow((1+$monthlyRateOfReturn),(-$totalMonths)))/$monthlyRateOfReturn;
                             
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            
                            $maxMoneyBack =round( ($accumulated * $monthlyRateOfReturn)/(1-pow((1+$monthlyRateOfReturn),-$totalMonths)));
                            
                            $maxMoneyBackPercent = ($maxMoneyBack/$accumulated * 12)*100;
                            
                            
                            $totalWithdrawal = $swpAmt * $totalMonths;
                        }
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 21;
                        
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($actotal1 == $inpercent)
                            {
                            //echo("Coming 1");
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                            //echo("Coming 2");
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                            //dd($totalMonths." ".$total1);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($actotal1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        
                        else if($investmentmode == 4 && $def==0 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 22;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 0)
                        {
                            $calctype = 23;
                            $swpAmt = $swpamount;
                            
                                
                            if($withdrawtype == "month"){
                                $totalMonths = $swp * 12;
                                $mainMonths = $totalMonths;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/12))-1;
                                $inter = 1;
                                $addonText = "Monthly";
                                $annuityPeriod = $swp * 12;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/12))-1;
                            }
                                }
                            else if($withdrawtype == "quater"){
                                $totalMonths = $swp * 4;
                                $mainMonths = $totalMonths * 3;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/4))-1;
                                $inter = 3;
                                $addonText = "Quaterly";
                                $annuityPeriod = $swp * 4;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/4))-1;
                            }
                                }
                            else if($withdrawtype == "half"){
                                $totalMonths = $swp * 2;
                                $mainMonths = $totalMonths * 6;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/2))-1;
                                $inter = 6;
                                $addonText = "Half Yearly";
                                $annuityPeriod = $swp * 2;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/2))-1;
                            }
                                }
                            else if($withdrawtype == "year"){
                                $totalMonths = $swp * 1;
                                $mainMonths = $totalMonths * 12;
                                $monthlyRateOfReturn = pow((1+$total1/100),(1/1))-1;
                                $inter = 12;
                                $addonText = "Yearly";
                                $annuityPeriod = $swp * 1;
                                 if($incrtype == 0){
                                $monthlyInflation =pow( (1+$inpercent/100),(1/1))-1;
                            }
                                }
                            
                            if($total1 == $inpercent)
                            {
                                $lumpsumforAnnuity = $swpAmt * $totalMonths/(1+$monthlyRateOfReturn);
                            }
                            else
                            {
                                $lumpsumforAnnuity = $swpAmt * ((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))/($monthlyRateOfReturn-$monthlyInflation)); 
                            }
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = $lumpsumForBalance * pow((1+$monthlyRateOfReturn),$totalMonths);
                            if($total1 == $inpercent)
                                $maxMoneyBack = round( $accumulated * (1+$monthlyRateOfReturn)/$totalMonths);
                            else
                            {
                                $maxMoneyBack = round( $accumulated * ($monthlyRateOfReturn - $monthlyInflation)/((1-(pow((1+$monthlyInflation),$totalMonths))*(pow((1+$monthlyRateOfReturn),-$totalMonths)))));
                            }
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $totalWithdrawal = $swpAmt * ((pow((1+$monthlyInflation),$totalMonths)-1)/((1+$monthlyInflation)-1));
                        }
                        else if($investmentmode == 4 && $def==1 && $annualincr == "2" && $taxation == 1 && $incrtype == 1)
                        {
                            $calctype = 24;
                            if($swptype=="inmonth")
                                $swpAmt = $swpamount;
                            else
                                $swpAmt = ($accumulated * ($swpamount/100))/12;
                                
                            $totalMonths = $swp * 12;
                            $annuityPeriod = $swp;
                            $annualRateOfReturn = (pow((1+$total1/100),(1/12))-1)*12;
                            $annualIncr = $inamount;
                            
                            $p = $swpAmt * 12 - $annualIncr*12;
                            $c = $annualIncr * 12;
                            $k = $annualRateOfReturn;
                            $n = $annuityPeriod;
                            
                            $factor1 = $p;
                            $factor2 = (pow((1+$k),$n)-1)/(pow((1+$k),$n) * $k);
                            $factor3 = $c;
                            $factor4 = (pow((1+$k),$n)-1)/(pow((1+$k),($n-1))*pow($k,2));
                            $factor5 = $n/(pow((1+$k),$n)*$k);
                            
                            $lumpsumforAnnuity =round( $factor1 * $factor2 + $factor3 * ($factor4 - $factor5));
                            
                           // dd($lumpsumforAnnuity);
                            $lumpsumForBalance = $accumulated - $lumpsumforAnnuity;
                            $balanceAvailable = round($lumpsumForBalance * pow((1+$annualRateOfReturn),$annuityPeriod));
                            $maxMoneyBack = round( ($accumulated - $factor3 * ($factor4 - $factor5))/$factor2);
                                
                            $maxMoneyBackInMonthly = round($maxMoneyBack/12);
                            $maxMoneyBackPercent = $maxMoneyBack/$accumulated * 12;
                            $fstyearWithdraw = $swpAmt * 12;
                            $sndyearWithdraw = $swpAmt * 12 + ($annualIncr * 12) * ($annuityPeriod-1);
                            $totalWithdrawal = (($fstyearWithdraw + $sndyearWithdraw)/2) * $annuityPeriod;
                        }
                        
                        else
                        {
                            dd("else");
                            
                        }
                        
                        
                        $data = DB::table('aaswpcomprehensive')->select('*')->first();
                        
                        //dd($data);
                        
                        if($calctype == 3 || $calctype == 4 || $calctype == 5 || $calctype == 6 ||$calctype == 9 ||$calctype == 10 ||$calctype == 11 ||$calctype == 12 ||$calctype == 15 ||$calctype == 16 ||$calctype == 17 ||$calctype == 18 ||$calctype == 21 ||$calctype == 22 ||$calctype == 23 ||$calctype == 24)
                        {
                        
                            $footNote = $data->footer;
                        
                        }
                        
                        @endphp
                        
                        <table class="table table-bordered">
                            <tbody>
                                @if($currentage > 0)
                                 <tr>
                                    <td>
                                        <strong>Current Age</strong>
                                    </td>
                                    <td>
                                          {{$currentage}} Yrs
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        @if($investmentmode==2)
                                        <strong>Annual Investment</strong>
                                        @elseif($investmentmode==3)
                                        <strong>SIP Investment</strong>
                                        @elseif($investmentmode==4)
                                        <strong>Lumpsum Investment</strong>
                                        @else
                                        <strong>Initial Investment</strong>
                                        @endif
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($initial)}}
                                    </td>
                                </tr>
                                @if($investmentmode==4)
                                <tr>
                                    <td>
                                        <strong>SIP Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($sipamt)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SIP Period</strong>
                                    </td>
                                    <td>
                                         {{$invperiod}} Yrs
                                    </td>
                                </tr>
                                @endif
                                 @if($investmentmode==2 || $investmentmode==3)
                                 <tr>
                                    <td>
                                        <strong>Payment Period</strong>
                                    </td>
                                    <td>
                                          {{$invperiod}} Yrs
                                    </td>
                                </tr>
                                 @endif
                                 @if($def==1)
                                <tr>
                                    <td>
                                        <strong>Deferment Period</strong>
                                    </td>
                                    <td>
                                          {{$defermentperiod}} Yrs
                                    </td>
                                </tr>
                                
                                @endif
                                 @if($calctype==2 || $calctype==5|| $calctype==6 ||$calctype==7 || $calctype==8 || $calctype==9 || $calctype==10 || $calctype==11 || $calctype==12 || $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18)
                                <tr>
                                    <td>
                                        <strong>Accumulated Corpus</strong>
                                    </td>
                                    <td>
                                          ₹ {{custome_money_format($accumulated)}}
                                    </td>
                                </tr>
                                @endif
                                @if($calctype == 4 || $calctype == 5)
                                <tr>
                                    <td>
                                        <strong> {{$addonText}} SWP Amount</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($swpAmt)}}
                                    </td>
                                </tr>
                                @endif
                                @if($annualincr == "2")
                                <tr>
                                    <td>
                                        <strong> Annual Increment</strong>
                                    </td>
                                    <td>
                                        @if($incrtype == 0)
                                          {{$inpercent}} %
                                         @else
                                         ₹ {{custome_money_format($inamount)}}
                                         @endif
                                    </td>
                                </tr>
                                @endif
                                
                                
                                <tr>
                                    <td>
                                        <strong>{{$addonText}} SWP Amount</strong>
                                    </td>
                                    <td>
                                        ₹ {{custome_money_format($swpAmt)}}
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>SWP Period</strong>
                                    </td>
                                    <td>
                                        {{$swp}} Years
                                    </td>
                                </tr>
                               
                                
                            </tbody>
                        </table>
                        <br/>
                        <h5>Suggested Asset Allocation</h5>
                        @if($calctype==2 || $calctype==5|| $calctype==6 || $calctype==7|| $calctype==8 || $calctype==9 || $calctype==10|| $calctype==11 || $calctype==12|| $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18)<br/> <h6>Accumulation Phase</h6> 
                        
                        <table class="table table-bordered">
                            <tr><td>Asset Class</td><td>Allocation</td><td>Assumed Return</td></tr>
                            @if($acequity > 0)
                            <tr>
                                    <td>
                                        <strong>Equity</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$acequity)}} %
                                    </td>
                                    <td>
                                     @if($acequity > 0)   {{sprintf('%0.2f',$acequity1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($achybrid > 0)
                                <tr>
                                    <td>
                                        <strong>Hybrid</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$achybrid)}} %
                                    </td>
                                    <td>
                                       @if($achybrid > 0)  {{sprintf('%0.2f',$achybrid1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($acdebt > 0)
                                <tr>
                                    <td>
                                        <strong>Debt</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$acdebt)}} %
                                    </td>
                                    <td>
                                       @if($acdebt1 > 0) {{sprintf('%0.2f',$acdebt1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                 <tr>
                                    <td>
                                        <strong>Portfolio</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$actotal)}} %
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$actotal1)}} %
                                    </td>
                                </tr>
                        </table>
                        @endif
                        @if($calctype==2 || $calctype==5 || $calctype==6 || $calctype==7 || $calctype==8 || $calctype==9 || $calctype==10|| $calctype==11 || $calctype==12|| $calctype==13|| $calctype==14|| $calctype==15|| $calctype==16 || $calctype==17 || $calctype==18 || $calctype>18) <h6>Distribution Phase</h6>
                         @endif
                        <table class="table table-bordered">
                            <tr><td>Asset Class</td><td>Allocation</td><td>Assumed Return</td></tr>
                            @if($equity > 0)
                            <tr>
                                    <td>
                                        <strong>Equity</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$equity)}} %
                                    </td>
                                    <td>
                                       @if($equity1 > 0) {{sprintf('%0.2f',$equity1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($hybrid > 0)
                                <tr>
                                    <td>
                                        <strong>Hybrid</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$hybrid)}} %
                                    </td>
                                    <td>
                                       @if($hybrid1 > 0) {{sprintf('%0.2f',$hybrid1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                @if($debt > 0)
                                <tr>
                                    <td>
                                        <strong>Debt</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$debt)}} %
                                    </td>
                                    <td>
                                      @if($debt1 > 0)  {{sprintf('%0.2f',$debt1)}} % @else - @endif
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td>
                                        <strong>Portfolio</strong>
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$total)}} %
                                    </td>
                                    <td>
                                        {{sprintf('%0.2f',$total1)}} %
                                    </td>
                                </tr>
                        </table>

                        @if($is_note == 1 && isset($note) && !empty($note))
                            <h1 class="midheading">Comments</h1>
                            <div class="roundBorderHolder">
                                <table class="table table-bordered text-center">
                                    <tbody>
                                        <tr>
                                            <td>{{$note}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Total Withdrawal</h5>

                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                            ">
                                ₹ {{custome_money_format($totalWithdrawal)}}
                        </h5>
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">End Value</h5>

                        <h5 style="
                                margin: 0 auto;
                                padding: 12px 10px;
                                max-width: 237px;
                                border: 1px solid #ccc;
                            ">
                                ₹ {{custome_money_format($balanceAvailable)}}
                        </h5>
                        
                        @if($report == 2)
                        
                        @if($calctype==1)
                        
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                        <br/>
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            
                            $month = 1;
                            $openingBal = $initial;
                            
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            //dd($monthlyRateOfReturn);
                            for($month = 1; $month <= ($swp * 12); $month++)
                            {
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                
                                if($month >= 12){
                                if($month % 12 ==0){
                                
                                echo("<tr><td>".$age."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                
                                }
                                
                                
                                }
                                
                                if($month % $inter == 0)
                                {
                                    $openingBal = $yearEnd;
                                }
                                
                                
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==2)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            while($defPeriod >= $year)
                            {

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($lumpsumInv))."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr><td>Year</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $month = 1;
                            $addYear = $year-1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $currentage += $addYear;
                            $year=1;
                            $totalMonths = $swp * 12;
                            while($year <= $swp)
                            {
                                
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                        @endif
                        @if($calctype==3)
                        <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            <br/>
                            <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Average {{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                @php
                                $swpperiod = $swp * 12;
                                
                                //dd($swpperiod);
                                
                                $month = 1;
                                $age = $currentage;
                                $openingBal = $initial;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                //dd($inter);
                                while($month<=$swpperiod)
                                {
                                if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        echo("<tr><td>".++$age."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                             @if($calctype==4)
                             <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $initial;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".$mainAge."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        @endif
                        
                        @if($calctype==5)
                             <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                             <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            while($defPeriod >= $year)
                            {

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($lumpsumInv))."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Projected {{$addonText}} Withdrawal & Fund Value</h5>
                            <br/>
                            <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                
                                @php
                                $addYear = $year-1;
                                $swpperiod = $totalMonths;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=($swp * 12))
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                        @endif
                        @if($calctype==6)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>Year</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $lumpsumInv = $initial;
                            $rateOfReturn = $actotal1;
                            $age = $currentage;
                            $onetime = false;
                            while($defPeriod >= $year)
                            {

                                $yearEndVal = $lumpsumInv * (1+$rateOfReturn/100);
                                if(!$onetime){
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($lumpsumInv))."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                $onetime = true;
                                }
                                else
                                {
                                    echo("<tr><td>".($year + $currentage)."</td><td>".'-'."</td><td>₹".custome_money_format(round($yearEndVal))."</td></tr>");
                                }
                                
                                $year++;
                                $lumpsumInv = $yearEndVal;
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                        @endif
                        
                        @if($calctype==7)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($age + $currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            
                             @if($calctype==8)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $swp-$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            while($year<=($defPeriod + $invperiod))
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                
                                if(($payPer + $deferApplied)>($defPeriod + $invperiod))
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                               
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year+$currentage)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                        @endif
                        
                        @if($calctype==9)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $totalMonths;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swp * 12)
                                {
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==10)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($currentage + $year)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                            @endif
                            
                             @if($calctype==11)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year + $currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==12)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = $actotal1;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn/100) * (pow((1+$rateOfReturn/100),$year)-1)/($rateOfReturn/100);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturn/100),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
    
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                             <br/>
                             <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Year @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==13)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:5px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested * 12))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                             
                             if($month % $inter == 0){   
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($age+$currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==14)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested * 12))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             <br/>
                             <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==15)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested * 12))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==16)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($yearendVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($currentage + $year)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                            @endif
                            @if($calctype==17)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested * 12))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$year+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==18)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $initial;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            //dd($swp);
                            while($year<=$invPer)
                            {

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                if($year <= $payPer)
                                echo("<tr><td>".($currentage+$year)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                else
                                echo("<tr><td>".($currentage+$year)."</td><td>-</td><td>₹".custome_money_format(round($yearEndValue))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                            @endif
                            @if($calctype==19)
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $annualLumpsum = $initial;
                            $lumpsum = '';
                            $paymentPer = $invperiod;
                            $forFirstInv = ($initial + $sipamt * 12);
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $yearendVal = '';
                            $yearEndValLumpsum = '';
                            while($year<=$paymentPer)
                            {
                                $amountInvested = $sipamt;
                                $lumpsum = $annualLumpsum;
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndValue = $yearendVal + $yearEndValLumpsum;
                                
                                if($year == 1)
                                $amountInvested = $forFirstInv / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndValue))."</td></tr>");
                                
                                
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr><td>Age</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = 1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($age+$currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==20)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $prevYearEnd = 1;
                            $mainAge = $currentage + $year;
                            $annualLumpsum = $initial;
                            $lumpsum = $annualLumpsum;
                            $yearEndValLumpsum = '';
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($year + $currentage)."</td><td>-</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $mainAge = $currentage + $year;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                             <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                             
                             <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                            $month = 1;
                            $openingBal = $accumulated;
                            $monthlySwp = $swpAmt;
                            $age = $currentage+1;
                            $year=1;
                            $totalMonths = $swp * 12;
                            $currentage += $addYear;
                            while($year <= $swp)
                            {
                                if($month % $inter == 0){
                                if($month <= $totalMonths)
                                    $monthlySwp = $monthlySwp;
                                else
                                    $monthlySwp = '-';
                                
                                if($month <= $totalMonths)
                                    $yearEnd = $openingBal+$openingBal*$monthlyRateOfReturn-$monthlySwp;
                                else
                                    $yearEnd = '-';
                                    }
                                
                                
                                if($month % 12 ==0){
                                echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($monthlySwp))."</td><td>₹".custome_money_format(round($yearEnd))."</td></tr>");
                                $age++;
                                $year++;
                                }
                                
                                if($month % $inter == 0){
                                $openingBal = $yearEnd;
                                }
                                $month++;
                                
                            }
                            
                            @endphp
                            </table>
                            @endif
                            @if($calctype==21)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $lumpsum = $initial;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $yearendVal = '';
                            $yearEndValLumpsum = '';
                            while($year<=$paymentPer)
                            {
                                $amountInvested = $sipamt;
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearendVal + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        
                                        
                                        
                                        if($currentage>0)
                                        echo("<tr><td>". (++$age+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$age+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==22)
                        <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $age = $currentage+$year;
                            $paymentPer = $invperiod;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $lumpsum = $initial;
                            $yearendVal = '';
                            while($year<=$paymentPer)
                            {
                                $amountInvested = $sipamt;
                                $yearendVal = $amountInvested * (1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearendVal + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                $age++;
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                            @endif
                            @if($calctype==23)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            <br/>
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $onetime = false;
                            $lumpsum = $initial;
                            $prevYearEnd = 1;
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($year + $currentage)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($year+$currentage)."</td><td>-</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected {{$addonText}} Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>{{$addonText}} SWP Amount</td><td>Fund Value at End of Year</td></tr>
                            @php
                            $addYear = $year-1;
                                $swpperiod = $swp * 12;
                                $month = 1;
                                $age = 0;
                                $year = 0;
                                $openingBal = $accumulated;
                                $monthlyRate = $monthlyRateOfReturn;
                                $eomVal = $openingBal + $openingBal * $monthlyRate;
                                $monthlyInf = $monthlyInflation;
                                $monthlySw = $swpAmt;
                                $store = 0;
                                $isYear = false;
                                $currentage += $addYear;
                                while($month<=$swpperiod)
                                {
                                
                                    if($month % $inter == 0){
                                    $store += $monthlySw;
                                    $val = $openingBal + $openingBal * $monthlyRate-$monthlySw;
                                    }
                                    
                                    if($month % 12 == 0)
                                    {
                                        $avgMonth = $store/(12 / $inter);
                                        if($currentage>0)
                                        echo("<tr><td>". (++$year+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        else
                                        echo("<tr><td>".(++$year+$currentage)."</td><td>₹".custome_money_format(round($avgMonth))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                        $isYear = true;
                                        $store = 0;
                                    }
                                    
                                    if($month % $inter == 0){
                                    $monthlySw = $monthlySw + $monthlySw * $monthlyInf;
                                    
                                    $openingBal = $val;
                                    }
                                    $month++;
                                }
                                
                                @endphp
                            </table>
                            @endif
                            @if($calctype==24)
                            <br/>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Accumulation Phase<br/>Projected Annual Investment Value</h5>
                            
                            <table class="table table-bordered">
                            <tr><td>@if($currentage>0) Age @else Year @endif</td><td>Annual Investment</td><td>Year End Value</td></tr>
                            @php
                            $year = 1;
                            $defPeriod = $defermentperiod;
                            $invPer = $defPeriod+$invperiod;
                            $payPer = $invperiod;
                            $deferApplied = 0;
                            $deferYear = 0;
                            $amountInvested = $sipamt;
                            $rateOfReturn = pow((1+$actotal1/100),(1/12))-1;
                            $rateOfReturnAnnual = $actotal1/100;
                            $lumpsum = $initial;
                            $onetime = false;
                            $prevYearEnd = 1;
                            //dd($swp);
                            while($year<=$invPer)
                            {
                                $amountInvested = $sipamt;

                                if($year <= $payPer)
                                {
                                    $futureValue = $amountInvested *(1+$rateOfReturn) * (pow((1+$rateOfReturn),($year*12))-1)/($rateOfReturn);
                                }
                                else
                                {
                                    $deferApplied++;
                                    $futureValue = '-';
                                }
                                
                                if(($payPer + $deferApplied)>$invPer)
                                {
                                    $deferYear = 0;
                                }
                                else
                                {
                                    $deferYear = $deferApplied;
                                }
                                
                                if($deferYear == 0)
                                {
                                    $yearEndValue = $futureValue;
                                    //dd("f:".$yearEndValue);
                                }
                                else
                                {
                                    if($deferYear > 0)
                                    {
                                        $yearEndValue = $prevYearEnd * pow((1+$rateOfReturnAnnual),1);
                                        //dd("s:".$yearEndValue);
                                    }
                                    else
                                    {
                                        $yearEndValue = 0;
                                        //dd("t:".$yearEndValue);
                                    }
                                }
                                
                                $prevYearEnd = $yearEndValue;
                                $yearEndValLumpsum = $lumpsum * pow((1+$rateOfReturnAnnual),$year);
                                $totalYearEndVal = $yearEndValue + $yearEndValLumpsum;
                                
                                if($year == 1)
                                $amountInvested = ($initial + ($sipamt * 12)) / 12;
                                
                                if($year <= $payPer)
                                echo("<tr><td>".($currentage+$year)."</td><td>₹".custome_money_format(round($amountInvested*12))."</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                else
                                echo("<tr><td>".($currentage+$year)."</td><td>-</td><td>₹".custome_money_format(round($totalYearEndVal))."</td></tr>");
                                $year++;
                                //$lumpsumInv = $yearEndVal;
                                
                                
                            }
                            @endphp
                            </table>
                            <br/>
                        <h5 style="color: #131f55;font-size:22px;margin-bottom:20px; text-align:center;">Distribution Phase<br/>Projected Monthly Withdrawal & Fund Value</h5>
                        
                        <table class="table table-bordered">
                                <tr><td>@if($currentage>0) Year @else Year @endif</td><td>Average Monthly Withdrawal Amount</td><td>Fund Value at End of Year</td></tr>
                                @php
                                $addYear = $year-1;
                                $swpPer = $swp;
                                $year = 1;
                                $openingBal = $accumulated;
                                $annualRate = $annualRateOfReturn;
                                $eoyVal = $openingBal+$openingBal*$annualRate;
                                $annualIncrement = $inamount;
                                $annualSwp = $swpAmt * 12;
                                $store = $annualSwp;
                                $mainAge = $currentage + $year;
                                $currentage += $addYear;
                                while($year <= $swpPer)
                                {
                                    $val = $openingBal + $openingBal * $annualRate-$annualSwp;
                                    echo("<tr><td>".($year+$currentage)."</td><td>₹".custome_money_format(round($annualSwp/12))."</td><td>₹".custome_money_format(round($val))."</td></tr>");
                                    $year++;
                                    $annualSwp = $store+$annualIncrement*12;
                                    $store = $annualSwp;
                                    $openingBal = $val;
                                    $mainAge = $currentage + $year;
                                }
                                
                                @endphp
                        <br/>
                        </table>
                            @endif
                            @endif
                    <p></p>
                    

                    @include('frontend.calculators.suggested.output')
                    <br/>
                    <div style="">
                        <p>
                            
                        
                        
        <br/>
        @if(isset($footNote))
        * {{$footNote}}
        @endif
        * The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}<br>
        
        
        </p>
        </div>
        <br/><br/>
                    
                    <a href="{{route('frontend.swp_comprehension')}}" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    
                    @if($permission['is_save'])
                        @if($edit_id)
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Update</a>
                        @else
                            <a id="save_only" href="javascript:void(0)"  class="btn btn-primary btn-round save_only" data-toggle="modal" data-target="#saveOutput">Save</a>
                        @endif
                    @else
                        <a href="javascript:void(0)"  class="btn btn-primary btn-round" onclick="openSavePermissionModal();">Save</a>
                    @endif
                    @if($permission['is_download'])
                        @if($permission['is_cover'])
                            <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @else
                            <a href="{{route('frontend.swp_comprehension_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>

                </div>
            </div>
        </div>
        
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt="">
        </div>
        
    </section>


    <script type="text/javascript">
        var base_url = "{{route('frontend.swp_comprehension_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection

