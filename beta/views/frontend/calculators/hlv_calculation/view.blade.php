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
    @include('frontend.calculators.common.view_style')
@endsection
@section('content')

    <div class="banner">
        <div class="container">
            {{-- <div class="row">
                <div class="col-md-12 text-center">
                    <h2 class="page-title">PREMIUM CALCULATORS</h2>
                </div>
            </div> --}}
        </div>
    </div>
    
    <section  class="main-sec styleApril">
        <div id="content" class="container">
            <div class="row">
                <div class="col-md-12 offset-md-0 text-center">
                    
                    
                    <div class="outputTableHolder">
                        <h1 class="midheading">Human Life Value Calculation @if(isset($clientname) && !empty($clientname)) <br> For {{$clientname?$clientname:''}}  @else  @endif</h1>
                        
                            @if($formType == 1)
                            @php 
                            $net = $anual - $personal;
                            if($expected == $discount)
                                $humanLifeValue = $net * ($retire-$current) / (1+$discount/100);
                            else
                                $humanLifeValue =  $net * (1-(pow(1+$expected/100,($retire-$current))*pow(1+$discount/100 , -($retire-$current))))/(($discount/100)-($expected/100));  
                                   
                            @endphp
                        <div class="roundBorderHolder">
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
                        </div>
                            <h1 class="midheading">Human Life Value</h1>

                            <h5 class="outputSingleBox">
                                    ₹ {{custome_money_format($humanLifeValue)}}
                            </h5>
                            
                            
                            <p class="text-center">*Income Replacement Method</p>

                            @else
                            <div class="roundBorderHolder">
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
                            <div class="roundBorderHolder">
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
                            </div>
                            <div class="roundBorderHolder">
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
                            </div>
                            <div class="roundBorderHolder">
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
                            </div>
                            <div class="roundBorderHolder">
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
                            </div>
                            @endif
                            
                        {{-- comment or note section here --}}
                        @include('frontend.calculators.common.comment_output')
                        <p class="text-left">*Returns are not guaranteed. The above is for illustration purpose only.  Report Date : {{date('d/m/Y')}}</p>

                        @include('frontend.calculators.suggested.output')
                    </div>
                    <div class="text-center viewBelowBtn">
                        <a href="javascript:history.back()" class="btn btn-primary btn-round"><i class="fa fa-angle-left"></i> Back</a>
                        
                        @if($permission['is_download'])
                            @if($permission['is_cover'])
                                <a href="javascript:void(0);" onclick="openModal();" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @else
                                <a href="{{route('frontend.lumsumInvestmentRequiredForTargetFutureValuePdf')}}" target="_blank" id="cmd" class="btn btn-primary btn-round btn-solid">Download / Print </a>
                            @endif
                            
                        @else
                            <a href="javascript:void(0);" class="btn btn-primary btn-round btn-solid" onclick="openDownloadPermissionModal();">Download / Print </a>
                        @endif

                        <a href="javascript:void(0)" class="btn btn-primary btn-round" data-toggle="modal" data-target="#mergeSalesPresentersOutput" style="width: 320px;">Save & Merge with Sales Presenters</a>
                    </div>
                </div>
            </div>
        </div>
        <!--<div class="btm-shape-prt">-->
        <!--    <img class="img-fluid" src="{{asset('')}}/f/images/shape2.png" alt=""/>-->
        <!--</div>-->
    </section>
    <script type="text/javascript">
        var base_url = "{{route('frontend.hlv_calculation_pdf')}}";
    </script>
    @include('frontend.calculators.modal')
    @include('frontend.calculators.common.cover')

    <div class="modal fade" id="mergeSalesPresentersOutput" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">SALES PRESENTER SOFTCOPY SAVED LIST</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form target="_blank" action="{{route('frontend.hlv_calculation_merge_download')}}" method="get">
                        <input type="hidden" name="save_file_id" value="{{$id}}">
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th>Date</th>
                                <th>List Name</th>
                                <th>Valid Till</th>
                            </tr>
                            @if(isset($savelists) && count($savelists)>0)
                                @foreach($savelists as $svlist)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="saved_sp_list_id[]" value="{{$svlist['id']}}">
                                        </td>
                                        <td>{{$svlist['created_at']->format('d/m/Y - h:i A')}}</td>
                                        <td>{{$svlist['title']}} ({{$svlist->softcopies->count()}} images)</td>
                                        <td>{{date('d/m/Y - h:i A',strtotime($svlist['validate_at']))}}</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">SUGGESTED PRESENTATION LIST</h5>
                        <table>
                            <tbody>
                            <tr>
                                <th></th>
                                <th style="text-align: left">List Name</th>
                            </tr>
                            @if(isset($suggestedlists) && count($suggestedlists)>0)
                                @foreach($suggestedlists as $sglist)
                                    <tr>
                                        <td>
                                            <input type="radio" name="saved_list_id" value="{{$sglist['id']}}">
                                        </td>
                                        <td style="text-align: left" >{{$sglist['title']}} ({{$sglist->softcopies->count()}} images)</td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <h5 class="modal-title">WHERE YOU WANT TO MERGE?</h5>
                        <table>
                            <tbody>
                            <tr>
                                <td style="text-align: left">
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="before" name="mergeposition">Before
                                        </label>
                                    </div>
                                    <div class="form-check-inline">
                                        <label class="form-check-label">
                                            <input type="radio" class="form-check-input" value="after" name="mergeposition" checked>After
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        @if($permission['is_cover'])
                            <h5 class="modal-title">&nbsp;</h5>
                            <table>
                                <tbody>
                                <tr>
                                    <td style="text-align: left">
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="1" name="is_cover" onchange="changeCover(1);">With Cover
                                            </label>
                                        </div>
                                        <div class="form-check-inline">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" value="0" name="is_cover"  onchange="changeCover(0);" checked>Without Cover
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        @endif
                        <h5 class="modal-title">&nbsp;</h5>
                        <div id="pdf_title_line_id" style="display: none;">
                            <div class="form-group">
                                <input type="text" name="pdf_title_line1" class="form-control" id="pdf_title_line1" placeholder="PDF Title Line 1" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="pdf_title_line2" class="form-control" id="pdf_title_line2" placeholder="PDF Title Line 2" value="" maxlength="22">
                            </div>
                            <div class="form-group">
                                <input type="text" name="client_name" class="form-control" id="client_name" placeholder="Client Name" value="" maxlength="22">
                            </div>
                        </div>
                        <p></p>
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">Back</button>
                        <button type="submit" class="btn btn-primary btn-round" >Merge & Download</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>
        </div>
    </div>
@endsection

