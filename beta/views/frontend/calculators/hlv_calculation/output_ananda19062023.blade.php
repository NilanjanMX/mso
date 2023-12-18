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
                        url: "{{ route('frontend.hlv_calculation_save') }}",
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
    <style>
        
    </style>
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
    
    <div class="banner styleApril">
        <div class="container">
        @include('frontend.calculators.left_sidebar')
            <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">Human Life Value Calculator</h2>
                </div>
            </div>
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-10 offset-md-1 text-center">
                    @if($edit_id)
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @else
                        <a href="{{route('frontend.hlv_calculation_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                    @endif
                    
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
                            <a href="{{route('frontend.hlv_calculation_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print</a>
                        @endif
                    @else
                        <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print</a>
                    @endif
                    <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>



                    <div class="outputTableHolder">
                        <h5 class="midheading">Human Life Value Calculation @if(isset($clientname) && !empty($clientname)) For {{$clientname?$clientname:''}}  @else  @endif</h5>
                        <div class="roundBorderHolder">
                            @if($formType == 1)
                            @php 
                            $net = $anual - $personal;
                            if($expected == $discount)
                                $humanLifeValue = $net * ($retire-$current) / (1+$discount/100);
                            else
                                $humanLifeValue =  $net * (1-(pow(1+$expected/100,($retire-$current))*pow(1+$discount/100 , -($retire-$current))))/(($discount/100)-($expected/100));  
                                   
                            @endphp
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Current Age</strong>
                                        </td>
                                        <td>
                                            {{$current}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Retirement Age</strong>
                                        </td>
                                        <td>
                                            {{$retire}}  Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Discounting Rate</strong>
                                        </td>
                                        <td>
                                            {{$discount}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Annual Income</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($anual)}}
                                        </td>
                                    </tr>
                                    @if($expected != '')
                                    <tr>
                                        <td>
                                            <strong>Expected Annual Increment</strong>
                                        </td>
                                        <td>
                                        @if($expected != '') {{$expected}} % @else 0 % @endif
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if($personal != '')
                                    <tr>
                                        <td>
                                            <strong>Personal Expenses</strong>
                                        </td>
                                        <td>
                                        @if($personal != '') ₹ {{custome_money_format($personal)}} @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <h5 style="color: #131f55;font-size:22px;margin-bottom:20px;">Human Life Value</h5>

                            <h5 style="
                                    margin: 0 auto;
                                    padding: 12px 10px;
                                    max-width: 237px;
                                    border: 1px solid #ccc;
                                ">
                                    ₹ {{custome_money_format($humanLifeValue)}}
                            </h5>
                            <br>
                            
                            *Income Replacement Method

                            @else
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Current Age</strong>
                                        </td>
                                        <td>
                                            {{$current}} Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Retirement Age</strong>
                                        </td>
                                        <td>
                                            {{$retire}}  Years
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Age of Spouse</strong>
                                        </td>
                                        <td>
                                            {{$spouse}}  Years
                                        </td>
                                    </tr>
                                    @if(isset($annuity))
                                <tr>
                                        <td>
                                            <strong> Annuity Ends at Age</strong>
                                        </td>
                                        <td>
                                            {{$annuity}} Years
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td>
                                            <strong>Discounting Rate</strong>
                                        </td>
                                        <td>
                                            {{$discount}}  %
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <strong>Current Annual Income</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($anual)}}
                                        </td>
                                    </tr>
                                    @if($expected != '')
                                    <tr>
                                        <td>
                                            <strong>Expected Annual Increment</strong>
                                        </td>
                                        <td>
                                        @if($expected != '') {{$expected}} % @else 0 % @endif
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <br />
                            <table class="table table-bordered">
                                @if(isset($household))
                                <tr>
                                        <td>
                                            <strong> Annual Household Expenses</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($household)}}
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($anualincrement))
                                <tr>
                                        <td>
                                            <strong> Expected Annual Increment</strong>
                                        </td>
                                        <td>
                                            {{$anualincrement}} %
                                        </td>
                                    </tr>
                                    @endif
                                    @if(isset($anualretire))
                                <tr>
                                        <td>
                                            <strong> Annual Retirement Expenses For Spouse</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($anualretire)}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(isset($inflation))
                                <tr>
                                        <td>
                                            <strong> Assumed Inflation Rate</strong>
                                        </td>
                                        <td>
                                            {{$inflation}} %
                                        </td>
                                    </tr>
                                    @endif
                                    @if(isset($rateofreturn))
                                <tr>
                                        <td>
                                            <strong> Assumed Rate of Return (Distribution Period)</strong>
                                        </td>
                                        <td>
                                            {{$rateofreturn}} %
                                        </td>
                                    </tr>
                                    @endif
                                    
                            </table>
                            <br />
                            <table class="table table-bordered">
                                <tr>
                                    <td colspan="3"><strong>Other Financial Goals</strong></td>
                                </tr>
                                <tr>
                                    <td><strong>Goal Name</strong></td><td><strong>Amount Required</strong></td><td><strong>Period Remaining</strong></td>
                                </tr>
                                @php
                                $count = 0;
                                
                                foreach($special as $spec)
                                {
                                    echo("<tr><td>".$spec[$count]."</td><td>₹ ".custome_money_format($spec[$count+1])."</td><td>".$spec[$count+2]." Years</td></tr>");
                                    
                                }
                                @endphp
                            </table>
                            <br />
                            <table class="table table-bordered">
                                @if(isset($market))
                                <tr>
                                        <td>
                                            <strong> Current Market Value of Investments</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($market)}}
                                        </td>
                                    </tr>
                                    @endif
                                    
                                    @if(isset($lifeinsure))
                                <tr>
                                        <td>
                                            <strong> Current Life Insurance Cover + Accrued Bonus</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($lifeinsure)}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(isset($anualsavings))
                                <tr>
                                        <td>
                                            <strong> Current Annual Savings</strong>
                                        </td>
                                        <td>
                                        ₹ {{custome_money_format($anualsavings)}}
                                        </td>
                                    </tr>
                                    @endif
                                    @if(isset($assumedrate))
                                <tr>
                                        <td>
                                            <strong> Assumed Rate of Return on Investment</strong>
                                        </td>
                                        <td>
                                            {{$assumedrate}} %
                                        </td>
                                    </tr>
                                    @endif
                                    
                            </table>
                            <br />
                            <table class="table table-bordered">
                                <tr>
                                    <td> </td><td><strong>Shortfall / (Excess)</strong></td><td><strong>Value of Assets + Insurance Cover</strong></td><td><strong>Life Cover Required</strong></td>
                                </tr>
                            
                            @php
                            
                            $pv_total_need_year1 =0 ;
                            $pv_total_need_year5 =0 ;
                            $pv_total_need_year10 =0 ;
                            $earningLeft = $retire - $current;
                            if($retire > $current)
                                $annuityPeriod = $annuity-$retire+($current-$spouse);
                            else
                                $annuityPeriod = $annuity-$spouse;
                                if($discount == $anualincrement){
                                $pv_house_hold = $household * $earningLeft / (1+$discount/100);
                                }
                                else{
                                $pv_house_hold = $household * (1-pow((1+$anualincrement/100),$earningLeft)*pow((1+$discount/100),-$earningLeft))/($discount/100-$anualincrement/100);
                                }
                                
                                if($inflation == $rateofreturn){
                                $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                }
                                else{
                                $retirement_corpus = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                }
                                //dd(round($retirement_corpus));
                                $pv_retirement_corpus = round($retirement_corpus)/pow((1+$discount/100),$earningLeft);
                                $count = 0;
                                $tech = 0;
                                foreach($special as $spec)
                                {
                                    $goalAmount = $spec[$count+1]/pow((1+$discount/100),$spec[$count+2]);
                                    
                                    
                                    $pv_total_need_year1 += $goalAmount;
                                    $tech++;
                                }
                                
                                $pv_total_need_year1 += $pv_house_hold + $pv_retirement_corpus;
                                $currentAssetInsurance = $market + $lifeinsure;
                                
                                $valueOfAssets = $currentAssetInsurance;
                                $lifeCoverRequired = $pv_total_need_year1 - $valueOfAssets;
                            
                                echo("<tr><td><strong>Present Day</strong></td><td>₹ ".custome_money_format($pv_total_need_year1)."</td><td>₹ ".custome_money_format($valueOfAssets)."</td><td>₹ ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                
                                //FiveYears
                                
                                if($earningLeft >= 5){
                                if($discount == $anualincrement){
                                $pv_house_hold = $household * ($earningLeft-5)/(1+$discount/100);
                                }
                                else{
                                $pv_house_hold = $household * (1-pow((1+$anualincrement/100),($earningLeft-5))*pow((1+$discount/100),(-$earningLeft+5)))/($discount/100-$anualincrement/100);
                                }
                                }
                                else
                                $pv_house_hold = 0;
                                
                                
                                if($earningLeft >=5){
                                    $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                    }
                                else{
                                    $retirement_corpus = $anualretire * ($annuityPeriod +$earningLeft-5)/(1+$rateofreturn/100);
                                    }
                                    
                                    if($earningLeft >=5){
                                    if($inflation == $rateofreturn)
                                    {
                                        $retirement_corpus2 = 0;
                                    }
                                    else{
                                        $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                        }
                                    }
                                else{
                                    if($inflation == $rateofreturn)
                                    {
                                        $retirement_corpus2 = 0;
                                    }
                                    else{
                                    $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),($annuityPeriod+$earningLeft-5))*pow((1+$rateofreturn/100),(-$annuityPeriod-$earningLeft+5)))/($rateofreturn/100-$inflation/100);
                                    }
                                    }
                                    
                                    if($inflation != $rateofreturn)
                                    {
                                        $retirement_corpus = $retirement_corpus2;
                                    }
                                    
                                    if($earningLeft >=5)
                                        $pv_retirement_corpus = $retirement_corpus/pow((1+$discount/100),($earningLeft-5));
                                        else
                                        $pv_retirement_corpus = $retirement_corpus;
                                $count = 0;
                                
                                foreach($special as $spec)
                                {
                                    if($spec[$count+2] >= 5){
                                    
                                    $goalAmount = $spec[$count+1]/pow((1+$discount/100),($spec[$count+2]-5));
                                    $pv_total_need_year5 += $goalAmount;
                                    }
                                }
                                
                                $pv_total_need_year5 += $pv_house_hold + $pv_retirement_corpus;
                                
                                $valueOfOldInvest = $market*pow((1+$assumedrate/100),5);
                                // dd($assumedrate/100);
                                if($earningLeft >=5)
                                {
                                $newInvestment = $anualsavings*((pow(1+$assumedrate/100,5)-1)/($assumedrate/100));
                                }
                                else
                                {
                                $newInvestment = ($anualsavings*((pow(1+$assumedrate/100,$earningLeft)-1)/($assumedrate/100)))* pow(1+$assumedrate/100, (5-$earningLeft));
                                }
                                //dd(round($newInvestment));
                                $insuranceCover = $lifeinsure;
                                $currentAssetInsurance = $valueOfOldInvest + round($newInvestment) + $insuranceCover;
                                $valueOfAssets = $currentAssetInsurance;
                                
                                $lifeCoverRequired = $pv_total_need_year5 - $valueOfAssets;
                                
                                
                                echo("<tr><td><strong>After 5 Years</strong></td><td>₹ ".custome_money_format($pv_total_need_year5)."</td><td>₹ ".custome_money_format($valueOfAssets)."</td><td>₹ ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                //EndFive years
                                
                                //Ten years
                                if($earningLeft >= 10){
                                if($discount == $anualincrement){
                                $pv_house_hold = $household * ($earningLeft-10)/(1+$discount/100);
                                }
                                else{
                                $pv_house_hold = $household * (1-pow((1+$anualincrement/100),($earningLeft-10))*pow((1+$discount/100),(-$earningLeft+10)))/($discount/100-$anualincrement/100);
                                }
                                }
                                else
                                $pv_house_hold = 0;
                                
                                
                                if($earningLeft >=10)
                                    $retirement_corpus = $anualretire * $annuityPeriod/(1+$rateofreturn/100);
                                else
                                    $retirement_corpus = $anualretire * ($annuityPeriod +$earningLeft-10)/(1+$rateofreturn/100);
                                    
                                    
                                    if($earningLeft >=10){
                                    if($inflation == $rateofreturn)
                                    {
                                        $retirement_corpus2 = 0;
                                    }
                                    else{
                                        $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),$annuityPeriod)*pow((1+$rateofreturn/100),-$annuityPeriod))/($rateofreturn/100-$inflation/100);
                                        }
                                    }
                                else{
                                    if($inflation == $rateofreturn)
                                    {
                                        $retirement_corpus2 = 0;
                                    }
                                    else{
                                    $retirement_corpus2 = $anualretire * (1-pow((1+$inflation/100),($annuityPeriod+$earningLeft-10))*pow((1+$rateofreturn/100),(-$annuityPeriod-$earningLeft+10)))/($rateofreturn/100-$inflation/100);
                                    }
                                    }
                                    
                                    if($inflation != $rateofreturn)
                                    {
                                        $retirement_corpus = $retirement_corpus2;
                                    }
                                    
                                    if($earningLeft >=10)
                                        $pv_retirement_corpus = $retirement_corpus/pow((1+$discount/100),($earningLeft-10));
                                        else
                                        $pv_retirement_corpus = $retirement_corpus;
                                $count = 0;
                                
                                foreach($special as $spec)
                                {
                                    if($spec[$count+2] >= 10){
                                    
                                    $goalAmount = $spec[$count+1]/pow((1+$discount/100),($spec[$count+2]-10));
                                    $pv_total_need_year10 += $goalAmount;
                                    }
                                }
                                
                                $pv_total_need_year10 += $pv_house_hold + $pv_retirement_corpus;
                                
                                $valueOfOldInvest = $market*pow((1+$assumedrate/100),10);
                                if($earningLeft >=10)
                                {
                                $newInvestment = $anualsavings*((pow(1+$assumedrate/100,10)-1)/($assumedrate/100));
                                }
                                else
                                {
                                $newInvestment = ($anualsavings*((pow(1+$assumedrate/100,$earningLeft)-1)/($assumedrate/100)))* pow(1+$assumedrate/100, (10-$earningLeft));
                                }
                                $insuranceCover = $lifeinsure;
                                $currentAssetInsurance = $valueOfOldInvest + $newInvestment + $insuranceCover;
                                $valueOfAssets = $currentAssetInsurance;
                                
                                $lifeCoverRequired = $pv_total_need_year10 - $valueOfAssets;
                                
                                
                                echo("<tr><td><strong>After 10 Years</strong></td><td>₹ ".custome_money_format($pv_total_need_year10)."</td><td>₹ ".custome_money_format($valueOfAssets)."</td><td>₹ ".custome_money_format($lifeCoverRequired)."</td></tr>");
                                //End of tenyears
                            @endphp
                            </table>
                            @endif
                        </div>
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
                        <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center" style="padding:83px 0 20px 0;">
                        @if($edit_id)
                            <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @else
                            <a href="{{route('frontend.hlv_calculation_back')}}?action=back" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        @endif
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
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.hlv_calculation_pdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif
                        <a id="view_save_only" href="{{route('frontend.view_saved_files')}}" class="btn btn-primary btn-round view_save_only" style="display:none;">View saved files</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="btm-shape-prt">
            <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>
        </div>
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.hlv_calculation_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

@endsection
