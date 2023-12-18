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
                    url: "{{ route('frontend.stp_custom_transfer_output_save') }}",
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
                @php
               
                
                @endphp
                <div class="col-md-8 offset-md-2 text-center">

                    <a href="{{route('frontend.stp_custom_transfer_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
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
                            <a href="{{route('frontend.stp_custom_transfer_output_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif

                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>


                    <h5 class="mb-3">STP Comprehensive @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                    
                    @php
                    
                    
                    
                    if($transfermode == 1 && ($investmentperiod == 1 || $investmentperiod == 2) && ($reportcat == 2 || $reportcat == 1))
                    {
                        $calcType = 1;
                        $transferPeriod = $installments;
                        $debtFundBalance = $initial;
                        if($investmentperiod == 2)
                        $years = $months/12;
                        
                        if($transferfrequency == 'monthly')
                        {
                            $transferPeriodYrs = $transferPeriod/12;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 12;
                            $transferPeriodMonths = $transferPeriod;
                            $investmentPeriod = $years * 12;
                            $rateOfReturn = pow((1+$debt/100),(1/12))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/12))-1;
                            $eachTransferAmt = $initial * $rateOfReturn;
                            $equityfundVal = $eachTransferAmt * (pow((1+$rateOfReturnEq),($transferPeriod))-1)/$rateOfReturnEq;
                            $finalFutureValueDebt = $debtFundBalance * pow((1+$rateOfReturn),($investmentPeriod - $transferPeriod));
                            $finalFutureValueEquity = $equityfundVal * pow((1+$rateOfReturnEq),($investmentPeriod - $transferPeriod));
                            
                        }
                        else if($transferfrequency == 'quater')
                        {
                            $transferPeriodYrs = $transferPeriod/4;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 4;
                            $transferPeriodMonths = $transferPeriod*3;
                            $investmentPeriod = $years * 4;
                            $rateOfReturn = pow((1+$debt/100),(1/4))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/4))-1;
                            $eachTransferAmt = $initial * $rateOfReturn;
                            $equityfundVal = $eachTransferAmt * (pow((1+$rateOfReturnEq),($transferPeriod))-1)/$rateOfReturnEq;
                            $finalFutureValueDebt = $debtFundBalance * pow((1+$rateOfReturn),($investmentPeriod - $transferPeriod));
                            $finalFutureValueEquity = $equityfundVal * pow((1+$rateOfReturnEq),($investmentPeriod - $transferPeriod));
                        }
                        else if($transferfrequency == 'half-year')
                        {
                            $transferPeriodYrs = $transferPeriod/2;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 2;
                            $transferPeriodMonths = $transferPeriod * 6;
                            $investmentPeriod = $years * 2;
                            $rateOfReturn = pow((1+$debt/100),(1/2))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/2))-1;
                            $eachTransferAmt = $initial * $rateOfReturn;
                            $equityfundVal = $eachTransferAmt * (pow((1+$rateOfReturnEq),($transferPeriod))-1)/$rateOfReturnEq;
                            $finalFutureValueDebt = $debtFundBalance * pow((1+$rateOfReturn),($investmentPeriod - $transferPeriod));
                            $finalFutureValueEquity = $equityfundVal * pow((1+$rateOfReturnEq),($investmentPeriod - $transferPeriod));
                        }
                        else
                        {
                            $transferPeriodYrs = $transferPeriod;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 1;
                            $transferPeriodMonths = $transferPeriod * 12;
                            $investmentPeriod = $years;
                            $rateOfReturn = pow((1+$debt/100),(1/1))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/1))-1;
                            $eachTransferAmt = $initial * $rateOfReturn;
                            $equityfundVal = $eachTransferAmt * (pow((1+$rateOfReturnEq),($transferPeriod))-1)/$rateOfReturnEq;
                            $finalFutureValueDebt = $debtFundBalance * pow((1+$rateOfReturn),($investmentPeriod - $transferPeriod));
                            $finalFutureValueEquity = $equityfundVal * pow((1+$rateOfReturnEq),($investmentPeriod - $transferPeriod));
                        }
                        $rateOfReturnDebt = $rateOfReturn;
                        $rateOfReturnEquity = $rateOfReturnEq;
                        
                        
                        if($transferPeriodFrac > 0)
                        {
                            $minimumInvPeriodYrs = $transferPeriodYrs + 1;
                        }
                        else
                        {
                            $minimumInvPeriodYrs = $transferPeriodYrs;
                        }
                        
                        $totalFundValue = $finalFutureValueDebt + $finalFutureValueEquity;
                        $return = pow(($totalFundValue/$initial),(1/$investmentPeriod))-1;
                        $annualReturn = 0;
                        if($transferfrequency == 'monthly')
                        {
                            $annualReturn = pow((1+$return),12)-1;
                            //dd($annualReturn);
                            
                        }
                        else if($transferfrequency == 'quater')
                        {
                            $annualReturn = pow((1+$return),4)-1;
                        }
                        else if($transferfrequency == 'half-year')
                        {
                            $annualReturn = pow((1+$return),2)-1;
                        }
                        else
                        {
                            $annualReturn = pow((1+$return),1)-1;
                        }
                        $annualReturn = $annualReturn * 100;
                        
                        
                    }
                    else if($transfermode == 2 && ($investmentperiod == 1 || $investmentperiod == 2) && ($reportcat == 2 || $reportcat == 1))
                    {
                        $calcType = 2;
                        $transferPeriod = $installments;
                        $debtFundBalance = $initial;
                        if($investmentperiod == 2)
                        $years = $months/12;
                        
                        $fixedpercent = $fixedpercent / 100;
                        
                        if($transferfrequency == 'monthly')
                        {
                            $transferPeriodYrs = $transferPeriod/12;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 12;
                            $transferPeriodMonths = $transferPeriod;
                            $investmentPeriod = $years * 12;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/12))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/12))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $years * 12 - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                            
                            $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                        }
                        else if($transferfrequency == 'daily')
                        {
                        //$years = 2;
                        
                            $transferPeriodYrs = intval($transferPeriod/365);
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * 365;
                            $transferPeriodMonths = intval( $transferPeriod/30);
                            if($investmentperiod == "2")
                                $investmentPeriod = $months * 30;
                            else
                                $investmentPeriod = $years * 365;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/365))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/365))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $investmentPeriod - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                             //dd($totalFundValue);
                             //dd($rateOfReturn.','.$rateOfReturnEq.','.$maxPossibleSwp.','.$maxPossibleSwpInPercent.','.$pvOfTransfer.','.$debtFundBalance.','.$equityFundVal.','.$left.','.$finalFutreValueLeft.','.$finalFutureValueEquity);
                        }
                        elseif($transferfrequency == 'weekly')
                        {
                            $eno = 52;
                            $transferPeriodYrs = $transferPeriod/$eno;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * $eno;
                            $transferPeriodMonths = $transferPeriod/intval($eno/12);
                            if($investmentperiod == "2")
                                $investmentPeriod = $months * 4;
                            else
                                $investmentPeriod = $years * $eno;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/$eno))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/$eno))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $investmentPeriod - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                        }
                        elseif($transferfrequency == 'fortnight')
                        {
                            $eno = 24;
                            $transferPeriodYrs = $transferPeriod/$eno;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * $eno;
                            $transferPeriodMonths = $transferPeriod/intval($eno/12);
                            if($investmentperiod == "2")
                                $investmentPeriod = $months * 2;
                            else
                                $investmentPeriod = $years * $eno;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/$eno))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/$eno))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $investmentPeriod - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                            // dd($totalFundValue);
                        }
                        else if($transferfrequency == 'quater')
                        {
                            
                             $eno = 4;
                            $transferPeriodYrs = $transferPeriod/$eno;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * $eno;
                            $transferPeriodMonths = $transferPeriod*3;
                            $investmentPeriod = $years * $eno;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/$eno))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/$eno))-1;
                            //dd("yolo ".$rateOfReturnEq);
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $years * $eno - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                        }
                        else if($transferfrequency == 'half-year')
                        {
                            $eno = 2;
                            $transferPeriodYrs = $transferPeriod/$eno;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * $eno;
                            $transferPeriodMonths = $transferPeriod*6;
                            $investmentPeriod = $years * $eno;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/$eno))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/$eno))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $years * $eno - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                        }
                        else
                        {
                            $eno = 1;
                            $transferPeriodYrs = $transferPeriod/$eno;
                            $transferPeriodFrac = $transferPeriod - $transferPeriodYrs * $eno;
                            $transferPeriodMonths = $transferPeriod*12;
                            $investmentPeriod = $years * $eno;
                            if($transferPeriodFrac > 1)
                            $minimumInvestmentPeriod = $transferPeriodYrs + 1;
                            else
                            $minimumInvestmentPeriod = $transferPeriodYrs;
                            
                            $rateOfReturn = pow((1+$debt/100),(1/$eno))-1;
                            $rateOfReturnEq = pow((1+$equity/100),(1/$eno))-1;
                            $maxPossibleSwp = ($rateOfReturn * $initial) / (1-pow((1+$rateOfReturn),-$transferPeriod));
                            if($eachtransfer == 2)
                            {
                                $maxPossibleSwpInPercent = $maxPossibleSwp/$initial;
                            }
                            if($eachtransfer == 2)
                            {
                                $fixedamount = $initial * $fixedpercent;
                            }
                            //dd($fixedamount);
                            $eachTransferAmt = $fixedamount;
                            $pvOfTransfer = $fixedamount * (1-pow((1+$rateOfReturn),-$transferPeriod))/$rateOfReturn;
                            $debtFundBalance = ($initial - $pvOfTransfer)*pow((1+$rateOfReturn),$transferPeriod);
                            
                            $equityFundVal = $fixedamount * (pow((1+$rateOfReturnEq),$transferPeriod)-1)/$rateOfReturnEq;
                            $left = $years * $eno - $transferPeriod;
                            $finalFutreValueLeft = $debtFundBalance * pow((1+$rateOfReturn),$left);
                            $finalFutureValueEquity = $equityFundVal * pow((1+$rateOfReturnEq),$left);
                             $totalFundValue = $finalFutreValueLeft + $finalFutureValueEquity;
                        }
                        $rateOfReturnDebt = $rateOfReturn;
                        $rateOfReturnEquity = $rateOfReturnEq;
                        
                        
                        if($transferPeriodFrac > 0)
                        {
                            $minimumInvPeriodYrs = $transferPeriodYrs + 1;
                        }
                        else
                        {
                            $minimumInvPeriodYrs = $transferPeriodYrs;
                        }
                        
                        $annualReturn =pow(( $totalFundValue/$initial),(1/$years))-1;
                        $annualReturn *= 100;
                       // dd($annualReturn * 100);
                    }
                    @endphp
                    
                    <?php 
                    use App\Models\Calculatorfooter;
                    
                    $AllFooters = Calculatorfooter::select('*')->get();
                    $footmsg = "";
                    foreach($AllFooters as $af)
                    {
                        $alltypes = explode('~',$af->types);
                        
                        $totalInvTypes = ($alltypes[0] == "monthly")?"2":"1";
                        if($alltypes[0]==$transferfrequency && $alltypes[1]==$reportcat && $totalInvTypes == $investmentperiod )
                        {
                            $footmsg = $af->context;
                        }
                        
                    }
                    
                    ?>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                    <td>
                                        <strong>Initial Investment</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($initial)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Investment Period</strong>
                                    </td>
                                    <td>
                                        @if($investmentperiod == 1)
                                          {{$years}} Years
                                          @else
                                          {{$months}} Months
                                          @endif
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td style="vertical-align:middle;">
                                        <strong>
                                        Assumed Rate Of Return
                                        </strong>
                                    </td>
                                    <td>
                                        <table style="width:103.5%; margin-left:-5px; margin-top:-5px;margin-bottom:-5px;">
                                            <tr>
                                    <td style="border-top:0px; border-left:0px;">
                                        <strong>Debt Fund</strong>
                                    </td>
                                    <td style="border-top:0px; border-right:0px;">
                                          {{sprintf('%0.2f',$debt)}} %
                                    </td>
                                </tr>
                                <tr>
                                    <td style="border-bottom:0px; border-left:0px;">
                                        <strong>Equity Fund</strong>
                                    </td>
                                    <td style="border-bottom:0px; border-right:0px;">
                                          {{sprintf('%0.2f',$equity)}} %
                                    </td>
                                </tr>
                                        </table>
                                    </td>
                                    
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Transfer Mode</strong>
                                    </td>
                                    <td>
                                        @if($transfermode == 1)
                                          Captial Appreciation
                                          @else
                                          Fixed Amount
                                          @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Transfer Frequency</strong>
                                    </td>
                                    <td>
                                        @if($transferfrequency=='monthly')
                                          Monthly
                                          @elseif($transferfrequency=='daily')
                                          Daily
                                          @elseif($transferfrequency=='half-year')
                                          Half-Yearly
                                          @elseif($transferfrequency=='quater')
                                          Quarterly
                                           @elseif($transferfrequency=='weekly')
                                          Weekly
                                          @elseif($transferfrequency=='fortnight')
                                          Fortnightly
                                          @else
                                          Yearly
                                          @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Each Transfer Amount</strong>
                                    </td>
                                    <td>
                                        @if($transfermode == 2)
                                        ₹ {{custome_money_format($fixedamount)}}
                                        @else
                                         ₹ {{custome_money_format($eachTransferAmt)}}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>No. of Instalments</strong>
                                    </td>
                                    <td>
                                         {{$installments}}
                                    </td>
                                </tr>
                        </tbody>
                    </table>
                    <h5>Projected Future Value</h5>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                    <td>
                                        <strong>Debt Fund Value</strong>
                                    </td>
                                    <td>
                                        @if($transfermode == 2)
                                        ₹ {{custome_money_format($finalFutreValueLeft)}}
                                        @else
                                         ₹ {{custome_money_format($finalFutureValueDebt)}}
                                         @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Equity Fund Value</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($finalFutureValueEquity)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Total Fund Value</strong>
                                    </td>
                                    <td>
                                         ₹ {{custome_money_format($totalFundValue)}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>Annualised Returns</strong>
                                    </td>
                                    <td>
                                          {{sprintf('%0.2f',$annualReturn)}} %
                                    </td>
                                </tr>
                        </tbody>
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
                    
                    @if($report == 2)
                    @php
                    if($reportcat == 1)
                        $addon = "Month";
                        else
                        $addon = "Year";
                    @endphp
                    @if($calcType == 1 || $calcType == 2)
                    <h5>Projected Annual Investment Value</h5>
                    <table class="table table-bordered">
                        <tbody>
                       
                           <tr>
                               <td><strong>{{$addon}}</strong></td><td><strong>Debt Fund Value at the Beginning of {{$addon}}</strong></td><td><strong>Transfer to Equity Every {{$addon}}</strong></td><td><strong>Equity Fund Value at the Beginning of {{$addon}}</strong></td><td><strong>Equity Fund Value at the End of {{$addon}}</strong></td>
                           <td><strong>Total Value at the End of {{$addon}} (Debt+Equity)</strong></td><td><strong>Annualised Returns</strong></td>
                           </tr>
                       
                    
                    @php
                        $monthNo = 1;
                        
                        //dd($reportcat);
                        $showDebtVal = $initial;
                        if($transferfrequency == "monthly")
                        $noOfMonths = $installments;
                        else if($transferfrequency == "daily")
                        $noOfMonths = $installments/30;
                        else if($transferfrequency == "weekly")
                        $noOfMonths = $installments/4;
                        else if($transferfrequency == "fortnight")
                        $noOfMonths = $installments/2;
                        else if($transferfrequency == "half-year")
                        $noOfMonths = $installments*6;
                        else if($transferfrequency == "quater")
                        $noOfMonths = $installments*3;
                        else if($transferfrequency == "annualy")
                        $noOfMonths = $installments*12;
                        
                        $ins = $installments;
                        
                        //dd($noOfMonths);
                        $reamainingMonths = $noOfMonths;
                        if($calcType == 1)
                        $transferAmount = $eachTransferAmt;
                        else if($calcType == 2)
                        $transferAmount = $fixedamount;
                        
                        
                        $debtFundBow = $initial;
                        //dd($initial);
                        $debtRateOfReturn = $rateOfReturnDebt;
                        $appreciation = $debtFundBow * $debtRateOfReturn;
                        $balance = $debtFundBow + $appreciation;
                        if($ins > 0)
                            $transfer = $transferAmount;
                            else
                            $transfer = 0;
                            
                        $debtValEow = $debtFundBow;
                        //dd($debtValEow);
                        $equityBalEow = $transferAmount;
                        
                        $equityValAtBegin = 0;
                        if($calcType == 1){
                        $tot = $years*12;
                        $modWith = 12;
                        if($reportcat == "1")
                            {
                                $modWith = 1;
                            
                            }
                            
                        }
                        else{
                        if($transferfrequency=='daily'){
                        $tot = $years*365;
                        $modWith = 365;
                        if($reportcat == "1")
                        {
                            $modWith = intval(365/12);
                            
                        }
                        }
                        if($transferfrequency=='monthly')
                        {
                            $tot = $years*12;
                            $modWith = 12;
                            if($reportcat == "1")
                            {
                                $modWith = 1;
                            
                            }
                        }
                    
                        
                        
                        }
                        
                        if($transferfrequency == "monthly")
                        $divwith = 12;
                        
                        if($transferfrequency == "daily")
                         $divwith = 365;
                         
                         if($transferfrequency == "quater")
                         $divwith = 4;
                         
                         if($transferfrequency == "half-year")
                         $divwith = 2;
                         
                         if($transferfrequency == "weekly"){
                            $divwith = 48;
                         if($reportcat != "1")
                            $divwith = 52;
                         }
                         
                         if($transferfrequency == "annualy")
                         $divwith = 1;
                         
                         if($transferfrequency == "fortnight"){
                         $divwith = 24;
                         if($reportcat != "1")
                            $divwith = 24;
                         }
                         $modWith = $divwith;
                         
                         
                         if($reportcat == "1")
                          {
                            if($transferfrequency=='daily')
                            {
                                $modWith = 30;
                            }
                            else if($transferfrequency=='weekly')
                            {
                                $modWith = 4;
                            }
                            else if($transferfrequency=='fortnight')
                            {
                                $modWith = 2;
                            }
                            else
                            {
                                $modWith = 1;
                            }
                          }
                          
                          $tot = $years * $divwith;
                          
                          //dd($tot/$modWith);
                        
                        $count = 1;
                         
                         
                         
                         /*if($reportcat == "1" && $transferfrequency != 'daily'){
                         $debtValEow = $balance - $transfer;
                         $totalValAtEnd = $debtValEow + $equityBalEow;
                        // dd($transferAmount);
                         $annualReturn =pow(( $totalValAtEnd/$initial),(1/(1/12)))-1;
                            $annualReturn *= 100;
                            echo("<tr><td>1</td><td>₹ ".custome_money_format($showDebtVal)."</td><td>₹ ".custome_money_format($transferAmount)."</td><td>₹ ".custome_money_format($equityValAtBegin)."</td><td>₹ ".custome_money_format($equityBalEow)."</td><td>₹ ".custome_money_format($totalValAtEnd)."</td><td>".sprintf('%0.2f',$annualReturn)." %</td></tr>");
                            $equityValAtBegin = $equityBalEow;
                            $monthNo = 2; 
                        }*/
                        
                        $checkAmt = 0;
                        $yearCount = 0;
                        while($monthNo <= $tot)
                        {
                          
                            $debtFundBow = $debtValEow;
                            
                            $appreciation = $debtFundBow * $debtRateOfReturn;
                            $balance = $debtFundBow + $appreciation;
                            if($ins > 0)
                            $transfer = $transferAmount;
                            else
                            $transfer = 0;
                            
                            /*if($reportcat == "1")
                            {
                                if($monthNo <= $noOfMonths * 12)
                                $transfer = $transferAmount;
                                else
                                $transfer = 0;
                            }*/
                            
                            $debtValEow = $balance - $transfer;
                            
                           
                            
                            
                            //if($monthNo == 26)
                          //dd($equityBalEow);
                           
                           
                            $totalValAtEnd = $debtValEow + $equityBalEow;
                            
                            
                            
                             if($monthNo % $modWith == 0){
                             
                             
                             $yearCount++;
                             
                             if($reportcat == "1")
                             {
                             if($transferfrequency == "monthly")
                             $gofor =1;
                             if($transferfrequency == "daily")
                             $gofor =30;
                             if($transferfrequency == "weekly")
                             $gofor =4;
                             if($transferfrequency == "fortnight")
                             $gofor =2;
                             
                                if(($installments - $checkAmt) >= $gofor)
                                {
                                    $transferEveryYear = $transferAmount * $gofor;
                                }
                                else
                                {
                                    $transferEveryYear = $transferAmount * ($installments - $checkAmt);
                                }
                                
                                if($transferEveryYear < 0)
                                    $transferEveryYear = 0;
                                    
                                   $checkAmt += $gofor; 
                             }
                             else
                             {
                                if($transferfrequency == "monthly")
                             $gofor =12;
                             if($transferfrequency == "daily")
                             $gofor =365;
                             if($transferfrequency == "weekly")
                             $gofor =52;
                             if($transferfrequency == "fortnight")
                             $gofor =24;
                             if($transferfrequency == "half-year")
                             $gofor =2;
                             if($transferfrequency == "quater")
                             $gofor =4;
                             if($transferfrequency == "annualy")
                             $gofor = 1;
                             
                             
                             if(($installments - $checkAmt) >= $gofor)
                                {
                                    $transferEveryYear = $transferAmount * $gofor;
                                    
                                    //echo($gofor);
                                }
                                else
                                {
                                    $transferEveryYear = $transferAmount * ($installments - $checkAmt);
                                }
                                
                                if($transferEveryYear < 0)
                                    $transferEveryYear = 0;
                                    
                                   $checkAmt += $gofor; 
                             
                             }
                              
                            $annualReturn =pow(( $totalValAtEnd/$initial),(1/($monthNo/$divwith)))-1;
                            
                            if($reportcat == "1"){
                            $annualReturn =pow((1+(($totalValAtEnd - $initial)/$initial)),(12/($monthNo/$modWith)))-1;
                            }
                            
                            $annualReturn *= 100;
                            if($calcType == 1)
                            $showMonth = $monthNo/12;
                            else{
                            if($transferfrequency=='daily')
                            $showMonth = $monthNo/365;
                            else if($transferfrequency=='monthly')
                            $showMonth = $monthNo/12;
                            
                            }
                            if($reportcat == "1"){
                            
                                $showMonth = $monthNo/$modWith;
                            
                            
                            
                            }
                            else
                            {
                              $showMonth =  $yearCount;
                            }
                            
                            echo("<tr><td>".$showMonth."</td><td>₹ ".custome_money_format($showDebtVal)."</td><td>₹ ".custome_money_format($transferEveryYear)."</td><td>₹ ".custome_money_format($equityValAtBegin)."</td><td>₹ ".custome_money_format($equityBalEow)."</td><td>₹ ".custome_money_format($totalValAtEnd)."</td><td>".sprintf('%0.2f',$annualReturn)." %</td></tr>");
                            
                            $equityValAtBegin = $equityBalEow;
                            
                            
                            }
                            if($monthNo % ($modWith) == 0)
                                $showDebtVal = $debtValEow;
                                
                                
                            $monthNo++;
                            
                            
                            
                            if($reportcat != "1"){
                            if($yearCount >= $years)
                            break;
                            }
                            else
                            {
                            if($yearCount >= $years * 12)
                            break;
                            }
                            $ins--;
                            if($ins > 0)
                            $transfer = $transferAmount;
                            else
                            $transfer = 0;
                            $equityBalEow = $equityBalEow + $equityBalEow * $rateOfReturnEq + $transfer;
                            
                            
                        }
                    @endphp
                    </tbody>
                    </table>
                    @endif
                    @endif
                    </br>
                    * The above is for illustration purpose only. Report Date : {{date('d/m/Y')}}<br>
                    @include('frontend.calculators.suggested.output')
                    <div style="">
                        <p>
                       <br/>
        
                        @if($footmsg != '') * {{$footmsg}} @endif

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.stp_custom_transfer_output_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')
@endsection